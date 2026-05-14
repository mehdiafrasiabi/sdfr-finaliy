<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\TrialWeek;
use Illuminate\Database\Eloquent\Factories\Factory;

class AcquisitionContactFactory extends Factory
{
    public function definition(): array
    {
        return [
            'trial_week_id'         => TrialWeek::factory(),
            'admin_id'              => Admin::factory(),
            'type'                  => fake()->randomElement(['initial', 'secondary', 'supplementary']),
            'answered'              => fake()->boolean(75),
            'notes'                 => fake()->optional()->paragraph(),
            'prediction_percentage' => null,
            'attraction_plan'       => null,
            'contacted_at'          => fake()->dateTimeBetween('-7 days', 'now'),
        ];
    }

    public function secondary(): static
    {
        return $this->state(fn() => [
            'type'                  => 'secondary',
            'prediction_percentage' => fake()->numberBetween(0, 100),
            'attraction_plan'       => fake()->optional()->paragraph(),
        ]);
    }
}
