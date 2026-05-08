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
        'password_changed',
        'is_active',
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

    // Relasi ke nasabah
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
