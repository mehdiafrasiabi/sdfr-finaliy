<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Student;
use App\Models\TypedExam;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypedExamAssignmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'typed_exam_id'          => TypedExam::factory(),
            'student_id'             => Student::factory(),
            'admin_id'               => Admin::factory(),
            'status'                 => fake()->randomElement(['pending', 'started', 'completed', 'expired']),
            'result_visibility'      => fake()->randomElement(['after_exam_end', 'immediately', 'custom']),
            'answer_key_visibility'  => fake()->randomElement(['after_exam_end', 'immediately', 'custom']),
            'result_visible_at'      => null,
            'answer_key_visible_at'  => null,
        ];
    }
}
