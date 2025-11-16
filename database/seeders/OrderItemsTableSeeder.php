<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class OrderItemsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('order_items')->delete();
        
        \DB::table('order_items')->insert(array (
            0 => 
            array (
                'id' => 1,
                'price' => 25000000,
                'order_id' => 1,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-10-18 23:41:38',
                'updated_at' => '2025-10-18 23:41:38',
            ),
            1 => 
            array (
                'id' => 2,
                'price' => 25000000,
                'order_id' => 2,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-10-20 14:45:57',
                'updated_at' => '2025-10-20 14:45:57',
            ),
            2 => 
            array (
                'id' => 3,
                'price' => 25000000,
                'order_id' => 3,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-10-25 12:35:44',
                'updated_at' => '2025-10-25 12:35:44',
            ),
            3 => 
            array (
                'id' => 4,
                'price' => 20000000,
                'order_id' => 4,
                'product_id' => 8,
                'deleted_at' => NULL,
                'created_at' => '2025-10-25 15:21:13',
                'updated_at' => '2025-10-25 15:21:13',
            ),
            4 => 
            array (
                'id' => 5,
                'price' => 25000000,
                'order_id' => 5,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-03 15:36:08',
                'updated_at' => '2025-11-03 15:36:08',
            ),
            5 => 
            array (
                'id' => 6,
                'price' => 25000000,
                'order_id' => 6,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-03 16:08:10',
                'updated_at' => '2025-11-03 16:08:10',
            ),
            6 => 
            array (
                'id' => 7,
                'price' => 25000000,
                'order_id' => 7,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-03 17:11:04',
                'updated_at' => '2025-11-03 17:11:04',
            ),
            7 => 
            array (
                'id' => 8,
                'price' => 25000000,
                'order_id' => 8,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-03 17:56:47',
                'updated_at' => '2025-11-03 17:56:47',
            ),
            8 => 
            array (
                'id' => 9,
                'price' => 25000000,
                'order_id' => 9,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-03 17:57:13',
                'updated_at' => '2025-11-03 17:57:13',
            ),
            9 => 
            array (
                'id' => 10,
                'price' => 25000000,
                'order_id' => 10,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-03 18:12:00',
                'updated_at' => '2025-11-03 18:12:00',
            ),
            10 => 
            array (
                'id' => 11,
                'price' => 25000000,
                'order_id' => 11,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-05 17:07:49',
                'updated_at' => '2025-11-05 17:07:49',
            ),
            11 => 
            array (
                'id' => 12,
                'price' => 25000000,
                'order_id' => 12,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-05 17:08:29',
                'updated_at' => '2025-11-05 17:08:29',
            ),
            12 => 
            array (
                'id' => 13,
                'price' => 25000000,
                'order_id' => 13,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-05 17:10:32',
                'updated_at' => '2025-11-05 17:10:32',
            ),
            13 => 
            array (
                'id' => 14,
                'price' => 25000000,
                'order_id' => 14,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-05 17:35:22',
                'updated_at' => '2025-11-05 17:35:22',
            ),
            14 => 
            array (
                'id' => 15,
                'price' => 25000000,
                'order_id' => 15,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-05 18:04:18',
                'updated_at' => '2025-11-05 18:04:18',
            ),
            15 => 
            array (
                'id' => 16,
                'price' => 25000000,
                'order_id' => 16,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-05 18:42:39',
                'updated_at' => '2025-11-05 18:42:39',
            ),
            16 => 
            array (
                'id' => 17,
                'price' => 25000000,
                'order_id' => 17,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-06 14:55:09',
                'updated_at' => '2025-11-06 14:55:09',
            ),
            17 => 
            array (
                'id' => 18,
                'price' => 25000000,
                'order_id' => 18,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-06 16:43:55',
                'updated_at' => '2025-11-06 16:43:55',
            ),
            18 => 
            array (
                'id' => 19,
                'price' => 25000000,
                'order_id' => 19,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-06 16:50:15',
                'updated_at' => '2025-11-06 16:50:15',
            ),
            19 => 
            array (
                'id' => 20,
                'price' => 25000000,
                'order_id' => 20,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-06 17:05:58',
                'updated_at' => '2025-11-06 17:05:58',
            ),
            20 => 
            array (
                'id' => 21,
                'price' => 25000000,
                'order_id' => 21,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-06 19:11:56',
                'updated_at' => '2025-11-06 19:11:56',
            ),
            21 => 
            array (
                'id' => 22,
                'price' => 25000000,
                'order_id' => 22,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-06 19:13:30',
                'updated_at' => '2025-11-06 19:13:30',
            ),
            22 => 
            array (
                'id' => 23,
                'price' => 25000000,
                'order_id' => 23,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-08 09:05:14',
                'updated_at' => '2025-11-08 09:05:14',
            ),
            23 => 
            array (
                'id' => 24,
                'price' => 25000000,
                'order_id' => 24,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-09 16:50:18',
                'updated_at' => '2025-11-09 16:50:18',
            ),
            24 => 
            array (
                'id' => 25,
                'price' => 25000000,
                'order_id' => 25,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-09 16:57:53',
                'updated_at' => '2025-11-09 16:57:53',
            ),
            25 => 
            array (
                'id' => 26,
                'price' => 25000000,
                'order_id' => 26,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-10 16:39:05',
                'updated_at' => '2025-11-10 16:39:05',
            ),
            26 => 
            array (
                'id' => 27,
                'price' => 25000000,
                'order_id' => 27,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-10 20:01:17',
                'updated_at' => '2025-11-10 20:01:17',
            ),
            27 => 
            array (
                'id' => 28,
                'price' => 25000000,
                'order_id' => 28,
                'product_id' => 7,
                'deleted_at' => NULL,
                'created_at' => '2025-11-13 18:31:14',
                'updated_at' => '2025-11-13 18:31:14',
            ),
        ));
        
        
    }
}