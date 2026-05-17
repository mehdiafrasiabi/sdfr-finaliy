<?php

namespace App\Livewire\Admin\AcquisitionSupporter\PrimaryCall;

use App\Models\AcquisitionContact;
use App\Models\TrialWeek;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * C-2 — تماس اولیه: دانش‌آموزان جدیدی که هنوز تماس اولیهٔ موفق نداشته‌اند.
 *
 * نتایج تماس:
 *   - گرفته شد (answered=true) ← دانش‌آموز از این لیست خارج می‌شود و ۲ روز
 *     بعد به‌صورت خودکار در صفحهٔ تماس ثانویه نمایش داده می‌شود.
 *   - پاسخ نداد (answered=false) ← شمارش پاسخ‌نگرفته‌ها افزایش می‌یابد.
 */
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    /** ID هفته آزمایشی که در حال ثبت نتیجه تماس برای آن هستیم. */
    public ?int $activeTrialId = null;

    public string $educationalFollowUp = '';
    public string $contactNotes        = '';

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openSuccessForm(int $trialId): void
    {
        $this->activeTrialId       = $trialId;
        $this->educationalFollowUp = '';
        $this->contactNotes        = '';
        $this->resetErrorBag();
    }

    public function closeForm(): void
    {
        $this->activeTrialId       = null;
        $this->educationalFollowUp = '';
        $this->contactNotes        = '';
        $this->resetErrorBag();
    }

    /**
     * ثبت «گرفته شد» — تماس موفق.
     */
    public function markAnswered(): void
    {
        $this->validate([
            'activeTrialId'       => ['required', 'integer'],
            'educationalFollowUp' => ['required', 'in:father,mother,student,other'],
            'contactNotes'        => ['nullable', 'string', 'max:2000'],
        ], [
            'educationalFollowUp.required' => 'انتخاب «پیگیر آموزشی» الزامی است.',
            'educationalFollowUp.in'       => 'مقدار «پیگیر آموزشی» نامعتبر است.',
        ]);

        $trial = $this->loadTrialForSupporter($this->activeTrialId);
        if (! $trial) {
            session()->flash('error', 'دانش‌آموز یافت نشد.');
            $this->closeForm();
            return;
        }

        DB::transaction(function () use ($trial) {
            AcquisitionContact::create([
                'trial_week_id'         => $trial->id,
                'admin_id'              => Auth::guard('admin')->id(),
                'type'                  => AcquisitionContact::TYPE_INITIAL,
                'answered'              => true,
                'notes'                 => $this->contactNotes ?: null,
                'educational_follow_up' => $this->educationalFollowUp,
                'contacted_at'          => now(),
            ]);
        });

        session()->flash('success', 'تماس اولیه با موفقیت ثبت شد.');
        $this->closeForm();
    }

    /**
     * ثبت «پاسخ نداد».
     */
    public function markUnanswered(int $trialId): void
    {
        $trial = $this->loadTrialForSupporter($trialId);
        if (! $trial) {
            session()->flash('error', 'دانش‌آموز یافت نشد.');
            return;
        }

        AcquisitionContact::create([
            'trial_week_id' => $trial->id,
            'admin_id'      => Auth::guard('admin')->id(),
            'type'          => AcquisitionContact::TYPE_INITIAL,
            'answered'      => false,
            'contacted_at'  => now(),
        ]);

        session()->flash('success', 'عدم پاسخ ثبت شد.');
    }

    protected function loadTrialForSupporter(?int $trialId): ?TrialWeek
    {
        if (! $trialId) {
            return null;
        }
        return TrialWeek::where('acquisition_supporter_id', Auth::guard('admin')->id())
            ->find($trialId);
    }

    public function render()
    {
        $adminId = Auth::guard('admin')->id();

        // هفته‌های آزمایشی که هنوز تماس اولیه موفق نداشته‌اند
        $trials = TrialWeek::with(['user.personalInformation'])
            ->where('acquisition_supporter_id', $adminId)
            ->whereDoesntHave('acquisitionContacts', function ($q) {
                $q->where('type', AcquisitionContact::TYPE_INITIAL)
                    ->where('answered', true);
            })
            ->when($this->search, fn($q) =>
                $q->whereHas('user', fn($u) =>
                    $u->where('name', 'like', "%{$this->search}%")
                        ->orWhere('mobile', 'like', "%{$this->search}%")
                )
            )
            ->withCount([
                'acquisitionContacts as unanswered_count' => function ($q) {
                    $q->where('type', AcquisitionContact::TYPE_INITIAL)
                        ->where('answered', false);
                },
            ])
            ->latest()
            ->paginate(15);

        return view('livewire.admin.acquisition-supporter.primary-call.index', [
            'trials' => $trials,
        ])->layout('layouts.admin.app');
    }
}
