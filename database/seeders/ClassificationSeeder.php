<?php

namespace Database\Seeders;



use App\Models\CcField;

use App\Models\CcGrade;

use App\Models\EducationLevel;

use Illuminate\Database\Seeder;



class ClassificationSeeder extends Seeder

{

    public function run(): void

    {

        // Education Levels

        $level = EducationLevel::firstOrCreate(

            ['slug' => 'high-school'],

            [

                'name' => 'متوسطه دوم',

                'order' => 1,

                'is_active' => true,

            ]

        );



        // Grades (10, 11, 12)

        $grades = [

            ['name' => 'دهم', 'grade_number' => 10, 'order' => 1],

            ['name' => 'یازدهم', 'grade_number' => 11, 'order' => 2],

            ['name' => 'دوازدهم', 'grade_number' => 12, 'order' => 3],

        ];



        foreach ($grades as $gradeData) {

            CcGrade::firstOrCreate(

                ['grade_number' => $gradeData['grade_number']],

                [

                    'education_level_id' => $level->id,

                    'name' => $gradeData['name'],

                    'order' => $gradeData['order'],

                    'is_active' => true,

                ]

            );

        }



        // Fields

        $fields = [

            ['name' => 'ریاضی', 'slug' => 'math', 'order' => 1],

            ['name' => 'تجربی', 'slug' => 'experimental', 'order' => 2],

            ['name' => 'انسانی', 'slug' => 'human', 'order' => 3],

        ];



        foreach ($fields as $fieldData) {

            CcField::firstOrCreate(

                ['slug' => $fieldData['slug']],

                [

                    'name' => $fieldData['name'],

                    'order' => $fieldData['order'],

                    'is_active' => true,

                ]

            );

        }

    }

}


