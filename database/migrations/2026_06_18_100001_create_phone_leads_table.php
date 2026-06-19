<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * شماره‌های «مشاور جذب تلفنی» — لیدهایی که مدیر آموزشی به‌صورت دستی وارد می‌کند.
 * این لیدها حساب کاربری ندارند (برخلاف هفتهٔ آزمایشی) پس جدول مستقل دارند.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phone_leads', function (Blueprint $table) {
            $table->id();

            $table->string('full_name')->nullable();
            // تنها فیلد اجباری
            $table->string('mobile', 20)->index();

            // پایه ۹..۱۳ و رشته math|experimental|human (هم‌راستا با TrialWeek)
            $table->unsignedTinyInteger('grade')->nullable();
            $table->string('field', 20)->nullable();

            $table->foreignId('state_id')->nullable()->constrained('states')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();

            // active = در جریان | closed = ثبت‌نام/عدم تمایل | dead = شماره اشتباه یا ۵ بار تماس (خاکستری)
            $table->string('status', 20)->default('active')->index();

            // تعداد تماس‌ها (denormalized) — مبنای رنگ‌بندی لید
            $table->unsignedTinyInteger('attempts_count')->default(0);
            // آخرین نتیجهٔ تماس (کلید result یا fail_reason)
            $table->string('last_outcome', 30)->nullable();

            $table->foreignId('created_by')->constrained('admins')->cascadeOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phone_leads');
    }
};
