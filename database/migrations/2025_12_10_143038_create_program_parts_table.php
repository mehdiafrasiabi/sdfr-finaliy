<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void

    {
        // جدول پارت‌های برنامه
        Schema::create('program_parts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weekly_program_id')->constrained('weekly_programs')->onDelete('cascade');
            $table->foreignId('lesson_id')->nullable()->constrained('lessons')->onDelete('set null');
            $table->string('lesson_name'); // نام درس (برای ذخیره سازی)
            $table->date('part_date'); // تاریخ پارت
            $table->tinyInteger('day_of_week'); // روز هفته (0=شنبه تا 6=جمعه)
            $table->tinyInteger('part_order')->default(1); // ترتیب پارت در روز (1 تا 10)
            $table->text('description')->nullable(); // توضیحات راجب پارت
            $table->integer('duration_minutes')->default(60); // مدت زمان به دقیقه
            $table->integer('test_count')->nullable(); // تعداد تست (اختیاری)
            $table->enum('part_type', ['test', 'descriptive', 'video'])->default('descriptive'); // نوع پارت: تستی، تشریحی، ویدئو
            $table->enum('lesson_type', ['general', 'specialized'])->default('specialized'); // عمومی یا تخصصی
            $table->enum('grade', ['10', '11', '12'])->nullable(); // پایه
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('program_parts');
    }
};
