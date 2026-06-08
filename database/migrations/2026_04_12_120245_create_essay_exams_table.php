<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // اطمینان از وجود admins
        if (!Schema::hasTable('admins')) {
            throw new \RuntimeException('admins table must exist before essay_exams');
        }

        if (!Schema::hasTable('essay_exams')) {
            Schema::create('essay_exams', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('admin_id');
                $table->unsignedBigInteger('cc_topic_id')->nullable();
                $table->string('title');
                $table->string('question_pdf_path')->nullable();
                $table->string('answer_pdf_path')->nullable();
                $table->decimal('total_score', 5, 2)->default(0);
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('admin_id')->references('id')->on('admins')->cascadeOnDelete();
                $table->foreign('cc_topic_id')->references('id')->on('cc_topics')->nullOnDelete();
            });
        }

    }
    public function down(): void
    {
        Schema::dropIfExists('essay_exams');
    }
};

