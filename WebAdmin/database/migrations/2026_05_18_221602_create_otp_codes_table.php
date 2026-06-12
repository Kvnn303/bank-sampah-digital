<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('otp_codes', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('no_telepon');
            $table->string('otp'); // Menyimpan kode 6 digit
            $table->timestamp('expires_at'); // Batas waktu OTP
            $table->boolean('is_verified')->default(false); // Tandai kalau sudah terpakai
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('otp_codes');
    }
};
