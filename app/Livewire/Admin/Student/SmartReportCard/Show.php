<?php

namespace App\Livewire\Admin\Student\SmartReportCard;

use App\Models\SmartReportCard;
use App\Models\User;
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

    public function mount(User $student): void
    {
        if (!$student->student) {
            abort(404, 'Student not found');
        }

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

    public function toggleMonth(int $month): void
    {
        if ($month < 1 || $month > 12) {
            $this->dispatch('warning', 'ماه نامعتبر است.');
            return;
        }

        $range = SmartReportCard::jalaliMonthRange($this->selectedYear, $month);

        $card = SmartReportCard::where('student_id', $this->studentId)
            ->where('jalali_year', $this->selectedYear)
            ->where('jalali_month', $month)
            ->first();

        if ($card) {
            $card->update([
                'is_active' => !$card->is_active,
                'admin_id' => auth()->id(),
                'activated_at' => !$card->is_active ? now() : $card->activated_at,
                'start_date' => $range['start']->toDateString(),
                'end_date' => $range['end']->toDateString(),
            ]);
            $this->dispatch('success', $card->is_active
                ? 'کارنامه ' . SmartReportCard::MONTH_NAMES[$month] . ' فعال شد.'
                : 'کارنامه ' . SmartReportCard::MONTH_NAMES[$month] . ' غیرفعال شد.');
            return;
        }

        SmartReportCard::create([
            'student_id' => $this->studentId,
            'admin_id' => auth()->id(),
            'jalali_year' => $this->selectedYear,
            'jalali_month' => $month,
            'start_date' => $range['start']->toDateString(),
            'end_date' => $range['end']->toDateString(),
            'is_active' => true,
            'activated_at' => now(),
        ]);

        $this->dispatch('success', 'کارنامه ' . SmartReportCard::MONTH_NAMES[$month] . ' فعال شد.');
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
}
