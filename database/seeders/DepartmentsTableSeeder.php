<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('departments')->delete();
        
        \DB::table('departments')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'پشتیبانی',
                'created_at' => '2025-07-31 11:37:30',
                'updated_at' => '2025-07-31 11:37:30',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'فنی',
                'created_at' => '2025-07-31 11:37:33',
                'updated_at' => '2025-07-31 11:37:33',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'گزارش',
                'created_at' => '2025-07-31 11:37:51',
                'updated_at' => '2025-07-31 11:37:51',
            ),
        ));
        
        
    }
}