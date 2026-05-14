<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExamCountdownSettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'       => fake()->sentence(4),
            'subtitle'    => fake()->sentence(6),
            'card_title'  => fake()->sentence(3),
            'start_date'  => fake()->dateTimeBetween('-1 month', 'now')->format('Y-m-d'),
            'end_date'    => fake()->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
            'description' => fake()->optional()->paragraph(),
        ];
    }
}
