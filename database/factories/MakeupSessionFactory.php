<?php

namespace Database\Factories;

use App\Models\CcTopic;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class MakeupSessionFactory extends Factory
{
    public function definition(): array
    {
        $startedAt = fake()->optional()->dateTimeBetween('-30 days', 'now');
        $duration  = fake()->numberBetween(600, 5400);

        return [
            'student_id'       => Student::factory(),
            'cc_topic_id'      => CcTopic::factory(),
            'duration_seconds' => $duration,
            'status'           => fake()->randomElement(['pending', 'approved', 'rejected']),
            'note'             => fake()->optional()->sentence(),
            'part_type'        => fake()->randomElement(['test', 'descriptive', 'video']),
            'started_at'       => $startedAt,
            'ended_at'         => $startedAt ? (clone $startedAt)->modify("+{$duration} seconds") : null,
        ];
    }
}
