<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * تماس‌های اورژانسی که مشاور برای پیگیری مدیر مدرسه ثبت می‌کند.
     */
    public function up(): void
    {
        Schema::create('emergency_calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete(); // مشاور ثبت‌کننده
            $table->text('reason');
            $table->enum('status', ['pending', 'resolved'])->default('pending');
            $table->timestamp('called_at');
            $table->text('manager_note')->nullable();
            $table->foreignId('resolved_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('emergency_calls');
    }
};
