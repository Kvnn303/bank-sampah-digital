<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Drop the foreign key constraint first
        DB::statement('ALTER TABLE notifications DROP FOREIGN KEY notifications_user_id_foreign');

        // Change the column to nullable using raw SQL
        DB::statement('ALTER TABLE notifications MODIFY COLUMN user_id BIGINT UNSIGNED NULL');

        // Re-add the foreign key constraint
        DB::statement('ALTER TABLE notifications ADD CONSTRAINT notifications_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE notifications MODIFY COLUMN user_id BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE notifications DROP FOREIGN KEY notifications_user_id_foreign');
        DB::statement('ALTER TABLE notifications ADD CONSTRAINT notifications_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
    }
};
