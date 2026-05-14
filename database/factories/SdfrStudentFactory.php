<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SdfrStudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'     => fake()->name(),
            'document' => null,
            'status'   => fake()->boolean(50),
        ];
    }
}
