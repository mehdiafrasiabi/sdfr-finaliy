<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TicketMessageFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ticket_id'  => Ticket::factory(),
            'user_id'    => User::factory(),
            'admin_id'   => null,
            'message'    => fake()->paragraph(),
            'attachment' => null,
            'is_read'    => fake()->boolean(50),
        ];
    }
}
