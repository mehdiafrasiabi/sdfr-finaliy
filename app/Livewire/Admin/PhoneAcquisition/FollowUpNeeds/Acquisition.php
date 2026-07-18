<?php

namespace App\Livewire\Admin\PhoneAcquisition\FollowUpNeeds;

use App\Livewire\Admin\PhoneAcquisition\Concerns\LogsPhoneCalls;
use App\Models\PhoneCall;
use App\Models\PhoneLead;
use App\Models\PhoneLeadAssignment;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Acquisition extends Component
{
    use WithPagination, LogsPhoneCalls;

    public string $search = '';
    public bool $dueOnly = false;

    protected $queryString = [
        'search' => ['except' => ''],
        'dueOnly' => ['except' => false],
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingDueOnly(): void
    {
        $this->resetPage();
    }

    protected function baseLeadQuery()
    {
        $adminId = Auth::guard('admin')->id();

        return PhoneLead::query()
            ->where('status', PhoneLead::STATUS_ACTIVE)
            ->where('last_outcome', PhoneCall::RESULT_FOLLOW_UP)
            ->whereHas('assignments', fn ($q) => $q
                ->where('admin_id', $adminId)
                ->where('status', PhoneLeadAssignment::STATUS_ACTIVE)
            )
            ->whereHas('calls', fn ($q) => $q->where('result', PhoneCall::RESULT_FOLLOW_UP));
    }

    public function render()
    {
        $query = $this->baseLeadQuery()
            ->with([
                'state:id,name',
                'city:id,name',
                'calls' => fn ($q) => $q->where('result', PhoneCall::RESULT_FOLLOW_UP)->latest('called_at'),
            ])
            ->when($this->dueOnly, fn ($q) => $q->where('next_call_at', '<=', now()))
            ->when($this->search, fn ($q) => $q->where(function ($s) {
                $s->where('full_name', 'like', "%{$this->search}%")
                    ->orWhere('mobile', 'like', "%{$this->search}%");
            }))
            ->orderByRaw('CASE WHEN next_call_at IS NULL THEN 1 ELSE 0 END')
            ->orderBy('next_call_at')
            ->latest();

        $colorCounts = $this->baseLeadQuery()
            ->get(['id', 'attempts_count', 'status'])
            ->groupBy(fn (PhoneLead $lead) => $lead->color)
            ->map(fn ($items) => $items->count())
            ->all();

        $activeLead = $this->activeLeadId ? PhoneLead::find($this->activeLeadId) : null;

        return view('livewire.admin.phone-acquisition.follow-up-needs.acquisition', [
            'leads' => $query->paginate(15),
            'activeLead' => $activeLead,
            'totalCount' => $this->baseLeadQuery()->count(),
            'dueCount' => $this->baseLeadQuery()->where('next_call_at', '<=', now())->count(),
            'colorCounts' => $colorCounts,
            'now' => now(),
        ])->layout('layouts.admin.app');
    }
}
