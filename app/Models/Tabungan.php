<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use App\Models\User;

class Tabungan extends Model
{
    protected $table = 'tabungan';

    protected $fillable = [
        'nasabah_id',
        'admin_id',
        'jenis_sampah_id',
        'berat_kg',
        'harga_per_kg_saat_itu',
        'nilai_rupiah',
        'tanggal_setor',
        'catatan',
    ];

    protected $casts = [
        'berat_kg'             => 'decimal:2',
        'harga_per_kg_saat_itu'=> 'decimal:2',
        'nilai_rupiah'         => 'decimal:2',
        'tanggal_setor'        => 'date',
    ];

    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function jenisSampah()
    {
        return $this->belongsTo(JenisSampah::class);
    }

    protected function nilaiRupiahDisplay(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Rp ' . number_format($this->nilai_rupiah, 0, ',', '.')
        );
    }

    // Digunakan di Blade sebagai: $tabungan->beratDisplay
    protected function beratDisplay(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->berat_kg, 2, ',', '.') . ' Kg'
        );
    }

    // Digunakan di Blade sebagai: $tabungan->tanggalDisplay
    protected function tanggalDisplay(): Attribute
    {
        return Attribute::make(
            get: fn () => \Carbon\Carbon::parse($this->tanggal_setor)->locale('id')->translatedFormat('d F Y')
        );
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tabungan) {
            // Otomatis hitung nilai jika belum diisi manual
            if (empty($tabungan->nilai_rupiah)) {
                $tabungan->nilai_rupiah = $tabungan->berat_kg * $tabungan->harga_per_kg_saat_itu;
            }
        });
    }
}
