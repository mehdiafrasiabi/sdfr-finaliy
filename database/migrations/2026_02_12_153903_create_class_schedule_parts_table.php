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
        Schema::create('class_schedule_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_schedule_id')->constrained('class_schedules')->onDelete('cascade');
            $table->tinyInteger('day_of_week'); // 0=شنبه تا 6=جمعه
            $table->tinyInteger('part_order'); // 1 تا 5
            $table->foreignId('cc_subject_id')->constrained('cc_subjects')->onDelete('cascade');
            $table->string('lesson_name'); // نام درس (کش شده)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('class_schedule_parts');
    }
};
