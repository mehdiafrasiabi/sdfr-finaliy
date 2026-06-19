<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ثبت تماس مشاور جذب تلفنی با هر شماره — هر ردیف یک «تلاش تماس» است.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phone_calls', function (Blueprint $table) {
            $table->id();

            $table->foreignId('phone_lead_id')->constrained('phone_leads')->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();

            // شمارهٔ تلاش (۱..۵) — مبنای رنگ
            $table->unsignedTinyInteger('attempt_number');
            // آیا تماس برقرار شد؟
            $table->boolean('connected')->default(false);

            // شاخهٔ ناموفق: no_answer | off | rejected | wrong
            $table->string('fail_reason', 20)->nullable();

            // شاخهٔ موفق
            // با چه شخصی صحبت شد: father|mother|student|other
            $table->string('spoke_with', 20)->nullable();
            // درصد تمایل به همکاری 0..100
            $table->unsignedTinyInteger('willingness')->nullable();
            // اگر زیر ۵۰٪ بود، علت عدم تمایل
            $table->text('low_willingness_reason')->nullable();
            // نتیجه: registered | follow_up | no_interest
            $table->string('result', 20)->nullable();
            // تاریخ و ساعت پیگیری مجدد (در صورت follow_up)
            $table->dateTime('follow_up_at')->nullable();
            // خلاصه و نتیجهٔ گفتگو (تایپ دستی)
            $table->text('summary')->nullable();

            $table->timestamp('called_at');
            $table->timestamps();

            $table->index('phone_lead_id');
            $table->index('admin_id');
            $table->index('result');
            $table->index('follow_up_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phone_calls');
    }
};
