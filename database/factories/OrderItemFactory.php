<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'price'      => fake()->numberBetween(1000000, 50000000),
            'order_id'   => Order::factory(),
            'product_id' => Product::factory(),
        ];
    }
}
