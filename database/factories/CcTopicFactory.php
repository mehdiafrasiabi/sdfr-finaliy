<?php

namespace Database\Factories;

use App\Models\CcChapter;
use Illuminate\Database\Eloquent\Factories\Factory;

class CcTopicFactory extends Factory
{
    public function definition(): array
    {
        return [
            'cc_chapter_id' => CcChapter::factory(),
            'parent_id'     => null,
            'name'          => fake()->words(4, true),
            'order'         => fake()->numberBetween(0, 30),
            'is_active'     => true,
        ];
    }
}
