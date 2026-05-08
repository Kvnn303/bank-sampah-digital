<?php

namespace App\Http\Controllers;

use App\Models\JenisSampah;
use App\Models\Artikel;
use App\Models\Nasabah;
use App\Models\Tabungan;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $totalNasabah = Nasabah::whereIn('status_akun', ['active', 'verified'])->count();
        $totalSampah = Tabungan::sum('berat_kg');
        $jenisSampah = JenisSampah::where('is_active', true)->orderBy('kategori', 'asc')->get();
        $artikels = Artikel::where('is_published', true)->latest()->take(3)->get();

        return view('welcome', compact('totalNasabah', 'totalSampah', 'jenisSampah', 'artikels'));
    }

    // Memabacar artikel berdasarkan slug
    public function bacaArtikel($slug)
    {
        $artikel = Artikel::where('slug', $slug)->where('is_published', true)->firstOrFail();

        // Ambil artikel lain untuk rekomendasi
        $artikelLain = Artikel::where('id', '!=', $artikel->id)
                            ->where('is_published', true)
                            ->latest()
                            ->take(2)
                            ->get();

        return view('artikel-detail', compact('artikel', 'artikelLain'));
    }
}
