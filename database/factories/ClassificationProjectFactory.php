<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClassificationProjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'        => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'start_at'    => fake()->dateTimeBetween('-1 month', 'now'),
            'end_at'      => fake()->dateTimeBetween('now', '+2 months'),
            'is_active'   => fake()->boolean(70),
        ];
    }
}
