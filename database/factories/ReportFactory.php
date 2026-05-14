<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id'          => Student::factory(),
            'admin_id'            => Admin::factory(),
            'required_parts'      => fake()->numberBetween(1, 10),
            'done_parts'          => fake()->numberBetween(0, 10),
            'required_tests'      => fake()->numberBetween(0, 100),
            'done_tests'          => fake()->numberBetween(0, 100),
            'phone_study_hours'   => fake()->numberBetween(0, 24),
            'phone_nonstudy_hours' => fake()->numberBetween(0, 24),
            'report_file'         => null,
            'description'         => fake()->optional()->paragraph(),
            'complacent'          => fake()->numberBetween(0, 100),
            'status'              => fake()->randomElement(['pending', 'completed', 'rejected']),
            'comments'            => fake()->optional()->sentence(),
            'student_reply_seen_at' => null,
        ];
    }
}
