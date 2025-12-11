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

        Schema::create('student_classification_submissions', function (Blueprint $table) {
            $table->id();

            // user_id
            $table->foreignId('user_id')
                ->constrained()          // این هنوز مشکلی از نظر طول اسم ندارد، ولی اگر خواستی، می‌توانی دستی تعریفش کنی
                ->cascadeOnDelete();

            // classification_project_id
            $table->unsignedBigInteger('classification_project_id');

            $table->boolean('is_completed')->default(false);

            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();

            $table->unique(['user_id', 'classification_project_id'], 'unique_submission');

            // تعریف FK با اسم کوتاه
            $table->foreign('classification_project_id', 'scs_class_proj_fk')
                ->references('id')
                ->on('classification_projects')
                ->onDelete('cascade');
        });


    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_classification_submissions');
    }
};
