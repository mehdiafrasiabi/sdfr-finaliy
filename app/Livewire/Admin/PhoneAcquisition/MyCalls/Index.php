<?php

namespace App\Livewire\Admin\PhoneAcquisition\MyCalls;

use App\Models\PhoneCall;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * تماس‌های من — فهرست تختِ همهٔ تماس‌های ثبت‌شده توسط مشاور جذب تلفنی،
 * با نام/شمارهٔ دانش‌آموز، رنگ تماس، تاریخ و ساعت.
 */
class Index extends Component
{
    use WithPagination;

    public string $search  = '';
    public string $outcome = 'all'; // all | connected | no_answer | off | rejected | wrong

    protected $queryString = ['search', 'outcome'];

    public function updatingSearch(): void  { $this->resetPage(); }
    public function updatingOutcome(): void { $this->resetPage(); }

    public function render()
    {
        $adminId = Auth::guard('admin')->id();

        $calls = PhoneCall::query()
            ->with(['lead:id,full_name,mobile,grade,field'])
            ->where('admin_id', $adminId)
            ->when($this->outcome === 'connected', fn ($q) => $q->where('connected', true))
            ->when(
                in_array($this->outcome, [PhoneCall::FAIL_NO_ANSWER, PhoneCall::FAIL_OFF, PhoneCall::FAIL_REJECTED, PhoneCall::FAIL_WRONG], true),
                fn ($q) => $q->where('connected', false)->where('fail_reason', $this->outcome)
            )
            ->when($this->search, fn ($q) =>
                $q->whereHas('lead', fn ($l) =>
                    $l->where('full_name', 'like', "%{$this->search}%")
                      ->orWhere('mobile', 'like', "%{$this->search}%")
                )
            )
            ->latest('called_at')
            ->paginate(20);

        return view('livewire.admin.phone-acquisition.my-calls.index', [
            'calls' => $calls,
        ])->layout('layouts.admin.app');
    }
}
