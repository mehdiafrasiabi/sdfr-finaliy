<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletTransactionFactory extends Factory
{
    public function definition(): array
    {
        $amount = fake()->numberBetween(10000, 5000000);

        return [
            'wallet_id'     => Wallet::factory(),
            'user_id'       => User::factory(),
            'type'          => fake()->randomElement(['deposit', 'withdraw', 'gift', 'purchase', 'refund']),
            'amount'        => $amount,
            'balance_after' => fake()->numberBetween($amount, 10000000),
            'description'   => fake()->optional()->sentence(),
        ];
    }
}
