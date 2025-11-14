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
                'admin_id' => 6,
                'payment_id' => 1,
                'created_at' => '2025-07-24 15:39:18',
                'updated_at' => '2025-07-24 15:39:28',
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => 2,
                'admin_id' => 6,
                'payment_id' => 2,
                'created_at' => '2025-07-26 20:20:01',
                'updated_at' => '2025-07-26 20:21:05',
            ),
        ));
        
        
    }
}