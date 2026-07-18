<?php

namespace App\Livewire\Admin\TrialAcquisition;

use App\Models\TrialWeek;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class MyStudents extends Component
{
    use WithPagination;

    public string $search = '';

    protected $paginationTheme = 'bootstrap';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $adminId = Auth::guard('admin')->id();

        $students = TrialWeek::query()
            ->with(['user.personalInformation'])
            ->where('acquisition_supporter_id', $adminId)
            ->when($this->search, function ($query) {
                $term = trim($this->search);

                $query->where(function ($q) use ($term) {
                    $q->whereHas('user', function ($userQuery) use ($term) {
                        $userQuery->where('name', 'like', "%{$term}%")
                            ->orWhere('mobile', 'like', "%{$term}%");
                    })->orWhereHas('user.personalInformation', function ($infoQuery) use ($term) {
                        $infoQuery->where('name', 'like', "%{$term}%")
                            ->orWhere('name_full', 'like', "%{$term}%")
                            ->orWhere('father_mobile', 'like', "%{$term}%")
                            ->orWhere('mother_mobile', 'like', "%{$term}%");
                    });
                });
            })
            ->latest()
            ->paginate(20);

        return view('livewire.admin.trial-acquisition.my-students', [
            'students' => $students,
        ])->layout('layouts.admin.app');
    }
}
