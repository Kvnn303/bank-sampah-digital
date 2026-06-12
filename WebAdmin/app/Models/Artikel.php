<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Artikel extends Model
{
    use HasFactory;

    protected $table = 'artikels';

    protected $fillable = [
        'judul',
        'slug',
        'konten',
        'kategori',
        'gambar',
        'is_published',
        'author_id',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    // Relasi: Artikel ditulis oleh User (Author)
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function galeri()
    {
        // Menggunakan hasMany karena 1 Artikel punya banyak galeri
        return $this->hasMany(ArtikelGaleri::class, 'artikel_id');
    }


    protected static function boot()
    {
        parent::boot();

        static::creating(function ($artikel) {
            if (empty($artikel->slug)) {
                $artikel->slug = Str::slug($artikel->judul);
            }
        });

        static::updating(function ($artikel) {
            if ($artikel->isDirty('judul')) {
                $artikel->slug = Str::slug($artikel->judul);
            }
        });
    }
}
