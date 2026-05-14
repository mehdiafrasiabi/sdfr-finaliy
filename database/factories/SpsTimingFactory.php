<?php

namespace Database\Factories;

use App\Models\StudyPartSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpsTimingFactory extends Factory
{
    public function definition(): array
    {
        $startedAt = fake()->dateTimeBetween('-30 days', 'now');
        $duration  = fake()->numberBetween(300, 5400);

        return [
            'study_part_session_id' => StudyPartSession::factory(),
            'started_at'            => $startedAt,
            'ended_at'              => (clone $startedAt)->modify("+{$duration} seconds"),
            'duration_seconds'      => $duration,
            'planned_seconds'       => $duration + fake()->numberBetween(-300, 900),
            'completed_at'          => fake()->optional(0.7)->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
