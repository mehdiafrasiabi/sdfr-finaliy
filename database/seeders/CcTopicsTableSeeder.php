<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CcTopicsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('cc_topics')->delete();
        
        \DB::table('cc_topics')->insert(array (
            0 => 
            array (
                'id' => 2,
                'cc_chapter_id' => 2,
                'name' => 'تبدیل نمودار توابع',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:18:05',
                'updated_at' => '2026-02-07 16:18:05',
            ),
            1 => 
            array (
                'id' => 3,
                'cc_chapter_id' => 2,
                'name' => 'تابع درجه سوم',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:18:31',
                'updated_at' => '2026-02-07 16:18:55',
            ),
            2 => 
            array (
                'id' => 4,
                'cc_chapter_id' => 2,
                'name' => ' توابع یکنوا ',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:19:17',
                'updated_at' => '2026-02-07 16:19:17',
            ),
            3 => 
            array (
                'id' => 5,
                'cc_chapter_id' => 2,
                'name' => 'بخشپذیری و تقسیم',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:19:24',
                'updated_at' => '2026-02-07 16:19:24',
            ),
            4 => 
            array (
                'id' => 6,
                'cc_chapter_id' => 3,
                'name' => 'تناوب و تانژانت',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:19:48',
                'updated_at' => '2026-02-07 16:19:48',
            ),
            5 => 
            array (
                'id' => 7,
                'cc_chapter_id' => 3,
                'name' => 'معادالت مثلثاتی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:20:02',
                'updated_at' => '2026-02-07 16:20:02',
            ),
            6 => 
            array (
                'id' => 8,
                'cc_chapter_id' => 4,
                'name' => 'حدهای نامتناهی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:20:37',
                'updated_at' => '2026-02-07 16:20:37',
            ),
            7 => 
            array (
                'id' => 9,
                'cc_chapter_id' => 4,
                'name' => 'حددر بینهایت',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:20:51',
                'updated_at' => '2026-02-07 16:20:51',
            ),
            8 => 
            array (
                'id' => 10,
                'cc_chapter_id' => 5,
                'name' => 'آشنایی با مفهوم مشتق',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:21:12',
                'updated_at' => '2026-02-07 16:21:12',
            ),
            9 => 
            array (
                'id' => 11,
                'cc_chapter_id' => 5,
                'name' => 'مشتق پذیری و پیوستگی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:21:36',
                'updated_at' => '2026-02-07 16:21:36',
            ),
            10 => 
            array (
                'id' => 12,
                'cc_chapter_id' => 5,
                'name' => 'آهنگ متوسط تغییر',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:21:57',
                'updated_at' => '2026-02-07 16:21:57',
            ),
            11 => 
            array (
                'id' => 13,
                'cc_chapter_id' => 5,
                'name' => 'آهنگ لحظهای تغییر',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:22:10',
                'updated_at' => '2026-02-07 16:22:10',
            ),
            12 => 
            array (
                'id' => 14,
                'cc_chapter_id' => 6,
                'name' => 'اکسترمم های یک تابع',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:22:49',
                'updated_at' => '2026-02-07 16:22:49',
            ),
            13 => 
            array (
                'id' => 15,
                'cc_chapter_id' => 6,
                'name' => 'توابع صعودی و نزولی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:22:56',
                'updated_at' => '2026-02-07 16:22:56',
            ),
            14 => 
            array (
                'id' => 16,
                'cc_chapter_id' => 6,
                'name' => 'جهت تقعر نمودار یک تابع',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:24:14',
                'updated_at' => '2026-02-07 16:39:41',
            ),
            15 => 
            array (
                'id' => 17,
                'cc_chapter_id' => 6,
                'name' => 'رسم نمودار تابع',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:24:23',
                'updated_at' => '2026-02-07 16:24:23',
            ),
            16 => 
            array (
                'id' => 18,
                'cc_chapter_id' => 6,
                'name' => 'نقطه عطف آن',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:39:53',
                'updated_at' => '2026-02-07 16:39:53',
            ),
            17 => 
            array (
                'id' => 19,
                'cc_chapter_id' => 7,
                'name' => 'ماتریس و اعمال روی ماتریس ها',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:41:48',
                'updated_at' => '2026-02-07 16:41:48',
            ),
            18 => 
            array (
                'id' => 20,
                'cc_chapter_id' => 7,
                'name' => 'وارون ماتریس',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:42:01',
                'updated_at' => '2026-02-07 16:42:01',
            ),
            19 => 
            array (
                'id' => 21,
                'cc_chapter_id' => 7,
                'name' => 'دترمینان',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:42:07',
                'updated_at' => '2026-02-07 16:42:07',
            ),
            20 => 
            array (
                'id' => 22,
                'cc_chapter_id' => 8,
                'name' => 'آشنایی با مقاطع مخروطی ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:42:38',
                'updated_at' => '2026-02-07 16:42:38',
            ),
            21 => 
            array (
                'id' => 23,
                'cc_chapter_id' => 8,
                'name' => 'مکان هندسی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:42:41',
                'updated_at' => '2026-02-07 16:42:41',
            ),
            22 => 
            array (
                'id' => 24,
                'cc_chapter_id' => 8,
                'name' => 'دایـره',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:42:50',
                'updated_at' => '2026-02-07 16:42:50',
            ),
            23 => 
            array (
                'id' => 25,
                'cc_chapter_id' => 8,
                'name' => 'بیضی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:42:57',
                'updated_at' => '2026-02-07 16:42:57',
            ),
            24 => 
            array (
                'id' => 26,
                'cc_chapter_id' => 8,
                'name' => 'سهمی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:43:06',
                'updated_at' => '2026-02-07 16:43:06',
            ),
            25 => 
            array (
                'id' => 27,
                'cc_chapter_id' => 9,
                'name' => 'معرفی فضای سه بعدی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:43:27',
                'updated_at' => '2026-02-07 16:43:27',
            ),
            26 => 
            array (
                'id' => 28,
                'cc_chapter_id' => 9,
                'name' => 'ضرب داخلی بردارها',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:43:45',
                'updated_at' => '2026-02-07 16:43:45',
            ),
            27 => 
            array (
                'id' => 29,
                'cc_chapter_id' => 9,
                'name' => 'ضرب خارجی بردارها',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:44:00',
                'updated_at' => '2026-02-07 16:44:00',
            ),
            28 => 
            array (
                'id' => 30,
                'cc_chapter_id' => 10,
                'name' => 'استدلال ریاضی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:47:04',
                'updated_at' => '2026-02-07 16:47:15',
            ),
            29 => 
            array (
                'id' => 31,
                'cc_chapter_id' => 10,
                'name' => 'بخش پذیری در اعداد صحیح',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:47:27',
                'updated_at' => '2026-02-07 16:47:27',
            ),
            30 => 
            array (
                'id' => 32,
                'cc_chapter_id' => 10,
                'name' => 'همنهشتی در اعداد صحیح و کاربردها',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:47:37',
                'updated_at' => '2026-02-07 16:47:37',
            ),
            31 => 
            array (
                'id' => 33,
                'cc_chapter_id' => 11,
                'name' => 'معرفی گراف',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:47:49',
                'updated_at' => '2026-02-07 16:47:49',
            ),
            32 => 
            array (
                'id' => 34,
                'cc_chapter_id' => 11,
                'name' => 'مدل سازی با گراف',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:47:58',
                'updated_at' => '2026-02-07 16:47:58',
            ),
            33 => 
            array (
                'id' => 35,
                'cc_chapter_id' => 12,
                'name' => 'مباحثی در ترکیبیات',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:48:32',
                'updated_at' => '2026-02-07 16:48:32',
            ),
            34 => 
            array (
                'id' => 36,
                'cc_chapter_id' => 12,
                'name' => 'روش هایی برای شمارش',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:48:42',
                'updated_at' => '2026-02-07 16:48:42',
            ),
            35 => 
            array (
                'id' => 37,
                'cc_chapter_id' => 13,
                'name' => 'شناخت حرکت',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:52:55',
                'updated_at' => '2026-02-07 16:52:55',
            ),
            36 => 
            array (
                'id' => 38,
                'cc_chapter_id' => 13,
                'name' => 'حرکت با سرعت ثابت',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:53:06',
                'updated_at' => '2026-02-07 16:53:11',
            ),
            37 => 
            array (
                'id' => 39,
                'cc_chapter_id' => 13,
                'name' => 'حرکت با شتاب ثابت',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:53:28',
                'updated_at' => '2026-02-07 16:53:28',
            ),
            38 => 
            array (
                'id' => 40,
                'cc_chapter_id' => 13,
                'name' => 'سقوط آزاد',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:53:36',
                'updated_at' => '2026-02-07 16:53:36',
            ),
            39 => 
            array (
                'id' => 41,
                'cc_chapter_id' => 14,
                'name' => 'قوانین حرکت نیوتون',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:54:35',
                'updated_at' => '2026-02-07 16:54:35',
            ),
            40 => 
            array (
                'id' => 42,
                'cc_chapter_id' => 14,
                'name' => 'معرفی برخی از نیروهای خاص',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:54:48',
                'updated_at' => '2026-02-07 16:54:48',
            ),
            41 => 
            array (
                'id' => 43,
                'cc_chapter_id' => 14,
                'name' => 'تکانه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:54:56',
                'updated_at' => '2026-02-07 16:54:56',
            ),
            42 => 
            array (
                'id' => 44,
                'cc_chapter_id' => 14,
                'name' => 'قانون دوم نیوتن ',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:55:08',
                'updated_at' => '2026-02-07 16:55:08',
            ),
            43 => 
            array (
                'id' => 45,
                'cc_chapter_id' => 14,
                'name' => 'حرکت دایره ای یکنواخت',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:55:21',
                'updated_at' => '2026-02-07 16:55:21',
            ),
            44 => 
            array (
                'id' => 46,
                'cc_chapter_id' => 14,
                'name' => 'نیروی گرانشی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:55:33',
                'updated_at' => '2026-02-07 16:55:33',
            ),
            45 => 
            array (
                'id' => 47,
                'cc_chapter_id' => 15,
                'name' => 'نوسان دوره ای',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:55:51',
                'updated_at' => '2026-02-07 16:55:51',
            ),
            46 => 
            array (
                'id' => 48,
                'cc_chapter_id' => 15,
                'name' => 'حرکت هماهنگ ساده',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:56:10',
                'updated_at' => '2026-02-07 16:56:10',
            ),
            47 => 
            array (
                'id' => 49,
                'cc_chapter_id' => 15,
                'name' => 'انرژی در حرکت هماهنگ ساده',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:56:45',
                'updated_at' => '2026-02-07 16:56:45',
            ),
            48 => 
            array (
                'id' => 50,
                'cc_chapter_id' => 15,
                'name' => 'تشدید',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:57:03',
                'updated_at' => '2026-02-07 16:57:03',
            ),
            49 => 
            array (
                'id' => 51,
                'cc_chapter_id' => 15,
                'name' => 'موج و انواع آن',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:57:12',
                'updated_at' => '2026-02-07 16:57:12',
            ),
            50 => 
            array (
                'id' => 52,
                'cc_chapter_id' => 15,
                'name' => 'مشخصه های موج',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:57:25',
                'updated_at' => '2026-02-07 16:57:25',
            ),
            51 => 
            array (
                'id' => 53,
                'cc_chapter_id' => 16,
                'name' => 'بازتاب موج',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:57:40',
                'updated_at' => '2026-02-07 16:57:40',
            ),
            52 => 
            array (
                'id' => 54,
                'cc_chapter_id' => 16,
                'name' => 'شکست موج',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:57:46',
                'updated_at' => '2026-02-07 16:57:46',
            ),
            53 => 
            array (
                'id' => 55,
                'cc_chapter_id' => 16,
                'name' => 'پراش موج',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:57:55',
                'updated_at' => '2026-02-07 16:57:55',
            ),
            54 => 
            array (
                'id' => 56,
                'cc_chapter_id' => 16,
                'name' => 'تداخل امواج',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:58:04',
                'updated_at' => '2026-02-07 16:58:04',
            ),
            55 => 
            array (
                'id' => 57,
                'cc_chapter_id' => 17,
                'name' => 'اثر فوتوالکتریک و فوتون ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:58:30',
                'updated_at' => '2026-02-07 16:58:30',
            ),
            56 => 
            array (
                'id' => 58,
                'cc_chapter_id' => 17,
                'name' => 'طیف خطی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:58:37',
                'updated_at' => '2026-02-07 16:58:37',
            ),
            57 => 
            array (
                'id' => 59,
                'cc_chapter_id' => 17,
                'name' => 'مدل اتم رادرفورد-بور',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:58:49',
                'updated_at' => '2026-02-07 16:58:49',
            ),
            58 => 
            array (
                'id' => 60,
                'cc_chapter_id' => 17,
                'name' => 'لیزر',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:59:02',
                'updated_at' => '2026-02-07 16:59:02',
            ),
            59 => 
            array (
                'id' => 61,
                'cc_chapter_id' => 18,
                'name' => 'ساختار هسته',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:59:18',
                'updated_at' => '2026-02-07 16:59:18',
            ),
            60 => 
            array (
                'id' => 62,
                'cc_chapter_id' => 18,
                'name' => 'پرتوزایی طبیعی ',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:59:32',
                'updated_at' => '2026-02-07 16:59:32',
            ),
            61 => 
            array (
                'id' => 63,
                'cc_chapter_id' => 18,
                'name' => 'نیمه عمر',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:59:38',
                'updated_at' => '2026-02-07 16:59:38',
            ),
            62 => 
            array (
                'id' => 64,
                'cc_chapter_id' => 18,
                'name' => 'شکافت هسته ای',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:59:54',
                'updated_at' => '2026-02-07 16:59:54',
            ),
            63 => 
            array (
                'id' => 65,
                'cc_chapter_id' => 18,
            'name' => 'گداخت(همجوشی) هسته ای',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:00:11',
                'updated_at' => '2026-02-07 17:00:11',
            ),
            64 => 
            array (
                'id' => 66,
                'cc_chapter_id' => 24,
                'name' => 'ستایش : ملکا، ذکر تو گویم',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:17:58',
                'updated_at' => '2026-02-07 17:17:58',
            ),
            65 => 
            array (
                'id' => 67,
                'cc_chapter_id' => 24,
                'name' => 'درس اول : شکر نعمت',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:18:10',
                'updated_at' => '2026-02-07 17:20:55',
            ),
            66 => 
            array (
                'id' => 68,
                'cc_chapter_id' => 24,
                'name' => 'گنج حکمت : گمان',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:21:38',
                'updated_at' => '2026-02-07 17:21:38',
            ),
            67 => 
            array (
                'id' => 69,
                'cc_chapter_id' => 24,
                'name' => 'درس دوم : مست و هوشیار',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:21:52',
                'updated_at' => '2026-02-07 17:21:52',
            ),
            68 => 
            array (
                'id' => 70,
                'cc_chapter_id' => 32,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:23:42',
                'updated_at' => '2026-02-07 17:23:42',
            ),
            69 => 
            array (
                'id' => 71,
                'cc_chapter_id' => 32,
                'name' => 'درک مطلب',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:24:06',
                'updated_at' => '2026-02-07 17:24:06',
            ),
            70 => 
            array (
                'id' => 72,
                'cc_chapter_id' => 32,
                'name' => 'مکالمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:24:12',
                'updated_at' => '2026-02-07 17:24:12',
            ),
            71 => 
            array (
                'id' => 73,
                'cc_chapter_id' => 32,
                'name' => 'قواعد',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:24:18',
                'updated_at' => '2026-02-07 17:24:18',
            ),
            72 => 
            array (
                'id' => 74,
                'cc_chapter_id' => 32,
                'name' => 'ترجمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:24:24',
                'updated_at' => '2026-02-07 17:24:24',
            ),
            73 => 
            array (
                'id' => 75,
                'cc_chapter_id' => 32,
                'name' => 'اعراب و تحلیل صرفی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:25:04',
                'updated_at' => '2026-02-07 17:25:04',
            ),
            74 => 
            array (
                'id' => 76,
                'cc_chapter_id' => 32,
                'name' => 'مفهوم',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:25:32',
                'updated_at' => '2026-02-07 17:25:32',
            ),
            75 => 
            array (
                'id' => 77,
                'cc_chapter_id' => 33,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:23:42',
                'updated_at' => '2026-02-07 17:23:42',
            ),
            76 => 
            array (
                'id' => 78,
                'cc_chapter_id' => 33,
                'name' => 'درک مطلب',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:24:06',
                'updated_at' => '2026-02-07 17:24:06',
            ),
            77 => 
            array (
                'id' => 79,
                'cc_chapter_id' => 33,
                'name' => 'مکالمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:24:12',
                'updated_at' => '2026-02-07 17:24:12',
            ),
            78 => 
            array (
                'id' => 80,
                'cc_chapter_id' => 33,
                'name' => 'قواعد',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:24:18',
                'updated_at' => '2026-02-07 17:24:18',
            ),
            79 => 
            array (
                'id' => 81,
                'cc_chapter_id' => 33,
                'name' => 'ترجمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:24:24',
                'updated_at' => '2026-02-07 17:24:24',
            ),
            80 => 
            array (
                'id' => 82,
                'cc_chapter_id' => 33,
                'name' => 'اعراب و تحلیل صرفی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:25:04',
                'updated_at' => '2026-02-07 17:25:04',
            ),
            81 => 
            array (
                'id' => 83,
                'cc_chapter_id' => 33,
                'name' => 'مفهوم',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:25:32',
                'updated_at' => '2026-02-07 17:25:32',
            ),
            82 => 
            array (
                'id' => 84,
                'cc_chapter_id' => 34,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:23:42',
                'updated_at' => '2026-02-07 17:23:42',
            ),
            83 => 
            array (
                'id' => 85,
                'cc_chapter_id' => 34,
                'name' => 'درک مطلب',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:24:06',
                'updated_at' => '2026-02-07 17:24:06',
            ),
            84 => 
            array (
                'id' => 86,
                'cc_chapter_id' => 34,
                'name' => 'مکالمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:24:12',
                'updated_at' => '2026-02-07 17:24:12',
            ),
            85 => 
            array (
                'id' => 87,
                'cc_chapter_id' => 34,
                'name' => 'قواعد',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:24:18',
                'updated_at' => '2026-02-07 17:24:18',
            ),
            86 => 
            array (
                'id' => 88,
                'cc_chapter_id' => 34,
                'name' => 'ترجمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:24:24',
                'updated_at' => '2026-02-07 17:24:24',
            ),
            87 => 
            array (
                'id' => 89,
                'cc_chapter_id' => 34,
                'name' => 'اعراب و تحلیل صرفی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:25:04',
                'updated_at' => '2026-02-07 17:25:04',
            ),
            88 => 
            array (
                'id' => 90,
                'cc_chapter_id' => 34,
                'name' => 'مفهوم',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:25:32',
                'updated_at' => '2026-02-07 17:25:32',
            ),
            89 => 
            array (
                'id' => 91,
                'cc_chapter_id' => 35,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:23:42',
                'updated_at' => '2026-02-07 17:23:42',
            ),
            90 => 
            array (
                'id' => 92,
                'cc_chapter_id' => 35,
                'name' => 'درک مطلب',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:24:06',
                'updated_at' => '2026-02-07 17:24:06',
            ),
            91 => 
            array (
                'id' => 93,
                'cc_chapter_id' => 35,
                'name' => 'مکالمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:24:12',
                'updated_at' => '2026-02-07 17:24:12',
            ),
            92 => 
            array (
                'id' => 94,
                'cc_chapter_id' => 35,
                'name' => 'قواعد',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:24:18',
                'updated_at' => '2026-02-07 17:24:18',
            ),
            93 => 
            array (
                'id' => 95,
                'cc_chapter_id' => 35,
                'name' => 'ترجمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:24:24',
                'updated_at' => '2026-02-07 17:24:24',
            ),
            94 => 
            array (
                'id' => 96,
                'cc_chapter_id' => 35,
                'name' => 'اعراب و تحلیل صرفی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:25:04',
                'updated_at' => '2026-02-07 17:25:04',
            ),
            95 => 
            array (
                'id' => 97,
                'cc_chapter_id' => 35,
                'name' => 'مفهوم',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:25:32',
                'updated_at' => '2026-02-07 17:25:32',
            ),
            96 => 
            array (
                'id' => 98,
                'cc_chapter_id' => 36,
                'name' => 'آیه ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:41',
                'updated_at' => '2026-02-07 17:32:41',
            ),
            97 => 
            array (
                'id' => 99,
                'cc_chapter_id' => 36,
                'name' => 'متن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:45',
                'updated_at' => '2026-02-07 17:32:45',
            ),
            98 => 
            array (
                'id' => 100,
                'cc_chapter_id' => 36,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:50',
                'updated_at' => '2026-02-07 17:32:50',
            ),
            99 => 
            array (
                'id' => 101,
                'cc_chapter_id' => 37,
                'name' => 'آیه ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:41',
                'updated_at' => '2026-02-07 17:32:41',
            ),
            100 => 
            array (
                'id' => 102,
                'cc_chapter_id' => 37,
                'name' => 'متن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:45',
                'updated_at' => '2026-02-07 17:32:45',
            ),
            101 => 
            array (
                'id' => 103,
                'cc_chapter_id' => 37,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:50',
                'updated_at' => '2026-02-07 17:32:50',
            ),
            102 => 
            array (
                'id' => 104,
                'cc_chapter_id' => 38,
                'name' => 'آیه ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:41',
                'updated_at' => '2026-02-07 17:32:41',
            ),
            103 => 
            array (
                'id' => 105,
                'cc_chapter_id' => 38,
                'name' => 'متن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:45',
                'updated_at' => '2026-02-07 17:32:45',
            ),
            104 => 
            array (
                'id' => 106,
                'cc_chapter_id' => 38,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:50',
                'updated_at' => '2026-02-07 17:32:50',
            ),
            105 => 
            array (
                'id' => 107,
                'cc_chapter_id' => 39,
                'name' => 'آیه ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:41',
                'updated_at' => '2026-02-07 17:32:41',
            ),
            106 => 
            array (
                'id' => 108,
                'cc_chapter_id' => 39,
                'name' => 'متن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:45',
                'updated_at' => '2026-02-07 17:32:45',
            ),
            107 => 
            array (
                'id' => 109,
                'cc_chapter_id' => 39,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:50',
                'updated_at' => '2026-02-07 17:32:50',
            ),
            108 => 
            array (
                'id' => 110,
                'cc_chapter_id' => 40,
                'name' => 'آیه ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:41',
                'updated_at' => '2026-02-07 17:32:41',
            ),
            109 => 
            array (
                'id' => 111,
                'cc_chapter_id' => 40,
                'name' => 'متن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:45',
                'updated_at' => '2026-02-07 17:32:45',
            ),
            110 => 
            array (
                'id' => 112,
                'cc_chapter_id' => 40,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:50',
                'updated_at' => '2026-02-07 17:32:50',
            ),
            111 => 
            array (
                'id' => 113,
                'cc_chapter_id' => 41,
                'name' => 'آیه ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:41',
                'updated_at' => '2026-02-07 17:32:41',
            ),
            112 => 
            array (
                'id' => 114,
                'cc_chapter_id' => 41,
                'name' => 'متن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:45',
                'updated_at' => '2026-02-07 17:32:45',
            ),
            113 => 
            array (
                'id' => 115,
                'cc_chapter_id' => 41,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:50',
                'updated_at' => '2026-02-07 17:32:50',
            ),
            114 => 
            array (
                'id' => 116,
                'cc_chapter_id' => 42,
                'name' => 'آیه ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:41',
                'updated_at' => '2026-02-07 17:32:41',
            ),
            115 => 
            array (
                'id' => 117,
                'cc_chapter_id' => 42,
                'name' => 'متن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:45',
                'updated_at' => '2026-02-07 17:32:45',
            ),
            116 => 
            array (
                'id' => 118,
                'cc_chapter_id' => 42,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:50',
                'updated_at' => '2026-02-07 17:32:50',
            ),
            117 => 
            array (
                'id' => 119,
                'cc_chapter_id' => 43,
                'name' => 'آیه ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:41',
                'updated_at' => '2026-02-07 17:32:41',
            ),
            118 => 
            array (
                'id' => 120,
                'cc_chapter_id' => 43,
                'name' => 'متن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:45',
                'updated_at' => '2026-02-07 17:32:45',
            ),
            119 => 
            array (
                'id' => 121,
                'cc_chapter_id' => 43,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:50',
                'updated_at' => '2026-02-07 17:32:50',
            ),
            120 => 
            array (
                'id' => 122,
                'cc_chapter_id' => 44,
                'name' => 'آیه ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:41',
                'updated_at' => '2026-02-07 17:32:41',
            ),
            121 => 
            array (
                'id' => 123,
                'cc_chapter_id' => 44,
                'name' => 'متن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:45',
                'updated_at' => '2026-02-07 17:32:45',
            ),
            122 => 
            array (
                'id' => 124,
                'cc_chapter_id' => 44,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:50',
                'updated_at' => '2026-02-07 17:32:50',
            ),
            123 => 
            array (
                'id' => 125,
                'cc_chapter_id' => 45,
                'name' => 'آیه ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:41',
                'updated_at' => '2026-02-07 17:32:41',
            ),
            124 => 
            array (
                'id' => 126,
                'cc_chapter_id' => 45,
                'name' => 'متن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:45',
                'updated_at' => '2026-02-07 17:32:45',
            ),
            125 => 
            array (
                'id' => 127,
                'cc_chapter_id' => 45,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:50',
                'updated_at' => '2026-02-07 17:32:50',
            ),
            126 => 
            array (
                'id' => 128,
                'cc_chapter_id' => 46,
            'name' => 'درک مطلب (reading comprehension)',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:38:37',
                'updated_at' => '2026-02-07 17:38:37',
            ),
            127 => 
            array (
                'id' => 129,
                'cc_chapter_id' => 46,
            'name' => 'گرامر (grammar)',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:38:52',
                'updated_at' => '2026-02-07 17:38:52',
            ),
            128 => 
            array (
                'id' => 130,
                'cc_chapter_id' => 46,
            'name' => 'واژگان (vocabulary)',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:38:57',
                'updated_at' => '2026-02-07 17:38:57',
            ),
            129 => 
            array (
                'id' => 131,
                'cc_chapter_id' => 46,
            'name' => 'نگارش (writing)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:39:07',
                'updated_at' => '2026-02-07 17:39:07',
            ),
            130 => 
            array (
                'id' => 132,
                'cc_chapter_id' => 46,
            'name' => 'Listening (شنیداری)',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:39:18',
                'updated_at' => '2026-02-07 17:39:18',
            ),
            131 => 
            array (
                'id' => 133,
                'cc_chapter_id' => 47,
            'name' => 'درک مطلب (reading comprehension)',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:38:37',
                'updated_at' => '2026-02-07 17:38:37',
            ),
            132 => 
            array (
                'id' => 134,
                'cc_chapter_id' => 47,
            'name' => 'گرامر (grammar)',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:38:52',
                'updated_at' => '2026-02-07 17:38:52',
            ),
            133 => 
            array (
                'id' => 135,
                'cc_chapter_id' => 47,
            'name' => 'واژگان (vocabulary)',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:38:57',
                'updated_at' => '2026-02-07 17:38:57',
            ),
            134 => 
            array (
                'id' => 136,
                'cc_chapter_id' => 47,
            'name' => 'نگارش (writing)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:39:07',
                'updated_at' => '2026-02-07 17:39:07',
            ),
            135 => 
            array (
                'id' => 137,
                'cc_chapter_id' => 47,
            'name' => 'Listening (شنیداری)',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:39:18',
                'updated_at' => '2026-02-07 17:39:18',
            ),
            136 => 
            array (
                'id' => 138,
                'cc_chapter_id' => 48,
            'name' => 'درک مطلب (reading comprehension)',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:38:37',
                'updated_at' => '2026-02-07 17:38:37',
            ),
            137 => 
            array (
                'id' => 139,
                'cc_chapter_id' => 48,
            'name' => 'گرامر (grammar)',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:38:52',
                'updated_at' => '2026-02-07 17:38:52',
            ),
            138 => 
            array (
                'id' => 140,
                'cc_chapter_id' => 48,
            'name' => 'واژگان (vocabulary)',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:38:57',
                'updated_at' => '2026-02-07 17:38:57',
            ),
            139 => 
            array (
                'id' => 141,
                'cc_chapter_id' => 48,
            'name' => 'نگارش (writing)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:39:07',
                'updated_at' => '2026-02-07 17:39:07',
            ),
            140 => 
            array (
                'id' => 142,
                'cc_chapter_id' => 48,
            'name' => 'Listening (شنیداری)',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:39:18',
                'updated_at' => '2026-02-07 17:39:18',
            ),
            141 => 
            array (
                'id' => 143,
                'cc_chapter_id' => 49,
                'name' => 'سلامت چیست؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:56:12',
                'updated_at' => '2026-02-07 17:56:12',
            ),
            142 => 
            array (
                'id' => 144,
                'cc_chapter_id' => 49,
                'name' => 'سبک زندگی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:56:19',
                'updated_at' => '2026-02-07 17:56:19',
            ),
            143 => 
            array (
                'id' => 145,
                'cc_chapter_id' => 50,
                'name' => 'برنامۀ غذایی سالم',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:57:04',
                'updated_at' => '2026-02-07 17:57:04',
            ),
            144 => 
            array (
                'id' => 146,
                'cc_chapter_id' => 50,
                'name' => 'کنترل وزن و تناسب اندام',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:57:12',
                'updated_at' => '2026-02-07 17:57:12',
            ),
            145 => 
            array (
                'id' => 147,
                'cc_chapter_id' => 50,
                'name' => ' بهداشت و ایمنی مواد غذایی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:58:45',
                'updated_at' => '2026-02-07 17:58:45',
            ),
            146 => 
            array (
                'id' => 148,
                'cc_chapter_id' => 51,
                'name' => 'بیماری‎های غیر واگیر',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:59:09',
                'updated_at' => '2026-02-07 17:59:09',
            ),
            147 => 
            array (
                'id' => 149,
                'cc_chapter_id' => 51,
                'name' => 'بیماری‎های واگیر',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:59:20',
                'updated_at' => '2026-02-07 17:59:20',
            ),
            148 => 
            array (
                'id' => 150,
                'cc_chapter_id' => 52,
                'name' => 'بهداشت فردی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:59:39',
                'updated_at' => '2026-02-07 17:59:39',
            ),
            149 => 
            array (
                'id' => 151,
                'cc_chapter_id' => 52,
                'name' => 'بهداشت ازدواج و باروری',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:59:46',
                'updated_at' => '2026-02-07 17:59:46',
            ),
            150 => 
            array (
                'id' => 152,
                'cc_chapter_id' => 52,
                'name' => 'بهداشت روان',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:59:55',
                'updated_at' => '2026-02-07 17:59:55',
            ),
            151 => 
            array (
                'id' => 153,
                'cc_chapter_id' => 53,
                'name' => 'مصرف دخانیات و الکل',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:00:14',
                'updated_at' => '2026-02-07 18:00:14',
            ),
            152 => 
            array (
                'id' => 154,
                'cc_chapter_id' => 53,
                'name' => 'اعتیاد به مواد مخدر و عوارض آن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:00:24',
                'updated_at' => '2026-02-07 18:00:24',
            ),
            153 => 
            array (
                'id' => 155,
                'cc_chapter_id' => 54,
                'name' => 'پیشگیری از اختلالات اسکلتی - عضلانی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:00:39',
                'updated_at' => '2026-02-07 18:00:39',
            ),
            154 => 
            array (
                'id' => 156,
                'cc_chapter_id' => 54,
                'name' => 'پیشگیری از حوادث خانگی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:00:50',
                'updated_at' => '2026-02-07 18:00:50',
            ),
            155 => 
            array (
                'id' => 157,
                'cc_chapter_id' => 55,
                'name' => 'کنش ما انسان‌ها چه ویژگی‌هایی دارد؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:03:51',
                'updated_at' => '2026-02-07 18:03:51',
            ),
            156 => 
            array (
                'id' => 158,
                'cc_chapter_id' => 55,
                'name' => 'کنش ما چه آثار و پیامد‌هایی دارد؟',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:03:57',
                'updated_at' => '2026-02-07 18:03:57',
            ),
            157 => 
            array (
                'id' => 159,
                'cc_chapter_id' => 56,
                'name' => 'کنش اجتماعی چیست؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:04:38',
                'updated_at' => '2026-02-07 18:04:38',
            ),
            158 => 
            array (
                'id' => 160,
                'cc_chapter_id' => 56,
                'name' => 'پدیده‌های اجتماعی کدام‌اند و چگونه شکل می‌گیرند؟',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:04:45',
                'updated_at' => '2026-02-07 18:04:45',
            ),
            159 => 
            array (
                'id' => 161,
                'cc_chapter_id' => 57,
                'name' => 'از جامعه و فرهنگ چه تصوری دارید؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:05:01',
                'updated_at' => '2026-02-07 18:05:01',
            ),
            160 => 
            array (
                'id' => 162,
                'cc_chapter_id' => 57,
            'name' => 'جامعه و فرهنگ (جهان اجتماعی) چه الزاماتی دارد؟',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:05:07',
                'updated_at' => '2026-02-07 18:05:07',
            ),
            161 => 
            array (
                'id' => 163,
                'cc_chapter_id' => 58,
                'name' => 'منظور از فرهنگ آرمانی و فرهنگ واقعی چیست؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:05:22',
                'updated_at' => '2026-02-07 18:05:22',
            ),
            162 => 
            array (
                'id' => 164,
                'cc_chapter_id' => 58,
                'name' => 'منظور از فرهنگ حق و فرهنگ باطل چیست؟',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:05:27',
                'updated_at' => '2026-02-07 18:05:27',
            ),
            163 => 
            array (
                'id' => 166,
                'cc_chapter_id' => 59,
                'name' => 'در مورد هویت چه می‌دانید؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:05:59',
                'updated_at' => '2026-02-07 18:05:59',
            ),
            164 => 
            array (
                'id' => 167,
                'cc_chapter_id' => 59,
                'name' => 'هویت فردی و اجتماعی چه نسبتی با هم دارند؟',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:06:05',
                'updated_at' => '2026-02-07 18:06:05',
            ),
            165 => 
            array (
                'id' => 168,
                'cc_chapter_id' => 59,
                'name' => 'خودآگاهی یا ناخودآگاهی؟',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:06:11',
                'updated_at' => '2026-02-07 18:06:11',
            ),
            166 => 
            array (
                'id' => 169,
                'cc_chapter_id' => 60,
                'name' => 'هویت اجتماعی چگونه شکل می‌گیرد و تداوم می‌یابد؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:06:32',
                'updated_at' => '2026-02-07 18:06:32',
            ),
            167 => 
            array (
                'id' => 170,
                'cc_chapter_id' => 60,
                'name' => 'هویت اجتماعی چگونه تغییر می‌کند؟',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:06:39',
                'updated_at' => '2026-02-07 18:06:39',
            ),
            168 => 
            array (
                'id' => 171,
                'cc_chapter_id' => 61,
                'name' => 'تحولات هویتی جامعه چگونه است؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:06:56',
                'updated_at' => '2026-02-07 18:06:56',
            ),
            169 => 
            array (
                'id' => 172,
                'cc_chapter_id' => 61,
                'name' => 'ازخودبیگانگی فرهنگی چیست؟',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:07:02',
                'updated_at' => '2026-02-07 18:07:02',
            ),
            170 => 
            array (
                'id' => 173,
                'cc_chapter_id' => 62,
                'name' => 'هویت ایرانی در گذر زمان چه تحولاتی به خود دیده است؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:07:18',
                'updated_at' => '2026-02-07 18:07:18',
            ),
            171 => 
            array (
                'id' => 174,
                'cc_chapter_id' => 62,
                'name' => 'انقلاب اسلامی چه تاثیری بر هویت ایرانی داشته است؟',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:07:24',
                'updated_at' => '2026-02-07 18:07:24',
            ),
            172 => 
            array (
                'id' => 175,
                'cc_chapter_id' => 63,
                'name' => 'چه رابطه‌ای میان جامعه و نظام سیاسی آن وجود دارد؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:07:36',
                'updated_at' => '2026-02-07 18:07:36',
            ),
            173 => 
            array (
                'id' => 176,
                'cc_chapter_id' => 63,
                'name' => 'نظام سیاسی و انواع آن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:07:41',
                'updated_at' => '2026-02-07 18:07:41',
            ),
            174 => 
            array (
                'id' => 177,
                'cc_chapter_id' => 63,
                'name' => 'لیبرال دموکراسی و جمهوری اسلامی چه تفاوت‌هایی با هم دارند؟',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:07:50',
                'updated_at' => '2026-02-07 18:07:50',
            ),
            175 => 
            array (
                'id' => 178,
                'cc_chapter_id' => 64,
                'name' => 'جمعیت هر جامعه چه رابطه‌ای با هویت آن دارد؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:08:00',
                'updated_at' => '2026-02-07 18:08:00',
            ),
            176 => 
            array (
                'id' => 179,
                'cc_chapter_id' => 64,
                'name' => 'اقتصاد هر جامعه چه رابطه‌ای با هویت آن دارد؟',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:08:07',
                'updated_at' => '2026-02-07 18:08:07',
            ),
            177 => 
            array (
                'id' => 180,
                'cc_chapter_id' => 24,
                'name' => 'شعرخوانی: در مکتب حقایق',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:10:23',
                'updated_at' => '2026-02-07 18:10:23',
            ),
            178 => 
            array (
                'id' => 181,
                'cc_chapter_id' => 25,
                'name' => 'درس 3: آزادی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:10:43',
                'updated_at' => '2026-02-07 18:10:43',
            ),
            179 => 
            array (
                'id' => 182,
                'cc_chapter_id' => 25,
                'name' => 'درس 5: دماوندیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:10:54',
                'updated_at' => '2026-02-07 18:10:54',
            ),
            180 => 
            array (
                'id' => 183,
                'cc_chapter_id' => 26,
                'name' => 'درس 6: نی نامه',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:11:22',
                'updated_at' => '2026-02-07 18:11:22',
            ),
            181 => 
            array (
                'id' => 184,
                'cc_chapter_id' => 26,
                'name' => 'درس 7: در حقیقت عشق',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:11:33',
                'updated_at' => '2026-02-07 18:11:33',
            ),
            182 => 
            array (
                'id' => 185,
                'cc_chapter_id' => 26,
                'name' => 'شعرخوانی: صبح ستاره باران',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:14:35',
                'updated_at' => '2026-02-07 18:14:35',
            ),
            183 => 
            array (
                'id' => 186,
                'cc_chapter_id' => 27,
                'name' => 'درس 8: از پاریز تا پاریس',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:14:52',
                'updated_at' => '2026-02-07 18:14:52',
            ),
            184 => 
            array (
                'id' => 187,
                'cc_chapter_id' => 27,
                'name' => 'درس 9: کویر',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:15:00',
                'updated_at' => '2026-02-07 18:15:00',
            ),
            185 => 
            array (
                'id' => 188,
                'cc_chapter_id' => 28,
                'name' => 'درس 10: فصل شکوفایی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:15:17',
                'updated_at' => '2026-02-07 18:15:17',
            ),
            186 => 
            array (
                'id' => 189,
                'cc_chapter_id' => 28,
                'name' => 'درس 11: آن شب عزیز',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:15:28',
                'updated_at' => '2026-02-07 18:15:28',
            ),
            187 => 
            array (
                'id' => 190,
                'cc_chapter_id' => 28,
                'name' => 'شعرخوانی: شکوه چشمان تو',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:15:37',
                'updated_at' => '2026-02-07 18:15:37',
            ),
            188 => 
            array (
                'id' => 191,
                'cc_chapter_id' => 29,
                'name' => 'درس 12: گذر سیاوش از آتش',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:16:05',
                'updated_at' => '2026-02-07 18:16:05',
            ),
            189 => 
            array (
                'id' => 192,
                'cc_chapter_id' => 29,
                'name' => 'شعرخوانی: ای میهن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:16:09',
                'updated_at' => '2026-02-07 18:16:09',
            ),
            190 => 
            array (
                'id' => 193,
                'cc_chapter_id' => 30,
                'name' => 'درس 14: سی مرغ و سیمرغ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:16:26',
                'updated_at' => '2026-02-07 18:16:26',
            ),
            191 => 
            array (
                'id' => 194,
                'cc_chapter_id' => 30,
                'name' => 'درس 16: کباب غاز',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:16:35',
                'updated_at' => '2026-02-07 18:16:35',
            ),
            192 => 
            array (
                'id' => 195,
                'cc_chapter_id' => 31,
                'name' => 'درس 17: خنده‌ی تو',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:16:58',
                'updated_at' => '2026-02-07 18:16:58',
            ),
            193 => 
            array (
                'id' => 196,
                'cc_chapter_id' => 31,
                'name' => 'درس 18: عشق جاودانی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:17:06',
                'updated_at' => '2026-02-07 18:17:06',
            ),
            194 => 
            array (
                'id' => 197,
                'cc_chapter_id' => 65,
                'name' => ' مجموع جملات دنباله های حسابی و هندسی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:33:24',
                'updated_at' => '2026-02-07 18:33:24',
            ),
            195 => 
            array (
                'id' => 198,
                'cc_chapter_id' => 65,
                'name' => 'معادلات درجه دوم',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:33:44',
                'updated_at' => '2026-02-07 18:33:44',
            ),
            196 => 
            array (
                'id' => 199,
                'cc_chapter_id' => 65,
                'name' => 'معادلات گویا و گنگ',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:34:00',
                'updated_at' => '2026-02-07 18:34:00',
            ),
            197 => 
            array (
                'id' => 200,
                'cc_chapter_id' => 65,
                'name' => 'قدر مطلق و ویژگی های آن',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:34:16',
                'updated_at' => '2026-02-07 18:34:16',
            ),
            198 => 
            array (
                'id' => 201,
                'cc_chapter_id' => 65,
                'name' => 'آشنایی با هندسه تحلیلی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:34:31',
                'updated_at' => '2026-02-07 18:34:31',
            ),
            199 => 
            array (
                'id' => 202,
                'cc_chapter_id' => 66,
                'name' => 'آشنایی بیشتر با تابع',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:35:01',
                'updated_at' => '2026-02-07 18:35:01',
            ),
            200 => 
            array (
                'id' => 203,
                'cc_chapter_id' => 66,
                'name' => 'انواع توابع',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:35:08',
                'updated_at' => '2026-02-07 18:35:08',
            ),
            201 => 
            array (
                'id' => 204,
                'cc_chapter_id' => 66,
                'name' => 'وارون تابع',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:35:16',
                'updated_at' => '2026-02-07 18:35:16',
            ),
            202 => 
            array (
                'id' => 205,
                'cc_chapter_id' => 66,
                'name' => 'اعمال روی توابع',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:35:24',
                'updated_at' => '2026-02-07 18:35:24',
            ),
            203 => 
            array (
                'id' => 206,
                'cc_chapter_id' => 66,
                'name' => 'تابع نمایی و لگاریتمی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:35:34',
                'updated_at' => '2026-02-07 18:35:34',
            ),
            204 => 
            array (
                'id' => 207,
                'cc_chapter_id' => 66,
                'name' => 'مثلثات',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:35:42',
                'updated_at' => '2026-02-07 18:35:42',
            ),
            205 => 
            array (
                'id' => 208,
                'cc_chapter_id' => 66,
                'name' => 'حد و پیوستگی',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:35:53',
                'updated_at' => '2026-02-07 18:35:53',
            ),
            206 => 
            array (
                'id' => 209,
                'cc_chapter_id' => 67,
                'name' => 'تابع نمایی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:36:25',
                'updated_at' => '2026-02-07 18:36:25',
            ),
            207 => 
            array (
                'id' => 210,
                'cc_chapter_id' => 67,
                'name' => 'تابع لگاریتمی و لگاریتم',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:36:32',
                'updated_at' => '2026-02-07 18:36:49',
            ),
            208 => 
            array (
                'id' => 211,
                'cc_chapter_id' => 67,
                'name' => 'ویژگی های لگاریتم و حل معادله های لگاریتمی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:36:53',
                'updated_at' => '2026-02-07 18:36:53',
            ),
            209 => 
            array (
                'id' => 213,
                'cc_chapter_id' => 68,
                'name' => 'رادیان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:37:36',
                'updated_at' => '2026-02-07 18:37:36',
            ),
            210 => 
            array (
                'id' => 214,
                'cc_chapter_id' => 68,
                'name' => 'نسبت های مثلثاتی برخی زاویه ها',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:37:43',
                'updated_at' => '2026-02-07 18:37:43',
            ),
            211 => 
            array (
                'id' => 215,
                'cc_chapter_id' => 68,
                'name' => 'توابع مثلثاتی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:37:52',
                'updated_at' => '2026-02-07 18:37:52',
            ),
            212 => 
            array (
                'id' => 216,
                'cc_chapter_id' => 68,
                'name' => ' روابط مثلثاتی مجموع و تفاضل زوایا',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:38:10',
                'updated_at' => '2026-02-07 18:38:10',
            ),
            213 => 
            array (
                'id' => 217,
                'cc_chapter_id' => 69,
                'name' => 'مفهوم حد و فرایند های حدی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:41:21',
                'updated_at' => '2026-02-07 18:41:21',
            ),
            214 => 
            array (
                'id' => 218,
                'cc_chapter_id' => 69,
            'name' => ' حد های یک طرفه (حد چپ و حد راست)',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:41:35',
                'updated_at' => '2026-02-07 18:41:35',
            ),
            215 => 
            array (
                'id' => 219,
                'cc_chapter_id' => 69,
                'name' => ' قضایای حد',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:41:50',
                'updated_at' => '2026-02-07 18:41:50',
            ),
            216 => 
            array (
                'id' => 220,
                'cc_chapter_id' => 69,
            'name' => ' محاسبه حد توابع کسری (حالت صفر صفرم)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:42:22',
                'updated_at' => '2026-02-07 18:42:22',
            ),
            217 => 
            array (
                'id' => 221,
                'cc_chapter_id' => 69,
                'name' => 'پیوستگی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:42:30',
                'updated_at' => '2026-02-07 18:42:30',
            ),
            218 => 
            array (
                'id' => 222,
                'cc_chapter_id' => 70,
                'name' => 'مفاهیم اولیه و زاویه ها در دایره',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:43:43',
                'updated_at' => '2026-02-07 18:43:43',
            ),
            219 => 
            array (
                'id' => 223,
                'cc_chapter_id' => 70,
                'name' => ' رابطه های طولی در دایره',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:43:52',
                'updated_at' => '2026-02-07 18:43:52',
            ),
            220 => 
            array (
                'id' => 224,
                'cc_chapter_id' => 70,
                'name' => 'چند ضلعی های محاطی و محیطی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:44:03',
                'updated_at' => '2026-02-07 18:44:03',
            ),
            221 => 
            array (
                'id' => 225,
                'cc_chapter_id' => 71,
                'name' => 'تبدیل های هندسی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:44:24',
                'updated_at' => '2026-02-07 18:44:24',
            ),
            222 => 
            array (
                'id' => 226,
                'cc_chapter_id' => 71,
                'name' => 'کاربرد تبدیل ها',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:44:31',
                'updated_at' => '2026-02-07 18:44:31',
            ),
            223 => 
            array (
                'id' => 227,
                'cc_chapter_id' => 72,
                'name' => ' قضیه سینوس ها',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:45:09',
                'updated_at' => '2026-02-07 18:45:09',
            ),
            224 => 
            array (
                'id' => 228,
                'cc_chapter_id' => 72,
                'name' => 'قضیه کسینوس ها',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:45:19',
                'updated_at' => '2026-02-07 18:45:19',
            ),
            225 => 
            array (
                'id' => 229,
                'cc_chapter_id' => 72,
                'name' => 'قضیه نیمساز های زوایای داخلی و محاسبه طول نیمساز ها',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:45:28',
                'updated_at' => '2026-02-07 18:45:28',
            ),
            226 => 
            array (
                'id' => 230,
                'cc_chapter_id' => 72,
            'name' => 'قضیه هرون (محاسبه ارتفاع ها و مساحت مثلث)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:45:37',
                'updated_at' => '2026-02-07 18:45:37',
            ),
            227 => 
            array (
                'id' => 231,
                'cc_chapter_id' => 73,
                'name' => 'رفتار عنصرها',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:46:46',
                'updated_at' => '2026-02-07 18:46:46',
            ),
            228 => 
            array (
                'id' => 232,
                'cc_chapter_id' => 73,
                'name' => 'دنیای رنگی با عنصرهای دستۀ d',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:46:54',
                'updated_at' => '2026-02-07 18:46:54',
            ),
            229 => 
            array (
                'id' => 233,
                'cc_chapter_id' => 73,
                'name' => 'عنصرها به چه شکلی در طبیعت یافت می‌شوند؟',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:47:03',
                'updated_at' => '2026-02-07 18:47:03',
            ),
            230 => 
            array (
                'id' => 234,
                'cc_chapter_id' => 73,
                'name' => '‌‎دنیای واقعی واکنش‌ها',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:47:11',
                'updated_at' => '2026-02-07 18:47:11',
            ),
            231 => 
            array (
                'id' => 235,
                'cc_chapter_id' => 73,
                'name' => '‌‎نفت هدیه‌ای شگفت‌انگیز',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:47:20',
                'updated_at' => '2026-02-07 18:47:20',
            ),
            232 => 
            array (
                'id' => 236,
                'cc_chapter_id' => 73,
                'name' => 'آلکان‌ها، هیدروکربن‌هایی با پیوندهای یگانه‎',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:47:29',
                'updated_at' => '2026-02-07 18:47:29',
            ),
            233 => 
            array (
                'id' => 237,
                'cc_chapter_id' => 73,
                'name' => '‌‎آلکن‌ها، آلکین‌ها و هیدروکربن‌های حلقوی‎',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:47:41',
                'updated_at' => '2026-02-07 18:47:41',
            ),
            234 => 
            array (
                'id' => 238,
                'cc_chapter_id' => 73,
                'name' => 'نفت، ماده‌ای که اقتصاد جهان را دگرگون ساخت',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:47:50',
                'updated_at' => '2026-02-07 18:47:50',
            ),
            235 => 
            array (
                'id' => 239,
                'cc_chapter_id' => 74,
                'name' => 'غذا، ماده و انرژی‎',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:48:09',
                'updated_at' => '2026-02-07 18:48:09',
            ),
            236 => 
            array (
                'id' => 240,
                'cc_chapter_id' => 74,
                'name' => 'جاری شدن انرژی ـ آنتالپی‎',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:48:16',
                'updated_at' => '2026-02-07 18:48:16',
            ),
            237 => 
            array (
                'id' => 241,
                'cc_chapter_id' => 74,
                'name' => 'آنتالپی پیوند و میانگین آن',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:48:23',
                'updated_at' => '2026-02-07 18:48:23',
            ),
            238 => 
            array (
                'id' => 242,
                'cc_chapter_id' => 74,
                'name' => 'گروه‌های عاملی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:48:38',
                'updated_at' => '2026-02-07 18:48:38',
            ),
            239 => 
            array (
                'id' => 243,
                'cc_chapter_id' => 74,
                'name' => 'آنتالپی سوختن، تکیه‌گاهی برای تأمین انرژی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:48:48',
                'updated_at' => '2026-02-07 18:48:48',
            ),
            240 => 
            array (
                'id' => 244,
                'cc_chapter_id' => 74,
                'name' => 'گرماسنجی و قانون هس',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:48:57',
                'updated_at' => '2026-02-07 18:48:57',
            ),
            241 => 
            array (
                'id' => 245,
                'cc_chapter_id' => 74,
                'name' => 'غذای سالم و عوامل مؤثر بر سرعت واکنش‌ها',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:49:29',
                'updated_at' => '2026-02-07 18:49:29',
            ),
            242 => 
            array (
                'id' => 246,
                'cc_chapter_id' => 74,
                'name' => 'سینتیک شیمیایی و مسائل سرعت',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:49:40',
                'updated_at' => '2026-02-07 18:49:40',
            ),
            243 => 
            array (
                'id' => 247,
                'cc_chapter_id' => 74,
                'name' => 'غذا، پسماند و رد پای آن',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:49:50',
                'updated_at' => '2026-02-07 18:49:50',
            ),
            244 => 
            array (
                'id' => 248,
                'cc_chapter_id' => 75,
                'name' => 'پلیمری شدن ترکیب‌های دارای پیوند دوگانه کربن - کربن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:50:12',
                'updated_at' => '2026-02-07 18:50:12',
            ),
            245 => 
            array (
                'id' => 249,
                'cc_chapter_id' => 75,
                'name' => 'پلی استرها و روش تهیۀ آنها',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:50:22',
                'updated_at' => '2026-02-07 18:50:22',
            ),
            246 => 
            array (
                'id' => 250,
                'cc_chapter_id' => 75,
                'name' => '‌‎پلی‌آمیدها و روش تهیۀ آنها‎',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:50:29',
                'updated_at' => '2026-02-07 18:50:29',
            ),
            247 => 
            array (
                'id' => 251,
                'cc_chapter_id' => 75,
                'name' => 'پلیمرها، ماندگار یا تخریب پذیر- پلیمر سبز',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:50:38',
                'updated_at' => '2026-02-07 18:50:38',
            ),
            248 => 
            array (
                'id' => 252,
                'cc_chapter_id' => 76,
                'name' => 'بار الکتریکی - پایستگی و کوانتیده بودن بار الکتریکی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:52:17',
                'updated_at' => '2026-02-07 18:52:17',
            ),
            249 => 
            array (
                'id' => 253,
                'cc_chapter_id' => 76,
                'name' => 'قانون کولن - بر هم نهی نیرو های الکتروستاتیکی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:52:22',
                'updated_at' => '2026-02-07 18:52:36',
            ),
            250 => 
            array (
                'id' => 254,
                'cc_chapter_id' => 76,
                'name' => 'میدان الکتریکی - بر هم نهی میدان های الکتریکی - خطوط میدان الکتریکی - میدان الکتریکی یکنواخت',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:52:31',
                'updated_at' => '2026-02-07 18:52:40',
            ),
            251 => 
            array (
                'id' => 255,
                'cc_chapter_id' => 76,
                'name' => 'انرژی پتانسیل الکتریکی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:52:48',
                'updated_at' => '2026-02-07 18:52:48',
            ),
            252 => 
            array (
                'id' => 256,
                'cc_chapter_id' => 76,
                'name' => 'پتانسیل الکتریکی - رابطه ی اختلاف پتانسیل دو نقطه و اندازه ی میدان یکنواخت - کار نیروی خارجی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:53:05',
                'updated_at' => '2026-02-07 18:53:05',
            ),
            253 => 
            array (
                'id' => 257,
                'cc_chapter_id' => 76,
                'name' => 'میدان الکتریکی در داخل رسانا ها - رسانای خنثی در میدان الکتریکی - چگالی سطحی بار الکتریکی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:53:15',
                'updated_at' => '2026-02-07 18:53:15',
            ),
            254 => 
            array (
                'id' => 258,
                'cc_chapter_id' => 76,
                'name' => 'خازن - خازن با دی الکتریک - انرژی خازن',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:53:39',
                'updated_at' => '2026-02-07 18:53:39',
            ),
            255 => 
            array (
                'id' => 259,
                'cc_chapter_id' => 77,
                'name' => 'جریان الکتریکی ـ مقاومت الکتریکی و قانون اهم',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:55:37',
                'updated_at' => '2026-02-07 18:55:37',
            ),
            256 => 
            array (
                'id' => 260,
                'cc_chapter_id' => 77,
                'name' => 'عوامل مؤثر بر مقاومت الکتریکی، انواع مقاومت ها و کدگذاری',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:55:43',
                'updated_at' => '2026-02-07 18:55:43',
            ),
            257 => 
            array (
                'id' => 261,
                'cc_chapter_id' => 77,
                'name' => 'نیروی محرکه الکتریکی و مدار ها - قاعده حلقه، مدار تک حلقه ای و افت پتانسیل در مقاومت',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:55:49',
                'updated_at' => '2026-02-07 18:55:49',
            ),
            258 => 
            array (
                'id' => 262,
                'cc_chapter_id' => 77,
                'name' => 'توان و انرژی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:55:57',
                'updated_at' => '2026-02-07 18:55:57',
            ),
            259 => 
            array (
                'id' => 263,
                'cc_chapter_id' => 77,
                'name' => 'قاعدۀ انشعاب، ترکیب مقاومت ها - به هم بستن متوالی، موازی یا ترکیبی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:56:06',
                'updated_at' => '2026-02-07 18:56:06',
            ),
            260 => 
            array (
                'id' => 264,
                'cc_chapter_id' => 78,
                'name' => 'مغناطیس و قطب های مغناطیسی - میدان مغناطیسی - میدان مغناطیسی زمین - میدان مغناطیسی یکنواخت',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:56:39',
                'updated_at' => '2026-02-07 18:56:39',
            ),
            261 => 
            array (
                'id' => 265,
                'cc_chapter_id' => 78,
                'name' => 'نیروی مغناطیسی وارد بر ذره ی باردار متحرک در میدان مغناطیسی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:56:45',
                'updated_at' => '2026-02-07 18:56:45',
            ),
            262 => 
            array (
                'id' => 266,
                'cc_chapter_id' => 78,
                'name' => 'نیروی مغناطیسی وارد بر سیم حامل جریان',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:56:54',
                'updated_at' => '2026-02-07 18:56:54',
            ),
            263 => 
            array (
                'id' => 267,
                'cc_chapter_id' => 78,
                'name' => 'میدان مغناطیسی اطراف سیم راست حامل جریان',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:57:00',
                'updated_at' => '2026-02-07 18:57:00',
            ),
            264 => 
            array (
                'id' => 268,
                'cc_chapter_id' => 78,
                'name' => 'میدان ناشی از حلقه و سیملوله',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:57:07',
                'updated_at' => '2026-02-07 18:57:07',
            ),
            265 => 
            array (
                'id' => 269,
                'cc_chapter_id' => 78,
                'name' => 'ویژگی های مغناطیسی مواد',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:57:15',
                'updated_at' => '2026-02-07 18:57:15',
            ),
            266 => 
            array (
                'id' => 270,
                'cc_chapter_id' => 79,
                'name' => 'القای الکترومغناطیس',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:57:33',
                'updated_at' => '2026-02-07 18:57:33',
            ),
            267 => 
            array (
                'id' => 271,
                'cc_chapter_id' => 79,
                'name' => 'القاگر ها - خود القاوری - ضریب القاوری - القای متقابل - انرژی ذخیره شده در القاگر',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:57:41',
                'updated_at' => '2026-02-07 18:57:41',
            ),
            268 => 
            array (
                'id' => 272,
                'cc_chapter_id' => 79,
                'name' => 'جریان متناوب - مبدل ها',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:57:49',
                'updated_at' => '2026-02-07 18:57:49',
            ),
            269 => 
            array (
                'id' => 273,
                'cc_chapter_id' => 80,
                'name' => 'منطق ریاضی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:58:54',
                'updated_at' => '2026-02-07 18:58:54',
            ),
            270 => 
            array (
                'id' => 274,
                'cc_chapter_id' => 80,
                'name' => 'مجموعه و زیر مجموعه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:59:06',
                'updated_at' => '2026-02-07 18:59:06',
            ),
            271 => 
            array (
                'id' => 275,
                'cc_chapter_id' => 81,
                'name' => 'مبانی احتمال',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:59:24',
                'updated_at' => '2026-02-07 18:59:24',
            ),
            272 => 
            array (
                'id' => 276,
                'cc_chapter_id' => 81,
                'name' => 'احتمال غیر هم شانس',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:59:39',
                'updated_at' => '2026-02-07 18:59:39',
            ),
            273 => 
            array (
                'id' => 277,
                'cc_chapter_id' => 81,
                'name' => 'احتمال شرطی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:59:47',
                'updated_at' => '2026-02-07 18:59:47',
            ),
            274 => 
            array (
                'id' => 278,
                'cc_chapter_id' => 81,
                'name' => 'پیشامد های مستقل و وابسته',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:59:56',
                'updated_at' => '2026-02-07 18:59:56',
            ),
            275 => 
            array (
                'id' => 279,
                'cc_chapter_id' => 82,
                'name' => ' توصیف و نمایش داده ها',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:00:34',
                'updated_at' => '2026-02-07 19:00:34',
            ),
            276 => 
            array (
                'id' => 280,
                'cc_chapter_id' => 82,
                'name' => 'معیار های گرایش به مرکز',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:00:41',
                'updated_at' => '2026-02-07 19:00:41',
            ),
            277 => 
            array (
                'id' => 281,
                'cc_chapter_id' => 82,
                'name' => ' معیار های پراکندگی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:00:51',
                'updated_at' => '2026-02-07 19:00:51',
            ),
            278 => 
            array (
                'id' => 282,
                'cc_chapter_id' => 83,
                'name' => 'گردآوری داده ها',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:01:10',
                'updated_at' => '2026-02-07 19:01:10',
            ),
            279 => 
            array (
                'id' => 283,
                'cc_chapter_id' => 83,
                'name' => ' برآورد',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:01:18',
                'updated_at' => '2026-02-07 19:01:18',
            ),
            280 => 
            array (
                'id' => 284,
                'cc_chapter_id' => 84,
                'name' => ' نیکی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:27:01',
                'updated_at' => '2026-02-07 19:27:01',
            ),
            281 => 
            array (
                'id' => 285,
                'cc_chapter_id' => 84,
                'name' => 'قاضی بُست',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:27:11',
                'updated_at' => '2026-02-07 19:27:11',
            ),
            282 => 
            array (
                'id' => 286,
                'cc_chapter_id' => 84,
                'name' => 'شعرخوانی: زاغ و کبک',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:27:25',
                'updated_at' => '2026-02-07 19:27:25',
            ),
            283 => 
            array (
                'id' => 287,
                'cc_chapter_id' => 85,
                'name' => 'در امواج سند',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:27:54',
                'updated_at' => '2026-02-07 19:27:54',
            ),
            284 => 
            array (
                'id' => 288,
                'cc_chapter_id' => 85,
                'name' => 'آغازگری تنها',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:28:03',
                'updated_at' => '2026-02-07 19:28:03',
            ),
            285 => 
            array (
                'id' => 289,
                'cc_chapter_id' => 86,
                'name' => 'پرورده ی عشق',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:28:41',
                'updated_at' => '2026-02-07 19:28:41',
            ),
            286 => 
            array (
                'id' => 290,
                'cc_chapter_id' => 86,
                'name' => ' باران محبّت',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:28:51',
                'updated_at' => '2026-02-07 19:28:51',
            ),
            287 => 
            array (
                'id' => 291,
                'cc_chapter_id' => 86,
                'name' => 'شعرخوانی: آفتاب حُسن',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:28:58',
                'updated_at' => '2026-02-07 19:28:58',
            ),
            288 => 
            array (
                'id' => 292,
                'cc_chapter_id' => 87,
                'name' => 'در کوی عاشقان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:31:03',
                'updated_at' => '2026-02-07 19:31:03',
            ),
            289 => 
            array (
                'id' => 293,
                'cc_chapter_id' => 87,
                'name' => ' ذوق لطیف',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:31:12',
                'updated_at' => '2026-02-07 19:31:12',
            ),
            290 => 
            array (
                'id' => 294,
                'cc_chapter_id' => 88,
                'name' => 'بانگ جَرَس',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:31:37',
                'updated_at' => '2026-02-07 19:31:37',
            ),
            291 => 
            array (
                'id' => 295,
                'cc_chapter_id' => 88,
                'name' => ' یارانِ عاشق',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:31:47',
                'updated_at' => '2026-02-07 19:31:47',
            ),
            292 => 
            array (
                'id' => 296,
                'cc_chapter_id' => 88,
                'name' => 'شعرخوانی: صبح بی تو',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:31:56',
                'updated_at' => '2026-02-07 19:31:56',
            ),
            293 => 
            array (
                'id' => 297,
                'cc_chapter_id' => 89,
                'name' => 'کاوه ی دادخواه',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:32:18',
                'updated_at' => '2026-02-07 19:32:18',
            ),
            294 => 
            array (
                'id' => 298,
                'cc_chapter_id' => 89,
                'name' => 'حمله ی حیدری',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:32:26',
                'updated_at' => '2026-02-07 19:32:26',
            ),
            295 => 
            array (
                'id' => 299,
                'cc_chapter_id' => 89,
                'name' => 'شعرخوانی: وطن',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:32:34',
                'updated_at' => '2026-02-07 19:32:34',
            ),
            296 => 
            array (
                'id' => 300,
                'cc_chapter_id' => 90,
                'name' => 'کبوتر طوقدار',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:32:59',
                'updated_at' => '2026-02-07 19:32:59',
            ),
            297 => 
            array (
                'id' => 301,
                'cc_chapter_id' => 90,
                'name' => 'قصّه ی عینکم',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:33:08',
                'updated_at' => '2026-02-07 19:33:08',
            ),
            298 => 
            array (
                'id' => 302,
                'cc_chapter_id' => 91,
                'name' => 'خاموشی دریا',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:33:27',
                'updated_at' => '2026-02-07 19:33:27',
            ),
            299 => 
            array (
                'id' => 303,
                'cc_chapter_id' => 91,
                'name' => 'خوان عدل',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:33:40',
                'updated_at' => '2026-02-07 19:33:40',
            ),
            300 => 
            array (
                'id' => 304,
                'cc_chapter_id' => 92,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:07',
                'updated_at' => '2026-02-07 19:58:07',
            ),
            301 => 
            array (
                'id' => 305,
                'cc_chapter_id' => 92,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:16',
                'updated_at' => '2026-02-07 19:58:16',
            ),
            302 => 
            array (
                'id' => 306,
                'cc_chapter_id' => 92,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:26',
                'updated_at' => '2026-02-07 19:58:26',
            ),
            303 => 
            array (
                'id' => 307,
                'cc_chapter_id' => 93,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:07',
                'updated_at' => '2026-02-07 19:58:07',
            ),
            304 => 
            array (
                'id' => 308,
                'cc_chapter_id' => 93,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:16',
                'updated_at' => '2026-02-07 19:58:16',
            ),
            305 => 
            array (
                'id' => 309,
                'cc_chapter_id' => 93,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:26',
                'updated_at' => '2026-02-07 19:58:26',
            ),
            306 => 
            array (
                'id' => 310,
                'cc_chapter_id' => 94,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:07',
                'updated_at' => '2026-02-07 19:58:07',
            ),
            307 => 
            array (
                'id' => 311,
                'cc_chapter_id' => 94,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:16',
                'updated_at' => '2026-02-07 19:58:16',
            ),
            308 => 
            array (
                'id' => 312,
                'cc_chapter_id' => 94,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:26',
                'updated_at' => '2026-02-07 19:58:26',
            ),
            309 => 
            array (
                'id' => 313,
                'cc_chapter_id' => 95,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:07',
                'updated_at' => '2026-02-07 19:58:07',
            ),
            310 => 
            array (
                'id' => 314,
                'cc_chapter_id' => 95,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:16',
                'updated_at' => '2026-02-07 19:58:16',
            ),
            311 => 
            array (
                'id' => 315,
                'cc_chapter_id' => 95,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:26',
                'updated_at' => '2026-02-07 19:58:26',
            ),
            312 => 
            array (
                'id' => 316,
                'cc_chapter_id' => 96,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:07',
                'updated_at' => '2026-02-07 19:58:07',
            ),
            313 => 
            array (
                'id' => 317,
                'cc_chapter_id' => 96,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:16',
                'updated_at' => '2026-02-07 19:58:16',
            ),
            314 => 
            array (
                'id' => 318,
                'cc_chapter_id' => 96,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:26',
                'updated_at' => '2026-02-07 19:58:26',
            ),
            315 => 
            array (
                'id' => 319,
                'cc_chapter_id' => 97,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:07',
                'updated_at' => '2026-02-07 19:58:07',
            ),
            316 => 
            array (
                'id' => 320,
                'cc_chapter_id' => 97,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:16',
                'updated_at' => '2026-02-07 19:58:16',
            ),
            317 => 
            array (
                'id' => 321,
                'cc_chapter_id' => 97,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:26',
                'updated_at' => '2026-02-07 19:58:26',
            ),
            318 => 
            array (
                'id' => 322,
                'cc_chapter_id' => 98,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:07',
                'updated_at' => '2026-02-07 19:58:07',
            ),
            319 => 
            array (
                'id' => 323,
                'cc_chapter_id' => 98,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:16',
                'updated_at' => '2026-02-07 19:58:16',
            ),
            320 => 
            array (
                'id' => 324,
                'cc_chapter_id' => 98,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:26',
                'updated_at' => '2026-02-07 19:58:26',
            ),
            321 => 
            array (
                'id' => 325,
                'cc_chapter_id' => 99,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:07',
                'updated_at' => '2026-02-07 19:58:07',
            ),
            322 => 
            array (
                'id' => 326,
                'cc_chapter_id' => 99,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:16',
                'updated_at' => '2026-02-07 19:58:16',
            ),
            323 => 
            array (
                'id' => 327,
                'cc_chapter_id' => 99,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:26',
                'updated_at' => '2026-02-07 19:58:26',
            ),
            324 => 
            array (
                'id' => 328,
                'cc_chapter_id' => 100,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:07',
                'updated_at' => '2026-02-07 19:58:07',
            ),
            325 => 
            array (
                'id' => 329,
                'cc_chapter_id' => 100,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:16',
                'updated_at' => '2026-02-07 19:58:16',
            ),
            326 => 
            array (
                'id' => 330,
                'cc_chapter_id' => 100,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:26',
                'updated_at' => '2026-02-07 19:58:26',
            ),
            327 => 
            array (
                'id' => 331,
                'cc_chapter_id' => 101,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:07',
                'updated_at' => '2026-02-07 19:58:07',
            ),
            328 => 
            array (
                'id' => 332,
                'cc_chapter_id' => 101,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:16',
                'updated_at' => '2026-02-07 19:58:16',
            ),
            329 => 
            array (
                'id' => 333,
                'cc_chapter_id' => 101,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:26',
                'updated_at' => '2026-02-07 19:58:26',
            ),
            330 => 
            array (
                'id' => 334,
                'cc_chapter_id' => 102,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:07',
                'updated_at' => '2026-02-07 19:58:07',
            ),
            331 => 
            array (
                'id' => 335,
                'cc_chapter_id' => 102,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:16',
                'updated_at' => '2026-02-07 19:58:16',
            ),
            332 => 
            array (
                'id' => 337,
                'cc_chapter_id' => 102,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:07',
                'updated_at' => '2026-02-07 19:58:07',
            ),
            333 => 
            array (
                'id' => 340,
                'cc_chapter_id' => 103,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:07',
                'updated_at' => '2026-02-07 19:58:07',
            ),
            334 => 
            array (
                'id' => 341,
                'cc_chapter_id' => 103,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:16',
                'updated_at' => '2026-02-07 19:58:16',
            ),
            335 => 
            array (
                'id' => 342,
                'cc_chapter_id' => 103,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:58:26',
                'updated_at' => '2026-02-07 19:58:26',
            ),
            336 => 
            array (
                'id' => 343,
                'cc_chapter_id' => 104,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:10:20',
                'updated_at' => '2026-02-07 20:10:20',
            ),
            337 => 
            array (
                'id' => 344,
                'cc_chapter_id' => 104,
                'name' => 'ترجمه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:10:26',
                'updated_at' => '2026-02-07 20:10:26',
            ),
            338 => 
            array (
                'id' => 345,
                'cc_chapter_id' => 104,
                'name' => 'مکالمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:10:36',
                'updated_at' => '2026-02-07 20:10:36',
            ),
            339 => 
            array (
                'id' => 346,
                'cc_chapter_id' => 104,
                'name' => 'درک مطلب',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:10:43',
                'updated_at' => '2026-02-07 20:10:43',
            ),
            340 => 
            array (
                'id' => 347,
                'cc_chapter_id' => 104,
                'name' => 'قواعد',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:11:10',
                'updated_at' => '2026-02-07 20:11:10',
            ),
            341 => 
            array (
                'id' => 348,
                'cc_chapter_id' => 104,
                'name' => 'مفهوم ',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:11:26',
                'updated_at' => '2026-02-07 20:11:26',
            ),
            342 => 
            array (
                'id' => 349,
                'cc_chapter_id' => 104,
                'name' => 'اعراب و تحلیل صرفی',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:11:35',
                'updated_at' => '2026-02-07 20:11:35',
            ),
            343 => 
            array (
                'id' => 351,
                'cc_chapter_id' => 111,
                'name' => 'حکومت قاجار از آقا محمد خان تا محمد شاه',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:00:28',
                'updated_at' => '2026-02-07 21:00:28',
            ),
            344 => 
            array (
                'id' => 352,
                'cc_chapter_id' => 112,
                'name' => 'دوران ناصرالدین شاه',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:00:47',
                'updated_at' => '2026-02-07 21:00:47',
            ),
            345 => 
            array (
                'id' => 353,
                'cc_chapter_id' => 113,
                'name' => 'زمینه‌های نهضت مشروطه',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:01:02',
                'updated_at' => '2026-02-07 21:01:02',
            ),
            346 => 
            array (
                'id' => 354,
                'cc_chapter_id' => 114,
                'name' => 'آغاز حرکت مردم علیه استبداد و پیروزی نهضت مشروطه',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:01:15',
                'updated_at' => '2026-02-07 21:01:15',
            ),
            347 => 
            array (
                'id' => 355,
                'cc_chapter_id' => 115,
                'name' => 'مشروطه در دوزۀ محمدعلی شاه',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:01:28',
                'updated_at' => '2026-02-07 21:01:28',
            ),
            348 => 
            array (
                'id' => 356,
                'cc_chapter_id' => 116,
            'name' => 'دورۀ مشروطۀ دوم (1288 - 1293)',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:01:43',
                'updated_at' => '2026-02-07 21:01:43',
            ),
            349 => 
            array (
                'id' => 357,
                'cc_chapter_id' => 117,
                'name' => ' کودتای 1299',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:01:56',
                'updated_at' => '2026-02-07 21:01:56',
            ),
            350 => 
            array (
                'id' => 358,
                'cc_chapter_id' => 118,
                'name' => 'رضاخان، تثبیت قدرت',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:02:16',
                'updated_at' => '2026-02-07 21:02:16',
            ),
            351 => 
            array (
                'id' => 359,
                'cc_chapter_id' => 119,
                'name' => ' ویژگی‌های حکومت رضا شاه',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:02:31',
                'updated_at' => '2026-02-07 21:02:31',
            ),
            352 => 
            array (
                'id' => 360,
                'cc_chapter_id' => 120,
                'name' => 'سقوط رضا شاه',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:02:40',
                'updated_at' => '2026-02-07 21:02:40',
            ),
            353 => 
            array (
                'id' => 361,
                'cc_chapter_id' => 121,
                'name' => 'اشغال ایران توسط متفقین و آثار آن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:02:59',
                'updated_at' => '2026-02-07 21:02:59',
            ),
            354 => 
            array (
                'id' => 362,
                'cc_chapter_id' => 122,
                'name' => 'نهضت ملی شدن صنعت نفت',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:03:10',
                'updated_at' => '2026-02-07 21:03:10',
            ),
            355 => 
            array (
                'id' => 363,
                'cc_chapter_id' => 123,
                'name' => 'زمینه‌های کودتای 28 مرداد',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:03:27',
                'updated_at' => '2026-02-07 21:03:27',
            ),
            356 => 
            array (
                'id' => 364,
                'cc_chapter_id' => 124,
                'name' => 'کودتای بیست و هشتم مرداد',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:03:37',
                'updated_at' => '2026-02-07 21:03:37',
            ),
            357 => 
            array (
                'id' => 365,
                'cc_chapter_id' => 125,
                'name' => 'ربع قرن سیطرۀ آمریکا بر ایران',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:03:49',
                'updated_at' => '2026-02-07 21:03:49',
            ),
            358 => 
            array (
                'id' => 366,
                'cc_chapter_id' => 126,
                'name' => ' زمینه‌ها و هدف‌های اصلاحات آمریکایی در ایران',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:04:00',
                'updated_at' => '2026-02-07 21:04:00',
            ),
            359 => 
            array (
                'id' => 367,
                'cc_chapter_id' => 127,
                'name' => 'پیدایش نهضت روحانیت و اوج‌گیری بیداری اسلامی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:04:14',
                'updated_at' => '2026-02-07 21:04:14',
            ),
            360 => 
            array (
                'id' => 368,
                'cc_chapter_id' => 128,
                'name' => ' قیام 15 خرداد',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:04:31',
                'updated_at' => '2026-02-07 21:04:31',
            ),
            361 => 
            array (
                'id' => 369,
                'cc_chapter_id' => 129,
                'name' => 'تحولات ایران پس از تبعید امام خمینی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:04:47',
                'updated_at' => '2026-02-07 21:04:47',
            ),
            362 => 
            array (
                'id' => 370,
                'cc_chapter_id' => 130,
                'name' => ' ایران در مسیر انقلاب اسلامی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:05:00',
                'updated_at' => '2026-02-07 21:05:00',
            ),
            363 => 
            array (
                'id' => 371,
                'cc_chapter_id' => 131,
                'name' => 'پیروزی انقلاب اسلامی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:05:18',
                'updated_at' => '2026-02-07 21:05:18',
            ),
            364 => 
            array (
                'id' => 372,
                'cc_chapter_id' => 132,
                'name' => 'دولت موقت مهندس مهدی بازرگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:05:37',
                'updated_at' => '2026-02-07 21:05:37',
            ),
            365 => 
            array (
                'id' => 373,
                'cc_chapter_id' => 133,
                'name' => ' اولین دورۀ ریاست جمهوری',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:06:05',
                'updated_at' => '2026-02-07 21:06:05',
            ),
            366 => 
            array (
                'id' => 374,
                'cc_chapter_id' => 134,
                'name' => 'جنگ تحمیلی رژیم بعثی حاکم بر عراق علیه ایران',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:06:21',
                'updated_at' => '2026-02-07 21:06:21',
            ),
            367 => 
            array (
                'id' => 375,
                'cc_chapter_id' => 135,
                'name' => ' آرمان‌ها و دستاوردهای انقلاب اسلامی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:06:34',
                'updated_at' => '2026-02-07 21:06:34',
            ),
            368 => 
            array (
                'id' => 376,
                'cc_chapter_id' => 136,
                'name' => ' بیداری اسلامی در جهان اسلام',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:06:47',
                'updated_at' => '2026-02-07 21:06:47',
            ),
            369 => 
            array (
                'id' => 377,
                'cc_chapter_id' => 137,
            'name' => 'درک مطلب (reading comprehension)',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:08:42',
                'updated_at' => '2026-02-07 21:08:42',
            ),
            370 => 
            array (
                'id' => 378,
                'cc_chapter_id' => 137,
            'name' => 'گرامر (grammar)',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:08:51',
                'updated_at' => '2026-02-07 21:08:51',
            ),
            371 => 
            array (
                'id' => 379,
                'cc_chapter_id' => 137,
            'name' => 'واژگان (vocabulary)',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:08:58',
                'updated_at' => '2026-02-07 21:08:58',
            ),
            372 => 
            array (
                'id' => 380,
                'cc_chapter_id' => 137,
            'name' => 'نگارش (writing)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:09:07',
                'updated_at' => '2026-02-07 21:09:07',
            ),
            373 => 
            array (
                'id' => 381,
                'cc_chapter_id' => 137,
            'name' => 'Listening (شنیداری)',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:09:20',
                'updated_at' => '2026-02-07 21:09:20',
            ),
            374 => 
            array (
                'id' => 382,
                'cc_chapter_id' => 138,
            'name' => 'درک مطلب (reading comprehension)',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:08:42',
                'updated_at' => '2026-02-07 21:08:42',
            ),
            375 => 
            array (
                'id' => 383,
                'cc_chapter_id' => 138,
            'name' => 'گرامر (grammar)',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:08:51',
                'updated_at' => '2026-02-07 21:08:51',
            ),
            376 => 
            array (
                'id' => 384,
                'cc_chapter_id' => 138,
            'name' => 'واژگان (vocabulary)',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:08:58',
                'updated_at' => '2026-02-07 21:08:58',
            ),
            377 => 
            array (
                'id' => 385,
                'cc_chapter_id' => 138,
            'name' => 'نگارش (writing)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:09:07',
                'updated_at' => '2026-02-07 21:09:07',
            ),
            378 => 
            array (
                'id' => 386,
                'cc_chapter_id' => 138,
            'name' => 'Listening (شنیداری)',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:09:20',
                'updated_at' => '2026-02-07 21:09:20',
            ),
            379 => 
            array (
                'id' => 387,
                'cc_chapter_id' => 139,
            'name' => 'درک مطلب (reading comprehension)',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:08:42',
                'updated_at' => '2026-02-07 21:08:42',
            ),
            380 => 
            array (
                'id' => 388,
                'cc_chapter_id' => 139,
            'name' => 'گرامر (grammar)',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:08:51',
                'updated_at' => '2026-02-07 21:08:51',
            ),
            381 => 
            array (
                'id' => 389,
                'cc_chapter_id' => 139,
            'name' => 'واژگان (vocabulary)',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:08:58',
                'updated_at' => '2026-02-07 21:08:58',
            ),
            382 => 
            array (
                'id' => 390,
                'cc_chapter_id' => 139,
            'name' => 'نگارش (writing)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:09:07',
                'updated_at' => '2026-02-07 21:09:07',
            ),
            383 => 
            array (
                'id' => 391,
                'cc_chapter_id' => 139,
            'name' => 'Listening (شنیداری)',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:09:20',
                'updated_at' => '2026-02-07 21:09:20',
            ),
            384 => 
            array (
                'id' => 392,
                'cc_chapter_id' => 140,
                'name' => ' زمین‌شناسی ایران',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:13:03',
                'updated_at' => '2026-02-07 21:13:03',
            ),
            385 => 
            array (
                'id' => 393,
                'cc_chapter_id' => 140,
                'name' => 'تشکیل عناصر',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:13:09',
                'updated_at' => '2026-02-07 21:13:23',
            ),
            386 => 
            array (
                'id' => 394,
                'cc_chapter_id' => 140,
                'name' => 'کهکشان راه شیری',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:13:19',
                'updated_at' => '2026-02-07 21:13:19',
            ),
            387 => 
            array (
                'id' => 395,
                'cc_chapter_id' => 140,
                'name' => 'سامانۀ خورشیدی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:13:31',
                'updated_at' => '2026-02-07 21:13:31',
            ),
            388 => 
            array (
                'id' => 396,
                'cc_chapter_id' => 140,
                'name' => 'تکوین زمین و آغاز زندگی در آن',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:13:38',
                'updated_at' => '2026-02-07 21:13:38',
            ),
            389 => 
            array (
                'id' => 397,
                'cc_chapter_id' => 140,
                'name' => 'سن زمین',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:13:44',
                'updated_at' => '2026-02-07 21:13:44',
            ),
            390 => 
            array (
                'id' => 398,
                'cc_chapter_id' => 140,
                'name' => 'زمان در زمین‌شناسی',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:13:51',
                'updated_at' => '2026-02-07 21:13:51',
            ),
            391 => 
            array (
                'id' => 399,
                'cc_chapter_id' => 140,
                'name' => 'تغییرات آب‌و‌هوایی',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:13:58',
                'updated_at' => '2026-02-07 21:13:58',
            ),
            392 => 
            array (
                'id' => 400,
                'cc_chapter_id' => 140,
                'name' => 'علم، زندگی، کارآفرینی',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:14:05',
                'updated_at' => '2026-02-07 21:14:05',
            ),
            393 => 
            array (
                'id' => 401,
                'cc_chapter_id' => 141,
                'name' => 'منابع معدنی در زندگی ما',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:14:23',
                'updated_at' => '2026-02-07 21:14:23',
            ),
            394 => 
            array (
                'id' => 402,
                'cc_chapter_id' => 141,
                'name' => 'غلظت عناصر در پوستۀ زمین و کانی‌های سیلیکاتی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:14:31',
                'updated_at' => '2026-02-07 21:14:31',
            ),
            395 => 
            array (
                'id' => 403,
                'cc_chapter_id' => 141,
                'name' => 'سری واکنشی بوون',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:14:38',
                'updated_at' => '2026-02-07 21:14:38',
            ),
            396 => 
            array (
                'id' => 404,
                'cc_chapter_id' => 141,
                'name' => 'کانه و کانسنگ',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:14:44',
                'updated_at' => '2026-02-07 21:14:44',
            ),
            397 => 
            array (
                'id' => 405,
                'cc_chapter_id' => 141,
                'name' => 'طبقه‌بندی کانسنگ‌ها',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:14:51',
                'updated_at' => '2026-02-07 21:14:51',
            ),
            398 => 
            array (
                'id' => 406,
                'cc_chapter_id' => 141,
                'name' => 'اکتشاف معدن',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:14:57',
                'updated_at' => '2026-02-07 21:14:57',
            ),
            399 => 
            array (
                'id' => 407,
                'cc_chapter_id' => 141,
                'name' => 'استخراج معدن و فرآوری مادۀ معدنی',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:15:04',
                'updated_at' => '2026-02-07 21:15:04',
            ),
            400 => 
            array (
                'id' => 408,
                'cc_chapter_id' => 141,
                'name' => 'گوهرها، زیبایی شگفت‌انگیز دنیای کانی‌ها',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:15:11',
                'updated_at' => '2026-02-07 21:15:11',
            ),
            401 => 
            array (
                'id' => 409,
                'cc_chapter_id' => 141,
                'name' => 'سوخت‌های فسیلی',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:15:26',
                'updated_at' => '2026-02-07 21:15:26',
            ),
            402 => 
            array (
                'id' => 410,
                'cc_chapter_id' => 142,
                'name' => 'سوخت‌های فسیلی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:15:52',
                'updated_at' => '2026-02-07 21:15:52',
            ),
            403 => 
            array (
                'id' => 411,
                'cc_chapter_id' => 142,
                'name' => 'فرونشست زمین',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:16:00',
                'updated_at' => '2026-02-07 21:16:00',
            ),
            404 => 
            array (
                'id' => 412,
                'cc_chapter_id' => 142,
                'name' => 'منابع خاک',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:16:06',
                'updated_at' => '2026-02-07 21:16:06',
            ),
            405 => 
            array (
                'id' => 413,
                'cc_chapter_id' => 142,
                'name' => 'فرسایش',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:16:13',
                'updated_at' => '2026-02-07 21:16:13',
            ),
            406 => 
            array (
                'id' => 414,
                'cc_chapter_id' => 142,
                'name' => 'علم، زندگی، کارآفرینی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:16:19',
                'updated_at' => '2026-02-07 21:16:19',
            ),
            407 => 
            array (
                'id' => 415,
                'cc_chapter_id' => 143,
                'name' => 'مقدمه و چرخۀ ویلسون',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:17:07',
                'updated_at' => '2026-02-07 21:17:07',
            ),
            408 => 
            array (
                'id' => 416,
                'cc_chapter_id' => 143,
                'name' => 'مقاومت سنگ‌ها در برابر تنش',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:17:15',
                'updated_at' => '2026-02-07 21:17:15',
            ),
            409 => 
            array (
                'id' => 417,
                'cc_chapter_id' => 143,
                'name' => 'امتداد و شیب لایه‌ها‌',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:17:21',
                'updated_at' => '2026-02-07 21:17:21',
            ),
            410 => 
            array (
                'id' => 418,
                'cc_chapter_id' => 143,
                'name' => 'شکستگی‌ها',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:17:30',
                'updated_at' => '2026-02-07 21:17:30',
            ),
            411 => 
            array (
                'id' => 419,
                'cc_chapter_id' => 143,
                'name' => 'چین‌خوردگی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:17:37',
                'updated_at' => '2026-02-07 21:17:37',
            ),
            412 => 
            array (
                'id' => 420,
                'cc_chapter_id' => 143,
                'name' => 'آتشفشان‌',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:17:48',
                'updated_at' => '2026-02-07 21:17:48',
            ),
            413 => 
            array (
                'id' => 421,
                'cc_chapter_id' => 143,
                'name' => 'گاز و بخارهای آتشفشانی‌',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:17:55',
                'updated_at' => '2026-02-07 21:17:55',
            ),
            414 => 
            array (
                'id' => 422,
                'cc_chapter_id' => 143,
                'name' => 'فواید آتشفشان‌ها‌',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:18:01',
                'updated_at' => '2026-02-07 21:18:01',
            ),
            415 => 
            array (
                'id' => 423,
                'cc_chapter_id' => 143,
                'name' => 'زمین‌لرزه‌',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:18:11',
                'updated_at' => '2026-02-07 21:18:11',
            ),
            416 => 
            array (
                'id' => 424,
                'cc_chapter_id' => 143,
                'name' => 'امواج لرزه‌ای',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:18:24',
                'updated_at' => '2026-02-07 21:18:24',
            ),
            417 => 
            array (
                'id' => 425,
                'cc_chapter_id' => 143,
                'name' => 'مقیاس اندازه‌گیری زمین‌لرزه‌',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:18:39',
                'updated_at' => '2026-02-07 21:18:39',
            ),
            418 => 
            array (
                'id' => 426,
                'cc_chapter_id' => 143,
                'name' => 'پیش‌بینی زمین‌لرزه‌',
                'order' => 11,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:19:01',
                'updated_at' => '2026-02-07 21:19:01',
            ),
            419 => 
            array (
                'id' => 427,
                'cc_chapter_id' => 143,
                'name' => 'ایمنی در برابر زمین‌لرزه‌',
                'order' => 12,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:19:11',
                'updated_at' => '2026-02-07 21:19:11',
            ),
            420 => 
            array (
                'id' => 428,
                'cc_chapter_id' => 143,
                'name' => 'علم، زندگی، کارآفرینی‌',
                'order' => 14,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:19:22',
                'updated_at' => '2026-02-07 21:19:22',
            ),
            421 => 
            array (
                'id' => 429,
                'cc_chapter_id' => 144,
                'name' => 'زمین‌شناسی پزشکی‌',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:19:48',
                'updated_at' => '2026-02-07 21:19:48',
            ),
            422 => 
            array (
                'id' => 430,
                'cc_chapter_id' => 144,
                'name' => 'چرخه بیوژئوشیمیایی و تقسیم‌بندی بیوشیمیایی عناصر‌',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:20:48',
                'updated_at' => '2026-02-07 21:20:48',
            ),
            423 => 
            array (
                'id' => 431,
                'cc_chapter_id' => 144,
                'name' => 'منشا بیماری‌های زمین‌زاد‌',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:20:54',
                'updated_at' => '2026-02-07 21:20:54',
            ),
            424 => 
            array (
                'id' => 432,
                'cc_chapter_id' => 144,
                'name' => 'اثرات توفان‌های گرد و غبار و ریزگردها‌',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:21:03',
                'updated_at' => '2026-02-07 21:21:03',
            ),
            425 => 
            array (
                'id' => 433,
                'cc_chapter_id' => 144,
                'name' => 'کاربرد کانی‌ها در داروسازی و صنایع بهداشتی‌',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:21:09',
                'updated_at' => '2026-02-07 21:21:09',
            ),
            426 => 
            array (
                'id' => 434,
                'cc_chapter_id' => 144,
                'name' => 'علم، زندگی، کارآفرینی‌',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:21:17',
                'updated_at' => '2026-02-07 21:21:17',
            ),
            427 => 
            array (
                'id' => 436,
                'cc_chapter_id' => 145,
                'name' => 'مکان‌یابی سازه‌ها‌',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:21:58',
                'updated_at' => '2026-02-07 21:21:58',
            ),
            428 => 
            array (
                'id' => 437,
                'cc_chapter_id' => 145,
                'name' => 'نحوۀ به‌دست آوردن اطلاعات زمین‌شناسی‌',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:22:03',
                'updated_at' => '2026-02-07 21:22:03',
            ),
            429 => 
            array (
                'id' => 438,
                'cc_chapter_id' => 145,
                'name' => 'عوامل مؤثر بر مکان‌یابی سازه‌ها‌',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:22:08',
                'updated_at' => '2026-02-07 21:22:08',
            ),
            430 => 
            array (
                'id' => 439,
                'cc_chapter_id' => 145,
                'name' => 'مکان مناسب برای ساخت سد‌',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:22:17',
                'updated_at' => '2026-02-07 21:22:17',
            ),
            431 => 
            array (
                'id' => 440,
                'cc_chapter_id' => 145,
                'name' => 'مکان مناسب برای ساخت تونل و فضاهای زیرزمینی‌',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:22:23',
                'updated_at' => '2026-02-07 21:22:23',
            ),
            432 => 
            array (
                'id' => 441,
                'cc_chapter_id' => 145,
                'name' => 'مکان یابی مناسب برای ساخت سازه‌های دریایی‌',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:22:35',
                'updated_at' => '2026-02-07 21:22:35',
            ),
            433 => 
            array (
                'id' => 442,
                'cc_chapter_id' => 145,
                'name' => 'شاخص‌های مهندسی مصالح‌',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:22:43',
                'updated_at' => '2026-02-07 21:22:43',
            ),
            434 => 
            array (
                'id' => 443,
                'cc_chapter_id' => 145,
                'name' => 'مصالح مورد نیاز برای احداث سازه‌ها‌',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:22:54',
                'updated_at' => '2026-02-07 21:22:54',
            ),
            435 => 
            array (
                'id' => 444,
                'cc_chapter_id' => 145,
                'name' => 'علم، زندگی، کارآفرینی‌',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:23:04',
                'updated_at' => '2026-02-07 21:23:04',
            ),
            436 => 
            array (
                'id' => 445,
                'cc_chapter_id' => 146,
                'name' => 'تاریخچۀ زمین‌شناسی ایران‌',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:23:36',
                'updated_at' => '2026-02-07 21:23:36',
            ),
            437 => 
            array (
                'id' => 446,
                'cc_chapter_id' => 146,
                'name' => 'نقشه‌های زمین‌شناسی‌',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:23:42',
                'updated_at' => '2026-02-07 21:23:42',
            ),
            438 => 
            array (
                'id' => 447,
                'cc_chapter_id' => 146,
                'name' => 'پهنه‌های زمین‌شناسی ایران‌',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:23:48',
                'updated_at' => '2026-02-07 21:23:48',
            ),
            439 => 
            array (
                'id' => 448,
                'cc_chapter_id' => 146,
                'name' => 'منابع معدنی و ذخایر انرژی ایران‌',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:23:56',
                'updated_at' => '2026-02-07 21:23:56',
            ),
            440 => 
            array (
                'id' => 449,
                'cc_chapter_id' => 146,
                'name' => 'ذخایر نفت و گاز ایران‌',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:24:04',
                'updated_at' => '2026-02-07 21:24:04',
            ),
            441 => 
            array (
                'id' => 450,
                'cc_chapter_id' => 146,
                'name' => 'گسل‌های ایران‌',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:24:14',
                'updated_at' => '2026-02-07 21:24:14',
            ),
            442 => 
            array (
                'id' => 451,
                'cc_chapter_id' => 146,
                'name' => 'آتشفشان‌های ایران‌',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:24:22',
                'updated_at' => '2026-02-07 21:24:22',
            ),
            443 => 
            array (
                'id' => 452,
                'cc_chapter_id' => 146,
                'name' => 'زمین گردشگری و ژئوپارک‌',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:24:39',
                'updated_at' => '2026-02-07 21:24:39',
            ),
            444 => 
            array (
                'id' => 453,
                'cc_chapter_id' => 146,
                'name' => 'علم، زندگی، کارآفرینی‌',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:24:47',
                'updated_at' => '2026-02-07 21:24:47',
            ),
            445 => 
            array (
                'id' => 454,
                'cc_chapter_id' => 147,
                'name' => ' سرچشمه‌ی زندگی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:37:25',
                'updated_at' => '2026-02-07 21:37:25',
            ),
            446 => 
            array (
                'id' => 456,
                'cc_chapter_id' => 148,
                'name' => 'خاک، بستر زندگی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:38:18',
                'updated_at' => '2026-02-07 21:38:18',
            ),
            447 => 
            array (
                'id' => 457,
                'cc_chapter_id' => 149,
                'name' => 'هوا، نَفَسِ زندگی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:38:45',
                'updated_at' => '2026-02-07 21:38:45',
            ),
            448 => 
            array (
                'id' => 458,
                'cc_chapter_id' => 150,
                'name' => 'انرژی، حرکت، زندگی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:39:04',
                'updated_at' => '2026-02-07 21:39:04',
            ),
            449 => 
            array (
                'id' => 459,
                'cc_chapter_id' => 151,
                'name' => 'زباله، فاجعه‌ی محیط زیست',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:39:27',
                'updated_at' => '2026-02-07 21:39:27',
            ),
            450 => 
            array (
                'id' => 460,
                'cc_chapter_id' => 152,
                'name' => 'تنوع زیستی، تابلوی زیبای آفرینش',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:40:11',
                'updated_at' => '2026-02-07 21:40:11',
            ),
            451 => 
            array (
                'id' => 461,
                'cc_chapter_id' => 153,
                'name' => 'محیط زیست، بستر گردشگری مسئولانه',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:40:37',
                'updated_at' => '2026-02-07 21:40:37',
            ),
            452 => 
            array (
                'id' => 462,
                'cc_chapter_id' => 154,
                'name' => 'مجموعه های متناهی و نامتناهی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:51:05',
                'updated_at' => '2026-02-08 14:51:05',
            ),
            453 => 
            array (
                'id' => 466,
                'cc_chapter_id' => 154,
                'name' => 'متمم یک مجموعه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:51:53',
                'updated_at' => '2026-02-08 14:51:53',
            ),
            454 => 
            array (
                'id' => 467,
                'cc_chapter_id' => 154,
                'name' => 'الگو و دنباله',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:52:05',
                'updated_at' => '2026-02-08 14:52:05',
            ),
            455 => 
            array (
                'id' => 468,
                'cc_chapter_id' => 154,
                'name' => 'دنباله های حسابی و هندسی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:52:19',
                'updated_at' => '2026-02-08 14:52:19',
            ),
            456 => 
            array (
                'id' => 469,
                'cc_chapter_id' => 155,
                'name' => 'نسبت های مثلثاتی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:53:19',
                'updated_at' => '2026-02-08 14:53:19',
            ),
            457 => 
            array (
                'id' => 470,
                'cc_chapter_id' => 155,
                'name' => 'مثلث های متشابه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:53:26',
                'updated_at' => '2026-02-08 14:53:26',
            ),
            458 => 
            array (
                'id' => 471,
                'cc_chapter_id' => 155,
            'name' => 'نسبت های مثلثاتی(تعریف و خواص)',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:53:32',
                'updated_at' => '2026-02-08 14:53:32',
            ),
            459 => 
            array (
                'id' => 472,
                'cc_chapter_id' => 155,
                'name' => 'مساحت مثلث',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:53:37',
                'updated_at' => '2026-02-08 14:53:37',
            ),
            460 => 
            array (
                'id' => 473,
                'cc_chapter_id' => 156,
                'name' => 'ریشه و توان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:53:54',
                'updated_at' => '2026-02-08 14:53:54',
            ),
            461 => 
            array (
                'id' => 474,
                'cc_chapter_id' => 156,
                'name' => 'ریشه n ام',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:54:02',
                'updated_at' => '2026-02-08 14:54:02',
            ),
            462 => 
            array (
                'id' => 475,
                'cc_chapter_id' => 156,
                'name' => 'توان های گویا',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:54:09',
                'updated_at' => '2026-02-08 14:54:09',
            ),
            463 => 
            array (
                'id' => 476,
                'cc_chapter_id' => 156,
                'name' => 'عبارت های جبری',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:54:17',
                'updated_at' => '2026-02-08 14:54:17',
            ),
            464 => 
            array (
                'id' => 477,
                'cc_chapter_id' => 157,
                'name' => 'معادله درجه دوم و روش های مختلف حل آن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:54:39',
                'updated_at' => '2026-02-08 14:54:39',
            ),
            465 => 
            array (
                'id' => 478,
                'cc_chapter_id' => 157,
                'name' => 'سهمی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:54:57',
                'updated_at' => '2026-02-08 14:54:57',
            ),
            466 => 
            array (
                'id' => 479,
                'cc_chapter_id' => 157,
                'name' => 'تعیین علامت',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:55:08',
                'updated_at' => '2026-02-08 14:55:08',
            ),
            467 => 
            array (
                'id' => 480,
                'cc_chapter_id' => 158,
                'name' => 'مفهوم تابع و بازنمایی های آن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:55:47',
                'updated_at' => '2026-02-08 14:55:47',
            ),
            468 => 
            array (
                'id' => 481,
                'cc_chapter_id' => 158,
                'name' => 'دامنه و برد توابع',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:55:54',
                'updated_at' => '2026-02-08 14:55:54',
            ),
            469 => 
            array (
                'id' => 482,
                'cc_chapter_id' => 158,
                'name' => 'انواع توابع',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:56:04',
                'updated_at' => '2026-02-08 14:56:04',
            ),
            470 => 
            array (
                'id' => 483,
                'cc_chapter_id' => 159,
                'name' => 'شمارش',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:02:09',
                'updated_at' => '2026-02-08 15:02:09',
            ),
            471 => 
            array (
                'id' => 484,
                'cc_chapter_id' => 159,
                'name' => 'جایگشت',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:02:19',
                'updated_at' => '2026-02-08 15:02:19',
            ),
            472 => 
            array (
                'id' => 485,
                'cc_chapter_id' => 159,
                'name' => 'ترکیب',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:02:27',
                'updated_at' => '2026-02-08 15:02:27',
            ),
            473 => 
            array (
                'id' => 486,
                'cc_chapter_id' => 160,
                'name' => 'احتمال یا اندازه گیری شانس',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:02:53',
                'updated_at' => '2026-02-08 15:02:53',
            ),
            474 => 
            array (
                'id' => 487,
                'cc_chapter_id' => 160,
                'name' => 'مقدمه ای بر علم آمار، جامعه و نمونه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:03:02',
                'updated_at' => '2026-02-08 15:03:02',
            ),
            475 => 
            array (
                'id' => 488,
                'cc_chapter_id' => 160,
                'name' => 'متغیر و انواع آن',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:03:14',
                'updated_at' => '2026-02-08 15:03:14',
            ),
            476 => 
            array (
                'id' => 489,
                'cc_chapter_id' => 161,
                'name' => 'ترسیم های هندسی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:04:22',
                'updated_at' => '2026-02-08 15:04:39',
            ),
            477 => 
            array (
                'id' => 490,
                'cc_chapter_id' => 161,
                'name' => 'استدلال',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:04:34',
                'updated_at' => '2026-02-08 15:04:34',
            ),
            478 => 
            array (
                'id' => 491,
                'cc_chapter_id' => 162,
                'name' => 'نسبت و تناسب',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:05:02',
                'updated_at' => '2026-02-08 15:05:02',
            ),
            479 => 
            array (
                'id' => 492,
                'cc_chapter_id' => 162,
                'name' => 'قضیه تالس',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:05:09',
                'updated_at' => '2026-02-08 15:05:09',
            ),
            480 => 
            array (
                'id' => 493,
                'cc_chapter_id' => 162,
                'name' => 'تشابه مثلثها',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:05:18',
                'updated_at' => '2026-02-08 15:05:18',
            ),
            481 => 
            array (
                'id' => 494,
                'cc_chapter_id' => 162,
                'name' => 'کاربرد تالس و تشابه',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:05:34',
                'updated_at' => '2026-02-08 15:05:34',
            ),
            482 => 
            array (
                'id' => 495,
                'cc_chapter_id' => 163,
                'name' => 'چند ضلعی ها و ویژگی های آنها',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:06:10',
                'updated_at' => '2026-02-08 15:06:10',
            ),
            483 => 
            array (
                'id' => 496,
                'cc_chapter_id' => 163,
                'name' => 'مساحت و کاربردهای آن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:06:19',
                'updated_at' => '2026-02-08 15:06:19',
            ),
            484 => 
            array (
                'id' => 497,
                'cc_chapter_id' => 164,
                'name' => 'خط، نقطه و صفحه',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:06:35',
                'updated_at' => '2026-02-08 15:06:35',
            ),
            485 => 
            array (
                'id' => 498,
                'cc_chapter_id' => 164,
                'name' => 'تفکر تجسمی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:06:40',
                'updated_at' => '2026-02-08 15:06:40',
            ),
            486 => 
            array (
                'id' => 499,
                'cc_chapter_id' => 165,
                'name' => 'پیدایش عنصرها',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:07:33',
                'updated_at' => '2026-02-08 15:07:33',
            ),
            487 => 
            array (
                'id' => 500,
                'cc_chapter_id' => 165,
                'name' => 'طبقه‌بندی عنصرها و جرم اتمی آن‌ها',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:07:40',
                'updated_at' => '2026-02-08 15:07:40',
            ),
            488 => 
            array (
                'id' => 501,
                'cc_chapter_id' => 165,
                'name' => 'شمارش ذره‌ها از روی جرم آن‌ها',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:07:47',
                'updated_at' => '2026-02-08 15:07:47',
            ),
            489 => 
            array (
                'id' => 502,
                'cc_chapter_id' => 165,
                'name' => 'نور، کلید شناخت جهان',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:07:57',
                'updated_at' => '2026-02-08 15:07:57',
            ),
            490 => 
            array (
                'id' => 503,
                'cc_chapter_id' => 165,
                'name' => 'لایه‌ها و زیرلایه‌های الکترونی و آرایش الکترونی اتم',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:08:10',
                'updated_at' => '2026-02-08 15:08:10',
            ),
            491 => 
            array (
                'id' => 504,
                'cc_chapter_id' => 165,
                'name' => 'ساختار اتم و رفتار آن',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:08:25',
                'updated_at' => '2026-02-08 15:08:25',
            ),
            492 => 
            array (
                'id' => 505,
                'cc_chapter_id' => 166,
                'name' => 'هواکره و ویژگی‌های آن:',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:09:08',
                'updated_at' => '2026-02-08 15:09:08',
            ),
            493 => 
            array (
                'id' => 506,
                'cc_chapter_id' => 166,
                'name' => 'ترکیب اکسیژن با فلزها و نافلز ها',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:09:21',
                'updated_at' => '2026-02-08 15:09:21',
            ),
            494 => 
            array (
                'id' => 507,
                'cc_chapter_id' => 166,
                'name' => 'ساختار لوویس مولکول‌ها',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:09:33',
                'updated_at' => '2026-02-08 15:09:33',
            ),
            495 => 
            array (
                'id' => 508,
                'cc_chapter_id' => 166,
                'name' => 'واکنش‌های شیمیایی و قانون پایستگی جرم',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:09:55',
                'updated_at' => '2026-02-08 15:09:55',
            ),
            496 => 
            array (
                'id' => 509,
                'cc_chapter_id' => 166,
                'name' => 'چه بر سر هواکره می آوریم؟',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:10:02',
                'updated_at' => '2026-02-08 15:10:02',
            ),
            497 => 
            array (
                'id' => 510,
                'cc_chapter_id' => 166,
                'name' => 'اوزون، دگرشکلی از اکسیژن در هواکره',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:10:11',
                'updated_at' => '2026-02-08 15:10:11',
            ),
            498 => 
            array (
                'id' => 511,
                'cc_chapter_id' => 166,
                'name' => 'رفتار گازها',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:10:21',
                'updated_at' => '2026-02-08 15:10:21',
            ),
            499 => 
            array (
                'id' => 512,
                'cc_chapter_id' => 166,
                'name' => 'استوکیومتری واکنش ها',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:10:31',
                'updated_at' => '2026-02-08 15:10:31',
            ),
        ));
        \DB::table('cc_topics')->insert(array (
            0 => 
            array (
                'id' => 513,
                'cc_chapter_id' => 166,
                'name' => 'تولید آمونیاک به روش هابر',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:10:43',
                'updated_at' => '2026-02-08 15:10:43',
            ),
            1 => 
            array (
                'id' => 514,
                'cc_chapter_id' => 167,
                'name' => 'منابع آب در زمین',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:11:15',
                'updated_at' => '2026-02-08 15:11:15',
            ),
            2 => 
            array (
                'id' => 515,
                'cc_chapter_id' => 167,
                'name' => 'ترکیب‌های یونی چندتایی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:11:52',
                'updated_at' => '2026-02-08 15:11:52',
            ),
            3 => 
            array (
                'id' => 516,
                'cc_chapter_id' => 167,
                'name' => 'محلول و مقدار حل‌شونده‌ها',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:12:00',
                'updated_at' => '2026-02-08 15:12:00',
            ),
            4 => 
            array (
                'id' => 517,
                'cc_chapter_id' => 167,
                'name' => 'آیا نمک ها به یک اندازه در آب حل می شوند؟',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:12:06',
                'updated_at' => '2026-02-08 15:12:06',
            ),
            5 => 
            array (
                'id' => 518,
                'cc_chapter_id' => 167,
                'name' => 'رفتار آب و دیگر مولکول ها در میدان الکتریکی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:12:14',
                'updated_at' => '2026-02-08 15:12:14',
            ),
            6 => 
            array (
                'id' => 519,
                'cc_chapter_id' => 167,
                'name' => 'آب و دیگر حلال ها',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:12:23',
                'updated_at' => '2026-02-08 15:12:23',
            ),
            7 => 
            array (
                'id' => 520,
                'cc_chapter_id' => 167,
                'name' => 'انحلال گاز ها در آب',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:12:30',
                'updated_at' => '2026-02-08 15:12:30',
            ),
            8 => 
            array (
                'id' => 521,
                'cc_chapter_id' => 167,
                'name' => 'ردّ پای آب در زندگی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:12:36',
                'updated_at' => '2026-02-08 15:12:36',
            ),
            9 => 
            array (
                'id' => 522,
                'cc_chapter_id' => 168,
                'name' => 'فیزیک، دانش بنیادی - مدل‌سازی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:14:03',
                'updated_at' => '2026-02-08 15:14:03',
            ),
            10 => 
            array (
                'id' => 523,
                'cc_chapter_id' => 168,
                'name' => 'اندازه‌گیری و کمیت‌های فیزیکی - دستگاه بین‌المللی یکاها',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:14:11',
                'updated_at' => '2026-02-08 15:14:11',
            ),
            11 => 
            array (
                'id' => 524,
                'cc_chapter_id' => 168,
                'name' => 'تبدیل یکاها',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:14:18',
                'updated_at' => '2026-02-08 15:14:18',
            ),
            12 => 
            array (
                'id' => 525,
                'cc_chapter_id' => 168,
                'name' => 'اندازه‌گیری و دقت وسیله‌های اندازه‌گیری',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:14:32',
                'updated_at' => '2026-02-08 15:14:32',
            ),
            13 => 
            array (
                'id' => 526,
                'cc_chapter_id' => 168,
                'name' => 'چگالی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:14:40',
                'updated_at' => '2026-02-08 15:14:40',
            ),
            14 => 
            array (
                'id' => 527,
                'cc_chapter_id' => 169,
                'name' => 'حالت‌های ماده و نیروهای بین مولکولی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:15:00',
                'updated_at' => '2026-02-08 15:15:00',
            ),
            15 => 
            array (
                'id' => 528,
                'cc_chapter_id' => 169,
                'name' => 'فشار در جامدات',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:15:07',
                'updated_at' => '2026-02-08 15:15:07',
            ),
            16 => 
            array (
                'id' => 529,
                'cc_chapter_id' => 169,
                'name' => 'فشار در شاره‌ها',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:15:14',
                'updated_at' => '2026-02-08 15:15:14',
            ),
            17 => 
            array (
                'id' => 530,
                'cc_chapter_id' => 169,
                'name' => 'شناوری',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:15:21',
                'updated_at' => '2026-02-08 15:15:21',
            ),
            18 => 
            array (
                'id' => 531,
                'cc_chapter_id' => 169,
                'name' => 'شاره در حال حرکت و اصل برنولی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:15:32',
                'updated_at' => '2026-02-08 15:15:32',
            ),
            19 => 
            array (
                'id' => 532,
                'cc_chapter_id' => 170,
                'name' => 'انرژی جنبشی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:15:55',
                'updated_at' => '2026-02-08 15:15:55',
            ),
            20 => 
            array (
                'id' => 533,
                'cc_chapter_id' => 170,
                'name' => 'کار انجام شده توسط نیروی ثابت',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:16:03',
                'updated_at' => '2026-02-08 15:16:03',
            ),
            21 => 
            array (
                'id' => 534,
                'cc_chapter_id' => 170,
                'name' => 'قضیه کار و انرژی جنبشی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:16:12',
                'updated_at' => '2026-02-08 15:16:12',
            ),
            22 => 
            array (
                'id' => 535,
                'cc_chapter_id' => 170,
                'name' => 'کار و انرژی پتانسیل گرانشی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:16:23',
                'updated_at' => '2026-02-08 15:16:23',
            ),
            23 => 
            array (
                'id' => 536,
                'cc_chapter_id' => 170,
                'name' => 'پایستگی انرژی مکانیکی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:16:33',
                'updated_at' => '2026-02-08 15:16:33',
            ),
            24 => 
            array (
                'id' => 537,
                'cc_chapter_id' => 170,
                'name' => 'کار و انرژی درونی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:17:02',
                'updated_at' => '2026-02-08 15:17:02',
            ),
            25 => 
            array (
                'id' => 538,
                'cc_chapter_id' => 170,
                'name' => ' توان و بازده',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:17:15',
                'updated_at' => '2026-02-08 15:17:15',
            ),
            26 => 
            array (
                'id' => 539,
                'cc_chapter_id' => 170,
                'name' => 'دما و گرما',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:18:38',
                'updated_at' => '2026-02-08 15:18:38',
            ),
            27 => 
            array (
                'id' => 540,
                'cc_chapter_id' => 171,
                'name' => 'دما و دماسنجی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:24:59',
                'updated_at' => '2026-02-08 15:24:59',
            ),
            28 => 
            array (
                'id' => 541,
                'cc_chapter_id' => 171,
                'name' => 'انبساط گرمایی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:25:12',
                'updated_at' => '2026-02-08 15:25:12',
            ),
            29 => 
            array (
                'id' => 542,
                'cc_chapter_id' => 171,
                'name' => 'گرما',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:25:20',
                'updated_at' => '2026-02-08 15:25:20',
            ),
            30 => 
            array (
                'id' => 543,
                'cc_chapter_id' => 171,
                'name' => 'تغییر حالت‌های ماده',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:25:32',
                'updated_at' => '2026-02-08 15:25:32',
            ),
            31 => 
            array (
                'id' => 544,
                'cc_chapter_id' => 171,
                'name' => 'روش‌های انتقال گرما',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:32:21',
                'updated_at' => '2026-02-08 15:32:21',
            ),
            32 => 
            array (
                'id' => 545,
                'cc_chapter_id' => 171,
                'name' => 'قوانین گازها',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:32:44',
                'updated_at' => '2026-02-08 15:32:44',
            ),
            33 => 
            array (
                'id' => 546,
                'cc_chapter_id' => 172,
                'name' => 'معادله حالت و فرآیندهای ترمودینامیکی ایستاوار',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:33:08',
                'updated_at' => '2026-02-08 15:33:08',
            ),
            34 => 
            array (
                'id' => 547,
                'cc_chapter_id' => 172,
                'name' => ' تبادل انرژی، انرژی درونی و قانون اول ترمودینامیک',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:33:17',
                'updated_at' => '2026-02-08 15:33:17',
            ),
            35 => 
            array (
                'id' => 548,
                'cc_chapter_id' => 172,
                'name' => 'برخی از فرایندهای ترمودینامیکی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:33:29',
                'updated_at' => '2026-02-08 15:33:29',
            ),
            36 => 
            array (
                'id' => 549,
                'cc_chapter_id' => 172,
                'name' => 'چرخه ترمودینامیکی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:33:38',
                'updated_at' => '2026-02-08 15:33:38',
            ),
            37 => 
            array (
                'id' => 550,
                'cc_chapter_id' => 172,
                'name' => 'ماشین‌های گرمایی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:33:54',
                'updated_at' => '2026-02-08 15:33:54',
            ),
            38 => 
            array (
                'id' => 551,
                'cc_chapter_id' => 172,
            'name' => ' قانون دوم ترمودینامیک (به بیان ماشین گرمایی و یخچال)',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:34:05',
                'updated_at' => '2026-02-08 15:34:05',
            ),
            39 => 
            array (
                'id' => 552,
                'cc_chapter_id' => 173,
                'name' => 'چشمه',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:41:09',
                'updated_at' => '2026-02-08 15:41:09',
            ),
            40 => 
            array (
                'id' => 553,
                'cc_chapter_id' => 173,
                'name' => 'از آموختن، ننگ مدار',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:41:36',
                'updated_at' => '2026-02-08 15:41:36',
            ),
            41 => 
            array (
                'id' => 554,
                'cc_chapter_id' => 174,
                'name' => 'پاسداری از حقیقت',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:42:01',
                'updated_at' => '2026-02-08 15:42:01',
            ),
            42 => 
            array (
                'id' => 555,
                'cc_chapter_id' => 174,
                'name' => ' بیداد ظالمان',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:42:14',
                'updated_at' => '2026-02-08 15:42:14',
            ),
            43 => 
            array (
                'id' => 556,
                'cc_chapter_id' => 174,
                'name' => 'همای رحمت',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:42:25',
                'updated_at' => '2026-02-08 15:42:25',
            ),
            44 => 
            array (
                'id' => 557,
                'cc_chapter_id' => 175,
                'name' => 'مهر و وفا',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:42:49',
                'updated_at' => '2026-02-08 15:42:49',
            ),
            45 => 
            array (
                'id' => 558,
                'cc_chapter_id' => 175,
                'name' => 'جمال و کمال',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:43:02',
                'updated_at' => '2026-02-08 15:43:02',
            ),
            46 => 
            array (
                'id' => 559,
                'cc_chapter_id' => 175,
                'name' => 'بوی گل و ریحان‌ها',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:43:12',
                'updated_at' => '2026-02-08 15:43:12',
            ),
            47 => 
            array (
                'id' => 560,
                'cc_chapter_id' => 176,
                'name' => 'سفر به بصره',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:43:41',
                'updated_at' => '2026-02-08 15:43:41',
            ),
            48 => 
            array (
                'id' => 561,
                'cc_chapter_id' => 176,
                'name' => 'کلاس نقاشی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:43:53',
                'updated_at' => '2026-02-08 15:43:53',
            ),
            49 => 
            array (
                'id' => 562,
                'cc_chapter_id' => 177,
                'name' => ' دریادلان صف‌شکن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:44:12',
                'updated_at' => '2026-02-08 15:44:12',
            ),
            50 => 
            array (
                'id' => 563,
                'cc_chapter_id' => 177,
                'name' => 'خاک آزادگان',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:44:22',
                'updated_at' => '2026-02-08 15:44:22',
            ),
            51 => 
            array (
                'id' => 564,
                'cc_chapter_id' => 178,
                'name' => 'رستم و اشکبوس',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:44:45',
                'updated_at' => '2026-02-08 15:44:45',
            ),
            52 => 
            array (
                'id' => 565,
                'cc_chapter_id' => 178,
                'name' => ' گرد آفرید',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:44:54',
                'updated_at' => '2026-02-08 15:44:54',
            ),
            53 => 
            array (
                'id' => 566,
                'cc_chapter_id' => 178,
                'name' => 'دلیران و مردان ایران زمین',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:45:02',
                'updated_at' => '2026-02-08 15:45:02',
            ),
            54 => 
            array (
                'id' => 567,
                'cc_chapter_id' => 179,
                'name' => 'طوطی و بقال',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:45:20',
                'updated_at' => '2026-02-08 15:45:20',
            ),
            55 => 
            array (
                'id' => 568,
                'cc_chapter_id' => 179,
                'name' => 'خسرو',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:45:28',
                'updated_at' => '2026-02-08 15:45:28',
            ),
            56 => 
            array (
                'id' => 569,
                'cc_chapter_id' => 180,
                'name' => 'سپیده دم',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:45:50',
                'updated_at' => '2026-02-08 15:45:50',
            ),
            57 => 
            array (
                'id' => 570,
                'cc_chapter_id' => 180,
                'name' => 'عظمت نگاه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:46:04',
                'updated_at' => '2026-02-08 15:46:04',
            ),
            58 => 
            array (
                'id' => 571,
                'cc_chapter_id' => 181,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:12',
                'updated_at' => '2026-02-08 16:03:12',
            ),
            59 => 
            array (
                'id' => 572,
                'cc_chapter_id' => 181,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:19',
                'updated_at' => '2026-02-08 16:03:19',
            ),
            60 => 
            array (
                'id' => 573,
                'cc_chapter_id' => 181,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:54',
                'updated_at' => '2026-02-08 16:03:54',
            ),
            61 => 
            array (
                'id' => 574,
                'cc_chapter_id' => 182,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:12',
                'updated_at' => '2026-02-08 16:03:12',
            ),
            62 => 
            array (
                'id' => 575,
                'cc_chapter_id' => 182,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:19',
                'updated_at' => '2026-02-08 16:03:19',
            ),
            63 => 
            array (
                'id' => 576,
                'cc_chapter_id' => 182,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:54',
                'updated_at' => '2026-02-08 16:03:54',
            ),
            64 => 
            array (
                'id' => 577,
                'cc_chapter_id' => 183,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:12',
                'updated_at' => '2026-02-08 16:03:12',
            ),
            65 => 
            array (
                'id' => 578,
                'cc_chapter_id' => 183,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:19',
                'updated_at' => '2026-02-08 16:03:19',
            ),
            66 => 
            array (
                'id' => 579,
                'cc_chapter_id' => 183,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:54',
                'updated_at' => '2026-02-08 16:03:54',
            ),
            67 => 
            array (
                'id' => 580,
                'cc_chapter_id' => 184,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:12',
                'updated_at' => '2026-02-08 16:03:12',
            ),
            68 => 
            array (
                'id' => 581,
                'cc_chapter_id' => 184,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:19',
                'updated_at' => '2026-02-08 16:03:19',
            ),
            69 => 
            array (
                'id' => 582,
                'cc_chapter_id' => 184,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:54',
                'updated_at' => '2026-02-08 16:03:54',
            ),
            70 => 
            array (
                'id' => 583,
                'cc_chapter_id' => 185,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:12',
                'updated_at' => '2026-02-08 16:03:12',
            ),
            71 => 
            array (
                'id' => 584,
                'cc_chapter_id' => 185,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:19',
                'updated_at' => '2026-02-08 16:03:19',
            ),
            72 => 
            array (
                'id' => 585,
                'cc_chapter_id' => 185,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:54',
                'updated_at' => '2026-02-08 16:03:54',
            ),
            73 => 
            array (
                'id' => 586,
                'cc_chapter_id' => 186,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:12',
                'updated_at' => '2026-02-08 16:03:12',
            ),
            74 => 
            array (
                'id' => 587,
                'cc_chapter_id' => 186,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:19',
                'updated_at' => '2026-02-08 16:03:19',
            ),
            75 => 
            array (
                'id' => 588,
                'cc_chapter_id' => 186,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:54',
                'updated_at' => '2026-02-08 16:03:54',
            ),
            76 => 
            array (
                'id' => 589,
                'cc_chapter_id' => 187,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:12',
                'updated_at' => '2026-02-08 16:03:12',
            ),
            77 => 
            array (
                'id' => 590,
                'cc_chapter_id' => 187,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:19',
                'updated_at' => '2026-02-08 16:03:19',
            ),
            78 => 
            array (
                'id' => 591,
                'cc_chapter_id' => 187,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:54',
                'updated_at' => '2026-02-08 16:03:54',
            ),
            79 => 
            array (
                'id' => 592,
                'cc_chapter_id' => 188,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:12',
                'updated_at' => '2026-02-08 16:03:12',
            ),
            80 => 
            array (
                'id' => 593,
                'cc_chapter_id' => 188,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:19',
                'updated_at' => '2026-02-08 16:03:19',
            ),
            81 => 
            array (
                'id' => 594,
                'cc_chapter_id' => 188,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:54',
                'updated_at' => '2026-02-08 16:03:54',
            ),
            82 => 
            array (
                'id' => 595,
                'cc_chapter_id' => 189,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:12',
                'updated_at' => '2026-02-08 16:03:12',
            ),
            83 => 
            array (
                'id' => 596,
                'cc_chapter_id' => 189,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:19',
                'updated_at' => '2026-02-08 16:03:19',
            ),
            84 => 
            array (
                'id' => 597,
                'cc_chapter_id' => 189,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:54',
                'updated_at' => '2026-02-08 16:03:54',
            ),
            85 => 
            array (
                'id' => 598,
                'cc_chapter_id' => 190,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:12',
                'updated_at' => '2026-02-08 16:03:12',
            ),
            86 => 
            array (
                'id' => 599,
                'cc_chapter_id' => 190,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:19',
                'updated_at' => '2026-02-08 16:03:19',
            ),
            87 => 
            array (
                'id' => 600,
                'cc_chapter_id' => 190,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:54',
                'updated_at' => '2026-02-08 16:03:54',
            ),
            88 => 
            array (
                'id' => 601,
                'cc_chapter_id' => 191,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:12',
                'updated_at' => '2026-02-08 16:03:12',
            ),
            89 => 
            array (
                'id' => 602,
                'cc_chapter_id' => 191,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:19',
                'updated_at' => '2026-02-08 16:03:19',
            ),
            90 => 
            array (
                'id' => 603,
                'cc_chapter_id' => 191,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:54',
                'updated_at' => '2026-02-08 16:03:54',
            ),
            91 => 
            array (
                'id' => 604,
                'cc_chapter_id' => 192,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:12',
                'updated_at' => '2026-02-08 16:03:12',
            ),
            92 => 
            array (
                'id' => 605,
                'cc_chapter_id' => 192,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:19',
                'updated_at' => '2026-02-08 16:03:19',
            ),
            93 => 
            array (
                'id' => 606,
                'cc_chapter_id' => 192,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:54',
                'updated_at' => '2026-02-08 16:03:54',
            ),
            94 => 
            array (
                'id' => 607,
                'cc_chapter_id' => 193,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:03',
                'updated_at' => '2026-02-08 16:14:03',
            ),
            95 => 
            array (
                'id' => 608,
                'cc_chapter_id' => 193,
                'name' => 'قواعد',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:09',
                'updated_at' => '2026-02-08 16:14:09',
            ),
            96 => 
            array (
                'id' => 609,
                'cc_chapter_id' => 193,
                'name' => 'ترجمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:15',
                'updated_at' => '2026-02-08 16:14:15',
            ),
            97 => 
            array (
                'id' => 610,
                'cc_chapter_id' => 193,
                'name' => 'درک مطلب',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:23',
                'updated_at' => '2026-02-08 16:14:23',
            ),
            98 => 
            array (
                'id' => 611,
                'cc_chapter_id' => 193,
                'name' => 'مکالمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:32',
                'updated_at' => '2026-02-08 16:14:32',
            ),
            99 => 
            array (
                'id' => 612,
                'cc_chapter_id' => 193,
                'name' => 'مفهوم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:47',
                'updated_at' => '2026-02-08 16:14:47',
            ),
            100 => 
            array (
                'id' => 613,
                'cc_chapter_id' => 194,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:03',
                'updated_at' => '2026-02-08 16:14:03',
            ),
            101 => 
            array (
                'id' => 614,
                'cc_chapter_id' => 194,
                'name' => 'قواعد',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:09',
                'updated_at' => '2026-02-08 16:14:09',
            ),
            102 => 
            array (
                'id' => 615,
                'cc_chapter_id' => 194,
                'name' => 'ترجمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:15',
                'updated_at' => '2026-02-08 16:14:15',
            ),
            103 => 
            array (
                'id' => 616,
                'cc_chapter_id' => 194,
                'name' => 'درک مطلب',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:23',
                'updated_at' => '2026-02-08 16:14:23',
            ),
            104 => 
            array (
                'id' => 617,
                'cc_chapter_id' => 194,
                'name' => 'مکالمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:32',
                'updated_at' => '2026-02-08 16:14:32',
            ),
            105 => 
            array (
                'id' => 618,
                'cc_chapter_id' => 194,
                'name' => 'مفهوم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:47',
                'updated_at' => '2026-02-08 16:14:47',
            ),
            106 => 
            array (
                'id' => 619,
                'cc_chapter_id' => 195,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:03',
                'updated_at' => '2026-02-08 16:14:03',
            ),
            107 => 
            array (
                'id' => 620,
                'cc_chapter_id' => 195,
                'name' => 'قواعد',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:09',
                'updated_at' => '2026-02-08 16:14:09',
            ),
            108 => 
            array (
                'id' => 621,
                'cc_chapter_id' => 195,
                'name' => 'ترجمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:15',
                'updated_at' => '2026-02-08 16:14:15',
            ),
            109 => 
            array (
                'id' => 622,
                'cc_chapter_id' => 195,
                'name' => 'درک مطلب',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:23',
                'updated_at' => '2026-02-08 16:14:23',
            ),
            110 => 
            array (
                'id' => 623,
                'cc_chapter_id' => 195,
                'name' => 'مکالمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:32',
                'updated_at' => '2026-02-08 16:14:32',
            ),
            111 => 
            array (
                'id' => 624,
                'cc_chapter_id' => 195,
                'name' => 'مفهوم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:47',
                'updated_at' => '2026-02-08 16:14:47',
            ),
            112 => 
            array (
                'id' => 625,
                'cc_chapter_id' => 196,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:03',
                'updated_at' => '2026-02-08 16:14:03',
            ),
            113 => 
            array (
                'id' => 626,
                'cc_chapter_id' => 196,
                'name' => 'قواعد',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:09',
                'updated_at' => '2026-02-08 16:14:09',
            ),
            114 => 
            array (
                'id' => 627,
                'cc_chapter_id' => 196,
                'name' => 'ترجمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:15',
                'updated_at' => '2026-02-08 16:14:15',
            ),
            115 => 
            array (
                'id' => 628,
                'cc_chapter_id' => 196,
                'name' => 'درک مطلب',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:23',
                'updated_at' => '2026-02-08 16:14:23',
            ),
            116 => 
            array (
                'id' => 629,
                'cc_chapter_id' => 196,
                'name' => 'مکالمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:32',
                'updated_at' => '2026-02-08 16:14:32',
            ),
            117 => 
            array (
                'id' => 630,
                'cc_chapter_id' => 196,
                'name' => 'مفهوم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:47',
                'updated_at' => '2026-02-08 16:14:47',
            ),
            118 => 
            array (
                'id' => 631,
                'cc_chapter_id' => 197,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:03',
                'updated_at' => '2026-02-08 16:14:03',
            ),
            119 => 
            array (
                'id' => 632,
                'cc_chapter_id' => 197,
                'name' => 'قواعد',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:09',
                'updated_at' => '2026-02-08 16:14:09',
            ),
            120 => 
            array (
                'id' => 633,
                'cc_chapter_id' => 197,
                'name' => 'ترجمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:15',
                'updated_at' => '2026-02-08 16:14:15',
            ),
            121 => 
            array (
                'id' => 634,
                'cc_chapter_id' => 197,
                'name' => 'درک مطلب',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:23',
                'updated_at' => '2026-02-08 16:14:23',
            ),
            122 => 
            array (
                'id' => 635,
                'cc_chapter_id' => 197,
                'name' => 'مکالمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:32',
                'updated_at' => '2026-02-08 16:14:32',
            ),
            123 => 
            array (
                'id' => 636,
                'cc_chapter_id' => 197,
                'name' => 'مفهوم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:47',
                'updated_at' => '2026-02-08 16:14:47',
            ),
            124 => 
            array (
                'id' => 637,
                'cc_chapter_id' => 198,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:03',
                'updated_at' => '2026-02-08 16:14:03',
            ),
            125 => 
            array (
                'id' => 638,
                'cc_chapter_id' => 198,
                'name' => 'قواعد',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:09',
                'updated_at' => '2026-02-08 16:14:09',
            ),
            126 => 
            array (
                'id' => 639,
                'cc_chapter_id' => 198,
                'name' => 'ترجمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:15',
                'updated_at' => '2026-02-08 16:14:15',
            ),
            127 => 
            array (
                'id' => 640,
                'cc_chapter_id' => 198,
                'name' => 'درک مطلب',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:23',
                'updated_at' => '2026-02-08 16:14:23',
            ),
            128 => 
            array (
                'id' => 641,
                'cc_chapter_id' => 198,
                'name' => 'مکالمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:32',
                'updated_at' => '2026-02-08 16:14:32',
            ),
            129 => 
            array (
                'id' => 642,
                'cc_chapter_id' => 198,
                'name' => 'مفهوم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:47',
                'updated_at' => '2026-02-08 16:14:47',
            ),
            130 => 
            array (
                'id' => 643,
                'cc_chapter_id' => 199,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:03',
                'updated_at' => '2026-02-08 16:14:03',
            ),
            131 => 
            array (
                'id' => 644,
                'cc_chapter_id' => 199,
                'name' => 'قواعد',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:09',
                'updated_at' => '2026-02-08 16:14:09',
            ),
            132 => 
            array (
                'id' => 645,
                'cc_chapter_id' => 199,
                'name' => 'ترجمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:15',
                'updated_at' => '2026-02-08 16:14:15',
            ),
            133 => 
            array (
                'id' => 646,
                'cc_chapter_id' => 199,
                'name' => 'درک مطلب',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:23',
                'updated_at' => '2026-02-08 16:14:23',
            ),
            134 => 
            array (
                'id' => 647,
                'cc_chapter_id' => 199,
                'name' => 'مکالمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:32',
                'updated_at' => '2026-02-08 16:14:32',
            ),
            135 => 
            array (
                'id' => 648,
                'cc_chapter_id' => 199,
                'name' => 'مفهوم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:47',
                'updated_at' => '2026-02-08 16:14:47',
            ),
            136 => 
            array (
                'id' => 649,
                'cc_chapter_id' => 200,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:03',
                'updated_at' => '2026-02-08 16:14:03',
            ),
            137 => 
            array (
                'id' => 650,
                'cc_chapter_id' => 200,
                'name' => 'قواعد',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:09',
                'updated_at' => '2026-02-08 16:14:09',
            ),
            138 => 
            array (
                'id' => 651,
                'cc_chapter_id' => 200,
                'name' => 'ترجمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:15',
                'updated_at' => '2026-02-08 16:14:15',
            ),
            139 => 
            array (
                'id' => 652,
                'cc_chapter_id' => 200,
                'name' => 'درک مطلب',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:23',
                'updated_at' => '2026-02-08 16:14:23',
            ),
            140 => 
            array (
                'id' => 653,
                'cc_chapter_id' => 200,
                'name' => 'مکالمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:32',
                'updated_at' => '2026-02-08 16:14:32',
            ),
            141 => 
            array (
                'id' => 654,
                'cc_chapter_id' => 200,
                'name' => 'مفهوم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:14:47',
                'updated_at' => '2026-02-08 16:14:47',
            ),
            142 => 
            array (
                'id' => 655,
                'cc_chapter_id' => 205,
                'name' => 'جغرافیا، علمی برای زندگی بهتر‌‎',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:40:55',
                'updated_at' => '2026-02-08 16:40:55',
            ),
            143 => 
            array (
                'id' => 658,
                'cc_chapter_id' => 205,
                'name' => 'روش مطالعه در جغرافیا',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:42:05',
                'updated_at' => '2026-02-08 16:42:05',
            ),
            144 => 
            array (
                'id' => 659,
                'cc_chapter_id' => 206,
                'name' => 'موقعیت جغرافیایی ایران',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:43:02',
                'updated_at' => '2026-02-08 16:43:02',
            ),
            145 => 
            array (
                'id' => 660,
                'cc_chapter_id' => 206,
                'name' => 'ناهمواری های ایران',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:43:10',
                'updated_at' => '2026-02-08 16:43:10',
            ),
            146 => 
            array (
                'id' => 661,
                'cc_chapter_id' => 206,
                'name' => ' آب و هوای ایران',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:43:21',
                'updated_at' => '2026-02-08 16:43:21',
            ),
            147 => 
            array (
                'id' => 662,
                'cc_chapter_id' => 206,
                'name' => 'منابع آب ایران',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:43:33',
                'updated_at' => '2026-02-08 16:43:33',
            ),
            148 => 
            array (
                'id' => 663,
                'cc_chapter_id' => 207,
                'name' => 'ویژگی‌های جمعیت ایران‌‎',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:45:45',
                'updated_at' => '2026-02-08 16:45:45',
            ),
            149 => 
            array (
                'id' => 664,
                'cc_chapter_id' => 207,
                'name' => 'تقسیمات کشوری ایران',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:45:56',
                'updated_at' => '2026-02-08 16:45:56',
            ),
            150 => 
            array (
                'id' => 665,
                'cc_chapter_id' => 207,
                'name' => 'سکونتگاه‌های ایران‌‎',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:46:10',
                'updated_at' => '2026-02-08 16:46:10',
            ),
            151 => 
            array (
                'id' => 666,
                'cc_chapter_id' => 207,
                'name' => 'توان‌های اقتصادی ایران‌‎',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:48:23',
                'updated_at' => '2026-02-08 16:48:23',
            ),
            152 => 
            array (
                'id' => 667,
                'cc_chapter_id' => 208,
            'name' => 'گرامر (Grammar)',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:11:45',
                'updated_at' => '2026-02-08 17:11:45',
            ),
            153 => 
            array (
                'id' => 668,
                'cc_chapter_id' => 208,
            'name' => 'واژگان (Vocabulary)',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:11:51',
                'updated_at' => '2026-02-08 17:11:51',
            ),
            154 => 
            array (
                'id' => 669,
                'cc_chapter_id' => 208,
            'name' => 'درک مطلب (Reading comprehension)',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:11:58',
                'updated_at' => '2026-02-08 17:11:58',
            ),
            155 => 
            array (
                'id' => 670,
                'cc_chapter_id' => 208,
            'name' => 'نگارش (Writing)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:14:15',
                'updated_at' => '2026-02-08 17:14:15',
            ),
            156 => 
            array (
                'id' => 671,
                'cc_chapter_id' => 208,
            'name' => 'Listening (شنیداری)',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:14:37',
                'updated_at' => '2026-02-08 17:14:37',
            ),
            157 => 
            array (
                'id' => 672,
                'cc_chapter_id' => 209,
            'name' => 'گرامر (Grammar)',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:11:45',
                'updated_at' => '2026-02-08 17:11:45',
            ),
            158 => 
            array (
                'id' => 673,
                'cc_chapter_id' => 209,
            'name' => 'واژگان (Vocabulary)',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:11:51',
                'updated_at' => '2026-02-08 17:11:51',
            ),
            159 => 
            array (
                'id' => 674,
                'cc_chapter_id' => 209,
            'name' => 'درک مطلب (Reading comprehension)',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:11:58',
                'updated_at' => '2026-02-08 17:11:58',
            ),
            160 => 
            array (
                'id' => 675,
                'cc_chapter_id' => 209,
            'name' => 'نگارش (Writing)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:14:15',
                'updated_at' => '2026-02-08 17:14:15',
            ),
            161 => 
            array (
                'id' => 676,
                'cc_chapter_id' => 209,
            'name' => 'Listening (شنیداری)',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:14:37',
                'updated_at' => '2026-02-08 17:14:37',
            ),
            162 => 
            array (
                'id' => 677,
                'cc_chapter_id' => 210,
            'name' => 'گرامر (Grammar)',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:11:45',
                'updated_at' => '2026-02-08 17:11:45',
            ),
            163 => 
            array (
                'id' => 678,
                'cc_chapter_id' => 210,
            'name' => 'واژگان (Vocabulary)',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:11:51',
                'updated_at' => '2026-02-08 17:11:51',
            ),
            164 => 
            array (
                'id' => 679,
                'cc_chapter_id' => 210,
            'name' => 'درک مطلب (Reading comprehension)',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:11:58',
                'updated_at' => '2026-02-08 17:11:58',
            ),
            165 => 
            array (
                'id' => 680,
                'cc_chapter_id' => 210,
            'name' => 'نگارش (Writing)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:14:15',
                'updated_at' => '2026-02-08 17:14:15',
            ),
            166 => 
            array (
                'id' => 681,
                'cc_chapter_id' => 210,
            'name' => 'Listening (شنیداری)',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:14:37',
                'updated_at' => '2026-02-08 17:14:37',
            ),
            167 => 
            array (
                'id' => 682,
                'cc_chapter_id' => 211,
            'name' => 'گرامر (Grammar)',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:11:45',
                'updated_at' => '2026-02-08 17:11:45',
            ),
            168 => 
            array (
                'id' => 683,
                'cc_chapter_id' => 211,
            'name' => 'واژگان (Vocabulary)',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:11:51',
                'updated_at' => '2026-02-08 17:11:51',
            ),
            169 => 
            array (
                'id' => 684,
                'cc_chapter_id' => 211,
            'name' => 'درک مطلب (Reading comprehension)',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:11:58',
                'updated_at' => '2026-02-08 17:11:58',
            ),
            170 => 
            array (
                'id' => 685,
                'cc_chapter_id' => 211,
            'name' => 'نگارش (Writing)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:14:15',
                'updated_at' => '2026-02-08 17:14:15',
            ),
            171 => 
            array (
                'id' => 686,
                'cc_chapter_id' => 211,
            'name' => 'Listening (شنیداری)',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:14:37',
                'updated_at' => '2026-02-08 17:14:37',
            ),
            172 => 
            array (
                'id' => 687,
                'cc_chapter_id' => 212,
                'name' => 'امنیت پایدار',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:31:29',
                'updated_at' => '2026-02-08 17:31:29',
            ),
            173 => 
            array (
                'id' => 688,
                'cc_chapter_id' => 212,
                'name' => 'اقتدار دفاعی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:31:47',
                'updated_at' => '2026-02-08 17:31:47',
            ),
            174 => 
            array (
                'id' => 689,
                'cc_chapter_id' => 213,
                'name' => 'انقلاب اسلامی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:32:12',
                'updated_at' => '2026-02-08 17:32:12',
            ),
            175 => 
            array (
                'id' => 690,
                'cc_chapter_id' => 213,
                'name' => 'آشنایی با بسیج',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:32:36',
                'updated_at' => '2026-02-08 17:32:36',
            ),
            176 => 
            array (
                'id' => 691,
                'cc_chapter_id' => 213,
                'name' => 'علوم و معارف دفاع مقدس',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:32:44',
                'updated_at' => '2026-02-08 17:33:08',
            ),
            177 => 
            array (
                'id' => 692,
                'cc_chapter_id' => 213,
                'name' => 'الگوها و اسوه های پایداری و مقاومت',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:32:54',
                'updated_at' => '2026-02-08 17:33:12',
            ),
            178 => 
            array (
                'id' => 693,
                'cc_chapter_id' => 214,
                'name' => 'آشنایی با نیروهای مسلح و خدمت مقدس سربازی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:33:31',
                'updated_at' => '2026-02-08 17:33:31',
            ),
            179 => 
            array (
                'id' => 694,
                'cc_chapter_id' => 214,
                'name' => 'نظام جمع و شیوه های رزم انفرادی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:33:39',
                'updated_at' => '2026-02-08 17:33:39',
            ),
            180 => 
            array (
                'id' => 695,
                'cc_chapter_id' => 214,
                'name' => 'جنگ افزارشناسی و اصول تیراندازی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:33:47',
                'updated_at' => '2026-02-08 17:33:47',
            ),
            181 => 
            array (
                'id' => 696,
                'cc_chapter_id' => 215,
                'name' => 'شناخت و مقابله با جنگ نرم',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:34:20',
                'updated_at' => '2026-02-08 17:34:20',
            ),
            182 => 
            array (
                'id' => 697,
                'cc_chapter_id' => 215,
                'name' => 'پدافند غیرعامل',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:34:28',
                'updated_at' => '2026-02-08 17:34:28',
            ),
            183 => 
            array (
                'id' => 698,
                'cc_chapter_id' => 215,
                'name' => 'ایمنی و پیشگیری',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:34:43',
                'updated_at' => '2026-02-08 17:34:43',
            ),
            184 => 
            array (
                'id' => 699,
                'cc_chapter_id' => 215,
                'name' => 'امداد و نجات',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:34:55',
                'updated_at' => '2026-02-08 17:34:55',
            ),
            185 => 
            array (
                'id' => 700,
                'cc_chapter_id' => 216,
                'name' => 'تبدیل نمودار توابع',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            186 => 
            array (
                'id' => 701,
                'cc_chapter_id' => 216,
                'name' => 'تابع درجه سوم',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            187 => 
            array (
                'id' => 702,
                'cc_chapter_id' => 216,
                'name' => ' توابع یکنوا ',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            188 => 
            array (
                'id' => 703,
                'cc_chapter_id' => 216,
                'name' => 'بخشپذیری و تقسیم',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            189 => 
            array (
                'id' => 704,
                'cc_chapter_id' => 217,
                'name' => 'تناوب و تانژانت',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            190 => 
            array (
                'id' => 705,
                'cc_chapter_id' => 217,
                'name' => 'معادالت مثلثاتی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            191 => 
            array (
                'id' => 706,
                'cc_chapter_id' => 218,
                'name' => 'حدهای نامتناهی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            192 => 
            array (
                'id' => 707,
                'cc_chapter_id' => 218,
                'name' => 'حددر بینهایت',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            193 => 
            array (
                'id' => 708,
                'cc_chapter_id' => 219,
                'name' => 'آشنایی با مفهوم مشتق',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            194 => 
            array (
                'id' => 709,
                'cc_chapter_id' => 219,
                'name' => 'مشتق پذیری و پیوستگی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            195 => 
            array (
                'id' => 710,
                'cc_chapter_id' => 219,
                'name' => 'آهنگ متوسط تغییر',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            196 => 
            array (
                'id' => 711,
                'cc_chapter_id' => 219,
                'name' => 'آهنگ لحظهای تغییر',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            197 => 
            array (
                'id' => 712,
                'cc_chapter_id' => 220,
                'name' => 'اکسترمم های یک تابع',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            198 => 
            array (
                'id' => 713,
                'cc_chapter_id' => 220,
                'name' => 'توابع صعودی و نزولی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            199 => 
            array (
                'id' => 714,
                'cc_chapter_id' => 220,
                'name' => 'جهت تقعر نمودار یک تابع',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            200 => 
            array (
                'id' => 715,
                'cc_chapter_id' => 220,
                'name' => 'رسم نمودار تابع',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            201 => 
            array (
                'id' => 716,
                'cc_chapter_id' => 220,
                'name' => 'نقطه عطف آن',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            202 => 
            array (
                'id' => 735,
                'cc_chapter_id' => 227,
                'name' => 'شناخت حرکت',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            203 => 
            array (
                'id' => 736,
                'cc_chapter_id' => 227,
                'name' => 'حرکت با سرعت ثابت',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            204 => 
            array (
                'id' => 737,
                'cc_chapter_id' => 227,
                'name' => 'حرکت با شتاب ثابت',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            205 => 
            array (
                'id' => 738,
                'cc_chapter_id' => 227,
                'name' => 'سقوط آزاد',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            206 => 
            array (
                'id' => 739,
                'cc_chapter_id' => 228,
                'name' => 'قوانین حرکت نیوتون',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            207 => 
            array (
                'id' => 740,
                'cc_chapter_id' => 228,
                'name' => 'معرفی برخی از نیروهای خاص',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            208 => 
            array (
                'id' => 741,
                'cc_chapter_id' => 228,
                'name' => 'تکانه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            209 => 
            array (
                'id' => 742,
                'cc_chapter_id' => 228,
                'name' => 'قانون دوم نیوتن ',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            210 => 
            array (
                'id' => 743,
                'cc_chapter_id' => 228,
                'name' => 'حرکت دایره ای یکنواخت',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            211 => 
            array (
                'id' => 744,
                'cc_chapter_id' => 228,
                'name' => 'نیروی گرانشی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            212 => 
            array (
                'id' => 745,
                'cc_chapter_id' => 229,
                'name' => 'نوسان دوره ای',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            213 => 
            array (
                'id' => 746,
                'cc_chapter_id' => 229,
                'name' => 'حرکت هماهنگ ساده',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            214 => 
            array (
                'id' => 747,
                'cc_chapter_id' => 229,
                'name' => 'انرژی در حرکت هماهنگ ساده',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            215 => 
            array (
                'id' => 748,
                'cc_chapter_id' => 229,
                'name' => 'تشدید',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            216 => 
            array (
                'id' => 749,
                'cc_chapter_id' => 229,
                'name' => 'موج و انواع آن',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            217 => 
            array (
                'id' => 750,
                'cc_chapter_id' => 229,
                'name' => 'مشخصه های موج',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            218 => 
            array (
                'id' => 751,
                'cc_chapter_id' => 230,
                'name' => 'بازتاب موج',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            219 => 
            array (
                'id' => 752,
                'cc_chapter_id' => 230,
                'name' => 'شکست موج',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            220 => 
            array (
                'id' => 753,
                'cc_chapter_id' => 230,
                'name' => 'پراش موج',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            221 => 
            array (
                'id' => 754,
                'cc_chapter_id' => 230,
                'name' => 'تداخل امواج',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            222 => 
            array (
                'id' => 755,
                'cc_chapter_id' => 231,
                'name' => 'اثر فوتوالکتریک و فوتون ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            223 => 
            array (
                'id' => 756,
                'cc_chapter_id' => 231,
                'name' => 'طیف خطی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            224 => 
            array (
                'id' => 757,
                'cc_chapter_id' => 231,
                'name' => 'مدل اتم رادرفورد-بور',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            225 => 
            array (
                'id' => 758,
                'cc_chapter_id' => 231,
                'name' => 'لیزر',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            226 => 
            array (
                'id' => 759,
                'cc_chapter_id' => 232,
                'name' => 'ساختار هسته',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            227 => 
            array (
                'id' => 760,
                'cc_chapter_id' => 232,
                'name' => 'پرتوزایی طبیعی ',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            228 => 
            array (
                'id' => 761,
                'cc_chapter_id' => 232,
                'name' => 'نیمه عمر',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            229 => 
            array (
                'id' => 762,
                'cc_chapter_id' => 232,
                'name' => 'شکافت هسته ای',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            230 => 
            array (
                'id' => 763,
                'cc_chapter_id' => 232,
            'name' => 'گداخت(همجوشی) هسته ای',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            231 => 
            array (
                'id' => 764,
                'cc_chapter_id' => 237,
                'name' => 'ستایش : ملکا، ذکر تو گویم',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            232 => 
            array (
                'id' => 765,
                'cc_chapter_id' => 237,
                'name' => 'درس اول : شکر نعمت',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            233 => 
            array (
                'id' => 766,
                'cc_chapter_id' => 237,
                'name' => 'گنج حکمت : گمان',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            234 => 
            array (
                'id' => 767,
                'cc_chapter_id' => 237,
                'name' => 'درس دوم : مست و هوشیار',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            235 => 
            array (
                'id' => 768,
                'cc_chapter_id' => 237,
                'name' => 'شعرخوانی: در مکتب حقایق',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            236 => 
            array (
                'id' => 769,
                'cc_chapter_id' => 238,
                'name' => 'درس 3: آزادی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            237 => 
            array (
                'id' => 770,
                'cc_chapter_id' => 238,
                'name' => 'درس 5: دماوندیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            238 => 
            array (
                'id' => 771,
                'cc_chapter_id' => 239,
                'name' => 'درس 6: نی نامه',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            239 => 
            array (
                'id' => 772,
                'cc_chapter_id' => 239,
                'name' => 'درس 7: در حقیقت عشق',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            240 => 
            array (
                'id' => 773,
                'cc_chapter_id' => 239,
                'name' => 'شعرخوانی: صبح ستاره باران',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            241 => 
            array (
                'id' => 774,
                'cc_chapter_id' => 240,
                'name' => 'درس 8: از پاریز تا پاریس',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            242 => 
            array (
                'id' => 775,
                'cc_chapter_id' => 240,
                'name' => 'درس 9: کویر',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            243 => 
            array (
                'id' => 776,
                'cc_chapter_id' => 241,
                'name' => 'درس 10: فصل شکوفایی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            244 => 
            array (
                'id' => 777,
                'cc_chapter_id' => 241,
                'name' => 'درس 11: آن شب عزیز',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            245 => 
            array (
                'id' => 778,
                'cc_chapter_id' => 241,
                'name' => 'شعرخوانی: شکوه چشمان تو',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            246 => 
            array (
                'id' => 779,
                'cc_chapter_id' => 242,
                'name' => 'درس 12: گذر سیاوش از آتش',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            247 => 
            array (
                'id' => 780,
                'cc_chapter_id' => 242,
                'name' => 'شعرخوانی: ای میهن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            248 => 
            array (
                'id' => 781,
                'cc_chapter_id' => 243,
                'name' => 'درس 14: سی مرغ و سیمرغ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            249 => 
            array (
                'id' => 782,
                'cc_chapter_id' => 243,
                'name' => 'درس 16: کباب غاز',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            250 => 
            array (
                'id' => 783,
                'cc_chapter_id' => 244,
                'name' => 'درس 17: خنده‌ی تو',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            251 => 
            array (
                'id' => 784,
                'cc_chapter_id' => 244,
                'name' => 'درس 18: عشق جاودانی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            252 => 
            array (
                'id' => 785,
                'cc_chapter_id' => 245,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            253 => 
            array (
                'id' => 786,
                'cc_chapter_id' => 245,
                'name' => 'درک مطلب',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            254 => 
            array (
                'id' => 787,
                'cc_chapter_id' => 245,
                'name' => 'مکالمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            255 => 
            array (
                'id' => 788,
                'cc_chapter_id' => 245,
                'name' => 'قواعد',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            256 => 
            array (
                'id' => 789,
                'cc_chapter_id' => 245,
                'name' => 'ترجمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            257 => 
            array (
                'id' => 790,
                'cc_chapter_id' => 245,
                'name' => 'اعراب و تحلیل صرفی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            258 => 
            array (
                'id' => 791,
                'cc_chapter_id' => 245,
                'name' => 'مفهوم',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            259 => 
            array (
                'id' => 792,
                'cc_chapter_id' => 246,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            260 => 
            array (
                'id' => 793,
                'cc_chapter_id' => 246,
                'name' => 'درک مطلب',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            261 => 
            array (
                'id' => 794,
                'cc_chapter_id' => 246,
                'name' => 'مکالمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            262 => 
            array (
                'id' => 795,
                'cc_chapter_id' => 246,
                'name' => 'قواعد',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            263 => 
            array (
                'id' => 796,
                'cc_chapter_id' => 246,
                'name' => 'ترجمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            264 => 
            array (
                'id' => 797,
                'cc_chapter_id' => 246,
                'name' => 'اعراب و تحلیل صرفی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            265 => 
            array (
                'id' => 798,
                'cc_chapter_id' => 246,
                'name' => 'مفهوم',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            266 => 
            array (
                'id' => 799,
                'cc_chapter_id' => 247,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            267 => 
            array (
                'id' => 800,
                'cc_chapter_id' => 247,
                'name' => 'درک مطلب',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            268 => 
            array (
                'id' => 801,
                'cc_chapter_id' => 247,
                'name' => 'مکالمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            269 => 
            array (
                'id' => 802,
                'cc_chapter_id' => 247,
                'name' => 'قواعد',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            270 => 
            array (
                'id' => 803,
                'cc_chapter_id' => 247,
                'name' => 'ترجمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            271 => 
            array (
                'id' => 804,
                'cc_chapter_id' => 247,
                'name' => 'اعراب و تحلیل صرفی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            272 => 
            array (
                'id' => 805,
                'cc_chapter_id' => 247,
                'name' => 'مفهوم',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            273 => 
            array (
                'id' => 806,
                'cc_chapter_id' => 248,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            274 => 
            array (
                'id' => 807,
                'cc_chapter_id' => 248,
                'name' => 'درک مطلب',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            275 => 
            array (
                'id' => 808,
                'cc_chapter_id' => 248,
                'name' => 'مکالمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            276 => 
            array (
                'id' => 809,
                'cc_chapter_id' => 248,
                'name' => 'قواعد',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            277 => 
            array (
                'id' => 810,
                'cc_chapter_id' => 248,
                'name' => 'ترجمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            278 => 
            array (
                'id' => 811,
                'cc_chapter_id' => 248,
                'name' => 'اعراب و تحلیل صرفی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            279 => 
            array (
                'id' => 812,
                'cc_chapter_id' => 248,
                'name' => 'مفهوم',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            280 => 
            array (
                'id' => 813,
                'cc_chapter_id' => 249,
                'name' => 'آیه ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            281 => 
            array (
                'id' => 814,
                'cc_chapter_id' => 249,
                'name' => 'متن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            282 => 
            array (
                'id' => 815,
                'cc_chapter_id' => 249,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            283 => 
            array (
                'id' => 816,
                'cc_chapter_id' => 250,
                'name' => 'آیه ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            284 => 
            array (
                'id' => 817,
                'cc_chapter_id' => 250,
                'name' => 'متن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            285 => 
            array (
                'id' => 818,
                'cc_chapter_id' => 250,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            286 => 
            array (
                'id' => 819,
                'cc_chapter_id' => 251,
                'name' => 'آیه ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            287 => 
            array (
                'id' => 820,
                'cc_chapter_id' => 251,
                'name' => 'متن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            288 => 
            array (
                'id' => 821,
                'cc_chapter_id' => 251,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            289 => 
            array (
                'id' => 822,
                'cc_chapter_id' => 252,
                'name' => 'آیه ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            290 => 
            array (
                'id' => 823,
                'cc_chapter_id' => 252,
                'name' => 'متن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            291 => 
            array (
                'id' => 824,
                'cc_chapter_id' => 252,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            292 => 
            array (
                'id' => 825,
                'cc_chapter_id' => 253,
                'name' => 'آیه ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            293 => 
            array (
                'id' => 826,
                'cc_chapter_id' => 253,
                'name' => 'متن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            294 => 
            array (
                'id' => 827,
                'cc_chapter_id' => 253,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            295 => 
            array (
                'id' => 828,
                'cc_chapter_id' => 254,
                'name' => 'آیه ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            296 => 
            array (
                'id' => 829,
                'cc_chapter_id' => 254,
                'name' => 'متن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            297 => 
            array (
                'id' => 830,
                'cc_chapter_id' => 254,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            298 => 
            array (
                'id' => 831,
                'cc_chapter_id' => 255,
                'name' => 'آیه ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            299 => 
            array (
                'id' => 832,
                'cc_chapter_id' => 255,
                'name' => 'متن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            300 => 
            array (
                'id' => 833,
                'cc_chapter_id' => 255,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            301 => 
            array (
                'id' => 834,
                'cc_chapter_id' => 256,
                'name' => 'آیه ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            302 => 
            array (
                'id' => 835,
                'cc_chapter_id' => 256,
                'name' => 'متن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            303 => 
            array (
                'id' => 836,
                'cc_chapter_id' => 256,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            304 => 
            array (
                'id' => 837,
                'cc_chapter_id' => 257,
                'name' => 'آیه ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            305 => 
            array (
                'id' => 838,
                'cc_chapter_id' => 257,
                'name' => 'متن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            306 => 
            array (
                'id' => 839,
                'cc_chapter_id' => 257,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            307 => 
            array (
                'id' => 840,
                'cc_chapter_id' => 258,
                'name' => 'آیه ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            308 => 
            array (
                'id' => 841,
                'cc_chapter_id' => 258,
                'name' => 'متن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            309 => 
            array (
                'id' => 842,
                'cc_chapter_id' => 258,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            310 => 
            array (
                'id' => 843,
                'cc_chapter_id' => 259,
            'name' => 'درک مطلب (reading comprehension)',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            311 => 
            array (
                'id' => 844,
                'cc_chapter_id' => 259,
            'name' => 'گرامر (grammar)',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            312 => 
            array (
                'id' => 845,
                'cc_chapter_id' => 259,
            'name' => 'واژگان (vocabulary)',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            313 => 
            array (
                'id' => 846,
                'cc_chapter_id' => 259,
            'name' => 'نگارش (writing)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            314 => 
            array (
                'id' => 847,
                'cc_chapter_id' => 259,
            'name' => 'Listening (شنیداری)',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            315 => 
            array (
                'id' => 848,
                'cc_chapter_id' => 260,
            'name' => 'درک مطلب (reading comprehension)',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            316 => 
            array (
                'id' => 849,
                'cc_chapter_id' => 260,
            'name' => 'گرامر (grammar)',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            317 => 
            array (
                'id' => 850,
                'cc_chapter_id' => 260,
            'name' => 'واژگان (vocabulary)',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            318 => 
            array (
                'id' => 851,
                'cc_chapter_id' => 260,
            'name' => 'نگارش (writing)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            319 => 
            array (
                'id' => 852,
                'cc_chapter_id' => 260,
            'name' => 'Listening (شنیداری)',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            320 => 
            array (
                'id' => 853,
                'cc_chapter_id' => 261,
            'name' => 'درک مطلب (reading comprehension)',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            321 => 
            array (
                'id' => 854,
                'cc_chapter_id' => 261,
            'name' => 'گرامر (grammar)',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            322 => 
            array (
                'id' => 855,
                'cc_chapter_id' => 261,
            'name' => 'واژگان (vocabulary)',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            323 => 
            array (
                'id' => 856,
                'cc_chapter_id' => 261,
            'name' => 'نگارش (writing)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            324 => 
            array (
                'id' => 857,
                'cc_chapter_id' => 261,
            'name' => 'Listening (شنیداری)',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            325 => 
            array (
                'id' => 858,
                'cc_chapter_id' => 262,
                'name' => 'سلامت چیست؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            326 => 
            array (
                'id' => 859,
                'cc_chapter_id' => 262,
                'name' => 'سبک زندگی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            327 => 
            array (
                'id' => 860,
                'cc_chapter_id' => 263,
                'name' => 'برنامۀ غذایی سالم',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            328 => 
            array (
                'id' => 861,
                'cc_chapter_id' => 263,
                'name' => 'کنترل وزن و تناسب اندام',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            329 => 
            array (
                'id' => 862,
                'cc_chapter_id' => 263,
                'name' => ' بهداشت و ایمنی مواد غذایی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            330 => 
            array (
                'id' => 863,
                'cc_chapter_id' => 264,
                'name' => 'بیماری‎های غیر واگیر',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            331 => 
            array (
                'id' => 864,
                'cc_chapter_id' => 264,
                'name' => 'بیماری‎های واگیر',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            332 => 
            array (
                'id' => 865,
                'cc_chapter_id' => 265,
                'name' => 'بهداشت فردی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            333 => 
            array (
                'id' => 866,
                'cc_chapter_id' => 265,
                'name' => 'بهداشت ازدواج و باروری',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            334 => 
            array (
                'id' => 867,
                'cc_chapter_id' => 265,
                'name' => 'بهداشت روان',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            335 => 
            array (
                'id' => 868,
                'cc_chapter_id' => 266,
                'name' => 'مصرف دخانیات و الکل',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            336 => 
            array (
                'id' => 869,
                'cc_chapter_id' => 266,
                'name' => 'اعتیاد به مواد مخدر و عوارض آن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            337 => 
            array (
                'id' => 870,
                'cc_chapter_id' => 267,
                'name' => 'پیشگیری از اختلالات اسکلتی - عضلانی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            338 => 
            array (
                'id' => 871,
                'cc_chapter_id' => 267,
                'name' => 'پیشگیری از حوادث خانگی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            339 => 
            array (
                'id' => 872,
                'cc_chapter_id' => 268,
                'name' => 'کنش ما انسان‌ها چه ویژگی‌هایی دارد؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            340 => 
            array (
                'id' => 873,
                'cc_chapter_id' => 268,
                'name' => 'کنش ما چه آثار و پیامد‌هایی دارد؟',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            341 => 
            array (
                'id' => 874,
                'cc_chapter_id' => 269,
                'name' => 'کنش اجتماعی چیست؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            342 => 
            array (
                'id' => 875,
                'cc_chapter_id' => 269,
                'name' => 'پدیده‌های اجتماعی کدام‌اند و چگونه شکل می‌گیرند؟',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            343 => 
            array (
                'id' => 876,
                'cc_chapter_id' => 270,
                'name' => 'از جامعه و فرهنگ چه تصوری دارید؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            344 => 
            array (
                'id' => 877,
                'cc_chapter_id' => 270,
            'name' => 'جامعه و فرهنگ (جهان اجتماعی) چه الزاماتی دارد؟',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            345 => 
            array (
                'id' => 878,
                'cc_chapter_id' => 271,
                'name' => 'منظور از فرهنگ آرمانی و فرهنگ واقعی چیست؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            346 => 
            array (
                'id' => 879,
                'cc_chapter_id' => 271,
                'name' => 'منظور از فرهنگ حق و فرهنگ باطل چیست؟',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            347 => 
            array (
                'id' => 880,
                'cc_chapter_id' => 272,
                'name' => 'در مورد هویت چه می‌دانید؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            348 => 
            array (
                'id' => 881,
                'cc_chapter_id' => 272,
                'name' => 'هویت فردی و اجتماعی چه نسبتی با هم دارند؟',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            349 => 
            array (
                'id' => 882,
                'cc_chapter_id' => 272,
                'name' => 'خودآگاهی یا ناخودآگاهی؟',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            350 => 
            array (
                'id' => 883,
                'cc_chapter_id' => 273,
                'name' => 'هویت اجتماعی چگونه شکل می‌گیرد و تداوم می‌یابد؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            351 => 
            array (
                'id' => 884,
                'cc_chapter_id' => 273,
                'name' => 'هویت اجتماعی چگونه تغییر می‌کند؟',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            352 => 
            array (
                'id' => 885,
                'cc_chapter_id' => 274,
                'name' => 'تحولات هویتی جامعه چگونه است؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            353 => 
            array (
                'id' => 886,
                'cc_chapter_id' => 274,
                'name' => 'ازخودبیگانگی فرهنگی چیست؟',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            354 => 
            array (
                'id' => 887,
                'cc_chapter_id' => 275,
                'name' => 'هویت ایرانی در گذر زمان چه تحولاتی به خود دیده است؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            355 => 
            array (
                'id' => 888,
                'cc_chapter_id' => 275,
                'name' => 'انقلاب اسلامی چه تاثیری بر هویت ایرانی داشته است؟',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            356 => 
            array (
                'id' => 889,
                'cc_chapter_id' => 276,
                'name' => 'چه رابطه‌ای میان جامعه و نظام سیاسی آن وجود دارد؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            357 => 
            array (
                'id' => 890,
                'cc_chapter_id' => 276,
                'name' => 'نظام سیاسی و انواع آن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            358 => 
            array (
                'id' => 891,
                'cc_chapter_id' => 276,
                'name' => 'لیبرال دموکراسی و جمهوری اسلامی چه تفاوت‌هایی با هم دارند؟',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            359 => 
            array (
                'id' => 892,
                'cc_chapter_id' => 277,
                'name' => 'جمعیت هر جامعه چه رابطه‌ای با هویت آن دارد؟',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            360 => 
            array (
                'id' => 893,
                'cc_chapter_id' => 277,
                'name' => 'اقتصاد هر جامعه چه رابطه‌ای با هویت آن دارد؟',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            361 => 
            array (
                'id' => 894,
                'cc_chapter_id' => 278,
                'name' => 'مجموعه های متناهی و نامتناهی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            362 => 
            array (
                'id' => 895,
                'cc_chapter_id' => 278,
                'name' => 'متمم یک مجموعه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            363 => 
            array (
                'id' => 896,
                'cc_chapter_id' => 278,
                'name' => 'الگو و دنباله',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            364 => 
            array (
                'id' => 897,
                'cc_chapter_id' => 278,
                'name' => 'دنباله های حسابی و هندسی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            365 => 
            array (
                'id' => 898,
                'cc_chapter_id' => 279,
                'name' => 'نسبت های مثلثاتی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            366 => 
            array (
                'id' => 899,
                'cc_chapter_id' => 279,
                'name' => 'مثلث های متشابه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            367 => 
            array (
                'id' => 900,
                'cc_chapter_id' => 279,
            'name' => 'نسبت های مثلثاتی(تعریف و خواص)',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            368 => 
            array (
                'id' => 901,
                'cc_chapter_id' => 279,
                'name' => 'مساحت مثلث',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            369 => 
            array (
                'id' => 902,
                'cc_chapter_id' => 280,
                'name' => 'ریشه و توان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            370 => 
            array (
                'id' => 903,
                'cc_chapter_id' => 280,
                'name' => 'ریشه n ام',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            371 => 
            array (
                'id' => 904,
                'cc_chapter_id' => 280,
                'name' => 'توان های گویا',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            372 => 
            array (
                'id' => 905,
                'cc_chapter_id' => 280,
                'name' => 'عبارت های جبری',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            373 => 
            array (
                'id' => 906,
                'cc_chapter_id' => 281,
                'name' => 'معادله درجه دوم و روش های مختلف حل آن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            374 => 
            array (
                'id' => 907,
                'cc_chapter_id' => 281,
                'name' => 'سهمی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            375 => 
            array (
                'id' => 908,
                'cc_chapter_id' => 281,
                'name' => 'تعیین علامت',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            376 => 
            array (
                'id' => 909,
                'cc_chapter_id' => 282,
                'name' => 'مفهوم تابع و بازنمایی های آن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            377 => 
            array (
                'id' => 910,
                'cc_chapter_id' => 282,
                'name' => 'دامنه و برد توابع',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            378 => 
            array (
                'id' => 911,
                'cc_chapter_id' => 282,
                'name' => 'انواع توابع',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            379 => 
            array (
                'id' => 912,
                'cc_chapter_id' => 283,
                'name' => 'شمارش',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            380 => 
            array (
                'id' => 913,
                'cc_chapter_id' => 283,
                'name' => 'جایگشت',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            381 => 
            array (
                'id' => 914,
                'cc_chapter_id' => 283,
                'name' => 'ترکیب',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            382 => 
            array (
                'id' => 915,
                'cc_chapter_id' => 284,
                'name' => 'احتمال یا اندازه گیری شانس',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            383 => 
            array (
                'id' => 916,
                'cc_chapter_id' => 284,
                'name' => 'مقدمه ای بر علم آمار، جامعه و نمونه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            384 => 
            array (
                'id' => 917,
                'cc_chapter_id' => 284,
                'name' => 'متغیر و انواع آن',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            385 => 
            array (
                'id' => 928,
                'cc_chapter_id' => 289,
                'name' => 'پیدایش عنصرها',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            386 => 
            array (
                'id' => 929,
                'cc_chapter_id' => 289,
                'name' => 'طبقه‌بندی عنصرها و جرم اتمی آن‌ها',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            387 => 
            array (
                'id' => 930,
                'cc_chapter_id' => 289,
                'name' => 'شمارش ذره‌ها از روی جرم آن‌ها',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            388 => 
            array (
                'id' => 931,
                'cc_chapter_id' => 289,
                'name' => 'نور، کلید شناخت جهان',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            389 => 
            array (
                'id' => 932,
                'cc_chapter_id' => 289,
                'name' => 'لایه‌ها و زیرلایه‌های الکترونی و آرایش الکترونی اتم',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            390 => 
            array (
                'id' => 933,
                'cc_chapter_id' => 289,
                'name' => 'ساختار اتم و رفتار آن',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            391 => 
            array (
                'id' => 934,
                'cc_chapter_id' => 290,
                'name' => 'هواکره و ویژگی‌های آن:',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            392 => 
            array (
                'id' => 935,
                'cc_chapter_id' => 290,
                'name' => 'ترکیب اکسیژن با فلزها و نافلز ها',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            393 => 
            array (
                'id' => 936,
                'cc_chapter_id' => 290,
                'name' => 'ساختار لوویس مولکول‌ها',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            394 => 
            array (
                'id' => 937,
                'cc_chapter_id' => 290,
                'name' => 'واکنش‌های شیمیایی و قانون پایستگی جرم',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            395 => 
            array (
                'id' => 938,
                'cc_chapter_id' => 290,
                'name' => 'چه بر سر هواکره می آوریم؟',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            396 => 
            array (
                'id' => 939,
                'cc_chapter_id' => 290,
                'name' => 'اوزون، دگرشکلی از اکسیژن در هواکره',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            397 => 
            array (
                'id' => 940,
                'cc_chapter_id' => 290,
                'name' => 'رفتار گازها',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            398 => 
            array (
                'id' => 941,
                'cc_chapter_id' => 290,
                'name' => 'استوکیومتری واکنش ها',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            399 => 
            array (
                'id' => 942,
                'cc_chapter_id' => 290,
                'name' => 'تولید آمونیاک به روش هابر',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            400 => 
            array (
                'id' => 943,
                'cc_chapter_id' => 291,
                'name' => 'منابع آب در زمین',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            401 => 
            array (
                'id' => 944,
                'cc_chapter_id' => 291,
                'name' => 'ترکیب‌های یونی چندتایی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            402 => 
            array (
                'id' => 945,
                'cc_chapter_id' => 291,
                'name' => 'محلول و مقدار حل‌شونده‌ها',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            403 => 
            array (
                'id' => 946,
                'cc_chapter_id' => 291,
                'name' => 'آیا نمک ها به یک اندازه در آب حل می شوند؟',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            404 => 
            array (
                'id' => 947,
                'cc_chapter_id' => 291,
                'name' => 'رفتار آب و دیگر مولکول ها در میدان الکتریکی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            405 => 
            array (
                'id' => 948,
                'cc_chapter_id' => 291,
                'name' => 'آب و دیگر حلال ها',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            406 => 
            array (
                'id' => 949,
                'cc_chapter_id' => 291,
                'name' => 'انحلال گاز ها در آب',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            407 => 
            array (
                'id' => 950,
                'cc_chapter_id' => 291,
                'name' => 'ردّ پای آب در زندگی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            408 => 
            array (
                'id' => 951,
                'cc_chapter_id' => 292,
                'name' => 'فیزیک، دانش بنیادی - مدل‌سازی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            409 => 
            array (
                'id' => 952,
                'cc_chapter_id' => 292,
                'name' => 'اندازه‌گیری و کمیت‌های فیزیکی - دستگاه بین‌المللی یکاها',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            410 => 
            array (
                'id' => 953,
                'cc_chapter_id' => 292,
                'name' => 'تبدیل یکاها',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            411 => 
            array (
                'id' => 954,
                'cc_chapter_id' => 292,
                'name' => 'اندازه‌گیری و دقت وسیله‌های اندازه‌گیری',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            412 => 
            array (
                'id' => 955,
                'cc_chapter_id' => 292,
                'name' => 'چگالی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            413 => 
            array (
                'id' => 956,
                'cc_chapter_id' => 293,
                'name' => 'حالت‌های ماده و نیروهای بین مولکولی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            414 => 
            array (
                'id' => 957,
                'cc_chapter_id' => 293,
                'name' => 'فشار در جامدات',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            415 => 
            array (
                'id' => 958,
                'cc_chapter_id' => 293,
                'name' => 'فشار در شاره‌ها',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            416 => 
            array (
                'id' => 959,
                'cc_chapter_id' => 293,
                'name' => 'شناوری',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            417 => 
            array (
                'id' => 960,
                'cc_chapter_id' => 293,
                'name' => 'شاره در حال حرکت و اصل برنولی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            418 => 
            array (
                'id' => 961,
                'cc_chapter_id' => 294,
                'name' => 'انرژی جنبشی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            419 => 
            array (
                'id' => 962,
                'cc_chapter_id' => 294,
                'name' => 'کار انجام شده توسط نیروی ثابت',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            420 => 
            array (
                'id' => 963,
                'cc_chapter_id' => 294,
                'name' => 'قضیه کار و انرژی جنبشی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            421 => 
            array (
                'id' => 964,
                'cc_chapter_id' => 294,
                'name' => 'کار و انرژی پتانسیل گرانشی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            422 => 
            array (
                'id' => 965,
                'cc_chapter_id' => 294,
                'name' => 'پایستگی انرژی مکانیکی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            423 => 
            array (
                'id' => 966,
                'cc_chapter_id' => 294,
                'name' => 'کار و انرژی درونی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            424 => 
            array (
                'id' => 967,
                'cc_chapter_id' => 294,
                'name' => ' توان و بازده',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            425 => 
            array (
                'id' => 968,
                'cc_chapter_id' => 294,
                'name' => 'دما و گرما',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            426 => 
            array (
                'id' => 969,
                'cc_chapter_id' => 295,
                'name' => 'دما و دماسنجی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            427 => 
            array (
                'id' => 970,
                'cc_chapter_id' => 295,
                'name' => 'انبساط گرمایی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            428 => 
            array (
                'id' => 971,
                'cc_chapter_id' => 295,
                'name' => 'گرما',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            429 => 
            array (
                'id' => 972,
                'cc_chapter_id' => 295,
                'name' => 'تغییر حالت‌های ماده',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            430 => 
            array (
                'id' => 973,
                'cc_chapter_id' => 295,
                'name' => 'روش‌های انتقال گرما',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            431 => 
            array (
                'id' => 974,
                'cc_chapter_id' => 295,
                'name' => 'قوانین گازها',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            432 => 
            array (
                'id' => 981,
                'cc_chapter_id' => 297,
                'name' => 'چشمه',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            433 => 
            array (
                'id' => 982,
                'cc_chapter_id' => 297,
                'name' => 'از آموختن، ننگ مدار',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            434 => 
            array (
                'id' => 983,
                'cc_chapter_id' => 298,
                'name' => 'پاسداری از حقیقت',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            435 => 
            array (
                'id' => 984,
                'cc_chapter_id' => 298,
                'name' => ' بیداد ظالمان',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            436 => 
            array (
                'id' => 985,
                'cc_chapter_id' => 298,
                'name' => 'همای رحمت',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            437 => 
            array (
                'id' => 986,
                'cc_chapter_id' => 299,
                'name' => 'مهر و وفا',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            438 => 
            array (
                'id' => 987,
                'cc_chapter_id' => 299,
                'name' => 'جمال و کمال',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            439 => 
            array (
                'id' => 988,
                'cc_chapter_id' => 299,
                'name' => 'بوی گل و ریحان‌ها',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            440 => 
            array (
                'id' => 989,
                'cc_chapter_id' => 300,
                'name' => 'سفر به بصره',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            441 => 
            array (
                'id' => 990,
                'cc_chapter_id' => 300,
                'name' => 'کلاس نقاشی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            442 => 
            array (
                'id' => 991,
                'cc_chapter_id' => 301,
                'name' => ' دریادلان صف‌شکن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            443 => 
            array (
                'id' => 992,
                'cc_chapter_id' => 301,
                'name' => 'خاک آزادگان',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            444 => 
            array (
                'id' => 993,
                'cc_chapter_id' => 302,
                'name' => 'رستم و اشکبوس',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            445 => 
            array (
                'id' => 994,
                'cc_chapter_id' => 302,
                'name' => ' گرد آفرید',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            446 => 
            array (
                'id' => 995,
                'cc_chapter_id' => 302,
                'name' => 'دلیران و مردان ایران زمین',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            447 => 
            array (
                'id' => 996,
                'cc_chapter_id' => 303,
                'name' => 'طوطی و بقال',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            448 => 
            array (
                'id' => 997,
                'cc_chapter_id' => 303,
                'name' => 'خسرو',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            449 => 
            array (
                'id' => 998,
                'cc_chapter_id' => 304,
                'name' => 'سپیده دم',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            450 => 
            array (
                'id' => 999,
                'cc_chapter_id' => 304,
                'name' => 'عظمت نگاه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            451 => 
            array (
                'id' => 1000,
                'cc_chapter_id' => 305,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            452 => 
            array (
                'id' => 1001,
                'cc_chapter_id' => 305,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            453 => 
            array (
                'id' => 1002,
                'cc_chapter_id' => 305,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            454 => 
            array (
                'id' => 1003,
                'cc_chapter_id' => 306,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            455 => 
            array (
                'id' => 1004,
                'cc_chapter_id' => 306,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            456 => 
            array (
                'id' => 1005,
                'cc_chapter_id' => 306,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            457 => 
            array (
                'id' => 1006,
                'cc_chapter_id' => 307,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            458 => 
            array (
                'id' => 1007,
                'cc_chapter_id' => 307,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            459 => 
            array (
                'id' => 1008,
                'cc_chapter_id' => 307,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            460 => 
            array (
                'id' => 1009,
                'cc_chapter_id' => 308,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            461 => 
            array (
                'id' => 1010,
                'cc_chapter_id' => 308,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            462 => 
            array (
                'id' => 1011,
                'cc_chapter_id' => 308,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            463 => 
            array (
                'id' => 1012,
                'cc_chapter_id' => 309,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            464 => 
            array (
                'id' => 1013,
                'cc_chapter_id' => 309,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            465 => 
            array (
                'id' => 1014,
                'cc_chapter_id' => 309,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            466 => 
            array (
                'id' => 1015,
                'cc_chapter_id' => 310,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            467 => 
            array (
                'id' => 1016,
                'cc_chapter_id' => 310,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            468 => 
            array (
                'id' => 1017,
                'cc_chapter_id' => 310,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            469 => 
            array (
                'id' => 1018,
                'cc_chapter_id' => 311,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            470 => 
            array (
                'id' => 1019,
                'cc_chapter_id' => 311,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            471 => 
            array (
                'id' => 1020,
                'cc_chapter_id' => 311,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            472 => 
            array (
                'id' => 1021,
                'cc_chapter_id' => 312,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            473 => 
            array (
                'id' => 1022,
                'cc_chapter_id' => 312,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            474 => 
            array (
                'id' => 1023,
                'cc_chapter_id' => 312,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            475 => 
            array (
                'id' => 1024,
                'cc_chapter_id' => 313,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            476 => 
            array (
                'id' => 1025,
                'cc_chapter_id' => 313,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            477 => 
            array (
                'id' => 1026,
                'cc_chapter_id' => 313,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            478 => 
            array (
                'id' => 1027,
                'cc_chapter_id' => 314,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            479 => 
            array (
                'id' => 1028,
                'cc_chapter_id' => 314,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            480 => 
            array (
                'id' => 1029,
                'cc_chapter_id' => 314,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            481 => 
            array (
                'id' => 1030,
                'cc_chapter_id' => 315,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            482 => 
            array (
                'id' => 1031,
                'cc_chapter_id' => 315,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            483 => 
            array (
                'id' => 1032,
                'cc_chapter_id' => 315,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            484 => 
            array (
                'id' => 1033,
                'cc_chapter_id' => 316,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            485 => 
            array (
                'id' => 1034,
                'cc_chapter_id' => 316,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            486 => 
            array (
                'id' => 1035,
                'cc_chapter_id' => 316,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            487 => 
            array (
                'id' => 1036,
                'cc_chapter_id' => 317,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            488 => 
            array (
                'id' => 1037,
                'cc_chapter_id' => 317,
                'name' => 'قواعد',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            489 => 
            array (
                'id' => 1038,
                'cc_chapter_id' => 317,
                'name' => 'ترجمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            490 => 
            array (
                'id' => 1039,
                'cc_chapter_id' => 317,
                'name' => 'درک مطلب',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            491 => 
            array (
                'id' => 1040,
                'cc_chapter_id' => 317,
                'name' => 'مکالمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            492 => 
            array (
                'id' => 1041,
                'cc_chapter_id' => 317,
                'name' => 'مفهوم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            493 => 
            array (
                'id' => 1042,
                'cc_chapter_id' => 318,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            494 => 
            array (
                'id' => 1043,
                'cc_chapter_id' => 318,
                'name' => 'قواعد',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            495 => 
            array (
                'id' => 1044,
                'cc_chapter_id' => 318,
                'name' => 'ترجمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            496 => 
            array (
                'id' => 1045,
                'cc_chapter_id' => 318,
                'name' => 'درک مطلب',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            497 => 
            array (
                'id' => 1046,
                'cc_chapter_id' => 318,
                'name' => 'مکالمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            498 => 
            array (
                'id' => 1047,
                'cc_chapter_id' => 318,
                'name' => 'مفهوم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            499 => 
            array (
                'id' => 1048,
                'cc_chapter_id' => 319,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
        ));
        \DB::table('cc_topics')->insert(array (
            0 => 
            array (
                'id' => 1049,
                'cc_chapter_id' => 319,
                'name' => 'قواعد',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            1 => 
            array (
                'id' => 1050,
                'cc_chapter_id' => 319,
                'name' => 'ترجمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            2 => 
            array (
                'id' => 1051,
                'cc_chapter_id' => 319,
                'name' => 'درک مطلب',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            3 => 
            array (
                'id' => 1052,
                'cc_chapter_id' => 319,
                'name' => 'مکالمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            4 => 
            array (
                'id' => 1053,
                'cc_chapter_id' => 319,
                'name' => 'مفهوم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            5 => 
            array (
                'id' => 1054,
                'cc_chapter_id' => 320,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            6 => 
            array (
                'id' => 1055,
                'cc_chapter_id' => 320,
                'name' => 'قواعد',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            7 => 
            array (
                'id' => 1056,
                'cc_chapter_id' => 320,
                'name' => 'ترجمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            8 => 
            array (
                'id' => 1057,
                'cc_chapter_id' => 320,
                'name' => 'درک مطلب',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            9 => 
            array (
                'id' => 1058,
                'cc_chapter_id' => 320,
                'name' => 'مکالمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            10 => 
            array (
                'id' => 1059,
                'cc_chapter_id' => 320,
                'name' => 'مفهوم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            11 => 
            array (
                'id' => 1060,
                'cc_chapter_id' => 321,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            12 => 
            array (
                'id' => 1061,
                'cc_chapter_id' => 321,
                'name' => 'قواعد',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            13 => 
            array (
                'id' => 1062,
                'cc_chapter_id' => 321,
                'name' => 'ترجمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            14 => 
            array (
                'id' => 1063,
                'cc_chapter_id' => 321,
                'name' => 'درک مطلب',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            15 => 
            array (
                'id' => 1064,
                'cc_chapter_id' => 321,
                'name' => 'مکالمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            16 => 
            array (
                'id' => 1065,
                'cc_chapter_id' => 321,
                'name' => 'مفهوم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            17 => 
            array (
                'id' => 1066,
                'cc_chapter_id' => 322,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            18 => 
            array (
                'id' => 1067,
                'cc_chapter_id' => 322,
                'name' => 'قواعد',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            19 => 
            array (
                'id' => 1068,
                'cc_chapter_id' => 322,
                'name' => 'ترجمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            20 => 
            array (
                'id' => 1069,
                'cc_chapter_id' => 322,
                'name' => 'درک مطلب',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            21 => 
            array (
                'id' => 1070,
                'cc_chapter_id' => 322,
                'name' => 'مکالمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            22 => 
            array (
                'id' => 1071,
                'cc_chapter_id' => 322,
                'name' => 'مفهوم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            23 => 
            array (
                'id' => 1072,
                'cc_chapter_id' => 323,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            24 => 
            array (
                'id' => 1073,
                'cc_chapter_id' => 323,
                'name' => 'قواعد',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            25 => 
            array (
                'id' => 1074,
                'cc_chapter_id' => 323,
                'name' => 'ترجمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            26 => 
            array (
                'id' => 1075,
                'cc_chapter_id' => 323,
                'name' => 'درک مطلب',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            27 => 
            array (
                'id' => 1076,
                'cc_chapter_id' => 323,
                'name' => 'مکالمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            28 => 
            array (
                'id' => 1077,
                'cc_chapter_id' => 323,
                'name' => 'مفهوم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            29 => 
            array (
                'id' => 1078,
                'cc_chapter_id' => 324,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            30 => 
            array (
                'id' => 1079,
                'cc_chapter_id' => 324,
                'name' => 'قواعد',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            31 => 
            array (
                'id' => 1080,
                'cc_chapter_id' => 324,
                'name' => 'ترجمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            32 => 
            array (
                'id' => 1081,
                'cc_chapter_id' => 324,
                'name' => 'درک مطلب',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            33 => 
            array (
                'id' => 1082,
                'cc_chapter_id' => 324,
                'name' => 'مکالمه',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            34 => 
            array (
                'id' => 1083,
                'cc_chapter_id' => 324,
                'name' => 'مفهوم',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            35 => 
            array (
                'id' => 1084,
                'cc_chapter_id' => 325,
                'name' => 'جغرافیا، علمی برای زندگی بهتر‌‎',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            36 => 
            array (
                'id' => 1085,
                'cc_chapter_id' => 325,
                'name' => 'روش مطالعه در جغرافیا',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            37 => 
            array (
                'id' => 1086,
                'cc_chapter_id' => 326,
                'name' => 'موقعیت جغرافیایی ایران',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            38 => 
            array (
                'id' => 1087,
                'cc_chapter_id' => 326,
                'name' => 'ناهمواری های ایران',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            39 => 
            array (
                'id' => 1088,
                'cc_chapter_id' => 326,
                'name' => ' آب و هوای ایران',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            40 => 
            array (
                'id' => 1089,
                'cc_chapter_id' => 326,
                'name' => 'منابع آب ایران',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            41 => 
            array (
                'id' => 1090,
                'cc_chapter_id' => 327,
                'name' => 'ویژگی‌های جمعیت ایران‌‎',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            42 => 
            array (
                'id' => 1091,
                'cc_chapter_id' => 327,
                'name' => 'تقسیمات کشوری ایران',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            43 => 
            array (
                'id' => 1092,
                'cc_chapter_id' => 327,
                'name' => 'سکونتگاه‌های ایران‌‎',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            44 => 
            array (
                'id' => 1093,
                'cc_chapter_id' => 327,
                'name' => 'توان‌های اقتصادی ایران‌‎',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            45 => 
            array (
                'id' => 1094,
                'cc_chapter_id' => 328,
            'name' => 'گرامر (Grammar)',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            46 => 
            array (
                'id' => 1095,
                'cc_chapter_id' => 328,
            'name' => 'واژگان (Vocabulary)',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            47 => 
            array (
                'id' => 1096,
                'cc_chapter_id' => 328,
            'name' => 'درک مطلب (Reading comprehension)',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            48 => 
            array (
                'id' => 1097,
                'cc_chapter_id' => 328,
            'name' => 'نگارش (Writing)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            49 => 
            array (
                'id' => 1098,
                'cc_chapter_id' => 328,
            'name' => 'Listening (شنیداری)',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            50 => 
            array (
                'id' => 1099,
                'cc_chapter_id' => 329,
            'name' => 'گرامر (Grammar)',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            51 => 
            array (
                'id' => 1100,
                'cc_chapter_id' => 329,
            'name' => 'واژگان (Vocabulary)',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            52 => 
            array (
                'id' => 1101,
                'cc_chapter_id' => 329,
            'name' => 'درک مطلب (Reading comprehension)',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            53 => 
            array (
                'id' => 1102,
                'cc_chapter_id' => 329,
            'name' => 'نگارش (Writing)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            54 => 
            array (
                'id' => 1103,
                'cc_chapter_id' => 329,
            'name' => 'Listening (شنیداری)',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            55 => 
            array (
                'id' => 1104,
                'cc_chapter_id' => 330,
            'name' => 'گرامر (Grammar)',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            56 => 
            array (
                'id' => 1105,
                'cc_chapter_id' => 330,
            'name' => 'واژگان (Vocabulary)',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            57 => 
            array (
                'id' => 1106,
                'cc_chapter_id' => 330,
            'name' => 'درک مطلب (Reading comprehension)',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            58 => 
            array (
                'id' => 1107,
                'cc_chapter_id' => 330,
            'name' => 'نگارش (Writing)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            59 => 
            array (
                'id' => 1108,
                'cc_chapter_id' => 330,
            'name' => 'Listening (شنیداری)',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            60 => 
            array (
                'id' => 1109,
                'cc_chapter_id' => 331,
            'name' => 'گرامر (Grammar)',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            61 => 
            array (
                'id' => 1110,
                'cc_chapter_id' => 331,
            'name' => 'واژگان (Vocabulary)',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            62 => 
            array (
                'id' => 1111,
                'cc_chapter_id' => 331,
            'name' => 'درک مطلب (Reading comprehension)',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            63 => 
            array (
                'id' => 1112,
                'cc_chapter_id' => 331,
            'name' => 'نگارش (Writing)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            64 => 
            array (
                'id' => 1113,
                'cc_chapter_id' => 331,
            'name' => 'Listening (شنیداری)',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            65 => 
            array (
                'id' => 1114,
                'cc_chapter_id' => 332,
                'name' => 'امنیت پایدار',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            66 => 
            array (
                'id' => 1115,
                'cc_chapter_id' => 332,
                'name' => 'اقتدار دفاعی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            67 => 
            array (
                'id' => 1116,
                'cc_chapter_id' => 333,
                'name' => 'انقلاب اسلامی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            68 => 
            array (
                'id' => 1117,
                'cc_chapter_id' => 333,
                'name' => 'آشنایی با بسیج',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            69 => 
            array (
                'id' => 1118,
                'cc_chapter_id' => 333,
                'name' => 'علوم و معارف دفاع مقدس',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            70 => 
            array (
                'id' => 1119,
                'cc_chapter_id' => 333,
                'name' => 'الگوها و اسوه های پایداری و مقاومت',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            71 => 
            array (
                'id' => 1120,
                'cc_chapter_id' => 334,
                'name' => 'آشنایی با نیروهای مسلح و خدمت مقدس سربازی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            72 => 
            array (
                'id' => 1121,
                'cc_chapter_id' => 334,
                'name' => 'نظام جمع و شیوه های رزم انفرادی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            73 => 
            array (
                'id' => 1122,
                'cc_chapter_id' => 334,
                'name' => 'جنگ افزارشناسی و اصول تیراندازی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            74 => 
            array (
                'id' => 1123,
                'cc_chapter_id' => 335,
                'name' => 'شناخت و مقابله با جنگ نرم',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            75 => 
            array (
                'id' => 1124,
                'cc_chapter_id' => 335,
                'name' => 'پدافند غیرعامل',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            76 => 
            array (
                'id' => 1125,
                'cc_chapter_id' => 335,
                'name' => 'ایمنی و پیشگیری',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            77 => 
            array (
                'id' => 1126,
                'cc_chapter_id' => 335,
                'name' => 'امداد و نجات',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            78 => 
            array (
                'id' => 1127,
                'cc_chapter_id' => 336,
                'name' => ' مجموع جملات دنباله های حسابی و هندسی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            79 => 
            array (
                'id' => 1128,
                'cc_chapter_id' => 336,
                'name' => 'معادلات درجه دوم',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            80 => 
            array (
                'id' => 1129,
                'cc_chapter_id' => 336,
                'name' => 'معادلات گویا و گنگ',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            81 => 
            array (
                'id' => 1130,
                'cc_chapter_id' => 336,
                'name' => 'قدر مطلق و ویژگی های آن',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            82 => 
            array (
                'id' => 1131,
                'cc_chapter_id' => 336,
                'name' => 'آشنایی با هندسه تحلیلی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            83 => 
            array (
                'id' => 1132,
                'cc_chapter_id' => 337,
                'name' => 'آشنایی بیشتر با تابع',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            84 => 
            array (
                'id' => 1133,
                'cc_chapter_id' => 337,
                'name' => 'انواع توابع',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            85 => 
            array (
                'id' => 1134,
                'cc_chapter_id' => 337,
                'name' => 'وارون تابع',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            86 => 
            array (
                'id' => 1135,
                'cc_chapter_id' => 337,
                'name' => 'اعمال روی توابع',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            87 => 
            array (
                'id' => 1136,
                'cc_chapter_id' => 337,
                'name' => 'تابع نمایی و لگاریتمی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            88 => 
            array (
                'id' => 1137,
                'cc_chapter_id' => 337,
                'name' => 'مثلثات',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            89 => 
            array (
                'id' => 1138,
                'cc_chapter_id' => 337,
                'name' => 'حد و پیوستگی',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            90 => 
            array (
                'id' => 1139,
                'cc_chapter_id' => 338,
                'name' => 'تابع نمایی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            91 => 
            array (
                'id' => 1140,
                'cc_chapter_id' => 338,
                'name' => 'تابع لگاریتمی و لگاریتم',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            92 => 
            array (
                'id' => 1141,
                'cc_chapter_id' => 338,
                'name' => 'ویژگی های لگاریتم و حل معادله های لگاریتمی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            93 => 
            array (
                'id' => 1142,
                'cc_chapter_id' => 339,
                'name' => 'رادیان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            94 => 
            array (
                'id' => 1143,
                'cc_chapter_id' => 339,
                'name' => 'نسبت های مثلثاتی برخی زاویه ها',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            95 => 
            array (
                'id' => 1144,
                'cc_chapter_id' => 339,
                'name' => 'توابع مثلثاتی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            96 => 
            array (
                'id' => 1145,
                'cc_chapter_id' => 339,
                'name' => ' روابط مثلثاتی مجموع و تفاضل زوایا',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            97 => 
            array (
                'id' => 1146,
                'cc_chapter_id' => 340,
                'name' => 'مفهوم حد و فرایند های حدی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            98 => 
            array (
                'id' => 1147,
                'cc_chapter_id' => 340,
            'name' => ' حد های یک طرفه (حد چپ و حد راست)',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            99 => 
            array (
                'id' => 1148,
                'cc_chapter_id' => 340,
                'name' => ' قضایای حد',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            100 => 
            array (
                'id' => 1149,
                'cc_chapter_id' => 340,
            'name' => ' محاسبه حد توابع کسری (حالت صفر صفرم)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            101 => 
            array (
                'id' => 1150,
                'cc_chapter_id' => 340,
                'name' => 'پیوستگی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            102 => 
            array (
                'id' => 1160,
                'cc_chapter_id' => 344,
                'name' => 'رفتار عنصرها',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            103 => 
            array (
                'id' => 1161,
                'cc_chapter_id' => 344,
                'name' => 'دنیای رنگی با عنصرهای دستۀ d',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            104 => 
            array (
                'id' => 1162,
                'cc_chapter_id' => 344,
                'name' => 'عنصرها به چه شکلی در طبیعت یافت می‌شوند؟',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            105 => 
            array (
                'id' => 1163,
                'cc_chapter_id' => 344,
                'name' => '‌‎دنیای واقعی واکنش‌ها',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            106 => 
            array (
                'id' => 1164,
                'cc_chapter_id' => 344,
                'name' => '‌‎نفت هدیه‌ای شگفت‌انگیز',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            107 => 
            array (
                'id' => 1165,
                'cc_chapter_id' => 344,
                'name' => 'آلکان‌ها، هیدروکربن‌هایی با پیوندهای یگانه‎',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            108 => 
            array (
                'id' => 1166,
                'cc_chapter_id' => 344,
                'name' => '‌‎آلکن‌ها، آلکین‌ها و هیدروکربن‌های حلقوی‎',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            109 => 
            array (
                'id' => 1167,
                'cc_chapter_id' => 344,
                'name' => 'نفت، ماده‌ای که اقتصاد جهان را دگرگون ساخت',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            110 => 
            array (
                'id' => 1168,
                'cc_chapter_id' => 345,
                'name' => 'غذا، ماده و انرژی‎',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            111 => 
            array (
                'id' => 1169,
                'cc_chapter_id' => 345,
                'name' => 'جاری شدن انرژی ـ آنتالپی‎',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            112 => 
            array (
                'id' => 1170,
                'cc_chapter_id' => 345,
                'name' => 'آنتالپی پیوند و میانگین آن',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            113 => 
            array (
                'id' => 1171,
                'cc_chapter_id' => 345,
                'name' => 'گروه‌های عاملی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            114 => 
            array (
                'id' => 1172,
                'cc_chapter_id' => 345,
                'name' => 'آنتالپی سوختن، تکیه‌گاهی برای تأمین انرژی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            115 => 
            array (
                'id' => 1173,
                'cc_chapter_id' => 345,
                'name' => 'گرماسنجی و قانون هس',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            116 => 
            array (
                'id' => 1174,
                'cc_chapter_id' => 345,
                'name' => 'غذای سالم و عوامل مؤثر بر سرعت واکنش‌ها',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            117 => 
            array (
                'id' => 1175,
                'cc_chapter_id' => 345,
                'name' => 'سینتیک شیمیایی و مسائل سرعت',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            118 => 
            array (
                'id' => 1176,
                'cc_chapter_id' => 345,
                'name' => 'غذا، پسماند و رد پای آن',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            119 => 
            array (
                'id' => 1177,
                'cc_chapter_id' => 346,
                'name' => 'پلیمری شدن ترکیب‌های دارای پیوند دوگانه کربن - کربن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            120 => 
            array (
                'id' => 1178,
                'cc_chapter_id' => 346,
                'name' => 'پلی استرها و روش تهیۀ آنها',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            121 => 
            array (
                'id' => 1179,
                'cc_chapter_id' => 346,
                'name' => '‌‎پلی‌آمیدها و روش تهیۀ آنها‎',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            122 => 
            array (
                'id' => 1180,
                'cc_chapter_id' => 346,
                'name' => 'پلیمرها، ماندگار یا تخریب پذیر- پلیمر سبز',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            123 => 
            array (
                'id' => 1181,
                'cc_chapter_id' => 347,
                'name' => 'بار الکتریکی - پایستگی و کوانتیده بودن بار الکتریکی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            124 => 
            array (
                'id' => 1182,
                'cc_chapter_id' => 347,
                'name' => 'قانون کولن - بر هم نهی نیرو های الکتروستاتیکی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            125 => 
            array (
                'id' => 1183,
                'cc_chapter_id' => 347,
                'name' => 'میدان الکتریکی - بر هم نهی میدان های الکتریکی - خطوط میدان الکتریکی - میدان الکتریکی یکنواخت',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            126 => 
            array (
                'id' => 1184,
                'cc_chapter_id' => 347,
                'name' => 'انرژی پتانسیل الکتریکی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            127 => 
            array (
                'id' => 1185,
                'cc_chapter_id' => 347,
                'name' => 'پتانسیل الکتریکی - رابطه ی اختلاف پتانسیل دو نقطه و اندازه ی میدان یکنواخت - کار نیروی خارجی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            128 => 
            array (
                'id' => 1186,
                'cc_chapter_id' => 347,
                'name' => 'میدان الکتریکی در داخل رسانا ها - رسانای خنثی در میدان الکتریکی - چگالی سطحی بار الکتریکی',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            129 => 
            array (
                'id' => 1187,
                'cc_chapter_id' => 347,
                'name' => 'خازن - خازن با دی الکتریک - انرژی خازن',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            130 => 
            array (
                'id' => 1188,
                'cc_chapter_id' => 348,
                'name' => 'جریان الکتریکی ـ مقاومت الکتریکی و قانون اهم',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            131 => 
            array (
                'id' => 1189,
                'cc_chapter_id' => 348,
                'name' => 'عوامل مؤثر بر مقاومت الکتریکی، انواع مقاومت ها و کدگذاری',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            132 => 
            array (
                'id' => 1190,
                'cc_chapter_id' => 348,
                'name' => 'نیروی محرکه الکتریکی و مدار ها - قاعده حلقه، مدار تک حلقه ای و افت پتانسیل در مقاومت',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            133 => 
            array (
                'id' => 1191,
                'cc_chapter_id' => 348,
                'name' => 'توان و انرژی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            134 => 
            array (
                'id' => 1192,
                'cc_chapter_id' => 348,
                'name' => 'قاعدۀ انشعاب، ترکیب مقاومت ها - به هم بستن متوالی، موازی یا ترکیبی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            135 => 
            array (
                'id' => 1193,
                'cc_chapter_id' => 349,
                'name' => 'مغناطیس و قطب های مغناطیسی - میدان مغناطیسی - میدان مغناطیسی زمین - میدان مغناطیسی یکنواخت',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            136 => 
            array (
                'id' => 1194,
                'cc_chapter_id' => 349,
                'name' => 'نیروی مغناطیسی وارد بر ذره ی باردار متحرک در میدان مغناطیسی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            137 => 
            array (
                'id' => 1195,
                'cc_chapter_id' => 349,
                'name' => 'نیروی مغناطیسی وارد بر سیم حامل جریان',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            138 => 
            array (
                'id' => 1196,
                'cc_chapter_id' => 349,
                'name' => 'میدان مغناطیسی اطراف سیم راست حامل جریان',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            139 => 
            array (
                'id' => 1197,
                'cc_chapter_id' => 349,
                'name' => 'میدان ناشی از حلقه و سیملوله',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            140 => 
            array (
                'id' => 1198,
                'cc_chapter_id' => 349,
                'name' => 'ویژگی های مغناطیسی مواد',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            141 => 
            array (
                'id' => 1199,
                'cc_chapter_id' => 350,
                'name' => 'القای الکترومغناطیس',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            142 => 
            array (
                'id' => 1200,
                'cc_chapter_id' => 350,
                'name' => 'القاگر ها - خود القاوری - ضریب القاوری - القای متقابل - انرژی ذخیره شده در القاگر',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            143 => 
            array (
                'id' => 1201,
                'cc_chapter_id' => 350,
                'name' => 'جریان متناوب - مبدل ها',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            144 => 
            array (
                'id' => 1213,
                'cc_chapter_id' => 355,
                'name' => ' نیکی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            145 => 
            array (
                'id' => 1214,
                'cc_chapter_id' => 355,
                'name' => 'قاضی بُست',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            146 => 
            array (
                'id' => 1215,
                'cc_chapter_id' => 355,
                'name' => 'شعرخوانی: زاغ و کبک',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            147 => 
            array (
                'id' => 1216,
                'cc_chapter_id' => 356,
                'name' => 'در امواج سند',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            148 => 
            array (
                'id' => 1217,
                'cc_chapter_id' => 356,
                'name' => 'آغازگری تنها',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            149 => 
            array (
                'id' => 1218,
                'cc_chapter_id' => 357,
                'name' => 'پرورده ی عشق',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            150 => 
            array (
                'id' => 1219,
                'cc_chapter_id' => 357,
                'name' => ' باران محبّت',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            151 => 
            array (
                'id' => 1220,
                'cc_chapter_id' => 357,
                'name' => 'شعرخوانی: آفتاب حُسن',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            152 => 
            array (
                'id' => 1221,
                'cc_chapter_id' => 358,
                'name' => 'در کوی عاشقان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            153 => 
            array (
                'id' => 1222,
                'cc_chapter_id' => 358,
                'name' => ' ذوق لطیف',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            154 => 
            array (
                'id' => 1223,
                'cc_chapter_id' => 359,
                'name' => 'بانگ جَرَس',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            155 => 
            array (
                'id' => 1224,
                'cc_chapter_id' => 359,
                'name' => ' یارانِ عاشق',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            156 => 
            array (
                'id' => 1225,
                'cc_chapter_id' => 359,
                'name' => 'شعرخوانی: صبح بی تو',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            157 => 
            array (
                'id' => 1226,
                'cc_chapter_id' => 360,
                'name' => 'کاوه ی دادخواه',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            158 => 
            array (
                'id' => 1227,
                'cc_chapter_id' => 360,
                'name' => 'حمله ی حیدری',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            159 => 
            array (
                'id' => 1228,
                'cc_chapter_id' => 360,
                'name' => 'شعرخوانی: وطن',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            160 => 
            array (
                'id' => 1229,
                'cc_chapter_id' => 361,
                'name' => 'کبوتر طوقدار',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-08 19:20:43',
            ),
            161 => 
            array (
                'id' => 1230,
                'cc_chapter_id' => 361,
                'name' => 'قصّه ی عینکم',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            162 => 
            array (
                'id' => 1231,
                'cc_chapter_id' => 362,
                'name' => 'خاموشی دریا',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            163 => 
            array (
                'id' => 1232,
                'cc_chapter_id' => 362,
                'name' => 'خوان عدل',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            164 => 
            array (
                'id' => 1233,
                'cc_chapter_id' => 363,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            165 => 
            array (
                'id' => 1234,
                'cc_chapter_id' => 363,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            166 => 
            array (
                'id' => 1235,
                'cc_chapter_id' => 363,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            167 => 
            array (
                'id' => 1236,
                'cc_chapter_id' => 364,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            168 => 
            array (
                'id' => 1237,
                'cc_chapter_id' => 364,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            169 => 
            array (
                'id' => 1238,
                'cc_chapter_id' => 364,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            170 => 
            array (
                'id' => 1239,
                'cc_chapter_id' => 365,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            171 => 
            array (
                'id' => 1240,
                'cc_chapter_id' => 365,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            172 => 
            array (
                'id' => 1241,
                'cc_chapter_id' => 365,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            173 => 
            array (
                'id' => 1242,
                'cc_chapter_id' => 366,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            174 => 
            array (
                'id' => 1243,
                'cc_chapter_id' => 366,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            175 => 
            array (
                'id' => 1244,
                'cc_chapter_id' => 366,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            176 => 
            array (
                'id' => 1245,
                'cc_chapter_id' => 367,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            177 => 
            array (
                'id' => 1246,
                'cc_chapter_id' => 367,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            178 => 
            array (
                'id' => 1247,
                'cc_chapter_id' => 367,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            179 => 
            array (
                'id' => 1248,
                'cc_chapter_id' => 368,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            180 => 
            array (
                'id' => 1249,
                'cc_chapter_id' => 368,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            181 => 
            array (
                'id' => 1250,
                'cc_chapter_id' => 368,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            182 => 
            array (
                'id' => 1251,
                'cc_chapter_id' => 369,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            183 => 
            array (
                'id' => 1252,
                'cc_chapter_id' => 369,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            184 => 
            array (
                'id' => 1253,
                'cc_chapter_id' => 369,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            185 => 
            array (
                'id' => 1254,
                'cc_chapter_id' => 370,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            186 => 
            array (
                'id' => 1255,
                'cc_chapter_id' => 370,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            187 => 
            array (
                'id' => 1256,
                'cc_chapter_id' => 370,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            188 => 
            array (
                'id' => 1257,
                'cc_chapter_id' => 371,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            189 => 
            array (
                'id' => 1258,
                'cc_chapter_id' => 371,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            190 => 
            array (
                'id' => 1259,
                'cc_chapter_id' => 371,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            191 => 
            array (
                'id' => 1260,
                'cc_chapter_id' => 372,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            192 => 
            array (
                'id' => 1261,
                'cc_chapter_id' => 372,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            193 => 
            array (
                'id' => 1262,
                'cc_chapter_id' => 372,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            194 => 
            array (
                'id' => 1263,
                'cc_chapter_id' => 373,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            195 => 
            array (
                'id' => 1264,
                'cc_chapter_id' => 373,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            196 => 
            array (
                'id' => 1265,
                'cc_chapter_id' => 374,
                'name' => 'متن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            197 => 
            array (
                'id' => 1266,
                'cc_chapter_id' => 374,
                'name' => 'آیه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            198 => 
            array (
                'id' => 1267,
                'cc_chapter_id' => 374,
                'name' => 'حدیث',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            199 => 
            array (
                'id' => 1268,
                'cc_chapter_id' => 375,
                'name' => 'واژگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            200 => 
            array (
                'id' => 1269,
                'cc_chapter_id' => 375,
                'name' => 'ترجمه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            201 => 
            array (
                'id' => 1270,
                'cc_chapter_id' => 375,
                'name' => 'مکالمه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            202 => 
            array (
                'id' => 1271,
                'cc_chapter_id' => 375,
                'name' => 'درک مطلب',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            203 => 
            array (
                'id' => 1272,
                'cc_chapter_id' => 375,
                'name' => 'قواعد',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            204 => 
            array (
                'id' => 1273,
                'cc_chapter_id' => 375,
                'name' => 'مفهوم ',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            205 => 
            array (
                'id' => 1274,
                'cc_chapter_id' => 375,
                'name' => 'اعراب و تحلیل صرفی',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            206 => 
            array (
                'id' => 1275,
                'cc_chapter_id' => 382,
                'name' => 'حکومت قاجار از آقا محمد خان تا محمد شاه',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            207 => 
            array (
                'id' => 1276,
                'cc_chapter_id' => 383,
                'name' => 'دوران ناصرالدین شاه',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            208 => 
            array (
                'id' => 1277,
                'cc_chapter_id' => 384,
                'name' => 'زمینه‌های نهضت مشروطه',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            209 => 
            array (
                'id' => 1278,
                'cc_chapter_id' => 385,
                'name' => 'آغاز حرکت مردم علیه استبداد و پیروزی نهضت مشروطه',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            210 => 
            array (
                'id' => 1279,
                'cc_chapter_id' => 386,
                'name' => 'مشروطه در دوزۀ محمدعلی شاه',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            211 => 
            array (
                'id' => 1280,
                'cc_chapter_id' => 387,
            'name' => 'دورۀ مشروطۀ دوم (1288 - 1293)',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            212 => 
            array (
                'id' => 1281,
                'cc_chapter_id' => 388,
                'name' => ' کودتای 1299',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            213 => 
            array (
                'id' => 1282,
                'cc_chapter_id' => 389,
                'name' => 'رضاخان، تثبیت قدرت',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            214 => 
            array (
                'id' => 1283,
                'cc_chapter_id' => 390,
                'name' => ' ویژگی‌های حکومت رضا شاه',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            215 => 
            array (
                'id' => 1284,
                'cc_chapter_id' => 391,
                'name' => 'سقوط رضا شاه',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            216 => 
            array (
                'id' => 1285,
                'cc_chapter_id' => 392,
                'name' => 'اشغال ایران توسط متفقین و آثار آن',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            217 => 
            array (
                'id' => 1286,
                'cc_chapter_id' => 393,
                'name' => 'نهضت ملی شدن صنعت نفت',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            218 => 
            array (
                'id' => 1287,
                'cc_chapter_id' => 394,
                'name' => 'زمینه‌های کودتای 28 مرداد',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            219 => 
            array (
                'id' => 1288,
                'cc_chapter_id' => 395,
                'name' => 'کودتای بیست و هشتم مرداد',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            220 => 
            array (
                'id' => 1289,
                'cc_chapter_id' => 396,
                'name' => 'ربع قرن سیطرۀ آمریکا بر ایران',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            221 => 
            array (
                'id' => 1290,
                'cc_chapter_id' => 397,
                'name' => ' زمینه‌ها و هدف‌های اصلاحات آمریکایی در ایران',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            222 => 
            array (
                'id' => 1291,
                'cc_chapter_id' => 398,
                'name' => 'پیدایش نهضت روحانیت و اوج‌گیری بیداری اسلامی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            223 => 
            array (
                'id' => 1292,
                'cc_chapter_id' => 399,
                'name' => ' قیام 15 خرداد',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            224 => 
            array (
                'id' => 1293,
                'cc_chapter_id' => 400,
                'name' => 'تحولات ایران پس از تبعید امام خمینی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            225 => 
            array (
                'id' => 1294,
                'cc_chapter_id' => 401,
                'name' => ' ایران در مسیر انقلاب اسلامی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            226 => 
            array (
                'id' => 1295,
                'cc_chapter_id' => 402,
                'name' => 'پیروزی انقلاب اسلامی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            227 => 
            array (
                'id' => 1296,
                'cc_chapter_id' => 403,
                'name' => 'دولت موقت مهندس مهدی بازرگان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            228 => 
            array (
                'id' => 1297,
                'cc_chapter_id' => 404,
                'name' => ' اولین دورۀ ریاست جمهوری',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            229 => 
            array (
                'id' => 1298,
                'cc_chapter_id' => 405,
                'name' => 'جنگ تحمیلی رژیم بعثی حاکم بر عراق علیه ایران',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            230 => 
            array (
                'id' => 1299,
                'cc_chapter_id' => 406,
                'name' => ' آرمان‌ها و دستاوردهای انقلاب اسلامی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            231 => 
            array (
                'id' => 1300,
                'cc_chapter_id' => 407,
                'name' => ' بیداری اسلامی در جهان اسلام',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            232 => 
            array (
                'id' => 1301,
                'cc_chapter_id' => 408,
            'name' => 'درک مطلب (reading comprehension)',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            233 => 
            array (
                'id' => 1302,
                'cc_chapter_id' => 408,
            'name' => 'گرامر (grammar)',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            234 => 
            array (
                'id' => 1303,
                'cc_chapter_id' => 408,
            'name' => 'واژگان (vocabulary)',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            235 => 
            array (
                'id' => 1304,
                'cc_chapter_id' => 408,
            'name' => 'نگارش (writing)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            236 => 
            array (
                'id' => 1305,
                'cc_chapter_id' => 408,
            'name' => 'Listening (شنیداری)',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            237 => 
            array (
                'id' => 1306,
                'cc_chapter_id' => 409,
            'name' => 'درک مطلب (reading comprehension)',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            238 => 
            array (
                'id' => 1307,
                'cc_chapter_id' => 409,
            'name' => 'گرامر (grammar)',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            239 => 
            array (
                'id' => 1308,
                'cc_chapter_id' => 409,
            'name' => 'واژگان (vocabulary)',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            240 => 
            array (
                'id' => 1309,
                'cc_chapter_id' => 409,
            'name' => 'نگارش (writing)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            241 => 
            array (
                'id' => 1310,
                'cc_chapter_id' => 409,
            'name' => 'Listening (شنیداری)',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            242 => 
            array (
                'id' => 1311,
                'cc_chapter_id' => 410,
            'name' => 'درک مطلب (reading comprehension)',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            243 => 
            array (
                'id' => 1312,
                'cc_chapter_id' => 410,
            'name' => 'گرامر (grammar)',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            244 => 
            array (
                'id' => 1313,
                'cc_chapter_id' => 410,
            'name' => 'واژگان (vocabulary)',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            245 => 
            array (
                'id' => 1314,
                'cc_chapter_id' => 410,
            'name' => 'نگارش (writing)',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            246 => 
            array (
                'id' => 1315,
                'cc_chapter_id' => 410,
            'name' => 'Listening (شنیداری)',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            247 => 
            array (
                'id' => 1316,
                'cc_chapter_id' => 411,
                'name' => ' زمین‌شناسی ایران',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            248 => 
            array (
                'id' => 1317,
                'cc_chapter_id' => 411,
                'name' => 'تشکیل عناصر',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            249 => 
            array (
                'id' => 1318,
                'cc_chapter_id' => 411,
                'name' => 'کهکشان راه شیری',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            250 => 
            array (
                'id' => 1319,
                'cc_chapter_id' => 411,
                'name' => 'سامانۀ خورشیدی',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            251 => 
            array (
                'id' => 1320,
                'cc_chapter_id' => 411,
                'name' => 'تکوین زمین و آغاز زندگی در آن',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            252 => 
            array (
                'id' => 1321,
                'cc_chapter_id' => 411,
                'name' => 'سن زمین',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            253 => 
            array (
                'id' => 1322,
                'cc_chapter_id' => 411,
                'name' => 'زمان در زمین‌شناسی',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            254 => 
            array (
                'id' => 1323,
                'cc_chapter_id' => 411,
                'name' => 'تغییرات آب‌و‌هوایی',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            255 => 
            array (
                'id' => 1324,
                'cc_chapter_id' => 411,
                'name' => 'علم، زندگی، کارآفرینی',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            256 => 
            array (
                'id' => 1325,
                'cc_chapter_id' => 412,
                'name' => 'منابع معدنی در زندگی ما',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            257 => 
            array (
                'id' => 1326,
                'cc_chapter_id' => 412,
                'name' => 'غلظت عناصر در پوستۀ زمین و کانی‌های سیلیکاتی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            258 => 
            array (
                'id' => 1327,
                'cc_chapter_id' => 412,
                'name' => 'سری واکنشی بوون',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            259 => 
            array (
                'id' => 1328,
                'cc_chapter_id' => 412,
                'name' => 'کانه و کانسنگ',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            260 => 
            array (
                'id' => 1329,
                'cc_chapter_id' => 412,
                'name' => 'طبقه‌بندی کانسنگ‌ها',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            261 => 
            array (
                'id' => 1330,
                'cc_chapter_id' => 412,
                'name' => 'اکتشاف معدن',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            262 => 
            array (
                'id' => 1331,
                'cc_chapter_id' => 412,
                'name' => 'استخراج معدن و فرآوری مادۀ معدنی',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            263 => 
            array (
                'id' => 1332,
                'cc_chapter_id' => 412,
                'name' => 'گوهرها، زیبایی شگفت‌انگیز دنیای کانی‌ها',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            264 => 
            array (
                'id' => 1333,
                'cc_chapter_id' => 412,
                'name' => 'سوخت‌های فسیلی',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            265 => 
            array (
                'id' => 1334,
                'cc_chapter_id' => 413,
                'name' => 'سوخت‌های فسیلی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            266 => 
            array (
                'id' => 1335,
                'cc_chapter_id' => 413,
                'name' => 'فرونشست زمین',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            267 => 
            array (
                'id' => 1336,
                'cc_chapter_id' => 413,
                'name' => 'منابع خاک',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            268 => 
            array (
                'id' => 1337,
                'cc_chapter_id' => 413,
                'name' => 'فرسایش',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            269 => 
            array (
                'id' => 1338,
                'cc_chapter_id' => 413,
                'name' => 'علم، زندگی، کارآفرینی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            270 => 
            array (
                'id' => 1339,
                'cc_chapter_id' => 414,
                'name' => 'مقدمه و چرخۀ ویلسون',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            271 => 
            array (
                'id' => 1340,
                'cc_chapter_id' => 414,
                'name' => 'مقاومت سنگ‌ها در برابر تنش',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            272 => 
            array (
                'id' => 1341,
                'cc_chapter_id' => 414,
                'name' => 'امتداد و شیب لایه‌ها‌',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            273 => 
            array (
                'id' => 1342,
                'cc_chapter_id' => 414,
                'name' => 'شکستگی‌ها',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            274 => 
            array (
                'id' => 1343,
                'cc_chapter_id' => 414,
                'name' => 'چین‌خوردگی',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            275 => 
            array (
                'id' => 1344,
                'cc_chapter_id' => 414,
                'name' => 'آتشفشان‌',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            276 => 
            array (
                'id' => 1345,
                'cc_chapter_id' => 414,
                'name' => 'گاز و بخارهای آتشفشانی‌',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            277 => 
            array (
                'id' => 1346,
                'cc_chapter_id' => 414,
                'name' => 'فواید آتشفشان‌ها‌',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            278 => 
            array (
                'id' => 1347,
                'cc_chapter_id' => 414,
                'name' => 'زمین‌لرزه‌',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            279 => 
            array (
                'id' => 1348,
                'cc_chapter_id' => 414,
                'name' => 'امواج لرزه‌ای',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            280 => 
            array (
                'id' => 1349,
                'cc_chapter_id' => 414,
                'name' => 'مقیاس اندازه‌گیری زمین‌لرزه‌',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            281 => 
            array (
                'id' => 1350,
                'cc_chapter_id' => 414,
                'name' => 'پیش‌بینی زمین‌لرزه‌',
                'order' => 11,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            282 => 
            array (
                'id' => 1351,
                'cc_chapter_id' => 414,
                'name' => 'ایمنی در برابر زمین‌لرزه‌',
                'order' => 12,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            283 => 
            array (
                'id' => 1352,
                'cc_chapter_id' => 414,
                'name' => 'علم، زندگی، کارآفرینی‌',
                'order' => 14,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            284 => 
            array (
                'id' => 1353,
                'cc_chapter_id' => 415,
                'name' => 'زمین‌شناسی پزشکی‌',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            285 => 
            array (
                'id' => 1354,
                'cc_chapter_id' => 415,
                'name' => 'چرخه بیوژئوشیمیایی و تقسیم‌بندی بیوشیمیایی عناصر‌',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            286 => 
            array (
                'id' => 1355,
                'cc_chapter_id' => 415,
                'name' => 'منشا بیماری‌های زمین‌زاد‌',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            287 => 
            array (
                'id' => 1356,
                'cc_chapter_id' => 415,
                'name' => 'اثرات توفان‌های گرد و غبار و ریزگردها‌',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            288 => 
            array (
                'id' => 1357,
                'cc_chapter_id' => 415,
                'name' => 'کاربرد کانی‌ها در داروسازی و صنایع بهداشتی‌',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            289 => 
            array (
                'id' => 1358,
                'cc_chapter_id' => 415,
                'name' => 'علم، زندگی، کارآفرینی‌',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            290 => 
            array (
                'id' => 1359,
                'cc_chapter_id' => 416,
                'name' => 'مکان‌یابی سازه‌ها‌',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            291 => 
            array (
                'id' => 1360,
                'cc_chapter_id' => 416,
                'name' => 'نحوۀ به‌دست آوردن اطلاعات زمین‌شناسی‌',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            292 => 
            array (
                'id' => 1361,
                'cc_chapter_id' => 416,
                'name' => 'عوامل مؤثر بر مکان‌یابی سازه‌ها‌',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            293 => 
            array (
                'id' => 1362,
                'cc_chapter_id' => 416,
                'name' => 'مکان مناسب برای ساخت سد‌',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            294 => 
            array (
                'id' => 1363,
                'cc_chapter_id' => 416,
                'name' => 'مکان مناسب برای ساخت تونل و فضاهای زیرزمینی‌',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            295 => 
            array (
                'id' => 1364,
                'cc_chapter_id' => 416,
                'name' => 'مکان یابی مناسب برای ساخت سازه‌های دریایی‌',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            296 => 
            array (
                'id' => 1365,
                'cc_chapter_id' => 416,
                'name' => 'شاخص‌های مهندسی مصالح‌',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            297 => 
            array (
                'id' => 1366,
                'cc_chapter_id' => 416,
                'name' => 'مصالح مورد نیاز برای احداث سازه‌ها‌',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            298 => 
            array (
                'id' => 1367,
                'cc_chapter_id' => 416,
                'name' => 'علم، زندگی، کارآفرینی‌',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            299 => 
            array (
                'id' => 1368,
                'cc_chapter_id' => 417,
                'name' => 'تاریخچۀ زمین‌شناسی ایران‌',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            300 => 
            array (
                'id' => 1369,
                'cc_chapter_id' => 417,
                'name' => 'نقشه‌های زمین‌شناسی‌',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            301 => 
            array (
                'id' => 1370,
                'cc_chapter_id' => 417,
                'name' => 'پهنه‌های زمین‌شناسی ایران‌',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            302 => 
            array (
                'id' => 1371,
                'cc_chapter_id' => 417,
                'name' => 'منابع معدنی و ذخایر انرژی ایران‌',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            303 => 
            array (
                'id' => 1372,
                'cc_chapter_id' => 417,
                'name' => 'ذخایر نفت و گاز ایران‌',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            304 => 
            array (
                'id' => 1373,
                'cc_chapter_id' => 417,
                'name' => 'گسل‌های ایران‌',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            305 => 
            array (
                'id' => 1374,
                'cc_chapter_id' => 417,
                'name' => 'آتشفشان‌های ایران‌',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            306 => 
            array (
                'id' => 1375,
                'cc_chapter_id' => 417,
                'name' => 'زمین گردشگری و ژئوپارک‌',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            307 => 
            array (
                'id' => 1376,
                'cc_chapter_id' => 417,
                'name' => 'علم، زندگی، کارآفرینی‌',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            308 => 
            array (
                'id' => 1377,
                'cc_chapter_id' => 418,
                'name' => ' سرچشمه‌ی زندگی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            309 => 
            array (
                'id' => 1378,
                'cc_chapter_id' => 419,
                'name' => 'خاک، بستر زندگی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            310 => 
            array (
                'id' => 1379,
                'cc_chapter_id' => 420,
                'name' => 'هوا، نَفَسِ زندگی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            311 => 
            array (
                'id' => 1380,
                'cc_chapter_id' => 421,
                'name' => 'انرژی، حرکت، زندگی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            312 => 
            array (
                'id' => 1381,
                'cc_chapter_id' => 422,
                'name' => 'زباله، فاجعه‌ی محیط زیست',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            313 => 
            array (
                'id' => 1382,
                'cc_chapter_id' => 423,
                'name' => 'تنوع زیستی، تابلوی زیبای آفرینش',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            314 => 
            array (
                'id' => 1383,
                'cc_chapter_id' => 424,
                'name' => 'محیط زیست، بستر گردشگری مسئولانه',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:44',
                'updated_at' => '2026-02-08 19:20:44',
            ),
            315 => 
            array (
                'id' => 1384,
                'cc_chapter_id' => 425,
                'name' => 'نوکلئیک اسیدها',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:45:37',
                'updated_at' => '2026-02-08 19:45:37',
            ),
            316 => 
            array (
                'id' => 1385,
                'cc_chapter_id' => 425,
                'name' => 'همانندسازی دِنا',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:45:46',
                'updated_at' => '2026-02-08 19:45:46',
            ),
            317 => 
            array (
                'id' => 1386,
                'cc_chapter_id' => 425,
                'name' => 'پروتئین ها',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:45:55',
                'updated_at' => '2026-02-08 19:45:55',
            ),
            318 => 
            array (
                'id' => 1387,
                'cc_chapter_id' => 426,
                'name' => 'رونویسی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:46:26',
                'updated_at' => '2026-02-08 19:46:26',
            ),
            319 => 
            array (
                'id' => 1388,
                'cc_chapter_id' => 426,
                'name' => 'به سوی پروتئین ',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:46:33',
                'updated_at' => '2026-02-08 19:46:33',
            ),
            320 => 
            array (
                'id' => 1389,
                'cc_chapter_id' => 426,
                'name' => 'تنظیم بیان ژن',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:46:40',
                'updated_at' => '2026-02-08 19:46:40',
            ),
            321 => 
            array (
                'id' => 1390,
                'cc_chapter_id' => 427,
                'name' => 'مفاهیم پایه ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:46:58',
                'updated_at' => '2026-02-08 19:46:58',
            ),
            322 => 
            array (
                'id' => 1391,
                'cc_chapter_id' => 427,
                'name' => 'انواع صفات ',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:47:07',
                'updated_at' => '2026-02-08 19:47:07',
            ),
            323 => 
            array (
                'id' => 1392,
                'cc_chapter_id' => 428,
                'name' => 'تغییر در مادۀ وراثتی جانداران ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:47:24',
                'updated_at' => '2026-02-08 19:47:24',
            ),
            324 => 
            array (
                'id' => 1393,
                'cc_chapter_id' => 428,
                'name' => 'تغییر در جمعیت ها',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:47:35',
                'updated_at' => '2026-02-08 19:47:35',
            ),
            325 => 
            array (
                'id' => 1394,
                'cc_chapter_id' => 428,
                'name' => 'تغییر در گونه ها ',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:47:53',
                'updated_at' => '2026-02-08 19:47:53',
            ),
            326 => 
            array (
                'id' => 1395,
                'cc_chapter_id' => 429,
                'name' => 'تأمین انرژی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:48:04',
                'updated_at' => '2026-02-08 19:48:04',
            ),
            327 => 
            array (
                'id' => 1396,
                'cc_chapter_id' => 429,
                'name' => 'اکسایش بیشتر ',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:48:15',
                'updated_at' => '2026-02-08 19:48:15',
            ),
            328 => 
            array (
                'id' => 1397,
                'cc_chapter_id' => 429,
                'name' => 'زیستن مستقل از اکسیژن',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:48:21',
                'updated_at' => '2026-02-08 19:48:21',
            ),
            329 => 
            array (
                'id' => 1398,
                'cc_chapter_id' => 430,
                'name' => 'فتوسنتز: تبدیل انرژی نور به انرژی شیمیایی ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:48:36',
                'updated_at' => '2026-02-08 19:48:36',
            ),
            330 => 
            array (
                'id' => 1399,
                'cc_chapter_id' => 430,
                'name' => 'واکنش های فتوسنتزی ',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:48:42',
                'updated_at' => '2026-02-08 19:48:42',
            ),
            331 => 
            array (
                'id' => 1400,
                'cc_chapter_id' => 430,
                'name' => 'فتوسنتز در شرایط دشوار ',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:48:48',
                'updated_at' => '2026-02-08 19:48:48',
            ),
            332 => 
            array (
                'id' => 1401,
                'cc_chapter_id' => 431,
                'name' => 'زیست فناوری و مهندسی ژنتیک',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:49:07',
                'updated_at' => '2026-02-08 19:49:07',
            ),
            333 => 
            array (
                'id' => 1402,
                'cc_chapter_id' => 431,
                'name' => 'فناوری مهندسی پروتئین و بافت ',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:49:13',
                'updated_at' => '2026-02-08 19:49:13',
            ),
            334 => 
            array (
                'id' => 1403,
                'cc_chapter_id' => 431,
                'name' => 'کاربردهای زیست فناوری',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:49:19',
                'updated_at' => '2026-02-08 19:49:19',
            ),
            335 => 
            array (
                'id' => 1404,
                'cc_chapter_id' => 432,
                'name' => ' اساس رفتار ',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:49:30',
                'updated_at' => '2026-02-08 19:49:30',
            ),
            336 => 
            array (
                'id' => 1405,
                'cc_chapter_id' => 432,
                'name' => 'انتخاب طبیعی و رفتار ',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:49:36',
                'updated_at' => '2026-02-08 19:49:36',
            ),
            337 => 
            array (
                'id' => 1406,
                'cc_chapter_id' => 432,
                'name' => 'ارتباط و زندگی گروهی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:49:43',
                'updated_at' => '2026-02-08 19:49:43',
            ),
            338 => 
            array (
                'id' => 1408,
                'cc_chapter_id' => 433,
                'name' => 'ساختار دستگاه عصبی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:58:14',
                'updated_at' => '2026-02-08 20:19:51',
            ),
            339 => 
            array (
                'id' => 1410,
                'cc_chapter_id' => 434,
                'name' => 'گیرنده های حسی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:58:57',
                'updated_at' => '2026-02-08 20:20:19',
            ),
            340 => 
            array (
                'id' => 1411,
                'cc_chapter_id' => 434,
                'name' => 'حواس ویژه',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:59:07',
                'updated_at' => '2026-02-08 20:20:28',
            ),
            341 => 
            array (
                'id' => 1412,
                'cc_chapter_id' => 434,
                'name' => 'گیرنده های حسی جانوران',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:59:18',
                'updated_at' => '2026-02-08 20:20:38',
            ),
            342 => 
            array (
                'id' => 1413,
                'cc_chapter_id' => 435,
                'name' => 'استخوان ها و اسکلت',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:59:42',
                'updated_at' => '2026-02-08 20:20:54',
            ),
            343 => 
            array (
                'id' => 1414,
                'cc_chapter_id' => 435,
                'name' => 'ماهیچه و حرکت',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:59:56',
                'updated_at' => '2026-02-08 20:21:21',
            ),
            344 => 
            array (
                'id' => 1416,
                'cc_chapter_id' => 436,
                'name' => 'نخستین خط دفاعی: ورود ممنوع',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:00:51',
                'updated_at' => '2026-02-08 20:22:01',
            ),
            345 => 
            array (
                'id' => 1417,
                'cc_chapter_id' => 436,
                'name' => 'دومین خط دفاعی: واکنش های عمومی اما سریع',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:00:56',
                'updated_at' => '2026-02-08 20:22:13',
            ),
            346 => 
            array (
                'id' => 1418,
                'cc_chapter_id' => 436,
                'name' => 'سومین خط دفاعی: دفاع اختصاصی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:01:04',
                'updated_at' => '2026-02-08 20:22:22',
            ),
            347 => 
            array (
                'id' => 1420,
                'cc_chapter_id' => 437,
            'name' => 'فام تن (کروموزوم)',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:01:49',
                'updated_at' => '2026-02-08 20:23:14',
            ),
            348 => 
            array (
                'id' => 1421,
                'cc_chapter_id' => 437,
            'name' => 'رِشتِمان (میتوز)',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:02:05',
                'updated_at' => '2026-02-08 20:23:36',
            ),
            349 => 
            array (
                'id' => 1422,
                'cc_chapter_id' => 437,
            'name' => 'کاستمان(میوز)و تولیدمثل جنسی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:02:27',
                'updated_at' => '2026-02-08 20:24:12',
            ),
            350 => 
            array (
                'id' => 1423,
                'cc_chapter_id' => 438,
                'name' => 'دستگاه تولیدمثل در مرد',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:02:51',
                'updated_at' => '2026-02-08 20:24:36',
            ),
            351 => 
            array (
                'id' => 1424,
                'cc_chapter_id' => 438,
                'name' => 'دستگاه تولیدمثل در زن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:03:18',
                'updated_at' => '2026-02-08 20:24:45',
            ),
            352 => 
            array (
                'id' => 1425,
                'cc_chapter_id' => 438,
                'name' => 'رشد و نمو جنین',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:03:23',
                'updated_at' => '2026-02-08 20:24:53',
            ),
            353 => 
            array (
                'id' => 1426,
                'cc_chapter_id' => 439,
                'name' => 'تولیدمثل غیر جنسی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:04:36',
                'updated_at' => '2026-02-08 20:25:21',
            ),
            354 => 
            array (
                'id' => 1427,
                'cc_chapter_id' => 439,
                'name' => 'تولیدمثل جنسی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:04:48',
                'updated_at' => '2026-02-08 20:25:33',
            ),
            355 => 
            array (
                'id' => 1428,
                'cc_chapter_id' => 439,
                'name' => 'از یاخته تخم تا گیاه',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:04:54',
                'updated_at' => '2026-02-08 20:25:42',
            ),
            356 => 
            array (
                'id' => 1429,
                'cc_chapter_id' => 440,
                'name' => 'تنظیم کننده های رشد در گیاهان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:09:44',
                'updated_at' => '2026-02-08 20:26:00',
            ),
            357 => 
            array (
                'id' => 1430,
                'cc_chapter_id' => 440,
                'name' => 'پاسخ به محیط',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:09:54',
                'updated_at' => '2026-02-08 20:26:08',
            ),
            358 => 
            array (
                'id' => 1432,
                'cc_chapter_id' => 441,
                'name' => 'ساختار و عملکرد لولۀ گوارش',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:10:20',
                'updated_at' => '2026-02-08 20:10:20',
            ),
            359 => 
            array (
                'id' => 1433,
                'cc_chapter_id' => 441,
                'name' => 'جذب مواد و تنظیم فعّالیت دستگاه گوارش',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:10:27',
                'updated_at' => '2026-02-08 20:10:27',
            ),
            360 => 
            array (
                'id' => 1434,
                'cc_chapter_id' => 441,
                'name' => 'تنوع گوارش در جانداران',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:10:37',
                'updated_at' => '2026-02-08 20:10:37',
            ),
            361 => 
            array (
                'id' => 1435,
                'cc_chapter_id' => 442,
                'name' => 'ساز و کار دستگاه تنفس در انسان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:10:57',
                'updated_at' => '2026-02-08 20:10:57',
            ),
            362 => 
            array (
                'id' => 1436,
                'cc_chapter_id' => 442,
                'name' => 'تهویۀ ششی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:11:05',
                'updated_at' => '2026-02-08 20:11:05',
            ),
            363 => 
            array (
                'id' => 1437,
                'cc_chapter_id' => 442,
                'name' => 'تنوع تبادلات گازی',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:11:13',
                'updated_at' => '2026-02-08 20:11:13',
            ),
            364 => 
            array (
                'id' => 1438,
                'cc_chapter_id' => 443,
                'name' => 'قلب',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:11:53',
                'updated_at' => '2026-02-08 20:11:53',
            ),
            365 => 
            array (
                'id' => 1439,
                'cc_chapter_id' => 443,
                'name' => 'رگ ها',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:12:01',
                'updated_at' => '2026-02-08 20:12:01',
            ),
            366 => 
            array (
                'id' => 1440,
                'cc_chapter_id' => 443,
                'name' => 'خون',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:12:10',
                'updated_at' => '2026-02-08 20:12:10',
            ),
            367 => 
            array (
                'id' => 1441,
                'cc_chapter_id' => 443,
                'name' => 'تنوع گردش مواد در جانداران',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:12:20',
                'updated_at' => '2026-02-08 20:12:20',
            ),
            368 => 
            array (
                'id' => 1442,
                'cc_chapter_id' => 444,
                'name' => 'هم ایستایی و کلیه ها',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:12:34',
                'updated_at' => '2026-02-08 20:12:34',
            ),
            369 => 
            array (
                'id' => 1443,
                'cc_chapter_id' => 444,
                'name' => 'تشکیل ادرار و تخلیۀ آن',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:12:40',
                'updated_at' => '2026-02-08 20:12:40',
            ),
            370 => 
            array (
                'id' => 1444,
                'cc_chapter_id' => 444,
                'name' => 'تنوع دفع و تنظیم اسمزی در جانداران',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:12:48',
                'updated_at' => '2026-02-08 20:12:48',
            ),
            371 => 
            array (
                'id' => 1445,
                'cc_chapter_id' => 445,
                'name' => 'ویژگی های یاختۀ گیاهی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:13:05',
                'updated_at' => '2026-02-08 20:13:05',
            ),
            372 => 
            array (
                'id' => 1446,
                'cc_chapter_id' => 445,
                'name' => 'سامانۀ بافتی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:13:14',
                'updated_at' => '2026-02-08 20:13:14',
            ),
            373 => 
            array (
                'id' => 1447,
                'cc_chapter_id' => 445,
                'name' => 'ساختار گیاهان',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:13:25',
                'updated_at' => '2026-02-08 20:13:25',
            ),
            374 => 
            array (
                'id' => 1448,
                'cc_chapter_id' => 446,
                'name' => 'تغذیۀ گیاهی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:13:39',
                'updated_at' => '2026-02-08 20:13:39',
            ),
            375 => 
            array (
                'id' => 1449,
                'cc_chapter_id' => 446,
                'name' => 'جانداران مؤثر در تغذیۀ گیاهی',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:13:49',
                'updated_at' => '2026-02-08 20:13:49',
            ),
            376 => 
            array (
                'id' => 1450,
                'cc_chapter_id' => 446,
                'name' => 'انتقال مواد در گیاهان',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:13:59',
                'updated_at' => '2026-02-08 20:13:59',
            ),
            377 => 
            array (
                'id' => 1451,
                'cc_chapter_id' => 433,
                'name' => 'یاخته های بافت عصبی',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:19:16',
                'updated_at' => '2026-02-08 20:19:16',
            ),
            378 => 
            array (
                'id' => 1452,
                'cc_chapter_id' => 438,
                'name' => 'تولیدمثل در جانوران',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:25:03',
                'updated_at' => '2026-02-08 20:25:03',
            ),
            379 => 
            array (
                'id' => 1453,
                'cc_chapter_id' => 447,
                'name' => 'تنظیم کننده های رشد در گیاهان',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:28:44',
                'updated_at' => '2026-02-08 20:28:44',
            ),
            380 => 
            array (
                'id' => 1454,
                'cc_chapter_id' => 447,
                'name' => 'پاسخ به محیط',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:28:51',
                'updated_at' => '2026-02-08 20:28:51',
            ),
        ));
        
        
    }
}