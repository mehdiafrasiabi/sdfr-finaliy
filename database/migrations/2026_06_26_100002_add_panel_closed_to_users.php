<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * بستن پنلِ تکیِ یک دانش‌آموز توسط مدیر (مستقل از کلیدِ سراسریِ
 * general_settings.student_panel_closed):
 *   - panel_closed: اگر true باشد، آن کاربر به هیچ‌یک از مسیرهای profile دسترسی ندارد.
 *   - panel_closed_message: پیامِ اختصاصیِ نمایش‌داده‌شده به کاربر.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'panel_closed')) {
                $table->boolean('panel_closed')->default(false)->after('password');
            }
            if (! Schema::hasColumn('users', 'panel_closed_message')) {
                $table->text('panel_closed_message')->nullable()->after('panel_closed');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach (['panel_closed', 'panel_closed_message'] as $col) {
                if (Schema::hasColumn('users', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
