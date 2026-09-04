<?php

namespace App\Livewire\Admin\Student\SmartReportCard;

use App\Models\GeneralSetting;
use App\Models\SmartReportCard;
use App\Models\User;
use App\Services\ExamPlanningService;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

class Show extends Component
{
    use SEOTools;

    public int $studentId;
    public int $userId;
    public string $studentName;
    public int $selectedYear;
    public int $minYear;
    public int $maxYear;
    public bool $automaticReportOnly = false;

    public function mount(User $student): void
    {
        if (!$student->student) {
            abort(404, 'Student not found');
        }

        $examPlanning = app(ExamPlanningService::class);
        abort_unless($examPlanning->studentIsVisibleInSmartReportCards($student->student), 404);
        abort_unless($examPlanning->adminCanViewSmartReportCardStudent($student->student), 403);
        $this->ensureSmartReportCardAccess($student->student);
        $this->automaticReportOnly = $examPlanning->automaticTrialReportCardUnlockedForStudent($student->student);

        $this->studentId = $student->student->id;
        $this->userId = $student->id;
        $this->studentName = $student->personalInformation->name ?? $student->name ?? 'دانش‌آموز';

        $currentYear = (int) Jalalian::now()->getYear();
        $this->selectedYear = $currentYear;
        $this->minYear = $currentYear - 1;
        $this->maxYear = $currentYear + 1;

        $this->seo()->setTitle('کارنامه هوشمند ' . $this->studentName);
    }

    public function changeYear(int $year): void
    {
        if ($year < $this->minYear || $year > $this->maxYear) {
            $this->dispatch('warning', 'سال خارج از محدوده مجاز است.');
            return;
        }
        $this->selectedYear = $year;
    }

    public function render()
    {
        $existing = SmartReportCard::where('student_id', $this->studentId)
            ->where('jalali_year', $this->selectedYear)
            ->get()
            ->keyBy('jalali_month');

        $months = [];
        foreach (SmartReportCard::MONTH_NAMES as $num => $name) {
            $range = SmartReportCard::jalaliMonthRange($this->selectedYear, $num);
            $days = $range['days'];
            $card = $existing->get($num);

            if ($this->automaticReportOnly && (! $card || ! $card->is_active)) {
                continue;
            }

            $months[] = [
                'number' => $num,
                'name' => $name,
                'days' => $days,
                'jalali_start' => sprintf('%04d/%02d/01', $this->selectedYear, $num),
                'jalali_end' => sprintf('%04d/%02d/%02d', $this->selectedYear, $num, $days),
                'is_active' => $card?->is_active ?? false,
                'exists' => (bool) $card,
                'activated_at' => $card?->activated_at,
            ];
        }

        return view('livewire.admin.student.smart-report-card.show', [
            'months' => $months,
        ])->layout('layouts.admin.app');
    }

    private function ensureSmartReportCardAccess(\App\Models\Student $student): void
    {
        if (app(ExamPlanningService::class)->automaticTrialReportCardUnlockedForStudent($student)) {
            return;
        }

        $isEnabled = (bool) GeneralSetting::query()->value('smart_report_card_enabled');

        abort_unless($isEnabled, 403, 'دسترسی به کارنامه هوشمند توسط manager غیرفعال است.');
    }
}
