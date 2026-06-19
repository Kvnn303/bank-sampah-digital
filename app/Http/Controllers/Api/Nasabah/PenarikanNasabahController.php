<?php

namespace App\Http\Controllers\Api\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Penarikan;
use App\Models\Tabungan;
use App\Services\AuditLogService;
use App\Services\NotificationService;
use App\Traits\NotifiableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class PenarikanNasabahController extends Controller
{
    use NotifiableTrait;

    // GET daftar pengajuan penarikan nasabah beserta Struk Digital & Bukti Foto
    public function index(Request $request)
    {
        $nasabah = $request->user()->nasabah;

        if (!$nasabah) {
            return response()->json([
                'message' => 'Data nasabah tidak ditemukan'
            ], 404);
        }

        // Muat relasi 'diprosesoleh' agar aplikasi mobile tahu admin/petugas yang memproses pencairan dana
        $penarikan = Penarikan::with(['diprosesoleh'])
                        ->where('nasabah_id', $nasabah->id)
                        ->orderByDesc('created_at')
                        ->paginate(10);

        // Transformasi data koleksi di dalam paginasi agar menyertakan objek struk_digital terformat
        $penarikan->getCollection()->transform(function ($item) {
            $nominalFormatted = 'Rp' . number_format($item->nominal, 0, ',', '.');
            $waktuProses = $item->tanggal_proses ? Carbon::parse($item->tanggal_proses)->format('d M Y, H:i:s') . ' WIB' : '-';
            $namaAdmin = $item->diprosesoleh ? $item->diprosesoleh->name : 'Admin Sistem';

            // Membuat kombinasi nomor referensi unik agar serasi dengan sistem admin panel web
            $nomorReferensi = $item->tanggal_proses
                ? 'TRX-WD-' . date('Ymd', strtotime($item->tanggal_proses)) . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT)
                : 'TRX-WD-' . date('Ymd', strtotime($item->created_at)) . '-' . str_pad($item->id, 4, '0', STR_PAD_LEFT);

            // Menyisipkan struk_digital ke respon data agar mempermudah rendering layout di React Native
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

        return response()->json($penarikan);
    }

    // POST ajukan penarikan
    public function store(Request $request)
    {
        $nasabah = $request->user()->nasabah;

        if (!$nasabah) {
            return response()->json([
                'message' => 'Data nasabah tidak ditemukan'
            ], 404);
        }

        // Cek status akun
        if ($nasabah->status_akun !== 'active') {
            return response()->json([
                'message' => 'Akun belum aktif, belum bisa melakukan penarikan'
            ], 400);
        }

        $request->validate([
            'nominal'          => 'required|numeric|min:10000',
            'metode'           => 'required|string',
            'tanggal_ambil'    => 'required|date|after_or_equal:today',
            'catatan_nasabah'  => 'nullable|string',
        ]);

        // Hitung saldo aktif real-time (Hanya penarikan berstatus 'selesai' yang memotong saldo)
        $totalTabungan = (float) Tabungan::where('nasabah_id', $nasabah->id)->sum('nilai_rupiah');
        $totalPenarikan = (float) Penarikan::where('nasabah_id', $nasabah->id)
                            ->where('status', 'selesai')
                            ->sum('nominal');
        $saldoAktif = $totalTabungan - $totalPenarikan;

        // Cek kecukupan saldo
        if ($saldoAktif < $request->nominal) {
            return response()->json([
                'message' => 'Saldo tidak mencukupi',
                'saldo'   => $saldoAktif,
                'nominal' => $request->nominal,
            ], 400);
        }

        // Mencegah nasabah mengajukan penarikan ganda jika ada yang masih pending
        $penarikanPending = Penarikan::where('nasabah_id', $nasabah->id)
                                ->where('status', 'pending')
                                ->exists();

        if ($penarikanPending) {
            return response()->json([
                'message' => 'Masih ada pengajuan penarikan yang belum diproses'
            ], 400);
        }

        // Format Catatan internal penarikan
        $metodeLabel = $request->metode === 'whatsapp' ? 'Transfer WA' : 'Ambil Tunai';
        $teksCatatan = $request->catatan_nasabah ? " - " . $request->catatan_nasabah : "";
        $catatanAkhir = "[Metode: " . $metodeLabel . "]" . $teksCatatan;

        $penarikan = Penarikan::create([
            'nasabah_id'      => $nasabah->id,
            'nominal'         => $request->nominal,
            'status'          => 'pending',
            'tanggal_ambil'   => $request->tanggal_ambil,
            'catatan_nasabah' => $catatanAkhir,
        ]);

        AuditLogService::log(
            action: 'PENARIKAN_AJUKAN',
            module: 'Penarikan',
            description: "Nasabah {$nasabah->nama_lengkap} mengajukan penarikan Rp{$request->nominal} ({$metodeLabel})",
            newData: $penarikan->toArray()
        );

        $nominal = 'Rp' . number_format($penarikan->nominal, 0, ',', '.');
        NotificationService::penarikanBaru($nasabah->nama_lengkap, $nominal);

        // 🔔 TRIGGER: Notifikasi ke internal sistem nasabah
        $this->notifyPenarikanPending($nasabah->user_id, $nasabah->nama_lengkap, $nominal);

        try {
            Notification::create([
                'user_id'   => $nasabah->user_id,
                'type'      => 'tabungan',
                'title'     => 'Penarikan Sedang Diproses',
                'message'   => 'Permintaan penarikan saldo Anda sebesar ' . $nominal . ' sedang diproses.',
                'status'    => 'unread',
                'priority'  => 'normal'
            ]);
        } catch (\Exception $e) {
            Log::warning('Gagal menyimpan notifikasi penarikan: ' . $e->getMessage());
        }

        return response()->json([
            'message'   => 'Pengajuan penarikan berhasil dikirim',
            'penarikan' => $penarikan,
        ], 201);
    }

    // DELETE batalkan penarikan (hanya yang masih pending)
    public function batalkan(Request $request, $id)
    {
        $nasabah = $request->user()->nasabah;

        if (!$nasabah) {
            return response()->json([
                'message' => 'Data nasabah tidak ditemukan'
            ], 404);
        }

        $penarikan = Penarikan::where('nasabah_id', $nasabah->id)
                        ->where('id', $id)
                        ->first();

        if (!$penarikan) {
            return response()->json([
                'message' => 'Data penarikan tidak ditemukan'
            ], 404);
        }

        if ($penarikan->status !== 'pending') {
            return response()->json([
                'message' => 'Penarikan tidak bisa dibatalkan karena sudah diproses'
            ], 400);
        }

        AuditLogService::log(
            action: 'PENARIKAN_BATAL',
            module: 'Penarikan',
            description: "Nasabah {$nasabah->nama_lengkap} membatalkan penarikan Rp" . number_format($penarikan->nominal, 0, ',', '.'),
            oldData: $penarikan->toArray()
        );

        $penarikan->delete();

        return response()->json([
            'message'  => 'Pengajuan penarikan berhasil dibatalkan',
        ]);
    }
}
