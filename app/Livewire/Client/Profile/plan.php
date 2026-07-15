<?php

namespace App\Livewire\Client\Profile;

use Artesaos\SEOTools\Traits\SEOTools;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\WeeklyProgram;

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

        $weeklyPrograms = WeeklyProgram::where('student_id', $studentId ?? 0)
            ->with(['parts', 'advisingSession', 'examSchedule.setting'])
            ->whereHas('advisingSession', function ($query) {
                $query->where('result_status', 'held');
            })
            ->where('is_active', true)
            ->orderBy('start_date', 'desc')
            ->paginate(12);

        return view('livewire.client.profile.plan', [
            'weeklyPrograms' => $weeklyPrograms,
        ])->layout('layouts.client.app');
    }

    public function programTitle(WeeklyProgram $program): string
    {
        if ($program->examSchedule) {
            $termLabel = $program->examSchedule->setting?->term_type_label;
            $suffix = $termLabel && $termLabel !== 'امتحانات' ? ' ' . $termLabel : '';

            return 'برنامه ' .' امتحانات' . $suffix;
        }

        return 'برنامه هفته ' . jdate($program->start_date)->format('d %B');
    }

    private function programDurationLabel(WeeklyProgram $program): string
    {
        $start = Carbon::parse($program->start_date);
        $end = Carbon::parse($program->end_date ?: $program->start_date);
        $days = $start->diffInDays($end) + 1;

        if ($days >= 27 && $days <= 32) {
            return 'یک ماه';
        }

        if ($days >= 14) {
            return (string) ceil($days / 7) . ' هفته‌ای';
        }

        return (string) $days . ' روزه';
    }
}
