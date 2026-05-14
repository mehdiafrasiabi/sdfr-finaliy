<?php

namespace Database\Factories;

use App\Models\Student;
use App\Models\TypedExam;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypedExamStudentOrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id'    => Student::factory(),
            'typed_exam_id' => TypedExam::factory(),
        ];
    }
}
