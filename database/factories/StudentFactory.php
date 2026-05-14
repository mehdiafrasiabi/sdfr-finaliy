<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'      => User::factory(),
            'supporter_id' => null,
            'payment_id'   => null,
            'product_id'   => null,
            'star'         => fake()->randomElement(['A', 'B', 'C', 'D']),
            'is_trial'     => fake()->boolean(30),
        ];
    }

    public function withSupporter(): static
    {
        return $this->state(fn () => ['supporter_id' => Admin::factory()]);
    }

    public function trial(): static
    {
        return $this->state(fn () => ['is_trial' => true]);
    }
}
