<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\PersonalInformation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PaymentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'order_id'               => Order::factory(),
            'user_id'                => User::factory(),
            'amount'                 => fake()->numberBetween(1000000, 50000000),
            'order_number'           => strtoupper(Str::random(10)),
            'refNumber'              => fake()->optional()->unique()->numerify('REF-########'),
            'cardNumber'             => fake()->optional()->numerify('####-####-####-####'),
            'personal_information_id' => null,
            'status'                 => fake()->randomElement(['pending', 'completed', 'cancelled']),
        ];
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status'    => 'completed',
            'refNumber' => fake()->unique()->numerify('REF-########'),
        ]);
    }
}
