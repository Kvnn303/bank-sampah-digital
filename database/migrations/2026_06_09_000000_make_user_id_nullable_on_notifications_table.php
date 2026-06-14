<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // 1. Hapus foreign key menggunakan fungsi bawaan Laravel
            $table->dropForeign(['user_id']);

            // 2. Ubah kolom menjadi nullable menggunakan ->change()
            $table->unsignedBigInteger('user_id')->nullable()->change();

            // 3. Pasang kembali foreign key-nya
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            // 1. Hapus foreign key
            $table->dropForeign(['user_id']);

            // 2. Kembalikan kolom menjadi NOT NULL (hapus nullable)
            $table->unsignedBigInteger('user_id')->nullable(false)->change();

            // 3. Pasang kembali foreign key-nya
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
