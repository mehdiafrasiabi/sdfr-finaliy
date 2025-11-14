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
                'price' => 15000000,
                'order_id' => 1,
                'product_id' => 2,
                'deleted_at' => NULL,
                'created_at' => '2025-07-24 15:38:43',
                'updated_at' => '2025-07-24 15:38:43',
            ),
            1 => 
            array (
                'id' => 2,
                'price' => 25000000,
                'order_id' => 2,
                'product_id' => 3,
                'deleted_at' => NULL,
                'created_at' => '2025-07-26 20:19:58',
                'updated_at' => '2025-07-26 20:19:58',
            ),
        ));
        
        
    }
}