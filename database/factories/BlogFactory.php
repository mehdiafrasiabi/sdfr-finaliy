<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class BlogFactory extends Factory
{
    public function definition(): array
    {
        $title = fake()->sentence(6);

        return [
            'title'       => $title,
            'description' => fake()->paragraphs(5, true),
            'study_time'  => fake()->randomElement(['5 دقیقه', '10 دقیقه', '15 دقیقه']),
            'category_id' => Category::factory(),
            'blog_code'   => Str::slug($title) . '-' . fake()->unique()->numberBetween(1, 9999),
            'status'      => fake()->randomElement(['pending', 'rejected', 'completed']),
        ];
    }
}
