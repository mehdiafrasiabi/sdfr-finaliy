<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trial_weeks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->tinyInteger('grade'); // 9, 10, 11, 12
            $table->string('field', 20)->nullable(); // math|experimental|human  — null for grade 9
            $table->string('father_mobile', 20);
            $table->string('mother_mobile', 20);
            $table->foreignId('supporter_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->foreignId('advising_session_id')->nullable()->constrained('advising_sessions')->nullOnDelete();
            $table->tinyInteger('daily_study_hours')->nullable();

            // pending → supporter_assigned → classification_done → pre_session_done → program_built
            $table->string('status', 30)->default('pending');

            $table->timestamp('expires_at')->nullable();
            $table->timestamp('supporter_assigned_at')->nullable();
            $table->timestamp('classification_locked_at')->nullable();
            $table->timestamp('pre_session_completed_at')->nullable();
            $table->timestamp('program_built_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trial_weeks');
    }
};
