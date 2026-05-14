<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportCallingStudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'       => fake()->optional()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'answer'      => fake()->randomElement(['mather', 'father', 'student']),
            'call_date'   => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'admin_id'    => Admin::factory(),
            'student_id'  => Student::factory(),
        ];
    }
}
