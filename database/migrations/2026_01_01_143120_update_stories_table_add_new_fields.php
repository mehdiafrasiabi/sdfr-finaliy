<?php


use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;

use Illuminate\Support\Facades\Schema;


return new class extends Migration {

    /**
     * Run the migrations.
     */

    public function up(): void

    {

        Schema::table('stories', function (Blueprint $table) {

            $table->enum('type', ['image', 'video'])->default('video')->after('title');

            $table->timestamp('expires_at')->nullable()->after('status');

            $table->string('widget_title')->nullable()->after('expires_at');

            $table->string('widget_link')->nullable()->after('widget_title');

            $table->unsignedBigInteger('admin_id')->nullable()->after('id');


            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('set null');

        });

    }


    /**
     * Reverse the migrations.
     */

    public function down(): void

    {

        Schema::table('stories', function (Blueprint $table) {

            $table->dropForeign(['admin_id']);

            $table->dropColumn(['type', 'expires_at', 'widget_title', 'widget_link', 'admin_id']);

        });

    }

};
