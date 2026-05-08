<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenisSampah extends Model
{
    protected $table = 'jenis_sampah';

    protected $fillable = [
        'nama',
        'kategori',
        'harga_per_kg',
        'keterangan',
        'is_active',
    ];

    protected $casts = [
        'harga_per_kg' => 'decimal:2',
        'is_active'    => 'boolean',
    ];

    // Relasi ke tabungan
    public function tabungan()
    {
        return $this->hasMany(Tabungan::class);
    }

    // Relasi ke riwayat harga
    public function riwayatHarga()
    {
        return $this->hasMany(RiwayatHargaSampah::class);
    }

    // Ambil harga terakhir
    public function hargaTerakhir()
    {
        return $this->hasOne(RiwayatHargaSampah::class)->latestOfMany();
    }
}
