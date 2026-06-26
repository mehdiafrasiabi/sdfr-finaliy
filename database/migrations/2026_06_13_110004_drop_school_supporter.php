<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    private function foreignKeyName(string $table, string $column): ?string
    {
        $row = DB::selectOne(
            'SELECT CONSTRAINT_NAME
               FROM information_schema.KEY_COLUMN_USAGE
              WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME = ?
                AND COLUMN_NAME = ?
                AND REFERENCED_TABLE_NAME IS NOT NULL
              LIMIT 1',
            [$table, $column]
        );

        return $row->CONSTRAINT_NAME ?? null;
    }

    public function up(): void
    {
        if (Schema::hasColumn('students', 'school_supporter_id')) {
            if ($fk = $this->foreignKeyName('students', 'school_supporter_id')) {
                DB::statement("ALTER TABLE `students` DROP FOREIGN KEY `{$fk}`");
            }

            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('school_supporter_id');
            });
        }

        Schema::dropIfExists('school_admin');
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('school_supporter_id')->nullable()->after('school_id')
                ->constrained('admins')->nullOnDelete();
        });

        Schema::create('school_admin', function (Blueprint $table) {
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->foreignId('admin_id')->constrained('admins')->cascadeOnDelete();
            $table->timestamps();
            $table->primary(['school_id', 'admin_id']);
        });
    }
};
