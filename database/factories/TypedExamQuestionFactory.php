<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\TypedExam;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypedExamQuestionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'typed_exam_id' => TypedExam::factory(),
            'question_id'   => Question::factory(),
            'order'         => fake()->numberBetween(0, 100),
        ];
    }
}
