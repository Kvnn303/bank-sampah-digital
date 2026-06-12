<?php

namespace App\Http\Controllers\Api\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\Tabungan;
use App\Models\Penarikan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RiwayatController extends Controller
{
    // GET riwayat tabungan nasabah
    public function tabungan(Request $request)
    {
        $nasabah = $request->user()->nasabah;

        if (!$nasabah) {
            return response()->json([
                'message' => 'Data nasabah tidak ditemukan'
            ], 404);
        }

        $query = Tabungan::with(['jenisSampah:id,nama,kategori'])
                    ->where('nasabah_id', $nasabah->id)
                    ->orderByDesc('created_at');

        // Filter by bulan & tahun
        if ($request->bulan && $request->tahun) {
            $query->whereMonth('tanggal_setor', $request->bulan)
                ->whereYear('tanggal_setor', $request->tahun);
        }

        // Filter by jenis sampah
        if ($request->jenis_sampah_id) {
            $query->where('jenis_sampah_id', $request->jenis_sampah_id);
        }

        $riwayat = $query->paginate(10);

        return response()->json($riwayat)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }

    // GET riwayat penarikan nasabah
    public function penarikan(Request $request)
    {
        $nasabah = $request->user()->nasabah;

        if (!$nasabah) {
            return response()->json([
                'message' => 'Data nasabah tidak ditemukan'
            ], 404);
        }

        $query = Penarikan::where('nasabah_id', $nasabah->id)
                    ->orderByDesc('created_at');

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $riwayat = $query->paginate(10);

        return response()->json($riwayat)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }

    // GET semua riwayat transaksi (tabungan + penarikan) - UNTUK DASHBOARD & RIWAYAT APP
    public function semua(Request $request)
    {
        $nasabah = $request->user()->nasabah;

        if (!$nasabah) {
            return response()->json([
                'message' => 'Data nasabah tidak ditemukan'
            ], 404);
        }

        // 1. Ambil Data Tabungan (Setoran)
        $tabungan = Tabungan::with(['jenisSampah:id,nama'])
                        ->where('nasabah_id', $nasabah->id)
                        ->orderByDesc('created_at')
                        ->take(50) // Naikkan limit agar RiwayatScreen tidak kosong
                        ->get()
                        ->map(function($t) {
                            // PENGAMAN: Jika jenisSampah kosong/terhapus, jangan sampai error
                            $namaSampah = $t->jenisSampah ? $t->jenisSampah->nama : 'Sampah';
                            $nominal = $t->nilai_rupiah ?? 0;

                            return [
                                'id'          => 'T-' . $t->id,
                                'tipe'        => 'tabungan',
                                'keterangan'  => 'Setor ' . $namaSampah . ' ' . ($t->berat_kg ?? 0) . 'kg',
                                'nominal'     => '+Rp' . number_format($nominal, 0, ',', '.'),
                                'tanggal'     => Carbon::parse($t->created_at)->toIso8601String(),
                                'status'      => 'selesai',
                            ];
                        });

        // 2. Ambil Data Penarikan
        $penarikan = Penarikan::where('nasabah_id', $nasabah->id)
                        ->orderByDesc('created_at')
                        ->take(50) // Naikkan limit
                        ->get()
                        ->map(function($p) {
                            $nominal = $p->nominal ?? 0;

                            return [
                                'id'          => 'P-' . $p->id,
                                'tipe'        => 'penarikan',
                                'keterangan'  => 'Penarikan saldo',
                                'nominal'     => '-Rp' . number_format($nominal, 0, ',', '.'),
                                // ✅ PERBAIKAN: Gunakan format ISO8601
                                'tanggal'     => Carbon::parse($p->created_at)->toIso8601String(),
                                'status'      => $p->status,
                            ];
                        });

        // 3. Gabungkan dan urutkan by tanggal dari yang terbaru (sekarang sudah level detik)
        $semua = $tabungan->merge($penarikan)
                    ->sortByDesc('tanggal')
                    ->values();

        return response()->json([
            'success' => true,
            'riwayat' => $semua,
        ], 200)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }
}
