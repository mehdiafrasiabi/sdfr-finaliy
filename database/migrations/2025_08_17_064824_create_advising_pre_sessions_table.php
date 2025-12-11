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
        Schema::create('advising_pre_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advising_session_id')->constrained('advising_sessions')->onDelete('cascade');
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->string('title');
            $table->enum('status', ['pending', 'completed'])->default('pending'); // وضعیت پر کردن توسط دانش آموز
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('advising_pre_sessions');
    }
};
