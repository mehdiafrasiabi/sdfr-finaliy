<?php

namespace Database\Factories;

use App\Models\Lesson;
use App\Models\WeeklyProgram;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProgramPartFactory extends Factory
{
    public function definition(): array
    {
        $partDate = fake()->dateTimeBetween('-7 days', '+7 days');

        return [
            'weekly_program_id' => WeeklyProgram::factory(),
            'lesson_id'         => Lesson::factory(),
            'lesson_name'       => fake()->randomElement(['ریاضی', 'فیزیک', 'شیمی', 'زیست', 'ادبیات']),
            'part_date'         => $partDate->format('Y-m-d'),
            'day_of_week'       => fake()->numberBetween(0, 6),
            'part_order'        => fake()->numberBetween(1, 10),
            'description'       => fake()->optional()->sentence(),
            'duration_minutes'  => fake()->randomElement([30, 45, 60, 90, 120]),
            'test_count'        => fake()->optional()->numberBetween(5, 50),
            'part_type'         => fake()->randomElement(['test', 'descriptive', 'video', 'topic_exam', 'comprehensive_exam', 'exam_analysis']),
            'lesson_type'       => fake()->randomElement(['general', 'specialized']),
            'grade'             => fake()->randomElement(['10', '11', '12', null]),
            'source_type'       => fake()->optional()->randomElement(['کتاب', 'جزوه', 'ویدیو']),
            'cc_subject_id'     => null,
            'cc_chapter_id'     => null,
            'cc_topic_id'       => null,
        ];
    }
}
