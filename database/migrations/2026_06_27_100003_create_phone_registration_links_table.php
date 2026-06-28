<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * لینک یکتای ثبت‌نام برای شمارهٔ جذب تلفنی.
 * با این لینک می‌فهمیم ثبت‌نام برای کدام شماره و توسط کدام مشاور انجام شده
 * تا در آینده پاداش به مشاور تعلق گیرد.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phone_registration_links', function (Blueprint $table) {
            $table->id();

            $table->string('token', 40)->unique();
            $table->foreignId('phone_lead_id')->constrained('phone_leads')->cascadeOnDelete();
            // مشاوری که لینک را ارسال کرده (مبنای پاداش)
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
            $table->string('mobile', 20);

            $table->timestamp('sent_at')->nullable();

            // کاربری که با این لینک ثبت‌نام کرد (تبدیل)
            $table->foreignId('registered_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('used_at')->nullable();

            $table->timestamps();

            $table->index('registered_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phone_registration_links');
    }
};
