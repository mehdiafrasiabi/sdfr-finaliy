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

        // جدول پرسش و پاسخ کلاسی
        Schema::create('advising_pre_session_qas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pre_session_id')->constrained('advising_pre_sessions')->onDelete('cascade');
            $table->string('subject'); // نام درس
            $table->integer('part_count'); // تعداد پارت
            $table->integer('time_per_part')->default(60); // تایم هر پارت به دقیقه
            $table->date('qa_date'); // تاریخ پرسش و پاسخ
            $table->timestamps();
        });

        // جدول متفرقه
        Schema::create('advising_pre_session_misc', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pre_session_id')->constrained('advising_pre_sessions')->onDelete('cascade');
            $table->text('description'); // توضیحات
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('advising_pre_session_qas');
        Schema::dropIfExists('advising_pre_session_misc');
    }
};
