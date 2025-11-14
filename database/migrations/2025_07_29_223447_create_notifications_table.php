<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained(); // فرستنده اعلان
            $table->foreignId('student_id')->nullable()->constrained(); // اگر null باشه یعنی برای همه دانش‌آموزان ارسال شده
            $table->string('title');
            $table->text('body');
            $table->boolean('is_read')->default(false); // برای اینکه دانش‌آموز دیده یا نه
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
