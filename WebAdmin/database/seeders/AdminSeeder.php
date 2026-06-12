<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Nasabah;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Buat akun admin
        $user = User::create([
            'name'             => 'Admin Bank Sampah',
            'email'            => 'admin@banksampah.com',
            'password'         => Hash::make('admin123'),
            'role'             => 'admin',
            'password_changed' => true,
        ]);

        echo "✅ Admin berhasil dibuat!\n";
        echo "   Email    : admin@banksampah.com\n";
        echo "   Password : admin123\n";
    }
}
