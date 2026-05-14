<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'name'          => $name,
            'title'         => fake()->sentence(5),
            'tag'           => fake()->optional()->word(),
            'type'          => fake()->randomElement(['weekly', 'monthly', 'yearly_online', 'yearly_offline']),
            'duration_days' => fake()->randomElement([7, 30, 90, 365]),
            'has_supporter' => fake()->boolean(),
            'has_advisor'   => fake()->boolean(),
            'course_time'   => fake()->randomElement(['2 ساعت', '3 ساعت', '4 ساعت']),
            'meeting_time'  => fake()->randomElement(['هفته‌ای یکبار', 'هفته‌ای دوبار']),
            'price'         => fake()->numberBetween(1000000, 50000000),
            'description'   => fake()->paragraph(),
            'category_id'   => Category::factory(),
            'p_code'        => strtoupper(Str::random(8)),
        ];
    }
}
