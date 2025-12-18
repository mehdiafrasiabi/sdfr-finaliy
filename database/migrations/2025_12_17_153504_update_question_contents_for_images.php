<?php


use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;


return new class extends Migration {

    /**
     * Run the migrations.
     * تغییر question_contents برای ذخیره عکس به جای متن
     */

    public function up(): void

    {

        Schema::table('question_contents', function (Blueprint $table) {

            // نام فایل عکس سوال (فقط نام فایل، مسیر در کد مشخص می‌شود)

            $table->string('question_image')->nullable()->after('question_id');


            // نام فولدر hash شده

            $table->string('folder_hash')->nullable()->after('question_image');


            // نام فایل عکس پاسخ تشریحی

            $table->string('explanation_image')->nullable()->after('folder_hash');

        });

    }


    /**
     * Reverse the migrations.
     */

    public function down(): void

    {

        Schema::table('question_contents', function (Blueprint $table) {

            $table->dropColumn(['question_image', 'folder_hash', 'explanation_image']);

        });

    }

};
