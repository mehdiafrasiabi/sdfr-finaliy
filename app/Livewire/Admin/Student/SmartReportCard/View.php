<?php

namespace App\Livewire\Admin\Student\SmartReportCard;

use App\Concerns\BuildsSmartReportCardData;
use App\Models\SmartReportCard;
use App\Models\User;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

/**
 * نمایش کارنامهٔ هوشمندِ یک دانش‌آموز (دقیقاً همان دیتای سمت دانش‌آموز) برای مشاور.
 *
 * مشاور از صفحهٔ «مدیریت کارنامه» (فعال/غیرفعال‌سازی هر ماه) می‌تواند برای هر ماه
 * کارنامهٔ کامل را با تمام جزئیات ببیند. خروجی این صفحه با کامپوننت دانش‌آموز یکسان
 * است چون هر دو از تِرِیت مشترک BuildsSmartReportCardData استفاده می‌کنند.
 */
class View extends Component
{
    use SEOTools;
    use BuildsSmartReportCardData;

    public int $studentId;
    public int $userId;
    public string $studentName;
    public ?string $studentGrade = null;

    public int $jalaliYear;
    public int $jalaliMonth;

    public string $startDate;
    public string $endDate;

    public function mount(User $student, int $year, int $month): void
    {
        if (!$student->student) {
            abort(404, 'Student not found');
        }

        // فقط مشاورِ همان دانش‌آموز یا سوپرادمین اجازهٔ مشاهده دارد.
        $admin = auth('admin')->user();
        $isOwner = (int) $student->student->advisor_id === (int) auth('admin')->id();
        abort_unless($isOwner || $admin?->hasRole('super admin'), 403);

        if ($month < 1 || $month > 12) {
            abort(404);
        }

        $this->studentId = $student->student->id;
        $this->userId = $student->id;
        $this->studentName = $student->personalInformation->name ?? $student->name ?? 'دانش‌آموز';
        $this->studentGrade = (string) ($student->personalInformation->grade ?? '12');
        $this->jalaliYear = $year;
        $this->jalaliMonth = $month;

        // بازهٔ همان ماه؛ اگر کارنامه‌ای ذخیره شده باشد از بازهٔ همان استفاده می‌شود.
        $range = SmartReportCard::jalaliMonthRange($year, $month);
        $existing = SmartReportCard::where('student_id', $this->studentId)
            ->where('jalali_year', $year)
            ->where('jalali_month', $month)
            ->first();

        $this->startDate = ($existing?->start_date ?? $range['start'])->toDateString();
        $this->endDate = ($existing?->end_date ?? $range['end'])->toDateString();

        $monthName = SmartReportCard::MONTH_NAMES[$month] ?? '-';
        $this->seo()->setTitle('کارنامه هوشمند ' . $this->studentName . ' — ' . $monthName . ' ' . $year);
    }

    /**
     * یک نمونهٔ کارت (ذخیره‌نشده) از روی پراپرتی‌های اسکالر می‌سازد تا حتی برای
     * ماه‌هایی که کارنامه‌ای ثبت نشده هم دیتا قابل نمایش باشد و در رفت‌وبرگشت‌های
     * Livewire نیازی به hydrate کردن مدل نباشد.
     */
    private function makeCard(): SmartReportCard
    {
        return new SmartReportCard([
            'student_id' => $this->studentId,
            'jalali_year' => $this->jalaliYear,
            'jalali_month' => $this->jalaliMonth,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'is_active' => false,
        ]);
    }

    public function requestSubjectDetail(int $subjectId): void
    {
        $this->dispatch('open-subject-detail',
            subjectId: $subjectId,
            startDate: $this->startDate,
            endDate:   $this->endDate,
            studentId: $this->studentId,
        );
        $this->skipRender();
    }

    public function render()
    {
        $availableGrades = $this->getAvailableGrades();
        $payload = $this->buildReportCardPayload($this->makeCard(), $availableGrades);
        $payload['card'] = $this->makeCard();

        return view('livewire.admin.student.smart-report-card.view', $payload)
            ->layout('layouts.report-card-standalone');
    }

    private function getAvailableGrades(): array
    {
        $sg = (int) $this->studentGrade;
        if ($sg <= 10) return ['10'];
        if ($sg === 11) return ['10', '11'];
        return ['10', '11', '12'];
    }
}
