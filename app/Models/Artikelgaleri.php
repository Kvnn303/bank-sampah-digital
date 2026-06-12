<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArtikelGaleri extends Model
{
    protected $table = 'artikel_galeri';

    protected $fillable = [
        'artikel_id',
        'gambar',
        'keterangan',
        'urutan',
    ];

    public function artikel()
    {
        return $this->belongsTo(Artikel::class);
    }
}
