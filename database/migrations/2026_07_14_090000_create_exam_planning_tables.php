<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_planning_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('grade');
            $table->string('field', 32)->nullable();
            $table->string('term_type', 20)->default('final');
            $table->string('input_mode', 20)->default('student');
            $table->date('activation_starts_at');
            $table->date('activation_ends_at');
            $table->date('exam_starts_at')->nullable();
            $table->date('exam_ends_at')->nullable();
            $table->unsignedTinyInteger('max_daily_study_hours')->default(12);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['grade', 'field', 'is_active'], 'exam_planning_settings_grade_field_active_idx');
            $table->index(['activation_starts_at', 'activation_ends_at'], 'exam_planning_settings_activation_idx');
        });

        Schema::create('exam_planning_setting_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_planning_setting_id')
                ->constrained('exam_planning_settings')
                ->cascadeOnDelete();
            $table->foreignId('cc_subject_id')
                ->constrained('cc_subjects')
                ->cascadeOnDelete();
            $table->date('exam_date');
            $table->timestamps();

            $table->unique(
                ['exam_planning_setting_id', 'cc_subject_id'],
                'exam_planning_setting_days_setting_subject_unique'
            );
            $table->unique(
                ['exam_planning_setting_id', 'exam_date', 'cc_subject_id'],
                'exam_planning_setting_days_setting_date_subject_unique'
            );
        });

        Schema::create('student_exam_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('exam_planning_setting_id')
                ->constrained('exam_planning_settings')
                ->cascadeOnDelete();
            $table->foreignId('weekly_program_id')
                ->nullable()
                ->constrained('weekly_programs')
                ->nullOnDelete();
            $table->string('source_type', 20)->default('student');
            $table->date('exam_starts_at')->nullable();
            $table->date('exam_ends_at')->nullable();
            $table->unsignedTinyInteger('max_daily_study_hours')->default(12);
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('program_built_at')->nullable();
            $table->timestamp('access_expires_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'exam_planning_setting_id'], 'student_exam_schedules_user_setting_unique');
        });

        Schema::create('student_exam_schedule_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_exam_schedule_id')
                ->constrained('student_exam_schedules')
                ->cascadeOnDelete();
            $table->foreignId('cc_subject_id')
                ->constrained('cc_subjects')
                ->cascadeOnDelete();
            $table->date('exam_date');
            $table->timestamps();

            $table->unique(
                ['student_exam_schedule_id', 'cc_subject_id'],
                'student_exam_schedule_days_schedule_subject_unique'
            );
            $table->unique(
                ['student_exam_schedule_id', 'exam_date', 'cc_subject_id'],
                'student_exam_schedule_days_schedule_date_subject_unique'
            );
        });

        Schema::create('student_exam_study_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_exam_schedule_id')
                ->constrained('student_exam_schedules')
                ->cascadeOnDelete();
            $table->string('ratable_type');
            $table->unsignedBigInteger('ratable_id');
            $table->foreignId('cc_subject_id')
                ->nullable()
                ->constrained('cc_subjects')
                ->nullOnDelete();
            $table->unsignedInteger('planned_minutes')->default(0);
            $table->boolean('is_priority_subject')->default(false);
            $table->timestamps();

            $table->unique(
                ['student_exam_schedule_id', 'ratable_type', 'ratable_id'],
                'student_exam_study_allocations_schedule_ratable_unique'
            );
            $table->index(['student_exam_schedule_id', 'cc_subject_id'], 'student_exam_study_allocations_schedule_subject_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_exam_study_allocations');
        Schema::dropIfExists('student_exam_schedule_days');
        Schema::dropIfExists('student_exam_schedules');
        Schema::dropIfExists('exam_planning_setting_days');
        Schema::dropIfExists('exam_planning_settings');
    }
};
