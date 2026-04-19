<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class ExamPeriodsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('exam_periods')->delete();
        
        \DB::table('exam_periods')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => '۱۴۰۳-۱۴۰۴',
                'value' => '1403-1404',
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-19 15:19:08',
                'updated_at' => '2026-02-19 15:19:08',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => '۱۴۰۴-۱۴۰۵',
                'value' => '1404-1405',
                'order' => 2,
                'is_active' => 1,
                'created_at' => '2026-02-19 15:19:08',
                'updated_at' => '2026-02-19 15:19:08',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => '۱۴۰۵-۱۴۰۶',
                'value' => '1405-1406',
                'order' => 3,
                'is_active' => 1,
                'created_at' => '2026-02-19 15:19:08',
                'updated_at' => '2026-02-19 15:19:08',
            ),
        ));
        
        
    }
}