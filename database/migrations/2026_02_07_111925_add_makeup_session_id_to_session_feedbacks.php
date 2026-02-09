<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // مرحله 1: حذف foreign key constraint
        Schema::table('session_feedbacks', function (Blueprint $table) {
            $table->dropForeign(['sps_id']);
        });

        // مرحله 2: حذف unique index
        Schema::table('session_feedbacks', function (Blueprint $table) {
            $table->dropUnique(['sps_id']);
        });

        // مرحله 3: اضافه کردن ستون‌های جدید و تغییر sps_id
        Schema::table('session_feedbacks', function (Blueprint $table) {
            // تغییر sps_id به nullable
            $table->unsignedBigInteger('sps_id')->nullable()->change();

            // اضافه کردن ستون makeup_session_id
            $table->foreignId('makeup_session_id')
                ->nullable()
                ->after('sps_id')
                ->constrained('makeup_sessions')
                ->cascadeOnDelete();
        });

        // مرحله 4: بازگردانی foreign key برای sps_id
        Schema::table('session_feedbacks', function (Blueprint $table) {
            $table->foreign('sps_id')
                ->references('id')
                ->on('study_part_sessions')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        // حذف foreign key makeup_session_id
        Schema::table('session_feedbacks', function (Blueprint $table) {
            $table->dropForeign(['makeup_session_id']);
            $table->dropColumn('makeup_session_id');
        });

        // حذف foreign key sps_id
        Schema::table('session_feedbacks', function (Blueprint $table) {
            $table->dropForeign(['sps_id']);
        });

        // بازگردانی unique و foreign key اصلی
        Schema::table('session_feedbacks', function (Blueprint $table) {
            $table->unique('sps_id');
            $table->foreign('sps_id')
                ->references('id')
                ->on('study_part_sessions')
                ->cascadeOnDelete();
        });
    }
};
