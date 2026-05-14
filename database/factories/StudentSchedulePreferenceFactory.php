<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentSchedulePreferenceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id'          => Student::factory(),
            'assigned_advisor_id' => null,
            'status'              => fake()->randomElement(['pending', 'approved', 'replaced']),
            'year_period'         => (int) now()->year,
            'change_index'        => 0,
            'student_notes'       => fake()->optional()->sentence(),
            'submitted_at'        => fake()->optional()->dateTimeBetween('-30 days', 'now'),
            'approved_at'         => null,
        ];
    }
}
