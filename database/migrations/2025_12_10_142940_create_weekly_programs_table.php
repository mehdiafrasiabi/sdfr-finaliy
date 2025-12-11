<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void

    {

        // جدول برنامه هفتگی

        Schema::create('weekly_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->onDelete('cascade');
            $table->foreignId('advisor_id')->nullable()->constrained('admins')->onDelete('set null');
            $table->foreignId('advising_session_id')->nullable()->constrained('advising_sessions')->onDelete('set null');
            $table->date('start_date'); // تاریخ شروع هفته
            $table->date('end_date'); // تاریخ پایان هفته
            $table->string('advisor_name')->nullable(); // نام مشاور
            $table->string('supporter_name')->nullable(); // نام پشتیبان
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('weekly_programs');
    }
};
