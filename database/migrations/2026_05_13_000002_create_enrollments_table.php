<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->foreignId('cc_grade_id')->constrained('cc_grades');
            $table->foreignId('grade_pricing_id')->constrained('grade_pricings');
            $table->foreignId('supporter_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->foreignId('coupon_id')->nullable()->constrained('coupons')->nullOnDelete();

            $table->unsignedBigInteger('base_price');
            $table->unsignedBigInteger('monthly_step');
            $table->unsignedSmallInteger('elapsed_months')->default(0);
            $table->unsignedBigInteger('stepped_discount')->default(0);
            $table->unsignedBigInteger('grade_discount')->default(0);
            $table->unsignedBigInteger('coupon_discount')->default(0);
            $table->unsignedBigInteger('final_amount');

            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('supporter_assigned_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
