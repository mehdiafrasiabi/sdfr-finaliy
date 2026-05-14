<?php

namespace Database\Factories;

use App\Models\ProductComment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentLikeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'comment_id' => ProductComment::factory(),
            'user_id'    => User::factory(),
        ];
    }
}
