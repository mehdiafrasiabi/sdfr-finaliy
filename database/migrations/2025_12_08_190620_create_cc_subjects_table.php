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

        Schema::create('cc_subjects', function (Blueprint $table) {

            $table->id();

            $table->foreignId('cc_grade_id')->constrained()->cascadeOnDelete();

            $table->foreignId('cc_field_id')->nullable()->constrained()->nullOnDelete();

            $table->string('name'); // نام درس مثل: حسابان 2

            $table->enum('type', ['general', 'specialized']); // عمومی یا تخصصی

            $table->integer('order')->default(0);

            $table->timestamps();

        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cc_subjects');
    }
};
