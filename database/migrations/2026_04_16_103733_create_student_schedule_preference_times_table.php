<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('student_schedule_preference_times', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_schedule_preference_id');
            $table->foreign('student_schedule_preference_id', 'sspt_pref_id_foreign')
                ->references('id')
                ->on('student_schedule_preferences')
                ->cascadeOnDelete();
            // 0=شنبه .. 6=جمعه
            $table->unsignedTinyInteger('day_of_week');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();

            $table->index(['student_schedule_preference_id', 'day_of_week'], 'sspt_pref_day_idx');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_schedule_preference_times');
    }
};

