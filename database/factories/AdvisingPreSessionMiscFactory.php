<?php

namespace Database\Factories;

use App\Models\AdvisingPreSession;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdvisingPreSessionMiscFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pre_session_id' => AdvisingPreSession::factory(),
            'description'    => fake()->paragraph(),
        ];
    }
}
