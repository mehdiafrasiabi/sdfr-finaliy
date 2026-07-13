<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE personal_information MODIFY COLUMN `grade` ENUM('9','10','11','12') NOT NULL");
        DB::statement("ALTER TABLE personal_information MODIFY COLUMN `field` ENUM('math','experimental','human') NULL");
    }

    public function down(): void
    {
        DB::statement("UPDATE personal_information SET `grade` = '10' WHERE `grade` = '9'");
        DB::statement("UPDATE personal_information SET `field` = 'math' WHERE `field` IS NULL");
        DB::statement("ALTER TABLE personal_information MODIFY COLUMN `field` ENUM('math','experimental','human') NOT NULL");
        DB::statement("ALTER TABLE personal_information MODIFY COLUMN `grade` ENUM('10','11','12') NOT NULL");
    }
};
