<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * طرح اقساطی یک دانش‌آموز: پیش‌پرداخت ۳۰٪ + اقساط ماهانه تا پایان خرداد.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('installment_plans')) {
            return;
        }

        Schema::create('installment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('grade_price_id')->nullable()->constrained()->nullOnDelete();
            $table->tinyInteger('grade')->nullable();
            $table->unsignedTinyInteger('entry_month_index'); // 0=تیر .. 11=خرداد
            $table->date('purchase_date');
            $table->unsignedBigInteger('total_amount');    // کل پرداختی سال
            $table->unsignedBigInteger('initial_amount');  // پیش‌پرداخت
            $table->unsignedBigInteger('initial_payment_id')->nullable();
            $table->unsignedTinyInteger('installment_count'); // تعداد اقساط (بدون پیش‌پرداخت)
            $table->unsignedBigInteger('monthly_amount');  // مبلغ هر قسط
            $table->dateTime('access_ends_at')->nullable();
            // pending = در انتظار پرداخت پیش‌پرداخت، active = فعال، completed = تسویه، defaulted = نکول
            $table->string('status', 20)->default('pending');
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installment_plans');
    }
};
