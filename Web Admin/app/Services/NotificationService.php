<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    public static function send(string $targetRole, string $type, string $title, string $message, ?string $url = null, ?int $userId = null): Notification
    {
        return Notification::create([
            'type'        => $type,
            'target_role' => $targetRole,
            'user_id'     => $userId,
            'title'       => $title,
            'message'     => $message,
            'url'         => $url,
            'is_read'     => false,
        ]);
    }

    // ─── KE ADMIN (semua admin lihat) ───

    public static function toAdmin(string $type, string $title, string $message, ?string $url = null)
    {
        return self::send('admin', $type, $title, $message, $url, userId: null);
    }

    public static function nasabahBaru(string $nama)
    {
        return self::toAdmin('nasabah', 'Nasabah Baru Mendaftar', "{$nama} mendaftar dan menunggu verifikasi.", url('/admin/nasabah'));
    }

    public static function nasabahVerifikasi(string $nama)
    {
        return self::toAdmin('nasabah', 'Nasabah Diverifikasi', "Akun {$nama} telah diverifikasi.", url('/admin/nasabah'));
    }

    public static function nasabahNonaktif(string $nama)
    {
        return self::toAdmin('nasabah', 'Nasabah Dinonaktifkan', "Akun {$nama} dinonaktifkan.", url('/admin/nasabah'));
    }

    public static function nasabahAktifkan(string $nama)
    {
        return self::toAdmin('nasabah', 'Nasabah Diaktifkan', "Akun {$nama} diaktifkan kembali.", url('/admin/nasabah'));
    }

    public static function penarikanBaru(string $nama, string $nominal)
    {
        return self::toAdmin('penarikan', 'Permintaan Penarikan Baru', "{$nama} mengajukan penarikan {$nominal}.", url('/admin/penarikan'));
    }

    public static function penarikanSelesai(string $nama, string $nominal)
    {
        return self::toAdmin('penarikan', 'Penarikan Selesai', "Penarikan {$nominal} untuk {$nama} selesai.", url('/admin/penarikan'));
    }

    public static function penarikanDitolak(string $nama, string $nominal)
    {
        return self::toAdmin('penarikan', 'Penarikan Ditolak', "Penarikan {$nominal} untuk {$nama} ditolak.", url('/admin/penarikan'));
    }

    public static function tabunganMasuk(string $nama, string $nominal, string $jenisSampah)
    {
        return self::toAdmin('tabungan', 'Setoran Sampah Masuk', "{$nama} menyetor {$jenisSampah} senilai {$nominal}.", url('/admin/tabungan'));
    }

    public static function sampahDitambah(string $namaSampah, string $harga)
    {
        return self::toAdmin('sampah', 'Jenis Sampah Ditambah', "{$namaSampah} ditambahkan {$harga}/kg.", url('/admin/jenis-sampah'));
    }

    public static function sampahDiubah(string $namaSampah, string $hargaLama, string $hargaBaru)
    {
        return self::toAdmin('sampah', 'Harga Sampah Diubah', "{$namaSampah}: {$hargaLama} → {$hargaBaru}/kg.", url('/admin/jenis-sampah'));
    }

    public static function sampahDinonaktifkan(string $namaSampah)
    {
        return self::toAdmin('sampah', 'Sampah Dinonaktifkan', "{$namaSampah} dinonaktifkan.", url('/admin/jenis-sampah'));
    }

    public static function sampahDiaktifkan(string $namaSampah)
    {
        return self::toAdmin('sampah', 'Sampah Diaktifkan', "{$namaSampah} diaktifkan kembali.", url('/admin/jenis-sampah'));
    }

    public static function artikelDibuat(string $judul)
    {
        return self::toAdmin('artikel', 'Artikel Baru', "\"{$judul}\" dipublikasikan.", url('/admin/artikel'));
    }

    public static function artikelDiedit(string $judul)
    {
        return self::toAdmin('artikel', 'Artikel Diedit', "\"{$judul}\" diperbarui.", url('/admin/artikel'));
    }

    public static function artikelDihapus(string $judul)
    {
        return self::toAdmin('artikel', 'Artikel Dihapus', "\"{$judul}\" dihapus.", url('/admin/artikel'));
    }

    public static function loginGagal(string $email)
    {
        return self::toAdmin('auth', 'Login Gagal', "Percobaan login gagal: {$email}.", null);
    }

    // ─── KE NASABAH SPESIFIK ───

    public static function toNasabah(int $userId, string $type, string $title, string $message, ?string $url = null)
    {
        return self::send('nasabah', $type, $title, $message, $url, userId: $userId);
    }

    public static function penarikanDisetujuiNasabah(int $userId, string $nominal)
    {
        return self::toNasabah($userId, 'penarikan', 'Penarikan Disetujui', "Penarikan {$nominal} disetujui.", url('/nasabah/penarikan'));
    }

    public static function penarikanSelesaiNasabah(int $userId, string $nominal, string $tanggal)
    {
        return self::toNasabah($userId, 'penarikan', 'Penarikan Selesai', "Penarikan {$nominal} selesai. Ambil pada {$tanggal}.", url('/nasabah/penarikan'));
    }

    public static function penarikanDitolakNasabah(int $userId, string $nominal, string $alasan)
    {
        return self::toNasabah($userId, 'penarikan', 'Penarikan Ditolak', "Penarikan {$nominal} ditolak. Alasan: {$alasan}.", url('/nasabah/penarikan'));
    }

    public static function tabunganMasukNasabah(int $userId, string $nominal, string $tanggal)
    {
        return self::toNasabah($userId, 'tabungan', 'Tabungan Masuk', "Setoran {$nominal} dicatat pada {$tanggal}.", url('/nasabah/tabungan'));
    }

    public static function akunDiverifikasi(int $userId)
    {
        return self::toNasabah($userId, 'akun', 'Akun Diverifikasi', 'Akun Anda telah diverifikasi. Anda bisa mulai menyetor sampah.', url('/nasabah/dashboard'));
    }

    public static function akunDinonaktifkan(int $userId)
    {
        return self::toNasabah($userId, 'akun', 'Akun Dinonaktifkan', 'Akun Anda dinonaktifkan oleh admin.', null);
    }

    public static function passwordDireset(int $userId)
    {
        return self::toNasabah($userId, 'akun', 'Password Direset', 'Password Anda direset admin. Gunakan password default.', url('/nasabah/login'));
    }
}
