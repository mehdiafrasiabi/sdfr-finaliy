<?php

namespace App\Livewire\Admin\AcquisitionSupporter;

use App\Models\TrialWeek;
use App\Services\AcquisitionCallService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class Dashboard extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    #[Layout('layouts.admin.app')]
    public function render()
    {
        $admin = Auth::guard('admin')->user();

        $trials = TrialWeek::query()
            ->where('supporter_id', $admin?->id)
            ->with(['user', 'user.personalInformation', 'acquisitionCalls'])
            ->when($this->search !== '', function ($q) {
                $q->whereHas('user', function ($q2) {
                    $q2->where('name', 'like', "%{$this->search}%")
                       ->orWhere('mobile', 'like', "%{$this->search}%");
                });
            })
            ->latest('supporter_assigned_at')
            ->paginate(20);

        $service = app(AcquisitionCallService::class);
        $meta = [];
        foreach ($trials as $t) {
            $meta[$t->id] = [
                'next' => $service->nextCallType($t),
                'inactive' => $service->isStudentInactive($t),
            ];
        }

        return view('livewire.admin.acquisition-supporter.dashboard', compact('trials', 'meta'));
    }
}
