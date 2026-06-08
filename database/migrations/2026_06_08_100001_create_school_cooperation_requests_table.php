<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('school_cooperation_requests', function (Blueprint $table) {
            $table->id();
            $table->string('full_name', 150);
            $table->string('school_name', 200)->nullable();
            $table->string('mobile', 15);
            $table->unsignedInteger('student_count');
            $table->foreignId('state_id')->nullable()->constrained('states')->nullOnDelete();
            $table->foreignId('city_id')->nullable()->constrained('cities')->nullOnDelete();

            // اسنپ‌شات محاسبه‌گر در لحظه‌ی ثبت درخواست
            $table->string('package', 30)->nullable();        // برنزی / نقره‌ای / طلایی / پلاتینی
            $table->unsignedBigInteger('payable_amount')->default(0);   // هزینه‌ای که باید پرداخت شود (تومان)
            $table->unsignedBigInteger('discount_amount')->default(0);  // سود و تخفیف (تومان)

            $table->boolean('is_reviewed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_cooperation_requests');
    }
};
