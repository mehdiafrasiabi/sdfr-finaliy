<?php

namespace Database\Factories;

use App\Models\AdvisingPreSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvisingPreSessionQaFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pre_session_id' => AdvisingPreSession::factory(),
            'subject'        => fake()->randomElement(['ریاضی', 'فیزیک', 'شیمی', 'زیست', 'ادبیات']),
            'part_count'     => fake()->numberBetween(1, 10),
            'time_per_part'  => fake()->randomElement([30, 45, 60, 90]),
            'qa_date'        => fake()->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
            'cc_subject_id'  => null,
            'cc_chapter_id'  => null,
        ];
    }
}
