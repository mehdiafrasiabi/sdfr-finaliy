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
        Schema::create('report_student_studies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('admins')->onDelete('cascade');     // کسی که فایل رو فرستاده
            $table->foreignId('receiver_id')->constrained('admins')->onDelete('cascade');   // سوپرادمین گیرنده


            $table->string('file_path');   // مسیر فایل
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('report_student_studies');
    }
};
