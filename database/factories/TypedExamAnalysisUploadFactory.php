<?php

namespace Database\Factories;

use App\Models\TypedExamAttempt;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypedExamAnalysisUploadFactory extends Factory
{
    public function definition(): array
    {
        return [
            'attempt_id'    => TypedExamAttempt::factory(),
            'file_path'     => 'analysis/' . fake()->uuid() . '.pdf',
            'original_name' => fake()->word() . '.pdf',
            'file_size'     => fake()->numberBetween(10000, 5000000),
        ];
    }
}
