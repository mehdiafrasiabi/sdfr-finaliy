<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // بازخورد جلسات مطالعه
        Schema::create('session_feedbacks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('sps_id')->constrained('study_part_sessions')->cascadeOnDelete();
            $table->tinyInteger('rating')->unsigned();
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->unique('sps_id');
            $table->index(['student_id', 'rating']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('session_feedbacks');
    }
};
