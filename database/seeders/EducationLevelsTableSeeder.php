<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EducationLevelsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('education_levels')->delete();

        \DB::table('education_levels')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'name' => 'متوسطه دوم ',
                    'slug' => 'mtosth-dom',
                    'order' => 0,
                    'is_active' => 1,
                    'created_at' => '2026-02-07 14:07:20',
                    'updated_at' => '2026-02-07 14:07:20',
                ),
            1 =>
                array (
                    'id' => 2,
                    'name' => 'متوسطه اول ',
                    'slug' => 'mtosth-aol',
                    'order' => 1,
                    'is_active' => 1,
                    'created_at' => '2026-02-07 14:07:26',
                    'updated_at' => '2026-02-07 14:07:26',
                ),
        ));


    }
}
