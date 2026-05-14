<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CouponsFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code'         => strtoupper(Str::random(8)),
            'type'         => fake()->randomElement(['fixed', 'percent']),
            'value'        => fake()->numberBetween(10, 50000000),
            'limit'        => fake()->numberBetween(1, 100),
            'min_purchase' => fake()->numberBetween(0, 5000000),
            'expires_at'   => fake()->dateTimeBetween('now', '+1 year'),
            'is_active'    => true,
            'user_id'      => null,
            'is_public'    => fake()->boolean(80),
        ];
    }
}
