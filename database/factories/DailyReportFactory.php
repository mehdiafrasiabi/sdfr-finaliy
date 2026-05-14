<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\AdvisingSession;
use App\Models\Student;
use App\Models\WeeklyProgram;
use Illuminate\Database\Eloquent\Factories\Factory;

class DailyReportFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id'        => Student::factory(),
            'admin_id'          => Admin::factory(),
            'session_id'        => AdvisingSession::factory(),
            'weekly_program_id' => WeeklyProgram::factory(),
            'report_date'       => fake()->dateTimeBetween('-30 days', 'now')->format('Y-m-d'),
            'day_of_week'       => fake()->numberBetween(0, 6),
            'is_compensatory'   => false,
        ];
    }
}
