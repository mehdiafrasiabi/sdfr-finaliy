<?php

namespace App\Livewire\Admin\TrialWeek;

use App\Models\ClassSchedule;
use App\Models\TrialWeek;
use App\Services\TrialWeekService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $statusFilter = 'all';

    protected $queryString = ['search', 'statusFilter'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    // تکمیل پیش‌جلسه توسط پشتیبان
    public function markPreSessionDone(int $trialId, TrialWeekService $service): void
    {
        $trial = TrialWeek::where('id', $trialId)
            ->where('acquisition_supporter_id', Auth::guard('admin')->id())
            ->firstOrFail();

        if ($trial->status !== TrialWeek::STATUS_CLASSIFICATION_DONE) {
            session()->flash('error', 'وضعیت نادرست است.');
            return;
        }

        $hasFinalizedSchedule = ClassSchedule::where('student_id', $trial->student_id)
            ->where('is_finalized', true)
            ->exists();
        if (!$hasFinalizedSchedule) {
            session()->flash('error', 'دانش‌آموز هنوز برنامه کلاسی خود را نهایی نکرده است.');
            return;
        }

        $service->completePreSession($trial);
        session()->flash('success', 'پیش‌جلسه با موفقیت تکمیل شد.');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $adminId = Auth::guard('admin')->id();

        $query = TrialWeek::with(['user', 'student'])
            ->where('acquisition_supporter_id', $adminId)
            ->when($this->search, function ($q) {
                $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$this->search}%")
                    ->orWhere('mobile', 'like', "%{$this->search}%"));
            })
            ->when($this->statusFilter !== 'all', fn($q) => $q->where('status', $this->statusFilter))
            ->latest();

        return view('livewire.admin.trial-week.index', [
            'trials' => $query->paginate(15),
        ])->layout('layouts.admin.app');
    }
}
