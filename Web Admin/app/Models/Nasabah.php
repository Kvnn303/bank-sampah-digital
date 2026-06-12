<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nasabah extends Model
{
    protected $table = 'nasabah';

    protected $fillable = [
        'user_id',
        'nama_lengkap',
        'alamat',
        'no_telepon',
        'no_ktp',
        'foto_ktp',
        'foto',
        'status_akun',
        'sumber_daftar',
        'password_changed',
        'tanggal_bergabung',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal_bergabung' => 'date',
        'password_changed'  => 'boolean',
    ];

    // Relasi ke user (akun login)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke tabungan
    public function tabungan()
    {
        return $this->hasMany(Tabungan::class);
    }

    // Relasi ke penarikan
    public function penarikan()
    {
        return $this->hasMany(Penarikan::class);
    }

    // Konstanta status penarikan yang mengurangi saldo
    public const PENARIKAN_AKTIF_STATUS = ['selesai'];

    // Core accessor: hitung saldo real-time, pakai withSum jika ada, fallback ke query
    public function getSaldoRealtimeAttribute(): float
    {
        // Jika sudah di-load via withSum, gunakan langsung (0 query tambahan)
        if (array_key_exists('tabungan_sum_nilai_rupiah', $this->attributes)) {
            $totalTabungan = (float) $this->attributes['tabungan_sum_nilai_rupiah'];
            $totalPenarikan = (float) ($this->attributes['penarikan_aktif_sum_nominal'] ?? 0);

            return $totalTabungan - $totalPenarikan;
        }

        // Fallback: hitung langsung (detail page, API, atau tanpa withSum)
        $totalTabungan = $this->tabungan()->sum('nilai_rupiah');
        $totalPenarikan = $this->penarikan()
            ->where('status', 'selesai')
            ->sum('nominal');

        return $totalTabungan - $totalPenarikan;
    }

    // Alias backward-compat
    public function getSaldoAktifAttribute(): float
    {
        return $this->getSaldoRealtimeAttribute();
    }

    // Hitung total sampah terkumpul
    public function getTotalSampahAttribute(): float
    {
        $totalSampah = (float) ($this->attributes['tabungan_sum_berat_kg'] ?? 0);

        if ($totalSampah === 0.0 && !array_key_exists('tabungan_sum_berat_kg', $this->attributes)) {
            $totalSampah = (float) $this->tabungan()->sum('berat_kg');
        }

        return $totalSampah;
    }
}
