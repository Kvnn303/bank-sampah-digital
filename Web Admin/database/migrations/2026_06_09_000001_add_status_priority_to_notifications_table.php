<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('notifications', 'status')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->enum('status', ['unread', 'read'])->default('unread')->after('is_read');
            });
        }

        if (!Schema::hasColumn('notifications', 'priority')) {
            Schema::table('notifications', function (Blueprint $table) {
                $table->enum('priority', ['low', 'normal', 'high'])->default('normal')->after('status');
            });
        }
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['status', 'priority']);
        });
    }
};
