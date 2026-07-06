<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * پیام‌های یک مکالمه. فرستنده با sender_type مشخص می‌شود: student | advisor.
 * هر پیام می‌تواند متن (body) و/یا یک تصویر (image_path، webp) داشته باشد؛
 * body در صورت وجود تصویر، نقش کپشن را دارد.
 *
 * حذف نرم با ستون deleted_at انجام می‌شود اما عمداً از trait سراسری SoftDeletes
 * استفاده نمی‌کنیم تا ردیف همچنان لود شود و به‌صورت «این پیام حذف شد» رندر گردد
 * (هیچ داده‌ای از دیتابیس پاک نمی‌شود).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversation_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('conversation_id')->constrained('conversations')->cascadeOnDelete();
            $table->string('sender_type', 16); // student | advisor
            $table->text('body')->nullable();
            $table->string('image_path')->nullable();
            $table->timestamp('read_at')->nullable();   // لحظه‌ای که طرف مقابل خواند → دو تیک
            $table->timestamp('edited_at')->nullable();
            $table->timestamp('deleted_at')->nullable(); // حذف نرم (ردیف باقی می‌ماند)
            $table->timestamps();

            $table->index(['conversation_id', 'id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('conversation_messages');
    }
};
