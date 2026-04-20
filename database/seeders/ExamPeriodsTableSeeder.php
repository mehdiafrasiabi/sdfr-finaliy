<?php



namespace Database\Seeders;



use App\Models\ExamPeriod;

use Illuminate\Database\Seeder;



class ExamPeriodSeeder extends Seeder

{

    /**

     * Run the database seeds.

     */

    public function run(): void

    {

        $periods = [

            [

                'name' => '۱۴۰۳-۱۴۰۴',

                'value' => '1403-1404',

                'order' => 1,

                'is_active' => true,

            ],

            [

                'name' => '۱۴۰۴-۱۴۰۵',

                'value' => '1404-1405',

                'order' => 2,

                'is_active' => true,

            ],

            [

                'name' => '۱۴۰۵-۱۴۰۶',

                'value' => '1405-1406',

                'order' => 3,

                'is_active' => true,

            ],

        ];



        foreach ($periods as $period) {

            ExamPeriod::updateOrCreate(

                ['value' => $period['value']],

                $period

            );

        }

    }

}
