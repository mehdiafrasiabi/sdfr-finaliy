<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\TypedExamAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypedExamAttemptFactory extends Factory
{
    public function definition(): array
    {
        $startedAt = fake()->optional()->dateTimeBetween('-7 days', 'now');

        return [
            'assignment_id'   => TypedExamAssignment::factory(),
            'student_id'      => Student::factory(),
            'started_at'      => $startedAt,
            'submitted_at'    => $startedAt ? (clone $startedAt)->modify('+60 minutes') : null,
            'is_finished'     => fake()->boolean(60),
            'score'           => fake()->optional(0.6)->randomFloat(2, 0, 100),
            'analysis_status' => fake()->optional()->randomElement(['pending', 'done']),
        ];
    }
}
