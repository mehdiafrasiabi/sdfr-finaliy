<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_parts', function (Blueprint $table) {
            // پارت‌هایی که از طریق «اتفاقات یهویی» توسط خود دانش‌آموز اضافه شده‌اند
            $table->boolean('is_student_added')->default(false)->after('source_type');
        });
    }

    public function down(): void
    {
        Schema::table('program_parts', function (Blueprint $table) {
            $table->dropColumn('is_student_added');
        });
    }
};
