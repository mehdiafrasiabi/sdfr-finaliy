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
        Schema::create('typed_exam_assignment_times', function (Blueprint $table) {

            $table->id();

            $table->foreignId('assignment_id')->constrained('typed_exam_assignments')->cascadeOnDelete();

            $table->date('start_date'); // تاریخ شروع

            $table->date('end_date'); // تاریخ پایان

            $table->time('start_time'); // ساعت شروع

            $table->time('end_time'); // ساعت پایان

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('typed_exam_assignment_times');
    }
};
