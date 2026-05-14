<?php

namespace Database\Factories;

use App\Models\CcField;
use App\Models\CcGrade;
use Illuminate\Database\Eloquent\Factories\Factory;

class CcSubjectFactory extends Factory
{
    public function definition(): array
    {
        return [
            'cc_grade_id' => CcGrade::factory(),
            'cc_field_id' => null,
            'name'        => fake()->randomElement(['ریاضی', 'فیزیک', 'شیمی', 'زیست', 'ادبیات', 'عربی', 'دین و زندگی', 'زبان انگلیسی']),
            'type'        => fake()->randomElement(['general', 'specialized']),
            'order'       => fake()->numberBetween(0, 20),
        ];
    }
}
