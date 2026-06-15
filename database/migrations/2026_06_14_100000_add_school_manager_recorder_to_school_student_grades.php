<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * امکان ثبت نمره توسط مدیر مدرسه (گارد school-manager) علاوه بر ادمین/پشتیبان.
     */
    public function up(): void
    {
        Schema::table('school_student_grades', function (Blueprint $table) {
            $table->foreignId('recorded_by_school_manager_id')->nullable()
                ->after('recorded_by_admin_id')
                ->constrained('school_managers')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('school_student_grades', function (Blueprint $table) {
            $table->dropConstrainedForeignId('recorded_by_school_manager_id');
        });
    }
};
