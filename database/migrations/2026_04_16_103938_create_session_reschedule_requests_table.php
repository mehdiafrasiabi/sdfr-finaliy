<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('session_reschedule_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('advisor_id')->nullable()
                ->constrained('admins')->nullOnDelete();

            // نوع: exception (یک‌بار مصرف) / permanent (تغییر دائمی برنامه هفتگی)
            $table->enum('type', ['exception', 'permanent']);

            // برای نوع exception
            $table->foreignId('original_session_id')->nullable()
                ->constrained('advising_sessions')->nullOnDelete();

            // برای نوع permanent (اسلات فعلی در برنامه هفتگی)
            $table->unsignedTinyInteger('original_day')->nullable();
            $table->time('original_time')->nullable();

            // پیشنهاد دانش‌آموز
            $table->unsignedTinyInteger('student_proposed_day')->nullable();
            $table->time('student_proposed_time')->nullable();
            $table->text('student_description')->nullable();

            // اسلات‌های خالی پیشنهادی مدیر آموزشی (JSON آرایه‌ای از {day, start, end})
            $table->json('manager_available_slots')->nullable();

            // پیشنهاد مشاور
            $table->unsignedTinyInteger('consultant_proposed_day')->nullable();
            $table->time('consultant_proposed_time')->nullable();
            $table->text('consultant_notes')->nullable();

            // انتخاب نهایی دانش‌آموز
            $table->unsignedTinyInteger('student_selected_day')->nullable();
            $table->time('student_selected_time')->nullable();

            // وضعیت‌های جریان کار
            $table->enum('status', [
                'pending_manager_review',       // منتظر مدیر آموزشی
                'awaiting_consultant_proposal', // مدیر اسلات‌های خالی را فرستاد، منتظر پیشنهاد مشاور
                'awaiting_student_choice',      // منتظر انتخاب نهایی دانش‌آموز
                'awaiting_consultant_confirm',  // منتظر تایید نهایی مشاور
                'awaiting_manager_final',       // منتظر تایید نهایی مدیر آموزشی
                'approved',                     // تایید شد و اعمال شد
                'rejected',                     // رد شد
                'consultant_change_requested',  // مشاور ظرفیت نداشت، درخواست تعویض مشاور
            ])->default('pending_manager_review');

            $table->text('rejection_reason')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'status'], 'srr_student_status_idx');
            $table->index(['advisor_id', 'status'], 'srr_advisor_status_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_reschedule_requests');
    }
};

