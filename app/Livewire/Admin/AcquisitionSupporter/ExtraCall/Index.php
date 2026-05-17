<?php

namespace App\Livewire\Admin\AcquisitionSupporter\ExtraCall;

use App\Models\AcquisitionContact;
use App\Models\TrialWeek;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * C-4 — تماس اکسترا: تماس‌های متفرقه (اطلاع‌رسانی، پیگیری، تماس با والدین).
 */
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $activeTrialId = null;

    public string $contactNotes        = '';
    public string $educationalFollowUp = '';

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function openForm(int $trialId): void
    {
        $this->activeTrialId       = $trialId;
        $this->contactNotes        = '';
        $this->educationalFollowUp = '';
        $this->resetErrorBag();
    }

    public function closeForm(): void
    {
        $this->activeTrialId       = null;
        $this->contactNotes        = '';
        $this->educationalFollowUp = '';
        $this->resetErrorBag();
    }

    public function save(): void
    {
        $this->validate([
            'activeTrialId'       => ['required', 'integer'],
            'contactNotes'        => ['required', 'string', 'max:5000'],
            'educationalFollowUp' => ['nullable', 'in:father,mother,student,other'],
        ], [
            'contactNotes.required' => 'توضیحات تماس الزامی است.',
        ]);

        $trial = TrialWeek::where('acquisition_supporter_id', Auth::guard('admin')->id())
            ->find($this->activeTrialId);

        if (! $trial) {
            session()->flash('error', 'دانش‌آموز یافت نشد.');
            $this->closeForm();
            return;
        }

        AcquisitionContact::create([
            'trial_week_id'         => $trial->id,
            'admin_id'              => Auth::guard('admin')->id(),
            'type'                  => AcquisitionContact::TYPE_SUPPLEMENTARY,
            'answered'              => true,
            'notes'                 => $this->contactNotes,
            'educational_follow_up' => $this->educationalFollowUp ?: null,
            'contacted_at'          => now(),
        ]);

        session()->flash('success', 'تماس اکسترا با موفقیت ثبت شد.');
        $this->closeForm();
    }

    public function render()
    {
        $adminId = Auth::guard('admin')->id();

        $trials = TrialWeek::with(['user.personalInformation'])
            ->where('acquisition_supporter_id', $adminId)
            ->when($this->search, fn($q) =>
                $q->whereHas('user', fn($u) =>
                    $u->where('name', 'like', "%{$this->search}%")
                        ->orWhere('mobile', 'like', "%{$this->search}%")
                )
            )
            ->withCount([
                'acquisitionContacts as extra_count' => function ($q) {
                    $q->where('type', AcquisitionContact::TYPE_SUPPLEMENTARY);
                },
            ])
            ->latest()
            ->paginate(15);

        return view('livewire.admin.acquisition-supporter.extra-call.index', [
            'trials' => $trials,
        ])->layout('layouts.admin.app');
    }
}
