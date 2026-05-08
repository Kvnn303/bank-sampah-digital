<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_sampah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jenis_sampah_id')->constrained('jenis_sampah')->onDelete('cascade');
            $table->decimal('stok_masuk_kg', 10, 2)->default(0);
            $table->decimal('stok_terjual_kg', 10, 2)->default(0);
            $table->decimal('stok_tersisa_kg', 10, 2)->default(0);
            $table->decimal('harga_jual_per_kg', 10, 2)->default(0);
            $table->decimal('total_pendapatan', 12, 2)->default(0);
            $table->string('nama_pembeli')->nullable();
            $table->string('kontak_pembeli')->nullable();
            $table->enum('status', ['tersedia', 'terjual', 'sebagian'])->default('tersedia');
            $table->date('tanggal_masuk');
            $table->date('tanggal_jual')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('dicatat_oleh')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_sampah');
    }
};
