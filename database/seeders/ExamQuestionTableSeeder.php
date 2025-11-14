<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamQuestionTableSeeder extends Seeder
{



public function run(): void
    {
        $categories = [
            'دهم ریاضی', 'دهم تجربی', 'دهم انسانی',
            'یازدهم ریاضی', 'یازدهم تجربی', 'یازدهم انسانی',
            'دوازدهم ریاضی', 'دوازدهم تجربی', 'دوازدهم انسانی',
            'نکته و تست', 'کنکوری تجربی', 'کنکوری ریاضی', 'کنکوری انسانی'
        ];
        foreach ($categories as $title) {
            \App\Models\ExamCategory::create(['name' => $title]);
            }
    }
}
