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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('level', ['easy', 'medium', 'hard', 'comprehensive'])->default('medium'); // استفاده از enum
            $table->unsignedInteger('number_of_questions')->default(0); // فیلد جدید برای تعداد سوالات
            $table->boolean('is_active')->default(false);
            $table->string('pdf_path'); // مسیر فایل سوالات
            $table->string('solution_pdf_path')->nullable(); // فیلد جدید برای مسیر پاسخنامه تشریحی
            $table->unsignedInteger('duration_minutes');
            $table->foreignId('admin_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
