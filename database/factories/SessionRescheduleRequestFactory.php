<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class SessionRescheduleRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'student_id'              => Student::factory(),
            'advisor_id'              => null,
            'type'                    => fake()->randomElement(['exception', 'permanent']),
            'original_session_id'     => null,
            'original_day'            => null,
            'original_time'           => null,
            'student_proposed_day'    => fake()->numberBetween(0, 6),
            'student_proposed_time'   => fake()->time('H:i:s'),
            'student_description'     => fake()->optional()->sentence(),
            'manager_available_slots' => null,
            'consultant_proposed_day' => null,
            'consultant_proposed_time' => null,
            'consultant_notes'        => null,
            'student_selected_day'    => null,
            'student_selected_time'   => null,
            'status'                  => 'pending_manager_review',
            'rejection_reason'        => null,
            'approved_at'             => null,
        ];
    }
}
