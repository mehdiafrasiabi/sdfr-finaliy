<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class GeneralSettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('general_settings')->delete();
        
        \DB::table('general_settings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'site_title' => 'وبسایت مشاور تحصیلی و آموزشی SDFR',
                'site_short_title' => 'SDFR',
                'h1_tag' => 'بهترین سامانه مشاوره تحصیلی کشور',
                'site_description' => 'SDFR، اولین سامانه هوشمند مشاوره و آنالیز دقیق تحصیلی در ایران! با صرفه جویی در وقت و هزینه، پشتیبانی تحصیلی روزانه و ابزار های حرفه ای و هوشمند آموزشی حس پیشرفت در آزمون های تشریحی و تستی را تجربه کنید!',
                'support_hours' => '09:00 - 17:00',
                'support_phone' => '051-35092160',
                'head_scripts' => NULL,
                'footer_scripts' => NULL,
                'enamad_script' => '<a referrerpolicy="origin" target="_blank" href="https://trustseal.enamad.ir/?id=631278&amp;Code=tekNO0LdZ1opr25Z3ektQ3PXsJAlL7go"><img referrerpolicy="origin" src="https://trustseal.enamad.ir/logo.aspx?id=631278&amp;Code=tekNO0LdZ1opr25Z3ektQ3PXsJAlL7go" alt="" style="cursor:pointer" code="tekNO0LdZ1opr25Z3ektQ3PXsJAlL7go"></a>',
                'samandehi_script' => NULL,
                'etehaddiye_script' => NULL,
                'logo_header' => NULL,
                'logo_footer' => NULL,
                'favicon' => NULL,
                'phone' => NULL,
                'address' => NULL,
                'instagram' => 'https://instagram.com/sdfr.me',
                'telegram' => 'https://t.me/SdfrWebApp',
                'youtube' => NULL,
                'aparat' => 'https://www.aparat.com/sdfr.me',
                'floating_support_enabled' => 1,
                'floating_mobile' => '',
                'floating_phone' => '05136578382',
                'floating_whatsapp' => '09020029757',
                'floating_telegram' => 'https://t.me/SdfrWebApp',
                'about' => NULL,
                'created_at' => '2026-02-01 20:09:42',
                'updated_at' => '2026-02-04 13:46:55',
            ),
        ));
        
        
    }
}