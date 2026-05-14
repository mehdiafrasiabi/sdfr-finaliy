<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('acquisition_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trial_week_id')->constrained()->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained()->cascadeOnDelete();

            // initial = تماس اولیه  |  secondary = تماس ثانویه  |  supplementary = تماس جانبی
            $table->string('type', 20);

            $table->boolean('answered')->default(false);
            $table->text('notes')->nullable();

            // فقط برای تماس ثانویه
            $table->tinyInteger('prediction_percentage')->nullable(); // 0-100
            $table->text('attraction_plan')->nullable();

            $table->timestamp('contacted_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('acquisition_contacts');
    }
};
