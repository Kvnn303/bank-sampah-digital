<?php

namespace App\Http\Controllers\Api\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\JenisSampah;
use Illuminate\Http\Request;

class KatalogController extends Controller
{
    /**
     * GET /api/nasabah/katalog-sampah
     * Mengambil daftar semua jenis sampah yang aktif beserta harganya
     */
    public function index(Request $request)
    {
        // Ambil data sampah yang aktif, urutkan berdasarkan kategori lalu nama
        $katalog = JenisSampah::where('is_active', 1)
                    ->orderBy('kategori')
                    ->orderBy('nama')
                    ->get();

        return response()->json([
            'success' => true,
            'data'    => $katalog
        ], 200);
    }
}
