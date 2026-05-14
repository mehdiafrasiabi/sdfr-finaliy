<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class AdminFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'          => fake()->name(),
            'email'         => fake()->unique()->safeEmail(),
            'mobile'        => '09' . fake()->numerify('#########'),
            'password'      => Hash::make('password'),
            'national_code' => fake()->unique()->numerify('##########'),
            'address'       => fake()->address(),
            'postal_code'   => fake()->numerify('##########'),
            'document'      => null,
            'contract'      => null,
            'picture'       => null,
        ];
    }
}
