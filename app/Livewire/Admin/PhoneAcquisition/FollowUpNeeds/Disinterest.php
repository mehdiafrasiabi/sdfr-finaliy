<?php

namespace App\Livewire\Admin\PhoneAcquisition\FollowUpNeeds;

use App\Livewire\Admin\PhoneAcquisition\Concerns\LogsPhoneCalls;
use App\Models\PhoneCall;
use App\Models\PhoneLead;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Disinterest extends Component
{
    use WithPagination, LogsPhoneCalls;

    public string $status = PhoneCall::DISINTEREST_TEMPORARY;
    public string $search = '';

    protected $queryString = [
        'search' => ['except' => ''],
    ];

    public function mount(string $status = PhoneCall::DISINTEREST_TEMPORARY): void
    {
        $this->status = in_array($status, [PhoneCall::DISINTEREST_TEMPORARY, PhoneCall::DISINTEREST_DEFINITIVE], true)
            ? $status
            : PhoneCall::DISINTEREST_TEMPORARY;
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    protected function baseLeadQuery()
    {
        $adminId = Auth::guard('admin')->id();

        return PhoneLead::query()
            ->where('last_outcome', PhoneCall::RESULT_NO_INTEREST)
            ->where('disinterest_status', $this->status)
            ->whereHas('assignments', fn ($q) => $q->where('admin_id', $adminId));
    }

    public function render()
    {
        $baseQuery = $this->baseLeadQuery();
        $leads = $this->baseLeadQuery()
            ->with([
                'state:id,name',
                'city:id,name',
                'calls' => fn ($q) => $q
                    ->where('result', PhoneCall::RESULT_NO_INTEREST)
                    ->with('admin:id,name')
                    ->reorder()
                    ->latest('called_at'),
            ])
            ->when($this->search, fn ($q) => $q->where(function ($searchQuery) {
                $searchQuery->where('full_name', 'like', "%{$this->search}%")
                    ->orWhere('mobile', 'like', "%{$this->search}%");
            }))
            ->orderByRaw('CASE WHEN disinterest_at IS NULL THEN 1 ELSE 0 END')
            ->latest('disinterest_at')
            ->latest()
            ->paginate(15);

        return view('livewire.admin.phone-acquisition.follow-up-needs.disinterest', [
            'leads' => $leads,
            'activeLead' => $this->activeLeadId ? PhoneLead::find($this->activeLeadId) : null,
            'totalCount' => (clone $baseQuery)->count(),
            'dueCount' => $this->status === PhoneCall::DISINTEREST_TEMPORARY
                ? (clone $baseQuery)->whereNotNull('next_call_at')->where('next_call_at', '<=', now())->count()
                : 0,
            'lockedCount' => $this->status === PhoneCall::DISINTEREST_TEMPORARY
                ? (clone $baseQuery)->where('next_call_at', '>', now())->count()
                : 0,
            'isTemporary' => $this->status === PhoneCall::DISINTEREST_TEMPORARY,
            'pageTitle' => $this->status === PhoneCall::DISINTEREST_TEMPORARY ? 'عدم تمایل موقت' : 'عدم تمایل قطعی',
            'now' => now(),
        ])->layout('layouts.admin.app');
    }
}
