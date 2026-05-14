<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class PreAdvisingSessionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'homeworks'  => null,
            'exams'      => null,
            'free_times' => null,
        ];
    }
}
