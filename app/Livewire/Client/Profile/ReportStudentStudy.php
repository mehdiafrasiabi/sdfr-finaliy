<?php

namespace App\Livewire\Client\Profile;

use App\Models\ReportMonthly;
use App\Models\SmartReportCard;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ReportStudentStudy extends Component
{
    use WithPagination, SEOTools;

    public function mount()
    {
        $this->seo()
            ->setTitle('کارنامه وضعیت ماهانه من')
            ->setDescription('کارنامه وضعیت ماهانه من');
    }

    public function render()
    {
        $studentId = Auth::user()->student->id ?? null;

        $reportMonthly = ReportMonthly::query()
            ->where('student_id', $studentId)
            ->latest()
            ->paginate(12);

        $smartCards = SmartReportCard::query()
            ->where('student_id', $studentId)
            ->where('is_active', true)
            ->orderByDesc('jalali_year')
            ->orderByDesc('jalali_month')
            ->get();

        return view('livewire.client.profile.report-student-study', [
            'reportMonthly' => $reportMonthly,
            'smartCards' => $smartCards,
        ])->layout('layouts.client.app');
    }
}
