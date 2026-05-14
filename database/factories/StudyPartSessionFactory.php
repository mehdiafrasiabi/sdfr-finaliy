<?php

namespace Database\Factories;

use App\Models\ProgramPart;
use App\Models\Student;
use App\Models\WeeklyProgram;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudyPartSessionFactory extends Factory
{
    public function definition(): array
    {
        $startedAt = fake()->dateTimeBetween('-30 days', 'now');
        $duration  = fake()->numberBetween(600, 5400);

        return [
            'student_id'       => Student::factory(),
            'program_part_id'  => ProgramPart::factory(),
            'weekly_program_id' => WeeklyProgram::factory(),
            'started_at'       => $startedAt,
            'ended_at'         => (clone $startedAt)->modify("+{$duration} seconds"),
            'duration_seconds' => $duration,
            'planned_seconds'  => $duration + fake()->numberBetween(-300, 900),
            'is_completed'     => fake()->boolean(70),
            'completed_at'     => fake()->optional(0.7)->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
