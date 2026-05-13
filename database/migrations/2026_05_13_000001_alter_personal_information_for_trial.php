<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // Convert ENUM columns to plain strings so we can support grade 9 (null field)
        // and avoid Doctrine ENUM issues, plus relax NOT NULL constraints that the
        // unified onboarding wizard does not collect yet.
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE personal_information MODIFY grade VARCHAR(5) NULL");
            DB::statement("ALTER TABLE personal_information MODIFY field VARCHAR(20) NULL");
        }

        Schema::table('personal_information', function (Blueprint $table) {
            $table->string('place_of_birth')->nullable()->change();
            $table->text('birth_date')->nullable()->change();
            $table->string('address')->nullable()->change();
            $table->string('father_name')->nullable()->change();
            $table->string('last_name')->nullable()->after('name');
        });
    }

    public function down(): void
    {
        Schema::table('personal_information', function (Blueprint $table) {
            if (Schema::hasColumn('personal_information', 'last_name')) {
                $table->dropColumn('last_name');
            }
        });
    }
};
