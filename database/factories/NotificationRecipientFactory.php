<?php

namespace Database\Factories;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationRecipientFactory extends Factory
{
    public function definition(): array
    {
        return [
            'notification_id' => Notification::factory(),
            'user_id'         => User::factory(),
            'is_read'         => fake()->boolean(30),
            'read_at'         => fake()->optional(0.3)->dateTimeBetween('-30 days', 'now'),
        ];
    }
}
