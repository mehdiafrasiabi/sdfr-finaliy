<?php

namespace App\Livewire\Client\Profile;

use App\Models\BarnamehView;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\WeeklyProgram;

use App\Models\Student;

class Barnameh extends Component
{
    use WithPagination, SEOTools;

    public function mount()
    {
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('برنامه و گزارش های من')
            ->setDescription('برنامه و گزارش های من');
    }

    public function markAsViewed($barnamehId)
    {
        $studentId = auth()->user()?->student?->id;

        if (!$studentId) return;

        BarnamehView::firstOrCreate([
            'barnameh_id' => $barnamehId,
            'student_id' => $studentId,
        ]);
    }

    public function render()
    {
        $user = Auth::user();
        $studentId = $user->student->id ?? null;
        $plans = \App\Models\Barnameh::query()
            ->where('student_id', $studentId)
            ->latest()
            ->paginate(12);

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

        return view('livewire.client.profile.barnameh', [
            'plans' => $plans,
            'weeklyPrograms' => $weeklyPrograms,
        ])->layout('layouts.client.app');
    }
}
