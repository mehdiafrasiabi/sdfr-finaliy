<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class WorkshopGroupRoomFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'        => fake()->sentence(4),
            'link'         => fake()->optional()->url(),
            'description'  => fake()->optional()->paragraph(),
            'is_active'    => fake()->boolean(70),
            'scheduled_at' => fake()->optional()->dateTimeBetween('now', '+30 days'),
            'student_id'   => Student::factory(),
            'admin_id'     => Admin::factory(),
        ];
    }
}
