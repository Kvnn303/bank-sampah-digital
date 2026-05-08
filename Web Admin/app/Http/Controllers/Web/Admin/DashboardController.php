<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use App\Models\Tabungan;
use App\Models\Penarikan;
use App\Models\JenisSampah;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik nasabah
        $totalNasabah     = Nasabah::count();
        $nasabahPending   = Nasabah::where('status_akun', 'pending')->count();
        $nasabahVerified  = Nasabah::where('status_akun', 'verified')->count();
        $nasabahActive    = Nasabah::where('status_akun', 'active')->count();

        // Statistik sampah
        $totalSampahKg    = Tabungan::sum('berat_kg');
        $totalNilai       = Tabungan::sum('nilai_rupiah');
        $totalJenisSampah = JenisSampah::where('is_active', true)->count();

        // Sampah bulan ini
        $sampahBulanIni = Tabungan::whereMonth('tanggal_setor', now()->month)
                            ->whereYear('tanggal_setor', now()->year)
                            ->sum('berat_kg');

        $nilaiBulanIni = Tabungan::whereMonth('tanggal_setor', now()->month)
                            ->whereYear('tanggal_setor', now()->year)
                            ->sum('nilai_rupiah');

        // Statistik penarikan
        $penarikanPending  = Penarikan::where('status', 'pending')->count();
        $penarikanDiproses = Penarikan::where('status', 'diproses')->count();
        $penarikanSelesai  = Penarikan::where('status', 'selesai')->count();
        $totalDicairkan    = Penarikan::where('status', 'selesai')->sum('nominal');

        // Grafik sampah per bulan
        $grafikSampah = [];
        for ($i = 1; $i <= 12; $i++) {
            $grafikSampah[] = Tabungan::whereMonth('tanggal_setor', $i)
                                ->whereYear('tanggal_setor', now()->year)
                                ->sum('berat_kg');
        }

        // Grafik nasabah baru per bulan
        $grafikNasabah = [];
        for ($i = 1; $i <= 12; $i++) {
            $grafikNasabah[] = Nasabah::whereMonth('created_at', $i)
                                ->whereYear('created_at', now()->year)
                                ->count();
        }

        // Grafik jenis sampah terbanyak
        $grafikJenis = Tabungan::with('jenisSampah')
                        ->selectRaw('jenis_sampah_id, SUM(berat_kg) as total_kg')
                        ->groupBy('jenis_sampah_id')
                        ->orderByDesc('total_kg')
                        ->take(5)
                        ->get();

        // Jenis sampah aktif & harga
        $jenisSampahAktif = JenisSampah::where('is_active', true)
                            ->orderBy('kategori')
                            ->orderBy('nama')
                            ->get();

        // Tabungan terbaru
        $tabunganTerbaru = Tabungan::with(['nasabah', 'jenisSampah'])
                            ->orderByDesc('created_at')
                            ->take(5)
                            ->get();

        // Penarikan pending terbaru
        $penarikanTerbaru = Penarikan::with('nasabah')
                            ->where('status', 'pending')
                            ->orderByDesc('created_at')
                            ->take(5)
                            ->get();

        return view('admin.dashboard', compact(
            'totalNasabah',
            'nasabahPending',
            'nasabahVerified',
            'nasabahActive',
            'totalSampahKg',
            'totalNilai',
            'totalDicairkan',
            'totalJenisSampah',
            'sampahBulanIni',
            'nilaiBulanIni',
            'penarikanPending',
            'penarikanDiproses',
            'penarikanSelesai',
            'grafikSampah',
            'grafikNasabah',
            'grafikJenis',
            'jenisSampahAktif',
            'tabunganTerbaru',
            'penarikanTerbaru',
        ));
    }
}