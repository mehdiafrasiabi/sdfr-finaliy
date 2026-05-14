<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class GeneralSettingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'site_title'       => fake()->company(),
            'site_description' => fake()->sentence(10),
            'logo_header'      => null,
            'logo_footer'      => null,
            'favicon'          => null,
            'phone'            => '02100000000',
            'address'          => fake()->address(),
            'instagram'        => fake()->optional()->userName(),
            'telegram'         => fake()->optional()->userName(),
            'about'            => fake()->optional()->paragraph(),
            'whatsapp'         => fake()->optional()->numerify('0912#######'),
            'aparat'           => null,
            'youtube'          => null,
        ];
    }
}
