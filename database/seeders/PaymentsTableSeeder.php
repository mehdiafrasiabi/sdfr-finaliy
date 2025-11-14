<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class PaymentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('payments')->delete();
        
        \DB::table('payments')->insert(array (
            0 => 
            array (
                'id' => 1,
                'order_id' => 1,
                'user_id' => 1,
                'amount' => 15000000,
                'order_number' => 'REF-049762d7-8aad-4372-ac11-e58d77013ed0',
                'refNumber' => '1853985',
                'cardNumber' => NULL,
                'personal_information_id' => 1,
                'status' => 'completed',
                'created_at' => '2025-07-24 15:38:43',
                'updated_at' => '2025-07-24 15:39:18',
            ),
            1 => 
            array (
                'id' => 2,
                'order_id' => 2,
                'user_id' => 2,
                'amount' => 25000000,
                'order_number' => 'REF-31f304d7-0c9d-4381-992e-f203025019f1',
                'refNumber' => NULL,
                'cardNumber' => NULL,
                'personal_information_id' => 2,
                'status' => 'completed',
                'created_at' => '2025-07-26 20:19:58',
                'updated_at' => '2025-07-26 20:20:01',
            ),
        ));
        
        
    }
}