<?php

namespace Database\Factories;

use App\Models\GiftCode;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GiftCodeUsageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'gift_code_id' => GiftCode::factory(),
            'user_id'      => User::factory(),
            'amount'       => fake()->numberBetween(10000, 5000000),
        ];
    }
}
