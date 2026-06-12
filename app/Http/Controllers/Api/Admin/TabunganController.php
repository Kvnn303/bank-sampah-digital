<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tabungan;
use App\Models\Nasabah;
use App\Models\Penarikan;
use App\Models\JenisSampah;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class TabunganController extends Controller
{
    // GET semua tabungan
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

        // Filter by tanggal
        if ($request->dari_tanggal && $request->sampai_tanggal) {
            $query->whereBetween('tanggal_setor', [
                $request->dari_tanggal,
                $request->sampai_tanggal
            ]);
        }

        // Filter by bulan & tahun
        if ($request->bulan && $request->tahun) {
            $query->whereMonth('tanggal_setor', $request->bulan)
                  ->whereYear('tanggal_setor', $request->tahun);
        }

        $tabungan = $query->paginate(10);

        return response()->json($tabungan);
    }

    // POST input tabungan sampah
    public function store(Request $request)
    {
        $request->validate([
            'nasabah_id'      => 'required|exists:nasabah,id',
            'jenis_sampah_id' => 'required|exists:jenis_sampah,id',
            'berat_kg'        => 'required|numeric|min:0.1',
            'tanggal_setor'   => 'required|date',
            'catatan'         => 'nullable|string',
        ]);

        // Ambil harga sampah saat ini
        $jenisSampah = JenisSampah::findOrFail($request->jenis_sampah_id);

        // Hitung nilai rupiah otomatis
        $nilaiRupiah = $request->berat_kg * $jenisSampah->harga_per_kg;

        $tabungan = Tabungan::create([
            'nasabah_id'            => $request->nasabah_id,
            'admin_id'              => $request->user()->id,
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
            description: "Admin input tabungan {$request->berat_kg}kg {$jenisSampah->nama} untuk nasabah {$nasabah->nama_lengkap} senilai Rp{$nilaiRupiah}",
            newData: $tabungan->toArray()
        );

        // Notifikasi (admin + nasabah + mobile) otomatis dikirim oleh TabunganObserver
        // pada event 'created' — lihat app/Observers/TabunganObserver.php

        return response()->json([
            'message'      => 'Tabungan berhasil diinput',
            'tabungan'     => $tabungan->load(['nasabah', 'jenisSampah']),
            'nilai_rupiah' => $nilaiRupiah,
        ], 201);
    }

    // GET statistik tabungan per nasabah
    public function statistikNasabah(Request $request, $nasabahId)
    {
        $nasabah = Nasabah::findOrFail($nasabahId);

        // Per hari ini
        $hariIni = Tabungan::where('nasabah_id', $nasabahId)
                    ->whereDate('tanggal_setor', today())
                    ->sum('berat_kg');

        // Per bulan ini
        $bulanIni = Tabungan::where('nasabah_id', $nasabahId)
                    ->whereMonth('tanggal_setor', now()->month)
                    ->whereYear('tanggal_setor', now()->year)
                    ->sum('berat_kg');

        // Per tahun ini
        $tahunIni = Tabungan::where('nasabah_id', $nasabahId)
                    ->whereYear('tanggal_setor', now()->year)
                    ->sum('berat_kg');

        // Per jenis sampah
        $perJenis = Tabungan::where('nasabah_id', $nasabahId)
                    ->with('jenisSampah')
                    ->selectRaw('jenis_sampah_id, SUM(berat_kg) as total_kg, SUM(nilai_rupiah) as total_rupiah')
                    ->groupBy('jenis_sampah_id')
                    ->get();

        // Hitung saldo manual
        $totalTabungan = Tabungan::where('nasabah_id', $nasabahId)->sum('nilai_rupiah');
        $totalPenarikan = Penarikan::where('nasabah_id', $nasabahId)
                            ->where('status', 'selesai')
                            ->sum('nominal');
        $saldoAktif = $totalTabungan - $totalPenarikan;

        return response()->json([
            'nasabah'    => $nasabah->nama_lengkap,
            'saldo'      => $saldoAktif,
            'statistik'  => [
                'hari_ini'  => $hariIni . ' kg',
                'bulan_ini' => $bulanIni . ' kg',
                'tahun_ini' => $tahunIni . ' kg',
                'per_jenis' => $perJenis,
            ]
        ]);
    }

    // DELETE tabungan
    public function destroy(Request $request, $id)
    {
        try {
            $tabungan = Tabungan::with(['nasabah', 'jenisSampah'])->findOrFail($id);

            AuditLogService::log(
                action: 'TABUNGAN_HAPUS',
                module: 'Tabungan',
                description: "Admin menghapus tabungan id {$id}",
                oldData: $tabungan->toArray()
            );

            $tabungan->delete();

            return response()->json(['message' => 'Data tabungan berhasil dihapus']);
        } catch (\App\Exceptions\SaldoMinusException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tabungan tidak ditemukan.',
            ], 404);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data: ' . $e->getMessage(),
            ], 500);
        }
    }
}
