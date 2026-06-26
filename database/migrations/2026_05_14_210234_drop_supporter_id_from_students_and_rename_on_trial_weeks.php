<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * فاز A-1:
     *   - حذف ستون `students.supporter_id` (نقش «پشتیبان تحصیلی» منسوخ شد).
     *     قبل از drop اگر `advisor_id` خالی بود مقدار `supporter_id` به
     *     `advisor_id` منتقل می‌شود تا داده‌ی موجود از دست نرود.
     *   - تغییر نام `trial_weeks.supporter_id` به `acquisition_supporter_id`
     *     (نقش «پشتیبان جذب»).
     */
    /**
     * نام واقعی FK روی یک ستون را از information_schema می‌خواند.
     * اگر FKی روی آن ستون نباشد null برمی‌گرداند.
     */
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
        // ── 1) Students: انتقال داده و حذف ستون ──────────────────────────────
        if (Schema::hasColumn('students', 'supporter_id')) {
            // داده‌های supporter_id را به advisor_id منتقل می‌کنیم
            DB::statement('
                UPDATE students
                SET advisor_id = supporter_id
                WHERE advisor_id IS NULL
                  AND supporter_id IS NOT NULL
            ');

            // FK را فقط در صورت وجود drop می‌کنیم (با نام واقعی‌اش)
            if ($fk = $this->foreignKeyName('students', 'supporter_id')) {
                DB::statement("ALTER TABLE `students` DROP FOREIGN KEY `{$fk}`");
            }

            Schema::table('students', function (Blueprint $table) {
                $table->dropColumn('supporter_id');
            });
        }

        // ── 2) Trial Weeks: تغییر نام ستون به acquisition_supporter_id ───────
        if (Schema::hasColumn('trial_weeks', 'supporter_id')
            && ! Schema::hasColumn('trial_weeks', 'acquisition_supporter_id')) {

            if ($fk = $this->foreignKeyName('trial_weeks', 'supporter_id')) {
                DB::statement("ALTER TABLE `trial_weeks` DROP FOREIGN KEY `{$fk}`");
            }

            Schema::table('trial_weeks', function (Blueprint $table) {
                $table->renameColumn('supporter_id', 'acquisition_supporter_id');
            });

            Schema::table('trial_weeks', function (Blueprint $table) {
                $table->foreign('acquisition_supporter_id')
                    ->references('id')->on('admins')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        // ── 1) Trial Weeks: برگرداندن نام ستون ───────────────────────────────
        if (Schema::hasColumn('trial_weeks', 'acquisition_supporter_id')
            && ! Schema::hasColumn('trial_weeks', 'supporter_id')) {

            if ($fk = $this->foreignKeyName('trial_weeks', 'acquisition_supporter_id')) {
                DB::statement("ALTER TABLE `trial_weeks` DROP FOREIGN KEY `{$fk}`");
            }

            Schema::table('trial_weeks', function (Blueprint $table) {
                $table->renameColumn('acquisition_supporter_id', 'supporter_id');
            });

            Schema::table('trial_weeks', function (Blueprint $table) {
                $table->foreign('supporter_id')
                    ->references('id')->on('admins')
                    ->nullOnDelete();
            });
        }

        // ── 2) Students: بازگرداندن ستون (بدون داده) ─────────────────────────
        if (! Schema::hasColumn('students', 'supporter_id')) {
            Schema::table('students', function (Blueprint $table) {
                $table->foreignId('supporter_id')->nullable()->after('user_id')
                    ->constrained('admins');
            });
        }
    }
};
