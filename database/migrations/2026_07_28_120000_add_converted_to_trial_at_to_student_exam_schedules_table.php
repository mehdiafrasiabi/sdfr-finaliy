<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_exam_schedules', function (Blueprint $table) {
            if (! Schema::hasColumn('student_exam_schedules', 'converted_to_trial_at')) {
                $table->timestamp('converted_to_trial_at')->nullable()->after('exam_program_ended_sms_sent_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('student_exam_schedules', function (Blueprint $table) {
            if (Schema::hasColumn('student_exam_schedules', 'converted_to_trial_at')) {
                $table->dropColumn('converted_to_trial_at');
            }
        });
    }
};
