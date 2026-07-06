<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * انتخابِ مشاور توسط دانش‌آموز (دستی یا تصادفی) که در انتظارِ تاییدِ مدیر آموزشی است.
 * تا قبل از تایید، advisor_id روی خودِ دانش‌آموز ست نمی‌شود؛ این ردیف نقشِ «رزرو» را دارد.
 */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('advisor_selections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('advisor_id')->constrained('admins')->cascadeOnDelete();
            $table->unsignedTinyInteger('weekly_day');            // 0..6 (شنبه..جمعه)
            $table->string('preferred_hour', 5)->nullable();      // 'HH:MM' — ترجیحِ اولیه‌ی ساعت
            $table->enum('mode', ['manual', 'random'])->default('manual');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->string('reject_reason')->nullable();
            $table->timestamps();

            $table->index(['student_id', 'status']);
            $table->index(['advisor_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advisor_selections');
    }
};
