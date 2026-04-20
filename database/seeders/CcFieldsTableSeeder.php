<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class CcFieldsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('cc_fields')->delete();

        \DB::table('cc_fields')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'name' => 'ریاضی و فیزیک',
                    'slug' => 'math',
                    'order' => 1,
                    'is_active' => 1,
                    'created_at' => '2026-02-07 14:04:06',
                    'updated_at' => '2026-02-07 14:04:06',
                ),
            1 =>
                array (
                    'id' => 2,
                    'name' => 'علوم تجربی',
                    'slug' => 'experimental',
                    'order' => 2,
                    'is_active' => 1,
                    'created_at' => '2026-02-07 14:04:06',
                    'updated_at' => '2026-02-07 14:04:06',
                ),
            2 =>
                array (
                    'id' => 3,
                    'name' => 'علوم انسانی',
                    'slug' => 'human',
                    'order' => 3,
                    'is_active' => 1,
                    'created_at' => '2026-02-07 14:04:06',
                    'updated_at' => '2026-02-07 14:04:06',
                ),
            3 =>
                array (
                    'id' => 4,
                    'name' => 'علوم و معارف اسلامی',
                    'slug' => 'islamic',
                    'order' => 4,
                    'is_active' => 1,
                    'created_at' => '2026-02-07 14:04:06',
                    'updated_at' => '2026-02-07 14:04:06',
                ),
            4 =>
                array (
                    'id' => 5,
                    'name' => 'بدون رشته',
                    'slug' => 'none',
                    'order' => 5,
                    'is_active' => 1,
                    'created_at' => '2026-02-07 14:04:06',
                    'updated_at' => '2026-02-07 14:04:06',
                ),
        ));


    }
}
