<?php

namespace Database\Factories;

use App\Models\EssayExamAttempt;
use App\Models\EssayExamQuestion;
use Illuminate\Database\Eloquent\Factories\Factory;

class EssayExamQuestionScoreFactory extends Factory
{
    public function definition(): array
    {
        return [
            'attempt_id'  => EssayExamAttempt::factory(),
            'question_id' => EssayExamQuestion::factory(),
            'score'       => fake()->optional()->randomFloat(2, 0, 20),
        ];
    }
}
