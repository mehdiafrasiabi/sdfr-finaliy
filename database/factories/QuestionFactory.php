<?php

namespace Database\Factories;

use App\Models\Subject;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code'       => strtoupper(fake()->unique()->lexify('????')),
            'subject_id' => Subject::factory(),
            'difficulty' => fake()->randomElement(['easy', 'medium', 'hard', 'special']),
            'direction'  => fake()->randomElement(['rtl', 'ltr']),
        ];
    }
}
