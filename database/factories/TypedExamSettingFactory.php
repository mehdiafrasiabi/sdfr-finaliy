<?php

namespace Database\Factories;

use App\Models\TypedExam;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypedExamSettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'typed_exam_id'          => TypedExam::factory(),
            'result_visibility'      => fake()->randomElement(['after_exam_end', 'immediately']),
            'answer_key_visibility'  => fake()->randomElement(['after_exam_end', 'immediately']),
            'randomization_type'     => fake()->randomElement(['none', 'questions_only', 'options_only', 'both']),
            'description'            => fake()->optional()->paragraph(),
        ];
    }
}
