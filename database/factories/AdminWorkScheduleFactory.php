<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdminWorkScheduleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'admin_id'    => Admin::factory(),
            'day_of_week' => fake()->numberBetween(0, 6),
            'start_time'  => fake()->time('H:i:s', '12:00:00'),
            'end_time'    => fake()->time('H:i:s', '20:00:00'),
            'is_active'   => true,
        ];
    }
}
