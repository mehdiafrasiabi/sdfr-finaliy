<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class StudentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('students')->delete();

        \DB::table('students')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'user_id' => 1,
                    'advisor_id' => 11,

                    'payment_id' => 1,
                    'product_id' => NULL,
                    'star' => 'D',
                    'created_at' => '2025-10-18 23:41:41',
                    'updated_at' => '2025-10-18 23:41:41',
                ),
            1 =>
                array (
                    'id' => 2,
                    'user_id' => 3,
                    'advisor_id' => 11,

                    'payment_id' => 2,
                    'product_id' => NULL,
                    'star' => 'D',
                    'created_at' => '2025-10-20 14:45:59',
                    'updated_at' => '2025-10-20 14:45:59',
                ),
            2 =>
                array (
                    'id' => 3,
                    'user_id' => 5,
                    'advisor_id' => 11,

                    'payment_id' => 3,
                    'product_id' => NULL,
                    'star' => 'D',
                    'created_at' => '2025-10-25 12:35:47',
                    'updated_at' => '2025-10-25 12:37:09',
                ),
            3 =>
                array (
                    'id' => 4,
                    'user_id' => 6,
                    'advisor_id' => 11,
                    'payment_id' => 4,
                    'product_id' => NULL,
                    'star' => 'D',
                    'created_at' => '2025-10-25 15:21:17',
                    'updated_at' => '2025-10-25 15:54:00',
                ),
            4 =>
                array (
                    'id' => 5,
                    'user_id' => 14,
                    'advisor_id' => 17,

                    'payment_id' => 5,
                    'product_id' => NULL,
                    'star' => 'B',
                    'created_at' => '2025-11-03 15:36:26',
                    'updated_at' => '2025-11-14 12:14:21',
                ),
            5 =>
                array (
                    'id' => 6,
                    'user_id' => 15,
                    'advisor_id' => 17,

                    'payment_id' => 6,
                    'product_id' => NULL,
                    'star' => 'C',
                    'created_at' => '2025-11-03 16:08:19',
                    'updated_at' => '2025-11-14 12:14:57',
                ),
            6 =>
                array (
                    'id' => 7,
                    'user_id' => 16,
                    'advisor_id' => 17,

                    'payment_id' => 7,
                    'product_id' => NULL,
                    'star' => 'C',
                    'created_at' => '2025-11-03 17:11:12',
                    'updated_at' => '2025-11-14 12:14:32',
                ),
            7 =>
                array (
                    'id' => 8,
                    'user_id' => 7,
                    'advisor_id' => 17,

                    'payment_id' => 8,
                    'product_id' => NULL,
                    'star' => 'B',
                    'created_at' => '2025-11-03 17:56:56',
                    'updated_at' => '2025-11-14 12:14:37',
                ),
            8 =>
                array (
                    'id' => 9,
                    'user_id' => 8,
                    'advisor_id' => 17,

                    'payment_id' => 9,
                    'product_id' => NULL,
                    'star' => 'C',
                    'created_at' => '2025-11-03 17:58:07',
                    'updated_at' => '2025-11-14 12:14:49',
                ),
            9 =>
                array (
                    'id' => 10,
                    'user_id' => 17,
                    'advisor_id' => 17,

                    'payment_id' => 10,
                    'product_id' => NULL,
                    'star' => 'C',
                    'created_at' => '2025-11-03 18:12:13',
                    'updated_at' => '2025-11-14 12:15:04',
                ),
            10 =>
                array (
                    'id' => 11,
                    'user_id' => 18,
                    'advisor_id' => 17,

                    'payment_id' => 12,
                    'product_id' => NULL,
                    'star' => 'C',
                    'created_at' => '2025-11-05 17:09:14',
                    'updated_at' => '2025-11-14 12:15:10',
                ),
            11 =>
                array (
                    'id' => 12,
                    'user_id' => 20,
                    'advisor_id' => 17,

                    'payment_id' => 13,
                    'product_id' => NULL,
                    'star' => 'C',
                    'created_at' => '2025-11-05 17:10:48',
                    'updated_at' => '2025-11-14 12:15:16',
                ),
            12 =>
                array (
                    'id' => 13,
                    'user_id' => 21,
                    'advisor_id' => 17,

                    'payment_id' => 14,
                    'product_id' => NULL,
                    'star' => 'D',
                    'created_at' => '2025-11-05 17:35:46',
                    'updated_at' => '2025-11-05 17:35:46',
                ),
            13 =>
                array (
                    'id' => 14,
                    'user_id' => 22,
                    'advisor_id' => 17,

                    'payment_id' => 15,
                    'product_id' => NULL,
                    'star' => 'D',
                    'created_at' => '2025-11-05 18:06:18',
                    'updated_at' => '2025-11-05 18:06:18',
                ),
            14 =>
                array (
                    'id' => 15,
                    'user_id' => 23,
                    'advisor_id' => 17,

                    'payment_id' => 16,
                    'product_id' => NULL,
                    'star' => 'B',
                    'created_at' => '2025-11-05 18:42:46',
                    'updated_at' => '2025-11-14 12:15:30',
                ),
            15 =>
                array (
                    'id' => 16,
                    'user_id' => 11,
                    'advisor_id' => 17,

                    'payment_id' => 17,
                    'product_id' => NULL,
                    'star' => 'D',
                    'created_at' => '2025-11-14 14:58:28',
                    'updated_at' => '2025-11-14 14:58:28',
                ),
            16 =>
                array (
                    'id' => 17,
                    'user_id' => 25,
                    'advisor_id' => 17,

                    'payment_id' => 18,
                    'product_id' => NULL,
                    'star' => 'C',
                    'created_at' => '2025-11-06 16:44:10',
                    'updated_at' => '2025-11-14 12:15:36',
                ),
            17 =>
                array (
                    'id' => 18,
                    'user_id' => 26,
                    'advisor_id' => 17,

                    'payment_id' => 19,
                    'product_id' => NULL,
                    'star' => 'C',
                    'created_at' => '2025-11-06 16:50:20',
                    'updated_at' => '2025-11-14 12:15:40',
                ),
            18 =>
                array (
                    'id' => 19,
                    'user_id' => 9,
                    'advisor_id' => 17,

                    'payment_id' => 20,
                    'product_id' => NULL,
                    'star' => 'C',
                    'created_at' => '2025-11-06 17:06:12',
                    'updated_at' => '2025-11-14 12:15:45',
                ),
            19 =>
                array (
                    'id' => 20,
                    'user_id' => 10,
                    'advisor_id' => 17,

                    'payment_id' => 22,
                    'product_id' => NULL,
                    'star' => 'B',
                    'created_at' => '2025-11-06 19:13:38',
                    'updated_at' => '2025-11-14 12:15:49',
                ),
            20 =>
                array (
                    'id' => 21,
                    'user_id' => 27,
                    'advisor_id' => 17,

                    'payment_id' => 21,
                    'product_id' => NULL,
                    'star' => 'B',
                    'created_at' => '2025-11-06 19:14:23',
                    'updated_at' => '2025-11-14 12:15:59',
                ),
            21 =>
                array (
                    'id' => 22,
                    'user_id' => 29,
                    'advisor_id' => 17,

                    'payment_id' => 23,
                    'product_id' => NULL,
                    'star' => 'D',
                    'created_at' => '2025-11-08 09:05:22',
                    'updated_at' => '2025-11-08 09:05:22',
                ),
            22 =>
                array (
                    'id' => 23,
                    'user_id' => 12,
                    'advisor_id' => 17,

                    'payment_id' => 24,
                    'product_id' => NULL,
                    'star' => 'D',
                    'created_at' => '2025-11-09 16:50:38',
                    'updated_at' => '2025-11-09 16:50:38',
                ),
            23 =>
                array (
                    'id' => 24,
                    'user_id' => 30,
                    'advisor_id' => 17,

                    'payment_id' => 25,
                    'product_id' => NULL,
                    'star' => 'D',
                    'created_at' => '2025-11-09 16:57:57',
                    'updated_at' => '2025-11-09 16:57:57',
                ),
            24 =>
                array (
                    'id' => 25,
                    'user_id' => 31,
                    'advisor_id' => 17,

                    'payment_id' => 26,
                    'product_id' => NULL,
                    'star' => 'D',
                    'created_at' => '2025-11-10 16:39:15',
                    'updated_at' => '2025-11-10 17:06:35',
                ),
            25 =>
                array (
                    'id' => 26,
                    'user_id' => 33,
                    'advisor_id' => 17,

                    'payment_id' => 27,
                    'product_id' => NULL,
                    'star' => 'D',
                    'created_at' => '2025-11-10 20:01:58',
                    'updated_at' => '2025-11-10 20:01:58',
                ),
            26 =>
                array (
                    'id' => 27,
                    'user_id' => 28,
                    'advisor_id' => 17,

                    'payment_id' => 28,
                    'product_id' => NULL,
                    'star' => 'B',
                    'created_at' => '2025-11-13 18:31:18',
                    'updated_at' => '2025-11-14 12:16:13',
                ),
        ));


    }
}
