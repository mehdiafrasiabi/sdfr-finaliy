<?php

namespace Database\Factories;

use App\Models\EssayExamAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

class EssayExamAssignmentTimeFactory extends Factory
{
    public function definition(): array
    {
        $startTime = fake()->dateTimeBetween('now', '+7 days');
        $duration  = fake()->randomElement([60, 90, 120, 180]);

        return [
            'assignment_id'    => EssayExamAssignment::factory(),
            'start_time'       => $startTime,
            'end_time'         => (clone $startTime)->modify("+{$duration} minutes"),
            'duration_minutes' => $duration,
        ];
    }
}
