<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_schedule_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            // مشاور انتخاب شده توسط مدیر آموزشی بعد از تایید
            $table->foreignId('assigned_advisor_id')->nullable()
                ->constrained('admins')->nullOnDelete();
            $table->enum('status', ['pending', 'approved', 'replaced'])->default('pending');
            // دوره‌ی سالانه (سال میلادی)
            $table->unsignedSmallInteger('year_period');
            // شماره تغییر در سال: 0 ثبت اولیه، 1 و 2 تغییرات بعدی
            $table->unsignedTinyInteger('change_index')->default(0);
            $table->text('student_notes')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'status']);
            $table->index(['student_id', 'year_period']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_schedule_preferences');
    }
};

