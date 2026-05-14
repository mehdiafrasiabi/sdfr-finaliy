<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'   => User::factory(),
            'full_name' => fake()->name(),
            'state_id'  => State::factory(),
            'city_id'   => City::factory(),
            'gender'    => fake()->randomElement(['male', 'female']),
            'picture'   => null,
        ];
    }
}
