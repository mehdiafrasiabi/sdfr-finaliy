<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SdfrSchoolFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'     => fake()->company() . ' مدرسه',
            'document' => null,
            'status'   => fake()->boolean(50),
        ];
    }
}
