<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'admin_id'        => Admin::factory(),
            'student_id'      => null,
            'title'           => fake()->sentence(4),
            'body'            => fake()->paragraph(),
            'is_read'         => false,
            'category'        => fake()->randomElement(['announcement', 'special', 'advisor', 'supporter']),
            'target_type'     => fake()->randomElement(['all_users', 'all_students', 'single']),
            'is_from_manager' => false,
        ];
    }
}
