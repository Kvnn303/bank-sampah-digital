<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('penarikan', function (Blueprint $table) {
            // Tambahkan kolom bukti_transfer setelah catatan_admin
            $table->string('bukti_transfer')->nullable()->after('catatan_admin');
        });
    }

    public function down()
    {
        Schema::table('penarikan', function (Blueprint $table) {
            $table->dropColumn('bukti_transfer');
        });
    }
};
