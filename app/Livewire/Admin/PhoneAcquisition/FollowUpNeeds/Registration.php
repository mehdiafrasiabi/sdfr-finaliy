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

    protected function baseLinkQuery()
    {
        $adminId = Auth::guard('admin')->id();

        return PhoneRegistrationLink::query()
            ->where('admin_id', $adminId)
            ->where(function ($query) use ($adminId) {
                $query->where(function ($pendingQuery) use ($adminId) {
                    $pendingQuery->whereNull('registered_user_id')
                        ->whereHas('lead', fn ($leadQuery) => $leadQuery
                            ->whereHas('assignments', fn ($assignmentQuery) => $assignmentQuery
                                ->where('admin_id', $adminId)
                                ->where('status', PhoneLeadAssignment::STATUS_ACTIVE)
                            )
                        );
                })->orWhere(function ($registeredTodayQuery) use ($adminId) {
                    $registeredTodayQuery->whereNotNull('registered_user_id')
                        ->whereDate('used_at', now()->toDateString())
                        ->whereHas('lead.assignments', fn ($assignmentQuery) => $assignmentQuery
                            ->where('admin_id', $adminId)
                        );
                });
            })
            ->whereNotExists(function ($query) {
                $query->selectRaw('1')
                    ->from('phone_registration_links as newer_links')
                    ->whereColumn('newer_links.phone_lead_id', 'phone_registration_links.phone_lead_id')
                    ->whereColumn('newer_links.admin_id', 'phone_registration_links.admin_id')
                    ->whereColumn('newer_links.id', '>', 'phone_registration_links.id');
            });
    }

    public function render()
    {
        $links = $this->baseLinkQuery()
            ->with([
                'lead.state:id,name',
                'lead.city:id,name',
                'lead.calls' => fn ($q) => $q->reorder()->latest('called_at'),
            ])
            ->when($this->search, fn ($q) => $q->where(function ($s) {
                $s->where('mobile', 'like', "%{$this->search}%")
                    ->orWhereHas('lead', fn ($leadQuery) => $leadQuery
                        ->where('full_name', 'like', "%{$this->search}%")
                        ->orWhere('mobile', 'like', "%{$this->search}%")
                    );
            }))
            ->latest()
            ->paginate(15);

        $activeLead = $this->activeLeadId ? PhoneLead::find($this->activeLeadId) : null;

        return view('livewire.admin.phone-acquisition.follow-up-needs.registration', [
            'links' => $links,
            'activeLead' => $activeLead,
            'totalCount' => $this->baseLinkQuery()->count(),
            'successfulTodayCount' => PhoneRegistrationLink::query()
                ->where('admin_id', Auth::guard('admin')->id())
                ->whereNotNull('registered_user_id')
                ->whereDate('used_at', now()->toDateString())
                ->distinct('registered_user_id')
                ->count('registered_user_id'),
            'now' => now(),
        ])->layout('layouts.admin.app');
    }

    protected function phoneCallResultValues(): array
    {
        return [
            PhoneCall::RESULT_FOLLOW_UP,
            PhoneCall::RESULT_NO_INTEREST,
        ];
    }

    protected function followUpResultForContext(): string
    {
        return PhoneCall::RESULT_REGISTRATION_FOLLOW_UP;
    }
}
