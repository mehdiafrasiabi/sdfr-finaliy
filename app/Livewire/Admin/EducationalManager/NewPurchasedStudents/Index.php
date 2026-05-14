<?php

namespace App\Livewire\Admin\EducationalManager\NewPurchasedStudents;

use App\Models\Admin;
use App\Models\Student;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * صفحه «دانش‌آموز جدید (خرید کرده)» در پنل مدیر آموزشی.
 * مدیر آموزشی برای دانش‌آموزانی که خرید کرده‌اند و هنوز «مشاور تحصیلی»
 * ندارند، یک مشاور تحصیلی از لیست انتخاب می‌کند.
 */
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    /** انتخاب مشاور برای هر کاربر — کلید آن user->id است. */
    public array $selectedAdvisor = [];

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * تخصیص مشاور تحصیلی به دانش‌آموزی که پرداخت موفق داشته.
     */
    public function assign(int $userId): void
    {
        $advisorId = $this->selectedAdvisor[$userId] ?? null;

        if (! $advisorId) {
            $this->addError("selectedAdvisor.$userId", 'انتخاب مشاور الزامی است.');
            return;
        }

        $advisor = Admin::role('مشاور تحصیلی')->find($advisorId);
        if (! $advisor) {
            $this->addError("selectedAdvisor.$userId", 'مشاور نامعتبر است.');
            return;
        }

        $student = Student::where('user_id', $userId)
            ->whereNull('advisor_id')
            ->where('is_trial', false)
            ->first();

        if (! $student) {
            session()->flash('error', 'دانش‌آموز یافت نشد یا قبلاً مشاور دارد.');
            return;
        }

        $student->advisor_id = $advisor->id;
        $student->save();

        unset($this->selectedAdvisor[$userId]);

        session()->flash('success', 'مشاور با موفقیت تخصیص داده شد.');
    }

    public function render()
    {
        // کاربرانی که پرداخت موفق دارند، student غیرآزمایشی دارند و مشاور ندارند
        $users = User::query()
            ->whereHas('payments', fn($q) => $q->where('status', 'completed'))
            ->whereHas('student', fn($s) =>
                $s->whereNull('advisor_id')->where('is_trial', false)
            )
            ->when($this->search, fn($q) =>
                $q->where(fn($w) =>
                    $w->where('name', 'like', "%{$this->search}%")
                        ->orWhere('mobile', 'like', "%{$this->search}%")
                )
            )
            ->with('personalInformation', 'student.product')
            ->latest()
            ->paginate(15);

        $advisors = Admin::role('مشاور تحصیلی')
            ->orderBy('name')
            ->get(['id', 'name', 'mobile']);

        return view('livewire.admin.educational-manager.new-purchased-students.index', [
            'users'    => $users,
            'advisors' => $advisors,
        ])->layout('layouts.admin.app');
    }
}
