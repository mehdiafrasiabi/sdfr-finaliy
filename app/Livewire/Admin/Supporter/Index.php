<?php

namespace App\Livewire\Admin\Supporter;

use App\Models\Admin;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $supporters = Admin::query()
            ->role('acquisition_supporter')
            ->when($this->search !== '', function ($q) {
                $q->where(function ($q2) {
                    $q2->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('mobile', 'like', "%{$this->search}%");
                });
            })
            ->with(['roles.permissions', 'workSchedules', 'supportedStudents'])
            ->withCount('supportedStudents')
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.admin.supporter.index', compact('supporters'))
            ->layout('layouts.admin.app');
    }
}
