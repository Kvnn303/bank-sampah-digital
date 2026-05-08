<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tabungan;
use App\Models\Nasabah;
use App\Models\JenisSampah;
use App\Services\AuditLogService;
use App\Services\NotificationService;
use Illuminate\Http\Request;

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

        // Filter by tanggal
        if ($request->dari_tanggal && $request->sampai_tanggal) {
            $query->whereBetween('tanggal_setor', [
                $request->dari_tanggal,
                $request->sampai_tanggal
            ]);
        }

        $tabungan       = $query->paginate(10);
        $totalKg        = Tabungan::sum('berat_kg');
        $totalNilai     = Tabungan::sum('nilai_rupiah');
        $bulanIniKg     = Tabungan::whereMonth('tanggal_setor', now()->month)
                            ->whereYear('tanggal_setor', now()->year)
                            ->sum('berat_kg');
        $bulanIniNilai  = Tabungan::whereMonth('tanggal_setor', now()->month)
                            ->whereYear('tanggal_setor', now()->year)
                            ->sum('nilai_rupiah');

        $nasabahList    = Nasabah::where('status_akun', '!=', 'nonaktif')
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
        $nasabahList     = Nasabah::where('status_akun', '!=', 'nonaktif')
                            ->orderBy('nama_lengkap')->get();
        $jenisSampahList = JenisSampah::where('is_active', true)
                            ->orderBy('nama')->get();

        return view('admin.tabungan.create', compact('nasabahList', 'jenisSampahList'));
    }

    // Simpan tabungan baru
    public function store(Request $request)
    {
        $request->validate([
            'nasabah_id'      => 'required|exists:nasabah,id',
            'jenis_sampah_id' => 'required|exists:jenis_sampah,id',
            'berat_kg'        => 'required|numeric|min:0.1',
            'tanggal_setor'   => 'required|date',
            'catatan'         => 'nullable|string',
        ], [
            'nasabah_id.required'      => 'Nasabah wajib dipilih',
            'jenis_sampah_id.required' => 'Jenis sampah wajib dipilih',
            'berat_kg.required'        => 'Berat wajib diisi',
            'berat_kg.min'             => 'Berat minimal 0.1 kg',
            'tanggal_setor.required'   => 'Tanggal setor wajib diisi',
        ]);

        // Ambil harga sampah saat ini
        $jenisSampah = JenisSampah::findOrFail($request->jenis_sampah_id);
        $nilaiRupiah = $request->berat_kg * $jenisSampah->harga_per_kg;

        $tabungan = Tabungan::create([
            'nasabah_id'            => $request->nasabah_id,
            'admin_id'              => auth()->id(),
            'jenis_sampah_id'       => $request->jenis_sampah_id,
            'berat_kg'              => $request->berat_kg,
            'harga_per_kg_saat_itu' => $jenisSampah->harga_per_kg,
            'nilai_rupiah'          => $nilaiRupiah,
            'tanggal_setor'         => $request->tanggal_setor,
            'catatan'               => $request->catatan,
        ]);

        // Update status nasabah jadi active
        $nasabah = Nasabah::findOrFail($request->nasabah_id);
        if ($nasabah->status_akun === 'verified') {
            $nasabah->update(['status_akun' => 'active']);
        }

        AuditLogService::log(
            action: 'TABUNGAN_INPUT',
            module: 'Tabungan',
            description: "Admin input tabungan {$request->berat_kg}kg {$jenisSampah->nama} untuk {$nasabah->nama_lengkap} senilai Rp{$nilaiRupiah}",
        );

        // Notif ke admin + nasabah
        $nominal = 'Rp' . number_format($nilaiRupiah, 0, ',', '.');
        $tanggal = $tabungan->tanggal_setor->format('d M Y');

        NotificationService::tabunganMasuk($nasabah->nama_lengkap, $nominal, $jenisSampah->nama);
        NotificationService::tabunganMasukNasabah($nasabah->user_id, $nominal, $tanggal);

        return redirect()->route('admin.tabungan.index')
                        ->with('success', "Tabungan berhasil diinput! Nilai: Rp " . number_format($nilaiRupiah));
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
        $tabungan = Tabungan::findOrFail($id);

        $request->validate([
            'nasabah_id'      => 'required|exists:nasabah,id',
            'jenis_sampah_id' => 'required|exists:jenis_sampah,id',
            'berat_kg'        => 'required|numeric|min:0.1',
            'tanggal_setor'   => 'required|date',
            'catatan'         => 'nullable|string',
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

        return redirect()->route('admin.tabungan.index')
                        ->with('success', 'Data tabungan berhasil diperbarui!');
    }

    // Hapus tabungan
    public function destroy($id)
    {
        $tabungan = Tabungan::with(['nasabah', 'jenisSampah'])->findOrFail($id);

        AuditLogService::log(
            action: 'TABUNGAN_HAPUS',
            module: 'Tabungan',
            description: "Admin menghapus tabungan id {$id} nasabah {$tabungan->nasabah->nama_lengkap}",
        );

        $tabungan->delete();

        return redirect()->route('admin.tabungan.index')
                        ->with('success', 'Data tabungan berhasil dihapus!');
    }
}
