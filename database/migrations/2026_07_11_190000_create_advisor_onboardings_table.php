<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('advisor_onboardings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('advisor_id')->constrained('admins')->cascadeOnDelete();
            $table->foreignId('advisor_selection_id')->nullable()->constrained('advisor_selections')->nullOnDelete();
            $table->foreignId('contact_documentation_id')->nullable()->constrained('contact_documentations')->nullOnDelete();
            $table->string('status', 20)->default('pending_call');
            $table->string('group_link')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('admins')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->string('reject_reason')->nullable();
            $table->timestamps();

            $table->unique('student_id');
            $table->index(['advisor_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('advisor_onboardings');
    }
};
