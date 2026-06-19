<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * رسیدهای شارژ که مشاور جذب تلفنی ارسال می‌کند و مدیر آموزشی تایید/رد می‌کند.
 * فعلاً حداقلی؛ عملیات حسابداری در فاز بعد تکمیل می‌شود.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('charge_receipts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
            $table->string('image_path');
            $table->unsignedBigInteger('amount')->nullable();

            // pending | approved | rejected
            $table->string('status', 20)->default('pending');
            $table->string('note')->nullable();

            $table->foreignId('reviewed_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            $table->index(['admin_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('charge_receipts');
    }
};
