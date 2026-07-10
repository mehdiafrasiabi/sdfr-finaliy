<?php

namespace Database\Seeders;

use App\Models\Avatar;
use Illuminate\Database\Seeder;

class AvatarSeeder extends Seeder
{
    public function run(): void
    {
        $avatars = [
            ['title' => 'ستاره پسر 1', 'gender' => 'male', 'image_path' => '/client/assets/images/avatars/star-boy-1.webp', 'sort_order' => 1],
            ['title' => 'ستاره پسر 2', 'gender' => 'male', 'image_path' => '/client/assets/images/avatars/star-boy-2.webp', 'sort_order' => 2],
            ['title' => 'ستاره پسر 3', 'gender' => 'male', 'image_path' => '/client/assets/images/avatars/star-boy-3.png', 'sort_order' => 3],
            ['title' => 'ستاره دختر 1', 'gender' => 'female', 'image_path' => '/client/assets/images/avatars/star-girl-1.webp', 'sort_order' => 1],
            ['title' => 'ستاره دختر 2', 'gender' => 'female', 'image_path' => '/client/assets/images/avatars/star-girl-2.webp', 'sort_order' => 2],
            ['title' => 'ستاره دختر 3', 'gender' => 'female', 'image_path' => '/client/assets/images/avatars/star-girl-3.png', 'sort_order' => 3],
        ];

        foreach ($avatars as $avatar) {
            Avatar::query()->updateOrCreate(
                ['image_path' => $avatar['image_path']],
                $avatar + ['is_active' => true]
            );
        }
    }
}
