<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('orders')->delete();

        \DB::table('orders')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'amount' => 25000000,
                    'order_number' => 'REF-df207289-f8bc-44a0-9e2b-ff3e587f6398',
                    'user_id' => 1,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-10-18 23:41:38',
                    'updated_at' => '2025-10-18 23:41:41',
                ),
            1 =>
                array (
                    'id' => 2,
                    'amount' => 25000000,
                    'order_number' => 'REF-97d4c5b8-ca5a-4ff7-a93c-afce7076f6d5',
                    'user_id' => 3,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-10-20 14:45:57',
                    'updated_at' => '2025-10-20 14:45:59',
                ),
            2 =>
                array (
                    'id' => 3,
                    'amount' => 25000000,
                    'order_number' => 'REF-28bf2f0a-3f9e-4b01-9adb-e3f5f49e9ee9',
                    'user_id' => 5,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-10-25 12:35:44',
                    'updated_at' => '2025-10-25 12:35:47',
                ),
            3 =>
                array (
                    'id' => 4,
                    'amount' => 20000000,
                    'order_number' => 'REF-d6ad60b3-189c-415b-8f39-15c84ad184dd',
                    'user_id' => 6,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-10-25 15:21:13',
                    'updated_at' => '2025-10-25 15:21:17',
                ),
            4 =>
                array (
                    'id' => 5,
                    'amount' => 25000000,
                    'order_number' => 'REF-92730567-d8c0-421b-9071-a30cb0985f30',
                    'user_id' => 14,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-03 15:36:08',
                    'updated_at' => '2025-11-03 15:36:26',
                ),
            5 =>
                array (
                    'id' => 6,
                    'amount' => 25000000,
                    'order_number' => 'REF-543e4393-1ee3-4bf6-8c91-ac1665226596',
                    'user_id' => 15,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-03 16:08:10',
                    'updated_at' => '2025-11-03 16:08:19',
                ),
            6 =>
                array (
                    'id' => 7,
                    'amount' => 25000000,
                    'order_number' => 'REF-fd212f43-0689-4e0f-b012-3ffdf9b7fc92',
                    'user_id' => 16,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-03 17:11:04',
                    'updated_at' => '2025-11-03 17:11:12',
                ),
            7 =>
                array (
                    'id' => 8,
                    'amount' => 25000000,
                    'order_number' => 'REF-0ee3201d-fd25-4831-bef5-4c7b9940b7bd',
                    'user_id' => 7,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-03 17:56:47',
                    'updated_at' => '2025-11-03 17:56:56',
                ),
            8 =>
                array (
                    'id' => 9,
                    'amount' => 25000000,
                    'order_number' => 'REF-b1a0a3ef-5f3c-4901-8a23-bdaf493ea719',
                    'user_id' => 8,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-03 17:57:13',
                    'updated_at' => '2025-11-03 17:58:07',
                ),
            9 =>
                array (
                    'id' => 10,
                    'amount' => 25000000,
                    'order_number' => 'REF-6561c121-3867-4431-bbf4-38bffd62a34d',
                    'user_id' => 17,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-03 18:12:00',
                    'updated_at' => '2025-11-03 18:12:13',
                ),
            10 =>
                array (
                    'id' => 11,
                    'amount' => 25000000,
                    'order_number' => 'REF-cf240fc7-0b1d-4f23-9aeb-ae7bc706e694',
                    'user_id' => 19,
                    'payment_method_id' => 1,
                    'status' => 'pending',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-05 17:07:49',
                    'updated_at' => '2025-11-05 17:07:49',
                ),
            11 =>
                array (
                    'id' => 12,
                    'amount' => 25000000,
                    'order_number' => 'REF-f8443830-3f23-4375-bff3-7ca6590d5156',
                    'user_id' => 18,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-05 17:08:29',
                    'updated_at' => '2025-11-05 17:09:14',
                ),
            12 =>
                array (
                    'id' => 13,
                    'amount' => 25000000,
                    'order_number' => 'REF-d8ad84f9-ce34-474f-bd4d-b875837ad78a',
                    'user_id' => 20,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-05 17:10:32',
                    'updated_at' => '2025-11-05 17:10:48',
                ),
            13 =>
                array (
                    'id' => 14,
                    'amount' => 25000000,
                    'order_number' => 'REF-1f2bf07e-99b1-45f0-bf3b-1a2cd7b229b2',
                    'user_id' => 21,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-05 17:35:22',
                    'updated_at' => '2025-11-05 17:35:46',
                ),
            14 =>
                array (
                    'id' => 15,
                    'amount' => 25000000,
                    'order_number' => 'REF-908fc0c3-b047-48bb-a114-3a17ad48b359',
                    'user_id' => 22,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-05 18:04:18',
                    'updated_at' => '2025-11-05 18:06:18',
                ),
            15 =>
                array (
                    'id' => 16,
                    'amount' => 25000000,
                    'order_number' => 'REF-f70d9945-a356-43df-a71b-6d900bfa9c60',
                    'user_id' => 23,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-05 18:42:39',
                    'updated_at' => '2025-11-05 18:42:46',
                ),
            16 =>
                array (
                    'id' => 17,
                    'amount' => 25000000,
                    'order_number' => 'REF-89092271-5d6f-4d34-8894-e007e9d88bcb',
                    'user_id' => 11,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-06 14:55:09',
                    'updated_at' => '2025-11-06 14:58:28',
                ),
            17 =>
                array (
                    'id' => 18,
                    'amount' => 25000000,
                    'order_number' => 'REF-6375acd0-9af8-424f-b49e-a585f444aa5b',
                    'user_id' => 25,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-06 16:43:55',
                    'updated_at' => '2025-11-06 16:44:10',
                ),
            18 =>
                array (
                    'id' => 19,
                    'amount' => 25000000,
                    'order_number' => 'REF-00abd69c-0332-4d1f-b44a-6ee331bf6678',
                    'user_id' => 26,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-06 16:50:15',
                    'updated_at' => '2025-11-06 16:50:20',
                ),
            19 =>
                array (
                    'id' => 20,
                    'amount' => 25000000,
                    'order_number' => 'REF-fa24a3a3-8c06-4c79-a7bd-36d88580538e',
                    'user_id' => 9,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-06 17:05:58',
                    'updated_at' => '2025-11-06 17:06:12',
                ),
            20 =>
                array (
                    'id' => 21,
                    'amount' => 25000000,
                    'order_number' => 'REF-8bbf4910-bb5e-4f3a-a2c3-8aebe120e146',
                    'user_id' => 27,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-06 19:11:56',
                    'updated_at' => '2025-11-06 19:14:23',
                ),
            21 =>
                array (
                    'id' => 22,
                    'amount' => 25000000,
                    'order_number' => 'REF-21f45f1a-37f5-4df7-bdec-075179154afa',
                    'user_id' => 10,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-06 19:13:30',
                    'updated_at' => '2025-11-06 19:13:38',
                ),
            22 =>
                array (
                    'id' => 23,
                    'amount' => 25000000,
                    'order_number' => 'REF-e2fde0c1-9dc9-4488-8744-b34124345223',
                    'user_id' => 29,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-08 09:05:14',
                    'updated_at' => '2025-11-08 09:05:22',
                ),
            23 =>
                array (
                    'id' => 24,
                    'amount' => 25000000,
                    'order_number' => 'REF-0cb23d4a-f8d2-4012-92f3-40c844a98066',
                    'user_id' => 12,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-09 16:50:18',
                    'updated_at' => '2025-11-09 16:50:38',
                ),
            24 =>
                array (
                    'id' => 25,
                    'amount' => 25000000,
                    'order_number' => 'REF-a3322106-cb75-463d-a8e9-2f73016a9ab8',
                    'user_id' => 30,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-09 16:57:53',
                    'updated_at' => '2025-11-09 16:57:57',
                ),
            25 =>
                array (
                    'id' => 26,
                    'amount' => 25000000,
                    'order_number' => 'REF-a2d6ef63-6b89-49eb-ab54-519d752e5438',
                    'user_id' => 31,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-10 16:39:05',
                    'updated_at' => '2025-11-10 16:39:15',
                ),
            26 =>
                array (
                    'id' => 27,
                    'amount' => 25000000,
                    'order_number' => 'REF-67f6bb5c-f0dd-4555-af55-47c400bcddaa',
                    'user_id' => 33,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-10 20:01:17',
                    'updated_at' => '2025-11-10 20:01:58',
                ),
            27 =>
                array (
                    'id' => 28,
                    'amount' => 25000000,
                    'order_number' => 'REF-cb806e26-59b4-49c8-98bf-7864bc7b4c98',
                    'user_id' => 28,
                    'payment_method_id' => 1,
                    'status' => 'processing',
                    'deleted_at' => NULL,
                    'created_at' => '2025-11-13 18:31:14',
                    'updated_at' => '2025-11-13 18:31:19',
                ),
        ));


    }
}
