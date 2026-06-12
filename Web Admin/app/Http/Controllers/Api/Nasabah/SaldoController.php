<?php

namespace App\Http\Controllers\Api\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\Tabungan;
use App\Models\Penarikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaldoController extends Controller
{
    // GET saldo & info nasabah
    public function index(Request $request)
    {
        $user    = $request->user();
        $nasabah = $user->nasabah;

        if (!$nasabah) {
            return response()->json([
                'message' => 'Data nasabah tidak ditemukan'
            ], 404);
        }

        // Hitung Saldo Real-time langsung dari DB
        // Penarikan yang mengurangi saldo: HANYA yang sudah 'selesai'
        $totalTabungan = (float) Tabungan::where('nasabah_id', $nasabah->id)->sum('nilai_rupiah');
        $totalPenarikan = (float) Penarikan::where('nasabah_id', $nasabah->id)
                            ->where('status', 'selesai')
                            ->sum('nominal');

        $saldoAktif = $totalTabungan - $totalPenarikan;
        $totalSampah = (float) Tabungan::where('nasabah_id', $nasabah->id)->sum('berat_kg');

        return response()->json([
            'nama'              => $nasabah->nama_lengkap,
            'status_akun'       => $nasabah->status_akun,
            'saldo'             => (float) $saldoAktif,
            'total_sampah'      => $totalSampah,
            'tanggal_bergabung' => $nasabah->tanggal_bergabung,
        ]);
    }

    // GET statistik sampah nasabah
    public function statistik(Request $request)
    {
        $nasabah = $request->user()->nasabah;

        if (!$nasabah) {
            return response()->json([
                'message' => 'Data nasabah tidak ditemukan'
            ], 404);
        }

        // Per hari ini
        $hariIni = Tabungan::where('nasabah_id', $nasabah->id)
                    ->whereDate('tanggal_setor', today())
                    ->sum('berat_kg');

        // Per bulan ini
        $bulanIni = Tabungan::where('nasabah_id', $nasabah->id)
                    ->whereMonth('tanggal_setor', now()->month)
                    ->whereYear('tanggal_setor', now()->year)
                    ->sum('berat_kg');

        // Per tahun ini
        $tahunIni = Tabungan::where('nasabah_id', $nasabah->id)
                    ->whereYear('tanggal_setor', now()->year)
                    ->sum('berat_kg');

        // Per jenis sampah
        $perJenis = Tabungan::where('nasabah_id', $nasabah->id)
                    ->with('jenisSampah:id,nama,kategori')
                    ->selectRaw('jenis_sampah_id, SUM(berat_kg) as total_kg, SUM(nilai_rupiah) as total_rupiah')
                    ->groupBy('jenis_sampah_id')
                    ->orderByDesc('total_kg')
                    ->get();

        // Hitung ulang saldo untuk response statistik
        $totalTabungan = (float) Tabungan::where('nasabah_id', $nasabah->id)->sum('nilai_rupiah');
        $totalPenarikan = (float) Penarikan::where('nasabah_id', $nasabah->id)
                            ->where('status', 'selesai')
                            ->sum('nominal');
        $saldoAktif = $totalTabungan - $totalPenarikan;

        return response()->json([
            'nasabah'    => $nasabah->nama_lengkap,
            'saldo'      => (float) $saldoAktif,
            'statistik'  => [
                'hari_ini'  => (float) $hariIni . ' kg',
                'bulan_ini' => (float) $bulanIni . ' kg',
                'tahun_ini' => (float) $tahunIni . ' kg',
                'per_jenis' => $perJenis,
            ]
        ]);
    }
}
