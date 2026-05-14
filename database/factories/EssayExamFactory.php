<?php

namespace Database\Factories;

use App\Models\Admin;
use App\Models\CcTopic;
use Illuminate\Database\Eloquent\Factories\Factory;

class EssayExamFactory extends Factory
{
    public function definition(): array
    {
        return [
            'admin_id'          => Admin::factory(),
            'cc_topic_id'       => null,
            'title'             => fake()->sentence(4),
            'question_pdf_path' => null,
            'answer_pdf_path'   => null,
            'total_score'       => fake()->randomFloat(2, 10, 100),
        ];
    }
}
