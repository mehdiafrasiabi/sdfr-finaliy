<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ContactUsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'   => fake()->name(),
            'mobile' => '09' . fake()->numerify('#########'),
            'text'   => fake()->optional()->paragraph(),
            'status' => fake()->randomElement(['pending', 'completed', 'canceled']),
        ];
    }
}
