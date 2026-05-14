<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\StudyPartSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class SessionFeedbackFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id' => Student::factory(),
            'sps_id'     => StudyPartSession::factory(),
            'rating'     => fake()->numberBetween(1, 5),
            'comment'    => fake()->optional()->sentence(),
        ];
    }
}
