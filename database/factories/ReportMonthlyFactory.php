<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportMonthlyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'      => fake()->sentence(4),
            'report'     => fake()->paragraph(),
            'student_id' => Student::factory(),
            'admin_id'   => Admin::factory(),
        ];
    }
}
