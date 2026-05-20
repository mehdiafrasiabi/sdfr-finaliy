<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('school_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->date('report_date');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->unsignedInteger('total_study_minutes')->default(0);
            $table->unsignedInteger('total_mobile_minutes')->default(0);
            $table->text('advisor_comment')->nullable();
            $table->timestamp('advisor_commented_at')->nullable();
            $table->foreignId('reviewed_by_admin_id')->nullable()
                ->constrained('admins')->nullOnDelete();
            $table->timestamps();

            $table->unique(['student_id', 'report_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('school_reports');
    }
};
