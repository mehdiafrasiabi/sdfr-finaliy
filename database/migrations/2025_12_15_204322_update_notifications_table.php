<?php



use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;



return new class extends Migration

{

    /**

     * Run the migrations.

     */

    public function up(): void

    {

        Schema::table('notifications', function (Blueprint $table) {

            // دسته‌بندی پیام: announcement, special, advisor, supporter

            $table->enum('category', ['announcement', 'special', 'advisor', 'supporter'])->default('announcement')->after('body');



            // نوع گیرنده: all_users (همه کاربران), all_students (همه دانش‌آموزان), single (تکی)

            $table->enum('target_type', ['all_users', 'all_students', 'single'])->default('single')->after('category');



            // فرستنده از پنل manager یا null اگر از admin باشد

            $table->boolean('is_from_manager')->default(false)->after('target_type');

        });

    }



    /**

     * Reverse the migrations.

     */

    public function down(): void

    {

        Schema::table('notifications', function (Blueprint $table) {

            $table->dropColumn(['category', 'target_type', 'is_from_manager']);

        });

    }

};
