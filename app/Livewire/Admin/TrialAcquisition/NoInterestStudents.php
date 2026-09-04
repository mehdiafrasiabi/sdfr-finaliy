<?php

namespace App\Livewire\Admin\TrialAcquisition;

use App\Models\TrialWeek;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

abstract class NoInterestStudents extends Component
{
    use WithPagination;

    public string $search = '';

    protected $paginationTheme = 'bootstrap';

    abstract protected function status(): string;

    abstract protected function title(): string;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    protected function baseQuery(): Builder
    {
        $query = TrialWeek::query()
            ->with(['user.personalInformation', 'user.profile'])
            ->where('acq_disinterest_status', $this->status());

        if (! Auth::guard('admin')->user()?->hasRole('super admin')) {
            $query->where('acquisition_supporter_id', Auth::guard('admin')->id());
        }

        return $query;
    }

    public function render()
    {
        $students = $this->baseQuery()
            ->when(trim($this->search) !== '', function (Builder $query) {
                $term = trim($this->search);

                $query->where(function (Builder $q) use ($term) {
                    $q->whereHas('user', function (Builder $userQuery) use ($term) {
                        $userQuery->where('name', 'like', "%{$term}%")
                            ->orWhere('mobile', 'like', "%{$term}%");
                    })->orWhereHas('user.personalInformation', function (Builder $infoQuery) use ($term) {
                        $infoQuery->where('name', 'like', "%{$term}%")
                            ->orWhere('name_full', 'like', "%{$term}%")
                            ->orWhere('father_mobile', 'like', "%{$term}%")
                            ->orWhere('mother_mobile', 'like', "%{$term}%");
                    });
                });
            })
            ->latest('acq_disinterest_at')
            ->paginate(20);

        return view('livewire.admin.trial-acquisition.no-interest-students', [
            'students' => $students,
            'pageTitle' => $this->title(),
            'status' => $this->status(),
        ])->layout('layouts.admin.app');
    }
}
