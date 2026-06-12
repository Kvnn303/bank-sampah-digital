<?php

namespace App\Traits;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Log;

trait NotifiableTrait
{
    /**
     * Kirim notifikasi per-user (mobile banking style).
     */
    private function createNotification(
        int $userId,
        string $type,
        string $title,
        string $message,
        ?string $url = null,
        string $priority = 'normal'
    ): ?Notification {
        try {
            return Notification::create([
                'user_id'  => $userId,
                'type'     => $type,
                'title'    => $title,
                'message'  => $message,
                'url'      => $url,
                'priority' => $priority,
            ]);
        } catch (\Exception $e) {
            Log::warning('Notifikasi gagal dikirim: ' . $e->getMessage(), [
                'user_id' => $userId,
                'type'    => $type,
                'title'   => $title,
            ]);
            return null;
        }
    }

    // ─── TRIGGER: PENARIKAN (transaksi) ───────────────────────────────────────

    /**
     * Notifikasi ke Nasabah: pengajuan penarikan berhasil diajukan (Status: Pending).
     */
    public function notifyPenarikanPending(int $userId, string $nama, string $nominal): ?Notification
    {
        return $this->createNotification(
            $userId,
            'transaksi',
            'Penarikan Sedang Diproses',
            "Pengajuan penarikan {$nominal} oleh {$nama} telah kami terima dan sedang menunggu persetujuan admin.",
            null,
            'high'
        );
    }

    /**
     * Notifikasi ke Nasabah: penarikan disetujui admin (Status: ACC/Diproses).
     */
    public function notifyPenarikanDisetujui(int $userId, string $nominal): ?Notification
    {
        return $this->createNotification(
            $userId,
            'transaksi',
            'Penarikan Disetujui',
            "Pengajuan penarikan {$nominal} telah disetujui. Saldo akan segera dicairkan.",
            null,
            'high'
        );
    }

    /**
     * Notifikasi ke Nasabah: penarikan selesai dicairkan (Status: Selesai).
     */
    public function notifyPenarikanSelesai(int $userId, string $nominal, string $tanggal): ?Notification
    {
        return $this->createNotification(
            $userId,
            'transaksi',
            'Penarikan Berhasil dicairkan',
            "Penarikan {$nominal} telah berhasil dicairkan. Silakan cek saldo Anda.",
            null,
            'high'
        );
    }

    /**
     * Notifikasi ke Nasabah: penarikan ditolak.
     */
    public function notifyPenarikanDitolak(int $userId, string $nominal, string $alasan): ?Notification
    {
        return $this->createNotification(
            $userId,
            'transaksi',
            'Penarikan Ditolak',
            "Mohon maaf, pengajuan penarikan {$nominal} ditolak. Alasan: {$alasan}",
            null,
            'normal'
        );
    }

    // ─── TRIGGER: SETORAN TABUNGAN ───────────────────────────────────────────
    // Notifikasi setoran tabungan di-handle oleh TabunganObserver::created()
    // agar konsisten antara Web Admin & API Admin, dan agar pesan menyertakan
    // saldo akhir real-time. JANGAN panggil createNotification() di sini.

    // ─── TRIGGER: HARGA SAMPAH BERUBAH ───────────────────────────────────────

    /**
     * Notifikasi massal ke semua Nasabah: ada update harga sampah.
     */
    public function notifyHargaBerubah(string $namaSampah, string $hargaBaru): void
    {
        $users = User::where('role', 'nasabah')
            ->where('is_active', true)
            ->get();

        $title   = 'Update Harga Sampah Terbaru';
        $message = "Harga sampah {$namaSampah} kini berubah menjadi {$hargaBaru}/kg. Yuk makin semangat pilah sampah!";

        foreach ($users as $user) {
            try {
                Notification::create([
                    'user_id'  => $user->id,
                    'type'     => 'harga',
                    'title'    => $title,
                    'message'  => $message,
                    'url'      => null,
                    'priority' => 'normal',
                ]);
            } catch (\Exception $e) {
                Log::warning('Notifikasi harga gagal ke user ' . $user->id . ': ' . $e->getMessage());
            }
        }
    }

    // ─── TRIGGER: ARTIKEL BARU ───────────────────────────────────────────────

    /**
     * Notifikasi massal ke semua Nasabah: ada artikel baru.
     */
    public function notifyArtikelBaru(string $judul, string $kategori = ''): void
    {
        $users = User::where('role', 'nasabah')
            ->where('is_active', true)
            ->get();

        $kategoriLabel = $kategori ? ucfirst(str_replace('_', ' ', $kategori)) : 'Artikel';
        $title   = "{$kategoriLabel} Baru";
        $message = "\"{$judul}\" — Baca sekarang di aplikasi!";

        foreach ($users as $user) {
            try {
                Notification::create([
                    'user_id'  => $user->id,
                    'type'     => 'artikel',
                    'title'    => $title,
                    'message'  => $message,
                    'url'      => null,
                    'priority' => 'low',
                ]);
            } catch (\Exception $e) {
                Log::warning('Notifikasi artikel gagal ke user ' . $user->id . ': ' . $e->getMessage());
            }
        }
    }
}