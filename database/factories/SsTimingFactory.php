<?php

namespace Database\Factories;

use App\Models\StudySession;
use Illuminate\Database\Eloquent\Factories\Factory;

class SsTimingFactory extends Factory
{
    public function definition(): array
    {
        $startedAt = fake()->dateTimeBetween('-30 days', 'now');
        $duration  = fake()->numberBetween(600, 7200);

        return [
            'study_session_id' => StudySession::factory(),
            'started_at'       => $startedAt,
            'ended_at'         => (clone $startedAt)->modify("+{$duration} seconds"),
            'duration_seconds' => $duration,
            'planned_seconds'  => $duration + fake()->numberBetween(-300, 1800),
        ];
    }
}
