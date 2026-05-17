<?php

namespace App\Livewire\Admin\AcquisitionSupporter\SecondaryCall;

use App\Models\AcquisitionContact;
use App\Models\TrialWeek;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * C-3 — تماس ثانویه: دو روز پس از تماس اولیهٔ موفق، دانش‌آموز در این لیست
 * ظاهر می‌شود تا تماس ثانویه گرفته شود.
 *
 * در حالت موفق:
 *   - درصد احتمال ثبت‌نام (۰ تا ۱۰۰) الزامی است.
 *   - توضیحات الزامی است.
 */
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $activeTrialId = null;

    public ?int   $predictionPct  = null;
    public string $contactNotes   = '';
    public string $attractionPlan = '';

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openSuccessForm(int $trialId): void
    {
        $this->activeTrialId  = $trialId;
        $this->predictionPct  = null;
        $this->contactNotes   = '';
        $this->attractionPlan = '';
        $this->resetErrorBag();
    }

    public function closeForm(): void
    {
        $this->activeTrialId  = null;
        $this->predictionPct  = null;
        $this->contactNotes   = '';
        $this->attractionPlan = '';
        $this->resetErrorBag();
    }

    public function markAnswered(): void
    {
        $this->validate([
            'activeTrialId'  => ['required', 'integer'],
            'predictionPct'  => ['required', 'integer', 'min:0', 'max:100'],
            'contactNotes'   => ['required', 'string', 'max:5000'],
            'attractionPlan' => ['nullable', 'string', 'max:5000'],
        ], [
            'predictionPct.required' => 'درصد احتمال ثبت‌نام الزامی است.',
            'predictionPct.min'      => 'درصد نمی‌تواند کمتر از ۰ باشد.',
            'predictionPct.max'      => 'درصد نمی‌تواند بیشتر از ۱۰۰ باشد.',
            'contactNotes.required'  => 'توضیحات الزامی است.',
        ]);

        $trial = $this->loadTrialForSupporter($this->activeTrialId);
        if (! $trial) {
            session()->flash('error', 'دانش‌آموز یافت نشد.');
            $this->closeForm();
            return;
        }

        // ابتدا تماس اولیه باید موفق ثبت شده باشد
        $hasInitial = $trial->acquisitionContacts()
            ->where('type', AcquisitionContact::TYPE_INITIAL)
            ->where('answered', true)
            ->exists();

        if (! $hasInitial) {
            session()->flash('error', 'ابتدا تماس اولیه باید ثبت شود.');
            $this->closeForm();
            return;
        }

        AcquisitionContact::create([
            'trial_week_id'         => $trial->id,
            'admin_id'              => Auth::guard('admin')->id(),
            'type'                  => AcquisitionContact::TYPE_SECONDARY,
            'answered'              => true,
            'notes'                 => $this->contactNotes,
            'prediction_percentage' => $this->predictionPct,
            'attraction_plan'       => $this->attractionPlan ?: null,
            'contacted_at'          => now(),
        ]);

        session()->flash('success', 'تماس ثانویه با موفقیت ثبت شد.');
        $this->closeForm();
    }

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
            'type'          => AcquisitionContact::TYPE_SECONDARY,
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
        $cutoff  = Carbon::now()->subDays(2);

        // هفته‌های آزمایشی که:
        //   - تماس اولیهٔ موفق آن‌ها ≥ ۲ روز پیش بوده است،
        //   - و هنوز تماس ثانویهٔ موفق ثبت نشده است.
        $trials = TrialWeek::with(['user.personalInformation', 'acquisitionContacts'])
            ->where('acquisition_supporter_id', $adminId)
            ->whereHas('acquisitionContacts', function ($q) use ($cutoff) {
                $q->where('type', AcquisitionContact::TYPE_INITIAL)
                    ->where('answered', true)
                    ->where('contacted_at', '<=', $cutoff);
            })
            ->whereDoesntHave('acquisitionContacts', function ($q) {
                $q->where('type', AcquisitionContact::TYPE_SECONDARY)
                    ->where('answered', true);
            })
            ->when($this->search, fn($q) =>
                $q->whereHas('user', fn($u) =>
                    $u->where('name', 'like', "%{$this->search}%")
                        ->orWhere('mobile', 'like', "%{$this->search}%")
                )
            )
            ->withCount([
                'acquisitionContacts as secondary_unanswered_count' => function ($q) {
                    $q->where('type', AcquisitionContact::TYPE_SECONDARY)
                        ->where('answered', false);
                },
            ])
            ->latest()
            ->paginate(15);

        return view('livewire.admin.acquisition-supporter.secondary-call.index', [
            'trials' => $trials,
        ])->layout('layouts.admin.app');
    }
}
