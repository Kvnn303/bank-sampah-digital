<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    // ─── KONSTANTA TYPE ────────────────────────────────────────
    public const TYPE_TRANSASKSI = 'transaksi';
    public const TYPE_HARGA      = 'harga';
    public const TYPE_ARTIKEL    = 'artikel';
    public const TYPE_NASABAH   = 'nasabah';
    public const TYPE_PENARIKAN = 'penarikan';
    public const TYPE_TABUNGAN  = 'tabungan';
    public const TYPE_SAMPAH    = 'sampah';
    public const TYPE_STOK      = 'stok';
    public const TYPE_AUTH      = 'auth';
    public const TYPE_AKUN      = 'akun';
    public const TYPE_WARNING   = 'warning';
    public const TYPE_SUCCESS   = 'success';
    public const TYPE_DANGER    = 'danger';
    public const TYPE_INFO      = 'info';
    public const TYPE_DEFAULT   = 'default';

    // ─── KONSTANTA STATUS (mobile banking style) ────────────────
    public const STATUS_UNREAD = 'unread';
    public const STATUS_READ   = 'read';

    // ─── KONSTANTA PRIORITY ────────────────────────────────────
    public const PRIORITY_LOW    = 'low';
    public const PRIORITY_NORMAL = 'normal';
    public const PRIORITY_HIGH   = 'high';

    protected $fillable = [
        'user_id',
        'target_role',
        'type',
        'title',
        'message',
        'url',
        'is_read',
        'status',
        'priority',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // ─── RELASI ────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // ─── METHOD INSTANCE ───────────────────────────────────────

    /**
     * Tandai notifikasi ini sudah dibaca.
     */
    public function markAsRead(): void
    {
        if (! $this->is_read) {
            $this->update([
                'is_read' => true,
                'status'  => self::STATUS_READ,
                'read_at' => now(),
            ]);
        }
    }

    public function markAllAsRead(int $userId): int
    {
        return self::where('user_id', $userId)
                   ->where('is_read', false)
                   ->update(['is_read' => true, 'status' => self::STATUS_READ, 'read_at' => now()]);
    }

    public static function unreadCount(int $userId): int
    {
        return self::where('user_id', $userId)->where('is_read', false)->count();
    }

    // ─── SCOPE FILTER ─────────────────────────────────────────

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForAdmin($query, ?int $adminId = null)
    {
        return $query->where('target_role', 'admin')
                      ->where(function ($q) use ($adminId) {
                          $q->whereNull('user_id')
                            ->when($adminId, fn($q2) => $q2->orWhere('user_id', $adminId));
                      });
    }

    public function scopeForNasabah($query, int $nasabahId)
    {
        return $query->where('target_role', 'nasabah')
                      ->where('user_id', $nasabahId);
    }
public function getIconBgClass(): string
{
    return match ($this->type) {
        self::TYPE_AUTH      => 'bg-danger-soft text-danger',
        self::TYPE_AKUN      => 'bg-purple-soft text-purple',
        self::TYPE_TABUNGAN  => 'bg-success-soft text-success',
        self::TYPE_NASABAH   => 'bg-primary-soft text-primary',
        self::TYPE_PENARIKAN => 'bg-success-soft text-success',
        self::TYPE_SAMPAH    => 'bg-warning-soft text-warning',
        self::TYPE_STOK      => 'bg-info-soft text-info',
        self::TYPE_ARTIKEL   => 'bg-purple-soft text-purple',
        self::TYPE_HARGA     => 'bg-warning-soft text-warning',
        self::TYPE_TRANSASKSI=> 'bg-primary-soft text-primary',
        self::TYPE_SUCCESS   => 'bg-success-soft text-success',
        self::TYPE_DANGER    => 'bg-danger-soft text-danger',
        self::TYPE_WARNING   => 'bg-warning-soft text-warning',
        self::TYPE_INFO      => 'bg-info-soft text-info',
        default              => 'bg-light text-secondary',
    };
}
    
    public function scopeForRole($query, string $role)
    {
        return $query->where('target_role', $role);
    }

    public function scopeHighestPriority($query)
    {
        return $query->orderByRaw("FIELD(priority, 'high', 'normal', 'low')");
    }

    // ─── ACCESSOR: icon & warna (cocok untuk mobile banking UI) ─

    public function getIconName(): string
    {
        return match ($this->type) {
            self::TYPE_NASABAH   => 'users',
            self::TYPE_PENARIKAN => 'currency',
            self::TYPE_TABUNGAN  => 'wallet',
            self::TYPE_SAMPAH    => 'recycle',
            self::TYPE_ARTIKEL   => 'article',
            self::TYPE_STOK      => 'package',
            self::TYPE_AUTH      => 'shield',
            self::TYPE_AKUN      => 'user',
            self::TYPE_HARGA     => 'tag',
            self::TYPE_TRANSASKSI => 'repeat',
            self::TYPE_SUCCESS   => 'check-circle',
            self::TYPE_DANGER    => 'alert-circle',
            self::TYPE_WARNING   => 'alert-triangle',
            default              => 'bell',
        };
    }

    public function getColorClass(): string
    {
        return match ($this->type) {
            self::TYPE_PENARIKAN => '#16a34a',
            self::TYPE_TABUNGAN  => '#10b981',
            self::TYPE_SUCCESS  => '#16a34a',
            self::TYPE_DANGER    => '#dc2626',
            self::TYPE_WARNING   => '#d97706',
            self::TYPE_HARGA     => '#d97706',
            self::TYPE_ARTIKEL   => '#7c3aed',
            self::TYPE_NASABAH   => '#2563eb',
            self::TYPE_SAMPAH    => '#d97706',
            self::TYPE_STOK      => '#0891b2',
            self::TYPE_TRANSASKSI => '#2563eb',
            default              => '#6366f1',
        };
    }
}
