<?php

namespace App\Livewire\Client\Profile;

use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\WeeklyProgram;

use App\Models\Student;

class plan extends Component
{
    use WithPagination, SEOTools;

    public function mount()
    {
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('برنامه های مطالعاتی من')
            ->setDescription('برنامه های مطالعاتی من');
    }

    public function render()
    {
        $user = Auth::user();
        $studentId = $user->student->id ?? null;


        // برنامه‌های هفتگی که جلسه مربوطه result_status='held' دارد
        $weeklyPrograms = collect();
        if ($studentId) {
            $weeklyPrograms = WeeklyProgram::where('student_id', $studentId)
                ->with(['parts', 'advisingSession'])
                ->whereHas('advisingSession', function ($query) {

                    $query->where('result_status', 'held');

                })
                ->where('is_active', true)
                ->orderBy('start_date', 'desc')
                ->paginate(12);
        }

        return view('livewire.client.profile.plan', [
            'weeklyPrograms' => $weeklyPrograms,
        ])->layout('layouts.client.app');
    }
}
