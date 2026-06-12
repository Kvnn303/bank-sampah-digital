<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_harga_sampah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_sampah_id')->constrained('jenis_sampah')->onDelete('cascade');
            $table->decimal('harga_lama', 10, 2);
            $table->decimal('harga_baru', 10, 2);
            $table->text('alasan')->nullable();
            $table->foreignId('diubah_oleh')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_harga_sampah');
    }
};
