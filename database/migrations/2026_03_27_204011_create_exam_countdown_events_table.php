<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_countdown_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_countdown_setting_id')
                ->constrained('exam_countdown_settings')
                ->cascadeOnDelete();
            $table->string('event_title');
            $table->date('event_date');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_countdown_events');
    }
};
