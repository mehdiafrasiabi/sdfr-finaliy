<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CcGradesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('cc_grades')->delete();
        
        \DB::table('cc_grades')->insert(array (
            0 => 
            array (
                'id' => 1,
                'education_level_id' => 1,
                'cc_field_id' => 1,
            'name' => 'پایه دوازدهم (ریاضی و فیزیک)',
                'grade_number' => 12,
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-07 14:08:27',
                'updated_at' => '2026-02-07 14:08:27',
            ),
            1 => 
            array (
                'id' => 2,
                'education_level_id' => 1,
                'cc_field_id' => 2,
            'name' => 'پایه دوازدهم (علوم تجربی)',
                'grade_number' => 12,
                'order' => 1,
                'is_active' => 1,
                'created_at' => '2026-02-07 14:08:53',
                'updated_at' => '2026-02-07 14:08:53',
            ),
            2 => 
            array (
                'id' => 3,
                'education_level_id' => 1,
                'cc_field_id' => 3,
            'name' => 'پایه دوازدهم (علوم انسانی)',
                'grade_number' => 12,
                'order' => 2,
                'is_active' => 0,
                'created_at' => '2026-02-07 14:09:20',
                'updated_at' => '2026-02-08 18:54:26',
            ),
            3 => 
            array (
                'id' => 5,
                'education_level_id' => 1,
                'cc_field_id' => 1,
            'name' => 'پایه یازدهم (ریاضی و فیزیک)',
                'grade_number' => 11,
                'order' => 4,
                'is_active' => 1,
                'created_at' => '2026-02-07 14:10:28',
                'updated_at' => '2026-02-07 14:10:28',
            ),
            4 => 
            array (
                'id' => 6,
                'education_level_id' => 1,
                'cc_field_id' => 2,
            'name' => 'پایه یازدهم (علوم تجربی)',
                'grade_number' => 11,
                'order' => 5,
                'is_active' => 1,
                'created_at' => '2026-02-07 14:10:44',
                'updated_at' => '2026-02-07 14:10:44',
            ),
            5 => 
            array (
                'id' => 7,
                'education_level_id' => 1,
                'cc_field_id' => 3,
            'name' => 'پایه یازدهم (علوم انسانی)',
                'grade_number' => 11,
                'order' => 6,
                'is_active' => 0,
                'created_at' => '2026-02-07 14:11:03',
                'updated_at' => '2026-02-08 18:55:22',
            ),
            6 => 
            array (
                'id' => 9,
                'education_level_id' => 1,
                'cc_field_id' => 1,
            'name' => 'پایه دهم (ریاضی و فیزیک)',
                'grade_number' => 10,
                'order' => 8,
                'is_active' => 1,
                'created_at' => '2026-02-07 14:12:20',
                'updated_at' => '2026-02-07 14:12:20',
            ),
            7 => 
            array (
                'id' => 10,
                'education_level_id' => 1,
                'cc_field_id' => 2,
            'name' => 'پایه دهم (علوم تجربی)',
                'grade_number' => 10,
                'order' => 9,
                'is_active' => 1,
                'created_at' => '2026-02-07 14:12:46',
                'updated_at' => '2026-02-07 14:12:46',
            ),
            8 => 
            array (
                'id' => 11,
                'education_level_id' => 1,
                'cc_field_id' => 3,
            'name' => 'پایه دهم (علوم انسانی)',
                'grade_number' => 10,
                'order' => 10,
                'is_active' => 0,
                'created_at' => '2026-02-07 14:13:16',
                'updated_at' => '2026-02-08 18:56:45',
            ),
            9 => 
            array (
                'id' => 12,
                'education_level_id' => 2,
                'cc_field_id' => NULL,
                'name' => 'پایه نهم ',
                'grade_number' => 9,
                'order' => 0,
                'is_active' => 1,
                'created_at' => '2026-02-16 14:50:38',
                'updated_at' => '2026-02-16 14:50:38',
            ),
        ));
        
        
    }
}