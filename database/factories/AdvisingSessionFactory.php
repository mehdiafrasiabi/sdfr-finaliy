<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvisingSessionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'           => fake()->sentence(4),
            'description'     => fake()->optional()->paragraph(),
            'activation_date' => fake()->dateTimeBetween('-1 month', '+1 month')->format('Y-m-d'),
            'skyroom_link'    => fake()->optional()->url(),
            'student_id'      => Student::factory(),
            'advisor_id'      => Admin::factory(),
            'status'          => fake()->randomElement(['inactive', 'active', 'completed']),
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => ['status' => 'active']);
    }
}
