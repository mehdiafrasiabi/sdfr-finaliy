<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassScheduleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id'    => Student::factory(),
            'is_finalized'  => fake()->boolean(40),
            'finalized_at'  => fake()->optional(0.4)->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
