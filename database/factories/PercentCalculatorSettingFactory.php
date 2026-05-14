<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PercentCalculatorSettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'            => fake()->optional()->sentence(3),
            'subtitle'         => fake()->optional()->sentence(5),
            'image'            => null,
            'description'      => fake()->optional()->paragraph(),
            'meta_title'       => fake()->optional()->sentence(4),
            'meta_description' => fake()->optional()->sentence(8),
        ];
    }
}
