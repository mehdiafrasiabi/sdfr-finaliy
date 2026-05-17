<?php

namespace App\Livewire\Admin\EducationalManager\NewTrialStudents;

use App\Models\Admin;
use App\Models\TrialWeek;
use App\Services\TrialWeekService;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * صفحه «دانش‌آموز جدید (هفته آزمایشی)» در پنل مدیر آموزشی.
 * مدیر آموزشی برای هر هفته آزمایشی که هنوز «پشتیبان جذب» ندارد،
 * یک پشتیبان جذب از لیست انتخاب می‌کند.
 */
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    /** انتخاب پشتیبان جذب برای هر trial week — کلید آن trialWeek->id است. */
    public array $selectedSupporter = [];

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * تخصیص پشتیبان جذب به هفته آزمایشی توسط مدیر آموزشی.
     */
    public function assign(int $trialWeekId, TrialWeekService $service): void
    {
        $supporterId = $this->selectedSupporter[$trialWeekId] ?? null;

        if (! $supporterId) {
            $this->addError("selectedSupporter.$trialWeekId", 'انتخاب پشتیبان جذب الزامی است.');
            return;
        }

        $supporter = Admin::role('site acquisition')->find($supporterId);
        if (! $supporter) {
            $this->addError("selectedSupporter.$trialWeekId", 'پشتیبان جذب نامعتبر است.');
            return;
        }

        $trialWeek = TrialWeek::whereNull('acquisition_supporter_id')->find($trialWeekId);
        if (! $trialWeek) {
            session()->flash('error', 'هفته آزمایشی یافت نشد یا پشتیبان از قبل تخصیص داده شده.');
            return;
        }

        $service->assignSupporter($trialWeek, $supporter);
        unset($this->selectedSupporter[$trialWeekId]);

        session()->flash('success', 'پشتیبان جذب با موفقیت تخصیص داده شد.');
    }

    public function render()
    {
        $trials = TrialWeek::with(['user.personalInformation'])
            ->whereNull('acquisition_supporter_id')
            ->when($this->search, fn($q) =>
                $q->whereHas('user', fn($u) =>
                    $u->where('name', 'like', "%{$this->search}%")
                        ->orWhere('mobile', 'like', "%{$this->search}%")
                )
            )
            ->latest()
            ->paginate(15);

        $supporters = Admin::role('site acquisition')
            ->orderBy('name')
            ->get(['id', 'name', 'mobile']);

        return view('livewire.admin.educational-manager.new-trial-students.index', [
            'trials'     => $trials,
            'supporters' => $supporters,
        ])->layout('layouts.admin.app');
    }
}
