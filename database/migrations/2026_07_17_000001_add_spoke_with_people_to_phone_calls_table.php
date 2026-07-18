<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('phone_calls', function (Blueprint $table) {
            $table->json('spoke_with_people')->nullable()->after('spoke_with');
            $table->text('spoke_with_other')->nullable()->after('spoke_with_people');
        });
    }

    public function down(): void
    {
        Schema::table('phone_calls', function (Blueprint $table) {
            $table->dropColumn(['spoke_with_people', 'spoke_with_other']);
        });
    }
};
