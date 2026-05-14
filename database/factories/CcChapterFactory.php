<?php

namespace Database\Factories;

use App\Models\CcSubject;
use Illuminate\Database\Eloquent\Factories\Factory;

class CcChapterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'cc_subject_id' => CcSubject::factory(),
            'name'          => 'فصل ' . fake()->numberBetween(1, 10) . ': ' . fake()->words(3, true),
            'order'         => fake()->numberBetween(0, 20),
            'is_active'     => true,
        ];
    }
}
