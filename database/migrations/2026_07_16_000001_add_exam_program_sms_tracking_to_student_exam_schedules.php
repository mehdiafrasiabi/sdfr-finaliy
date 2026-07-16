<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('student_exam_schedules', function (Blueprint $table) {
            $table->timestamp('exam_program_started_sms_sent_at')->nullable()->after('access_expires_at');
            $table->timestamp('exam_program_ended_sms_sent_at')->nullable()->after('exam_program_started_sms_sent_at');
        });
    }

    public function down(): void
    {
        Schema::table('student_exam_schedules', function (Blueprint $table) {
            $table->dropColumn([
                'exam_program_started_sms_sent_at',
                'exam_program_ended_sms_sent_at',
            ]);
        });
    }
};
