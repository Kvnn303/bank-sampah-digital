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

    // Hitung total saldo otomatis
    public function getSaldoAttribute(): float
    {
        $totalTabungan = $this->tabungan()->sum('nilai_rupiah');
        $totalPenarikan = $this->penarikan()
            ->whereIn('status', ['selesai', 'diproses'])
            ->sum('nominal');

        return $totalTabungan - $totalPenarikan;
    }

    // Hitung total sampah terkumpul
    public function getTotalSampahAttribute(): float
    {
        return $this->tabungan()->sum('berat_kg');
    }
}
