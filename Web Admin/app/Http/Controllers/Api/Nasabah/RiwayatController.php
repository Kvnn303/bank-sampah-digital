<?php

namespace App\Http\Controllers\Api\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\Tabungan;
use App\Models\Penarikan;
use Illuminate\Http\Request;

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
                    ->orderByDesc('tanggal_setor');

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

        return response()->json($riwayat);
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

        return response()->json($riwayat);
    }

    // GET semua riwayat transaksi (tabungan + penarikan)
    public function semua(Request $request)
    {
        $nasabah = $request->user()->nasabah;

        if (!$nasabah) {
            return response()->json([
                'message' => 'Data nasabah tidak ditemukan'
            ], 404);
        }

        $tabungan = Tabungan::with(['jenisSampah:id,nama'])
                        ->where('nasabah_id', $nasabah->id)
                        ->orderByDesc('tanggal_setor')
                        ->take(5)
                        ->get()
                        ->map(function($t) {
                            return [
                                'tipe'        => 'tabungan',
                                'keterangan'  => 'Setor ' . $t->jenisSampah->nama . ' ' . $t->berat_kg . 'kg',
                                'nominal'     => '+Rp' . number_format($t->nilai_rupiah),
                                'tanggal'     => $t->tanggal_setor,
                                'status'      => 'selesai',
                            ];
                        });

        $penarikan = Penarikan::where('nasabah_id', $nasabah->id)
                        ->orderByDesc('created_at')
                        ->take(5)
                        ->get()
                        ->map(function($p) {
                            return [
                                'tipe'       => 'penarikan',
                                'keterangan' => 'Penarikan saldo',
                                'nominal'    => '-Rp' . number_format($p->nominal),
                                'tanggal'    => $p->created_at->toDateString(),
                                'status'     => $p->status,
                            ];
                        });

        // Gabungkan dan urutkan by tanggal
        $semua = $tabungan->merge($penarikan)
                    ->sortByDesc('tanggal')
                    ->values();

        return response()->json([
            'saldo'   => $nasabah->saldo,
            'riwayat' => $semua,
        ]);
    }
}
