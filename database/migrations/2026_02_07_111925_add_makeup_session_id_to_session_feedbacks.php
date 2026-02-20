<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) اگر FK وجود داشت حذفش کن
        $fkExists = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'session_feedbacks'
              AND COLUMN_NAME = 'sps_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
        ");

        if ($fkExists?->CONSTRAINT_NAME) {
            Schema::table('session_feedbacks', function (Blueprint $table) use ($fkExists) {
                $table->dropForeign($fkExists->CONSTRAINT_NAME);
            });
        }

        // 2) اگر unique index روی sps_id وجود داشت حذفش کن
        $uniqueExists = DB::selectOne("
            SELECT INDEX_NAME
            FROM information_schema.STATISTICS
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'session_feedbacks'
              AND COLUMN_NAME = 'sps_id'
              AND NON_UNIQUE = 0
            LIMIT 1
        ");

        if ($uniqueExists?->INDEX_NAME) {
            Schema::table('session_feedbacks', function (Blueprint $table) use ($uniqueExists) {
                $table->dropUnique($uniqueExists->INDEX_NAME);
            });
        }

        // 3) تغییر ستون و اضافه کردن makeup_session_id
        Schema::table('session_feedbacks', function (Blueprint $table) {
            $table->unsignedBigInteger('sps_id')->nullable()->change();

            $table->foreignId('makeup_session_id')
                ->nullable()
                ->after('sps_id')
                ->constrained('makeup_sessions')
                ->cascadeOnDelete();
        });

        // 4) FK برای sps_id را (دوباره) بساز
        Schema::table('session_feedbacks', function (Blueprint $table) {
            $table->foreign('sps_id')
                ->references('id')
                ->on('study_part_sessions')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        // حذف FK/ستون makeup_session_id اگر وجود داشت
        $fkMakeup = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'session_feedbacks'
              AND COLUMN_NAME = 'makeup_session_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
        ");

        if ($fkMakeup?->CONSTRAINT_NAME) {
            Schema::table('session_feedbacks', function (Blueprint $table) use ($fkMakeup) {
                $table->dropForeign($fkMakeup->CONSTRAINT_NAME);
            });
        }

        Schema::table('session_feedbacks', function (Blueprint $table) {
            if (Schema::hasColumn('session_feedbacks', 'makeup_session_id')) {
                $table->dropColumn('makeup_session_id');
            }
        });

        // FK sps_id را اگر وجود داشت حذف کن
        $fkSps = DB::selectOne("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
              AND TABLE_NAME = 'session_feedbacks'
              AND COLUMN_NAME = 'sps_id'
              AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
        ");

        if ($fkSps?->CONSTRAINT_NAME) {
            Schema::table('session_feedbacks', function (Blueprint $table) use ($fkSps) {
                $table->dropForeign($fkSps->CONSTRAINT_NAME);
            });
        }

        // برگرداندن unique و FK
        Schema::table('session_feedbacks', function (Blueprint $table) {
            $table->unique('sps_id');
            $table->foreign('sps_id')
                ->references('id')
                ->on('study_part_sessions')
                ->cascadeOnDelete();
        });
    }
};
