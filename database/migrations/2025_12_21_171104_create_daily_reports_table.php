<?php


use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;


return new class extends Migration {

    public function up(): void
    {
        Schema::create('daily_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('admin_id')->constrained()->onDelete('cascade');
            $table->foreignId('session_id')->constrained('advising_sessions')->onDelete('cascade');
            $table->foreignId('weekly_program_id')->constrained()->onDelete('cascade');
            $table->date('report_date');
            $table->tinyInteger('day_of_week');
            $table->tinyInteger('phone_hours')->default(0);
            $table->text('description')->nullable();
            $table->tinyInteger('rating')->default(3);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('advisor_comment')->nullable();
            $table->timestamp('advisor_commented_at')->nullable();
            $table->text('student_reply')->nullable();
            $table->timestamp('student_replied_at')->nullable();
            $table->boolean('is_compensatory')->default(false);
            $table->timestamps();

            $table->unique(['student_id', 'report_date', 'is_compensatory']);
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('daily_reports');
    }
};
