<?php

namespace Database\Factories;

use App\Models\EssayExamAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

class EssayExamAttemptFactory extends Factory
{
    public function definition(): array
    {
        $startedAt = fake()->optional()->dateTimeBetween('-7 days', 'now');

        return [
            'assignment_id'      => EssayExamAssignment::factory(),
            'started_at'         => $startedAt,
            'submitted_at'       => $startedAt ? (clone $startedAt)->modify('+90 minutes') : null,
            'total_score'        => fake()->optional(0.5)->randomFloat(2, 0, 100),
            'status'             => fake()->randomElement(['in_progress', 'submitted', 'graded']),
            'consultant_message' => fake()->optional()->sentence(),
        ];
    }
}
