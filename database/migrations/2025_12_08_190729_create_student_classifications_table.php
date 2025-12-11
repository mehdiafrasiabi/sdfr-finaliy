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

        Schema::create('student_classifications', function (Blueprint $table) {

            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->foreignId('classification_project_id')->constrained()->cascadeOnDelete();

            $table->foreignId('cc_topic_id')->constrained()->cascadeOnDelete();

            $table->tinyInteger('rating'); // 1-8 (D تا A+)

            $table->timestamps();



            // هر دانش‌آموز برای هر مبحث در هر پروژه یک رکورد

            $table->unique(['user_id', 'classification_project_id', 'cc_topic_id'], 'unique_classification');

        });

    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_classifications');
    }
};
