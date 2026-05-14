<?php

namespace Database\Factories;

use App\Models\ClassificationProject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentClassificationSubmissionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'                   => User::factory(),
            'classification_project_id' => ClassificationProject::factory(),
            'is_completed'              => fake()->boolean(60),
            'submitted_at'              => fake()->optional(0.6)->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
