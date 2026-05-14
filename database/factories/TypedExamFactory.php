<?php

namespace Database\Factories;

use App\Models\CcTopic;
use Illuminate\Database\Eloquent\Factories\Factory;

class TypedExamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'                => fake()->sentence(4),
            'academic_year'        => fake()->randomElement(['1403-1404', '1404-1405', '1405-1406']),
            'difficulty'           => fake()->randomElement(['easy', 'medium', 'hard', 'comprehensive']),
            'is_random_selection'  => fake()->boolean(20),
            'is_published'         => fake()->boolean(60),
            'cc_topic_id'          => null,
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => ['is_published' => true]);
    }
}
