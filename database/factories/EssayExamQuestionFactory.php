<?php

namespace Database\Factories;

use App\Models\EssayExam;
use Illuminate\Database\Eloquent\Factories\Factory;

class EssayExamQuestionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'essay_exam_id'   => EssayExam::factory(),
            'question_number' => fake()->unique()->numberBetween(1, 20),
            'score'           => fake()->randomFloat(2, 1, 20),
            'row_height'      => fake()->randomElement([80, 100, 110, 130, 150]),
        ];
    }
}
