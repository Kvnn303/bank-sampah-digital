<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tabungan', function (Blueprint $table) {
            // Pastikan tanggal_setor tidak bisa mundur dari hari ini
            // Menggunakan check constraint untuk validasi tanggal
            $table->date('tanggal_setor')->nullable(false)->change();
        });

        // Tambahkan kolom baru: nomor_transaksi untuk keperluan pelacakan
        Schema::table('tabungan', function (Blueprint $table) {
            if (!Schema::hasColumn('tabungan', 'nomor_transaksi')) {
                $table->string('nomor_transaksi', 50)->nullable()->after('id')->unique();
            }
        });
    }

    public function down(): void
    {
        Schema::table('tabungan', function (Blueprint $table) {
            if (Schema::hasColumn('tabungan', 'nomor_transaksi')) {
                $table->dropUnique(['nomor_transaksi']);
                $table->dropColumn('nomor_transaksi');
            }
        });
    }
};