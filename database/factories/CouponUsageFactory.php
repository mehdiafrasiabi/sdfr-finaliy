<?php

namespace Database\Factories;

use App\Models\Coupons;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CouponUsageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'coupon_id' => Coupons::factory(),
            'user_id'   => User::factory(),
        ];
    }
}
