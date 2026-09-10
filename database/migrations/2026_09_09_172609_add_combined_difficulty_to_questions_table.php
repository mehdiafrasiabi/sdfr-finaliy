<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * افزودن سطح سختی «ترکیبی» (combined) به enum فیلد difficulty جدول questions.
     */
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE `questions` MODIFY `difficulty` ENUM('easy','medium','hard','special','combined') NOT NULL");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            // قبل از باریک‌کردن enum، سوالات ترکیبی را به «ویژه» برمی‌گردانیم تا خطای دیتابیس رخ ندهد.
            DB::statement("UPDATE `questions` SET `difficulty` = 'special' WHERE `difficulty` = 'combined'");
            DB::statement("ALTER TABLE `questions` MODIFY `difficulty` ENUM('easy','medium','hard','special') NOT NULL");
        }
    }
};
