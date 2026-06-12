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

class PenarikanNasabahController extends Controller
{
    use NotifiableTrait;
    // GET daftar pengajuan penarikan nasabah
    public function index(Request $request)
    {
        $nasabah = $request->user()->nasabah;

        if (!$nasabah) {
            return response()->json([
                'message' => 'Data nasabah tidak ditemukan'
            ], 404);
        }

        $penarikan = Penarikan::where('nasabah_id', $nasabah->id)
                        ->orderByDesc('created_at')
                        ->paginate(10);

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
            'tanggal_ambil'    => 'required|date|after_or_equal:today', // Menangkap tanggal dari HP nasabah
            'catatan_nasabah'  => 'nullable|string',
        ]);

        // Hitung saldo aktif real-time
        // Hanya penarikan yang SUDAH selesai yang mengurangi saldo
        $totalTabungan = (float) Tabungan::where('nasabah_id', $nasabah->id)->sum('nilai_rupiah');
        $totalPenarikan = (float) Penarikan::where('nasabah_id', $nasabah->id)
                            ->where('status', 'selesai')
                            ->sum('nominal');
        $saldoAktif = $totalTabungan - $totalPenarikan;

        // Cek saldo cukup
        if ($saldoAktif < $request->nominal) {
            return response()->json([
                'message' => 'Saldo tidak mencukupi',
                'saldo'   => $saldoAktif,
                'nominal' => $request->nominal,
            ], 400);
        }

        // Cek tidak ada penarikan pending
        $penarikanPending = Penarikan::where('nasabah_id', $nasabah->id)
                                ->where('status', 'pending')
                                ->exists();

        if ($penarikanPending) {
            return response()->json([
                'message' => 'Masih ada pengajuan penarikan yang belum diproses'
            ], 400);
        }

        // Format Catatan (Gabungkan Metode + Teks Catatan Nasabah)
        $metodeLabel = $request->metode === 'whatsapp' ? 'Transfer WA' : 'Ambil Tunai';
        $teksCatatan = $request->catatan_nasabah ? " - " . $request->catatan_nasabah : "";
        $catatanAkhir = "[Metode: " . $metodeLabel . "]" . $teksCatatan;

        $penarikan = Penarikan::create([
            'nasabah_id'      => $nasabah->id,
            'nominal'         => $request->nominal,
            'status'          => 'pending',
            'tanggal_ambil'   => $request->tanggal_ambil, // Disimpan ke database
            'catatan_nasabah' => $catatanAkhir,
        ]);

        AuditLogService::log(
            action: 'PENARIKAN_AJUKAN',
            module: 'Penarikan',
            description: "Nasabah {$nasabah->nama_lengkap} mengajukan penarikan Rp{$request->nominal} ({$metodeLabel})",
            newData: $penarikan->toArray()
        );

        // Notif ke admin
        $nominal = 'Rp' . number_format($penarikan->nominal, 0, ',', '.');
        NotificationService::penarikanBaru($nasabah->nama_lengkap, $nominal);

        // 🔔 TRIGGER: Notifikasi ke Nasabah bahwa pengajuan penarikan berhasil diajukan (Pending)
        $this->notifyPenarikanPending($nasabah->user_id, $nasabah->nama_lengkap, $nominal);

        // Notifikasi langsung ke mobile via Notification model (tabungan type)
        try {
            Notification::create([
                'user_id'  => $nasabah->user_id,
                'type'     => 'tabungan',
                'title'    => 'Penarikan Sedang Diproses',
                'message'  => 'Permintaan penarikan saldo Anda sebesar ' . $nominal . ' sedang diproses.',
                'is_read'  => false,
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

        // Log audit pembatalan
        AuditLogService::log(
            action: 'PENARIKAN_BATAL',
            module: 'Penarikan',
            description: "Nasabah {$nasabah->nama_lengkap} membatalkan penarikan Rp" . number_format($penarikan->nominal, 0, ',', '.'),
            oldData: $penarikan->toArray()
        );

        // PERBAIKAN 2: Dihapus dari tabel, karena enum database tidak punya 'dibatalkan'
        $penarikan->delete();

        return response()->json([
            'message'  => 'Pengajuan penarikan berhasil dibatalkan',
        ]);
    }
}
