<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Kirim notifikasi dengan error handling
     */
    public static function send(
        string $targetRole,
        string $type,
        string $title,
        string $message,
        ?string $url = null,
        ?int $userId = null,
        string $status = 'unread',
        string $priority = 'normal'
    ): ?Notification {
        try {
            return Notification::create([
                'type'        => $type,
                'target_role' => $targetRole,
                'user_id'     => $userId,
                'title'       => $title,
                'message'     => $message,
                'url'         => $url,
                'is_read'     => $status === 'read',
                'status'      => $status,
                'priority'    => $priority,
            ]);
        } catch (\Exception $e) {
            Log::error('Gagal membuat notifikasi: ' . $e->getMessage(), [
                'target_role' => $targetRole,
                'type' => $type,
                'title' => $title,
                'user_id' => $userId
            ]);
            return null;
        }
    }

    // ─── KE ADMIN (semua admin lihat) ───

    public static function toAdmin(string $type, string $title, string $message, ?string $url = null): ?Notification
    {
        return self::send('admin', $type, $title, $message, $url, null);
    }

    // Kumpulan Notifikasi untuk Admin (Biarkan utuh seperti aslinya)
    public static function nasabahBaru(string $nama): ?Notification { return self::toAdmin('nasabah', 'Nasabah Baru Mendaftar', "{$nama} telah terdaftar sebagai anggota Bank Sampah Digital.", url('/admin/nasabah')); }
    public static function nasabahVerifikasi(string $nama): ?Notification { return self::toAdmin('nasabah', 'Nasabah Diverifikasi', "Akun {$nama} telah diverifikasi oleh admin.", url('/admin/nasabah')); }
    public static function nasabahNonaktif(string $nama): ?Notification { return self::toAdmin('nasabah', 'Nasabah Dinonaktifkan', "Akun {$nama} telah dinonaktifkan oleh admin.", url('/admin/nasabah')); }
    public static function nasabahAktifkan(string $nama): ?Notification { return self::toAdmin('nasabah', 'Nasabah Diaktifkan', "Akun {$nama} telah diaktifkan kembali.", url('/admin/nasabah')); }
    public static function penarikanBaru(string $nama, string $nominal): ?Notification { return self::toAdmin('penarikan', 'Permintaan Penarikan Baru', "{$nama} mengajukan penarikan {$nominal}.", url('/admin/penarikan')); }
    public static function penarikanSelesai(string $nama, string $nominal): ?Notification { return self::toAdmin('penarikan', 'Penarikan Selesai', "Penarikan {$nominal} untuk {$nama} telah selesai diproses.", url('/admin/penarikan')); }
    public static function penarikanDitolak(string $nama, string $nominal): ?Notification { return self::toAdmin('penarikan', 'Penarikan Ditolak', "Penarikan {$nominal} untuk {$nama} ditolak.", url('/admin/penarikan')); }
    public static function tabunganMasuk(string $nama, string $nominal, string $jenisSampah): ?Notification { return self::toAdmin('tabungan', 'Setoran Sampah Masuk', "{$nama} menyetor {$jenisSampah} senilai {$nominal}.", url('/admin/tabungan')); }
    public static function sampahDitambah(string $namaSampah, string $harga): ?Notification { return self::toAdmin('sampah', 'Jenis Sampah Ditambah', "Jenis sampah {$namaSampah} ditambahkan dengan harga {$harga}/kg.", url('/admin/jenis-sampah')); }
    public static function sampahDiubah(string $namaSampah, string $hargaLama, string $hargaBaru): ?Notification { return self::toAdmin('sampah', 'Harga Sampah Diubah', "{$namaSampah}: {$hargaLama} → {$hargaBaru}/kg.", url('/admin/jenis-sampah')); }
    public static function sampahDinonaktifkan(string $namaSampah): ?Notification { return self::toAdmin('sampah', 'Sampah Dinonaktifkan', "Jenis sampah {$namaSampah} dinonaktifkan.", url('/admin/jenis-sampah')); }
    public static function sampahDiaktifkan(string $namaSampah): ?Notification { return self::toAdmin('sampah', 'Sampah Diaktifkan', "Jenis sampah {$namaSampah} diaktifkan kembali.", url('/admin/jenis-sampah')); }
    public static function artikelDibuat(string $judul): ?Notification { return self::toAdmin('artikel', 'Artikel Baru', "Artikel \"{$judul}\" telah dipublikasikan.", url('/admin/artikel')); }
    public static function artikelDiedit(string $judul): ?Notification { return self::toAdmin('artikel', 'Artikel Diedit', "Artikel \"{$judul}\" telah diperbarui.", url('/admin/artikel')); }
    public static function artikelDihapus(string $judul): ?Notification { return self::toAdmin('artikel', 'Artikel Dihapus', "Artikel \"{$judul}\" telah dihapus.", url('/admin/artikel')); }
    public static function loginGagal(string $email): ?Notification { return self::toAdmin('auth', 'Login Gagal', "Percobaan login gagal untuk email: {$email}.", null); }
    public static function stokDitambahkan(string $namaSampah, float $berat, string $hargaJual): ?Notification { return self::toAdmin('stok', 'Stok Sampah Ditambah', "Stok {$namaSampah} ({$berat} kg) ditambahkan. Harga jual: {$hargaJual}/kg.", url('/admin/stok-sampah')); }
    public static function stokDiubah(string $namaSampah, int $stokId): ?Notification { return self::toAdmin('stok', 'Stok Diubah', "Stok {$namaSampah} (ID #{$stokId}) telah diperbarui.", url('/admin/stok-sampah')); }
    public static function stokDihapus(string $namaSampah): ?Notification { return self::toAdmin('stok', 'Stok Dihapus', "Stok {$namaSampah} telah dihapus dari sistem.", url('/admin/stok-sampah')); }
    public static function stokTerjual(string $namaSampah, float $berat, float $pendapatan, string $pembeli): ?Notification { return self::toAdmin('stok', 'Stok Terjual', "{$namaSampah} ({$berat} kg) terjual kepada {$pembeli}. Pendapatan: Rp " . number_format($pendapatan, 0, ',', '.') . ".", url('/admin/stok-sampah')); }
    public static function stokDibuat(string $namaSampah, float $berat): ?Notification { return self::toAdmin('stok', 'Stok Baru', "Stok {$namaSampah} ({$berat} kg) berhasil dicatat.", url('/admin/stok-sampah')); }

    // ─── KE NASABAH SPESIFIK & GLOBAL ───

    public static function toNasabah(?int $userId, string $type, string $title, string $message, ?string $url = null): ?Notification
    {
        // Jika $userId null, ini akan jadi notifikasi global untuk semua nasabah
        return self::send('nasabah', $type, $title, $message, $url, $userId);
    }

    public static function penarikanDisetujuiNasabah(int $userId, string $nominal): ?Notification
    {
        return self::toNasabah(
            $userId,
            'penarikan',
            'Penarikan Disetujui',
            "Pengajuan penarikan Rp{$nominal} telah disetujui oleh admin.",
            url('/nasabah/penarikan')
        );
    }

    public static function penarikanSelesaiNasabah(int $userId, string $nominal, string $tanggal): ?Notification
    {
        return self::toNasabah(
            $userId,
            'penarikan',
            'Penarikan Selesai',
            "Penarikan Rp{$nominal} selesai diproses. Silakan ambil/cek pada {$tanggal}.",
            url('/nasabah/penarikan')
        );
    }

    public static function penarikanDitolakNasabah(int $userId, string $nominal, string $alasan): ?Notification
    {
        return self::toNasabah(
            $userId,
            'penarikan',
            'Penarikan Ditolak',
            "Mohon maaf, penarikan Rp{$nominal} ditolak. Alasan: {$alasan}.",
            url('/nasabah/penarikan')
        );
    }

    public static function tabunganMasukNasabah(
        int $userId,
        string $nominal,
        string $tanggal,
        string $jenisSampah = '',
        string $saldoAkhir = null
    ): ?Notification {
        $base = $jenisSampah
            ? "Setoran {$jenisSampah} senilai {$nominal} berhasil dicatat pada {$tanggal}."
            : "Setoran {$nominal} berhasil dicatat pada {$tanggal}.";

        if ($saldoAkhir !== null) {
            $pesan = "{$base} Saldo Anda saat ini adalah {$saldoAkhir}.";
        } else {
            $pesan = $base;
        }

        return self::toNasabah(
            $userId,
            'tabungan',
            'Setoran Berhasil',
            $pesan,
            url('/nasabah/tabungan')
        );
    }

    // FITUR BARU: Notifikasi Global Harga Berubah (Terkirim ke semua nasabah)
    public static function hargaSampahBerubahNasabah(string $namaSampah, string $hargaBaru): ?Notification
    {
        return self::toNasabah(
            null, // NULL artinya terkirim massal ke semua yang target_role='nasabah'
            'info',
            'Update Harga Sampah Terbaru 📈',
            "Informasi: Harga sampah jenis {$namaSampah} kini berubah menjadi {$hargaBaru}/kg. Yuk makin semangat pilah sampah!",
            url('/nasabah/dashboard')
        );
    }

    public static function akunDiverifikasi(int $userId): ?Notification
    {
        return self::toNasabah(
            $userId,
            'akun',
            'Akun Diverifikasi',
            'Akun Anda telah diverifikasi. Selamat datang di Bank Sampah Digital! Anda bisa mulai menyetor sampah.',
            url('/nasabah/dashboard')
        );
    }

    public static function akunDinonaktifkan(int $userId): ?Notification
    {
        return self::toNasabah(
            $userId,
            'akun',
            'Akun Dinonaktifkan',
            'Akun Anda dinonaktifkan oleh admin. Hubungi admin untuk informasi lebih lanjut.',
            null
        );
    }

    public static function passwordDireset(int $userId): ?Notification
    {
        return self::toNasabah(
            $userId,
            'akun',
            'Password Direset',
            'Password Anda direset oleh admin. Gunakan password default untuk login.',
            url('/nasabah/login')
        );
    }
}
