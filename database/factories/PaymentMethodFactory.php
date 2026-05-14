<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'        => fake()->randomElement(['زرین‌پال', 'زیبال', 'آیدی پی']),
            'merchant_id' => fake()->uuid(),
            'active'      => fake()->boolean(70),
        ];
    }
}
