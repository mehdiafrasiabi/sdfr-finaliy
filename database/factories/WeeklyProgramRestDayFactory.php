<?php

namespace Database\Factories;

use App\Models\WeeklyProgram;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeeklyProgramRestDayFactory extends Factory
{
    public function definition(): array
    {
        return [
            'weekly_program_id' => WeeklyProgram::factory(),
        ];
    }
}
