<?php

namespace Database\Factories;

use App\Models\AdvisingPreSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvisingPreSessionAssignmentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pre_session_id' => AdvisingPreSession::factory(),
            'subject'        => fake()->randomElement(['ریاضی', 'فیزیک', 'شیمی', 'زیست', 'ادبیات']),
            'part_count'     => fake()->numberBetween(1, 10),
            'due_date'       => fake()->dateTimeBetween('now', '+2 weeks')->format('Y-m-d'),
            'cc_subject_id'  => null,
        ];
    }
}
