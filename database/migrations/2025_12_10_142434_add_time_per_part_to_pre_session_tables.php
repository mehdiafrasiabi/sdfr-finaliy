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
        // اضافه کردن تایم به جدول امتحانات
        Schema::table('advising_pre_session_exams', function (Blueprint $table) {
            $table->integer('time_per_part')->default(60)->after('part_count'); // تایم هر پارت به دقیقه
        });

        // اضافه کردن تایم به جدول تکالیف
        Schema::table('advising_pre_session_assignments', function (Blueprint $table) {
            $table->integer('time_per_part')->default(60)->after('part_count'); // تایم هر پارت به دقیقه
        });

        // اضافه کردن تایم به جدول اوقات فراغت
        Schema::table('advising_pre_session_free_times', function (Blueprint $table) {
            $table->integer('time_per_part')->default(60)->after('part_count'); // تایم هر پارت به دقیقه
        });
    }

    public function down(): void
    {
        Schema::table('advising_pre_session_exams', function (Blueprint $table) {
            $table->dropColumn('time_per_part');
        });

        Schema::table('advising_pre_session_assignments', function (Blueprint $table) {
            $table->dropColumn('time_per_part');
        });

        Schema::table('advising_pre_session_free_times', function (Blueprint $table) {
            $table->dropColumn('time_per_part');
        });
    }
};
