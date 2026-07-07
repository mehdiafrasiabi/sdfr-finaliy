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
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:16:56',
                'updated_at' => '2026-07-06 15:07:19',
            ),
            1 => 
            array (
                'id' => 3,
                'cc_subject_id' => 1,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:17:04',
                'updated_at' => '2026-07-06 15:07:32',
            ),
            2 => 
            array (
                'id' => 4,
                'cc_subject_id' => 1,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:17:21',
                'updated_at' => '2026-07-06 15:08:06',
            ),
            3 => 
            array (
                'id' => 539,
                'cc_subject_id' => 29,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:53:17',
                'updated_at' => '2026-07-06 15:53:29',
            ),
            4 => 
            array (
                'id' => 5,
                'cc_subject_id' => 1,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:17:32',
                'updated_at' => '2026-07-06 15:08:22',
            ),
            5 => 
            array (
                'id' => 6,
                'cc_subject_id' => 1,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:17:41',
                'updated_at' => '2026-07-06 15:08:41',
            ),
            6 => 
            array (
                'id' => 7,
                'cc_subject_id' => 2,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:41:03',
                'updated_at' => '2026-07-06 15:09:09',
            ),
            7 => 
            array (
                'id' => 8,
                'cc_subject_id' => 2,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:41:14',
                'updated_at' => '2026-07-06 15:09:43',
            ),
            8 => 
            array (
                'id' => 9,
                'cc_subject_id' => 2,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:41:27',
                'updated_at' => '2026-07-06 15:10:01',
            ),
            9 => 
            array (
                'id' => 10,
                'cc_subject_id' => 3,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:45:18',
                'updated_at' => '2026-07-06 15:10:55',
            ),
            10 => 
            array (
                'id' => 11,
                'cc_subject_id' => 3,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:45:27',
                'updated_at' => '2026-07-06 15:11:05',
            ),
            11 => 
            array (
                'id' => 12,
                'cc_subject_id' => 3,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:46:44',
                'updated_at' => '2026-07-06 15:11:18',
            ),
            12 => 
            array (
                'id' => 13,
                'cc_subject_id' => 4,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:51:15',
                'updated_at' => '2026-07-06 15:11:49',
            ),
            13 => 
            array (
                'id' => 14,
                'cc_subject_id' => 4,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:51:32',
                'updated_at' => '2026-07-06 15:12:00',
            ),
            14 => 
            array (
                'id' => 15,
                'cc_subject_id' => 4,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:51:42',
                'updated_at' => '2026-07-06 15:12:12',
            ),
            15 => 
            array (
                'id' => 16,
                'cc_subject_id' => 4,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:51:56',
                'updated_at' => '2026-07-06 15:12:24',
            ),
            16 => 
            array (
                'id' => 17,
                'cc_subject_id' => 4,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:52:18',
                'updated_at' => '2026-07-06 15:12:34',
            ),
            17 => 
            array (
                'id' => 18,
                'cc_subject_id' => 4,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 16:52:43',
                'updated_at' => '2026-07-06 15:12:53',
            ),
            18 => 
            array (
                'id' => 19,
                'cc_subject_id' => 5,
                'name' => 'فصل 1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:05:45',
                'updated_at' => '2026-02-07 17:08:19',
            ),
            19 => 
            array (
                'id' => 20,
                'cc_subject_id' => 5,
                'name' => 'فصل 2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:08:31',
                'updated_at' => '2026-02-07 17:08:31',
            ),
            20 => 
            array (
                'id' => 21,
                'cc_subject_id' => 5,
                'name' => 'فصل 3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:08:41',
                'updated_at' => '2026-02-07 17:08:41',
            ),
            21 => 
            array (
                'id' => 22,
                'cc_subject_id' => 5,
                'name' => 'فصل 4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:08:53',
                'updated_at' => '2026-02-07 17:08:53',
            ),
            22 => 
            array (
                'id' => 24,
                'cc_subject_id' => 6,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:11:48',
                'updated_at' => '2026-07-06 15:13:19',
            ),
            23 => 
            array (
                'id' => 25,
                'cc_subject_id' => 6,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:11:58',
                'updated_at' => '2026-07-06 15:13:28',
            ),
            24 => 
            array (
                'id' => 26,
                'cc_subject_id' => 6,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:12:06',
                'updated_at' => '2026-07-06 15:13:38',
            ),
            25 => 
            array (
                'id' => 27,
                'cc_subject_id' => 6,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:15:30',
                'updated_at' => '2026-07-06 15:13:48',
            ),
            26 => 
            array (
                'id' => 28,
                'cc_subject_id' => 6,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:15:44',
                'updated_at' => '2026-07-06 15:14:03',
            ),
            27 => 
            array (
                'id' => 29,
                'cc_subject_id' => 6,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:15:51',
                'updated_at' => '2026-07-06 15:14:19',
            ),
            28 => 
            array (
                'id' => 30,
                'cc_subject_id' => 6,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:16:07',
                'updated_at' => '2026-07-06 15:14:46',
            ),
            29 => 
            array (
                'id' => 31,
                'cc_subject_id' => 6,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:17:35',
                'updated_at' => '2026-07-06 15:14:57',
            ),
            30 => 
            array (
                'id' => 32,
                'cc_subject_id' => 7,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:22:46',
                'updated_at' => '2026-07-06 15:15:32',
            ),
            31 => 
            array (
                'id' => 33,
                'cc_subject_id' => 7,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:22:52',
                'updated_at' => '2026-07-06 15:15:42',
            ),
            32 => 
            array (
                'id' => 34,
                'cc_subject_id' => 7,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:22:59',
                'updated_at' => '2026-07-06 15:15:54',
            ),
            33 => 
            array (
                'id' => 35,
                'cc_subject_id' => 7,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:23:21',
                'updated_at' => '2026-07-06 15:16:06',
            ),
            34 => 
            array (
                'id' => 36,
                'cc_subject_id' => 8,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:30:18',
                'updated_at' => '2026-07-06 15:16:36',
            ),
            35 => 
            array (
                'id' => 37,
                'cc_subject_id' => 8,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:30:26',
                'updated_at' => '2026-07-06 15:16:47',
            ),
            36 => 
            array (
                'id' => 38,
                'cc_subject_id' => 8,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:30:41',
                'updated_at' => '2026-07-06 15:17:00',
            ),
            37 => 
            array (
                'id' => 39,
                'cc_subject_id' => 8,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:30:53',
                'updated_at' => '2026-07-06 15:17:14',
            ),
            38 => 
            array (
                'id' => 40,
                'cc_subject_id' => 8,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:31:02',
                'updated_at' => '2026-07-06 15:17:28',
            ),
            39 => 
            array (
                'id' => 41,
                'cc_subject_id' => 8,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:31:27',
                'updated_at' => '2026-07-06 15:17:50',
            ),
            40 => 
            array (
                'id' => 42,
                'cc_subject_id' => 8,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:31:36',
                'updated_at' => '2026-07-06 15:18:14',
            ),
            41 => 
            array (
                'id' => 43,
                'cc_subject_id' => 8,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:31:56',
                'updated_at' => '2026-07-06 15:18:35',
            ),
            42 => 
            array (
                'id' => 44,
                'cc_subject_id' => 8,
                'name' => 'فصل9',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:09',
                'updated_at' => '2026-07-06 15:18:49',
            ),
            43 => 
            array (
                'id' => 45,
                'cc_subject_id' => 8,
                'name' => 'فصل10',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:32:32',
                'updated_at' => '2026-07-06 15:19:07',
            ),
            44 => 
            array (
                'id' => 46,
                'cc_subject_id' => 9,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:38:01',
                'updated_at' => '2026-07-06 15:19:37',
            ),
            45 => 
            array (
                'id' => 47,
                'cc_subject_id' => 9,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:38:08',
                'updated_at' => '2026-07-06 15:19:48',
            ),
            46 => 
            array (
                'id' => 48,
                'cc_subject_id' => 9,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:38:14',
                'updated_at' => '2026-07-06 15:20:01',
            ),
            47 => 
            array (
                'id' => 49,
                'cc_subject_id' => 10,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:54:10',
                'updated_at' => '2026-07-06 15:20:28',
            ),
            48 => 
            array (
                'id' => 50,
                'cc_subject_id' => 10,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:54:25',
                'updated_at' => '2026-07-06 15:20:39',
            ),
            49 => 
            array (
                'id' => 538,
                'cc_subject_id' => 29,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:53:09',
                'updated_at' => '2026-07-06 15:53:09',
            ),
            50 => 
            array (
                'id' => 51,
                'cc_subject_id' => 10,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:54:38',
                'updated_at' => '2026-07-06 15:20:52',
            ),
            51 => 
            array (
                'id' => 52,
                'cc_subject_id' => 10,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:54:51',
                'updated_at' => '2026-07-06 15:21:04',
            ),
            52 => 
            array (
                'id' => 53,
                'cc_subject_id' => 10,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:55:00',
                'updated_at' => '2026-07-06 15:21:20',
            ),
            53 => 
            array (
                'id' => 54,
                'cc_subject_id' => 10,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 17:55:57',
                'updated_at' => '2026-07-06 15:21:33',
            ),
            54 => 
            array (
                'id' => 55,
                'cc_subject_id' => 11,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:02:12',
                'updated_at' => '2026-07-06 15:22:17',
            ),
            55 => 
            array (
                'id' => 56,
                'cc_subject_id' => 11,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:02:21',
                'updated_at' => '2026-07-06 15:22:27',
            ),
            56 => 
            array (
                'id' => 57,
                'cc_subject_id' => 11,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:02:29',
                'updated_at' => '2026-07-06 15:22:39',
            ),
            57 => 
            array (
                'id' => 58,
                'cc_subject_id' => 11,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:02:36',
                'updated_at' => '2026-07-06 15:24:12',
            ),
            58 => 
            array (
                'id' => 59,
                'cc_subject_id' => 11,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:02:44',
                'updated_at' => '2026-07-06 15:24:20',
            ),
            59 => 
            array (
                'id' => 60,
                'cc_subject_id' => 11,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:02:55',
                'updated_at' => '2026-07-06 15:24:41',
            ),
            60 => 
            array (
                'id' => 61,
                'cc_subject_id' => 11,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:03:05',
                'updated_at' => '2026-07-06 15:24:56',
            ),
            61 => 
            array (
                'id' => 62,
                'cc_subject_id' => 11,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:03:15',
                'updated_at' => '2026-07-06 15:25:13',
            ),
            62 => 
            array (
                'id' => 63,
                'cc_subject_id' => 11,
                'name' => 'فصل9',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:03:29',
                'updated_at' => '2026-07-06 15:25:35',
            ),
            63 => 
            array (
                'id' => 64,
                'cc_subject_id' => 11,
                'name' => 'فصل10',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:03:41',
                'updated_at' => '2026-07-06 15:26:26',
            ),
            64 => 
            array (
                'id' => 65,
                'cc_subject_id' => 116,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:31:36',
                'updated_at' => '2026-07-06 16:57:25',
            ),
            65 => 
            array (
                'id' => 66,
                'cc_subject_id' => 116,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:31:52',
                'updated_at' => '2026-07-06 16:57:36',
            ),
            66 => 
            array (
                'id' => 67,
                'cc_subject_id' => 116,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:32:07',
                'updated_at' => '2026-07-06 16:57:49',
            ),
            67 => 
            array (
                'id' => 68,
                'cc_subject_id' => 116,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:32:36',
                'updated_at' => '2026-07-06 16:58:02',
            ),
            68 => 
            array (
                'id' => 69,
                'cc_subject_id' => 116,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:32:56',
                'updated_at' => '2026-07-06 16:58:26',
            ),
            69 => 
            array (
                'id' => 70,
                'cc_subject_id' => 117,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:43:01',
                'updated_at' => '2026-07-06 16:58:52',
            ),
            70 => 
            array (
                'id' => 71,
                'cc_subject_id' => 117,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:43:09',
                'updated_at' => '2026-07-06 16:59:02',
            ),
            71 => 
            array (
                'id' => 72,
                'cc_subject_id' => 117,
                'name' => 'فصل3‎',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:43:20',
                'updated_at' => '2026-07-06 16:59:18',
            ),
            72 => 
            array (
                'id' => 73,
                'cc_subject_id' => 118,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:46:15',
                'updated_at' => '2026-07-06 17:00:08',
            ),
            73 => 
            array (
                'id' => 74,
                'cc_subject_id' => 118,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:46:21',
                'updated_at' => '2026-07-06 17:00:20',
            ),
            74 => 
            array (
                'id' => 75,
                'cc_subject_id' => 118,
                'name' => '‎فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:46:37',
                'updated_at' => '2026-07-06 17:00:32',
            ),
            75 => 
            array (
                'id' => 76,
                'cc_subject_id' => 119,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:51:09',
                'updated_at' => '2026-07-06 17:00:51',
            ),
            76 => 
            array (
                'id' => 77,
                'cc_subject_id' => 119,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:51:19',
                'updated_at' => '2026-07-06 17:01:02',
            ),
            77 => 
            array (
                'id' => 619,
                'cc_subject_id' => 41,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:09:10',
                'updated_at' => '2026-07-06 18:09:18',
            ),
            78 => 
            array (
                'id' => 78,
                'cc_subject_id' => 119,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:51:30',
                'updated_at' => '2026-07-06 17:01:13',
            ),
            79 => 
            array (
                'id' => 79,
                'cc_subject_id' => 119,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:51:46',
                'updated_at' => '2026-07-06 17:01:24',
            ),
            80 => 
            array (
                'id' => 618,
                'cc_subject_id' => 41,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:08:53',
                'updated_at' => '2026-07-06 18:08:53',
            ),
            81 => 
            array (
                'id' => 80,
                'cc_subject_id' => 120,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:58:15',
                'updated_at' => '2026-07-06 17:01:42',
            ),
            82 => 
            array (
                'id' => 81,
                'cc_subject_id' => 120,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:58:26',
                'updated_at' => '2026-07-06 17:01:53',
            ),
            83 => 
            array (
                'id' => 82,
                'cc_subject_id' => 120,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:58:34',
                'updated_at' => '2026-07-06 17:02:05',
            ),
            84 => 
            array (
                'id' => 83,
                'cc_subject_id' => 120,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 18:58:43',
                'updated_at' => '2026-07-06 17:02:34',
            ),
            85 => 
            array (
                'id' => 84,
                'cc_subject_id' => 122,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:25:24',
                'updated_at' => '2026-07-06 17:03:29',
            ),
            86 => 
            array (
                'id' => 85,
                'cc_subject_id' => 122,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:25:32',
                'updated_at' => '2026-07-06 17:03:39',
            ),
            87 => 
            array (
                'id' => 86,
                'cc_subject_id' => 122,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:25:44',
                'updated_at' => '2026-07-06 17:04:34',
            ),
            88 => 
            array (
                'id' => 87,
                'cc_subject_id' => 122,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:25:53',
                'updated_at' => '2026-07-06 17:04:42',
            ),
            89 => 
            array (
                'id' => 88,
                'cc_subject_id' => 122,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:26:08',
                'updated_at' => '2026-07-06 17:04:49',
            ),
            90 => 
            array (
                'id' => 89,
                'cc_subject_id' => 122,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:26:20',
                'updated_at' => '2026-07-06 17:04:56',
            ),
            91 => 
            array (
                'id' => 90,
                'cc_subject_id' => 122,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:26:31',
                'updated_at' => '2026-07-06 17:05:27',
            ),
            92 => 
            array (
                'id' => 91,
                'cc_subject_id' => 122,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:26:42',
                'updated_at' => '2026-07-06 17:05:47',
            ),
            93 => 
            array (
                'id' => 92,
                'cc_subject_id' => 124,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:55:50',
                'updated_at' => '2026-07-06 17:06:44',
            ),
            94 => 
            array (
                'id' => 93,
                'cc_subject_id' => 124,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:55:58',
                'updated_at' => '2026-07-06 17:06:58',
            ),
            95 => 
            array (
                'id' => 94,
                'cc_subject_id' => 124,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:56:06',
                'updated_at' => '2026-07-06 17:07:09',
            ),
            96 => 
            array (
                'id' => 95,
                'cc_subject_id' => 124,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:56:17',
                'updated_at' => '2026-07-06 17:07:20',
            ),
            97 => 
            array (
                'id' => 96,
                'cc_subject_id' => 124,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:56:25',
                'updated_at' => '2026-07-06 17:07:31',
            ),
            98 => 
            array (
                'id' => 97,
                'cc_subject_id' => 124,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:56:36',
                'updated_at' => '2026-07-06 17:07:44',
            ),
            99 => 
            array (
                'id' => 98,
                'cc_subject_id' => 124,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:56:53',
                'updated_at' => '2026-07-06 17:08:00',
            ),
            100 => 
            array (
                'id' => 617,
                'cc_subject_id' => 41,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:08:42',
                'updated_at' => '2026-07-06 18:08:42',
            ),
            101 => 
            array (
                'id' => 99,
                'cc_subject_id' => 124,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:57:03',
                'updated_at' => '2026-07-06 17:08:13',
            ),
            102 => 
            array (
                'id' => 100,
                'cc_subject_id' => 124,
                'name' => 'فصل9',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:57:14',
                'updated_at' => '2026-07-06 17:08:24',
            ),
            103 => 
            array (
                'id' => 101,
                'cc_subject_id' => 124,
                'name' => 'فصل10',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:57:27',
                'updated_at' => '2026-07-06 17:08:40',
            ),
            104 => 
            array (
                'id' => 102,
                'cc_subject_id' => 124,
                'name' => 'فصل11',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:57:37',
                'updated_at' => '2026-07-06 17:08:54',
            ),
            105 => 
            array (
                'id' => 103,
                'cc_subject_id' => 124,
                'name' => 'فصل12',
                'order' => 11,
                'is_active' => 1,
                'created_at' => '2026-02-07 19:57:49',
                'updated_at' => '2026-07-06 17:09:10',
            ),
            106 => 
            array (
                'id' => 104,
                'cc_subject_id' => 125,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:09:11',
                'updated_at' => '2026-07-06 17:11:06',
            ),
            107 => 
            array (
                'id' => 105,
                'cc_subject_id' => 125,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:09:23',
                'updated_at' => '2026-07-06 17:11:16',
            ),
            108 => 
            array (
                'id' => 106,
                'cc_subject_id' => 125,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:09:30',
                'updated_at' => '2026-07-06 17:11:28',
            ),
            109 => 
            array (
                'id' => 107,
                'cc_subject_id' => 125,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:09:41',
                'updated_at' => '2026-07-06 17:11:40',
            ),
            110 => 
            array (
                'id' => 108,
                'cc_subject_id' => 125,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:09:54',
                'updated_at' => '2026-07-06 17:11:52',
            ),
            111 => 
            array (
                'id' => 109,
                'cc_subject_id' => 125,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:10:01',
                'updated_at' => '2026-07-06 17:12:04',
            ),
            112 => 
            array (
                'id' => 110,
                'cc_subject_id' => 125,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:10:11',
                'updated_at' => '2026-07-06 17:12:18',
            ),
            113 => 
            array (
                'id' => 111,
                'cc_subject_id' => 127,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:53:56',
                'updated_at' => '2026-07-06 17:12:45',
            ),
            114 => 
            array (
                'id' => 112,
                'cc_subject_id' => 127,
                'name' => 'فصل2',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:54:02',
                'updated_at' => '2026-07-06 17:12:58',
            ),
            115 => 
            array (
                'id' => 113,
                'cc_subject_id' => 127,
                'name' => 'فصل3',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:54:07',
                'updated_at' => '2026-07-06 17:13:11',
            ),
            116 => 
            array (
                'id' => 114,
                'cc_subject_id' => 127,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:54:19',
                'updated_at' => '2026-07-06 17:14:03',
            ),
            117 => 
            array (
                'id' => 115,
                'cc_subject_id' => 127,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:54:30',
                'updated_at' => '2026-07-06 17:14:14',
            ),
            118 => 
            array (
                'id' => 116,
                'cc_subject_id' => 127,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:54:40',
                'updated_at' => '2026-07-06 17:14:32',
            ),
            119 => 
            array (
                'id' => 117,
                'cc_subject_id' => 127,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:54:48',
                'updated_at' => '2026-07-06 17:14:46',
            ),
            120 => 
            array (
                'id' => 118,
                'cc_subject_id' => 127,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:54:58',
                'updated_at' => '2026-07-06 17:15:00',
            ),
            121 => 
            array (
                'id' => 119,
                'cc_subject_id' => 127,
                'name' => 'فصل9',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:55:04',
                'updated_at' => '2026-07-06 17:15:12',
            ),
            122 => 
            array (
                'id' => 120,
                'cc_subject_id' => 127,
                'name' => 'فصل10',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:55:13',
                'updated_at' => '2026-07-06 17:15:29',
            ),
            123 => 
            array (
                'id' => 121,
                'cc_subject_id' => 127,
                'name' => 'فصل11',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:55:25',
                'updated_at' => '2026-07-06 17:15:53',
            ),
            124 => 
            array (
                'id' => 122,
                'cc_subject_id' => 127,
                'name' => 'فصل12',
                'order' => 11,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:55:36',
                'updated_at' => '2026-07-06 17:16:04',
            ),
            125 => 
            array (
                'id' => 123,
                'cc_subject_id' => 127,
                'name' => 'فصل13',
                'order' => 12,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:55:50',
                'updated_at' => '2026-07-06 17:16:15',
            ),
            126 => 
            array (
                'id' => 124,
                'cc_subject_id' => 127,
                'name' => 'فصل14',
                'order' => 13,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:55:59',
                'updated_at' => '2026-07-06 17:16:25',
            ),
            127 => 
            array (
                'id' => 125,
                'cc_subject_id' => 127,
                'name' => 'فصل15',
                'order' => 14,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:56:09',
                'updated_at' => '2026-07-06 17:16:35',
            ),
            128 => 
            array (
                'id' => 126,
                'cc_subject_id' => 127,
                'name' => 'فصل16',
                'order' => 15,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:56:17',
                'updated_at' => '2026-07-06 17:16:44',
            ),
            129 => 
            array (
                'id' => 127,
                'cc_subject_id' => 127,
                'name' => 'فصل17',
                'order' => 16,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:56:26',
                'updated_at' => '2026-07-06 17:17:02',
            ),
            130 => 
            array (
                'id' => 128,
                'cc_subject_id' => 127,
                'name' => 'فصل18',
                'order' => 17,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:56:42',
                'updated_at' => '2026-07-06 17:17:22',
            ),
            131 => 
            array (
                'id' => 129,
                'cc_subject_id' => 127,
                'name' => 'فصل19',
                'order' => 18,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:57:03',
                'updated_at' => '2026-07-06 17:17:33',
            ),
            132 => 
            array (
                'id' => 130,
                'cc_subject_id' => 127,
                'name' => 'فصل20',
                'order' => 19,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:57:17',
                'updated_at' => '2026-07-06 17:17:50',
            ),
            133 => 
            array (
                'id' => 131,
                'cc_subject_id' => 127,
                'name' => 'فصل21',
                'order' => 20,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:57:51',
                'updated_at' => '2026-07-06 17:18:14',
            ),
            134 => 
            array (
                'id' => 132,
                'cc_subject_id' => 127,
                'name' => 'فصل22',
                'order' => 21,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:58:44',
                'updated_at' => '2026-07-06 17:18:26',
            ),
            135 => 
            array (
                'id' => 133,
                'cc_subject_id' => 127,
                'name' => 'فصل23',
                'order' => 22,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:58:56',
                'updated_at' => '2026-07-06 17:18:46',
            ),
            136 => 
            array (
                'id' => 134,
                'cc_subject_id' => 127,
                'name' => ' فصل24',
                'order' => 23,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:59:18',
                'updated_at' => '2026-07-06 17:19:01',
            ),
            137 => 
            array (
                'id' => 135,
                'cc_subject_id' => 127,
                'name' => 'فصل25',
                'order' => 24,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:59:34',
                'updated_at' => '2026-07-06 17:19:13',
            ),
            138 => 
            array (
                'id' => 136,
                'cc_subject_id' => 127,
                'name' => 'فصل26',
                'order' => 26,
                'is_active' => 1,
                'created_at' => '2026-02-07 20:59:52',
                'updated_at' => '2026-07-06 17:19:25',
            ),
            139 => 
            array (
                'id' => 137,
                'cc_subject_id' => 128,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:08:19',
                'updated_at' => '2026-07-06 17:19:47',
            ),
            140 => 
            array (
                'id' => 138,
                'cc_subject_id' => 128,
                'name' => 'فصل2',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:08:25',
                'updated_at' => '2026-07-06 17:19:57',
            ),
            141 => 
            array (
                'id' => 139,
                'cc_subject_id' => 128,
                'name' => 'فصل3',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:08:29',
                'updated_at' => '2026-07-06 17:20:09',
            ),
            142 => 
            array (
                'id' => 140,
                'cc_subject_id' => 130,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:11:45',
                'updated_at' => '2026-07-06 17:21:10',
            ),
            143 => 
            array (
                'id' => 141,
                'cc_subject_id' => 130,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:11:53',
                'updated_at' => '2026-07-06 17:21:21',
            ),
            144 => 
            array (
                'id' => 616,
                'cc_subject_id' => 41,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:08:30',
                'updated_at' => '2026-07-06 18:08:30',
            ),
            145 => 
            array (
                'id' => 142,
                'cc_subject_id' => 130,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:12:01',
                'updated_at' => '2026-07-06 17:21:32',
            ),
            146 => 
            array (
                'id' => 143,
                'cc_subject_id' => 130,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:12:10',
                'updated_at' => '2026-07-06 17:21:42',
            ),
            147 => 
            array (
                'id' => 144,
                'cc_subject_id' => 130,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:12:20',
                'updated_at' => '2026-07-06 17:21:53',
            ),
            148 => 
            array (
                'id' => 145,
                'cc_subject_id' => 130,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:12:34',
                'updated_at' => '2026-07-06 17:22:06',
            ),
            149 => 
            array (
                'id' => 146,
                'cc_subject_id' => 130,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:12:50',
                'updated_at' => '2026-07-06 17:22:19',
            ),
            150 => 
            array (
                'id' => 147,
                'cc_subject_id' => 131,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:26:08',
                'updated_at' => '2026-07-06 17:22:56',
            ),
            151 => 
            array (
                'id' => 148,
                'cc_subject_id' => 131,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:26:13',
                'updated_at' => '2026-07-06 17:23:05',
            ),
            152 => 
            array (
                'id' => 149,
                'cc_subject_id' => 131,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:26:22',
                'updated_at' => '2026-07-06 17:23:15',
            ),
            153 => 
            array (
                'id' => 150,
                'cc_subject_id' => 131,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:26:46',
                'updated_at' => '2026-07-06 17:23:26',
            ),
            154 => 
            array (
                'id' => 151,
                'cc_subject_id' => 131,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:27:02',
                'updated_at' => '2026-07-06 17:23:41',
            ),
            155 => 
            array (
                'id' => 152,
                'cc_subject_id' => 131,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:27:13',
                'updated_at' => '2026-07-06 17:23:53',
            ),
            156 => 
            array (
                'id' => 153,
                'cc_subject_id' => 131,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-07 21:27:20',
                'updated_at' => '2026-07-06 17:24:03',
            ),
            157 => 
            array (
                'id' => 154,
                'cc_subject_id' => 100,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:49:21',
                'updated_at' => '2026-02-27 21:32:35',
            ),
            158 => 
            array (
                'id' => 155,
                'cc_subject_id' => 100,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:49:33',
                'updated_at' => '2026-02-27 21:32:41',
            ),
            159 => 
            array (
                'id' => 156,
                'cc_subject_id' => 100,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:49:43',
                'updated_at' => '2026-02-27 21:32:46',
            ),
            160 => 
            array (
                'id' => 157,
                'cc_subject_id' => 100,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:49:52',
                'updated_at' => '2026-02-27 21:32:54',
            ),
            161 => 
            array (
                'id' => 158,
                'cc_subject_id' => 100,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:50:04',
                'updated_at' => '2026-02-27 21:33:00',
            ),
            162 => 
            array (
                'id' => 159,
                'cc_subject_id' => 100,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:50:18',
                'updated_at' => '2026-02-27 21:33:07',
            ),
            163 => 
            array (
                'id' => 160,
                'cc_subject_id' => 100,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 14:50:53',
                'updated_at' => '2026-02-27 21:33:16',
            ),
            164 => 
            array (
                'id' => 161,
                'cc_subject_id' => 101,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:03:43',
                'updated_at' => '2026-02-27 21:35:31',
            ),
            165 => 
            array (
                'id' => 162,
                'cc_subject_id' => 101,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:03:51',
                'updated_at' => '2026-02-27 21:37:44',
            ),
            166 => 
            array (
                'id' => 163,
                'cc_subject_id' => 101,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:04:01',
                'updated_at' => '2026-02-27 21:38:01',
            ),
            167 => 
            array (
                'id' => 164,
                'cc_subject_id' => 101,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:04:13',
                'updated_at' => '2026-02-27 21:36:52',
            ),
            168 => 
            array (
                'id' => 165,
                'cc_subject_id' => 102,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:07:00',
                'updated_at' => '2026-02-27 21:38:29',
            ),
            169 => 
            array (
                'id' => 166,
                'cc_subject_id' => 102,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:07:09',
                'updated_at' => '2026-02-27 21:38:34',
            ),
            170 => 
            array (
                'id' => 167,
                'cc_subject_id' => 102,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:07:25',
                'updated_at' => '2026-02-27 21:38:41',
            ),
            171 => 
            array (
                'id' => 168,
                'cc_subject_id' => 103,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:13:02',
                'updated_at' => '2026-02-27 21:42:26',
            ),
            172 => 
            array (
                'id' => 169,
                'cc_subject_id' => 103,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:13:11',
                'updated_at' => '2026-02-27 21:42:31',
            ),
            173 => 
            array (
                'id' => 170,
                'cc_subject_id' => 103,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:13:22',
                'updated_at' => '2026-02-27 21:42:36',
            ),
            174 => 
            array (
                'id' => 171,
                'cc_subject_id' => 103,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:13:30',
                'updated_at' => '2026-02-27 21:42:41',
            ),
            175 => 
            array (
                'id' => 172,
                'cc_subject_id' => 103,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:13:39',
                'updated_at' => '2026-02-27 21:42:48',
            ),
            176 => 
            array (
                'id' => 173,
                'cc_subject_id' => 105,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:39:04',
                'updated_at' => '2026-02-27 21:42:59',
            ),
            177 => 
            array (
                'id' => 174,
                'cc_subject_id' => 105,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:39:15',
                'updated_at' => '2026-02-27 21:43:06',
            ),
            178 => 
            array (
                'id' => 175,
                'cc_subject_id' => 105,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:39:25',
                'updated_at' => '2026-02-27 21:43:12',
            ),
            179 => 
            array (
                'id' => 176,
                'cc_subject_id' => 105,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:39:33',
                'updated_at' => '2026-02-27 21:43:19',
            ),
            180 => 
            array (
                'id' => 177,
                'cc_subject_id' => 105,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:39:44',
                'updated_at' => '2026-02-27 21:43:25',
            ),
            181 => 
            array (
                'id' => 178,
                'cc_subject_id' => 105,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:39:55',
                'updated_at' => '2026-02-27 21:43:33',
            ),
            182 => 
            array (
                'id' => 179,
                'cc_subject_id' => 105,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:40:04',
                'updated_at' => '2026-02-27 21:43:41',
            ),
            183 => 
            array (
                'id' => 180,
                'cc_subject_id' => 105,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:40:17',
                'updated_at' => '2026-02-27 21:43:50',
            ),
            184 => 
            array (
                'id' => 181,
                'cc_subject_id' => 107,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:47:09',
                'updated_at' => '2026-07-07 12:48:16',
            ),
            185 => 
            array (
                'id' => 182,
                'cc_subject_id' => 107,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:47:18',
                'updated_at' => '2026-07-07 12:48:26',
            ),
            186 => 
            array (
                'id' => 183,
                'cc_subject_id' => 107,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 15:47:27',
                'updated_at' => '2026-07-07 12:48:38',
            ),
            187 => 
            array (
                'id' => 184,
                'cc_subject_id' => 107,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:01:48',
                'updated_at' => '2026-07-07 12:48:46',
            ),
            188 => 
            array (
                'id' => 185,
                'cc_subject_id' => 107,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:01:56',
                'updated_at' => '2026-07-07 12:48:57',
            ),
            189 => 
            array (
                'id' => 186,
                'cc_subject_id' => 107,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:02:04',
                'updated_at' => '2026-07-07 12:49:04',
            ),
            190 => 
            array (
                'id' => 187,
                'cc_subject_id' => 107,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:02:13',
                'updated_at' => '2026-07-07 12:49:12',
            ),
            191 => 
            array (
                'id' => 188,
                'cc_subject_id' => 107,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:02:20',
                'updated_at' => '2026-07-07 12:49:18',
            ),
            192 => 
            array (
                'id' => 189,
                'cc_subject_id' => 107,
                'name' => 'فصل9',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:02:30',
                'updated_at' => '2026-07-07 12:49:25',
            ),
            193 => 
            array (
                'id' => 190,
                'cc_subject_id' => 107,
                'name' => 'فصل10',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:02:40',
                'updated_at' => '2026-07-07 12:49:35',
            ),
            194 => 
            array (
                'id' => 191,
                'cc_subject_id' => 107,
                'name' => 'فصل11',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:02:49',
                'updated_at' => '2026-07-07 12:49:45',
            ),
            195 => 
            array (
                'id' => 192,
                'cc_subject_id' => 107,
                'name' => 'فصل12',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:03:01',
                'updated_at' => '2026-07-07 12:49:51',
            ),
            196 => 
            array (
                'id' => 193,
                'cc_subject_id' => 108,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:11:02',
                'updated_at' => '2026-07-06 19:47:12',
            ),
            197 => 
            array (
                'id' => 194,
                'cc_subject_id' => 108,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:11:07',
                'updated_at' => '2026-07-06 19:47:23',
            ),
            198 => 
            array (
                'id' => 195,
                'cc_subject_id' => 108,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:11:12',
                'updated_at' => '2026-07-06 19:47:33',
            ),
            199 => 
            array (
                'id' => 196,
                'cc_subject_id' => 108,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:11:16',
                'updated_at' => '2026-07-06 19:47:42',
            ),
            200 => 
            array (
                'id' => 197,
                'cc_subject_id' => 108,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:11:23',
                'updated_at' => '2026-07-06 19:47:51',
            ),
            201 => 
            array (
                'id' => 198,
                'cc_subject_id' => 108,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:11:27',
                'updated_at' => '2026-07-06 19:48:01',
            ),
            202 => 
            array (
                'id' => 199,
                'cc_subject_id' => 108,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:11:31',
                'updated_at' => '2026-07-06 19:48:11',
            ),
            203 => 
            array (
                'id' => 200,
                'cc_subject_id' => 108,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:11:38',
                'updated_at' => '2026-07-06 19:48:20',
            ),
            204 => 
            array (
                'id' => 205,
                'cc_subject_id' => 110,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:40:14',
                'updated_at' => '2026-02-27 21:48:48',
            ),
            205 => 
            array (
                'id' => 206,
                'cc_subject_id' => 110,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:40:35',
                'updated_at' => '2026-02-27 21:48:54',
            ),
            206 => 
            array (
                'id' => 207,
                'cc_subject_id' => 110,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:40:43',
                'updated_at' => '2026-02-27 21:49:00',
            ),
            207 => 
            array (
                'id' => 208,
                'cc_subject_id' => 111,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:48:52',
                'updated_at' => '2026-07-06 19:48:47',
            ),
            208 => 
            array (
                'id' => 209,
                'cc_subject_id' => 111,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:48:59',
                'updated_at' => '2026-07-06 19:48:58',
            ),
            209 => 
            array (
                'id' => 210,
                'cc_subject_id' => 111,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:49:04',
                'updated_at' => '2026-07-06 19:49:09',
            ),
            210 => 
            array (
                'id' => 211,
                'cc_subject_id' => 111,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 16:49:12',
                'updated_at' => '2026-07-06 19:49:19',
            ),
            211 => 
            array (
                'id' => 212,
                'cc_subject_id' => 114,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:20:34',
                'updated_at' => '2026-02-27 21:51:13',
            ),
            212 => 
            array (
                'id' => 213,
                'cc_subject_id' => 114,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:30:46',
                'updated_at' => '2026-02-27 21:51:18',
            ),
            213 => 
            array (
                'id' => 214,
                'cc_subject_id' => 114,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:30:59',
                'updated_at' => '2026-02-27 21:51:23',
            ),
            214 => 
            array (
                'id' => 215,
                'cc_subject_id' => 114,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 17:31:09',
                'updated_at' => '2026-02-27 21:51:31',
            ),
            215 => 
            array (
                'id' => 216,
                'cc_subject_id' => 132,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:27:18',
            ),
            216 => 
            array (
                'id' => 217,
                'cc_subject_id' => 132,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:27:30',
            ),
            217 => 
            array (
                'id' => 218,
                'cc_subject_id' => 132,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:27:45',
            ),
            218 => 
            array (
                'id' => 537,
                'cc_subject_id' => 28,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:51:40',
                'updated_at' => '2026-07-06 15:52:02',
            ),
            219 => 
            array (
                'id' => 219,
                'cc_subject_id' => 132,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:28:01',
            ),
            220 => 
            array (
                'id' => 220,
                'cc_subject_id' => 132,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:28:15',
            ),
            221 => 
            array (
                'id' => 227,
                'cc_subject_id' => 17,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:28:42',
            ),
            222 => 
            array (
                'id' => 228,
                'cc_subject_id' => 17,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:28:55',
            ),
            223 => 
            array (
                'id' => 229,
                'cc_subject_id' => 17,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:29:09',
            ),
            224 => 
            array (
                'id' => 230,
                'cc_subject_id' => 17,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:29:21',
            ),
            225 => 
            array (
                'id' => 231,
                'cc_subject_id' => 17,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:29:34',
            ),
            226 => 
            array (
                'id' => 232,
                'cc_subject_id' => 17,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:29:50',
            ),
            227 => 
            array (
                'id' => 233,
                'cc_subject_id' => 18,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:33:51',
            ),
            228 => 
            array (
                'id' => 234,
                'cc_subject_id' => 18,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:34:01',
            ),
            229 => 
            array (
                'id' => 235,
                'cc_subject_id' => 18,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:34:08',
            ),
            230 => 
            array (
                'id' => 236,
                'cc_subject_id' => 18,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:34:15',
            ),
            231 => 
            array (
                'id' => 237,
                'cc_subject_id' => 19,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:34:44',
            ),
            232 => 
            array (
                'id' => 238,
                'cc_subject_id' => 19,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:34:54',
            ),
            233 => 
            array (
                'id' => 239,
                'cc_subject_id' => 19,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:35:15',
            ),
            234 => 
            array (
                'id' => 240,
                'cc_subject_id' => 19,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:35:29',
            ),
            235 => 
            array (
                'id' => 241,
                'cc_subject_id' => 19,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:35:48',
            ),
            236 => 
            array (
                'id' => 242,
                'cc_subject_id' => 19,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:36:01',
            ),
            237 => 
            array (
                'id' => 243,
                'cc_subject_id' => 19,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:36:12',
            ),
            238 => 
            array (
                'id' => 244,
                'cc_subject_id' => 19,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:36:28',
            ),
            239 => 
            array (
                'id' => 245,
                'cc_subject_id' => 20,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:36:55',
            ),
            240 => 
            array (
                'id' => 246,
                'cc_subject_id' => 20,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:37:49',
            ),
            241 => 
            array (
                'id' => 247,
                'cc_subject_id' => 20,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:38:11',
            ),
            242 => 
            array (
                'id' => 248,
                'cc_subject_id' => 20,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:38:26',
            ),
            243 => 
            array (
                'id' => 249,
                'cc_subject_id' => 21,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:38:43',
            ),
            244 => 
            array (
                'id' => 250,
                'cc_subject_id' => 21,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:38:56',
            ),
            245 => 
            array (
                'id' => 251,
                'cc_subject_id' => 21,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:39:08',
            ),
            246 => 
            array (
                'id' => 252,
                'cc_subject_id' => 21,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:39:19',
            ),
            247 => 
            array (
                'id' => 253,
                'cc_subject_id' => 21,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:39:31',
            ),
            248 => 
            array (
                'id' => 254,
                'cc_subject_id' => 21,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:39:46',
            ),
            249 => 
            array (
                'id' => 255,
                'cc_subject_id' => 21,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:39:58',
            ),
            250 => 
            array (
                'id' => 256,
                'cc_subject_id' => 21,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:40:10',
            ),
            251 => 
            array (
                'id' => 257,
                'cc_subject_id' => 21,
                'name' => 'فصل9',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:40:33',
            ),
            252 => 
            array (
                'id' => 258,
                'cc_subject_id' => 21,
                'name' => 'فصل10',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:40:50',
            ),
            253 => 
            array (
                'id' => 259,
                'cc_subject_id' => 22,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:41:15',
            ),
            254 => 
            array (
                'id' => 260,
                'cc_subject_id' => 22,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:41:31',
            ),
            255 => 
            array (
                'id' => 261,
                'cc_subject_id' => 22,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:41:54',
            ),
            256 => 
            array (
                'id' => 262,
                'cc_subject_id' => 23,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:42:10',
            ),
            257 => 
            array (
                'id' => 263,
                'cc_subject_id' => 23,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:42:20',
            ),
            258 => 
            array (
                'id' => 536,
                'cc_subject_id' => 28,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:51:29',
                'updated_at' => '2026-07-06 15:51:53',
            ),
            259 => 
            array (
                'id' => 264,
                'cc_subject_id' => 23,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:42:31',
            ),
            260 => 
            array (
                'id' => 265,
                'cc_subject_id' => 23,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:42:49',
            ),
            261 => 
            array (
                'id' => 266,
                'cc_subject_id' => 23,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:43:00',
            ),
            262 => 
            array (
                'id' => 267,
                'cc_subject_id' => 23,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:43:12',
            ),
            263 => 
            array (
                'id' => 268,
                'cc_subject_id' => 24,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:44:05',
            ),
            264 => 
            array (
                'id' => 269,
                'cc_subject_id' => 24,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:44:16',
            ),
            265 => 
            array (
                'id' => 270,
                'cc_subject_id' => 24,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:44:29',
            ),
            266 => 
            array (
                'id' => 271,
                'cc_subject_id' => 24,
                'name' => 'فصل4',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:44:44',
            ),
            267 => 
            array (
                'id' => 272,
                'cc_subject_id' => 24,
                'name' => 'فصل5',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:44:56',
            ),
            268 => 
            array (
                'id' => 273,
                'cc_subject_id' => 24,
                'name' => 'فصل6',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:45:12',
            ),
            269 => 
            array (
                'id' => 274,
                'cc_subject_id' => 24,
                'name' => 'فصل7',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:45:23',
            ),
            270 => 
            array (
                'id' => 275,
                'cc_subject_id' => 24,
                'name' => 'فصل8',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:45:37',
            ),
            271 => 
            array (
                'id' => 276,
                'cc_subject_id' => 24,
                'name' => 'فصل9',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:45:49',
            ),
            272 => 
            array (
                'id' => 277,
                'cc_subject_id' => 24,
                'name' => 'فصل11',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 15:46:02',
            ),
            273 => 
            array (
                'id' => 535,
                'cc_subject_id' => 28,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:51:14',
                'updated_at' => '2026-07-06 15:51:14',
            ),
            274 => 
            array (
                'id' => 278,
                'cc_subject_id' => 133,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 21:54:34',
            ),
            275 => 
            array (
                'id' => 279,
                'cc_subject_id' => 133,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 21:54:38',
            ),
            276 => 
            array (
                'id' => 280,
                'cc_subject_id' => 133,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 21:54:44',
            ),
            277 => 
            array (
                'id' => 281,
                'cc_subject_id' => 133,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 21:54:49',
            ),
            278 => 
            array (
                'id' => 282,
                'cc_subject_id' => 133,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 21:54:54',
            ),
            279 => 
            array (
                'id' => 283,
                'cc_subject_id' => 133,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 21:55:00',
            ),
            280 => 
            array (
                'id' => 284,
                'cc_subject_id' => 133,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 21:55:06',
            ),
            281 => 
            array (
                'id' => 289,
                'cc_subject_id' => 135,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 18:44:31',
            ),
            282 => 
            array (
                'id' => 290,
                'cc_subject_id' => 135,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 18:44:48',
            ),
            283 => 
            array (
                'id' => 291,
                'cc_subject_id' => 135,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 18:45:09',
            ),
            284 => 
            array (
                'id' => 292,
                'cc_subject_id' => 136,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 21:56:42',
            ),
            285 => 
            array (
                'id' => 293,
                'cc_subject_id' => 136,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 21:56:48',
            ),
            286 => 
            array (
                'id' => 294,
                'cc_subject_id' => 136,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 21:56:53',
            ),
            287 => 
            array (
                'id' => 295,
                'cc_subject_id' => 136,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 21:56:59',
            ),
            288 => 
            array (
                'id' => 297,
                'cc_subject_id' => 137,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 21:58:59',
            ),
            289 => 
            array (
                'id' => 298,
                'cc_subject_id' => 137,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 21:59:04',
            ),
            290 => 
            array (
                'id' => 299,
                'cc_subject_id' => 137,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 21:59:09',
            ),
            291 => 
            array (
                'id' => 300,
                'cc_subject_id' => 137,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 21:59:13',
            ),
            292 => 
            array (
                'id' => 301,
                'cc_subject_id' => 137,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 21:59:18',
            ),
            293 => 
            array (
                'id' => 302,
                'cc_subject_id' => 137,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 21:59:23',
            ),
            294 => 
            array (
                'id' => 303,
                'cc_subject_id' => 137,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 21:59:29',
            ),
            295 => 
            array (
                'id' => 304,
                'cc_subject_id' => 137,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 21:59:36',
            ),
            296 => 
            array (
                'id' => 305,
                'cc_subject_id' => 91,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:38:58',
            ),
            297 => 
            array (
                'id' => 306,
                'cc_subject_id' => 91,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:39:12',
            ),
            298 => 
            array (
                'id' => 307,
                'cc_subject_id' => 91,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:39:21',
            ),
            299 => 
            array (
                'id' => 308,
                'cc_subject_id' => 91,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:39:30',
            ),
            300 => 
            array (
                'id' => 309,
                'cc_subject_id' => 91,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:39:39',
            ),
            301 => 
            array (
                'id' => 310,
                'cc_subject_id' => 91,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:39:48',
            ),
            302 => 
            array (
                'id' => 311,
                'cc_subject_id' => 91,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:39:59',
            ),
            303 => 
            array (
                'id' => 312,
                'cc_subject_id' => 91,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:40:11',
            ),
            304 => 
            array (
                'id' => 313,
                'cc_subject_id' => 91,
                'name' => 'فصل9',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:40:20',
            ),
            305 => 
            array (
                'id' => 314,
                'cc_subject_id' => 91,
                'name' => 'فصل10',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:40:30',
            ),
            306 => 
            array (
                'id' => 315,
                'cc_subject_id' => 91,
                'name' => 'فصل11',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:40:53',
            ),
            307 => 
            array (
                'id' => 316,
                'cc_subject_id' => 91,
                'name' => 'فصل12',
                'order' => 11,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:41:09',
            ),
            308 => 
            array (
                'id' => 317,
                'cc_subject_id' => 92,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:41:30',
            ),
            309 => 
            array (
                'id' => 318,
                'cc_subject_id' => 92,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:43:13',
            ),
            310 => 
            array (
                'id' => 319,
                'cc_subject_id' => 92,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:43:21',
            ),
            311 => 
            array (
                'id' => 320,
                'cc_subject_id' => 92,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:43:31',
            ),
            312 => 
            array (
                'id' => 321,
                'cc_subject_id' => 92,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:43:40',
            ),
            313 => 
            array (
                'id' => 322,
                'cc_subject_id' => 92,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:43:51',
            ),
            314 => 
            array (
                'id' => 323,
                'cc_subject_id' => 92,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:44:15',
            ),
            315 => 
            array (
                'id' => 324,
                'cc_subject_id' => 92,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:44:37',
            ),
            316 => 
            array (
                'id' => 325,
                'cc_subject_id' => 94,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 22:03:34',
            ),
            317 => 
            array (
                'id' => 326,
                'cc_subject_id' => 94,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 22:03:39',
            ),
            318 => 
            array (
                'id' => 327,
                'cc_subject_id' => 94,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 22:03:43',
            ),
            319 => 
            array (
                'id' => 328,
                'cc_subject_id' => 95,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:45:29',
            ),
            320 => 
            array (
                'id' => 329,
                'cc_subject_id' => 95,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:45:37',
            ),
            321 => 
            array (
                'id' => 330,
                'cc_subject_id' => 95,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:45:53',
            ),
            322 => 
            array (
                'id' => 331,
                'cc_subject_id' => 95,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 19:46:04',
            ),
            323 => 
            array (
                'id' => 332,
                'cc_subject_id' => 98,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 22:06:56',
            ),
            324 => 
            array (
                'id' => 333,
                'cc_subject_id' => 98,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 22:07:01',
            ),
            325 => 
            array (
                'id' => 334,
                'cc_subject_id' => 98,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 22:07:06',
            ),
            326 => 
            array (
                'id' => 335,
                'cc_subject_id' => 98,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-02-27 22:07:12',
            ),
            327 => 
            array (
                'id' => 336,
                'cc_subject_id' => 138,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:25:37',
            ),
            328 => 
            array (
                'id' => 337,
                'cc_subject_id' => 138,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:26:08',
            ),
            329 => 
            array (
                'id' => 338,
                'cc_subject_id' => 138,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:26:19',
            ),
            330 => 
            array (
                'id' => 339,
                'cc_subject_id' => 138,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:26:30',
            ),
            331 => 
            array (
                'id' => 340,
                'cc_subject_id' => 138,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:26:43',
            ),
            332 => 
            array (
                'id' => 344,
                'cc_subject_id' => 69,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:27:01',
            ),
            333 => 
            array (
                'id' => 345,
                'cc_subject_id' => 69,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:27:14',
            ),
            334 => 
            array (
                'id' => 346,
                'cc_subject_id' => 69,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:27:27',
            ),
            335 => 
            array (
                'id' => 615,
                'cc_subject_id' => 41,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:08:13',
                'updated_at' => '2026-07-06 18:08:13',
            ),
            336 => 
            array (
                'id' => 347,
                'cc_subject_id' => 140,
                'name' => ' فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:27:56',
            ),
            337 => 
            array (
                'id' => 348,
                'cc_subject_id' => 140,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:28:06',
            ),
            338 => 
            array (
                'id' => 614,
                'cc_subject_id' => 41,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:07:51',
                'updated_at' => '2026-07-06 18:07:59',
            ),
            339 => 
            array (
                'id' => 349,
                'cc_subject_id' => 140,
                'name' => ' فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:28:17',
            ),
            340 => 
            array (
                'id' => 350,
                'cc_subject_id' => 140,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:28:29',
            ),
            341 => 
            array (
                'id' => 613,
                'cc_subject_id' => 41,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:07:46',
                'updated_at' => '2026-07-06 18:07:46',
            ),
            342 => 
            array (
                'id' => 355,
                'cc_subject_id' => 142,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:28:44',
            ),
            343 => 
            array (
                'id' => 356,
                'cc_subject_id' => 142,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:28:54',
            ),
            344 => 
            array (
                'id' => 357,
                'cc_subject_id' => 142,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:30:04',
            ),
            345 => 
            array (
                'id' => 358,
                'cc_subject_id' => 142,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:29:56',
            ),
            346 => 
            array (
                'id' => 359,
                'cc_subject_id' => 142,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:29:49',
            ),
            347 => 
            array (
                'id' => 360,
                'cc_subject_id' => 142,
                'name' => 'فصل6',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:30:20',
            ),
            348 => 
            array (
                'id' => 361,
                'cc_subject_id' => 142,
                'name' => 'فصل7',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:30:32',
            ),
            349 => 
            array (
                'id' => 362,
                'cc_subject_id' => 142,
                'name' => 'فصل8',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:30:44',
            ),
            350 => 
            array (
                'id' => 363,
                'cc_subject_id' => 75,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:31:11',
            ),
            351 => 
            array (
                'id' => 364,
                'cc_subject_id' => 75,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:31:29',
            ),
            352 => 
            array (
                'id' => 365,
                'cc_subject_id' => 75,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:31:41',
            ),
            353 => 
            array (
                'id' => 366,
                'cc_subject_id' => 75,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:31:52',
            ),
            354 => 
            array (
                'id' => 367,
                'cc_subject_id' => 75,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:32:03',
            ),
            355 => 
            array (
                'id' => 368,
                'cc_subject_id' => 75,
                'name' => ' فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:32:18',
            ),
            356 => 
            array (
                'id' => 369,
                'cc_subject_id' => 75,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:32:33',
            ),
            357 => 
            array (
                'id' => 612,
                'cc_subject_id' => 40,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:06:03',
                'updated_at' => '2026-07-06 18:06:03',
            ),
            358 => 
            array (
                'id' => 370,
                'cc_subject_id' => 75,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:32:43',
            ),
            359 => 
            array (
                'id' => 371,
                'cc_subject_id' => 75,
                'name' => 'فصل9',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:32:52',
            ),
            360 => 
            array (
                'id' => 372,
                'cc_subject_id' => 75,
                'name' => 'فصل10',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:33:08',
            ),
            361 => 
            array (
                'id' => 373,
                'cc_subject_id' => 75,
                'name' => 'فصل11',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:33:30',
            ),
            362 => 
            array (
                'id' => 374,
                'cc_subject_id' => 75,
                'name' => 'فصل12',
                'order' => 11,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:33:41',
            ),
            363 => 
            array (
                'id' => 375,
                'cc_subject_id' => 79,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:36:29',
            ),
            364 => 
            array (
                'id' => 376,
                'cc_subject_id' => 79,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:36:39',
            ),
            365 => 
            array (
                'id' => 377,
                'cc_subject_id' => 79,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:36:49',
            ),
            366 => 
            array (
                'id' => 378,
                'cc_subject_id' => 79,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:36:59',
            ),
            367 => 
            array (
                'id' => 379,
                'cc_subject_id' => 79,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:37:10',
            ),
            368 => 
            array (
                'id' => 380,
                'cc_subject_id' => 79,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:37:23',
            ),
            369 => 
            array (
                'id' => 381,
                'cc_subject_id' => 79,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:37:34',
            ),
            370 => 
            array (
                'id' => 382,
                'cc_subject_id' => 143,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:38:04',
            ),
            371 => 
            array (
                'id' => 383,
                'cc_subject_id' => 143,
                'name' => 'فصل2',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:38:13',
            ),
            372 => 
            array (
                'id' => 384,
                'cc_subject_id' => 143,
                'name' => 'فصل3',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:38:26',
            ),
            373 => 
            array (
                'id' => 385,
                'cc_subject_id' => 143,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:38:36',
            ),
            374 => 
            array (
                'id' => 386,
                'cc_subject_id' => 143,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:38:49',
            ),
            375 => 
            array (
                'id' => 387,
                'cc_subject_id' => 143,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:39:01',
            ),
            376 => 
            array (
                'id' => 388,
                'cc_subject_id' => 143,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:39:12',
            ),
            377 => 
            array (
                'id' => 389,
                'cc_subject_id' => 143,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:39:42',
            ),
            378 => 
            array (
                'id' => 390,
                'cc_subject_id' => 143,
                'name' => 'فصل9',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:39:51',
            ),
            379 => 
            array (
                'id' => 391,
                'cc_subject_id' => 143,
                'name' => 'فصل10',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:40:02',
            ),
            380 => 
            array (
                'id' => 392,
                'cc_subject_id' => 143,
                'name' => 'فصل11',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:40:19',
            ),
            381 => 
            array (
                'id' => 393,
                'cc_subject_id' => 143,
                'name' => 'فصل12',
                'order' => 11,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:40:28',
            ),
            382 => 
            array (
                'id' => 394,
                'cc_subject_id' => 143,
                'name' => 'فصل13',
                'order' => 12,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:40:36',
            ),
            383 => 
            array (
                'id' => 395,
                'cc_subject_id' => 143,
                'name' => 'فصل14',
                'order' => 13,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:40:46',
            ),
            384 => 
            array (
                'id' => 396,
                'cc_subject_id' => 143,
                'name' => 'فصل15',
                'order' => 14,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:40:56',
            ),
            385 => 
            array (
                'id' => 397,
                'cc_subject_id' => 143,
                'name' => 'فصل16',
                'order' => 15,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:41:05',
            ),
            386 => 
            array (
                'id' => 398,
                'cc_subject_id' => 143,
                'name' => 'فصل17',
                'order' => 16,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:41:13',
            ),
            387 => 
            array (
                'id' => 399,
                'cc_subject_id' => 143,
                'name' => 'فصل18',
                'order' => 17,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:41:29',
            ),
            388 => 
            array (
                'id' => 400,
                'cc_subject_id' => 143,
                'name' => 'فصل19',
                'order' => 18,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:41:41',
            ),
            389 => 
            array (
                'id' => 401,
                'cc_subject_id' => 143,
                'name' => 'فصل20',
                'order' => 19,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:41:59',
            ),
            390 => 
            array (
                'id' => 402,
                'cc_subject_id' => 143,
                'name' => 'فصل21',
                'order' => 20,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:42:10',
            ),
            391 => 
            array (
                'id' => 403,
                'cc_subject_id' => 143,
                'name' => 'فصل22',
                'order' => 21,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:42:17',
            ),
            392 => 
            array (
                'id' => 404,
                'cc_subject_id' => 143,
                'name' => 'فصل23',
                'order' => 22,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:42:34',
            ),
            393 => 
            array (
                'id' => 405,
                'cc_subject_id' => 143,
                'name' => 'فصل24',
                'order' => 23,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:42:48',
            ),
            394 => 
            array (
                'id' => 406,
                'cc_subject_id' => 143,
                'name' => 'فصل25',
                'order' => 24,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:43:07',
            ),
            395 => 
            array (
                'id' => 407,
                'cc_subject_id' => 143,
                'name' => 'فصل26',
                'order' => 26,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:43:22',
            ),
            396 => 
            array (
                'id' => 408,
                'cc_subject_id' => 144,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:44:52',
            ),
            397 => 
            array (
                'id' => 409,
                'cc_subject_id' => 144,
                'name' => 'فصل2',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:45:02',
            ),
            398 => 
            array (
                'id' => 410,
                'cc_subject_id' => 144,
                'name' => 'فصل3',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:45:15',
            ),
            399 => 
            array (
                'id' => 411,
                'cc_subject_id' => 145,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:53:44',
            ),
            400 => 
            array (
                'id' => 412,
                'cc_subject_id' => 145,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:53:56',
            ),
            401 => 
            array (
                'id' => 611,
                'cc_subject_id' => 40,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:05:46',
                'updated_at' => '2026-07-06 18:05:55',
            ),
            402 => 
            array (
                'id' => 413,
                'cc_subject_id' => 145,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:54:06',
            ),
            403 => 
            array (
                'id' => 414,
                'cc_subject_id' => 145,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:54:17',
            ),
            404 => 
            array (
                'id' => 415,
                'cc_subject_id' => 145,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:54:26',
            ),
            405 => 
            array (
                'id' => 416,
                'cc_subject_id' => 145,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:54:39',
            ),
            406 => 
            array (
                'id' => 610,
                'cc_subject_id' => 40,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:05:38',
                'updated_at' => '2026-07-06 18:05:38',
            ),
            407 => 
            array (
                'id' => 417,
                'cc_subject_id' => 145,
                'name' => ' فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:54:59',
            ),
            408 => 
            array (
                'id' => 418,
                'cc_subject_id' => 83,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:55:27',
            ),
            409 => 
            array (
                'id' => 419,
                'cc_subject_id' => 83,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:55:36',
            ),
            410 => 
            array (
                'id' => 420,
                'cc_subject_id' => 83,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:55:47',
            ),
            411 => 
            array (
                'id' => 421,
                'cc_subject_id' => 83,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:55:57',
            ),
            412 => 
            array (
                'id' => 422,
                'cc_subject_id' => 83,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:56:07',
            ),
            413 => 
            array (
                'id' => 423,
                'cc_subject_id' => 83,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:56:17',
            ),
            414 => 
            array (
                'id' => 424,
                'cc_subject_id' => 83,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:20:43',
                'updated_at' => '2026-07-06 17:56:27',
            ),
            415 => 
            array (
                'id' => 425,
                'cc_subject_id' => 27,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:44:07',
                'updated_at' => '2026-07-06 15:46:32',
            ),
            416 => 
            array (
                'id' => 426,
                'cc_subject_id' => 27,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:44:15',
                'updated_at' => '2026-07-06 15:46:43',
            ),
            417 => 
            array (
                'id' => 427,
                'cc_subject_id' => 27,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:44:28',
                'updated_at' => '2026-07-06 15:46:54',
            ),
            418 => 
            array (
                'id' => 428,
                'cc_subject_id' => 27,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:44:38',
                'updated_at' => '2026-07-06 15:47:04',
            ),
            419 => 
            array (
                'id' => 429,
                'cc_subject_id' => 27,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:44:57',
                'updated_at' => '2026-07-06 15:47:14',
            ),
            420 => 
            array (
                'id' => 430,
                'cc_subject_id' => 27,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:45:05',
                'updated_at' => '2026-07-06 15:47:28',
            ),
            421 => 
            array (
                'id' => 431,
                'cc_subject_id' => 27,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:45:14',
                'updated_at' => '2026-07-06 15:47:39',
            ),
            422 => 
            array (
                'id' => 432,
                'cc_subject_id' => 27,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:45:24',
                'updated_at' => '2026-07-06 15:47:52',
            ),
            423 => 
            array (
                'id' => 433,
                'cc_subject_id' => 146,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:54:58',
                'updated_at' => '2026-07-06 17:56:55',
            ),
            424 => 
            array (
                'id' => 434,
                'cc_subject_id' => 146,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:55:29',
                'updated_at' => '2026-07-06 17:57:06',
            ),
            425 => 
            array (
                'id' => 435,
                'cc_subject_id' => 146,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:55:50',
                'updated_at' => '2026-07-06 17:57:15',
            ),
            426 => 
            array (
                'id' => 436,
                'cc_subject_id' => 146,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:56:08',
                'updated_at' => '2026-07-06 17:57:40',
            ),
            427 => 
            array (
                'id' => 437,
                'cc_subject_id' => 146,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:56:25',
                'updated_at' => '2026-07-06 17:57:53',
            ),
            428 => 
            array (
                'id' => 438,
                'cc_subject_id' => 146,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:56:39',
                'updated_at' => '2026-07-06 17:58:04',
            ),
            429 => 
            array (
                'id' => 439,
                'cc_subject_id' => 146,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-08 19:56:54',
                'updated_at' => '2026-07-06 17:58:15',
            ),
            430 => 
            array (
                'id' => 440,
                'cc_subject_id' => 84,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:06:28',
                'updated_at' => '2026-02-27 21:52:26',
            ),
            431 => 
            array (
                'id' => 441,
                'cc_subject_id' => 84,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:06:42',
                'updated_at' => '2026-02-27 21:52:31',
            ),
            432 => 
            array (
                'id' => 442,
                'cc_subject_id' => 84,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:06:50',
                'updated_at' => '2026-02-27 21:52:35',
            ),
            433 => 
            array (
                'id' => 443,
                'cc_subject_id' => 84,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:07:13',
                'updated_at' => '2026-02-27 21:52:42',
            ),
            434 => 
            array (
                'id' => 444,
                'cc_subject_id' => 84,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:09:00',
                'updated_at' => '2026-02-27 21:52:47',
            ),
            435 => 
            array (
                'id' => 445,
                'cc_subject_id' => 84,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:09:20',
                'updated_at' => '2026-02-27 21:52:53',
            ),
            436 => 
            array (
                'id' => 446,
                'cc_subject_id' => 84,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:09:32',
                'updated_at' => '2026-02-27 21:52:58',
            ),
            437 => 
            array (
                'id' => 447,
                'cc_subject_id' => 146,
                'name' => 'فصل9',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:18:55',
                'updated_at' => '2026-07-06 17:58:40',
            ),
            438 => 
            array (
                'id' => 448,
                'cc_subject_id' => 146,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-08 20:29:25',
                'updated_at' => '2026-07-06 17:57:24',
            ),
            439 => 
            array (
                'id' => 449,
                'cc_subject_id' => 147,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-16 14:57:31',
                'updated_at' => '2026-07-06 14:27:05',
            ),
            440 => 
            array (
                'id' => 450,
                'cc_subject_id' => 147,
                'name' => 'فصل3',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-16 14:57:45',
                'updated_at' => '2026-07-06 14:30:41',
            ),
            441 => 
            array (
                'id' => 451,
                'cc_subject_id' => 147,
                'name' => 'فصل4',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-16 14:57:58',
                'updated_at' => '2026-07-06 14:31:15',
            ),
            442 => 
            array (
                'id' => 452,
                'cc_subject_id' => 147,
                'name' => 'فصل5',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-16 14:58:13',
                'updated_at' => '2026-07-06 14:31:55',
            ),
            443 => 
            array (
                'id' => 453,
                'cc_subject_id' => 147,
                'name' => 'فصل6',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-16 14:58:28',
                'updated_at' => '2026-07-07 10:45:24',
            ),
            444 => 
            array (
                'id' => 454,
                'cc_subject_id' => 147,
                'name' => 'فصل7',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-16 14:58:41',
                'updated_at' => '2026-07-07 10:46:13',
            ),
            445 => 
            array (
                'id' => 455,
                'cc_subject_id' => 147,
                'name' => 'فصل8',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-16 14:58:52',
                'updated_at' => '2026-07-07 10:46:22',
            ),
            446 => 
            array (
                'id' => 456,
                'cc_subject_id' => 147,
                'name' => 'فصل9',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-16 14:59:04',
                'updated_at' => '2026-07-07 10:46:30',
            ),
            447 => 
            array (
                'id' => 457,
                'cc_subject_id' => 147,
                'name' => 'فصل10',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-16 14:59:16',
                'updated_at' => '2026-07-07 10:46:36',
            ),
            448 => 
            array (
                'id' => 458,
                'cc_subject_id' => 147,
                'name' => 'فصل11',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-16 14:59:27',
                'updated_at' => '2026-07-07 10:46:48',
            ),
            449 => 
            array (
                'id' => 459,
                'cc_subject_id' => 147,
                'name' => 'فصل12',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:00:03',
                'updated_at' => '2026-07-07 10:46:55',
            ),
            450 => 
            array (
                'id' => 460,
                'cc_subject_id' => 148,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:04:56',
                'updated_at' => '2026-07-06 14:41:00',
            ),
            451 => 
            array (
                'id' => 461,
                'cc_subject_id' => 148,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:05:07',
                'updated_at' => '2026-07-06 14:41:10',
            ),
            452 => 
            array (
                'id' => 462,
                'cc_subject_id' => 148,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:05:22',
                'updated_at' => '2026-07-06 14:41:22',
            ),
            453 => 
            array (
                'id' => 463,
                'cc_subject_id' => 148,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:05:32',
                'updated_at' => '2026-07-06 14:41:38',
            ),
            454 => 
            array (
                'id' => 464,
                'cc_subject_id' => 148,
                'name' => '‎فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:05:45',
                'updated_at' => '2026-07-06 14:41:53',
            ),
            455 => 
            array (
                'id' => 465,
                'cc_subject_id' => 148,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:05:58',
                'updated_at' => '2026-07-06 14:42:07',
            ),
            456 => 
            array (
                'id' => 466,
                'cc_subject_id' => 148,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:06:10',
                'updated_at' => '2026-07-06 14:42:26',
            ),
            457 => 
            array (
                'id' => 467,
                'cc_subject_id' => 148,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:06:25',
                'updated_at' => '2026-07-06 14:42:46',
            ),
            458 => 
            array (
                'id' => 468,
                'cc_subject_id' => 148,
                'name' => 'فصل9',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:06:42',
                'updated_at' => '2026-07-06 14:43:10',
            ),
            459 => 
            array (
                'id' => 469,
                'cc_subject_id' => 149,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:35:32',
                'updated_at' => '2026-07-06 14:36:54',
            ),
            460 => 
            array (
                'id' => 519,
                'cc_subject_id' => 154,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:01:16',
                'updated_at' => '2026-07-06 15:01:16',
            ),
            461 => 
            array (
                'id' => 470,
                'cc_subject_id' => 149,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:35:42',
                'updated_at' => '2026-07-06 14:37:09',
            ),
            462 => 
            array (
                'id' => 518,
                'cc_subject_id' => 153,
                'name' => 'فصل10',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-07-06 14:57:42',
                'updated_at' => '2026-07-06 14:57:42',
            ),
            463 => 
            array (
                'id' => 471,
                'cc_subject_id' => 149,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:35:53',
                'updated_at' => '2026-07-06 14:37:21',
            ),
            464 => 
            array (
                'id' => 517,
                'cc_subject_id' => 153,
                'name' => 'فصل9',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-07-06 14:57:31',
                'updated_at' => '2026-07-06 14:57:31',
            ),
            465 => 
            array (
                'id' => 472,
                'cc_subject_id' => 149,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:36:06',
                'updated_at' => '2026-07-06 14:37:35',
            ),
            466 => 
            array (
                'id' => 473,
                'cc_subject_id' => 149,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:36:20',
                'updated_at' => '2026-07-06 14:37:50',
            ),
            467 => 
            array (
                'id' => 474,
                'cc_subject_id' => 149,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:36:47',
                'updated_at' => '2026-07-06 14:38:02',
            ),
            468 => 
            array (
                'id' => 516,
                'cc_subject_id' => 153,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-07-06 14:57:17',
                'updated_at' => '2026-07-06 14:57:17',
            ),
            469 => 
            array (
                'id' => 475,
                'cc_subject_id' => 149,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:37:01',
                'updated_at' => '2026-07-06 14:38:18',
            ),
            470 => 
            array (
                'id' => 515,
                'cc_subject_id' => 153,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-07-06 14:57:02',
                'updated_at' => '2026-07-06 14:57:02',
            ),
            471 => 
            array (
                'id' => 476,
                'cc_subject_id' => 149,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:37:12',
                'updated_at' => '2026-07-06 14:38:30',
            ),
            472 => 
            array (
                'id' => 477,
                'cc_subject_id' => 149,
                'name' => 'فصل9',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:37:22',
                'updated_at' => '2026-07-06 14:38:43',
            ),
            473 => 
            array (
                'id' => 478,
                'cc_subject_id' => 149,
                'name' => 'فصل10',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:37:34',
                'updated_at' => '2026-07-06 14:39:00',
            ),
            474 => 
            array (
                'id' => 514,
                'cc_subject_id' => 153,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-07-06 14:56:51',
                'updated_at' => '2026-07-06 14:56:51',
            ),
            475 => 
            array (
                'id' => 479,
                'cc_subject_id' => 149,
                'name' => 'فصل11',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:37:46',
                'updated_at' => '2026-07-06 14:39:16',
            ),
            476 => 
            array (
                'id' => 513,
                'cc_subject_id' => 153,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-07-06 14:56:27',
                'updated_at' => '2026-07-06 14:56:27',
            ),
            477 => 
            array (
                'id' => 480,
                'cc_subject_id' => 149,
                'name' => 'فصل12',
                'order' => 11,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:38:04',
                'updated_at' => '2026-07-06 14:39:28',
            ),
            478 => 
            array (
                'id' => 512,
                'cc_subject_id' => 153,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 14:56:15',
                'updated_at' => '2026-07-06 14:56:39',
            ),
            479 => 
            array (
                'id' => 481,
                'cc_subject_id' => 149,
                'name' => 'فصل13',
                'order' => 12,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:38:16',
                'updated_at' => '2026-07-06 14:39:45',
            ),
            480 => 
            array (
                'id' => 511,
                'cc_subject_id' => 153,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 14:56:02',
                'updated_at' => '2026-07-06 14:56:02',
            ),
            481 => 
            array (
                'id' => 482,
                'cc_subject_id' => 149,
                'name' => 'فصل14',
                'order' => 13,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:38:29',
                'updated_at' => '2026-07-06 14:40:14',
            ),
            482 => 
            array (
                'id' => 510,
                'cc_subject_id' => 153,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 14:55:49',
                'updated_at' => '2026-07-06 14:55:49',
            ),
            483 => 
            array (
                'id' => 483,
                'cc_subject_id' => 149,
                'name' => 'فصل15',
                'order' => 14,
                'is_active' => 1,
                'created_at' => '2026-02-16 15:38:50',
                'updated_at' => '2026-07-06 14:40:27',
            ),
            484 => 
            array (
                'id' => 509,
                'cc_subject_id' => 153,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 14:55:39',
                'updated_at' => '2026-07-06 14:55:39',
            ),
            485 => 
            array (
                'id' => 484,
                'cc_subject_id' => 150,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-17 18:10:04',
                'updated_at' => '2026-07-06 14:43:46',
            ),
            486 => 
            array (
                'id' => 485,
                'cc_subject_id' => 150,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-17 18:10:11',
                'updated_at' => '2026-07-06 14:43:58',
            ),
            487 => 
            array (
                'id' => 486,
                'cc_subject_id' => 150,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-17 18:10:17',
                'updated_at' => '2026-07-06 14:44:13',
            ),
            488 => 
            array (
                'id' => 487,
                'cc_subject_id' => 150,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-17 18:10:23',
                'updated_at' => '2026-07-06 14:44:26',
            ),
            489 => 
            array (
                'id' => 488,
                'cc_subject_id' => 150,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-17 18:10:29',
                'updated_at' => '2026-07-06 14:44:39',
            ),
            490 => 
            array (
                'id' => 489,
                'cc_subject_id' => 150,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-17 18:10:37',
                'updated_at' => '2026-07-06 14:44:51',
            ),
            491 => 
            array (
                'id' => 490,
                'cc_subject_id' => 151,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-17 18:19:39',
                'updated_at' => '2026-07-06 14:45:47',
            ),
            492 => 
            array (
                'id' => 491,
                'cc_subject_id' => 151,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-17 18:19:46',
                'updated_at' => '2026-07-06 14:45:58',
            ),
            493 => 
            array (
                'id' => 508,
                'cc_subject_id' => 152,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-07-06 14:54:03',
                'updated_at' => '2026-07-06 14:54:08',
            ),
            494 => 
            array (
                'id' => 492,
                'cc_subject_id' => 151,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-17 18:19:56',
                'updated_at' => '2026-07-06 14:46:11',
            ),
            495 => 
            array (
                'id' => 507,
                'cc_subject_id' => 152,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 14:52:28',
                'updated_at' => '2026-07-06 14:52:46',
            ),
            496 => 
            array (
                'id' => 493,
                'cc_subject_id' => 151,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-17 18:20:06',
                'updated_at' => '2026-07-06 14:46:31',
            ),
            497 => 
            array (
                'id' => 494,
                'cc_subject_id' => 151,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-17 18:20:14',
                'updated_at' => '2026-07-06 14:46:45',
            ),
            498 => 
            array (
                'id' => 506,
                'cc_subject_id' => 152,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 14:52:10',
                'updated_at' => '2026-07-06 14:53:13',
            ),
            499 => 
            array (
                'id' => 495,
                'cc_subject_id' => 151,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-17 18:20:28',
                'updated_at' => '2026-07-06 14:47:04',
            ),
        ));
        \DB::table('cc_chapters')->insert(array (
            0 => 
            array (
                'id' => 505,
                'cc_subject_id' => 152,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 14:52:04',
                'updated_at' => '2026-07-06 14:53:26',
            ),
            1 => 
            array (
                'id' => 496,
                'cc_subject_id' => 151,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-02-17 18:20:37',
                'updated_at' => '2026-07-06 14:47:16',
            ),
            2 => 
            array (
                'id' => 497,
                'cc_subject_id' => 151,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-02-17 18:20:49',
                'updated_at' => '2026-07-06 14:47:33',
            ),
            3 => 
            array (
                'id' => 503,
                'cc_subject_id' => 152,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-07-06 14:51:31',
                'updated_at' => '2026-07-06 14:53:49',
            ),
            4 => 
            array (
                'id' => 504,
                'cc_subject_id' => 152,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 14:51:58',
                'updated_at' => '2026-07-06 14:53:40',
            ),
            5 => 
            array (
                'id' => 498,
                'cc_subject_id' => 151,
                'name' => 'فصل9',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-17 18:20:58',
                'updated_at' => '2026-07-06 14:47:45',
            ),
            6 => 
            array (
                'id' => 499,
                'cc_subject_id' => 151,
                'name' => 'فصل10',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-17 18:21:08',
                'updated_at' => '2026-07-06 14:47:57',
            ),
            7 => 
            array (
                'id' => 500,
                'cc_subject_id' => 151,
                'name' => 'فصل11',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-02-17 18:21:17',
                'updated_at' => '2026-07-06 14:48:14',
            ),
            8 => 
            array (
                'id' => 501,
                'cc_subject_id' => 151,
                'name' => 'فصل12',
                'order' => 11,
                'is_active' => 1,
                'created_at' => '2026-02-17 18:21:28',
                'updated_at' => '2026-07-06 14:48:26',
            ),
            9 => 
            array (
                'id' => 502,
                'cc_subject_id' => 147,
                'name' => 'فصل2',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 14:29:40',
                'updated_at' => '2026-07-06 14:30:34',
            ),
            10 => 
            array (
                'id' => 520,
                'cc_subject_id' => 154,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:01:28',
                'updated_at' => '2026-07-06 15:01:28',
            ),
            11 => 
            array (
                'id' => 521,
                'cc_subject_id' => 154,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:01:36',
                'updated_at' => '2026-07-06 15:01:36',
            ),
            12 => 
            array (
                'id' => 522,
                'cc_subject_id' => 154,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:01:49',
                'updated_at' => '2026-07-06 15:01:49',
            ),
            13 => 
            array (
                'id' => 523,
                'cc_subject_id' => 154,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:01:59',
                'updated_at' => '2026-07-06 15:01:59',
            ),
            14 => 
            array (
                'id' => 524,
                'cc_subject_id' => 156,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:03:51',
                'updated_at' => '2026-07-06 15:03:51',
            ),
            15 => 
            array (
                'id' => 525,
                'cc_subject_id' => 156,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:04:00',
                'updated_at' => '2026-07-06 15:04:00',
            ),
            16 => 
            array (
                'id' => 526,
                'cc_subject_id' => 156,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:04:10',
                'updated_at' => '2026-07-06 15:04:10',
            ),
            17 => 
            array (
                'id' => 527,
                'cc_subject_id' => 156,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:04:18',
                'updated_at' => '2026-07-06 15:04:18',
            ),
            18 => 
            array (
                'id' => 528,
                'cc_subject_id' => 156,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:04:29',
                'updated_at' => '2026-07-06 15:04:29',
            ),
            19 => 
            array (
                'id' => 529,
                'cc_subject_id' => 156,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:04:41',
                'updated_at' => '2026-07-06 15:04:41',
            ),
            20 => 
            array (
                'id' => 530,
                'cc_subject_id' => 156,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:04:50',
                'updated_at' => '2026-07-06 15:04:50',
            ),
            21 => 
            array (
                'id' => 531,
                'cc_subject_id' => 156,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:05:06',
                'updated_at' => '2026-07-06 15:05:06',
            ),
            22 => 
            array (
                'id' => 532,
                'cc_subject_id' => 156,
                'name' => 'فصل9',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:05:16',
                'updated_at' => '2026-07-06 15:05:16',
            ),
            23 => 
            array (
                'id' => 533,
                'cc_subject_id' => 156,
                'name' => 'فصل10',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:05:25',
                'updated_at' => '2026-07-06 15:05:25',
            ),
            24 => 
            array (
                'id' => 534,
                'cc_subject_id' => 156,
                'name' => 'فصل11',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:05:42',
                'updated_at' => '2026-07-06 15:05:42',
            ),
            25 => 
            array (
                'id' => 540,
                'cc_subject_id' => 29,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:53:45',
                'updated_at' => '2026-07-06 15:53:45',
            ),
            26 => 
            array (
                'id' => 541,
                'cc_subject_id' => 29,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:53:55',
                'updated_at' => '2026-07-06 15:53:55',
            ),
            27 => 
            array (
                'id' => 542,
                'cc_subject_id' => 29,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:54:06',
                'updated_at' => '2026-07-06 15:54:06',
            ),
            28 => 
            array (
                'id' => 543,
                'cc_subject_id' => 30,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 15:55:31',
                'updated_at' => '2026-07-06 15:55:31',
            ),
            29 => 
            array (
                'id' => 544,
                'cc_subject_id' => 30,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:29:00',
                'updated_at' => '2026-07-06 16:29:00',
            ),
            30 => 
            array (
                'id' => 545,
                'cc_subject_id' => 30,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:29:13',
                'updated_at' => '2026-07-06 16:29:13',
            ),
            31 => 
            array (
                'id' => 546,
                'cc_subject_id' => 30,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:29:30',
                'updated_at' => '2026-07-06 16:29:30',
            ),
            32 => 
            array (
                'id' => 547,
                'cc_subject_id' => 31,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:31:25',
                'updated_at' => '2026-07-06 16:31:25',
            ),
            33 => 
            array (
                'id' => 548,
                'cc_subject_id' => 31,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:31:32',
                'updated_at' => '2026-07-06 16:31:42',
            ),
            34 => 
            array (
                'id' => 549,
                'cc_subject_id' => 31,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:31:52',
                'updated_at' => '2026-07-06 16:31:52',
            ),
            35 => 
            array (
                'id' => 550,
                'cc_subject_id' => 32,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:32:54',
                'updated_at' => '2026-07-06 16:32:54',
            ),
            36 => 
            array (
                'id' => 551,
                'cc_subject_id' => 32,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:33:03',
                'updated_at' => '2026-07-06 16:33:35',
            ),
            37 => 
            array (
                'id' => 552,
                'cc_subject_id' => 32,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:33:10',
                'updated_at' => '2026-07-06 16:33:30',
            ),
            38 => 
            array (
                'id' => 553,
                'cc_subject_id' => 32,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:33:22',
                'updated_at' => '2026-07-06 16:33:22',
            ),
            39 => 
            array (
                'id' => 554,
                'cc_subject_id' => 32,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:33:56',
                'updated_at' => '2026-07-06 16:34:06',
            ),
            40 => 
            array (
                'id' => 555,
                'cc_subject_id' => 32,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:34:18',
                'updated_at' => '2026-07-06 16:34:18',
            ),
            41 => 
            array (
                'id' => 556,
                'cc_subject_id' => 32,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:34:30',
                'updated_at' => '2026-07-06 16:34:30',
            ),
            42 => 
            array (
                'id' => 557,
                'cc_subject_id' => 32,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:34:52',
                'updated_at' => '2026-07-06 16:34:52',
            ),
            43 => 
            array (
                'id' => 558,
                'cc_subject_id' => 32,
                'name' => 'فصل9',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:35:06',
                'updated_at' => '2026-07-06 16:35:06',
            ),
            44 => 
            array (
                'id' => 559,
                'cc_subject_id' => 32,
                'name' => 'فصل10',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:35:25',
                'updated_at' => '2026-07-06 16:35:25',
            ),
            45 => 
            array (
                'id' => 560,
                'cc_subject_id' => 32,
                'name' => 'فصل11',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:35:38',
                'updated_at' => '2026-07-06 16:35:38',
            ),
            46 => 
            array (
                'id' => 561,
                'cc_subject_id' => 32,
                'name' => 'فصل12',
                'order' => 11,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:35:54',
                'updated_at' => '2026-07-06 16:35:54',
            ),
            47 => 
            array (
                'id' => 562,
                'cc_subject_id' => 33,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:36:54',
                'updated_at' => '2026-07-06 16:36:54',
            ),
            48 => 
            array (
                'id' => 563,
                'cc_subject_id' => 33,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:37:07',
                'updated_at' => '2026-07-06 16:37:07',
            ),
            49 => 
            array (
                'id' => 564,
                'cc_subject_id' => 33,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:37:28',
                'updated_at' => '2026-07-06 16:37:28',
            ),
            50 => 
            array (
                'id' => 565,
                'cc_subject_id' => 34,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:38:36',
                'updated_at' => '2026-07-06 16:38:36',
            ),
            51 => 
            array (
                'id' => 566,
                'cc_subject_id' => 34,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:38:44',
                'updated_at' => '2026-07-06 16:38:44',
            ),
            52 => 
            array (
                'id' => 567,
                'cc_subject_id' => 34,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:38:52',
                'updated_at' => '2026-07-06 16:38:52',
            ),
            53 => 
            array (
                'id' => 568,
                'cc_subject_id' => 34,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:39:01',
                'updated_at' => '2026-07-06 16:39:01',
            ),
            54 => 
            array (
                'id' => 569,
                'cc_subject_id' => 34,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:39:11',
                'updated_at' => '2026-07-06 16:39:11',
            ),
            55 => 
            array (
                'id' => 570,
                'cc_subject_id' => 34,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:39:19',
                'updated_at' => '2026-07-06 16:39:19',
            ),
            56 => 
            array (
                'id' => 571,
                'cc_subject_id' => 34,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:39:40',
                'updated_at' => '2026-07-06 16:39:40',
            ),
            57 => 
            array (
                'id' => 572,
                'cc_subject_id' => 34,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:39:54',
                'updated_at' => '2026-07-06 16:39:54',
            ),
            58 => 
            array (
                'id' => 573,
                'cc_subject_id' => 34,
                'name' => 'فصل9',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:40:03',
                'updated_at' => '2026-07-06 16:40:03',
            ),
            59 => 
            array (
                'id' => 574,
                'cc_subject_id' => 34,
                'name' => 'فصل10',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:40:15',
                'updated_at' => '2026-07-06 16:40:15',
            ),
            60 => 
            array (
                'id' => 575,
                'cc_subject_id' => 35,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:40:46',
                'updated_at' => '2026-07-06 16:40:46',
            ),
            61 => 
            array (
                'id' => 576,
                'cc_subject_id' => 35,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:41:39',
                'updated_at' => '2026-07-06 16:41:39',
            ),
            62 => 
            array (
                'id' => 577,
                'cc_subject_id' => 35,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:41:57',
                'updated_at' => '2026-07-06 16:41:57',
            ),
            63 => 
            array (
                'id' => 578,
                'cc_subject_id' => 35,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:42:08',
                'updated_at' => '2026-07-06 16:42:08',
            ),
            64 => 
            array (
                'id' => 579,
                'cc_subject_id' => 35,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:42:24',
                'updated_at' => '2026-07-06 16:42:24',
            ),
            65 => 
            array (
                'id' => 580,
                'cc_subject_id' => 35,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:42:35',
                'updated_at' => '2026-07-06 16:42:35',
            ),
            66 => 
            array (
                'id' => 581,
                'cc_subject_id' => 35,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:42:43',
                'updated_at' => '2026-07-06 16:42:43',
            ),
            67 => 
            array (
                'id' => 582,
                'cc_subject_id' => 35,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:42:57',
                'updated_at' => '2026-07-06 16:42:57',
            ),
            68 => 
            array (
                'id' => 583,
                'cc_subject_id' => 36,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:44:19',
                'updated_at' => '2026-07-06 16:44:19',
            ),
            69 => 
            array (
                'id' => 584,
                'cc_subject_id' => 36,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:44:35',
                'updated_at' => '2026-07-06 16:44:35',
            ),
            70 => 
            array (
                'id' => 585,
                'cc_subject_id' => 36,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:44:54',
                'updated_at' => '2026-07-06 16:44:54',
            ),
            71 => 
            array (
                'id' => 586,
                'cc_subject_id' => 36,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:45:08',
                'updated_at' => '2026-07-06 16:45:08',
            ),
            72 => 
            array (
                'id' => 587,
                'cc_subject_id' => 36,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:45:17',
                'updated_at' => '2026-07-06 16:45:17',
            ),
            73 => 
            array (
                'id' => 588,
                'cc_subject_id' => 36,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:45:26',
                'updated_at' => '2026-07-06 16:45:26',
            ),
            74 => 
            array (
                'id' => 589,
                'cc_subject_id' => 36,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:45:36',
                'updated_at' => '2026-07-06 16:45:36',
            ),
            75 => 
            array (
                'id' => 590,
                'cc_subject_id' => 36,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:45:49',
                'updated_at' => '2026-07-06 16:45:49',
            ),
            76 => 
            array (
                'id' => 591,
                'cc_subject_id' => 36,
                'name' => 'فصل9',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:46:00',
                'updated_at' => '2026-07-06 16:46:00',
            ),
            77 => 
            array (
                'id' => 592,
                'cc_subject_id' => 36,
                'name' => 'فصل10',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:46:56',
                'updated_at' => '2026-07-06 16:46:56',
            ),
            78 => 
            array (
                'id' => 593,
                'cc_subject_id' => 36,
                'name' => 'فصل11',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:47:08',
                'updated_at' => '2026-07-06 16:47:08',
            ),
            79 => 
            array (
                'id' => 594,
                'cc_subject_id' => 36,
                'name' => 'فصل12',
                'order' => 11,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:47:19',
                'updated_at' => '2026-07-06 16:47:19',
            ),
            80 => 
            array (
                'id' => 595,
                'cc_subject_id' => 36,
                'name' => 'فصل13',
                'order' => 12,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:47:33',
                'updated_at' => '2026-07-06 16:47:33',
            ),
            81 => 
            array (
                'id' => 596,
                'cc_subject_id' => 37,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:50:45',
                'updated_at' => '2026-07-06 16:50:45',
            ),
            82 => 
            array (
                'id' => 597,
                'cc_subject_id' => 37,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:50:56',
                'updated_at' => '2026-07-06 16:50:56',
            ),
            83 => 
            array (
                'id' => 598,
                'cc_subject_id' => 37,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:51:08',
                'updated_at' => '2026-07-06 16:51:08',
            ),
            84 => 
            array (
                'id' => 599,
                'cc_subject_id' => 38,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:52:03',
                'updated_at' => '2026-07-06 16:52:03',
            ),
            85 => 
            array (
                'id' => 600,
                'cc_subject_id' => 38,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:52:19',
                'updated_at' => '2026-07-06 16:52:19',
            ),
            86 => 
            array (
                'id' => 601,
                'cc_subject_id' => 38,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:52:30',
                'updated_at' => '2026-07-06 16:52:30',
            ),
            87 => 
            array (
                'id' => 602,
                'cc_subject_id' => 38,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:52:44',
                'updated_at' => '2026-07-06 16:52:44',
            ),
            88 => 
            array (
                'id' => 603,
                'cc_subject_id' => 38,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:52:55',
                'updated_at' => '2026-07-06 16:52:55',
            ),
            89 => 
            array (
                'id' => 604,
                'cc_subject_id' => 38,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:53:26',
                'updated_at' => '2026-07-06 16:53:26',
            ),
            90 => 
            array (
                'id' => 605,
                'cc_subject_id' => 39,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:55:30',
                'updated_at' => '2026-07-06 16:55:30',
            ),
            91 => 
            array (
                'id' => 606,
                'cc_subject_id' => 39,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:55:39',
                'updated_at' => '2026-07-06 16:55:39',
            ),
            92 => 
            array (
                'id' => 607,
                'cc_subject_id' => 39,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:55:54',
                'updated_at' => '2026-07-06 16:55:54',
            ),
            93 => 
            array (
                'id' => 608,
                'cc_subject_id' => 39,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:56:06',
                'updated_at' => '2026-07-06 16:56:06',
            ),
            94 => 
            array (
                'id' => 609,
                'cc_subject_id' => 39,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-07-06 16:56:17',
                'updated_at' => '2026-07-06 16:56:17',
            ),
            95 => 
            array (
                'id' => 620,
                'cc_subject_id' => 42,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:10:31',
                'updated_at' => '2026-07-06 18:10:31',
            ),
            96 => 
            array (
                'id' => 621,
                'cc_subject_id' => 42,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:10:41',
                'updated_at' => '2026-07-06 18:10:41',
            ),
            97 => 
            array (
                'id' => 622,
                'cc_subject_id' => 42,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:10:52',
                'updated_at' => '2026-07-06 18:10:52',
            ),
            98 => 
            array (
                'id' => 623,
                'cc_subject_id' => 42,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:11:05',
                'updated_at' => '2026-07-06 18:11:05',
            ),
            99 => 
            array (
                'id' => 624,
                'cc_subject_id' => 43,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:12:19',
                'updated_at' => '2026-07-06 18:12:19',
            ),
            100 => 
            array (
                'id' => 625,
                'cc_subject_id' => 43,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:12:25',
                'updated_at' => '2026-07-06 18:12:43',
            ),
            101 => 
            array (
                'id' => 626,
                'cc_subject_id' => 43,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:12:35',
                'updated_at' => '2026-07-06 18:12:56',
            ),
            102 => 
            array (
                'id' => 627,
                'cc_subject_id' => 44,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:14:04',
                'updated_at' => '2026-07-06 18:14:04',
            ),
            103 => 
            array (
                'id' => 628,
                'cc_subject_id' => 44,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:14:09',
                'updated_at' => '2026-07-06 18:14:17',
            ),
            104 => 
            array (
                'id' => 629,
                'cc_subject_id' => 44,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:14:28',
                'updated_at' => '2026-07-06 18:14:28',
            ),
            105 => 
            array (
                'id' => 630,
                'cc_subject_id' => 44,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:14:57',
                'updated_at' => '2026-07-06 18:14:57',
            ),
            106 => 
            array (
                'id' => 631,
                'cc_subject_id' => 45,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:16:15',
                'updated_at' => '2026-07-06 18:16:15',
            ),
            107 => 
            array (
                'id' => 632,
                'cc_subject_id' => 45,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:16:36',
                'updated_at' => '2026-07-06 18:16:36',
            ),
            108 => 
            array (
                'id' => 633,
                'cc_subject_id' => 45,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:16:46',
                'updated_at' => '2026-07-06 18:16:46',
            ),
            109 => 
            array (
                'id' => 634,
                'cc_subject_id' => 45,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:16:57',
                'updated_at' => '2026-07-06 18:16:57',
            ),
            110 => 
            array (
                'id' => 635,
                'cc_subject_id' => 46,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:22:57',
                'updated_at' => '2026-07-06 18:22:57',
            ),
            111 => 
            array (
                'id' => 636,
                'cc_subject_id' => 46,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:23:10',
                'updated_at' => '2026-07-06 18:23:10',
            ),
            112 => 
            array (
                'id' => 637,
                'cc_subject_id' => 46,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:23:21',
                'updated_at' => '2026-07-06 18:23:21',
            ),
            113 => 
            array (
                'id' => 638,
                'cc_subject_id' => 46,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:23:45',
                'updated_at' => '2026-07-06 18:23:45',
            ),
            114 => 
            array (
                'id' => 639,
                'cc_subject_id' => 47,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:24:57',
                'updated_at' => '2026-07-06 18:24:57',
            ),
            115 => 
            array (
                'id' => 640,
                'cc_subject_id' => 47,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:25:13',
                'updated_at' => '2026-07-06 18:25:13',
            ),
            116 => 
            array (
                'id' => 641,
                'cc_subject_id' => 47,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:25:28',
                'updated_at' => '2026-07-06 18:25:28',
            ),
            117 => 
            array (
                'id' => 642,
                'cc_subject_id' => 47,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:25:39',
                'updated_at' => '2026-07-06 18:25:39',
            ),
            118 => 
            array (
                'id' => 643,
                'cc_subject_id' => 47,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:25:57',
                'updated_at' => '2026-07-06 18:25:57',
            ),
            119 => 
            array (
                'id' => 644,
                'cc_subject_id' => 47,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:26:07',
                'updated_at' => '2026-07-06 18:26:07',
            ),
            120 => 
            array (
                'id' => 645,
                'cc_subject_id' => 47,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:26:21',
                'updated_at' => '2026-07-06 18:26:21',
            ),
            121 => 
            array (
                'id' => 646,
                'cc_subject_id' => 47,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:27:22',
                'updated_at' => '2026-07-06 18:27:22',
            ),
            122 => 
            array (
                'id' => 647,
                'cc_subject_id' => 48,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:28:49',
                'updated_at' => '2026-07-06 18:28:49',
            ),
            123 => 
            array (
                'id' => 648,
                'cc_subject_id' => 48,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:28:55',
                'updated_at' => '2026-07-06 18:28:55',
            ),
            124 => 
            array (
                'id' => 649,
                'cc_subject_id' => 48,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:29:07',
                'updated_at' => '2026-07-06 18:29:07',
            ),
            125 => 
            array (
                'id' => 650,
                'cc_subject_id' => 48,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:29:35',
                'updated_at' => '2026-07-06 18:29:35',
            ),
            126 => 
            array (
                'id' => 651,
                'cc_subject_id' => 48,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:29:48',
                'updated_at' => '2026-07-06 18:29:48',
            ),
            127 => 
            array (
                'id' => 652,
                'cc_subject_id' => 48,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:29:58',
                'updated_at' => '2026-07-06 18:29:58',
            ),
            128 => 
            array (
                'id' => 653,
                'cc_subject_id' => 48,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:30:10',
                'updated_at' => '2026-07-06 18:30:10',
            ),
            129 => 
            array (
                'id' => 654,
                'cc_subject_id' => 48,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:30:20',
                'updated_at' => '2026-07-06 18:30:20',
            ),
            130 => 
            array (
                'id' => 655,
                'cc_subject_id' => 49,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:31:34',
                'updated_at' => '2026-07-06 18:31:34',
            ),
            131 => 
            array (
                'id' => 656,
                'cc_subject_id' => 49,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:31:59',
                'updated_at' => '2026-07-06 18:31:59',
            ),
            132 => 
            array (
                'id' => 657,
                'cc_subject_id' => 49,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:32:09',
                'updated_at' => '2026-07-06 18:32:09',
            ),
            133 => 
            array (
                'id' => 658,
                'cc_subject_id' => 49,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:32:22',
                'updated_at' => '2026-07-06 18:32:22',
            ),
            134 => 
            array (
                'id' => 659,
                'cc_subject_id' => 49,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:32:34',
                'updated_at' => '2026-07-06 18:32:34',
            ),
            135 => 
            array (
                'id' => 660,
                'cc_subject_id' => 49,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:32:44',
                'updated_at' => '2026-07-06 18:32:44',
            ),
            136 => 
            array (
                'id' => 661,
                'cc_subject_id' => 49,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:32:55',
                'updated_at' => '2026-07-06 18:32:55',
            ),
            137 => 
            array (
                'id' => 662,
                'cc_subject_id' => 49,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:33:04',
                'updated_at' => '2026-07-06 18:33:04',
            ),
            138 => 
            array (
                'id' => 663,
                'cc_subject_id' => 49,
                'name' => 'فصل9',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:33:21',
                'updated_at' => '2026-07-06 18:33:21',
            ),
            139 => 
            array (
                'id' => 664,
                'cc_subject_id' => 49,
                'name' => 'فصل10',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:33:37',
                'updated_at' => '2026-07-06 18:33:37',
            ),
            140 => 
            array (
                'id' => 665,
                'cc_subject_id' => 49,
                'name' => 'فصل11',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:33:50',
                'updated_at' => '2026-07-06 18:33:50',
            ),
            141 => 
            array (
                'id' => 666,
                'cc_subject_id' => 49,
                'name' => 'فصل12',
                'order' => 11,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:34:05',
                'updated_at' => '2026-07-06 18:34:05',
            ),
            142 => 
            array (
                'id' => 667,
                'cc_subject_id' => 49,
                'name' => 'فصل13',
                'order' => 12,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:34:16',
                'updated_at' => '2026-07-06 18:34:16',
            ),
            143 => 
            array (
                'id' => 668,
                'cc_subject_id' => 49,
                'name' => 'فصل14',
                'order' => 13,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:34:28',
                'updated_at' => '2026-07-06 18:34:28',
            ),
            144 => 
            array (
                'id' => 669,
                'cc_subject_id' => 49,
                'name' => 'فصل15',
                'order' => 14,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:34:39',
                'updated_at' => '2026-07-06 18:34:39',
            ),
            145 => 
            array (
                'id' => 670,
                'cc_subject_id' => 49,
                'name' => 'فصل16',
                'order' => 15,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:34:51',
                'updated_at' => '2026-07-06 18:34:51',
            ),
            146 => 
            array (
                'id' => 671,
                'cc_subject_id' => 49,
                'name' => 'فصل17',
                'order' => 16,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:35:04',
                'updated_at' => '2026-07-06 18:35:04',
            ),
            147 => 
            array (
                'id' => 672,
                'cc_subject_id' => 49,
                'name' => 'فصل18',
                'order' => 17,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:35:16',
                'updated_at' => '2026-07-06 18:35:16',
            ),
            148 => 
            array (
                'id' => 673,
                'cc_subject_id' => 50,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:36:36',
                'updated_at' => '2026-07-06 18:36:36',
            ),
            149 => 
            array (
                'id' => 674,
                'cc_subject_id' => 50,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:36:46',
                'updated_at' => '2026-07-06 18:36:46',
            ),
            150 => 
            array (
                'id' => 675,
                'cc_subject_id' => 50,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:37:00',
                'updated_at' => '2026-07-06 18:37:00',
            ),
            151 => 
            array (
                'id' => 676,
                'cc_subject_id' => 50,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:37:10',
                'updated_at' => '2026-07-06 18:37:10',
            ),
            152 => 
            array (
                'id' => 677,
                'cc_subject_id' => 50,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:37:21',
                'updated_at' => '2026-07-06 18:37:21',
            ),
            153 => 
            array (
                'id' => 678,
                'cc_subject_id' => 50,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:37:36',
                'updated_at' => '2026-07-06 18:37:36',
            ),
            154 => 
            array (
                'id' => 679,
                'cc_subject_id' => 50,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:37:51',
                'updated_at' => '2026-07-06 18:37:51',
            ),
            155 => 
            array (
                'id' => 680,
                'cc_subject_id' => 51,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:38:55',
                'updated_at' => '2026-07-06 18:38:55',
            ),
            156 => 
            array (
                'id' => 681,
                'cc_subject_id' => 51,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:39:04',
                'updated_at' => '2026-07-06 18:39:04',
            ),
            157 => 
            array (
                'id' => 682,
                'cc_subject_id' => 51,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:39:12',
                'updated_at' => '2026-07-06 18:39:12',
            ),
            158 => 
            array (
                'id' => 683,
                'cc_subject_id' => 53,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:47:44',
                'updated_at' => '2026-07-06 18:47:44',
            ),
            159 => 
            array (
                'id' => 684,
                'cc_subject_id' => 53,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:47:52',
                'updated_at' => '2026-07-06 18:47:52',
            ),
            160 => 
            array (
                'id' => 685,
                'cc_subject_id' => 53,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:48:00',
                'updated_at' => '2026-07-06 18:48:00',
            ),
            161 => 
            array (
                'id' => 686,
                'cc_subject_id' => 53,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:48:12',
                'updated_at' => '2026-07-06 18:48:12',
            ),
            162 => 
            array (
                'id' => 687,
                'cc_subject_id' => 53,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:48:26',
                'updated_at' => '2026-07-06 18:48:26',
            ),
            163 => 
            array (
                'id' => 688,
                'cc_subject_id' => 54,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:49:13',
                'updated_at' => '2026-07-06 18:49:13',
            ),
            164 => 
            array (
                'id' => 689,
                'cc_subject_id' => 54,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:49:21',
                'updated_at' => '2026-07-06 18:49:21',
            ),
            165 => 
            array (
                'id' => 690,
                'cc_subject_id' => 54,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:49:31',
                'updated_at' => '2026-07-06 18:49:31',
            ),
            166 => 
            array (
                'id' => 691,
                'cc_subject_id' => 54,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:49:39',
                'updated_at' => '2026-07-06 18:49:39',
            ),
            167 => 
            array (
                'id' => 692,
                'cc_subject_id' => 54,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:49:50',
                'updated_at' => '2026-07-06 18:49:50',
            ),
            168 => 
            array (
                'id' => 693,
                'cc_subject_id' => 54,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:50:00',
                'updated_at' => '2026-07-06 18:50:00',
            ),
            169 => 
            array (
                'id' => 694,
                'cc_subject_id' => 54,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:50:25',
                'updated_at' => '2026-07-06 18:50:25',
            ),
            170 => 
            array (
                'id' => 695,
                'cc_subject_id' => 54,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:50:49',
                'updated_at' => '2026-07-06 18:50:49',
            ),
            171 => 
            array (
                'id' => 696,
                'cc_subject_id' => 55,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:51:58',
                'updated_at' => '2026-07-06 18:51:58',
            ),
            172 => 
            array (
                'id' => 697,
                'cc_subject_id' => 55,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:52:05',
                'updated_at' => '2026-07-06 18:52:05',
            ),
            173 => 
            array (
                'id' => 698,
                'cc_subject_id' => 55,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:52:15',
                'updated_at' => '2026-07-06 18:52:15',
            ),
            174 => 
            array (
                'id' => 699,
                'cc_subject_id' => 55,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:52:36',
                'updated_at' => '2026-07-06 18:52:36',
            ),
            175 => 
            array (
                'id' => 700,
                'cc_subject_id' => 56,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:54:25',
                'updated_at' => '2026-07-06 18:54:25',
            ),
            176 => 
            array (
                'id' => 701,
                'cc_subject_id' => 56,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:54:32',
                'updated_at' => '2026-07-06 18:54:32',
            ),
            177 => 
            array (
                'id' => 702,
                'cc_subject_id' => 56,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:54:39',
                'updated_at' => '2026-07-06 18:54:39',
            ),
            178 => 
            array (
                'id' => 703,
                'cc_subject_id' => 56,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:54:47',
                'updated_at' => '2026-07-06 18:54:47',
            ),
            179 => 
            array (
                'id' => 704,
                'cc_subject_id' => 57,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:56:05',
                'updated_at' => '2026-07-06 18:56:05',
            ),
            180 => 
            array (
                'id' => 705,
                'cc_subject_id' => 57,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:56:11',
                'updated_at' => '2026-07-06 18:56:11',
            ),
            181 => 
            array (
                'id' => 706,
                'cc_subject_id' => 57,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:56:21',
                'updated_at' => '2026-07-06 18:56:21',
            ),
            182 => 
            array (
                'id' => 707,
                'cc_subject_id' => 58,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:57:24',
                'updated_at' => '2026-07-06 18:57:24',
            ),
            183 => 
            array (
                'id' => 708,
                'cc_subject_id' => 58,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:57:31',
                'updated_at' => '2026-07-06 18:57:31',
            ),
            184 => 
            array (
                'id' => 709,
                'cc_subject_id' => 58,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:57:42',
                'updated_at' => '2026-07-06 18:57:42',
            ),
            185 => 
            array (
                'id' => 710,
                'cc_subject_id' => 59,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:58:57',
                'updated_at' => '2026-07-06 18:58:57',
            ),
            186 => 
            array (
                'id' => 711,
                'cc_subject_id' => 59,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 18:59:05',
                'updated_at' => '2026-07-06 18:59:05',
            ),
            187 => 
            array (
                'id' => 712,
                'cc_subject_id' => 60,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:00:00',
                'updated_at' => '2026-07-06 19:00:00',
            ),
            188 => 
            array (
                'id' => 713,
                'cc_subject_id' => 60,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:00:04',
                'updated_at' => '2026-07-06 19:00:12',
            ),
            189 => 
            array (
                'id' => 714,
                'cc_subject_id' => 60,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:00:25',
                'updated_at' => '2026-07-06 19:00:25',
            ),
            190 => 
            array (
                'id' => 715,
                'cc_subject_id' => 60,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:00:36',
                'updated_at' => '2026-07-06 19:00:36',
            ),
            191 => 
            array (
                'id' => 716,
                'cc_subject_id' => 60,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:00:48',
                'updated_at' => '2026-07-06 19:00:55',
            ),
            192 => 
            array (
                'id' => 717,
                'cc_subject_id' => 60,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:01:04',
                'updated_at' => '2026-07-06 19:01:04',
            ),
            193 => 
            array (
                'id' => 718,
                'cc_subject_id' => 60,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:01:21',
                'updated_at' => '2026-07-06 19:01:21',
            ),
            194 => 
            array (
                'id' => 719,
                'cc_subject_id' => 60,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:01:35',
                'updated_at' => '2026-07-06 19:01:35',
            ),
            195 => 
            array (
                'id' => 720,
                'cc_subject_id' => 61,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:02:26',
                'updated_at' => '2026-07-06 19:02:26',
            ),
            196 => 
            array (
                'id' => 721,
                'cc_subject_id' => 61,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:02:34',
                'updated_at' => '2026-07-06 19:02:34',
            ),
            197 => 
            array (
                'id' => 722,
                'cc_subject_id' => 61,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:02:43',
                'updated_at' => '2026-07-06 19:02:43',
            ),
            198 => 
            array (
                'id' => 723,
                'cc_subject_id' => 61,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:03:01',
                'updated_at' => '2026-07-06 19:03:01',
            ),
            199 => 
            array (
                'id' => 724,
                'cc_subject_id' => 61,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:03:13',
                'updated_at' => '2026-07-06 19:03:13',
            ),
            200 => 
            array (
                'id' => 725,
                'cc_subject_id' => 61,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:03:22',
                'updated_at' => '2026-07-06 19:03:22',
            ),
            201 => 
            array (
                'id' => 726,
                'cc_subject_id' => 61,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:03:35',
                'updated_at' => '2026-07-06 19:03:35',
            ),
            202 => 
            array (
                'id' => 727,
                'cc_subject_id' => 61,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:03:46',
                'updated_at' => '2026-07-06 19:03:46',
            ),
            203 => 
            array (
                'id' => 728,
                'cc_subject_id' => 62,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:11:40',
                'updated_at' => '2026-07-06 19:11:40',
            ),
            204 => 
            array (
                'id' => 729,
                'cc_subject_id' => 62,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:11:50',
                'updated_at' => '2026-07-06 19:11:50',
            ),
            205 => 
            array (
                'id' => 730,
                'cc_subject_id' => 62,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:11:59',
                'updated_at' => '2026-07-06 19:11:59',
            ),
            206 => 
            array (
                'id' => 731,
                'cc_subject_id' => 62,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:12:06',
                'updated_at' => '2026-07-06 19:12:06',
            ),
            207 => 
            array (
                'id' => 732,
                'cc_subject_id' => 62,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:12:15',
                'updated_at' => '2026-07-06 19:12:15',
            ),
            208 => 
            array (
                'id' => 733,
                'cc_subject_id' => 62,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:12:28',
                'updated_at' => '2026-07-06 19:12:28',
            ),
            209 => 
            array (
                'id' => 734,
                'cc_subject_id' => 62,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:12:42',
                'updated_at' => '2026-07-06 19:12:42',
            ),
            210 => 
            array (
                'id' => 735,
                'cc_subject_id' => 62,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:12:59',
                'updated_at' => '2026-07-06 19:12:59',
            ),
            211 => 
            array (
                'id' => 736,
                'cc_subject_id' => 62,
                'name' => 'فصل9',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:13:16',
                'updated_at' => '2026-07-06 19:13:16',
            ),
            212 => 
            array (
                'id' => 737,
                'cc_subject_id' => 62,
                'name' => 'فصل10',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:13:32',
                'updated_at' => '2026-07-06 19:13:32',
            ),
            213 => 
            array (
                'id' => 738,
                'cc_subject_id' => 62,
                'name' => 'فصل11',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:13:45',
                'updated_at' => '2026-07-07 17:14:11',
            ),
            214 => 
            array (
                'id' => 739,
                'cc_subject_id' => 62,
                'name' => 'فصل12',
                'order' => 11,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:14:07',
                'updated_at' => '2026-07-06 19:14:07',
            ),
            215 => 
            array (
                'id' => 740,
                'cc_subject_id' => 62,
                'name' => 'فصل13',
                'order' => 12,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:14:18',
                'updated_at' => '2026-07-06 19:14:18',
            ),
            216 => 
            array (
                'id' => 741,
                'cc_subject_id' => 62,
                'name' => 'فصل14',
                'order' => 13,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:14:42',
                'updated_at' => '2026-07-06 19:14:42',
            ),
            217 => 
            array (
                'id' => 742,
                'cc_subject_id' => 63,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:16:36',
                'updated_at' => '2026-07-06 19:16:36',
            ),
            218 => 
            array (
                'id' => 743,
                'cc_subject_id' => 63,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:16:45',
                'updated_at' => '2026-07-06 19:16:45',
            ),
            219 => 
            array (
                'id' => 744,
                'cc_subject_id' => 63,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:16:53',
                'updated_at' => '2026-07-06 19:16:53',
            ),
            220 => 
            array (
                'id' => 745,
                'cc_subject_id' => 63,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:17:48',
                'updated_at' => '2026-07-06 19:17:48',
            ),
            221 => 
            array (
                'id' => 746,
                'cc_subject_id' => 64,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:19:00',
                'updated_at' => '2026-07-06 19:19:00',
            ),
            222 => 
            array (
                'id' => 747,
                'cc_subject_id' => 64,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:19:07',
                'updated_at' => '2026-07-06 19:19:07',
            ),
            223 => 
            array (
                'id' => 748,
                'cc_subject_id' => 64,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:19:21',
                'updated_at' => '2026-07-06 19:19:21',
            ),
            224 => 
            array (
                'id' => 749,
                'cc_subject_id' => 64,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:19:33',
                'updated_at' => '2026-07-06 19:19:33',
            ),
            225 => 
            array (
                'id' => 750,
                'cc_subject_id' => 65,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:26:31',
                'updated_at' => '2026-07-06 19:26:31',
            ),
            226 => 
            array (
                'id' => 751,
                'cc_subject_id' => 65,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:26:37',
                'updated_at' => '2026-07-06 19:26:37',
            ),
            227 => 
            array (
                'id' => 752,
                'cc_subject_id' => 65,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:26:49',
                'updated_at' => '2026-07-06 19:26:49',
            ),
            228 => 
            array (
                'id' => 753,
                'cc_subject_id' => 65,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:27:03',
                'updated_at' => '2026-07-06 19:27:03',
            ),
            229 => 
            array (
                'id' => 754,
                'cc_subject_id' => 67,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:28:05',
                'updated_at' => '2026-07-06 19:28:05',
            ),
            230 => 
            array (
                'id' => 755,
                'cc_subject_id' => 67,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:28:12',
                'updated_at' => '2026-07-06 19:28:12',
            ),
            231 => 
            array (
                'id' => 756,
                'cc_subject_id' => 67,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:28:20',
                'updated_at' => '2026-07-06 19:28:20',
            ),
            232 => 
            array (
                'id' => 757,
                'cc_subject_id' => 67,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:28:32',
                'updated_at' => '2026-07-06 19:28:32',
            ),
            233 => 
            array (
                'id' => 758,
                'cc_subject_id' => 67,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:28:44',
                'updated_at' => '2026-07-06 19:28:44',
            ),
            234 => 
            array (
                'id' => 759,
                'cc_subject_id' => 67,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:28:56',
                'updated_at' => '2026-07-06 19:28:56',
            ),
            235 => 
            array (
                'id' => 760,
                'cc_subject_id' => 67,
                'name' => 'فصل7',
                'order' => 6,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:29:12',
                'updated_at' => '2026-07-06 19:29:12',
            ),
            236 => 
            array (
                'id' => 761,
                'cc_subject_id' => 67,
                'name' => 'فصل8',
                'order' => 7,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:29:23',
                'updated_at' => '2026-07-06 19:29:23',
            ),
            237 => 
            array (
                'id' => 762,
                'cc_subject_id' => 67,
                'name' => 'فصل9',
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:29:37',
                'updated_at' => '2026-07-06 19:29:37',
            ),
            238 => 
            array (
                'id' => 763,
                'cc_subject_id' => 67,
                'name' => 'فصل10',
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:29:50',
                'updated_at' => '2026-07-06 19:29:50',
            ),
            239 => 
            array (
                'id' => 764,
                'cc_subject_id' => 67,
                'name' => 'فصل11',
                'order' => 10,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:30:04',
                'updated_at' => '2026-07-06 19:30:04',
            ),
            240 => 
            array (
                'id' => 765,
                'cc_subject_id' => 67,
                'name' => 'فصل12',
                'order' => 11,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:30:21',
                'updated_at' => '2026-07-06 19:30:21',
            ),
            241 => 
            array (
                'id' => 766,
                'cc_subject_id' => 67,
                'name' => 'فصل13',
                'order' => 12,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:30:30',
                'updated_at' => '2026-07-06 19:30:30',
            ),
            242 => 
            array (
                'id' => 767,
                'cc_subject_id' => 67,
                'name' => 'فصل14',
                'order' => 13,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:30:40',
                'updated_at' => '2026-07-06 19:30:40',
            ),
            243 => 
            array (
                'id' => 768,
                'cc_subject_id' => 67,
                'name' => 'فصل15',
                'order' => 14,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:30:52',
                'updated_at' => '2026-07-06 19:30:52',
            ),
            244 => 
            array (
                'id' => 769,
                'cc_subject_id' => 67,
                'name' => 'فصل16',
                'order' => 15,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:31:05',
                'updated_at' => '2026-07-06 19:31:05',
            ),
            245 => 
            array (
                'id' => 770,
                'cc_subject_id' => 67,
                'name' => 'فصل17',
                'order' => 16,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:31:14',
                'updated_at' => '2026-07-06 19:31:14',
            ),
            246 => 
            array (
                'id' => 771,
                'cc_subject_id' => 67,
                'name' => 'فصل18',
                'order' => 17,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:31:42',
                'updated_at' => '2026-07-06 19:31:42',
            ),
            247 => 
            array (
                'id' => 772,
                'cc_subject_id' => 67,
                'name' => 'فصل19',
                'order' => 18,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:31:55',
                'updated_at' => '2026-07-06 19:31:55',
            ),
            248 => 
            array (
                'id' => 773,
                'cc_subject_id' => 67,
                'name' => 'فصل20',
                'order' => 19,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:32:04',
                'updated_at' => '2026-07-06 19:32:04',
            ),
            249 => 
            array (
                'id' => 774,
                'cc_subject_id' => 67,
                'name' => 'فصل21',
                'order' => 20,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:32:18',
                'updated_at' => '2026-07-06 19:32:18',
            ),
            250 => 
            array (
                'id' => 775,
                'cc_subject_id' => 67,
                'name' => 'فصل22',
                'order' => 21,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:32:30',
                'updated_at' => '2026-07-06 19:32:30',
            ),
            251 => 
            array (
                'id' => 776,
                'cc_subject_id' => 67,
                'name' => 'فصل23',
                'order' => 22,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:32:42',
                'updated_at' => '2026-07-06 19:32:42',
            ),
            252 => 
            array (
                'id' => 777,
                'cc_subject_id' => 67,
                'name' => 'فصل24',
                'order' => 23,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:32:51',
                'updated_at' => '2026-07-06 19:32:51',
            ),
            253 => 
            array (
                'id' => 778,
                'cc_subject_id' => 67,
                'name' => 'فصل25',
                'order' => 24,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:33:01',
                'updated_at' => '2026-07-06 19:33:01',
            ),
            254 => 
            array (
                'id' => 779,
                'cc_subject_id' => 67,
                'name' => 'فصل26',
                'order' => 25,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:33:10',
                'updated_at' => '2026-07-06 19:33:10',
            ),
            255 => 
            array (
                'id' => 780,
                'cc_subject_id' => 67,
                'name' => 'فصل27',
                'order' => 26,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:33:19',
                'updated_at' => '2026-07-06 19:33:36',
            ),
            256 => 
            array (
                'id' => 781,
                'cc_subject_id' => 67,
                'name' => 'فصل28',
                'order' => 27,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:33:50',
                'updated_at' => '2026-07-06 19:33:50',
            ),
            257 => 
            array (
                'id' => 782,
                'cc_subject_id' => 67,
                'name' => 'فصل29',
                'order' => 28,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:34:07',
                'updated_at' => '2026-07-06 19:34:07',
            ),
            258 => 
            array (
                'id' => 783,
                'cc_subject_id' => 67,
                'name' => 'فصل30',
                'order' => 29,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:34:16',
                'updated_at' => '2026-07-06 19:34:16',
            ),
            259 => 
            array (
                'id' => 784,
                'cc_subject_id' => 67,
                'name' => 'فصل31',
                'order' => 30,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:34:27',
                'updated_at' => '2026-07-06 19:34:27',
            ),
            260 => 
            array (
                'id' => 785,
                'cc_subject_id' => 67,
                'name' => 'فصل32',
                'order' => 31,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:34:49',
                'updated_at' => '2026-07-06 19:34:49',
            ),
            261 => 
            array (
                'id' => 786,
                'cc_subject_id' => 68,
                'name' => 'فصل1',
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:35:09',
                'updated_at' => '2026-07-06 19:35:09',
            ),
            262 => 
            array (
                'id' => 787,
                'cc_subject_id' => 68,
                'name' => 'فصل2',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:35:42',
                'updated_at' => '2026-07-06 19:35:42',
            ),
            263 => 
            array (
                'id' => 788,
                'cc_subject_id' => 68,
                'name' => 'فصل3',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:35:49',
                'updated_at' => '2026-07-06 19:35:49',
            ),
            264 => 
            array (
                'id' => 789,
                'cc_subject_id' => 68,
                'name' => 'فصل4',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:36:01',
                'updated_at' => '2026-07-06 19:36:01',
            ),
            265 => 
            array (
                'id' => 790,
                'cc_subject_id' => 68,
                'name' => 'فصل5',
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:36:08',
                'updated_at' => '2026-07-06 19:36:08',
            ),
            266 => 
            array (
                'id' => 791,
                'cc_subject_id' => 68,
                'name' => 'فصل6',
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-07-06 19:36:20',
                'updated_at' => '2026-07-06 19:36:20',
            ),
        ));
        
        
    }
}