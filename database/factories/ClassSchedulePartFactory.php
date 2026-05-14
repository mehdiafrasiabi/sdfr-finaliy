<?php

namespace Database\Factories;

use App\Models\CcSubject;
use App\Models\ClassSchedule;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClassSchedulePartFactory extends Factory
{
    public function definition(): array
    {
        return [
            'class_schedule_id' => ClassSchedule::factory(),
            'day_of_week'       => fake()->numberBetween(0, 6),
            'part_order'        => fake()->numberBetween(1, 5),
            'cc_subject_id'     => CcSubject::factory(),
            'lesson_name'       => fake()->randomElement(['ریاضی', 'فیزیک', 'شیمی', 'زیست', 'ادبیات']),
        ];
    }
}
