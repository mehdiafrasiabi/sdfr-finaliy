<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('phone_registration_links', function (Blueprint $table) {
            $table->string('plan', 20)->default('default')->after('mobile');
        });
    }

    public function down(): void
    {
        Schema::table('phone_registration_links', function (Blueprint $table) {
            $table->dropColumn('plan');
        });
    }
};
