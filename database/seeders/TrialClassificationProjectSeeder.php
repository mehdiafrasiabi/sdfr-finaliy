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
                'name'        => 'طبقه‌بندی آزمایشی (دوره ۱ هفته)',
                'description' => 'طبقه‌بندی ویژه‌ی کاربران دوره‌ی ۱ هفته آزمایشی — همیشه فعال و بدون محدودیت زمانی.',
                'start_at'    => now()->subYear(),
                'end_at'      => now()->addYears(10),
                'is_active'   => true,
            ]
        );
    }
}
