<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('essay_exam_answer_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')
                ->constrained('essay_exam_attempts')
                ->cascadeOnDelete();
            $table->string('file_path'); // مسیر فایل webp در public_html
            $table->unsignedInteger('file_size'); // اندازه فایل به بایت
            $table->unsignedTinyInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('essay_exam_answer_uploads');
    }
};

