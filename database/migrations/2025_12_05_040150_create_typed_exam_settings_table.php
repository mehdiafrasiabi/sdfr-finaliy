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
        Schema::create('typed_exam_settings', function (Blueprint $table) {

            $table->id();

            $table->foreignId('typed_exam_id')->constrained()->cascadeOnDelete();

            // زمان نمایش کارنامه: after_exam_end = بعد از پایان آزمون، immediately = به محض پایان توسط کاربر

            $table->enum('result_visibility', ['after_exam_end', 'immediately'])->default('after_exam_end');

            // زمان نمایش پاسخ‌نامه

            $table->enum('answer_key_visibility', ['after_exam_end', 'immediately'])->default('after_exam_end');

            // ترتیب تصادفی: none, questions_only, options_only, both

            $table->enum('randomization_type', ['none', 'questions_only', 'options_only', 'both'])->default('none');

            $table->text('description')->nullable(); // توضیحات آزمون

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('typed_exam_settings');
    }
};
