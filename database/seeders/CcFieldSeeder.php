<?php


namespace Database\Seeders;


use App\Models\CcField;

use Illuminate\Database\Seeder;


class CcFieldSeeder extends Seeder

{

    /**
     * Run the database seeds.
     * رشته‌های تحصیلی پایه
     */

    public function run(): void

    {

        $fields = [

            [

                'name' => 'ریاضی و فیزیک',

                'slug' => 'math',

                'order' => 1,

                'is_active' => true,

            ],

            [

                'name' => 'علوم تجربی',

                'slug' => 'experimental',

                'order' => 2,

                'is_active' => true,

            ],

            [

                'name' => 'علوم انسانی',

                'slug' => 'human',

                'order' => 3,

                'is_active' => true,

            ],

            [

                'name' => 'علوم و معارف اسلامی',

                'slug' => 'islamic',

                'order' => 4,

                'is_active' => true,

            ],

            [

                'name' => 'بدون رشته',

                'slug' => 'none',

                'order' => 5,

                'is_active' => true,

            ],

        ];


        foreach ($fields as $field) {

            CcField::updateOrCreate(

                ['slug' => $field['slug']],

                $field

            );

        }

    }

}
