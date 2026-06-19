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

    // GET riwayat penarikan nasabah (DIPERBARUI DENGAN STRUK DIGITAL)
    public function penarikan(Request $request)
    {
        $nasabah = $request->user()->nasabah;

        if (!$nasabah) {
            return response()->json([
                'message' => 'Data nasabah tidak ditemukan'
            ], 404);
        }

        // Muat relasi admin yang memproses
        $query = Penarikan::with('diprosesoleh')
                    ->where('nasabah_id', $nasabah->id)
                    ->orderByDesc('created_at');

        // Filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $riwayat = $query->paginate(10);

        // Transformasi untuk menyisipkan struk_digital dan foto bukti
        $riwayat->getCollection()->transform(function ($item) {
            $nominalFormatted = 'Rp' . number_format($item->nominal, 0, ',', '.');
            $waktuProses = $item->tanggal_proses ? Carbon::parse($item->tanggal_proses)->format('d M Y, H:i:s') . ' WIB' : '-';
            $namaAdmin = $item->diprosesoleh ? $item->diprosesoleh->name : 'Admin Sistem';

            $nomorReferensi = $item->tanggal_proses
                ? 'TRX-WD-' . date('Ymd', strtotime($item->tanggal_proses)) . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT)
                : 'TRX-WD-' . date('Ymd', strtotime($item->created_at)) . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);

            $item->struk_digital = [
                'nomor_referensi' => $nomorReferensi,
                'status'          => strtoupper($item->status),
                'waktu_proses'    => $waktuProses,
                'diproses_oleh'   => $namaAdmin,
                'nominal_cair'    => $nominalFormatted,
                'metode'          => $item->catatan_nasabah ?? 'Ambil Tunai',
                'catatan_admin'   => $item->catatan_admin ?? '-',
                'alasan_penolakan'=> $item->alasan_penolakan ?? '-',
                'link_foto_bukti' => $item->bukti_transfer ? asset('storage/' . $item->bukti_transfer) : null,
            ];

            return $item;
        });

        return response()->json($riwayat)
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
    }

    // GET semua riwayat transaksi (DIPERBARUI DENGAN STRUK DIGITAL)
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
                        ->take(50)
                        ->get()
                        ->map(function($t) {
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

        // 2. Ambil Data Penarikan (Load relasi diprosesoleh)
        $penarikan = Penarikan::with('diprosesoleh')
                        ->where('nasabah_id', $nasabah->id)
                        ->orderByDesc('created_at')
                        ->take(50)
                        ->get()
                        ->map(function($p) {
                            $nominalFormatted = 'Rp' . number_format($p->nominal ?? 0, 0, ',', '.');
                            $waktuProses = $p->tanggal_proses ? Carbon::parse($p->tanggal_proses)->format('d M Y, H:i:s') . ' WIB' : '-';
                            $namaAdmin = $p->diprosesoleh ? $p->diprosesoleh->name : 'Admin Sistem';

                            $nomorReferensi = $p->tanggal_proses
                                ? 'TRX-WD-' . date('Ymd', strtotime($p->tanggal_proses)) . '-' . str_pad($p->id, 4, '0', STR_PAD_LEFT)
                                : 'TRX-WD-' . date('Ymd', strtotime($p->created_at)) . '-' . str_pad($p->id, 4, '0', STR_PAD_LEFT);

                            return [
                                'id'          => 'P-' . $p->id,
                                'tipe'        => 'penarikan',
                                'keterangan'  => 'Penarikan saldo',
                                'nominal'     => '-' . $nominalFormatted,
                                'tanggal'     => Carbon::parse($p->created_at)->toIso8601String(),
                                'status'      => $p->status,
                                // Sisipkan data struk agar bisa di-klik dari Dashboard
                                'struk_digital' => [
                                    'nomor_referensi' => $nomorReferensi,
                                    'status'          => strtoupper($p->status),
                                    'waktu_proses'    => $waktuProses,
                                    'diproses_oleh'   => $namaAdmin,
                                    'nominal_cair'    => $nominalFormatted,
                                    'metode'          => $p->catatan_nasabah ?? 'Ambil Tunai',
                                    'catatan_admin'   => $p->catatan_admin ?? '-',
                                    'alasan_penolakan'=> $p->alasan_penolakan ?? '-',
                                    'link_foto_bukti' => $p->bukti_transfer ? asset('storage/' . $p->bukti_transfer) : null,
                                ]
                            ];
                        });

        // 3. Gabungkan dan urutkan by tanggal dari yang terbaru
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
