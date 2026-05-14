<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class GradePriceFactory extends Factory
{
    public function definition(): array
    {
        $grade = fake()->randomElement([9, 10, 11, 12]);
        return [
            'grade'               => $grade,
            'field'               => $grade === 9 ? null : fake()->randomElement(['math', 'experimental', 'human', null]),
            'label'               => fake()->optional()->sentence(3),
            'total_amount'        => fake()->randomElement([3000000, 4000000, 5000000, 6000000]),
            'months'              => fake()->randomElement([6, 9, 12]),
            'discount_percentage' => fake()->randomElement([0, 0, 0, 5, 10, 15]),
            'start_at'            => fake()->dateTimeBetween('-1 month', 'now'),
            'end_at'              => fake()->optional(0.7)->dateTimeBetween('+3 months', '+1 year'),
            'is_active'           => fake()->boolean(80),
            'created_by'          => Admin::factory(),
        ];
    }
}
