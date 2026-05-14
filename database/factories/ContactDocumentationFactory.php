<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactDocumentationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'admin_id'       => Admin::factory(),
            'student_id'     => Student::factory(),
            'title'          => fake()->sentence(4),
            'description'    => fake()->optional()->paragraph(),
            'contact_status' => fake()->randomElement(['successful', 'unsuccessful']),
            'contact_date'   => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'respondent'     => fake()->randomElement(['father', 'mother', 'student', 'other']),
        ];
    }
}
