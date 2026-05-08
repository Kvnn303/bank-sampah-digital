<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nasabah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->string('nama_lengkap');
            $table->string('alamat')->nullable();
            $table->string('no_telepon')->nullable();
            $table->string('no_ktp')->nullable()->unique();
            $table->string('foto_ktp')->nullable();
            $table->string('foto')->nullable();
            $table->enum('status_akun', ['pending', 'verified', 'active'])->default('pending');
            $table->enum('sumber_daftar', ['mandiri', 'admin'])->default('mandiri');
            $table->boolean('password_changed')->default(false);
            $table->date('tanggal_bergabung')->nullable();
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nasabah');
    }
};
