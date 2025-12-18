<?php


use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;


return new class extends Migration {

    /**
     * Run the migrations.
     * به‌روزرسانی جدول questions برای پشتیبانی از آپلود عکس و اتصال به مبحث
     */

    public function up(): void

    {

        Schema::table('questions', function (Blueprint $table) {

            // اتصال به مبحث (topic)

            $table->foreignId('cc_topic_id')->nullable()->after('subject_id')->constrained('cc_topics')->nullOnDelete();


            // حذف direction چون دیگر نیازی نیست

            $table->dropColumn('direction');


            // اضافه کردن correct_option برای ذخیره گزینه صحیح (1-4)

            $table->unsignedTinyInteger('correct_option')->nullable()->after('difficulty');

        });

    }


    /**
     * Reverse the migrations.
     */

    public function down(): void

    {

        Schema::table('questions', function (Blueprint $table) {

            $table->dropForeign(['cc_topic_id']);

            $table->dropColumn('cc_topic_id');

            $table->dropColumn('correct_option');

            $table->enum('direction', ['rtl', 'ltr'])->default('rtl');

        });

    }

};
