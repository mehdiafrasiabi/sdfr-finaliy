<?php

namespace App\Exports\admin;

use App\Models\ProgramPart;
use App\Models\Student;
use App\Models\StudyPartSession;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;

class StudySessionSummaryExport implements FromCollection, WithHeadings, ShouldAutoSize
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
                    $metrics['total_hours'],
                    $metrics['average_hours'],
                    $metrics['not_registered_parts'],
                    $metrics['registered_parts'],
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
            'میزان کل ساعت مطالعه',
            'میانگین ساعت مطالعه',
            'تعداد پارت‌های ثبت ساعت مطالعه نشده',
            'تعداد پارت‌های ثبت ساعت مطالعه شده',
            'میانگین کل امتیاز ثبت ساعت مطالعه',
        ];
    }

    protected function studentsQuery(): Builder
    {
        return Student::query()
            ->with(['user.personalInformation'])
            ->where('supporter_id', $this->adminId)
            ->when($this->studentIds, fn(Builder $q) => $q->whereIn('id', $this->studentIds));
    }

    protected function buildMetricsForStudent(Student $student): array
    {
        $sessions = StudyPartSession::query()
            ->with(['feedback'])
            ->where('student_id', $student->id)
            ->whereBetween('started_at', [$this->startDate, $this->endDate])
            ->where('is_completed', true)
            ->get();

        $registeredParts = $sessions->whereNotNull('program_part_id')->pluck('program_part_id')->unique()->count();

        $scheduledParts = ProgramPart::query()
            ->with(['weeklyProgram:id,start_date'])
            ->whereHas('weeklyProgram', function (Builder $query) use ($student) {
                $query->where('student_id', $student->id)
                    ->whereDate('start_date', '<=', $this->endDate);
            })
            ->get()
            ->filter(function (ProgramPart $part) {
                if (!$part->weeklyProgram?->start_date) {
                    return false;
                }

                $partDate = Carbon::parse($part->weeklyProgram->start_date)->addDays((int) $part->day_of_week)->startOfDay();
                return $partDate->betweenIncluded($this->startDate->copy()->startOfDay(), $this->endDate->copy()->endOfDay());
            });

        $scheduledPartsCount = $scheduledParts->count();
        $totalSeconds = (int) $sessions->sum('duration_seconds');
        $avgSeconds = $sessions->count() > 0 ? (int) round($totalSeconds / $sessions->count()) : 0;

        $avgRating = round((float) ($sessions
            ->map(fn(StudyPartSession $session) => $session->feedback?->rating)
            ->filter(fn($rating) => $rating !== null)
            ->avg() ?? 0), 2);

        return [
            'total_hours' => $this->formatHours($totalSeconds),
            'average_hours' => $this->formatHours($avgSeconds),
            'registered_parts' => $registeredParts,
            'not_registered_parts' => max($scheduledPartsCount - $registeredParts, 0),
            'avg_rating' => $avgRating,
        ];
    }

    protected function formatHours(int $seconds): string
    {
        return sprintf('%02d:%02d:%02d', floor($seconds / 3600), floor(($seconds % 3600) / 60), $seconds % 60);
    }
}
