<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\AdvisingSession;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeeklyProgramFactory extends Factory
{
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-2 weeks', '+2 weeks');
        $endDate   = (clone $startDate)->modify('+6 days');

        return [
            'student_id'          => Student::factory(),
            'advisor_id'          => null,
            'advising_session_id' => null,
            'start_date'          => $startDate->format('Y-m-d'),
            'end_date'            => $endDate->format('Y-m-d'),
            'advisor_name'        => fake()->optional()->name(),
            'supporter_name'      => fake()->optional()->name(),
            'is_active'           => true,
        ];
    }
}
