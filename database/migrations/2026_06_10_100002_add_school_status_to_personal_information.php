<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * وضعیت تحصیلی دانش‌آموز در زمان ثبت‌نام:
     *  - is_graduate: فارغ‌التحصیل است (مدرسه‌اش تمام شده)
     *  - attends_school: در حال حاضر مدرسه می‌رود یا نه
     */
    public function up(): void
    {
        Schema::table('personal_information', function (Blueprint $table) {
            $table->boolean('is_graduate')->default(false)->after('grade');
            $table->boolean('attends_school')->default(true)->after('is_graduate');
        });
    }

    public function down(): void
    {
        Schema::table('personal_information', function (Blueprint $table) {
            $table->dropColumn(['is_graduate', 'attends_school']);
        });
    }
};
