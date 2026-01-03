<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void

    {

        Schema::table('general_settings', function (Blueprint $table) {


            // اطلاعات اصلی فروشگاه - SEO

            $table->string('site_short_title')->nullable()->after('site_title');

            $table->string('h1_tag')->nullable()->after('site_short_title');

            $table->string('support_hours')->nullable()->after('site_description');

            $table->string('support_phone')->nullable()->after('support_hours');


            // تگ‌ها و اسکریپت‌های هدر و فوتر

            $table->text('head_scripts')->nullable()->after('support_phone');

            $table->text('footer_scripts')->nullable()->after('head_scripts');


            // کد اسکریپت مجوزها

            $table->text('enamad_script')->nullable()->after('footer_scripts');

            $table->text('samandehi_script')->nullable()->after('enamad_script');

            $table->text('etehaddiye_script')->nullable()->after('samandehi_script');


            // لینک شبکه‌های اجتماعی

            $table->string('youtube')->nullable()->after('telegram');

            $table->string('aparat')->nullable()->after('youtube');


            // دکمه شناور پشتیبانی

            $table->boolean('floating_support_enabled')->default(false)->after('aparat');

            $table->string('floating_mobile')->nullable()->after('floating_support_enabled');

            $table->string('floating_phone')->nullable()->after('floating_mobile');

            $table->string('floating_whatsapp')->nullable()->after('floating_phone');

            $table->string('floating_telegram')->nullable()->after('floating_whatsapp');

        });

    }

    public function down(): void

    {

        Schema::table('general_settings', function (Blueprint $table) {

            $table->dropColumn([

                'site_short_title',

                'h1_tag',

                'support_hours',

                'support_phone',

                'head_scripts',

                'footer_scripts',

                'enamad_script',

                'samandehi_script',

                'etehaddiye_script',

                'youtube',

                'aparat',

                'floating_support_enabled',

                'floating_mobile',

                'floating_phone',

                'floating_whatsapp',

                'floating_telegram',

            ]);

        });

    }
};
