<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EducationLevelFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->randomElement(['متوسطه اول', 'متوسطه دوم', 'پیش دانشگاهی']);

        return [
            'name'      => $name,
            'slug'      => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 999),
            'order'     => fake()->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}
