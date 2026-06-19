<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * هدف‌گذاری ثبت‌نام جذب تلفنی که مدیر آموزشی تعیین می‌کند.
 * admin_id خالی = هدف کل تیم؛ مقداردار = هدف یک مشاور خاص.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_goals', function (Blueprint $table) {
            $table->id();

            $table->foreignId('admin_id')->nullable()->constrained('admins')->cascadeOnDelete();
            $table->unsignedInteger('target_count');
            $table->date('goal_date');
            $table->foreignId('created_by')->constrained('admins')->cascadeOnDelete();

            $table->timestamps();

            $table->index('admin_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_goals');
    }
};
