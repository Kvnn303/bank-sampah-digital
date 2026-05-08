<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatHargaSampah extends Model
{
    protected $table = 'riwayat_harga_sampah';

    protected $fillable = [
        'jenis_sampah_id',
        'harga_lama',
        'harga_baru',
        'alasan',
        'diubah_oleh',
    ];

    protected $casts = [
        'harga_lama' => 'decimal:2',
        'harga_baru' => 'decimal:2',
    ];

    // Relasi ke jenis sampah
    public function jenisSampah()
    {
        return $this->belongsTo(JenisSampah::class);
    }

    // Relasi ke admin yang ubah harga
    public function diubahOleh()
    {
        return $this->belongsTo(User::class, 'diubah_oleh');
    }
}
