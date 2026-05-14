<?php

namespace Database\Factories;

use App\Models\TypedExamAssignment;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypedExamAssignmentTimeFactory extends Factory
{
    public function definition(): array
    {
        $startTime = fake()->dateTimeBetween('now', '+7 days');
        $duration  = fake()->randomElement([30, 45, 60, 90, 120]);

        return [
            'assignment_id'    => TypedExamAssignment::factory(),
            'start_time'       => $startTime,
            'end_time'         => (clone $startTime)->modify("+{$duration} minutes"),
            'duration_minutes' => $duration,
        ];
    }
}
