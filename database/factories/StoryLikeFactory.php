<?php

namespace Database\Factories;

use App\Models\Story;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class StoryLikeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'story_id' => Story::factory(),
            'user_id'  => User::factory(),
        ];
    }
}
