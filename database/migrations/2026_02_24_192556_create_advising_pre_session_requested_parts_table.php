<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('advising_pre_session_requested_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pre_session_id')->constrained('advising_pre_sessions')->onDelete('cascade');
            $table->string('subject'); // نام درس
            $table->unsignedBigInteger('cc_subject_id')->nullable(); // آیدی درس از برنامه درسی
            $table->unsignedBigInteger('cc_chapter_id')->nullable(); // آیدی فصل (اختیاری)
            $table->text('description')->nullable(); // توضیحات (اختیاری)
            $table->integer('part_count')->default(1); // تعداد پارت
            $table->integer('time_per_part')->default(60); // زمان هر پارت به دقیقه
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advising_pre_session_requested_parts');
    }
};
