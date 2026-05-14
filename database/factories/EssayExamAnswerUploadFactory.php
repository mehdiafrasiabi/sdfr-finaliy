<?php

namespace Database\Factories;

use App\Models\EssayExamAttempt;
use Illuminate\Database\Eloquent\Factories\Factory;

class EssayExamAnswerUploadFactory extends Factory
{
    public function definition(): array
    {
        return [
            'attempt_id' => EssayExamAttempt::factory(),
            'file_path'  => 'essay-answers/' . fake()->uuid() . '.webp',
            'file_size'  => fake()->numberBetween(50000, 3000000),
            'sort_order' => fake()->numberBetween(0, 20),
        ];
    }
}
