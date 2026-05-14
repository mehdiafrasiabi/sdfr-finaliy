<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CcFieldFactory extends Factory
{
    protected static int $index = 0;
    protected static array $fields = [
        ['name' => 'ریاضی', 'slug' => 'math'],
        ['name' => 'تجربی', 'slug' => 'experimental'],
        ['name' => 'انسانی', 'slug' => 'human'],
        ['name' => 'عمومی', 'slug' => 'none'],
    ];

    public function definition(): array
    {
        $field = fake()->randomElement(self::$fields);

        return [
            'name'      => $field['name'],
            'slug'      => $field['slug'] . '-' . fake()->unique()->numberBetween(1, 9999),
            'order'     => fake()->numberBetween(0, 10),
            'is_active' => true,
        ];
    }
}
