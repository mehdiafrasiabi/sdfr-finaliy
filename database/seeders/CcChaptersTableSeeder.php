<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CcChaptersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('cc_chapters')->delete();
        
        \DB::table('cc_chapters')->insert(array (
            0 => 
            array (
                'id' => 2,
                'cc_subject_id' => 1,
                'name' => 'تابع',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:16:56',
                'updated_at' => '2026-02-07 16:16:56',
            ),
            1 => 
            array (
                'id' => 3,
                'cc_subject_id' => 1,
                'name' => 'مثلثات',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:17:04',
                'updated_at' => '2026-02-07 16:17:10',
            ),
            2 => 
            array (
                'id' => 4,
                'cc_subject_id' => 1,
                'name' => 'حدهای نامتناهی ـ حد در بینهایت',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:17:21',
                'updated_at' => '2026-02-07 16:17:21',
            ),
            3 => 
            array (
                'id' => 5,
                'cc_subject_id' => 1,
                'name' => 'مشتق',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:17:32',
                'updated_at' => '2026-02-07 16:17:32',
            ),
            4 => 
            array (
                'id' => 6,
                'cc_subject_id' => 1,
                'name' => 'کاربردهای مشتق',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:17:41',
                'updated_at' => '2026-02-07 16:17:41',
            ),
            5 => 
            array (
                'id' => 7,
                'cc_subject_id' => 2,
                'name' => 'ماتریس و کاربردها',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:41:03',
                'updated_at' => '2026-02-07 16:41:03',
            ),
            6 => 
            array (
                'id' => 8,
                'cc_subject_id' => 2,
                'name' => 'آشنایی با مقاطع مخروطی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:41:14',
                'updated_at' => '2026-02-07 16:41:14',
            ),
            7 => 
            array (
                'id' => 9,
                'cc_subject_id' => 2,
                'name' => 'بـردارهـا',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:41:27',
                'updated_at' => '2026-02-07 16:41:27',
            ),
            8 => 
            array (
                'id' => 10,
                'cc_subject_id' => 3,
                'name' => 'آشنایی با نظریۀ اعداد',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:45:18',
                'updated_at' => '2026-02-07 16:45:18',
            ),
            9 => 
            array (
                'id' => 11,
                'cc_subject_id' => 3,
                'name' => 'گراف و مدلسازی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:45:27',
                'updated_at' => '2026-02-07 16:45:27',
            ),
            10 => 
            array (
                'id' => 12,
                'cc_subject_id' => 3,
            'name' => 'ترکیبیات (شمارش)',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:46:44',
                'updated_at' => '2026-02-07 16:46:44',
            ),
            11 => 
            array (
                'id' => 13,
                'cc_subject_id' => 4,
                'name' => 'حرکت بر خط راست',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:51:15',
                'updated_at' => '2026-02-07 16:51:15',
            ),
            12 => 
            array (
                'id' => 14,
                'cc_subject_id' => 4,
                'name' => 'دینامیک و حرکت دایره ای',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:51:32',
                'updated_at' => '2026-02-07 16:51:32',
            ),
            13 => 
            array (
                'id' => 15,
                'cc_subject_id' => 4,
                'name' => 'نوسان و موج',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:51:42',
                'updated_at' => '2026-02-07 16:51:42',
            ),
            14 => 
            array (
                'id' => 16,
                'cc_subject_id' => 4,
                'name' => 'برهم کنش های موج',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:51:56',
                'updated_at' => '2026-02-07 16:51:56',
            ),
            15 => 
            array (
                'id' => 17,
                'cc_subject_id' => 4,
                'name' => 'آشنایی با فیزیک اتمی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:52:18',
                'updated_at' => '2026-02-07 16:52:18',
            ),
            16 => 
            array (
                'id' => 18,
                'cc_subject_id' => 4,
                'name' => 'آشنایی با فیزیک هسته ای',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:52:43',
                'updated_at' => '2026-02-07 16:54:14',
            ),
            17 => 
            array (
                'id' => 19,
                'cc_subject_id' => 5,
                'name' => 'فصل 1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:05:45',
                'updated_at' => '2026-02-07 17:08:19',
            ),
            18 => 
            array (
                'id' => 20,
                'cc_subject_id' => 5,
                'name' => 'فصل 2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:08:31',
                'updated_at' => '2026-02-07 17:08:31',
            ),
            19 => 
            array (
                'id' => 21,
                'cc_subject_id' => 5,
                'name' => 'فصل 3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:08:41',
                'updated_at' => '2026-02-07 17:08:41',
            ),
            20 => 
            array (
                'id' => 22,
                'cc_subject_id' => 5,
                'name' => 'فصل 4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:08:53',
                'updated_at' => '2026-02-07 17:08:53',
            ),
            21 => 
            array (
                'id' => 24,
                'cc_subject_id' => 6,
                'name' => 'ادبیات تعلیمی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:11:48',
                'updated_at' => '2026-02-07 17:11:48',
            ),
            22 => 
            array (
                'id' => 25,
                'cc_subject_id' => 6,
                'name' => 'ادبیات پایداری',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:11:58',
                'updated_at' => '2026-02-07 17:12:13',
            ),
            23 => 
            array (
                'id' => 26,
                'cc_subject_id' => 6,
                'name' => 'ادبیات غنایی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:12:06',
                'updated_at' => '2026-02-07 17:12:17',
            ),
            24 => 
            array (
                'id' => 27,
                'cc_subject_id' => 6,
                'name' => 'ادبیات سفر و زندگی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:15:30',
                'updated_at' => '2026-02-07 17:15:30',
            ),
            25 => 
            array (
                'id' => 28,
                'cc_subject_id' => 6,
                'name' => 'ادبیات انقلاب اسلامی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:15:44',
                'updated_at' => '2026-02-07 17:15:44',
            ),
            26 => 
            array (
                'id' => 29,
                'cc_subject_id' => 6,
                'name' => 'ادبیات حماسی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:15:51',
                'updated_at' => '2026-02-07 17:15:51',
            ),
            27 => 
            array (
                'id' => 30,
                'cc_subject_id' => 6,
                'name' => 'ادبیات داستانی',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:16:07',
                'updated_at' => '2026-02-07 17:16:07',
            ),
            28 => 
            array (
                'id' => 31,
                'cc_subject_id' => 6,
                'name' => 'ادبیات جهان ',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:17:35',
                'updated_at' => '2026-02-07 17:17:35',
            ),
            29 => 
            array (
                'id' => 32,
                'cc_subject_id' => 7,
                'name' => 'درس اول',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:22:46',
                'updated_at' => '2026-02-07 17:22:46',
            ),
            30 => 
            array (
                'id' => 33,
                'cc_subject_id' => 7,
                'name' => 'درس دوم',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:22:52',
                'updated_at' => '2026-02-07 17:22:52',
            ),
            31 => 
            array (
                'id' => 34,
                'cc_subject_id' => 7,
                'name' => 'درس سوم',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:22:59',
                'updated_at' => '2026-02-07 17:22:59',
            ),
            32 => 
            array (
                'id' => 35,
                'cc_subject_id' => 7,
                'name' => 'درس چهارم',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:23:21',
                'updated_at' => '2026-02-07 17:23:21',
            ),
            33 => 
            array (
                'id' => 36,
                'cc_subject_id' => 8,
                'name' => 'هستی بخش',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:30:18',
                'updated_at' => '2026-02-07 17:30:18',
            ),
            34 => 
            array (
                'id' => 37,
                'cc_subject_id' => 8,
                'name' => 'یگانه بی همتا',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:30:26',
                'updated_at' => '2026-02-07 17:30:26',
            ),
            35 => 
            array (
                'id' => 38,
                'cc_subject_id' => 8,
                'name' => 'توحید و سبک زندگی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:30:41',
                'updated_at' => '2026-02-07 17:30:41',
            ),
            36 => 
            array (
                'id' => 39,
                'cc_subject_id' => 8,
                'name' => 'فقط برای او',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:30:53',
                'updated_at' => '2026-02-07 17:30:53',
            ),
            37 => 
            array (
                'id' => 40,
                'cc_subject_id' => 8,
                'name' => 'قدرت پرواز',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:31:02',
                'updated_at' => '2026-02-07 17:31:02',
            ),
            38 => 
            array (
                'id' => 41,
                'cc_subject_id' => 8,
                'name' => 'سنت های خداوند در زندگی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:31:27',
                'updated_at' => '2026-02-07 17:31:27',
            ),
            39 => 
            array (
                'id' => 42,
                'cc_subject_id' => 8,
                'name' => 'بازگشت',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:31:36',
                'updated_at' => '2026-02-07 17:31:36',
            ),
            40 => 
            array (
                'id' => 43,
                'cc_subject_id' => 8,
                'name' => 'زندگی در دنیای امروز و عمل به احکام الهیی',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:31:56',
                'updated_at' => '2026-02-07 17:31:56',
            ),
            41 => 
            array (
                'id' => 44,
                'cc_subject_id' => 8,
                'name' => 'پایه های استوار',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:09',
                'updated_at' => '2026-02-07 17:32:09',
            ),
            42 => 
            array (
                'id' => 45,
                'cc_subject_id' => 8,
                'name' => 'تمدن جدید و مسئولیت ما',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:32',
                'updated_at' => '2026-02-07 17:32:32',
            ),
            43 => 
            array (
                'id' => 46,
                'cc_subject_id' => 9,
                'name' => 'درس اول ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:38:01',
                'updated_at' => '2026-02-07 17:38:01',
            ),
            44 => 
            array (
                'id' => 47,
                'cc_subject_id' => 9,
                'name' => 'درس دوم ',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:38:08',
                'updated_at' => '2026-02-07 17:38:08',
            ),
            45 => 
            array (
                'id' => 48,
                'cc_subject_id' => 9,
                'name' => 'درس سوم',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:38:14',
                'updated_at' => '2026-02-07 17:38:14',
            ),
            46 => 
            array (
                'id' => 49,
                'cc_subject_id' => 10,
                'name' => ' سلامت',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:54:10',
                'updated_at' => '2026-02-07 17:54:10',
            ),
            47 => 
            array (
                'id' => 50,
                'cc_subject_id' => 10,
                'name' => 'تغذیه سالم و بهداشت مواد غذایی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:54:25',
                'updated_at' => '2026-02-07 17:54:25',
            ),
            48 => 
            array (
                'id' => 51,
                'cc_subject_id' => 10,
                'name' => 'پیشگیری از بیماری‎ها',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:54:38',
                'updated_at' => '2026-02-07 18:01:15',
            ),
            49 => 
            array (
                'id' => 52,
                'cc_subject_id' => 10,
                'name' => 'بهداشت در دوران نوجوانی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:54:51',
                'updated_at' => '2026-02-07 17:54:51',
            ),
            50 => 
            array (
                'id' => 53,
                'cc_subject_id' => 10,
                'name' => 'پیشگیری از رفتارهای پرخطر',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:55:00',
                'updated_at' => '2026-02-07 17:55:00',
            ),
            51 => 
            array (
                'id' => 54,
                'cc_subject_id' => 10,
                'name' => 'محیط کار و زندگی سالم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:55:57',
                'updated_at' => '2026-02-07 17:55:57',
            ),
            52 => 
            array (
                'id' => 55,
                'cc_subject_id' => 11,
                'name' => 'کنش‌های ما',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:02:12',
                'updated_at' => '2026-02-07 18:02:12',
            ),
            53 => 
            array (
                'id' => 56,
                'cc_subject_id' => 11,
                'name' => 'پدیده‌های اجتماعی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:02:21',
                'updated_at' => '2026-02-07 18:02:21',
            ),
            54 => 
            array (
                'id' => 57,
                'cc_subject_id' => 11,
                'name' => 'جامعه و فرهنگ',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:02:29',
                'updated_at' => '2026-02-07 18:02:29',
            ),
            55 => 
            array (
                'id' => 58,
                'cc_subject_id' => 11,
                'name' => 'ارزیابی فرهنگ‌ها',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:02:36',
                'updated_at' => '2026-02-07 18:02:36',
            ),
            56 => 
            array (
                'id' => 59,
                'cc_subject_id' => 11,
                'name' => 'هویت فردی و اجتماعی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:02:44',
                'updated_at' => '2026-02-07 18:02:44',
            ),
            57 => 
            array (
                'id' => 60,
                'cc_subject_id' => 11,
                'name' => 'بازتولید هویت اجتماعی',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:02:55',
                'updated_at' => '2026-02-07 18:02:55',
            ),
            58 => 
            array (
                'id' => 61,
                'cc_subject_id' => 11,
                'name' => 'تحولات هویتی جامعه',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:03:05',
                'updated_at' => '2026-02-07 18:03:05',
            ),
            59 => 
            array (
                'id' => 62,
                'cc_subject_id' => 11,
                'name' => 'بعد فرهنگی هویت ایرانی',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:03:15',
                'updated_at' => '2026-02-07 18:03:15',
            ),
            60 => 
            array (
                'id' => 63,
                'cc_subject_id' => 11,
                'name' => 'بعد سیاسی هویت ایرانی',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:03:29',
                'updated_at' => '2026-02-07 18:03:29',
            ),
            61 => 
            array (
                'id' => 64,
                'cc_subject_id' => 11,
                'name' => 'ابعاد جمعیتی و اقتصادی هویت ایرانی',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:03:41',
                'updated_at' => '2026-02-07 18:03:41',
            ),
            62 => 
            array (
                'id' => 65,
                'cc_subject_id' => 116,
                'name' => 'جبر و معادله',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:31:36',
                'updated_at' => '2026-02-07 18:31:36',
            ),
            63 => 
            array (
                'id' => 66,
                'cc_subject_id' => 116,
                'name' => 'فصل دوم : تابع',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:31:52',
                'updated_at' => '2026-02-07 18:31:52',
            ),
            64 => 
            array (
                'id' => 67,
                'cc_subject_id' => 116,
                'name' => 'تابع نمایی و لگاریتمی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:32:07',
                'updated_at' => '2026-02-07 18:32:07',
            ),
            65 => 
            array (
                'id' => 68,
                'cc_subject_id' => 116,
                'name' => 'مثلثات',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:32:36',
                'updated_at' => '2026-02-07 18:32:36',
            ),
            66 => 
            array (
                'id' => 69,
                'cc_subject_id' => 116,
                'name' => 'حد و پیوستگی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:32:56',
                'updated_at' => '2026-02-07 18:32:56',
            ),
            67 => 
            array (
                'id' => 70,
                'cc_subject_id' => 117,
                'name' => 'دایره',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:43:01',
                'updated_at' => '2026-02-07 18:43:01',
            ),
            68 => 
            array (
                'id' => 71,
                'cc_subject_id' => 117,
                'name' => 'تبدیل های هندسی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:43:09',
                'updated_at' => '2026-02-07 18:43:09',
            ),
            69 => 
            array (
                'id' => 72,
                'cc_subject_id' => 117,
                'name' => 'روابط طولی در مثلث‌‎',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:43:20',
                'updated_at' => '2026-02-07 18:43:20',
            ),
            70 => 
            array (
                'id' => 73,
                'cc_subject_id' => 118,
                'name' => 'قدر هدایای زمینی را بدانیم',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:46:15',
                'updated_at' => '2026-02-07 18:46:15',
            ),
            71 => 
            array (
                'id' => 74,
                'cc_subject_id' => 118,
                'name' => 'در پی غذای سالم',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:46:21',
                'updated_at' => '2026-02-07 18:46:21',
            ),
            72 => 
            array (
                'id' => 75,
                'cc_subject_id' => 118,
                'name' => 'پوشاک، نیازی پایان ناپذیر‎',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:46:37',
                'updated_at' => '2026-02-07 18:46:37',
            ),
            73 => 
            array (
                'id' => 76,
                'cc_subject_id' => 119,
                'name' => ' الکتریسیته ساکن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:51:09',
                'updated_at' => '2026-02-07 18:51:09',
            ),
            74 => 
            array (
                'id' => 77,
                'cc_subject_id' => 119,
                'name' => 'جریان الکتریکی و مدار های جریان مستقیم',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:51:19',
                'updated_at' => '2026-02-07 18:51:19',
            ),
            75 => 
            array (
                'id' => 78,
                'cc_subject_id' => 119,
                'name' => ' مغناطیس',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:51:30',
                'updated_at' => '2026-02-07 18:51:30',
            ),
            76 => 
            array (
                'id' => 79,
                'cc_subject_id' => 119,
                'name' => 'القای الکترومغناطیسی و جریان متناوب',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:51:46',
                'updated_at' => '2026-02-07 18:51:46',
            ),
            77 => 
            array (
                'id' => 80,
                'cc_subject_id' => 120,
                'name' => ' مبانی ریاضی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:58:15',
                'updated_at' => '2026-02-07 18:58:15',
            ),
            78 => 
            array (
                'id' => 81,
                'cc_subject_id' => 120,
                'name' => 'احتمال',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:58:26',
                'updated_at' => '2026-02-07 18:58:26',
            ),
            79 => 
            array (
                'id' => 82,
                'cc_subject_id' => 120,
                'name' => 'آمار توصیفی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:58:34',
                'updated_at' => '2026-02-07 18:58:34',
            ),
            80 => 
            array (
                'id' => 83,
                'cc_subject_id' => 120,
                'name' => 'آمار استنباطی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:58:43',
                'updated_at' => '2026-02-07 18:58:43',
            ),
            81 => 
            array (
                'id' => 84,
                'cc_subject_id' => 122,
                'name' => 'ادبیات تعلیمی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:25:24',
                'updated_at' => '2026-02-07 19:25:24',
            ),
            82 => 
            array (
                'id' => 85,
                'cc_subject_id' => 122,
                'name' => ' ادبیات پایداری',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:25:32',
                'updated_at' => '2026-02-07 19:25:32',
            ),
            83 => 
            array (
                'id' => 86,
                'cc_subject_id' => 122,
                'name' => ' ادبیات غنایی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:25:44',
                'updated_at' => '2026-02-07 19:25:44',
            ),
            84 => 
            array (
                'id' => 87,
                'cc_subject_id' => 122,
                'name' => ' ادبیات سفر و زندگی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:25:53',
                'updated_at' => '2026-02-07 19:25:53',
            ),
            85 => 
            array (
                'id' => 88,
                'cc_subject_id' => 122,
                'name' => 'ادبیات انقلاب اسلامی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:26:08',
                'updated_at' => '2026-02-07 19:26:08',
            ),
            86 => 
            array (
                'id' => 89,
                'cc_subject_id' => 122,
                'name' => 'ادبیات حماسی',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:26:20',
                'updated_at' => '2026-02-07 19:26:20',
            ),
            87 => 
            array (
                'id' => 90,
                'cc_subject_id' => 122,
                'name' => 'ادبیات داستانی',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:26:31',
                'updated_at' => '2026-02-07 19:26:31',
            ),
            88 => 
            array (
                'id' => 91,
                'cc_subject_id' => 122,
                'name' => 'ادبیات جهان',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:26:42',
                'updated_at' => '2026-02-07 19:26:42',
            ),
            89 => 
            array (
                'id' => 92,
                'cc_subject_id' => 124,
                'name' => 'هدایت الهی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:55:50',
                'updated_at' => '2026-02-07 19:55:50',
            ),
            90 => 
            array (
                'id' => 93,
                'cc_subject_id' => 124,
                'name' => 'تداوم هدایت',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:55:58',
                'updated_at' => '2026-02-07 19:55:58',
            ),
            91 => 
            array (
                'id' => 94,
                'cc_subject_id' => 124,
                'name' => 'معجزه جاویدان',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:56:06',
                'updated_at' => '2026-02-07 19:56:06',
            ),
            92 => 
            array (
                'id' => 95,
                'cc_subject_id' => 124,
            'name' => 'مسئولیت های پیامبر (ص)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:56:17',
                'updated_at' => '2026-02-07 19:56:17',
            ),
            93 => 
            array (
                'id' => 96,
                'cc_subject_id' => 124,
                'name' => 'امامت، تداوم رسالت',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:56:25',
                'updated_at' => '2026-02-07 19:56:25',
            ),
            94 => 
            array (
                'id' => 97,
                'cc_subject_id' => 124,
                'name' => ' پیشوایان اسوه',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:56:36',
                'updated_at' => '2026-02-07 19:56:36',
            ),
            95 => 
            array (
                'id' => 98,
                'cc_subject_id' => 124,
            'name' => ' وضعیت فرهنگی، اجتماعی و سیاسی مسلمانان، پس از رسول خدا (ص)',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:56:53',
                'updated_at' => '2026-02-07 19:56:53',
            ),
            96 => 
            array (
                'id' => 99,
                'cc_subject_id' => 124,
                'name' => 'احیای ارزش های راستین',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:57:03',
                'updated_at' => '2026-02-07 19:57:03',
            ),
            97 => 
            array (
                'id' => 100,
                'cc_subject_id' => 124,
                'name' => 'عصر غیبت',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:57:14',
                'updated_at' => '2026-02-07 19:57:14',
            ),
            98 => 
            array (
                'id' => 101,
                'cc_subject_id' => 124,
                'name' => 'مرجعیت و ولایت فقیه',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:57:27',
                'updated_at' => '2026-02-07 19:57:27',
            ),
            99 => 
            array (
                'id' => 102,
                'cc_subject_id' => 124,
                'name' => 'عزّت نفس',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:57:37',
                'updated_at' => '2026-02-07 19:57:37',
            ),
            100 => 
            array (
                'id' => 103,
                'cc_subject_id' => 124,
                'name' => 'پیوند مقدس',
                'order' => 11,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:57:49',
                'updated_at' => '2026-02-07 19:57:49',
            ),
            101 => 
            array (
                'id' => 104,
                'cc_subject_id' => 125,
                'name' => 'درس اول',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:09:11',
                'updated_at' => '2026-02-07 20:09:11',
            ),
            102 => 
            array (
                'id' => 105,
                'cc_subject_id' => 125,
                'name' => 'درس دوم',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:09:23',
                'updated_at' => '2026-02-07 20:09:23',
            ),
            103 => 
            array (
                'id' => 106,
                'cc_subject_id' => 125,
                'name' => 'درس سوم',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:09:30',
                'updated_at' => '2026-02-07 20:09:30',
            ),
            104 => 
            array (
                'id' => 107,
                'cc_subject_id' => 125,
                'name' => 'درس چهارم',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:09:41',
                'updated_at' => '2026-02-07 20:09:41',
            ),
            105 => 
            array (
                'id' => 108,
                'cc_subject_id' => 125,
                'name' => 'درس پنجم',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:09:54',
                'updated_at' => '2026-02-07 20:09:54',
            ),
            106 => 
            array (
                'id' => 109,
                'cc_subject_id' => 125,
                'name' => 'درس ششم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:10:01',
                'updated_at' => '2026-02-07 20:10:01',
            ),
            107 => 
            array (
                'id' => 110,
                'cc_subject_id' => 125,
                'name' => 'درس هفتم',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:10:11',
                'updated_at' => '2026-02-07 20:10:11',
            ),
            108 => 
            array (
                'id' => 111,
                'cc_subject_id' => 127,
                'name' => 'درس اول',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:53:56',
                'updated_at' => '2026-02-07 20:53:56',
            ),
            109 => 
            array (
                'id' => 112,
                'cc_subject_id' => 127,
                'name' => 'درس دوم',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:54:02',
                'updated_at' => '2026-02-07 20:54:02',
            ),
            110 => 
            array (
                'id' => 113,
                'cc_subject_id' => 127,
                'name' => 'درس سوم',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:54:07',
                'updated_at' => '2026-02-07 20:54:07',
            ),
            111 => 
            array (
                'id' => 114,
                'cc_subject_id' => 127,
                'name' => 'درس چهارم',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:54:19',
                'updated_at' => '2026-02-07 20:54:19',
            ),
            112 => 
            array (
                'id' => 115,
                'cc_subject_id' => 127,
                'name' => 'درس پنجم',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:54:30',
                'updated_at' => '2026-02-07 20:54:30',
            ),
            113 => 
            array (
                'id' => 116,
                'cc_subject_id' => 127,
                'name' => 'درس ششم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:54:40',
                'updated_at' => '2026-02-07 20:54:40',
            ),
            114 => 
            array (
                'id' => 117,
                'cc_subject_id' => 127,
                'name' => 'درس هفتم',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:54:48',
                'updated_at' => '2026-02-07 20:54:48',
            ),
            115 => 
            array (
                'id' => 118,
                'cc_subject_id' => 127,
                'name' => 'درس هشتم',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:54:58',
                'updated_at' => '2026-02-07 20:54:58',
            ),
            116 => 
            array (
                'id' => 119,
                'cc_subject_id' => 127,
                'name' => 'درس نهم',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:55:04',
                'updated_at' => '2026-02-07 20:55:04',
            ),
            117 => 
            array (
                'id' => 120,
                'cc_subject_id' => 127,
                'name' => 'درس دهم',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:55:13',
                'updated_at' => '2026-02-07 20:55:13',
            ),
            118 => 
            array (
                'id' => 121,
                'cc_subject_id' => 127,
                'name' => 'درس یازدهم',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:55:25',
                'updated_at' => '2026-02-07 20:55:25',
            ),
            119 => 
            array (
                'id' => 122,
                'cc_subject_id' => 127,
                'name' => 'درس دوازدهم',
                'order' => 11,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:55:36',
                'updated_at' => '2026-02-07 20:55:36',
            ),
            120 => 
            array (
                'id' => 123,
                'cc_subject_id' => 127,
                'name' => 'درس سیزدهم',
                'order' => 12,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:55:50',
                'updated_at' => '2026-02-07 20:55:50',
            ),
            121 => 
            array (
                'id' => 124,
                'cc_subject_id' => 127,
                'name' => 'درس چهاردهم',
                'order' => 13,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:55:59',
                'updated_at' => '2026-02-07 20:55:59',
            ),
            122 => 
            array (
                'id' => 125,
                'cc_subject_id' => 127,
                'name' => 'درس پانزدهم',
                'order' => 14,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:56:09',
                'updated_at' => '2026-02-07 20:56:09',
            ),
            123 => 
            array (
                'id' => 126,
                'cc_subject_id' => 127,
                'name' => 'درس شانزدهم',
                'order' => 15,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:56:17',
                'updated_at' => '2026-02-07 20:56:17',
            ),
            124 => 
            array (
                'id' => 127,
                'cc_subject_id' => 127,
                'name' => 'درس هفدهم',
                'order' => 16,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:56:26',
                'updated_at' => '2026-02-07 20:56:26',
            ),
            125 => 
            array (
                'id' => 128,
                'cc_subject_id' => 127,
                'name' => 'دس هجدهم',
                'order' => 17,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:56:42',
                'updated_at' => '2026-02-07 20:56:42',
            ),
            126 => 
            array (
                'id' => 129,
                'cc_subject_id' => 127,
                'name' => 'درس نوزدهم',
                'order' => 18,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:57:03',
                'updated_at' => '2026-02-07 20:57:03',
            ),
            127 => 
            array (
                'id' => 130,
                'cc_subject_id' => 127,
                'name' => 'درس بیستم ',
                'order' => 19,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:57:17',
                'updated_at' => '2026-02-07 20:57:17',
            ),
            128 => 
            array (
                'id' => 131,
                'cc_subject_id' => 127,
                'name' => 'درس بیستم و یکم ',
                'order' => 20,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:57:51',
                'updated_at' => '2026-02-07 20:57:51',
            ),
            129 => 
            array (
                'id' => 132,
                'cc_subject_id' => 127,
                'name' => 'درس بیست و دوم',
                'order' => 21,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:58:44',
                'updated_at' => '2026-02-07 20:58:44',
            ),
            130 => 
            array (
                'id' => 133,
                'cc_subject_id' => 127,
                'name' => 'درس بیست و سوم',
                'order' => 22,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:58:56',
                'updated_at' => '2026-02-07 20:58:56',
            ),
            131 => 
            array (
                'id' => 134,
                'cc_subject_id' => 127,
                'name' => 'درس بیست و چهارم ',
                'order' => 23,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:59:18',
                'updated_at' => '2026-02-07 20:59:18',
            ),
            132 => 
            array (
                'id' => 135,
                'cc_subject_id' => 127,
                'name' => 'درس بیست و پنجم',
                'order' => 24,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:59:34',
                'updated_at' => '2026-02-07 20:59:34',
            ),
            133 => 
            array (
                'id' => 136,
                'cc_subject_id' => 127,
                'name' => 'درس بیست و ششم ',
                'order' => 26,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:59:52',
                'updated_at' => '2026-02-07 20:59:52',
            ),
            134 => 
            array (
                'id' => 137,
                'cc_subject_id' => 128,
                'name' => 'درس اول',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:08:19',
                'updated_at' => '2026-02-07 21:08:19',
            ),
            135 => 
            array (
                'id' => 138,
                'cc_subject_id' => 128,
                'name' => 'درس دوم',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:08:25',
                'updated_at' => '2026-02-07 21:08:25',
            ),
            136 => 
            array (
                'id' => 139,
                'cc_subject_id' => 128,
                'name' => 'درس سوم',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:08:29',
                'updated_at' => '2026-02-07 21:08:29',
            ),
            137 => 
            array (
                'id' => 140,
                'cc_subject_id' => 130,
                'name' => 'آفرینش کیهان و تکوین زمین',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:11:45',
                'updated_at' => '2026-02-07 21:11:45',
            ),
            138 => 
            array (
                'id' => 141,
                'cc_subject_id' => 130,
                'name' => 'منابع معدنی و ذخایر انرژی، زیربنای تمدن و توسعه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:11:53',
                'updated_at' => '2026-02-07 21:11:53',
            ),
            139 => 
            array (
                'id' => 142,
                'cc_subject_id' => 130,
                'name' => 'منابع آب و خاک',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:12:01',
                'updated_at' => '2026-02-07 21:12:01',
            ),
            140 => 
            array (
                'id' => 143,
                'cc_subject_id' => 130,
                'name' => 'پویایی زمین',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:12:10',
                'updated_at' => '2026-02-07 21:12:10',
            ),
            141 => 
            array (
                'id' => 144,
                'cc_subject_id' => 130,
                'name' => 'زمین‌شناسی و سلامت‌',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:12:20',
                'updated_at' => '2026-02-07 21:12:20',
            ),
            142 => 
            array (
                'id' => 145,
                'cc_subject_id' => 130,
                'name' => 'زمین‌شناسی و سازه‌های مهندسی‌',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:12:34',
                'updated_at' => '2026-02-07 21:12:34',
            ),
            143 => 
            array (
                'id' => 146,
                'cc_subject_id' => 130,
                'name' => ' زمین‌شناسی ایران',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:12:50',
                'updated_at' => '2026-02-07 21:12:50',
            ),
            144 => 
            array (
                'id' => 147,
                'cc_subject_id' => 131,
                'name' => 'درس اول ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:26:08',
                'updated_at' => '2026-02-07 21:26:08',
            ),
            145 => 
            array (
                'id' => 148,
                'cc_subject_id' => 131,
                'name' => 'درس دوم',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:26:13',
                'updated_at' => '2026-02-07 21:26:32',
            ),
            146 => 
            array (
                'id' => 149,
                'cc_subject_id' => 131,
                'name' => 'درس سوم',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:26:22',
                'updated_at' => '2026-02-07 21:26:36',
            ),
            147 => 
            array (
                'id' => 150,
                'cc_subject_id' => 131,
                'name' => 'درس جهارم',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:26:46',
                'updated_at' => '2026-02-07 21:26:46',
            ),
            148 => 
            array (
                'id' => 151,
                'cc_subject_id' => 131,
                'name' => 'درس پنجم',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:27:02',
                'updated_at' => '2026-02-07 21:27:02',
            ),
            149 => 
            array (
                'id' => 152,
                'cc_subject_id' => 131,
                'name' => 'درس ششم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:27:13',
                'updated_at' => '2026-02-07 21:27:13',
            ),
            150 => 
            array (
                'id' => 153,
                'cc_subject_id' => 131,
                'name' => 'درس هفتم',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:27:20',
                'updated_at' => '2026-02-07 21:27:31',
            ),
            151 => 
            array (
                'id' => 154,
                'cc_subject_id' => 100,
                'name' => 'مجموعه، الگو و دنباله',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:49:21',
                'updated_at' => '2026-02-08 14:49:21',
            ),
            152 => 
            array (
                'id' => 155,
                'cc_subject_id' => 100,
                'name' => 'مثلثات',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:49:33',
                'updated_at' => '2026-02-08 14:49:33',
            ),
            153 => 
            array (
                'id' => 156,
                'cc_subject_id' => 100,
                'name' => 'توان های گویا و عبارت های جبری',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:49:43',
                'updated_at' => '2026-02-08 14:49:43',
            ),
            154 => 
            array (
                'id' => 157,
                'cc_subject_id' => 100,
                'name' => 'معادله ها و نامعادله ها',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:49:52',
                'updated_at' => '2026-02-08 14:49:52',
            ),
            155 => 
            array (
                'id' => 158,
                'cc_subject_id' => 100,
                'name' => 'تابع',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:50:04',
                'updated_at' => '2026-02-08 14:50:04',
            ),
            156 => 
            array (
                'id' => 159,
                'cc_subject_id' => 100,
                'name' => 'شمارش، بدون شمردن',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:50:18',
                'updated_at' => '2026-02-08 14:50:18',
            ),
            157 => 
            array (
                'id' => 160,
                'cc_subject_id' => 100,
                'name' => 'آمار و احتمال',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:50:53',
                'updated_at' => '2026-02-08 14:50:53',
            ),
            158 => 
            array (
                'id' => 161,
                'cc_subject_id' => 101,
                'name' => 'ترسیم های هندسی و استدلال',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:03:43',
                'updated_at' => '2026-02-08 15:03:43',
            ),
            159 => 
            array (
                'id' => 162,
                'cc_subject_id' => 101,
                'name' => 'تالس و تشابه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:03:51',
                'updated_at' => '2026-02-08 15:03:51',
            ),
            160 => 
            array (
                'id' => 163,
                'cc_subject_id' => 101,
                'name' => 'چند ضلعی ها',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:04:01',
                'updated_at' => '2026-02-08 15:04:01',
            ),
            161 => 
            array (
                'id' => 164,
                'cc_subject_id' => 101,
                'name' => 'تجسم فضایی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:04:13',
                'updated_at' => '2026-02-08 15:04:13',
            ),
            162 => 
            array (
                'id' => 165,
                'cc_subject_id' => 102,
                'name' => 'کیهان زادگاه الفبای هستی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:07:00',
                'updated_at' => '2026-02-08 15:07:00',
            ),
            163 => 
            array (
                'id' => 166,
                'cc_subject_id' => 102,
                'name' => 'ردّ پای گازها در زندگی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:07:09',
                'updated_at' => '2026-02-08 15:07:09',
            ),
            164 => 
            array (
                'id' => 167,
                'cc_subject_id' => 102,
                'name' => ' آب، آهنگ زندگی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:07:25',
                'updated_at' => '2026-02-08 15:07:25',
            ),
            165 => 
            array (
                'id' => 168,
                'cc_subject_id' => 103,
                'name' => 'فیزیک و اندازه‌گیری',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:13:02',
                'updated_at' => '2026-02-08 15:13:02',
            ),
            166 => 
            array (
                'id' => 169,
                'cc_subject_id' => 103,
                'name' => 'ویژگی‌های فیزیکی مواد',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:13:11',
                'updated_at' => '2026-02-08 15:13:11',
            ),
            167 => 
            array (
                'id' => 170,
                'cc_subject_id' => 103,
                'name' => 'کار، انرژی و توان',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:13:22',
                'updated_at' => '2026-02-08 15:13:22',
            ),
            168 => 
            array (
                'id' => 171,
                'cc_subject_id' => 103,
                'name' => 'دما و گرما',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:13:30',
                'updated_at' => '2026-02-08 15:13:30',
            ),
            169 => 
            array (
                'id' => 172,
                'cc_subject_id' => 103,
                'name' => 'ترمودینامیک',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:13:39',
                'updated_at' => '2026-02-08 15:13:39',
            ),
            170 => 
            array (
                'id' => 173,
                'cc_subject_id' => 105,
                'name' => 'ادبیات تعلیمی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:39:04',
                'updated_at' => '2026-02-08 15:39:04',
            ),
            171 => 
            array (
                'id' => 174,
                'cc_subject_id' => 105,
                'name' => 'ادبیات پایداری',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:39:15',
                'updated_at' => '2026-02-08 15:39:15',
            ),
            172 => 
            array (
                'id' => 175,
                'cc_subject_id' => 105,
                'name' => 'ادبیات غنایی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:39:25',
                'updated_at' => '2026-02-08 15:39:25',
            ),
            173 => 
            array (
                'id' => 176,
                'cc_subject_id' => 105,
                'name' => 'ادبیات سفر و زندگی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:39:33',
                'updated_at' => '2026-02-08 15:39:33',
            ),
            174 => 
            array (
                'id' => 177,
                'cc_subject_id' => 105,
                'name' => 'ادبیات انقلاب اسلامی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:39:44',
                'updated_at' => '2026-02-08 15:39:44',
            ),
            175 => 
            array (
                'id' => 178,
                'cc_subject_id' => 105,
                'name' => 'ادبیات حماسی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:39:55',
                'updated_at' => '2026-02-08 15:39:55',
            ),
            176 => 
            array (
                'id' => 179,
                'cc_subject_id' => 105,
                'name' => 'ادبیات داستانی',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:40:04',
                'updated_at' => '2026-02-08 15:40:04',
            ),
            177 => 
            array (
                'id' => 180,
                'cc_subject_id' => 105,
                'name' => 'ادبیات جهانی',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:40:17',
                'updated_at' => '2026-02-08 15:40:17',
            ),
            178 => 
            array (
                'id' => 181,
                'cc_subject_id' => 107,
                'name' => 'هدف زندگی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:47:09',
                'updated_at' => '2026-02-08 15:47:09',
            ),
            179 => 
            array (
                'id' => 182,
                'cc_subject_id' => 107,
                'name' => 'پر پرواز',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:47:18',
                'updated_at' => '2026-02-08 15:47:18',
            ),
            180 => 
            array (
                'id' => 183,
                'cc_subject_id' => 107,
                'name' => 'پنجره ای به روشنایی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:47:27',
                'updated_at' => '2026-02-08 15:47:27',
            ),
            181 => 
            array (
                'id' => 184,
                'cc_subject_id' => 107,
                'name' => 'آینده روشن',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:01:48',
                'updated_at' => '2026-02-08 16:01:48',
            ),
            182 => 
            array (
                'id' => 185,
                'cc_subject_id' => 107,
                'name' => 'منزلگاه بعد',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:01:56',
                'updated_at' => '2026-02-08 16:01:56',
            ),
            183 => 
            array (
                'id' => 186,
                'cc_subject_id' => 107,
                'name' => 'واقعه بزرگ',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:02:04',
                'updated_at' => '2026-02-08 16:02:04',
            ),
            184 => 
            array (
                'id' => 187,
                'cc_subject_id' => 107,
                'name' => 'فرجام کار',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:02:13',
                'updated_at' => '2026-02-08 16:02:13',
            ),
            185 => 
            array (
                'id' => 188,
                'cc_subject_id' => 107,
                'name' => 'آهنگ سفر',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:02:20',
                'updated_at' => '2026-02-08 16:02:20',
            ),
            186 => 
            array (
                'id' => 189,
                'cc_subject_id' => 107,
                'name' => 'دوستی با خدا',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:02:30',
                'updated_at' => '2026-02-08 16:02:30',
            ),
            187 => 
            array (
                'id' => 190,
                'cc_subject_id' => 107,
                'name' => 'یاری از نماز و روزه',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:02:40',
                'updated_at' => '2026-02-08 16:02:40',
            ),
            188 => 
            array (
                'id' => 191,
                'cc_subject_id' => 107,
                'name' => 'فضیلت آراستگی',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:02:49',
                'updated_at' => '2026-02-08 16:02:49',
            ),
            189 => 
            array (
                'id' => 192,
                'cc_subject_id' => 107,
                'name' => 'زیبایی پوشیدگی',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:01',
                'updated_at' => '2026-02-08 16:03:01',
            ),
            190 => 
            array (
                'id' => 193,
                'cc_subject_id' => 108,
                'name' => 'درس اول',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:11:02',
                'updated_at' => '2026-02-08 16:11:02',
            ),
            191 => 
            array (
                'id' => 194,
                'cc_subject_id' => 108,
                'name' => 'درس دوم',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:11:07',
                'updated_at' => '2026-02-08 16:12:21',
            ),
            192 => 
            array (
                'id' => 195,
                'cc_subject_id' => 108,
                'name' => 'درس سوم',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:11:12',
                'updated_at' => '2026-02-08 16:12:51',
            ),
            193 => 
            array (
                'id' => 196,
                'cc_subject_id' => 108,
                'name' => 'درس چهارم',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:11:16',
                'updated_at' => '2026-02-08 16:12:40',
            ),
            194 => 
            array (
                'id' => 197,
                'cc_subject_id' => 108,
                'name' => 'درس پنجم',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:11:23',
                'updated_at' => '2026-02-08 16:13:03',
            ),
            195 => 
            array (
                'id' => 198,
                'cc_subject_id' => 108,
                'name' => 'درس ششم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:11:27',
                'updated_at' => '2026-02-08 16:13:16',
            ),
            196 => 
            array (
                'id' => 199,
                'cc_subject_id' => 108,
                'name' => 'درس هفتم',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:11:31',
                'updated_at' => '2026-02-08 16:13:24',
            ),
            197 => 
            array (
                'id' => 200,
                'cc_subject_id' => 108,
                'name' => 'درس هشتم',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:11:38',
                'updated_at' => '2026-02-08 16:13:35',
            ),
            198 => 
            array (
                'id' => 205,
                'cc_subject_id' => 110,
                'name' => 'جغرافیا چیست؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:40:14',
                'updated_at' => '2026-02-08 16:40:14',
            ),
            199 => 
            array (
                'id' => 206,
                'cc_subject_id' => 110,
                'name' => 'جغرافیای طبیعی در ایران',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:40:35',
                'updated_at' => '2026-02-08 16:40:35',
            ),
            200 => 
            array (
                'id' => 207,
                'cc_subject_id' => 110,
                'name' => 'جغرافیای انسانی ایران',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:40:43',
                'updated_at' => '2026-02-08 16:40:43',
            ),
            201 => 
            array (
                'id' => 208,
                'cc_subject_id' => 111,
                'name' => 'درس اول',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:48:52',
                'updated_at' => '2026-02-08 16:48:52',
            ),
            202 => 
            array (
                'id' => 209,
                'cc_subject_id' => 111,
                'name' => 'درس دوم',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:48:59',
                'updated_at' => '2026-02-08 16:48:59',
            ),
            203 => 
            array (
                'id' => 210,
                'cc_subject_id' => 111,
                'name' => 'درس سوم',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:49:04',
                'updated_at' => '2026-02-08 16:49:04',
            ),
            204 => 
            array (
                'id' => 211,
                'cc_subject_id' => 111,
                'name' => 'درس چهارم',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:49:12',
                'updated_at' => '2026-02-08 16:49:12',
            ),
            205 => 
            array (
                'id' => 212,
                'cc_subject_id' => 114,
                'name' => 'آموزه های دفاعی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:20:34',
                'updated_at' => '2026-02-08 17:20:34',
            ),
            206 => 
            array (
                'id' => 213,
                'cc_subject_id' => 114,
                'name' => 'فرهنگ دفاع',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:30:46',
                'updated_at' => '2026-02-08 17:30:46',
            ),
            207 => 
            array (
                'id' => 214,
                'cc_subject_id' => 114,
                'name' => 'دفاع نظامی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:30:59',
                'updated_at' => '2026-02-08 17:30:59',
            ),
            208 => 
            array (
                'id' => 215,
                'cc_subject_id' => 114,
                'name' => 'دفاع غیرنظامی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:31:09',
                'updated_at' => '2026-02-08 17:31:09',
            ),
            209 => 
            array (
                'id' => 216,
                'cc_subject_id' => 132,
                'name' => 'تابع',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            210 => 
            array (
                'id' => 217,
                'cc_subject_id' => 132,
                'name' => 'مثلثات',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            211 => 
            array (
                'id' => 218,
                'cc_subject_id' => 132,
                'name' => 'حدهای نامتناهی ـ حد در بینهایت',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            212 => 
            array (
                'id' => 219,
                'cc_subject_id' => 132,
                'name' => 'مشتق',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            213 => 
            array (
                'id' => 220,
                'cc_subject_id' => 132,
                'name' => 'کاربردهای مشتق',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            214 => 
            array (
                'id' => 227,
                'cc_subject_id' => 17,
                'name' => 'حرکت بر خط راست',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            215 => 
            array (
                'id' => 228,
                'cc_subject_id' => 17,
                'name' => 'دینامیک و حرکت دایره ای',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            216 => 
            array (
                'id' => 229,
                'cc_subject_id' => 17,
                'name' => 'نوسان و موج',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            217 => 
            array (
                'id' => 230,
                'cc_subject_id' => 17,
                'name' => 'برهم کنش های موج',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            218 => 
            array (
                'id' => 231,
                'cc_subject_id' => 17,
                'name' => 'آشنایی با فیزیک اتمی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            219 => 
            array (
                'id' => 232,
                'cc_subject_id' => 17,
                'name' => 'آشنایی با فیزیک هسته ای',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            220 => 
            array (
                'id' => 233,
                'cc_subject_id' => 18,
                'name' => 'فصل 1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            221 => 
            array (
                'id' => 234,
                'cc_subject_id' => 18,
                'name' => 'فصل 2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            222 => 
            array (
                'id' => 235,
                'cc_subject_id' => 18,
                'name' => 'فصل 3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            223 => 
            array (
                'id' => 236,
                'cc_subject_id' => 18,
                'name' => 'فصل 4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            224 => 
            array (
                'id' => 237,
                'cc_subject_id' => 19,
                'name' => 'ادبیات تعلیمی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            225 => 
            array (
                'id' => 238,
                'cc_subject_id' => 19,
                'name' => 'ادبیات پایداری',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            226 => 
            array (
                'id' => 239,
                'cc_subject_id' => 19,
                'name' => 'ادبیات غنایی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            227 => 
            array (
                'id' => 240,
                'cc_subject_id' => 19,
                'name' => 'ادبیات سفر و زندگی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            228 => 
            array (
                'id' => 241,
                'cc_subject_id' => 19,
                'name' => 'ادبیات انقلاب اسلامی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            229 => 
            array (
                'id' => 242,
                'cc_subject_id' => 19,
                'name' => 'ادبیات حماسی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            230 => 
            array (
                'id' => 243,
                'cc_subject_id' => 19,
                'name' => 'ادبیات داستانی',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            231 => 
            array (
                'id' => 244,
                'cc_subject_id' => 19,
                'name' => 'ادبیات جهان ',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            232 => 
            array (
                'id' => 245,
                'cc_subject_id' => 20,
                'name' => 'درس اول',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            233 => 
            array (
                'id' => 246,
                'cc_subject_id' => 20,
                'name' => 'درس دوم',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            234 => 
            array (
                'id' => 247,
                'cc_subject_id' => 20,
                'name' => 'درس سوم',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            235 => 
            array (
                'id' => 248,
                'cc_subject_id' => 20,
                'name' => 'درس چهارم',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            236 => 
            array (
                'id' => 249,
                'cc_subject_id' => 21,
                'name' => 'هستی بخش',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            237 => 
            array (
                'id' => 250,
                'cc_subject_id' => 21,
                'name' => 'یگانه بی همتا',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            238 => 
            array (
                'id' => 251,
                'cc_subject_id' => 21,
                'name' => 'توحید و سبک زندگی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            239 => 
            array (
                'id' => 252,
                'cc_subject_id' => 21,
                'name' => 'فقط برای او',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            240 => 
            array (
                'id' => 253,
                'cc_subject_id' => 21,
                'name' => 'قدرت پرواز',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            241 => 
            array (
                'id' => 254,
                'cc_subject_id' => 21,
                'name' => 'سنت های خداوند در زندگی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            242 => 
            array (
                'id' => 255,
                'cc_subject_id' => 21,
                'name' => 'بازگشت',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            243 => 
            array (
                'id' => 256,
                'cc_subject_id' => 21,
                'name' => 'زندگی در دنیای امروز و عمل به احکام الهیی',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            244 => 
            array (
                'id' => 257,
                'cc_subject_id' => 21,
                'name' => 'پایه های استوار',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            245 => 
            array (
                'id' => 258,
                'cc_subject_id' => 21,
                'name' => 'تمدن جدید و مسئولیت ما',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            246 => 
            array (
                'id' => 259,
                'cc_subject_id' => 22,
                'name' => 'درس اول ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            247 => 
            array (
                'id' => 260,
                'cc_subject_id' => 22,
                'name' => 'درس دوم ',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            248 => 
            array (
                'id' => 261,
                'cc_subject_id' => 22,
                'name' => 'درس سوم',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            249 => 
            array (
                'id' => 262,
                'cc_subject_id' => 23,
                'name' => ' سلامت',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            250 => 
            array (
                'id' => 263,
                'cc_subject_id' => 23,
                'name' => 'تغذیه سالم و بهداشت مواد غذایی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            251 => 
            array (
                'id' => 264,
                'cc_subject_id' => 23,
                'name' => 'پیشگیری از بیماری‎ها',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            252 => 
            array (
                'id' => 265,
                'cc_subject_id' => 23,
                'name' => 'بهداشت در دوران نوجوانی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            253 => 
            array (
                'id' => 266,
                'cc_subject_id' => 23,
                'name' => 'پیشگیری از رفتارهای پرخطر',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            254 => 
            array (
                'id' => 267,
                'cc_subject_id' => 23,
                'name' => 'محیط کار و زندگی سالم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            255 => 
            array (
                'id' => 268,
                'cc_subject_id' => 24,
                'name' => 'کنش‌های ما',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            256 => 
            array (
                'id' => 269,
                'cc_subject_id' => 24,
                'name' => 'پدیده‌های اجتماعی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            257 => 
            array (
                'id' => 270,
                'cc_subject_id' => 24,
                'name' => 'جامعه و فرهنگ',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            258 => 
            array (
                'id' => 271,
                'cc_subject_id' => 24,
                'name' => 'ارزیابی فرهنگ‌ها',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            259 => 
            array (
                'id' => 272,
                'cc_subject_id' => 24,
                'name' => 'هویت فردی و اجتماعی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            260 => 
            array (
                'id' => 273,
                'cc_subject_id' => 24,
                'name' => 'بازتولید هویت اجتماعی',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            261 => 
            array (
                'id' => 274,
                'cc_subject_id' => 24,
                'name' => 'تحولات هویتی جامعه',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            262 => 
            array (
                'id' => 275,
                'cc_subject_id' => 24,
                'name' => 'بعد فرهنگی هویت ایرانی',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            263 => 
            array (
                'id' => 276,
                'cc_subject_id' => 24,
                'name' => 'بعد سیاسی هویت ایرانی',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            264 => 
            array (
                'id' => 277,
                'cc_subject_id' => 24,
                'name' => 'ابعاد جمعیتی و اقتصادی هویت ایرانی',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            265 => 
            array (
                'id' => 278,
                'cc_subject_id' => 133,
                'name' => 'مجموعه، الگو و دنباله',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            266 => 
            array (
                'id' => 279,
                'cc_subject_id' => 133,
                'name' => 'مثلثات',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            267 => 
            array (
                'id' => 280,
                'cc_subject_id' => 133,
                'name' => 'توان های گویا و عبارت های جبری',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            268 => 
            array (
                'id' => 281,
                'cc_subject_id' => 133,
                'name' => 'معادله ها و نامعادله ها',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            269 => 
            array (
                'id' => 282,
                'cc_subject_id' => 133,
                'name' => 'تابع',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            270 => 
            array (
                'id' => 283,
                'cc_subject_id' => 133,
                'name' => 'شمارش، بدون شمردن',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            271 => 
            array (
                'id' => 284,
                'cc_subject_id' => 133,
                'name' => 'آمار و احتمال',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            272 => 
            array (
                'id' => 289,
                'cc_subject_id' => 135,
                'name' => 'کیهان زادگاه الفبای هستی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            273 => 
            array (
                'id' => 290,
                'cc_subject_id' => 135,
                'name' => 'ردّ پای گازها در زندگی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            274 => 
            array (
                'id' => 291,
                'cc_subject_id' => 135,
                'name' => ' آب، آهنگ زندگی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            275 => 
            array (
                'id' => 292,
                'cc_subject_id' => 136,
                'name' => 'فیزیک و اندازه‌گیری',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            276 => 
            array (
                'id' => 293,
                'cc_subject_id' => 136,
                'name' => 'ویژگی‌های فیزیکی مواد',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            277 => 
            array (
                'id' => 294,
                'cc_subject_id' => 136,
                'name' => 'کار، انرژی و توان',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            278 => 
            array (
                'id' => 295,
                'cc_subject_id' => 136,
                'name' => 'دما و گرما',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            279 => 
            array (
                'id' => 297,
                'cc_subject_id' => 137,
                'name' => 'ادبیات تعلیمی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            280 => 
            array (
                'id' => 298,
                'cc_subject_id' => 137,
                'name' => 'ادبیات پایداری',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            281 => 
            array (
                'id' => 299,
                'cc_subject_id' => 137,
                'name' => 'ادبیات غنایی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            282 => 
            array (
                'id' => 300,
                'cc_subject_id' => 137,
                'name' => 'ادبیات سفر و زندگی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            283 => 
            array (
                'id' => 301,
                'cc_subject_id' => 137,
                'name' => 'ادبیات انقلاب اسلامی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            284 => 
            array (
                'id' => 302,
                'cc_subject_id' => 137,
                'name' => 'ادبیات حماسی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            285 => 
            array (
                'id' => 303,
                'cc_subject_id' => 137,
                'name' => 'ادبیات داستانی',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            286 => 
            array (
                'id' => 304,
                'cc_subject_id' => 137,
                'name' => 'ادبیات جهانی',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            287 => 
            array (
                'id' => 305,
                'cc_subject_id' => 91,
                'name' => 'هدف زندگی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            288 => 
            array (
                'id' => 306,
                'cc_subject_id' => 91,
                'name' => 'پر پرواز',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            289 => 
            array (
                'id' => 307,
                'cc_subject_id' => 91,
                'name' => 'پنجره ای به روشنایی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            290 => 
            array (
                'id' => 308,
                'cc_subject_id' => 91,
                'name' => 'آینده روشن',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            291 => 
            array (
                'id' => 309,
                'cc_subject_id' => 91,
                'name' => 'منزلگاه بعد',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            292 => 
            array (
                'id' => 310,
                'cc_subject_id' => 91,
                'name' => 'واقعه بزرگ',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            293 => 
            array (
                'id' => 311,
                'cc_subject_id' => 91,
                'name' => 'فرجام کار',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            294 => 
            array (
                'id' => 312,
                'cc_subject_id' => 91,
                'name' => 'آهنگ سفر',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            295 => 
            array (
                'id' => 313,
                'cc_subject_id' => 91,
                'name' => 'دوستی با خدا',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            296 => 
            array (
                'id' => 314,
                'cc_subject_id' => 91,
                'name' => 'یاری از نماز و روزه',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            297 => 
            array (
                'id' => 315,
                'cc_subject_id' => 91,
                'name' => 'فضیلت آراستگی',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            298 => 
            array (
                'id' => 316,
                'cc_subject_id' => 91,
                'name' => 'زیبایی پوشیدگی',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            299 => 
            array (
                'id' => 317,
                'cc_subject_id' => 92,
                'name' => 'درس اول',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            300 => 
            array (
                'id' => 318,
                'cc_subject_id' => 92,
                'name' => 'درس دوم',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            301 => 
            array (
                'id' => 319,
                'cc_subject_id' => 92,
                'name' => 'درس سوم',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            302 => 
            array (
                'id' => 320,
                'cc_subject_id' => 92,
                'name' => 'درس چهارم',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            303 => 
            array (
                'id' => 321,
                'cc_subject_id' => 92,
                'name' => 'درس پنجم',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            304 => 
            array (
                'id' => 322,
                'cc_subject_id' => 92,
                'name' => 'درس ششم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            305 => 
            array (
                'id' => 323,
                'cc_subject_id' => 92,
                'name' => 'درس هفتم',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            306 => 
            array (
                'id' => 324,
                'cc_subject_id' => 92,
                'name' => 'درس هشتم',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            307 => 
            array (
                'id' => 325,
                'cc_subject_id' => 94,
                'name' => 'جغرافیا چیست؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            308 => 
            array (
                'id' => 326,
                'cc_subject_id' => 94,
                'name' => 'جغرافیای طبیعی در ایران',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            309 => 
            array (
                'id' => 327,
                'cc_subject_id' => 94,
                'name' => 'جغرافیای انسانی ایران',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            310 => 
            array (
                'id' => 328,
                'cc_subject_id' => 95,
                'name' => 'درس اول',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            311 => 
            array (
                'id' => 329,
                'cc_subject_id' => 95,
                'name' => 'درس دوم',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            312 => 
            array (
                'id' => 330,
                'cc_subject_id' => 95,
                'name' => 'درس سوم',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            313 => 
            array (
                'id' => 331,
                'cc_subject_id' => 95,
                'name' => 'درس چهارم',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            314 => 
            array (
                'id' => 332,
                'cc_subject_id' => 98,
                'name' => 'آموزه های دفاعی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            315 => 
            array (
                'id' => 333,
                'cc_subject_id' => 98,
                'name' => 'فرهنگ دفاع',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            316 => 
            array (
                'id' => 334,
                'cc_subject_id' => 98,
                'name' => 'دفاع نظامی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            317 => 
            array (
                'id' => 335,
                'cc_subject_id' => 98,
                'name' => 'دفاع غیرنظامی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            318 => 
            array (
                'id' => 336,
                'cc_subject_id' => 138,
                'name' => 'جبر و معادله',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            319 => 
            array (
                'id' => 337,
                'cc_subject_id' => 138,
                'name' => 'فصل دوم : تابع',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            320 => 
            array (
                'id' => 338,
                'cc_subject_id' => 138,
                'name' => 'تابع نمایی و لگاریتمی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            321 => 
            array (
                'id' => 339,
                'cc_subject_id' => 138,
                'name' => 'مثلثات',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            322 => 
            array (
                'id' => 340,
                'cc_subject_id' => 138,
                'name' => 'حد و پیوستگی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            323 => 
            array (
                'id' => 344,
                'cc_subject_id' => 69,
                'name' => 'قدر هدایای زمینی را بدانیم',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            324 => 
            array (
                'id' => 345,
                'cc_subject_id' => 69,
                'name' => 'در پی غذای سالم',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            325 => 
            array (
                'id' => 346,
                'cc_subject_id' => 69,
                'name' => 'پوشاک، نیازی پایان ناپذیر‎',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            326 => 
            array (
                'id' => 347,
                'cc_subject_id' => 140,
                'name' => ' الکتریسیته ساکن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            327 => 
            array (
                'id' => 348,
                'cc_subject_id' => 140,
                'name' => 'جریان الکتریکی و مدار های جریان مستقیم',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            328 => 
            array (
                'id' => 349,
                'cc_subject_id' => 140,
                'name' => ' مغناطیس',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            329 => 
            array (
                'id' => 350,
                'cc_subject_id' => 140,
                'name' => 'القای الکترومغناطیسی و جریان متناوب',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            330 => 
            array (
                'id' => 355,
                'cc_subject_id' => 142,
                'name' => 'ادبیات تعلیمی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            331 => 
            array (
                'id' => 356,
                'cc_subject_id' => 142,
                'name' => ' ادبیات پایداری',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            332 => 
            array (
                'id' => 357,
                'cc_subject_id' => 142,
                'name' => ' ادبیات غنایی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            333 => 
            array (
                'id' => 358,
                'cc_subject_id' => 142,
                'name' => ' ادبیات سفر و زندگی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            334 => 
            array (
                'id' => 359,
                'cc_subject_id' => 142,
                'name' => 'ادبیات انقلاب اسلامی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            335 => 
            array (
                'id' => 360,
                'cc_subject_id' => 142,
                'name' => 'ادبیات حماسی',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            336 => 
            array (
                'id' => 361,
                'cc_subject_id' => 142,
                'name' => 'ادبیات داستانی',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            337 => 
            array (
                'id' => 362,
                'cc_subject_id' => 142,
                'name' => 'ادبیات جهان',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            338 => 
            array (
                'id' => 363,
                'cc_subject_id' => 75,
                'name' => 'هدایت الهی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            339 => 
            array (
                'id' => 364,
                'cc_subject_id' => 75,
                'name' => 'تداوم هدایت',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            340 => 
            array (
                'id' => 365,
                'cc_subject_id' => 75,
                'name' => 'معجزه جاویدان',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            341 => 
            array (
                'id' => 366,
                'cc_subject_id' => 75,
            'name' => 'مسئولیت های پیامبر (ص)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            342 => 
            array (
                'id' => 367,
                'cc_subject_id' => 75,
                'name' => 'امامت، تداوم رسالت',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            343 => 
            array (
                'id' => 368,
                'cc_subject_id' => 75,
                'name' => ' پیشوایان اسوه',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            344 => 
            array (
                'id' => 369,
                'cc_subject_id' => 75,
            'name' => ' وضعیت فرهنگی، اجتماعی و سیاسی مسلمانان، پس از رسول خدا (ص)',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            345 => 
            array (
                'id' => 370,
                'cc_subject_id' => 75,
                'name' => 'احیای ارزش های راستین',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            346 => 
            array (
                'id' => 371,
                'cc_subject_id' => 75,
                'name' => 'عصر غیبت',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            347 => 
            array (
                'id' => 372,
                'cc_subject_id' => 75,
                'name' => 'مرجعیت و ولایت فقیه',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            348 => 
            array (
                'id' => 373,
                'cc_subject_id' => 75,
                'name' => 'عزّت نفس',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            349 => 
            array (
                'id' => 374,
                'cc_subject_id' => 75,
                'name' => 'پیوند مقدس',
                'order' => 11,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            350 => 
            array (
                'id' => 375,
                'cc_subject_id' => 79,
                'name' => 'درس اول',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            351 => 
            array (
                'id' => 376,
                'cc_subject_id' => 79,
                'name' => 'درس دوم',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            352 => 
            array (
                'id' => 377,
                'cc_subject_id' => 79,
                'name' => 'درس سوم',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            353 => 
            array (
                'id' => 378,
                'cc_subject_id' => 79,
                'name' => 'درس چهارم',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            354 => 
            array (
                'id' => 379,
                'cc_subject_id' => 79,
                'name' => 'درس پنجم',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            355 => 
            array (
                'id' => 380,
                'cc_subject_id' => 79,
                'name' => 'درس ششم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            356 => 
            array (
                'id' => 381,
                'cc_subject_id' => 79,
                'name' => 'درس هفتم',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            357 => 
            array (
                'id' => 382,
                'cc_subject_id' => 143,
                'name' => 'درس اول',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            358 => 
            array (
                'id' => 383,
                'cc_subject_id' => 143,
                'name' => 'درس دوم',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            359 => 
            array (
                'id' => 384,
                'cc_subject_id' => 143,
                'name' => 'درس سوم',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            360 => 
            array (
                'id' => 385,
                'cc_subject_id' => 143,
                'name' => 'درس چهارم',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            361 => 
            array (
                'id' => 386,
                'cc_subject_id' => 143,
                'name' => 'درس پنجم',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            362 => 
            array (
                'id' => 387,
                'cc_subject_id' => 143,
                'name' => 'درس ششم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            363 => 
            array (
                'id' => 388,
                'cc_subject_id' => 143,
                'name' => 'درس هفتم',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            364 => 
            array (
                'id' => 389,
                'cc_subject_id' => 143,
                'name' => 'درس هشتم',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            365 => 
            array (
                'id' => 390,
                'cc_subject_id' => 143,
                'name' => 'درس نهم',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            366 => 
            array (
                'id' => 391,
                'cc_subject_id' => 143,
                'name' => 'درس دهم',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            367 => 
            array (
                'id' => 392,
                'cc_subject_id' => 143,
                'name' => 'درس یازدهم',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            368 => 
            array (
                'id' => 393,
                'cc_subject_id' => 143,
                'name' => 'درس دوازدهم',
                'order' => 11,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            369 => 
            array (
                'id' => 394,
                'cc_subject_id' => 143,
                'name' => 'درس سیزدهم',
                'order' => 12,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            370 => 
            array (
                'id' => 395,
                'cc_subject_id' => 143,
                'name' => 'درس چهاردهم',
                'order' => 13,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            371 => 
            array (
                'id' => 396,
                'cc_subject_id' => 143,
                'name' => 'درس پانزدهم',
                'order' => 14,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            372 => 
            array (
                'id' => 397,
                'cc_subject_id' => 143,
                'name' => 'درس شانزدهم',
                'order' => 15,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            373 => 
            array (
                'id' => 398,
                'cc_subject_id' => 143,
                'name' => 'درس هفدهم',
                'order' => 16,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            374 => 
            array (
                'id' => 399,
                'cc_subject_id' => 143,
                'name' => 'دس هجدهم',
                'order' => 17,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            375 => 
            array (
                'id' => 400,
                'cc_subject_id' => 143,
                'name' => 'درس نوزدهم',
                'order' => 18,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            376 => 
            array (
                'id' => 401,
                'cc_subject_id' => 143,
                'name' => 'درس بیستم ',
                'order' => 19,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            377 => 
            array (
                'id' => 402,
                'cc_subject_id' => 143,
                'name' => 'درس بیستم و یکم ',
                'order' => 20,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            378 => 
            array (
                'id' => 403,
                'cc_subject_id' => 143,
                'name' => 'درس بیست و دوم',
                'order' => 21,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            379 => 
            array (
                'id' => 404,
                'cc_subject_id' => 143,
                'name' => 'درس بیست و سوم',
                'order' => 22,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            380 => 
            array (
                'id' => 405,
                'cc_subject_id' => 143,
                'name' => 'درس بیست و چهارم ',
                'order' => 23,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            381 => 
            array (
                'id' => 406,
                'cc_subject_id' => 143,
                'name' => 'درس بیست و پنجم',
                'order' => 24,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            382 => 
            array (
                'id' => 407,
                'cc_subject_id' => 143,
                'name' => 'درس بیست و ششم ',
                'order' => 26,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            383 => 
            array (
                'id' => 408,
                'cc_subject_id' => 144,
                'name' => 'درس اول',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            384 => 
            array (
                'id' => 409,
                'cc_subject_id' => 144,
                'name' => 'درس دوم',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            385 => 
            array (
                'id' => 410,
                'cc_subject_id' => 144,
                'name' => 'درس سوم',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            386 => 
            array (
                'id' => 411,
                'cc_subject_id' => 145,
                'name' => 'آفرینش کیهان و تکوین زمین',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            387 => 
            array (
                'id' => 412,
                'cc_subject_id' => 145,
                'name' => 'منابع معدنی و ذخایر انرژی، زیربنای تمدن و توسعه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            388 => 
            array (
                'id' => 413,
                'cc_subject_id' => 145,
                'name' => 'منابع آب و خاک',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            389 => 
            array (
                'id' => 414,
                'cc_subject_id' => 145,
                'name' => 'پویایی زمین',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            390 => 
            array (
                'id' => 415,
                'cc_subject_id' => 145,
                'name' => 'زمین‌شناسی و سلامت‌',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            391 => 
            array (
                'id' => 416,
                'cc_subject_id' => 145,
                'name' => 'زمین‌شناسی و سازه‌های مهندسی‌',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            392 => 
            array (
                'id' => 417,
                'cc_subject_id' => 145,
                'name' => ' زمین‌شناسی ایران',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            393 => 
            array (
                'id' => 418,
                'cc_subject_id' => 83,
                'name' => 'درس اول ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            394 => 
            array (
                'id' => 419,
                'cc_subject_id' => 83,
                'name' => 'درس دوم',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            395 => 
            array (
                'id' => 420,
                'cc_subject_id' => 83,
                'name' => 'درس سوم',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            396 => 
            array (
                'id' => 421,
                'cc_subject_id' => 83,
                'name' => 'درس جهارم',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            397 => 
            array (
                'id' => 422,
                'cc_subject_id' => 83,
                'name' => 'درس پنجم',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            398 => 
            array (
                'id' => 423,
                'cc_subject_id' => 83,
                'name' => 'درس ششم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            399 => 
            array (
                'id' => 424,
                'cc_subject_id' => 83,
                'name' => 'درس هفتم',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            400 => 
            array (
                'id' => 425,
                'cc_subject_id' => 27,
                'name' => 'مولکول های اطلاعاتی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:44:07',
                'updated_at' => '2026-02-08 19:44:07',
            ),
            401 => 
            array (
                'id' => 426,
                'cc_subject_id' => 27,
                'name' => 'جریان اطلاعات در یاخته',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:44:15',
                'updated_at' => '2026-02-08 19:44:42',
            ),
            402 => 
            array (
                'id' => 427,
                'cc_subject_id' => 27,
                'name' => 'انتقال اطلاعات در نسل ها',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:44:28',
                'updated_at' => '2026-02-08 19:44:28',
            ),
            403 => 
            array (
                'id' => 428,
                'cc_subject_id' => 27,
                'name' => 'تغییر در اطلاعات وراثتی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:44:38',
                'updated_at' => '2026-02-08 19:44:38',
            ),
            404 => 
            array (
                'id' => 429,
                'cc_subject_id' => 27,
                'name' => 'از ماده به انرژی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:44:57',
                'updated_at' => '2026-02-08 19:44:57',
            ),
            405 => 
            array (
                'id' => 430,
                'cc_subject_id' => 27,
                'name' => 'از انرژی به ماده',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:45:05',
                'updated_at' => '2026-02-08 19:45:05',
            ),
            406 => 
            array (
                'id' => 431,
                'cc_subject_id' => 27,
                'name' => 'فناوری های نوین زیستی',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:45:14',
                'updated_at' => '2026-02-08 19:45:14',
            ),
            407 => 
            array (
                'id' => 432,
                'cc_subject_id' => 27,
                'name' => 'رفتارهای جانوران',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:45:24',
                'updated_at' => '2026-02-08 19:45:24',
            ),
            408 => 
            array (
                'id' => 433,
                'cc_subject_id' => 146,
                'name' => 'تنظیم عصبی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:54:58',
                'updated_at' => '2026-02-08 20:34:33',
            ),
            409 => 
            array (
                'id' => 434,
                'cc_subject_id' => 146,
                'name' => 'حواس',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:55:29',
                'updated_at' => '2026-02-08 20:31:00',
            ),
            410 => 
            array (
                'id' => 435,
                'cc_subject_id' => 146,
                'name' => 'دستگاه حرکتی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:55:50',
                'updated_at' => '2026-02-08 20:31:08',
            ),
            411 => 
            array (
                'id' => 436,
                'cc_subject_id' => 146,
                'name' => 'ایمنی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:56:08',
                'updated_at' => '2026-02-08 20:31:54',
            ),
            412 => 
            array (
                'id' => 437,
                'cc_subject_id' => 146,
                'name' => 'تقسیم یاخته',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:56:25',
                'updated_at' => '2026-02-08 20:32:28',
            ),
            413 => 
            array (
                'id' => 438,
                'cc_subject_id' => 146,
                'name' => 'تولیدمثل',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:56:39',
                'updated_at' => '2026-02-08 20:36:17',
            ),
            414 => 
            array (
                'id' => 439,
                'cc_subject_id' => 146,
                'name' => 'تولیدمثل نهان دانگان',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:56:54',
                'updated_at' => '2026-02-08 20:33:57',
            ),
            415 => 
            array (
                'id' => 440,
                'cc_subject_id' => 84,
                'name' => 'دنیای زنده',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:06:28',
                'updated_at' => '2026-02-08 20:06:34',
            ),
            416 => 
            array (
                'id' => 441,
                'cc_subject_id' => 84,
                'name' => 'گوارش و جذب مواد',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:06:42',
                'updated_at' => '2026-02-08 20:06:42',
            ),
            417 => 
            array (
                'id' => 442,
                'cc_subject_id' => 84,
                'name' => 'تبادلات گازی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:06:50',
                'updated_at' => '2026-02-08 20:09:07',
            ),
            418 => 
            array (
                'id' => 443,
                'cc_subject_id' => 84,
                'name' => 'گردش مواد در بدن',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:07:13',
                'updated_at' => '2026-02-08 20:07:13',
            ),
            419 => 
            array (
                'id' => 444,
                'cc_subject_id' => 84,
                'name' => 'تنظیم اسمزی و دفع مواد زائد',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:09:00',
                'updated_at' => '2026-02-08 20:09:00',
            ),
            420 => 
            array (
                'id' => 445,
                'cc_subject_id' => 84,
                'name' => 'از یاخته تا گیاه',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:09:20',
                'updated_at' => '2026-02-08 20:09:20',
            ),
            421 => 
            array (
                'id' => 446,
                'cc_subject_id' => 84,
                'name' => 'جذب و انتقال مواد در گیاه ان',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:09:32',
                'updated_at' => '2026-02-08 20:09:32',
            ),
            422 => 
            array (
                'id' => 447,
                'cc_subject_id' => 146,
                'name' => 'پاسخ گیاهان به محرک ها',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:18:55',
                'updated_at' => '2026-02-08 20:36:40',
            ),
            423 => 
            array (
                'id' => 448,
                'cc_subject_id' => 146,
                'name' => 'تنظیم شیمیایی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:29:25',
                'updated_at' => '2026-02-08 20:31:16',
            ),
        ));
        
        
    }
}