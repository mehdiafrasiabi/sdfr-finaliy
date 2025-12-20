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
        Schema::table('cc_grades', function (Blueprint $table) {
            $table->foreignId('cc_field_id')->nullable()->after('education_level_id')->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cc_grades', function (Blueprint $table) {
            $table->dropForeign(['cc_field_id']);
            $table->dropColumn('cc_field_id');
        });
    }
};
