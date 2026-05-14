<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'       => fake()->sentence(4),
            'description' => fake()->optional()->paragraph(),
            'status'      => fake()->randomElement(['todo', 'in_progress', 'done']),
            'priority'    => fake()->randomElement(['low', 'medium', 'high']),
            'deadline'    => fake()->optional()->dateTimeBetween('now', '+30 days'),
            'assigned_to' => null,
            'created_by'  => Admin::factory(),
            'category_id' => null,
        ];
    }
}
