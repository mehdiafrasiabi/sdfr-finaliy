<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFeatureFactory extends Factory
{
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
        ];
    }
}
