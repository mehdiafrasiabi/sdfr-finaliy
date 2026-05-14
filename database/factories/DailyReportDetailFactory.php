<?php

namespace Database\Factories;

use App\Models\DailyReport;
use Illuminate\Database\Eloquent\Factories\Factory;

class DailyReportDetailFactory extends Factory
{
    public function definition(): array
    {
        return [
            'daily_report_id' => DailyReport::factory(),
            'phone_hours'     => fake()->numberBetween(0, 12),
            'description'     => fake()->optional()->paragraph(),
            'rating'          => fake()->numberBetween(1, 5),
            'status'          => fake()->randomElement(['pending', 'approved', 'rejected']),
        ];
    }
}
