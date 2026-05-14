<?php

namespace Database\Factories;

use App\Models\CcTopic;
use App\Models\ClassificationProject;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentClassificationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'                   => User::factory(),
            'classification_project_id' => ClassificationProject::factory(),
            'cc_topic_id'               => CcTopic::factory(),
            'rating'                    => fake()->numberBetween(1, 8),
        ];
    }
}
