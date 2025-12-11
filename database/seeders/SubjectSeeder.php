<?php

namespace Database\Seeders;



use App\Models\Subject;

use Illuminate\Database\Seeder;



class SubjectSeeder extends Seeder

{

    /**

     * Run the database seeds.

     */

    public function run(): void

    {

        $subjects = [

            ['name' => 'ریاضی', 'slug' => 'mathematics'],

            ['name' => 'فیزیک', 'slug' => 'physics'],

            ['name' => 'شیمی', 'slug' => 'chemistry'],

            ['name' => 'زیست‌شناسی', 'slug' => 'biology'],

            ['name' => 'ادبیات فارسی', 'slug' => 'persian-literature'],

            ['name' => 'عربی', 'slug' => 'arabic'],

            ['name' => 'زبان انگلیسی', 'slug' => 'english'],

            ['name' => 'دین و زندگی', 'slug' => 'religion'],

            ['name' => 'تاریخ', 'slug' => 'history'],

            ['name' => 'جغرافیا', 'slug' => 'geography'],

            ['name' => 'اقتصاد', 'slug' => 'economics'],

            ['name' => 'جامعه‌شناسی', 'slug' => 'sociology'],

            ['name' => 'فلسفه و منطق', 'slug' => 'philosophy-logic'],

            ['name' => 'روانشناسی', 'slug' => 'psychology'],

            ['name' => 'هندسه', 'slug' => 'geometry'],

            ['name' => 'آمار و احتمال', 'slug' => 'statistics'],

            ['name' => 'حسابان', 'slug' => 'calculus'],

            ['name' => 'گسسته', 'slug' => 'discrete-math'],

            ['name' => 'زمین‌شناسی', 'slug' => 'geology'],

            ['name' => 'علوم اجتماعی', 'slug' => 'social-sciences'],

        ];



        foreach ($subjects as $subject) {

            Subject::query()->firstOrCreate(

                ['slug' => $subject['slug']],

                ['name' => $subject['name'], 'is_active' => true]

            );

        }

    }

}
