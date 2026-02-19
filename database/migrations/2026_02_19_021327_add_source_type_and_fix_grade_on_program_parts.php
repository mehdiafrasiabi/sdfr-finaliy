<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('program_parts', function (Blueprint $table) {
            // Add source_type column to track where each part comes from
            $table->string('source_type', 30)->default('normal')->after('part_type');
        });

        // Fix grade column: change from enum('10','11','12') to string
        // This prevents data truncation when grade values like '11' come from CcGrade
        DB::statement("ALTER TABLE program_parts MODIFY COLUMN `grade` VARCHAR(20) NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_parts', function (Blueprint $table) {
            $table->dropColumn('source_type');
        });

        DB::statement("ALTER TABLE program_parts MODIFY COLUMN `grade` ENUM('10','11','12') NULL");
    }
};
