<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stok_sampah', function (Blueprint $table) {
            $table->boolean('is_published')->default(true)->after('keterangan');
            $table->boolean('is_pres')->default(false)->after('is_published');
            $table->string('slug')->nullable()->after('is_pres');
            $table->string('gambar')->nullable()->after('slug');
        });
    }

    public function down(): void
    {
        Schema::table('stok_sampah', function (Blueprint $table) {
            $table->dropColumn(['is_published', 'is_pres', 'slug', 'gambar']);
        });
    }
};
