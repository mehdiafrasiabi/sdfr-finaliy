<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\ProductComment;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentReplyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'comment_id' => ProductComment::factory(),
            'admin_id'   => Admin::factory(),
            'reply'      => fake()->paragraph(),
        ];
    }
}
