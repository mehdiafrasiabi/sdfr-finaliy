<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\EssayExam;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class EssayExamAssignmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'essay_exam_id' => EssayExam::factory(),
            'student_id'    => Student::factory(),
            'admin_id'      => Admin::factory(),
            'status'        => fake()->randomElement(['pending', 'in_progress', 'submitted', 'graded']),
        ];
    }
}
