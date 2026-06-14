<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nasabah', function (Blueprint $table) {
            // Ubah tipe enum menjadi string biasa agar kompatibel dengan PostgreSQL
            $table->string('status_akun')->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('nasabah', function (Blueprint $table) {
            $table->string('status_akun')->default('pending')->change();
        });
    }
};
