<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // ── 1) حذف FKها فقط در صورت وجود (مستقل از نام) ───────────────────────
        $this->dropForeignKeyIfExists('student_classifications', 'user_id');
        $this->dropForeignKeyIfExists('student_classifications', 'cc_topic_id');
        $this->dropForeignKeyIfExists('student_classifications', 'classification_project_id');

        // ── 2) خالی کردن جدول ────────────────────────────────────────────────
        DB::table('student_classifications')->truncate();

        // ── 3) حذف ایندکس یونیک قدیمی فقط در صورت وجود ────────────────────────
        $this->dropIndexIfExists('student_classifications', 'unique_classification');

        // ── 4) حذف ستون cc_topic_id ──────────────────────────────────────────
        if (Schema::hasColumn('student_classifications', 'cc_topic_id')) {
            Schema::table('student_classifications', function (Blueprint $table) {
                $table->dropColumn('cc_topic_id');
            });
        }

        // ── 5) افزودن ستون‌های polymorphic و ایندکس‌ها ────────────────────────
        Schema::table('student_classifications', function (Blueprint $table) {
            if (! Schema::hasColumn('student_classifications', 'ratable_type')) {
                $table->string('ratable_type')->after('classification_project_id');
            }
            if (! Schema::hasColumn('student_classifications', 'ratable_id')) {
                $table->unsignedBigInteger('ratable_id')->after('ratable_type');
            }
        });

        Schema::table('student_classifications', function (Blueprint $table) {
            if (! $this->indexExists('student_classifications', 'sc_ratable_idx')) {
                $table->index(['ratable_type', 'ratable_id'], 'sc_ratable_idx');
            }
            if (! $this->indexExists('student_classifications', 'sc_unique_ratable')) {
                $table->unique(
                    ['user_id', 'classification_project_id', 'ratable_type', 'ratable_id'],
                    'sc_unique_ratable'
                );
            }
        });

        // ── 6) برگرداندن FKها فقط در صورت نبودن ───────────────────────────────
        $this->addForeignKeyIfMissing(
            'student_classifications', 'user_id',
            'student_classifications_user_id_foreign', 'users', 'id'
        );
        $this->addForeignKeyIfMissing(
            'student_classifications', 'classification_project_id',
            'student_classifications_classification_project_id_foreign',
            'classification_projects', 'id'
        );
    }

    public function down(): void
    {
        $this->dropForeignKeyIfExists('student_classifications', 'user_id');
        $this->dropForeignKeyIfExists('student_classifications', 'classification_project_id');

        $this->dropIndexIfExists('student_classifications', 'sc_unique_ratable');
        $this->dropIndexIfExists('student_classifications', 'sc_ratable_idx');

        Schema::table('student_classifications', function (Blueprint $table) {
            if (Schema::hasColumn('student_classifications', 'ratable_type')) {
                $table->dropColumn('ratable_type');
            }
            if (Schema::hasColumn('student_classifications', 'ratable_id')) {
                $table->dropColumn('ratable_id');
            }
            if (! Schema::hasColumn('student_classifications', 'cc_topic_id')) {
                $table->unsignedBigInteger('cc_topic_id')->nullable();
            }
        });

        $this->dropIndexIfExists('student_classifications', 'unique_classification');
        DB::statement('ALTER TABLE student_classifications ADD CONSTRAINT unique_classification UNIQUE (user_id, classification_project_id, cc_topic_id)');

        $this->addForeignKeyIfMissing(
            'student_classifications', 'cc_topic_id',
            'student_classifications_cc_topic_id_foreign', 'cc_topics', 'id'
        );
        $this->addForeignKeyIfMissing(
            'student_classifications', 'user_id',
            'student_classifications_user_id_foreign', 'users', 'id'
        );
        $this->addForeignKeyIfMissing(
            'student_classifications', 'classification_project_id',
            'student_classifications_classification_project_id_foreign',
            'classification_projects', 'id'
        );
    }

    // ───────────────────────── Helpers ──────────────────────────────────────

    /** حذف فارن‌کی یک ستون مستقل از نام constraint (مناسب جداول بکاپی). */
    private function dropForeignKeyIfExists(string $table, string $column): void
    {
        $database = DB::getDatabaseName();

        $foreignKeys = DB::select('
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = ?
              AND TABLE_NAME = ?
              AND COLUMN_NAME = ?
              AND REFERENCED_TABLE_NAME IS NOT NULL
        ', [$database, $table, $column]);

        foreach ($foreignKeys as $fk) {
            DB::statement("ALTER TABLE `{$table}` DROP FOREIGN KEY `{$fk->CONSTRAINT_NAME}`");
        }
    }

    /** افزودن فارن‌کی فقط در صورتی که روی آن ستون FKای وجود نداشته باشد. */
    private function addForeignKeyIfMissing(
        string $table, string $column, string $constraintName,
        string $refTable, string $refColumn
    ): void {
        $database = DB::getDatabaseName();

        $exists = DB::select('
            SELECT 1
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = ?
              AND TABLE_NAME = ?
              AND COLUMN_NAME = ?
              AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
        ', [$database, $table, $column]);

        if (empty($exists)) {
            DB::statement("
                ALTER TABLE `{$table}`
                ADD CONSTRAINT `{$constraintName}`
                FOREIGN KEY (`{$column}`) REFERENCES `{$refTable}`(`{$refColumn}`)
            ");
        }
    }

    /** بررسی وجود ایندکس با نام مشخص. */
    private function indexExists(string $table, string $indexName): bool
    {
        $database = DB::getDatabaseName();

        $result = DB::select('
            SELECT 1
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = ?
              AND TABLE_NAME = ?
              AND INDEX_NAME = ?
            LIMIT 1
        ', [$database, $table, $indexName]);

        return ! empty($result);
    }

    /** حذف ایندکس فقط در صورت وجود. */
    private function dropIndexIfExists(string $table, string $indexName): void
    {
        if ($this->indexExists($table, $indexName)) {
            DB::statement("ALTER TABLE `{$table}` DROP INDEX `{$indexName}`");
        }
    }
};
