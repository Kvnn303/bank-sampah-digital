<?php

namespace App\Http\Controllers;

use App\Models\JenisSampah;
use App\Models\Artikel;
use App\Models\Nasabah;
use App\Models\Tabungan;
use App\Models\StokSampah;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        $totalNasabah = Nasabah::whereIn('status_akun', ['active', 'verified'])->count();
        $totalSampah = Tabungan::sum('berat_kg');
        $jenisSampah = JenisSampah::where('is_active', true)->orderBy('kategori', 'asc')->get();
        $artikels = Artikel::where('is_published', true)->latest()->take(3)->get();

        // Stok yang dipublikasikan (siap dijual ke pengepul)
        $stokTersedia = StokSampah::with('jenisSampah')
            ->published()
            ->diPress()
            ->where('status', '!=', 'terjual')
            ->orderByDesc('tanggal_masuk')
            ->take(6)
            ->get();

        return view('welcome', compact(
            'totalNasabah',
            'totalSampah',
            'jenisSampah',
            'artikels',
            'stokTersedia'
        ));
    }

    public function bacaArtikel($slug)
    {
        $artikel = Artikel::with(['galeri' => function ($q) {
                            $q->orderBy('urutan', 'asc')->orderBy('id', 'asc');
                        }])
                        ->where('slug', $slug)
                        ->where('is_published', true)
                        ->firstOrFail();
        $artikelLain = Artikel::where('id', '!=', $artikel->id)
                            ->where('is_published', true)
                            ->latest()
                            ->take(2)
                            ->get();
        return view('artikel-detail', compact('artikel', 'artikelLain'));
    }

    public function stokTersedia()
    {
        $stokList = StokSampah::with('jenisSampah')
            ->published()
            ->diPress()
            ->where('status', '!=', 'terjual')
            ->orderByDesc('tanggal_masuk')
            ->get();

        $totalBerat = StokSampah::published()->diPress()->where('status', '!=', 'terjual')->sum('stok_tersisa_kg');

        return view('stok-tersedia', compact('stokList', 'totalBerat'));
    }

    public function detailStok($slug)
    {
        $stok = StokSampah::with('jenisSampah')
            ->where('slug', $slug)
            ->published()
            ->diPress()
            ->firstOrFail();

        $stokLain = StokSampah::with('jenisSampah')
            ->published()
            ->diPress()
            ->where('status', '!=', 'terjual')
            ->where('id', '!=', $stok->id)
            ->orderByDesc('tanggal_masuk')
            ->take(3)
            ->get();

        return view('stok-detail', compact('stok', 'stokLain'));
    }
}
