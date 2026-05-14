<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class PomodoroSessionsFactory extends Factory
{
    public function definition(): array
    {
        $startedAt = fake()->optional()->dateTimeBetween('-30 days', 'now');

        return [
            'student_id'   => Student::factory(),
            'session_type' => fake()->randomElement(['focus', 'shortBreak', 'longBreak']),
            'duration'     => fake()->randomElement([25, 5, 15]),
            'started_at'   => $startedAt,
            'ended_at'     => $startedAt ? (clone $startedAt)->modify('+25 minutes') : null,
            'status'       => fake()->randomElement(['completed', 'interrupted', 'canceled']),
        ];
    }
}
