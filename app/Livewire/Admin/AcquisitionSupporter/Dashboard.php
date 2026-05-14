<?php

namespace App\Livewire\Admin\AcquisitionSupporter;

use App\Models\AcquisitionContact;
use App\Models\TrialWeek;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $statusFilter = 'all';
    public bool   $inactiveOnly = false;

    protected $queryString = ['search', 'statusFilter'];

    public function updatingSearch(): void  { $this->resetPage(); }
    public function updatingStatusFilter(): void { $this->resetPage(); }
    public function updatingInactiveOnly(): void { $this->resetPage(); }

    public function render(): \Illuminate\Contracts\View\View
    {
        $adminId = Auth::guard('admin')->id();

        $query = TrialWeek::with([
            'user.personalInformation',
            'acquisitionContacts' => fn($q) => $q->latest('contacted_at')->limit(3),
        ])
            ->where('supporter_id', $adminId)
            ->when($this->search, fn($q) =>
                $q->whereHas('user', fn($u) =>
                    $u->where('name', 'like', "%{$this->search}%")
                      ->orWhere('mobile', 'like', "%{$this->search}%")
                )
            )
            ->when($this->statusFilter !== 'all', fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->inactiveOnly, fn($q) =>
                // دانش‌آموزانی که در ۳ روز گذشته هیچ گزارشی ثبت نکرده‌اند
                $q->whereHas('student', fn($s) =>
                    $s->whereDoesntHave('reportdaily', fn($r) =>
                        $r->where('created_at', '>=', now()->subDays(3))
                    )
                )
            )
            ->latest();

        return view('livewire.admin.acquisition-supporter.dashboard', [
            'trials' => $query->paginate(15),
        ])->layout('layouts.admin.app');
    }
}
