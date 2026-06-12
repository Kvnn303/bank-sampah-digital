<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Artikel;
use Illuminate\Http\Request;

class ArtikelController extends Controller
{
    public function index(Request $request)
    {
        // Ambil 5 artikel terbaru yang sudah di-publish
        $artikels = Artikel::where('is_published', 1)
                    ->orderBy('created_at', 'desc')
                    ->take(5)
                    ->get();

        // Modifikasi URL gambar agar terbaca di HP (React Native butuh URL http://...)
        $artikels->transform(function ($item) use ($request) {
            // Kita gabungkan IP server dengan lokasi folder storage-nya
            $item->gambar_url = $item->gambar ? $request->getSchemeAndHttpHost() . '/storage/' . $item->gambar : null;
            return $item;
        });

        return response()->json([
            'success' => true,
            'data' => $artikels
        ], 200);
    }
}
