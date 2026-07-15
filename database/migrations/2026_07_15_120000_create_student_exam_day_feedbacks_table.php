<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_exam_day_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('student_exam_schedule_id')
                ->constrained('student_exam_schedules')
                ->cascadeOnDelete();
            $table->date('exam_date');
            $table->string('difficulty', 20);
            $table->text('note')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            $table->unique(
                ['user_id', 'student_exam_schedule_id', 'exam_date'],
                'student_exam_day_feedbacks_user_schedule_date_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_exam_day_feedbacks');
    }
};
