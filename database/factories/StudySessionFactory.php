<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudySessionFactory extends Factory
{
    public function definition(): array
    {
        $startedAt = fake()->dateTimeBetween('-30 days', 'now');
        $duration  = fake()->numberBetween(600, 7200);

        return [
            'student_id'       => Student::factory(),
            'started_at'       => $startedAt,
            'ended_at'         => (clone $startedAt)->modify("+{$duration} seconds"),
            'duration_seconds' => $duration,
            'planned_seconds'  => $duration + fake()->numberBetween(-300, 1800),
            'note'             => fake()->optional()->sentence(),
        ];
    }
}
