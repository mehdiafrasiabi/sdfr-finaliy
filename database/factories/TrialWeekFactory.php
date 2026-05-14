<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TrialWeekFactory extends Factory
{
    public function definition(): array
    {
        $grade = fake()->randomElement([9, 10, 11, 12]);

        return [
            'user_id'                  => User::factory(),
            'grade'                    => $grade,
            'field'                    => $grade === 9 ? null : fake()->randomElement(['math', 'experimental', 'human']),
            'father_mobile'            => '09' . fake()->numerify('#########'),
            'mother_mobile'            => '09' . fake()->numerify('#########'),
            'supporter_id'             => null,
            'student_id'               => null,
            'advising_session_id'      => null,
            'daily_study_hours'        => null,
            'status'                   => 'pending',
            'expires_at'               => null,
            'supporter_assigned_at'    => null,
            'classification_locked_at' => null,
            'pre_session_completed_at' => null,
            'program_built_at'         => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => 'pending']);
    }

    public function supporterAssigned(): static
    {
        return $this->state(fn () => [
            'status'                => 'supporter_assigned',
            'supporter_id'          => Admin::factory(),
            'supporter_assigned_at' => now(),
            'expires_at'            => now()->addDays(7),
        ]);
    }

    public function classificationDone(): static
    {
        return $this->state(fn () => [
            'status'                   => 'classification_done',
            'classification_locked_at' => now(),
        ]);
    }

    public function programBuilt(): static
    {
        return $this->state(fn () => [
            'status'           => 'program_built',
            'program_built_at' => now(),
        ]);
    }
}
