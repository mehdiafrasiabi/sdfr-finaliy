<?php

namespace Database\Seeders;

use App\Models\ClassificationProject;
use Illuminate\Database\Seeder;

class TrialClassificationProjectSeeder extends Seeder
{
    public function run(): void
    {
        ClassificationProject::updateOrCreate(
            ['is_trial' => true],
            [
                'name'        => 'طبقه‌بندی دروس',
                'description' => '',
                'start_at'    => now()->subYear(),
                'end_at'      => now()->addYears(10),
                'is_active'   => true,
            ]
        );
    }
}
