<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * هر ردیف = یک قسط ماهانه از یک طرح اقساطی. پرداخت باید به‌ترتیب sequence باشد.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('installments')) {
            return;
        }

        Schema::create('installments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('installment_plan_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('sequence'); // 1..N به‌ترتیب
            $table->date('due_date');
            $table->unsignedBigInteger('amount');
            $table->string('status', 20)->default('pending'); // pending | paid
            $table->unsignedBigInteger('payment_id')->nullable();
            $table->dateTime('paid_at')->nullable();
            $table->boolean('paid_manually')->default(false);
            $table->string('manual_note')->nullable();
            $table->unsignedBigInteger('manual_admin_id')->nullable();
            $table->timestamps();

            $table->unique(['installment_plan_id', 'sequence'], 'installment_plan_seq_unique');
            $table->index(['installment_plan_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('installments');
    }
};
