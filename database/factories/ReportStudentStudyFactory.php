<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReportStudentStudyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'sender_id'   => Admin::factory(),
            'receiver_id' => Admin::factory(),
            'file_path'   => 'reports/' . fake()->uuid() . '.pdf',
            'description' => fake()->optional()->paragraph(),
        ];
    }
}
