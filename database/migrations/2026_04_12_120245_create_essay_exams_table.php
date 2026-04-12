<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('essay_exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete(); // سازنده (مشاور/پشتیبان)
            $table->foreignId('cc_topic_id')->nullable()->constrained('cc_topics')->nullOnDelete();
            $table->string('title'); // عنوان آزمون
            $table->string('question_pdf_path')->nullable(); // مسیر PDF سوالات
            $table->string('answer_pdf_path')->nullable();   // مسیر PDF پاسخ‌نامه
            $table->decimal('total_score', 5, 2)->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('essay_exams');
    }
};

