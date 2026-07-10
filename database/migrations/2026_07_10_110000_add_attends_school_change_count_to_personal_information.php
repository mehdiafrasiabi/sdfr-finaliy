<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('personal_information', function (Blueprint $table) {
            $table->unsignedTinyInteger('attends_school_change_count')
                ->default(0)
                ->after('attends_school');
        });
    }

    public function down(): void
    {
        Schema::table('personal_information', function (Blueprint $table) {
            $table->dropColumn('attends_school_change_count');
        });
    }
};
