<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('acquisition_calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trial_week_id')->constrained('trial_weeks')->cascadeOnDelete();
            $table->foreignId('acquisition_supporter_id')->constrained('admins')->cascadeOnDelete();
            $table->string('type', 20); // initial|secondary|side
            $table->string('status', 20)->default('pending'); // pending|no_answer|answered
            $table->text('description')->nullable();
            $table->timestamp('called_at')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->unsignedTinyInteger('prediction_percent')->nullable();
            $table->text('attraction_plan')->nullable();
            $table->timestamps();

            $table->index(['trial_week_id', 'type']);
            $table->index(['acquisition_supporter_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acquisition_calls');
    }
};
