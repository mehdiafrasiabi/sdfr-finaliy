<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\State;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonalInformationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'          => fake()->firstName(),
            'father_name'   => fake()->lastName(),
            'code_mell'     => fake()->unique()->numerify('##########'),
            'place_of_birth' => fake()->city(),
            'father_mobile' => '09' . fake()->numerify('#########'),
            'mother_mobile' => '09' . fake()->numerify('#########'),
            'grade'         => fake()->randomElement(['10', '11', '12']),
            'field'         => fake()->randomElement(['math', 'experimental', 'human']),
            'birth_date'    => fake()->date('Y/m/d', '-15 years'),
            'address'       => fake()->address(),
            'state_id'      => State::factory(),
            'city_id'       => City::factory(),
            'user_id'       => User::factory(),
            'name_full'     => fake()->name(),
        ];
    }
}
