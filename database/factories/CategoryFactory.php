<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'        => fake()->words(2, true),
            'category_id' => null,
        ];
    }

    public function child(): static
    {
        return $this->state(fn () => [
            'category_id' => \App\Models\Category::factory(),
        ]);
    }
}
