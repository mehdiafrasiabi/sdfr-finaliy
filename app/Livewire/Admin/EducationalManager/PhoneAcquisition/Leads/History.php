<?php

namespace App\Livewire\Admin\EducationalManager\PhoneAcquisition\Leads;

use App\Models\PhoneLead;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * مدیر آموزشی — فهرست شماره‌هایی که با آن‌ها تماس گرفته شده است
 * (چه مشاوری، چند بار، با چه نتیجه‌ای).
 */
class History extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $leads = PhoneLead::query()
            ->with(['state:id,name', 'city:id,name'])
            ->whereHas('calls')
            ->when($this->search, fn ($q) => $q->where(function ($s) {
                $s->where('full_name', 'like', "%{$this->search}%")
                    ->orWhere('mobile', 'like', "%{$this->search}%");
            }))
            ->withCount('calls')
            ->withMax('calls', 'called_at')
            ->orderByDesc('calls_max_called_at')
            ->paginate(15);

        return view('livewire.admin.educational-manager.phone-acquisition.leads.history', [
            'leads' => $leads,
        ])->layout('layouts.admin.app');
    }
}
