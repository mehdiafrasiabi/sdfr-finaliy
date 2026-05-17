<?php

namespace App\Exports\admin;

use App\Models\AdvisingSession;
use App\Models\DailyReport;
use App\Models\Student;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportDailyActivitiesSummaryExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    public function __construct(
        private readonly int $adminId,
        private readonly Carbon $startDate,
        private readonly Carbon $endDate,
        private readonly ?array $studentIds = null,
    ) {
    }

    public function collection(): Collection
    {
        return $this->studentsQuery()
            ->get()
            ->map(function (Student $student, int $index) {
                $metrics = $this->buildMetricsForStudent($student);

                return [
                    $index + 1,
                    trim(($student->user?->personalInformation?->name ?? '') . ' ' . ($student->user?->personalInformation?->name_full ?? '')) ?: '-',
                    $student->user?->mobile ?? '-',
                    $metrics['sent_reports'],
                    $metrics['not_sent_reports'],
                    $metrics['total_reports'],
                    $metrics['read_parts'],
                    $metrics['unread_parts'],
                    $metrics['total_parts'],
                    $metrics['done_tests'],
                    $metrics['undone_tests'],
                    $metrics['total_tests'],
                    $metrics['avg_rating'],
                ];
            });
    }

    public function headings(): array
    {
        return [
            '#',
            'نام دانش‌آموز',
            'شماره موبایل',
            'تعداد گزارش ارسال شده',
            'تعداد گزارش ارسال نشده',
            'تعداد کل گزارشات',
            'پارت‌های خوانده شده',
            'پارت‌های خوانده نشده',
            'مجموع کل پارت‌ها',
            'تعداد کل تست زده شده',
            'تعداد کل تست نزده شده',
            'مجموع کل تست‌ها',
            'میانگین کل امتیاز ثبت ساعت مطالعه',
        ];
    }

    protected function studentsQuery(): Builder
    {
        return Student::query()
            ->with(['user.personalInformation'])
            ->where('advisor_id', $this->adminId)
            ->when($this->studentIds, fn(Builder $q) => $q->whereIn('id', $this->studentIds));
    }

    protected function buildMetricsForStudent(Student $student): array
    {
        $reports = DailyReport::query()
            ->with(['reportParts.programPart', 'weeklyProgram.parts'])
            ->where('student_id', $student->id)
            ->whereBetween('report_date', [$this->startDate->toDateString(), $this->endDate->toDateString()])
            ->get();

        $sentReports = $reports->count();
        $expectedDays = $this->calculateExpectedReportDays($student);
        $notSentReports = max($expectedDays - $sentReports, 0);

        $readParts = 0;
        $totalParts = 0;
        $doneTests = 0;
        $totalTests = 0;

        foreach ($reports as $report) {
            $reportPartsMap = $report->reportParts->keyBy('program_part_id');
            $programParts = $report->getProgramPartsForDay();

            if ($programParts->isEmpty()) {
                $programParts = $report->reportParts->map(fn($rp) => $rp->programPart)->filter();
            }

            $totalParts += $programParts->count();

            foreach ($programParts as $programPart) {
                $reportPart = $reportPartsMap->get($programPart->id);
                if ($reportPart?->is_read) {
                    $readParts++;
                }
                $doneTests += (int) ($reportPart?->tests_done ?? 0);
                $totalTests += (int) ($programPart->test_count ?? 0);
            }
        }

        $avgRating = round((float) ($student->sessionFeedbacks()
            ->whereBetween('created_at', [$this->startDate, $this->endDate])
            ->avg('rating') ?? 0), 2);

        return [
            'sent_reports' => $sentReports,
            'not_sent_reports' => $notSentReports,
            'total_reports' => $sentReports + $notSentReports,
            'read_parts' => $readParts,
            'unread_parts' => max($totalParts - $readParts, 0),
            'total_parts' => $totalParts,
            'done_tests' => $doneTests,
            'undone_tests' => max($totalTests - $doneTests, 0),
            'total_tests' => $totalTests,
            'avg_rating' => $avgRating,
        ];
    }

    protected function calculateExpectedReportDays(Student $student): int
    {
        $today = Carbon::today();
        $rangeEnd = $this->endDate->copy()->min($today);

        if ($rangeEnd->lt($this->startDate)) {
            return 0;
        }

        $sessions = AdvisingSession::query()
            ->where('student_id', $student->id)
            ->where('result_status', AdvisingSession::RESULT_HELD)
            ->whereDate('activation_date', '<=', $rangeEnd)
            ->get(['activation_date']);

        $dates = [];

        foreach ($sessions as $session) {
            $activation = Carbon::parse($session->activation_date)->startOfDay();
            $sessionEnd = $activation->copy()->addDays(7);

            $start = $activation->copy()->max($this->startDate->copy()->startOfDay());
            $end = $sessionEnd->copy()->min($rangeEnd->copy()->endOfDay());

            if ($start->gt($end)) {
                continue;
            }

            foreach (CarbonPeriod::create($start, '1 day', $end) as $day) {
                $dates[$day->format('Y-m-d')] = true;
            }
        }

        return count($dates);
    }
}
