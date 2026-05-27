<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parent_assessment_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('trial_week_id')->nullable()->constrained('trial_weeks')->cascadeOnDelete();
            // 'father' | 'mother'
            $table->string('parent_role', 10);
            $table->string('mobile', 20);
            $table->string('token', 64)->unique();
            $table->timestamp('expires_at');
            $table->timestamp('sent_at')->nullable();
            $table->unsignedTinyInteger('sms_attempts')->default(0);
            $table->timestamp('first_accessed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'parent_role']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parent_assessment_invitations');
    }
};
