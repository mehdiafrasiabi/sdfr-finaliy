<?php

namespace App\Livewire\Admin\PhoneAcquisition\FollowUpNeeds;

use App\Livewire\Admin\PhoneAcquisition\Concerns\LogsPhoneCalls;
use App\Models\PhoneCall;
use App\Models\PhoneLead;
use App\Models\PhoneLeadAssignment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Registration extends Component
{
    use WithPagination, LogsPhoneCalls;

    public string $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    protected function baseLeadQuery()
    {
        $adminId = Auth::guard('admin')->id();

        return PhoneLead::query()
            ->where('status', PhoneLead::STATUS_ACTIVE)
            ->where('last_outcome', PhoneCall::RESULT_REGISTRATION_FOLLOW_UP)
            ->whereHas('assignments', fn ($q) => $q
                ->where('admin_id', $adminId)
                ->where('status', PhoneLeadAssignment::STATUS_ACTIVE)
            )
            ->whereHas('calls', fn ($q) => $q->where('result', PhoneCall::RESULT_REGISTRATION_FOLLOW_UP));
    }

    public function render()
    {
        $leads = $this->baseLeadQuery()
            ->with([
                'state:id,name',
                'city:id,name',
                'calls' => fn ($q) => $q->where('result', PhoneCall::RESULT_REGISTRATION_FOLLOW_UP)->latest('called_at'),
            ])
            ->when($this->search, fn ($q) => $q->where(function ($s) {
                $s->where('full_name', 'like', "%{$this->search}%")
                    ->orWhere('mobile', 'like', "%{$this->search}%");
            }))
            ->orderByRaw('CASE WHEN next_call_at IS NULL THEN 1 ELSE 0 END')
            ->orderBy('next_call_at')
            ->latest()
            ->paginate(15);

        $activeLead = $this->activeLeadId ? PhoneLead::find($this->activeLeadId) : null;
        $dueCount = $this->baseLeadQuery()
            ->where('next_call_at', '<=', now())
            ->count();

        return view('livewire.admin.phone-acquisition.follow-up-needs.registration', [
            'leads' => $leads,
            'activeLead' => $activeLead,
            'totalCount' => $this->baseLeadQuery()->count(),
            'dueCount' => $dueCount,
            'now' => now(),
        ])->layout('layouts.admin.app');
    }
}
