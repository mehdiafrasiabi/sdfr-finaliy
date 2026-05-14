<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class TicketFactory extends Factory
{
    public function definition(): array
    {
        return [
            'ticket_number'     => 'TKT-' . strtoupper(Str::random(8)),
            'user_id'           => User::factory(),
            'department_id'     => Department::factory(),
            'assigned_admin_id' => null,
            'title'             => fake()->sentence(5),
            'priority'          => fake()->randomElement(['low', 'medium', 'high', 'urgent']),
            'status'            => fake()->randomElement(['waiting', 'answered', 'closed']),
            'closed_at'         => null,
        ];
    }
}
