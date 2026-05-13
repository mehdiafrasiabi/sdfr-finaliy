<?php

namespace App\Livewire\Manager\Enrollment;

use App\Models\Admin;
use App\Models\Enrollment;
use App\Services\EnrollmentService;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $assigningEnrollmentId = null;
    public ?int $supporterId = null;

    public function openAssign(int $enrollmentId): void
    {
        $this->assigningEnrollmentId = $enrollmentId;
        $this->supporterId = null;
    }

    public function closeAssign(): void
    {
        $this->assigningEnrollmentId = null;
        $this->supporterId = null;
    }

    public function assign(EnrollmentService $service): void
    {
        $this->validate([
            'assigningEnrollmentId' => ['required', 'exists:enrollments,id'],
            'supporterId'           => ['required', 'exists:admins,id'],
        ], [], [
            'supporterId' => 'پشتیبان',
        ]);

        $enrollment = Enrollment::findOrFail($this->assigningEnrollmentId);
        $supporter  = Admin::findOrFail($this->supporterId);

        $service->assignSupporter($enrollment, $supporter);

        $this->dispatch('success', 'پشتیبان با موفقیت تخصیص یافت.');
        $this->closeAssign();
    }

    public function render()
    {
        $query = Enrollment::with(['user', 'ccGrade', 'student'])
            ->where('status', Enrollment::STATUS_PAID)
            ->whereNull('supporter_id')
            ->latest('paid_at');

        if ($this->search !== '') {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('mobile', 'like', '%' . $this->search . '%');
            });
        }

        $enrollments = $query->paginate(15);

        $supporters = Admin::role('academic support')->get();

        return view('livewire.manager.enrollment.index', [
            'enrollments' => $enrollments,
            'supporters'  => $supporters,
        ])->layout('layouts.manager.app');
    }
}
