<?php

namespace Database\Factories;

use App\Models\Blog;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogImageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'blog_id' => Blog::factory(),
        ];
    }
}
