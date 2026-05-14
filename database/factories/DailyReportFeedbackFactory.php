<?php

namespace Database\Factories;

use App\Models\DailyReport;
use Illuminate\Database\Eloquent\Factories\Factory;

class DailyReportFeedbackFactory extends Factory
{
    public function definition(): array
    {
        return [
            'daily_report_id'     => DailyReport::factory(),
            'advisor_comment'     => fake()->optional()->paragraph(),
            'advisor_commented_at' => fake()->optional()->dateTimeBetween('-30 days', 'now'),
            'student_reply'       => fake()->optional()->sentence(),
            'student_replied_at'  => fake()->optional()->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
