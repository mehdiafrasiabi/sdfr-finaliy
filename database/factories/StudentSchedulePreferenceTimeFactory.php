<?php

namespace Database\Factories;

use App\Models\StudentSchedulePreference;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentSchedulePreferenceTimeFactory extends Factory
{
    public function definition(): array
    {
        $startHour = fake()->numberBetween(8, 18);
        $endHour   = $startHour + fake()->numberBetween(1, 4);

        return [
            'student_schedule_preference_id' => StudentSchedulePreference::factory(),
            'day_of_week'                    => fake()->numberBetween(0, 6),
            'start_time'                     => sprintf('%02d:00:00', $startHour),
            'end_time'                       => sprintf('%02d:00:00', min($endHour, 23)),
        ];
    }
}
