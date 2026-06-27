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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('title');
            $table->string('tag')->nullable();
            $table->enum('type', ['weekly', 'monthly', 'yearly_online', 'yearly_offline']);
            $table->unsignedSmallInteger('duration_days')->default(30); // مدت دوره به روز
            $table->boolean('has_supporter')->default(false); // پشتیبان دارد یا خیر
            $table->boolean('has_advisor')->default(false); // مشاور دارد یا خیر
            $table->string('course_time');
            $table->string('meeting_time');
            $table->bigInteger('price')->default(0);
            $table->longText('description')->nullable();
            $table->foreignId('category_id')->constrained();
            $table->string('p_code')->nullable()->unique();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
