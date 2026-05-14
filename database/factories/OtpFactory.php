<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OtpFactory extends Factory
{
    public function definition(): array
    {
        return [
            'mobile'     => '09' . fake()->numerify('#########'),
            'code'       => fake()->numerify('######'),
            'expires_at' => now()->addMinutes(5),
            'is_used'    => false,
        ];
    }
}
