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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained();
            $table->foreignId('admin_id')->constrained();
            // تعداد پارت موظفی و انجام شده
            $table->unsignedTinyInteger('required_parts')->default(0); // 1 تا 10
            $table->unsignedTinyInteger('done_parts')->default(0); // 1 تا 10

            // تست‌ها
            $table->unsignedInteger('required_tests')->default(0)->nullable();
            $table->unsignedInteger('done_tests')->default(0)->nullable();
            // ساعات گوشی
            $table->unsignedTinyInteger('phone_study_hours')->default(0); // 1 تا 24
            $table->unsignedTinyInteger('phone_nonstudy_hours')->default(0); // 1 تا 24

            $table->string('report_file')->nullable();
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('complacent')->default(0);
            $table->enum('status', ['pending','completed','rejected'])->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
