<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * اختصاص روزانهٔ شماره‌ها توسط مدیر آموزشی به مشاور جذب تلفنی.
 * تاریخچهٔ اختصاص؛ مبنای تشخیص «تکراری / چندبار اختصاص داده شده».
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phone_lead_assignments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('phone_lead_id')->constrained('phone_leads')->cascadeOnDelete();
            // مشاور جذب تلفنی که شماره به او اختصاص یافته
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
            // مدیر آموزشی که اختصاص داده
            $table->foreignId('assigned_by')->constrained('admins')->cascadeOnDelete();

            // active = در دست مشاور | done = کار روی این شماره تمام شده
            $table->string('status', 20)->default('active');
            $table->timestamp('assigned_at')->useCurrent();

            $table->timestamps();

            $table->index(['admin_id', 'status']);
            $table->index('phone_lead_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phone_lead_assignments');
    }
};
