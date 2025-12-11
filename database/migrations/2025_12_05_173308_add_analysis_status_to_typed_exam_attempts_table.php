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

        Schema::table('typed_exam_attempts', function (Blueprint $table) {

            $table->enum('analysis_status', ['pending', 'approved', 'rejected'])->nullable()->after('score');

        });

    }


    /**
     * Reverse the migrations.
     */

    public function down(): void

    {

        Schema::table('typed_exam_attempts', function (Blueprint $table) {

            $table->dropColumn('analysis_status');

        });

    }
};
