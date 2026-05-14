<?php

namespace Database\Factories;

use App\Models\DailyReport;
use App\Models\ProgramPart;
use Illuminate\Database\Eloquent\Factories\Factory;

class DailyReportPartFactory extends Factory
{
    public function definition(): array
    {
        return [
            'daily_report_id' => DailyReport::factory(),
            'program_part_id' => ProgramPart::factory(),
            'is_read'         => fake()->boolean(70),
            'tests_done'      => fake()->numberBetween(0, 50),
            'is_compensatory' => false,
            'missed_parts_reason' => fake()->optional()->sentence(),
            'part_rating'     => fake()->optional()->numberBetween(1, 5),
        ];
    }
}
