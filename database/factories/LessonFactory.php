<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LessonFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'      => fake()->randomElement(['ریاضی', 'فیزیک', 'شیمی', 'زیست', 'ادبیات', 'عربی', 'دین و زندگی', 'زبان']),
            'type'      => fake()->randomElement(['general', 'specialized']),
            'grade'     => fake()->randomElement(['10', '11', '12', null]),
            'field'     => fake()->randomElement(['math', 'experimental', 'human', null]),
            'is_active' => true,
        ];
    }
}
