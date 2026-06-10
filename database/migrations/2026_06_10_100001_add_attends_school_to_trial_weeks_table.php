<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * آیا دانش‌آموز در حال حاضر مدرسه می‌رود؟
     * (فارغ‌التحصیل‌ها و کسانی که مدرسه نمی‌روند از برنامه کلاسی معاف‌اند.)
     */
    public function up(): void
    {
        Schema::table('trial_weeks', function (Blueprint $table) {
            $table->boolean('attends_school')->default(true)->after('field');
        });
    }

    public function down(): void
    {
        Schema::table('trial_weeks', function (Blueprint $table) {
            $table->dropColumn('attends_school');
        });
    }
};
