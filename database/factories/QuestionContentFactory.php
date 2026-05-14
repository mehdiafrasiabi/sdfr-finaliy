<?php

namespace Database\Factories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionContentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'question_id'       => Question::factory(),
            'body'              => fake()->optional()->paragraph(),
            'explanation'       => fake()->optional()->paragraph(),
            'question_image'    => null,
            'folder_hash'       => null,
            'explanation_image' => null,
        ];
    }
}
