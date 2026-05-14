<?php

namespace Database\Factories;

use App\Models\TypedExam;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypedExamRandomConfigFactory extends Factory
{
    public function definition(): array
    {
        return [
            'typed_exam_id'      => TypedExam::factory(),
            'education_level_id' => null,
            'cc_grade_id'        => null,
            'cc_field_id'        => null,
            'cc_subject_id'      => null,
            'cc_chapter_id'      => null,
            'cc_topic_id'        => null,
            'difficulty'         => fake()->randomElement(['easy', 'medium', 'hard', 'special']),
            'count'              => fake()->numberBetween(5, 30),
        ];
    }
}
