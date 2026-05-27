<?php

namespace App\Livewire\Manager\Assessment;

use App\Models\Assessment;
use App\Models\StudentAssessmentAttempt;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class StudentsAssessmentsDashboard extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = ''; // ''|'not_started'|'in_progress'|'completed'

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingStatusFilter(): void { $this->resetPage(); }

    public function render(): \Illuminate\Contracts\View\View
    {
        $totalRequired = Assessment::active()->forStudent()->count();

        $usersQuery = User::query()
            ->whereHas('trialWeek')
            ->with(['personalInformation', 'trialWeek']);

        if ($this->search !== '') {
            $usersQuery->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                    ->orWhere('mobile', 'like', "%{$this->search}%");
            });
        }

        $users = $usersQuery->orderByDesc('id')->paginate(20);

        // یک کوئری برای شمارش completed/in_progress به ازای هر کاربر
        $userIds = collect($users->items())->pluck('id')->all();
        $counts = StudentAssessmentAttempt::query()
            ->selectRaw('user_id, status, COUNT(*) as cnt')
            ->whereIn('user_id', $userIds)
            ->whereHas('assessment', fn ($q) => $q->where('is_active', true)->where('audience', Assessment::AUDIENCE_STUDENT))
            ->groupBy('user_id', 'status')
            ->get()
            ->groupBy('user_id');

        $rows = collect($users->items())->map(function ($u) use ($counts, $totalRequired) {
            $userCounts = $counts->get($u->id, collect());
            $completed = (int) ($userCounts->firstWhere('status', StudentAssessmentAttempt::STATUS_COMPLETED)?->cnt ?? 0);
            $inProgress = (int) ($userCounts->firstWhere('status', StudentAssessmentAttempt::STATUS_IN_PROGRESS)?->cnt ?? 0);

            $status = match (true) {
                $completed >= $totalRequired && $totalRequired > 0 => 'completed',
                $completed > 0 || $inProgress > 0                  => 'in_progress',
                default                                            => 'not_started',
            };

            return (object) [
                'user'       => $u,
                'completed'  => $completed,
                'inProgress' => $inProgress,
                'total'      => $totalRequired,
                'status'     => $status,
            ];
        });

        if ($this->statusFilter !== '') {
            $rows = $rows->where('status', $this->statusFilter);
        }

        return view('livewire.manager.assessment.students-assessments-dashboard', [
            'rows'          => $rows,
            'paginator'     => $users,
            'totalRequired' => $totalRequired,
        ])->layout('layouts.manager.app');
    }
}
