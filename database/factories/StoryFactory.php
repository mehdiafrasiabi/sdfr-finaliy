<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StoryFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title'       => fake()->sentence(4),
            'thumbnail'   => 'thumbnails/' . fake()->uuid() . '.jpg',
            'story'       => 'stories/' . fake()->uuid() . '.mp4',
            'status'      => fake()->boolean(70),
            'image'       => null,
            'video_path'  => null,
            'description' => fake()->optional()->paragraph(),
        ];
    }
}
