<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\TypedExamAttempt;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypedExamAttemptAnswerFactory extends Factory
{
    public function definition(): array
    {
        $selected = fake()->optional()->numberBetween(1, 4);

        return [
            'attempt_id'      => TypedExamAttempt::factory(),
            'question_id'     => Question::factory(),
            'selected_option' => $selected,
            'is_correct'      => $selected ? fake()->boolean(25) : null,
            'answered_at'     => fake()->optional()->dateTimeBetween('-7 days', 'now'),
        ];
    }
}
