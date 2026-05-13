<?php

namespace App\Livewire\Admin\Consultant;

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
        $consultants = Admin::query()
            ->role('academic_advisor')
            ->when($this->search !== '', function ($q) {
                $q->where(function ($q2) {
                    $q2->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('mobile', 'like', "%{$this->search}%");
                });
            })
            ->with(['roles.permissions', 'permissions', 'workSchedules'])
            ->orderBy('name')
            ->paginate(15);

        return view('livewire.admin.consultant.index', compact('consultants'))
            ->layout('layouts.admin.app');
    }
}
