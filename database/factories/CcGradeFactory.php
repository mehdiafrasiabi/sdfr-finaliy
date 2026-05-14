<?php

namespace Database\Factories;

use App\Models\CcField;
use App\Models\EducationLevel;
use Illuminate\Database\Eloquent\Factories\Factory;

class CcGradeFactory extends Factory
{
    public function definition(): array
    {
        $gradeNumber = fake()->randomElement([10, 11, 12]);

        return [
            'education_level_id' => EducationLevel::factory(),
            'cc_field_id'        => null,
            'name'               => "پایه {$gradeNumber}",
            'grade_number'       => $gradeNumber,
            'order'              => fake()->numberBetween(0, 10),
            'is_active'          => true,
        ];
    }
}
