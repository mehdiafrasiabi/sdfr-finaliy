<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * گسترش enum پرداخت برای پشتیبانی از وضعیت «ناموفق» (failed).
     */
    public function up(): void
    {
        if (! Schema::hasTable('payments')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            DB::statement("
                ALTER TABLE payments
                MODIFY COLUMN `status`
                ENUM('pending','completed','cancelled','failed') NOT NULL DEFAULT 'pending'
            ");
        }
        // در غیر MySQL: ستون از قبل string است یا check constraint سفارشی نیاز دارد —
        // در این پروژه فقط MySQL استفاده می‌شود.
    }

    public function down(): void
    {
        if (! Schema::hasTable('payments')) {
            return;
        }

        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql' || $driver === 'mariadb') {
            // failed → cancelled قبل از کاهش enum
            DB::table('payments')->where('status', 'failed')->update(['status' => 'cancelled']);

            DB::statement("
                ALTER TABLE payments
                MODIFY COLUMN `status`
                ENUM('pending','completed','cancelled') NOT NULL DEFAULT 'pending'
            ");
        }
    }
};
