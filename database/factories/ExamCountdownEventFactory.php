<?php

namespace Database\Factories;

use App\Models\ExamCountdownSetting;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamCountdownEventFactory extends Factory
{
    public function definition(): array
    {
        return [
            'exam_countdown_setting_id' => ExamCountdownSetting::factory(),
            'event_title'               => fake()->sentence(3),
            'event_date'                => fake()->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
            'sort_order'                => fake()->numberBetween(0, 100),
        ];
    }
}
