<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            // mbti | vark | mindset | parent
            $table->string('stage', 20)->nullable()->after('audience');
            $table->index('stage');
        });
    }

    public function down(): void
    {
        Schema::table('assessments', function (Blueprint $table) {
            $table->dropIndex(['stage']);
            $table->dropColumn('stage');
        });
    }
};
