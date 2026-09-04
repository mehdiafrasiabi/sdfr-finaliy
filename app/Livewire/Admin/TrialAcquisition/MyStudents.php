<?php

namespace App\Livewire\Admin\TrialAcquisition;

use App\Models\SmartReportCard;
use App\Models\TrialWeek;
use App\Services\ExamPlanningService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MyStudents extends Component
{
    use WithPagination;

    public string $search = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $adminId = Auth::guard('admin')->id();

        $students = TrialWeek::query()
            ->with([
                'user.personalInformation.state',
                'user.personalInformation.city',
                'student.examSchedules' => function ($query) {
                    $query->orderByDesc('access_expires_at')
                        ->orderByDesc('exam_ends_at')
                        ->orderByDesc('updated_at');
                },
            ])
            ->visibleForAcquisition()
            ->where('acquisition_supporter_id', $adminId)
            ->when($this->search, function ($query) {
                $term = trim($this->search);

                $query->where(function ($q) use ($term) {
                    $q->whereHas('user', function ($userQuery) use ($term) {
                        $userQuery->where('name', 'like', "%{$term}%")
                            ->orWhere('mobile', 'like', "%{$term}%");
                    })->orWhereHas('user.personalInformation', function ($infoQuery) use ($term) {
                        $infoQuery->where('name', 'like', "%{$term}%")
                            ->orWhere('name_full', 'like', "%{$term}%")
                            ->orWhere('father_mobile', 'like', "%{$term}%")
                            ->orWhere('mother_mobile', 'like', "%{$term}%");
                    });
                });
            })
            ->latest()
            ->paginate(20);

        $examPlanning = app(ExamPlanningService::class);
        $reportCardAvailable = [];
        $reportCardLinks = [];
        $accessMeta = [];

        foreach ($students->getCollection() as $trial) {
            $card = $trial->student
                ? SmartReportCard::query()
                    ->where('student_id', $trial->student->id)
                    ->where('is_active', true)
                    ->orderByDesc('jalali_year')
                    ->orderByDesc('jalali_month')
                    ->first()
                : null;

            $reportCardAvailable[$trial->id] = $trial->student
                && $examPlanning->automaticTrialReportCardUnlockedForStudent($trial->student)
                && $card;

            $reportCardLinks[$trial->id] = $card && $trial->user_id
                ? route('admin.student.smartReportCard.view', [
                    'student' => $trial->user_id,
                    'year' => $card->jalali_year,
                    'month' => $card->jalali_month,
                ])
                : null;

            $accessMeta[$trial->id] = $this->accessMetaFor($trial);
        }

        return view('livewire.admin.trial-acquisition.my-students', [
            'students' => $students,
            'reportCardAvailable' => $reportCardAvailable,
            'reportCardLinks' => $reportCardLinks,
            'accessMeta' => $accessMeta,
        ])->layout('layouts.admin.app');
    }

    protected function accessMetaFor(TrialWeek $trial): array
    {
        $examSchedule = $trial->student?->examSchedules
            ?->filter(fn ($schedule) => $schedule->access_expires_at || $schedule->exam_ends_at)
            ->sortByDesc(function ($schedule) {
                $endsAt = $this->examAccessEndsAt($schedule);

                return $endsAt?->timestamp ?? 0;
            })
            ->first();

        $type = $examSchedule ? 'برنامه امتحانی' : 'هفته آزمایشی';
        $endsAt = $examSchedule ? $this->examAccessEndsAt($examSchedule) : $trial->expires_at;

        if (! $endsAt) {
            return [
                'label' => 'بدون تاریخ پایان',
                'color' => 'secondary',
                'type' => $type,
                'ends_at' => null,
            ];
        }

        $endsAt = Carbon::parse($endsAt);

        if ($endsAt->isPast()) {
            return [
                'label' => 'منقضی شده',
                'color' => 'danger',
                'type' => $type,
                'ends_at' => $endsAt,
            ];
        }

        $remainingDays = (int) now()->startOfDay()->diffInDays($endsAt->copy()->startOfDay()) + 1;

        return [
            'label' => $remainingDays <= 1 ? 'امروز آخرین روز دسترسی' : number_format($remainingDays) . ' روز مانده',
            'color' => $remainingDays <= 3 ? 'warning' : 'success',
            'type' => $type,
            'ends_at' => $endsAt,
        ];
    }

    protected function examAccessEndsAt($schedule): ?Carbon
    {
        if ($schedule->access_expires_at) {
            return Carbon::parse($schedule->access_expires_at);
        }

        return $schedule->exam_ends_at
            ? Carbon::parse($schedule->exam_ends_at)->addDay()->endOfDay()
            : null;
    }
}
