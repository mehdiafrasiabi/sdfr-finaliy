<?php

namespace Database\Factories;

use App\Models\AdvisingSession;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvisingPreSessionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'advising_session_id' => AdvisingSession::factory(),
            'student_id'          => Student::factory(),
            'title'               => fake()->sentence(3),
            'status'              => fake()->randomElement(['pending', 'completed']),
            'time_per_part'       => fake()->optional()->randomElement([30, 45, 60, 90]),
        ];
    }
}
