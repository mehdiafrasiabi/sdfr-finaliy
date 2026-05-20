<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::table('student_classifications')->truncate();

        Schema::table('student_classifications', function (Blueprint $table) {
            try { $table->dropForeign(['cc_topic_id']); } catch (\Throwable $e) {}
            try { $table->dropUnique('unique_classification'); } catch (\Throwable $e) {}
        });

        Schema::table('student_classifications', function (Blueprint $table) {
            if (Schema::hasColumn('student_classifications', 'cc_topic_id')) {
                $table->dropColumn('cc_topic_id');
            }
        });

        Schema::table('student_classifications', function (Blueprint $table) {
            $table->string('ratable_type')->after('classification_project_id');
            $table->unsignedBigInteger('ratable_id')->after('ratable_type');
            $table->index(['ratable_type', 'ratable_id'], 'sc_ratable_idx');
            $table->unique(
                ['user_id', 'classification_project_id', 'ratable_type', 'ratable_id'],
                'sc_unique_ratable'
            );
        });
    }

    public function down(): void
    {
        Schema::table('student_classifications', function (Blueprint $table) {
            try { $table->dropUnique('sc_unique_ratable'); } catch (\Throwable $e) {}
            try { $table->dropIndex('sc_ratable_idx'); } catch (\Throwable $e) {}
            $table->dropColumn(['ratable_type', 'ratable_id']);
            $table->unsignedBigInteger('cc_topic_id')->nullable();
        });
    }
};
