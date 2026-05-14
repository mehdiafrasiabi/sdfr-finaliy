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

            Schema::table('students', function (Blueprint $table) {
                // حذف FK ابتدا (نام پیش‌فرض Laravel)
                try {
                    $table->dropForeign(['supporter_id']);
                } catch (\Throwable $e) {
                    // اگر FK از قبل با نام دیگری drop شده باشد، عبور می‌کنیم.
                }
                $table->dropColumn('supporter_id');
            });
        }

        // ── 2) Trial Weeks: تغییر نام ستون به acquisition_supporter_id ───────
        if (Schema::hasColumn('trial_weeks', 'supporter_id')
            && ! Schema::hasColumn('trial_weeks', 'acquisition_supporter_id')) {

            Schema::table('trial_weeks', function (Blueprint $table) {
                try {
                    $table->dropForeign(['supporter_id']);
                } catch (\Throwable $e) {
                    // اگر FK وجود ندارد، عبور می‌کنیم.
                }
            });

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

            Schema::table('trial_weeks', function (Blueprint $table) {
                try {
                    $table->dropForeign(['acquisition_supporter_id']);
                } catch (\Throwable $e) {
                    // عبور
                }
            });

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
