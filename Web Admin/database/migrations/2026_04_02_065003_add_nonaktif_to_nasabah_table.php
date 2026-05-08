<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('nasabah', function (Blueprint $table) {
            $table->enum('status_akun', ['pending', 'verified', 'active', 'nonaktif'])
                  ->default('pending')
                  ->change();
        });
    }

    public function down(): void
    {
        Schema::table('nasabah', function (Blueprint $table) {
            $table->enum('status_akun', ['pending', 'verified', 'active'])
                  ->default('pending')
                  ->change();
        });
    }
};