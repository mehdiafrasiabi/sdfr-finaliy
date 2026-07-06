<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * درخواستِ مرخصیِ مشاور برای یک روز مشخص.
 * باید حداقل ۷۲ ساعت قبل ثبت شود و توسط مدیر آموزشی تایید/رد گردد. در صورتِ تایید،
 * برای همه‌ی دانش‌آموزانِ آن روز جلسه‌ی جبرانی ساخته می‌شود.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('advisor_leaves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('advisor_id')->constrained('admins')->cascadeOnDelete();
            $table->date('leave_date');
            $table->string('reason')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->string('reject_reason')->nullable();
            $table->timestamps();

            $table->index(['advisor_id', 'status']);
            $table->index('leave_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advisor_leaves');
    }
};
