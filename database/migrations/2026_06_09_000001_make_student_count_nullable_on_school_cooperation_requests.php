<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('school_cooperation_requests', function (Blueprint $table) {
            $table->unsignedInteger('student_count')->nullable()->default(null)->change();
        });
    }

    public function down(): void
    {
        Schema::table('school_cooperation_requests', function (Blueprint $table) {
            $table->unsignedInteger('student_count')->nullable(false)->change();
        });
    }
};
