<?php

namespace App\Livewire\Admin\PhoneAcquisition\FollowUpNeeds;

use App\Livewire\Admin\PhoneAcquisition\Concerns\LogsPhoneCalls;
use App\Models\PhoneCall;
use App\Models\PhoneLead;
use App\Models\PhoneLeadAssignment;
use App\Models\PhoneRegistrationLink;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class NoRegistration extends Component
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

    protected function baseLinkQuery()
    {
        $adminId = Auth::guard('admin')->id();

        return PhoneRegistrationLink::query()
            ->where('admin_id', $adminId)
            ->whereNull('registered_user_id')
            ->whereDate('created_at', '<', now()->toDateString())
            ->whereHas('lead', fn ($leadQuery) => $leadQuery
                ->where('status', PhoneLead::STATUS_ACTIVE)
                ->where('last_outcome', PhoneCall::RESULT_REGISTERED)
                ->whereHas('assignments', fn ($assignmentQuery) => $assignmentQuery
                    ->where('admin_id', $adminId)
                    ->where('status', PhoneLeadAssignment::STATUS_ACTIVE)
                )
            )
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('phone_registration_links as newer_links')
                    ->whereColumn('newer_links.phone_lead_id', 'phone_registration_links.phone_lead_id')
                    ->whereNull('newer_links.registered_user_id')
                    ->whereDate('newer_links.created_at', '>=', now()->toDateString());
            });
    }

    public function render()
    {
        $links = $this->baseLinkQuery()
            ->with([
                'lead.state:id,name',
                'lead.city:id,name',
                'lead.calls' => fn ($q) => $q->latest('called_at'),
            ])
            ->when($this->search, fn ($q) => $q->where(function ($searchQuery) {
                $searchQuery->where('mobile', 'like', "%{$this->search}%")
                    ->orWhereHas('lead', fn ($leadQuery) => $leadQuery
                        ->where('full_name', 'like', "%{$this->search}%")
                        ->orWhere('mobile', 'like', "%{$this->search}%")
                    );
            }))
            ->oldest()
            ->paginate(15);

        return view('livewire.admin.phone-acquisition.follow-up-needs.no-registration', [
            'links' => $links,
            'activeLead' => $this->activeLeadId ? PhoneLead::find($this->activeLeadId) : null,
            'totalCount' => $this->baseLinkQuery()->count(),
            'now' => now(),
        ])->layout('layouts.admin.app');
    }
}
