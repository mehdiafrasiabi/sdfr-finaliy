<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('grade_pricings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cc_grade_id')->constrained('cc_grades')->cascadeOnDelete();
            $table->unsignedBigInteger('total_price');
            $table->date('starts_on');
            $table->date('ends_on');
            $table->unsignedBigInteger('discount_amount')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['cc_grade_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_pricings');
    }
};
