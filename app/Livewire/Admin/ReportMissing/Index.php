<?php

namespace App\Livewire\Admin\ReportMissing;

use App\Models\Report;
use App\Models\Student;
use Artesaos\SEOTools\Traits\SEOTools;
use Carbon\CarbonPeriod;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;

class Index extends Component
{
    use WithPagination;
    use SEOTools;

    public ?int $selectedStudentId = null;
    public ?int $appliedStudentId = null;

    public ?string $selectedMonthKey = null;
    public ?string $appliedMonthKey = null;

    public string $appliedStudentName = 'همه دانش‌آموزان';

    // وضعیت گزارش
    public ?string $selectedStatus = 'not_sent'; // not_sent | rejected | both
    public ?string $appliedStatus = 'not_sent';

    public array $monthOptions = [];
    public int $currentJalaliYear;

    public bool $filtersSubmitted = false;

    protected int $perPage = 12;

    public function mount(): void
    {
        $this->monthOptions = $this->buildMonthOptions();
        $this->currentJalaliYear = Jalalian::fromCarbon(now())->getYear();

        $currentMonthKey = $this->formatMonthKey(Jalalian::fromCarbon(now())->getMonth());
        $this->selectedMonthKey = $currentMonthKey;

        $this->appliedMonthKey = null;
        $this->appliedStudentId = null;
        $this->appliedStudentName = 'همه دانش‌آموزان';

        $this->filtersSubmitted = false;

        // وضعیت پیش‌فرض
        $this->selectedStatus = 'not_sent';
        $this->appliedStatus = 'not_sent';

        $this->seoConfig();
    }

    public function seoConfig(): void
    {
        $this->seo()->setTitle('گزارش‌های ارسال نشده');
    }

    public function updatedSelectedStudentId($value): void
    {
        $this->selectedStudentId = $value ? (int) $value : null;
    }

    public function updatedSelectedMonthKey($value): void
    {
        $this->selectedMonthKey = $value ?: null;
    }

    public function selectAllStudents(): void
    {
        $this->selectedStudentId = null;
        $this->dispatch('refreshStudentSelect', value: null);
    }

    public function selectAllMonths(): void
    {
        $this->selectedMonthKey = null;
    }

    public function submitFilters(): void
    {
        // نرمال‌سازی برای جلوگیری از آی‌دی یا ماه غیرمجاز
        $this->selectedStudentId = $this->normalizeStudentId($this->selectedStudentId);
        $this->selectedMonthKey = $this->normalizeMonthKey($this->selectedMonthKey);

        // نرمال‌سازی وضعیت
        if (! in_array($this->selectedStatus, ['not_sent', 'rejected', 'both'], true)) {
            $this->selectedStatus = 'not_sent';
        }

        $this->appliedStudentId   = $this->selectedStudentId;
        $this->appliedMonthKey    = $this->selectedMonthKey;
        $this->appliedStudentName = $this->resolveStudentLabel($this->appliedStudentId);
        $this->appliedStatus      = $this->selectedStatus;

        $this->filtersSubmitted = true;

        $this->resetPage();

        // بعد از ثبت فیلتر، مقدار Select2 را هم آپدیت کن
        $this->dispatch('refreshStudentSelect', value: $this->selectedStudentId);
    }

    /**
     * لیست دانش‌آموزان منتسب به ادمین فعلی (پشتیبان یا مشاور)
     */
    protected function studentOptions(): Collection
    {
        return $this->managedStudentsQuery()
            ->with(['user.personalInformation'])
            ->orderByDesc('id')
            ->limit(50)
            ->get();
    }

    /**
     * صفحه‌بندی روزهای فاقد گزارش / رد شده
     */
    protected function buildMissingDaysPaginator(): LengthAwarePaginator
    {
        $students = $this->managedStudentsQuery()
            ->with(['user.personalInformation'])
            ->when($this->appliedStudentId, function ($query) {
                $query->where('id', $this->appliedStudentId);
            })
            ->get();

        if ($students->isEmpty()) {
            $results = collect();
        } else {
            switch ($this->appliedStatus) {
                case 'rejected':
                    $results = $this->compileRejectedDays($students);
                    break;

                case 'both':
                    $notSent  = $this->compileMissingDays($students);
                    $rejected = $this->compileRejectedDays($students);

                    $results = $notSent->concat($rejected)->sortBy([
                        ['student_name', 'asc'],
                        ['month_key', 'asc'],
                        ['day_formatted', 'asc'],
                    ])->values();
                    break;

                case 'not_sent':
                default:
                    $results = $this->compileMissingDays($students);
                    break;
            }
        }

        $pageName    = 'page';
        $currentPage = Paginator::resolveCurrentPage($pageName);
        $items       = $results->slice(($currentPage - 1) * $this->perPage, $this->perPage)->values();

        return new LengthAwarePaginator(
            $items,
            $results->count(),
            $this->perPage,
            $currentPage,
            [
                'path'     => request()->url(),
                'pageName' => $pageName,
            ]
        );
    }

    /**
     * محاسبه همه روزهای بدون گزارش بر اساس دانش‌آموزها و ماه‌های انتخاب‌شده (شمسی)
     */
    protected function compileMissingDays(Collection $students): Collection
    {
        $months = $this->appliedMonthKey
            ? [$this->appliedMonthKey]
            : array_keys($this->monthOptions);

        $now      = now();
        $timezone = config('app.timezone', 'UTC');

        $results = collect();

        foreach ($students as $student) {
            foreach ($months as $monthKey) {
                [$start, $end] = $this->monthDateRange((int) $this->currentJalaliYear, $monthKey);

                if ($start === null || $end === null) {
                    continue;
                }

                if ($end->greaterThan($now)) {
                    $end = $now->copy()->endOfDay();
                }

                if ($start->greaterThan($end)) {
                    continue;
                }

                $reportDays = Report::query()
                    ->where('student_id', $student->id)
                    ->where('admin_id', auth()->id())
                    ->whereBetween('created_at', [$start, $end])
                    ->pluck('created_at')
                    ->map(fn ($date) => $date->copy()->timezone($timezone)->toDateString())
                    ->unique();

                $period = CarbonPeriod::create(
                    $start->copy()->startOfDay(),
                    '1 day',
                    $end->copy()->endOfDay()
                );

                foreach ($period as $day) {
                    if ($day->greaterThan($now)) {
                        continue;
                    }

                    $dayKey = $day->copy()->timezone($timezone)->toDateString();

                    if ($reportDays->contains($dayKey)) {
                        continue;
                    }

                    $jalaliDay = Jalalian::fromCarbon($day);

                    $results->push([
                        'student_id'    => $student->id,
                        'student_name'  => $this->studentDisplayName($student),
                        'month_key'     => $monthKey,
                        'month_name'    => $this->monthOptions[$monthKey] ?? $monthKey,
                        'day_formatted' => $jalaliDay->format('Y/m/d'),
                        'weekday'       => $jalaliDay->format('%A'),
                        'status_type'   => 'not_sent',
                    ]);
                }
            }
        }

        return $results->sortBy([
            ['student_name', 'asc'],
            ['month_key', 'asc'],
            ['day_formatted', 'asc'],
        ])->values();
    }

    /**
     * دانش‌آموزان پشتیبان/مشاور فعلی
     */
    protected function managedStudentsQuery()
    {
        $adminId = auth()->id();

        return Student::query()
            ->where(function ($query) use ($adminId) {
                $query->where('supporter_id', $adminId)
                    ->orWhere('advisor_id', $adminId);
            });
    }

    protected function normalizeStudentId(?int $studentId): ?int
    {
        if (! $studentId) {
            return null;
        }

        $exists = $this->managedStudentsQuery()->where('id', $studentId)->exists();

        return $exists ? $studentId : null;
    }

    protected function normalizeMonthKey(?string $monthKey): ?string
    {
        if (! $monthKey) {
            return null;
        }

        $formatted = $this->formatMonthKey((int) $monthKey);

        return array_key_exists($formatted, $this->monthOptions) ? $formatted : null;
    }

    /**
     * بازه‌ی دقیق یک ماه شمسی (مثلاً آبان ۱۴۰۳ → ۱۴۰۳/۰۸/۰۱ تا ۱۴۰۳/۰۸/۳۰)
     */
    protected function monthDateRange(int $year, string $monthKey): array
    {
        $month = (int) $monthKey;

        try {
            // شروع ماه شمسی
            $jalaliStart = Jalalian::fromFormat('Y-m-d', sprintf('%04d-%02d-01', $year, $month));

            // تعداد روزهای همان ماه شمسی (۲۹/۳۰/۳۱)
            $daysInMonth = $jalaliStart->getMonthDays();

            // پایان ماه شمسی
            $jalaliEnd = Jalalian::fromFormat('Y-m-d', sprintf('%04d-%02d-%02d', $year, $month, $daysInMonth));
        } catch (\Throwable) {
            return [null, null];
        }

        return [
            $jalaliStart->toCarbon()->startOfDay(),
            $jalaliEnd->toCarbon()->endOfDay(),
        ];
    }

    protected function studentDisplayName(Student $student): string
    {
        return $student->user->personalInformation->name
            ?? $student->user->name
            ?? '---';
    }

    protected function resolveStudentLabel(?int $studentId): string
    {
        if (! $studentId) {
            return 'همه دانش‌آموزان';
        }

        $student = $this->managedStudentsQuery()
            ->with(['user.personalInformation'])
            ->where('id', $studentId)
            ->first();

        if (! $student) {
            return 'همه دانش‌آموزان';
        }

        return $this->studentDisplayName($student);
    }

    protected function buildMonthOptions(): array
    {
        return [
            '01' => 'فروردین',
            '02' => 'اردیبهشت',
            '03' => 'خرداد',
            '04' => 'تیر',
            '05' => 'مرداد',
            '06' => 'شهریور',
            '07' => 'مهر',
            '08' => 'آبان',
            '09' => 'آذر',
            '10' => 'دی',
            '11' => 'بهمن',
            '12' => 'اسفند',
        ];
    }

    protected function compileRejectedDays(Collection $students): Collection
    {
        $months = $this->appliedMonthKey
            ? [$this->appliedMonthKey]
            : array_keys($this->monthOptions);

        $timezone = config('app.timezone', 'UTC');
        $results  = collect();

        foreach ($students as $student) {
            foreach ($months as $monthKey) {
                [$start, $end] = $this->monthDateRange((int) $this->currentJalaliYear, $monthKey);

                if ($start === null || $end === null) {
                    continue;
                }

                $reports = Report::query()
                    ->where('student_id', $student->id)
                    ->where('admin_id', auth()->id())
                    ->where('status', 'rejected') // اگر اسم ستون یا مقدار فرق دارد، اینجا را تغییر بده
                    ->whereBetween('created_at', [$start, $end])
                    ->get();

                foreach ($reports as $report) {
                    $day       = $report->created_at->copy()->timezone($timezone);
                    $jalaliDay = Jalalian::fromCarbon($day);
                    $monthKeyNormalized = $this->formatMonthKey(
                        (int) Jalalian::fromCarbon($day)->getMonth()
                    );

                    $results->push([
                        'student_id'    => $student->id,
                        'student_name'  => $this->studentDisplayName($student),
                        'month_key'     => $monthKeyNormalized,
                        'month_name'    => $this->monthOptions[$monthKeyNormalized] ?? $monthKeyNormalized,
                        'day_formatted' => $jalaliDay->format('Y/m/d'),
                        'weekday'       => $jalaliDay->format('%A'),
                        'status_type'   => 'rejected',
                    ]);
                }
            }
        }

        return $results->sortBy([
            ['student_name', 'asc'],
            ['month_key', 'asc'],
            ['day_formatted', 'asc'],
        ])->values();
    }

    protected function formatMonthKey(int $month): string
    {
        return str_pad((string) $month, 2, '0', STR_PAD_LEFT);
    }

    public function render()
    {
        $studentOptions = $this->studentOptions();

        // اگر هنوز فیلتر ثبت نشده، هیچ چیزی را محاسبه نکن
        if (! $this->filtersSubmitted) {
            $emptyPaginator = new LengthAwarePaginator(
                collect(),
                0,
                $this->perPage,
                1,
                [
                    'path'     => request()->url(),
                    'pageName' => 'page',
                ]
            );

            return view('livewire.admin.report-missing.index', [
                'studentOptions' => $studentOptions,
                'missingDays'    => $emptyPaginator,
            ])->layout('layouts.admin.app');
        }

        // بعد از ثبت فیلترها:
        $missingDays = $this->buildMissingDaysPaginator();

        return view('livewire.admin.report-missing.index', [
            'studentOptions' => $studentOptions,
            'missingDays'    => $missingDays,
        ])->layout('layouts.admin.app');
    }
}
