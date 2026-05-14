<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class GiftCodeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code'        => strtoupper(Str::random(10)),
            'type'        => fake()->randomElement(['for_all', 'for_one']),
            'user_id'     => null,
            'usage_limit' => fake()->numberBetween(1, 50),
            'usage_count' => 0,
            'amount'      => fake()->numberBetween(10000, 5000000),
            'expires_at'  => fake()->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'is_active'   => true,
        ];
    }
}
