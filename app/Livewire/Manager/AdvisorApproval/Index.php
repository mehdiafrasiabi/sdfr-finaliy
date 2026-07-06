<?php

namespace App\Livewire\Manager\AdvisorApproval;

use App\Models\Admin;
use App\Models\AdvisorSelection;
use App\Models\AdminWorkSchedule;
use App\Models\GeneralSetting;
use App\Models\Student;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * پنل سوپرادمین — تاییدِ انتخابِ مشاور (منتقل‌شده از پنل admin).
 */
class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public array $overrideAdvisor = [];
    public array $manualAdvisor = [];
    public array $manualDay     = [];

    public bool $showStudentsModal = false;
    public ?int $studentsModalAdvisorId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    protected function reviewerId()
    {
        return auth('manager')->id() ?? auth('admin')->id();
    }

    protected function defaultCapacity(): int
    {
        return (int) (GeneralSetting::query()->value('advisor_default_capacity') ?? 50);
    }

    protected function advisorAtCapacity(Admin $advisor): bool
    {
        $cap = $advisor->student_capacity ?? $this->defaultCapacity();
        return $advisor->advisedStudents()->count() >= $cap;
    }

    public function approve(int $selectionId): void
    {
        $sel = AdvisorSelection::with(['advisor', 'student'])->find($selectionId);
        if (! $sel || $sel->status !== AdvisorSelection::STATUS_PENDING) {
            $this->dispatch('warning', 'این انتخاب دیگر معتبر نیست.');
            return;
        }
        $this->finalizeAssignment($sel, $sel->advisor, $sel->weekly_day);
    }

    public function assignOverride(int $selectionId): void
    {
        $sel = AdvisorSelection::with('student')->find($selectionId);
        if (! $sel || $sel->status !== AdvisorSelection::STATUS_PENDING) {
            $this->dispatch('warning', 'این انتخاب دیگر معتبر نیست.');
            return;
        }
        $advisorId = $this->overrideAdvisor[$selectionId] ?? null;
        $advisor = $advisorId ? Admin::role('مشاور تحصیلی')->find($advisorId) : null;
        if (! $advisor) {
            $this->dispatch('warning', 'مشاورِ جایگزین را انتخاب کنید.');
            return;
        }
        $this->finalizeAssignment($sel, $advisor, $sel->weekly_day);
        unset($this->overrideAdvisor[$selectionId]);
    }

    protected function finalizeAssignment(AdvisorSelection $sel, Admin $advisor, int $weeklyDay): void
    {
        $student = $sel->student;
        if (! $student || $student->advisor_id !== null) {
            $this->dispatch('error', 'دانش‌آموز یافت نشد یا قبلاً مشاور دارد.');
            return;
        }
        if ($this->advisorAtCapacity($advisor)) {
            $this->dispatch('error', 'ظرفیتِ این مشاور تکمیل است.');
            return;
        }

        DB::transaction(function () use ($sel, $student, $advisor, $weeklyDay) {
            $student->update([
                'advisor_id'  => $advisor->id,
                'session_day' => $weeklyDay,
            ]);
            $sel->update([
                'advisor_id'  => $advisor->id,
                'status'      => AdvisorSelection::STATUS_APPROVED,
                'reviewed_by' => $this->reviewerId(),
                'reviewed_at' => now(),
            ]);
            AdvisorSelection::where('student_id', $student->id)
                ->where('status', AdvisorSelection::STATUS_PENDING)
                ->where('id', '!=', $sel->id)
                ->update(['status' => AdvisorSelection::STATUS_REJECTED]);
        });

        $this->notifyAssigned($student, $advisor, $weeklyDay);
        $this->dispatch('success', 'مشاور با موفقیت به دانش‌آموز تخصیص داده شد.');
    }

    public function reject(int $selectionId): void
    {
        $sel = AdvisorSelection::with('student')->find($selectionId);
        if (! $sel || $sel->status !== AdvisorSelection::STATUS_PENDING) {
            $this->dispatch('warning', 'این انتخاب دیگر معتبر نیست.');
            return;
        }
        $sel->update([
            'status'      => AdvisorSelection::STATUS_REJECTED,
            'reviewed_by' => $this->reviewerId(),
            'reviewed_at' => now(),
        ]);
        if ($sel->student) {
            NotificationService::sendToStudent(
                $sel->student->id,
                'انتخاب مشاور',
                'انتخابِ مشاورِ شما تایید نشد. لطفاً دوباره از بخش «انتخاب مشاور» اقدام کنید.'
            );
        }
        $this->dispatch('success', 'انتخاب رد شد و به دانش‌آموز اطلاع داده شد.');
    }

    public function assignManual(int $studentId): void
    {
        $student = Student::find($studentId);
        if (! $student || $student->advisor_id !== null) {
            $this->dispatch('error', 'دانش‌آموز یافت نشد یا قبلاً مشاور دارد.');
            return;
        }
        $advisorId = $this->manualAdvisor[$studentId] ?? null;
        $day = $this->manualDay[$studentId] ?? null;
        $advisor = $advisorId ? Admin::role('مشاور تحصیلی')->find($advisorId) : null;
        if (! $advisor) {
            $this->dispatch('warning', 'مشاور را انتخاب کنید.');
            return;
        }
        if ($day === null || $day === '' || (int) $day < 0 || (int) $day > 6) {
            $this->dispatch('warning', 'روزِ هفتگیِ جلسه را انتخاب کنید.');
            return;
        }
        if ($this->advisorAtCapacity($advisor)) {
            $this->dispatch('error', 'ظرفیتِ این مشاور تکمیل است.');
            return;
        }

        DB::transaction(function () use ($student, $advisor, $day) {
            $student->update(['advisor_id' => $advisor->id, 'session_day' => (int) $day]);
            AdvisorSelection::create([
                'student_id'  => $student->id,
                'advisor_id'  => $advisor->id,
                'weekly_day'  => (int) $day,
                'mode'        => AdvisorSelection::MODE_MANUAL,
                'status'      => AdvisorSelection::STATUS_APPROVED,
                'reviewed_by' => $this->reviewerId(),
                'reviewed_at' => now(),
            ]);
        });

        unset($this->manualAdvisor[$studentId], $this->manualDay[$studentId]);
        $this->notifyAssigned($student, $advisor, (int) $day);
        $this->dispatch('success', 'مشاور به‌صورت دستی تخصیص داده شد.');
    }

    protected function notifyAssigned(Student $student, Admin $advisor, int $weeklyDay): void
    {
        $dayName = AdminWorkSchedule::DAYS[$weeklyDay] ?? '';
        NotificationService::sendToStudent(
            $student->id,
            'تخصیص مشاور',
            "مشاورِ شما «{$advisor->name}» تعیین شد. روزِ جلسه‌ی هفتگیِ شما «{$dayName}» است. ساعتِ دقیقِ هر جلسه را مشاور یک روز قبل اعلام می‌کند."
        );
    }

    public function openStudentsModal(int $advisorId): void
    {
        $this->studentsModalAdvisorId = $advisorId;
        $this->showStudentsModal = true;
    }

    public function closeStudentsModal(): void
    {
        $this->showStudentsModal = false;
        $this->studentsModalAdvisorId = null;
    }

    public function render()
    {
        $defaultCapacity = $this->defaultCapacity();

        $pendingSelections = AdvisorSelection::query()
            ->where('status', AdvisorSelection::STATUS_PENDING)
            ->with([
                'advisor' => fn ($q) => $q->withCount('advisedStudents'),
                'student.user.personalInformation',
            ])
            ->when($this->search, function ($q) {
                $q->whereHas('student.user', function ($w) {
                    $w->where('name', 'like', "%{$this->search}%")
                        ->orWhere('mobile', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->get();

        $unassignedStudents = Student::query()
            ->whereNull('advisor_id')
            ->where('is_trial', false)
            ->whereHas('user.payments', fn ($q) => $q->where('status', 'completed'))
            ->whereDoesntHave('advisorSelections', fn ($q) => $q->where('status', AdvisorSelection::STATUS_PENDING))
            ->with(['user.personalInformation', 'product'])
            ->latest()
            ->paginate(15);

        $advisors = Admin::role('مشاور تحصیلی')
            ->withCount('advisedStudents')
            ->orderBy('name')
            ->get()
            ->map(function (Admin $a) use ($defaultCapacity) {
                $a->cap    = $a->student_capacity ?? $defaultCapacity;
                $a->isFull = $a->advised_students_count >= $a->cap;
                return $a;
            });

        $modalAdvisor = $this->studentsModalAdvisorId
            ? Admin::with(['advisedStudents.user.personalInformation'])->withCount('advisedStudents')->find($this->studentsModalAdvisorId)
            : null;

        return view('livewire.manager.advisor-approval.index', [
            'pendingSelections'  => $pendingSelections,
            'unassignedStudents' => $unassignedStudents,
            'advisors'           => $advisors,
            'days'               => AdminWorkSchedule::DAYS,
            'defaultCapacity'    => $defaultCapacity,
            'modalAdvisor'       => $modalAdvisor,
        ])->layout('layouts.manager.app');
    }
}
