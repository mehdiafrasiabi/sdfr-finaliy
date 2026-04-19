<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('admin_work_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
            // 0=شنبه ... 6=جمعه (هفته ایرانی)
            $table->unsignedTinyInteger('day_of_week');
            $table->time('start_time');
            $table->time('end_time');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['admin_id', 'day_of_week']);
            $table->index('day_of_week');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_work_schedules');
    }
};

