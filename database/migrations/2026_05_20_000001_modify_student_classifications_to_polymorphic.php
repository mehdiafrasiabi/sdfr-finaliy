<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // حذف FK‌های وابسته به جداول دیگه قبل از truncate
        DB::statement('ALTER TABLE student_classifications DROP FOREIGN KEY student_classifications_user_id_foreign');
        DB::statement('ALTER TABLE student_classifications DROP FOREIGN KEY student_classifications_cc_topic_id_foreign');
        DB::statement('ALTER TABLE student_classifications DROP FOREIGN KEY student_classifications_classification_project_id_foreign');

        DB::table('student_classifications')->truncate();

        DB::statement('ALTER TABLE student_classifications DROP INDEX unique_classification');

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

        // FK‌ها رو برگردون
        DB::statement('ALTER TABLE student_classifications ADD CONSTRAINT student_classifications_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id)');
        DB::statement('ALTER TABLE student_classifications ADD CONSTRAINT student_classifications_classification_project_id_foreign FOREIGN KEY (classification_project_id) REFERENCES classification_projects(id)');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE student_classifications DROP FOREIGN KEY student_classifications_user_id_foreign');
        DB::statement('ALTER TABLE student_classifications DROP FOREIGN KEY student_classifications_classification_project_id_foreign');

        Schema::table('student_classifications', function (Blueprint $table) {
            try { $table->dropUnique('sc_unique_ratable'); } catch (\Throwable $e) {}
            try { $table->dropIndex('sc_ratable_idx'); } catch (\Throwable $e) {}
            $table->dropColumn(['ratable_type', 'ratable_id']);
            $table->unsignedBigInteger('cc_topic_id')->nullable();
        });

        DB::statement('ALTER TABLE student_classifications ADD CONSTRAINT unique_classification UNIQUE (user_id, classification_project_id, cc_topic_id)');
        DB::statement('ALTER TABLE student_classifications ADD CONSTRAINT student_classifications_cc_topic_id_foreign FOREIGN KEY (cc_topic_id) REFERENCES cc_topics(id)');
        DB::statement('ALTER TABLE student_classifications ADD CONSTRAINT student_classifications_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id)');
        DB::statement('ALTER TABLE student_classifications ADD CONSTRAINT student_classifications_classification_project_id_foreign FOREIGN KEY (classification_project_id) REFERENCES classification_projects(id)');
    }
};
