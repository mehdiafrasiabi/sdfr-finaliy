<?php

namespace App\Livewire\Admin\AcquisitionSupporter\MyStudents;

use App\Models\TrialWeek;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * C-1 — لیست دانش‌آموزانی که به این پشتیبان جذب تخصیص داده شده‌اند.
 */
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    protected $queryString = ['search'];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $adminId = Auth::guard('admin')->id();

        $trials = TrialWeek::with(['user.personalInformation', 'student.advisor'])
            ->where('acquisition_supporter_id', $adminId)
            ->when($this->search, fn($q) =>
                $q->whereHas('user', fn($u) =>
                    $u->where('name', 'like', "%{$this->search}%")
                        ->orWhere('mobile', 'like', "%{$this->search}%")
                )
            )
            ->latest()
            ->paginate(15);

        return view('livewire.admin.acquisition-supporter.my-students.index', [
            'trials' => $trials,
        ])->layout('layouts.admin.app');
    }
}
