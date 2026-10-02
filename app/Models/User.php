<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'foto',
        'password',
        'role',
        'login_username',
        'password_changed',
        'is_active',
        // Push notification
        'expo_push_token',
        // Security tracking
        'last_login_at',
        'last_login_ip',
        'failed_login_count',
        'locked_until',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'password_changed' => 'boolean',
        'is_active' => 'boolean',
    ];

    // ─── RELASI: NOTIFIKASI ───────────────────────────────────

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    // ─── RELASI: NASABAH ───────────────────────────────────────

    public function nasabah()
    {
        return $this->hasOne(Nasabah::class);
    }

    // Cek role
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isNasabah(): bool
    {
        return $this->role === 'nasabah';
    }
}
