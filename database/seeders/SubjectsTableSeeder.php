<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SubjectsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('subjects')->delete();
        
        \DB::table('subjects')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'ریاضی',
                'slug' => 'mathematics',
                'is_active' => 1,
                'created_at' => '2026-02-07 14:04:06',
                'updated_at' => '2026-02-07 14:04:06',
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'فیزیک',
                'slug' => 'physics',
                'is_active' => 1,
                'created_at' => '2026-02-07 14:04:06',
                'updated_at' => '2026-02-07 14:04:06',
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'شیمی',
                'slug' => 'chemistry',
                'is_active' => 1,
                'created_at' => '2026-02-07 14:04:06',
                'updated_at' => '2026-02-07 14:04:06',
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'زیست‌شناسی',
                'slug' => 'biology',
                'is_active' => 1,
                'created_at' => '2026-02-07 14:04:06',
                'updated_at' => '2026-02-07 14:04:06',
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'ادبیات فارسی',
                'slug' => 'persian-literature',
                'is_active' => 1,
                'created_at' => '2026-02-07 14:04:06',
                'updated_at' => '2026-02-07 14:04:06',
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'عربی',
                'slug' => 'arabic',
                'is_active' => 1,
                'created_at' => '2026-02-07 14:04:06',
                'updated_at' => '2026-02-07 14:04:06',
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'زبان انگلیسی',
                'slug' => 'english',
                'is_active' => 1,
                'created_at' => '2026-02-07 14:04:06',
                'updated_at' => '2026-02-07 14:04:06',
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'دین و زندگی',
                'slug' => 'religion',
                'is_active' => 1,
                'created_at' => '2026-02-07 14:04:06',
                'updated_at' => '2026-02-07 14:04:06',
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'تاریخ',
                'slug' => 'history',
                'is_active' => 1,
                'created_at' => '2026-02-07 14:04:06',
                'updated_at' => '2026-02-07 14:04:06',
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'جغرافیا',
                'slug' => 'geography',
                'is_active' => 1,
                'created_at' => '2026-02-07 14:04:06',
                'updated_at' => '2026-02-07 14:04:06',
            ),
            10 => 
            array (
                'id' => 11,
                'name' => 'اقتصاد',
                'slug' => 'economics',
                'is_active' => 1,
                'created_at' => '2026-02-07 14:04:06',
                'updated_at' => '2026-02-07 14:04:06',
            ),
            11 => 
            array (
                'id' => 12,
                'name' => 'جامعه‌شناسی',
                'slug' => 'sociology',
                'is_active' => 1,
                'created_at' => '2026-02-07 14:04:06',
                'updated_at' => '2026-02-07 14:04:06',
            ),
            12 => 
            array (
                'id' => 13,
                'name' => 'فلسفه و منطق',
                'slug' => 'philosophy-logic',
                'is_active' => 1,
                'created_at' => '2026-02-07 14:04:06',
                'updated_at' => '2026-02-07 14:04:06',
            ),
            13 => 
            array (
                'id' => 14,
                'name' => 'روانشناسی',
                'slug' => 'psychology',
                'is_active' => 1,
                'created_at' => '2026-02-07 14:04:06',
                'updated_at' => '2026-02-07 14:04:06',
            ),
            14 => 
            array (
                'id' => 15,
                'name' => 'هندسه',
                'slug' => 'geometry',
                'is_active' => 1,
                'created_at' => '2026-02-07 14:04:06',
                'updated_at' => '2026-02-07 14:04:06',
            ),
            15 => 
            array (
                'id' => 16,
                'name' => 'آمار و احتمال',
                'slug' => 'statistics',
                'is_active' => 1,
                'created_at' => '2026-02-07 14:04:06',
                'updated_at' => '2026-02-07 14:04:06',
            ),
            16 => 
            array (
                'id' => 17,
                'name' => 'حسابان',
                'slug' => 'calculus',
                'is_active' => 1,
                'created_at' => '2026-02-07 14:04:06',
                'updated_at' => '2026-02-07 14:04:06',
            ),
            17 => 
            array (
                'id' => 18,
                'name' => 'گسسته',
                'slug' => 'discrete-math',
                'is_active' => 1,
                'created_at' => '2026-02-07 14:04:06',
                'updated_at' => '2026-02-07 14:04:06',
            ),
            18 => 
            array (
                'id' => 19,
                'name' => 'زمین‌شناسی',
                'slug' => 'geology',
                'is_active' => 1,
                'created_at' => '2026-02-07 14:04:06',
                'updated_at' => '2026-02-07 14:04:06',
            ),
            19 => 
            array (
                'id' => 20,
                'name' => 'علوم اجتماعی',
                'slug' => 'social-sciences',
                'is_active' => 1,
                'created_at' => '2026-02-07 14:04:06',
                'updated_at' => '2026-02-07 14:04:06',
            ),
        ));
        
        
    }
}