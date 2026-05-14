<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SubjectFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->randomElement(['ریاضی', 'فیزیک', 'شیمی', 'زیست', 'ادبیات', 'عربی', 'دین و زندگی', 'زبان']);

        return [
            'name'      => $name,
            'slug'      => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 9999),
            'is_active' => true,
        ];
    }
}
