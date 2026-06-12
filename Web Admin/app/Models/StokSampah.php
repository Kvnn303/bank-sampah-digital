<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

class StokSampah extends Model
{
    protected $table = 'stok_sampah';

    protected $fillable = [
        'jenis_sampah_id',
        'stok_masuk_kg',
        'stok_terjual_kg',
        'stok_tersisa_kg',
        'harga_jual_per_kg',
        'total_pendapatan',
        'nama_pembeli',
        'kontak_pembeli',
        'status',
        'tanggal_masuk',
        'tanggal_jual',
        'keterangan',
        'dicatat_oleh',
        'is_published',
        'is_pres',
        'slug',
        'gambar',
    ];

    protected $casts = [
        'stok_masuk_kg'    => 'decimal:2',
        'stok_terjual_kg' => 'decimal:2',
        'stok_tersisa_kg'  => 'decimal:2',
        'harga_jual_per_kg'=> 'decimal:2',
        'total_pendapatan'  => 'decimal:2',
        'tanggal_masuk'    => 'date',
        'tanggal_jual'     => 'date',
        'is_published'     => 'boolean',
        'is_pres'         => 'boolean',
    ];

    // =============================================
    // RELASI
    // =============================================

    public function jenisSampah()
    {
        return $this->belongsTo(JenisSampah::class);
    }

    public function dicatatOleh()
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

    // =============================================
    // SCOPE - Filtering
    // =============================================

    /** Stok yang dipublikasikan ke publik */
    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    /** Stok yang sudah di-press */
    public function scopeDiPress($query)
    {
        return $query->where('is_pres', true);
    }

    /** Stok yang belum di-press */
    public function scopeBelumDiPress($query)
    {
        return $query->where('is_pres', false);
    }

    /** Stok yang masih tersedia untuk dijual */
    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    /** Stok yang sebagian sudah terjual */
    public function scopeSebagian($query)
    {
        return $query->where('status', 'sebagian');
    }

    /** Stok yang sudah terjual semua */
    public function scopeTerjual($query)
    {
        return $query->where('status', 'terjual');
    }

    /** Filter berdasarkan jenis sampah */
    public function scopeByJenis($query, $jenisId)
    {
        return $query->where('jenis_sampah_id', $jenisId);
    }

    // =============================================
    // ACCESSOR - Format Display
    // =============================================

    /** Berat masuk dengan satuan kg */
    protected function beratMasukDisplay(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->stok_masuk_kg, 2, ',', '.') . ' kg'
        );
    }

    /** Berat terjual dengan satuan kg */
    protected function beratTerjualDisplay(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->stok_terjual_kg, 2, ',', '.') . ' kg'
        );
    }

    /** Berat tersisa dengan satuan kg */
    protected function beratTersisaDisplay(): Attribute
    {
        return Attribute::make(
            get: fn () => number_format($this->stok_tersisa_kg, 2, ',', '.') . ' kg'
        );
    }

    /** Total pendapatan dalam Rupiah */
    protected function totalPendapatanDisplay(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Rp ' . number_format($this->total_pendapatan, 0, ',', '.')
        );
    }

    /** Harga jual per kg dalam Rupiah */
    protected function hargaJualDisplay(): Attribute
    {
        return Attribute::make(
            get: fn () => 'Rp ' . number_format($this->harga_jual_per_kg, 0, ',', '.') . '/kg'
        );
    }

    /** Tanggal masuk lengkap (Indonesia) */
    protected function tanggalMasukDisplay(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tanggal_masuk
                ? \Carbon\Carbon::parse($this->tanggal_masuk)->locale('id')->translatedFormat('d F Y')
                : '-'
        );
    }

    /** Tanggal masuk singkat */
    protected function tanggalMasukShort(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tanggal_masuk
                ? \Carbon\Carbon::parse($this->tanggal_masuk)->format('d/m/Y')
                : '-'
        );
    }

    /** Tanggal jual lengkap (Indonesia) */
    protected function tanggalJualDisplay(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->tanggal_jual
                ? \Carbon\Carbon::parse($this->tanggal_jual)->locale('id')->translatedFormat('d F Y')
                : '-'
        );
    }

    // =============================================
    // BOOT - Auto generate slug
    // =============================================

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($stok) {
            if (empty($stok->slug)) {
                $jenis = $stok->jenisSampah ? $stok->jenisSampah->nama : 'stok';
                $stok->slug = Str::slug($jenis . '-' . $stok->tanggal_masuk);
            }
        });

        static::updating(function ($stok) {
            if ($stok->isDirty('jenis_sampah_id') || $stok->isDirty('tanggal_masuk')) {
                $jenis = $stok->jenisSampah ? $stok->jenisSampah->nama : 'stok';
                $stok->slug = Str::slug($jenis . '-' . $stok->tanggal_masuk);
            }
        });
    }

    // =============================================
    // STATIC HELPERS - Statistik
    // =============================================

    public static function totalMasuk(): float
    {
        return (float) static::sum('stok_masuk_kg');
    }

    public static function totalTerjual(): float
    {
        return (float) static::sum('stok_terjual_kg');
    }

    public static function totalTersisa(): float
    {
        return (float) static::sum('stok_tersisa_kg');
    }

    public static function totalPendapatan(): float
    {
        return (float) static::sum('total_pendapatan');
    }

    public static function statistik(): array
    {
        return [
            'total_masuk_kg'   => static::totalMasuk(),
            'total_terjual_kg' => static::totalTerjual(),
            'total_tersisa_kg' => static::totalTersisa(),
            'total_pendapatan' => static::totalPendapatan(),
            'jumlah_transaksi' => static::count(),
            'published_count'   => static::published()->count(),
            'pres_count'       => static::diPress()->count(),
            'tersedia_count'   => static::tersedia()->count(),
            'terjual_count'   => static::terjual()->count(),
            'sebagian_count'   => static::sebagian()->count(),
        ];
    }
}
