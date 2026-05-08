<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->enum('target_role', ['admin', 'nasabah'])->default('admin')->after('type');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete()->after('target_role');
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn(['target_role', 'user_id']);
        });
    }
};
