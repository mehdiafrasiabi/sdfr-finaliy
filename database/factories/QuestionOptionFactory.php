<?php

namespace Database\Factories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionOptionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'question_id'   => Question::factory(),
            'option_number' => fake()->numberBetween(1, 4),
            'content'       => fake()->sentence(),
            'is_correct'    => false,
        ];
    }
}
