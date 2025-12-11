<?php

namespace App\Livewire\Manager\Advisors;

use App\Exports\AdvisorsExport;
use App\Models\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public function export()
    {
        return Excel::download(new AdvisorsExport(), 'advisors.xlsx');
    }

    public function render()
    {
        $advisors = Admin::role('academic_advisor')
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('email', 'like', '%' . $this->search . '%')
                    ->orWhere('mobile', 'like', '%' . $this->search . '%');
            })
            ->withCount('advisedStudents')
            ->paginate(10);

        return view('livewire.manager.advisors.index', [
            'advisors' => $advisors,
        ])->layout('layouts.manager.app');
    }
}
