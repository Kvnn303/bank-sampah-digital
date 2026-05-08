<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penarikan extends Model
{
    protected $table = 'penarikan';

    protected $fillable = [
        'nasabah_id',
        'nominal',
        'status',
        'alasan_penolakan',
        'diproses_oleh',
        'tanggal_proses',
        'tanggal_ambil',
        'catatan_nasabah',
        'catatan_admin',
    ];

    protected $casts = [
        'nominal'        => 'decimal:2',
        'tanggal_proses' => 'datetime',
        'tanggal_ambil'  => 'date',
    ];

    // Relasi ke nasabah
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    // Relasi ke admin yang proses
    public function diprosesoleh()
    {
        return $this->belongsTo(User::class, 'diproses_oleh');
    }

    // Cek apakah masih pending
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    // Cek apakah sudah selesai
    public function isSelesai(): bool
    {
        return $this->status === 'selesai';
    }
}
