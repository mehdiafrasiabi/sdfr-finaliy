<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $columns = array_values(array_filter([
            Schema::hasColumn('phone_calls', 'willingness') ? 'willingness' : null,
            Schema::hasColumn('phone_calls', 'low_willingness_reason') ? 'low_willingness_reason' : null,
        ]));

        if ($columns !== []) {
            Schema::table('phone_calls', function (Blueprint $table) use ($columns) {
                $table->dropColumn($columns);
            });
        }
    }

    public function down(): void
    {
        //
    }
};
