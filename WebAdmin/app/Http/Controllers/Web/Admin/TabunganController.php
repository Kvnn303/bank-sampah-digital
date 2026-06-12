<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tabungan;
use App\Models\Nasabah;
use App\Models\Penarikan;
use App\Models\JenisSampah;
use App\Services\AuditLogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
// TAMBAHAN: Import DomPDF Facade
use Barryvdh\DomPDF\Facade\Pdf;

class TabunganController extends Controller
{
    // Tampilkan semua tabungan (Index)
    public function index(Request $request)
    {
        $query = Tabungan::with(['nasabah', 'jenisSampah', 'admin'])
                    ->orderByDesc('tanggal_setor');

        // Filter by nasabah
        if ($request->nasabah_id) {
            $query->where('nasabah_id', $request->nasabah_id);
        }

        // Filter by jenis sampah
        if ($request->jenis_sampah_id) {
            $query->where('jenis_sampah_id', $request->jenis_sampah_id);
        }

        // Filter by bulan & tahun
        if ($request->bulan && $request->tahun) {
            $query->whereMonth('tanggal_setor', $request->bulan)
                  ->whereYear('tanggal_setor', $request->tahun);
        }

        // Filter by tanggal range
        if ($request->dari_tanggal) {
            $query->whereDate('tanggal_setor', '>=', $request->dari_tanggal);
        }
        if ($request->sampai_tanggal) {
            $query->whereDate('tanggal_setor', '<=', $request->sampai_tanggal);
        }

        $tabungan       = $query->paginate(10);
        $totalKg       = Tabungan::sum('berat_kg');
        $totalNilai    = Tabungan::sum('nilai_rupiah');
        $bulanIniKg    = Tabungan::whereMonth('tanggal_setor', now()->month)
                            ->whereYear('tanggal_setor', now()->year)
                            ->sum('berat_kg');
        $bulanIniNilai = Tabungan::whereMonth('tanggal_setor', now()->month)
                            ->whereYear('tanggal_setor', now()->year)
                            ->sum('nilai_rupiah');

        $nasabahList     = Nasabah::where('status_akun', '!=', 'nonaktif')
                            ->orderBy('nama_lengkap')->get();
        $jenisSampahList = JenisSampah::where('is_active', true)
                            ->orderBy('nama')->get();

        return view('admin.tabungan.index', compact(
            'tabungan',
            'totalKg',
            'totalNilai',
            'bulanIniKg',
            'bulanIniNilai',
            'nasabahList',
            'jenisSampahList'
        ));
    }

    // Form input tabungan
    public function create()
    {
        $tabunganSum = Tabungan::selectRaw('nasabah_id, SUM(nilai_rupiah) as total')
                            ->groupBy('nasabah_id')
                            ->pluck('total', 'nasabah_id');
        $penarikanSum = Penarikan::selectRaw('nasabah_id, SUM(nominal) as total')
                            ->where('status', 'selesai')
                            ->groupBy('nasabah_id')
                            ->pluck('total', 'nasabah_id');

        $nasabahList = Nasabah::where('status_akun', '!=', 'nonaktif')
                            ->orderBy('nama_lengkap')
                            ->get()->map(function ($n) use ($tabunganSum, $penarikanSum) {
                                $n->saldo_aktif = (float) ($tabunganSum[$n->id] ?? 0)
                                              - (float) ($penarikanSum[$n->id] ?? 0);
                                return $n;
                            });
        $jenisSampahList = JenisSampah::where('is_active', true)
                            ->orderBy('nama')->get();

        return view('admin.tabungan.create', compact('nasabahList', 'jenisSampahList'));
    }

    // Simpan tabungan baru (multiple items support)
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nasabah_id'    => 'required|exists:nasabah,id',
                'tanggal_setor' => 'required|date|after_or_equal:today',
                'catatan'       => 'nullable|string|max:500',
                'items'         => 'required|array|min:1',
                'items.*.jenis_sampah_id' => 'required|exists:jenis_sampah,id',
                'items.*.berat_kg'        => 'required|numeric|min:0.1|max:10000',
                'items.*.nilai'           => 'required|numeric|min:0',
            ], [
                'nasabah_id.required'          => 'Nasabah wajib dipilih',
                'tanggal_setor.after_or_equal' => 'Tanggal setor tidak boleh mundur dari hari ini',
                'items.required'              => 'Minimal harus ada 1 jenis sampah',
                'items.*.jenis_sampah_id.required' => 'Jenis sampah wajib dipilih',
                'items.*.berat_kg.required'  => 'Berat wajib diisi',
                'items.*.berat_kg.min'       => 'Berat minimal 0.1 kg',
                'items.*.berat_kg.max'       => 'Berat maksimal 10000 kg',
            ]);

            $nasabah = Nasabah::findOrFail($request->nasabah_id);
            $totalNilaiRupiah = 0;
            $createdCount = 0;

            // Simpan setiap item sebagai record tabungan terpisah
            foreach ($request->items as $item) {
                if (empty($item['jenis_sampah_id']) || empty($item['berat_kg'])) {
                    continue;
                }

                $jenisSampah = JenisSampah::findOrFail($item['jenis_sampah_id']);
                $nilaiRupiah = floatval($item['nilai']);

                Tabungan::create([
                    'nasabah_id'            => $request->nasabah_id,
                    'admin_id'              => auth()->id(),
                    'jenis_sampah_id'       => $item['jenis_sampah_id'],
                    'berat_kg'              => $item['berat_kg'],
                    'harga_per_kg_saat_itu' => $jenisSampah->harga_per_kg,
                    'nilai_rupiah'          => $nilaiRupiah,
                    'tanggal_setor'         => $request->tanggal_setor,
                    'catatan'               => $request->catatan,
                ]);

                $totalNilaiRupiah += $nilaiRupiah;
                $createdCount++;
            }

            if ($createdCount === 0) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak ada data setoran yang valid.'
                    ], 422);
                }
                return redirect()->back()->withInput()->with('error', 'Tidak ada data setoran yang valid.');
            }

            // Update status nasabah jadi active
            if ($nasabah->status_akun === 'verified') {
                $nasabah->update(['status_akun' => 'active']);
            }

            AuditLogService::log(
                action: 'TABUNGAN_INPUT',
                module: 'Tabungan',
                description: "Admin input {$createdCount} tabungan untuk {$nasabah->nama_lengkap} total Rp " . number_format($totalNilaiRupiah),
            );

            \Illuminate\Support\Facades\Cache::forget("nasabah:{$nasabah->id}:saldo");
            \Illuminate\Support\Facades\Cache::forget("nasabah:{$nasabah->id}:statistik");

            // Notifikasi (admin + nasabah + mobile) otomatis dikirim oleh TabunganObserver
            // pada event 'created' untuk setiap record — lihat app/Observers/TabunganObserver.php

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "{$createdCount} setoran berhasil diinput! Total: Rp " . number_format($totalNilaiRupiah),
                ]);
            }

            return redirect()->route('admin.tabungan.index')
                            ->with('success', "{$createdCount} setoran berhasil diinput! Total: Rp " . number_format($totalNilaiRupiah));

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                $firstError = collect($e->errors())->flatten()->first();
                return response()->json([
                    'success' => false,
                    'message' => $firstError ?? 'Validasi gagal',
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan tabungan: ' . $e->getMessage() . '\n' . $e->getTraceAsString());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan pada server. Silakan coba lagi.'
                ], 500);
            }

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan pada server.');
        }
    }

    public function show($id)
    {
        $tabungan = Tabungan::with(['nasabah', 'jenisSampah', 'admin'])->findOrFail($id);

        return view('admin.tabungan.show', compact('tabungan'));
    }

    // Form edit tabungan
    public function edit($id)
    {
        $tabungan        = Tabungan::findOrFail($id);
        $nasabahList     = Nasabah::where('status_akun', '!=', 'nonaktif')
                            ->orderBy('nama_lengkap')->get();
        $jenisSampahList = JenisSampah::where('is_active', true)
                            ->orderBy('nama')->get();

        return view('admin.tabungan.edit', compact('tabungan', 'nasabahList', 'jenisSampahList'));
    }

    // Update tabungan
    public function update(Request $request, $id)
    {
        try {
            $tabungan = Tabungan::findOrFail($id);
            $nasabahId = $tabungan->nasabah_id;

            $request->validate([
                'nasabah_id'      => 'required|exists:nasabah,id',
                'jenis_sampah_id' => 'required|exists:jenis_sampah,id',
                'berat_kg'        => 'required|numeric|min:0.1|max:10000',
                'tanggal_setor'   => 'required|date',
                'catatan'         => 'nullable|string|max:500',
            ]);

            // Ambil harga sampah saat ini
            $jenisSampah = JenisSampah::findOrFail($request->jenis_sampah_id);
            $nilaiRupiah = $request->berat_kg * $jenisSampah->harga_per_kg;

            $tabungan->update([
                'nasabah_id'            => $request->nasabah_id,
                'jenis_sampah_id'       => $request->jenis_sampah_id,
                'berat_kg'              => $request->berat_kg,
                'harga_per_kg_saat_itu' => $jenisSampah->harga_per_kg,
                'nilai_rupiah'          => $nilaiRupiah,
                'tanggal_setor'         => $request->tanggal_setor,
                'catatan'               => $request->catatan,
            ]);

            AuditLogService::log(
                action: 'TABUNGAN_EDIT',
                module: 'Tabungan',
                description: "Admin update tabungan ID {$id} (Nasabah: {$tabungan->nasabah->nama_lengkap})",
            );

            \Illuminate\Support\Facades\Cache::forget("nasabah:{$nasabahId}:saldo");
            \Illuminate\Support\Facades\Cache::forget("nasabah:{$nasabahId}:statistik");
            if ($request->nasabah_id != $nasabahId) {
                \Illuminate\Support\Facades\Cache::forget("nasabah:{$request->nasabah_id}:saldo");
                \Illuminate\Support\Facades\Cache::forget("nasabah:{$request->nasabah_id}:statistik");
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Data tabungan berhasil diperbarui!'
                ]);
            }

            return redirect()->route('admin.tabungan.index')
                            ->with('success', 'Data tabungan berhasil diperbarui!');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                $firstError = collect($e->errors())->flatten()->first();
                return response()->json([
                    'success' => false,
                    'message' => $firstError ?? 'Validasi gagal'
                ], 422);
            }
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error update tabungan: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan pada server.'
                ], 500);
            }

            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan pada server.');
        }
    }

    // Hapus tabungan
    public function destroy(Request $request, $id)
    {
        try {
            $tabungan = Tabungan::with(['nasabah', 'jenisSampah'])->findOrFail($id);
            $namaNasabah = $tabungan->nasabah->nama_lengkap ?? 'Unknown';
            $nilai = number_format($tabungan->nilai_rupiah, 0, ',', '.');
            $nasabahId = $tabungan->nasabah_id;

            AuditLogService::log(
                action: 'TABUNGAN_HAPUS',
                module: 'Tabungan',
                description: "Admin menghapus tabungan id {$id} nasabah {$namaNasabah}",
            );

            $tabungan->delete();

            \Illuminate\Support\Facades\Cache::forget("nasabah:{$nasabahId}:saldo");
            \Illuminate\Support\Facades\Cache::forget("nasabah:{$nasabahId}:statistik");

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Setoran {$namaNasabah} (Rp {$nilai}) berhasil dihapus!"
                ]);
            }

            return redirect()->route('admin.tabungan.index')
                            ->with('success', "Data setoran berhasil dihapus!");

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data setoran tidak ditemukan.'
                ], 404);
            }
            return redirect()->route('admin.tabungan.index')
                            ->with('error', 'Data setoran tidak ditemukan.');
        } catch (\App\Exceptions\SaldoMinusException $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error hapus tabungan: ' . $e->getMessage());

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus data.'
                ], 500);
            }

            return redirect()->route('admin.tabungan.index')
                            ->with('error', 'Terjadi kesalahan saat menghapus data.');
        }
    }

    // TAMBAHAN: Fungsi untuk download struk PDF
    public function downloadPdf($id)
    {
        try {
            $tabungan = Tabungan::with(['nasabah', 'jenisSampah', 'admin'])->findOrFail($id);

            // Pastikan view ini ada (resources/views/admin/tabungan/struk-pdf.blade.php)
            $pdf = Pdf::loadView('admin.tabungan.struk-pdf', compact('tabungan'));

            // Set judul dokumen
            $filename = 'Struk_Setoran_' . ($tabungan->nasabah->nama_lengkap ?? 'Unknown') . '_' . $tabungan->tanggal_setor->format('Ymd') . '.pdf';

            // Return file untuk didownload/dilihat di browser
            return $pdf->stream($filename);

        } catch (\Exception $e) {
            Log::error('Error cetak PDF: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal mencetak struk PDF: ' . $e->getMessage());
        }
    }
}