<?php

namespace App\Livewire\Admin\EducationalManager;

use App\Models\Admin;
use App\Models\TrialWeek;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class AssignAcquisitionSupporter extends Component
{
    use WithPagination;

    public array $assignments = []; // trialId => supporterId

    public function assign(int $trialId): void
    {
        $supporterId = (int) ($this->assignments[$trialId] ?? 0);
        if (!$supporterId) {
            return;
        }
        $trial = TrialWeek::find($trialId);
        if (!$trial) return;

        $trial->update([
            'supporter_id'          => $supporterId,
            'supporter_assigned_at' => now(),
            'status'                => TrialWeek::STATUS_SUPPORTER_ASSIGNED,
        ]);

        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'پشتیبان اختصاص داده شد.']);
    }

    #[Layout('layouts.admin.app')]
    public function render()
    {
        $trials = TrialWeek::with('user')
            ->whereNull('supporter_id')
            ->where('status', TrialWeek::STATUS_PENDING)
            ->where('is_active', true)
            ->latest()
            ->paginate(20);

        $supporters = Admin::role('acquisition_supporter')->get();

        return view('livewire.admin.educational-manager.assign-acquisition-supporter', compact('trials', 'supporters'));
    }
}
