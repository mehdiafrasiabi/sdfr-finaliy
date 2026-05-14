<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'amount'            => fake()->numberBetween(1000000, 50000000),
            'order_number'      => strtoupper(Str::random(10)),
            'user_id'           => User::factory(),
            'payment_method_id' => PaymentMethod::factory(),
            'status'            => fake()->randomElement(['pending', 'processing', 'completed', 'canceled']),
            'wallet_payment'    => null,
        ];
    }

    public function completed(): static
    {
        return $this->state(fn () => ['status' => 'completed']);
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => 'pending']);
    }
}
