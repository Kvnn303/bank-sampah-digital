<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tabungan;
use App\Models\Penarikan;
use App\Models\Nasabah;
use App\Models\JenisSampah;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    // GET dashboard statistik utama
    public function dashboard()
    {
        // Total nasabah
        $totalNasabah     = Nasabah::count();
        $nasabahPending   = Nasabah::where('status_akun', 'pending')->count();
        $nasabahVerified  = Nasabah::where('status_akun', 'verified')->count();
        $nasabahActive    = Nasabah::where('status_akun', 'active')->count();

        // Total sampah
        $totalSampahKg    = Tabungan::sum('berat_kg');
        $totalNilai       = Tabungan::sum('nilai_rupiah');

        // Penarikan
        $penarikanPending = Penarikan::where('status', 'pending')->count();
        $penarikanSelesai = Penarikan::where('status', 'selesai')->sum('nominal');

        // Sampah bulan ini
        $sampahBulanIni   = Tabungan::whereMonth('tanggal_setor', now()->month)
                                ->whereYear('tanggal_setor', now()->year)
                                ->sum('berat_kg');

        // Nilai bulan ini
        $nilaiBulanIni    = Tabungan::whereMonth('tanggal_setor', now()->month)
                                ->whereYear('tanggal_setor', now()->year)
                                ->sum('nilai_rupiah');

        return response()->json([
            'nasabah' => [
                'total'    => $totalNasabah,
                'pending'  => $nasabahPending,
                'verified' => $nasabahVerified,
                'active'   => $nasabahActive,
            ],
            'sampah' => [
                'total_kg'       => $totalSampahKg,
                'total_nilai'    => $totalNilai,
                'bulan_ini_kg'   => $sampahBulanIni,
                'bulan_ini_nilai'=> $nilaiBulanIni,
            ],
            'penarikan' => [
                'pending'          => $penarikanPending,
                'total_dicairkan'  => $penarikanSelesai,
            ],
        ]);
    }

    // GET grafik sampah per bulan
    public function grafikSampahPerBulan(Request $request)
    {
        $tahun = $request->tahun ?? now()->year;

        $data = Tabungan::whereYear('tanggal_setor', $tahun)
                    ->selectRaw('MONTH(tanggal_setor) as bulan, SUM(berat_kg) as total_kg, SUM(nilai_rupiah) as total_nilai')
                    ->groupBy('bulan')
                    ->orderBy('bulan')
                    ->get();

        // Format untuk Chart.js
        $bulanLabel = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $totalKg    = array_fill(0, 12, 0);
        $totalNilai = array_fill(0, 12, 0);

        foreach ($data as $item) {
            $totalKg[$item->bulan - 1]    = $item->total_kg;
            $totalNilai[$item->bulan - 1] = $item->total_nilai;
        }

        return response()->json([
            'tahun'       => $tahun,
            'labels'      => $bulanLabel,
            'total_kg'    => $totalKg,
            'total_nilai' => $totalNilai,
        ]);
    }

    // GET grafik jenis sampah terbanyak
    public function grafikJenisSampah()
    {
        $data = Tabungan::with('jenisSampah')
                    ->selectRaw('jenis_sampah_id, SUM(berat_kg) as total_kg')
                    ->groupBy('jenis_sampah_id')
                    ->orderByDesc('total_kg')
                    ->get();

        $labels   = $data->map(fn($d) => $d->jenisSampah->nama ?? 'Unknown');
        $totalKg  = $data->map(fn($d) => $d->total_kg);

        return response()->json([
            'labels'   => $labels,
            'total_kg' => $totalKg,
        ]);
    }

    // GET grafik nasabah baru per bulan
    public function grafikNasabahBaru(Request $request)
    {
        $tahun = $request->tahun ?? now()->year;

        $data = Nasabah::whereYear('created_at', $tahun)
                    ->selectRaw('MONTH(created_at) as bulan, COUNT(*) as total')
                    ->groupBy('bulan')
                    ->orderBy('bulan')
                    ->get();

        $bulanLabel = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
        $totalBaru  = array_fill(0, 12, 0);

        foreach ($data as $item) {
            $totalBaru[$item->bulan - 1] = $item->total;
        }

        return response()->json([
            'tahun'      => $tahun,
            'labels'     => $bulanLabel,
            'total_baru' => $totalBaru,
        ]);
    }

    // GET laporan transaksi
    public function laporanTransaksi(Request $request)
    {
        $request->validate([
            'dari_tanggal'   => 'required|date',
            'sampai_tanggal' => 'required|date',
        ]);

        $tabungan = Tabungan::with(['nasabah', 'jenisSampah', 'admin'])
                        ->whereBetween('tanggal_setor', [
                            $request->dari_tanggal,
                            $request->sampai_tanggal
                        ])
                        ->orderByDesc('tanggal_setor')
                        ->get();

        $penarikan = Penarikan::with(['nasabah', 'diprosesoleh'])
                        ->whereBetween('created_at', [
                            $request->dari_tanggal,
                            $request->sampai_tanggal
                        ])
                        ->orderByDesc('created_at')
                        ->get();

        return response()->json([
            'periode' => [
                'dari'   => $request->dari_tanggal,
                'sampai' => $request->sampai_tanggal,
            ],
            'tabungan' => [
                'data'        => $tabungan,
                'total_kg'    => $tabungan->sum('berat_kg'),
                'total_nilai' => $tabungan->sum('nilai_rupiah'),
            ],
            'penarikan' => [
                'data'          => $penarikan,
                'total_nominal' => $penarikan->sum('nominal'),
            ],
        ]);
    }
}
