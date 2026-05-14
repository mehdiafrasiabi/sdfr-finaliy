<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grade_prices', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('grade');                  // 9, 10, 11, 12
            $table->string('field', 20)->nullable();       // math|experimental|human|null(grade 9)
            $table->string('label', 100)->nullable();      // نام نمایشی این پلن

            $table->unsignedBigInteger('total_amount');    // مبلغ کل (تومان)
            $table->tinyInteger('months');                 // تعداد ماه (مدت برنامه)
            // monthly_amount = total_amount / months → computed in model

            $table->tinyInteger('discount_percentage')->default(0); // تخفیف درصدی (0-100)
            // final_amount = total_amount * (1 - discount_percentage / 100)

            $table->date('start_at');                      // از چه تاریخی اعمال می‌شود
            $table->date('end_at')->nullable();            // تا چه تاریخی (null = تا اطلاع ثانوی)

            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('admins')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_prices');
    }
};
