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
        Schema::create('advising_pre_session_free_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pre_session_id')->constrained('advising_pre_sessions')->onDelete('cascade');
            $table->string('subject'); // نام درس
            $table->integer('part_count'); // تعداد پارت
            $table->date('exam_date'); // تاریخ آزمون
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advising_pre_session_free_times');
    }
};
