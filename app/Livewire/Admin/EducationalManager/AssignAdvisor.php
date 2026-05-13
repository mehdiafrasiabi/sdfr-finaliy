<?php

namespace App\Livewire\Admin\EducationalManager;

use App\Models\Admin;
use App\Models\Student;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

class AssignAdvisor extends Component
{
    use WithPagination;

    public array $assignments = [];

    public function assign(int $studentId): void
    {
        $advisorId = (int) ($this->assignments[$studentId] ?? 0);
        if (!$advisorId) return;

        $student = Student::find($studentId);
        if (!$student) return;

        $student->update(['advisor_id' => $advisorId]);
        $this->dispatch('show-toast', ['type' => 'success', 'message' => 'مشاور اختصاص داده شد.']);
    }

    #[Layout('layouts.admin.app')]
    public function render()
    {
        $students = Student::with(['user', 'payment'])
            ->whereNull('advisor_id')
            ->whereHas('payment', fn ($q) => $q->where('status', 'completed'))
            ->latest()
            ->paginate(20);

        $advisors = Admin::role('advisor')->get();

        return view('livewire.admin.educational-manager.assign-advisor', compact('students', 'advisors'));
    }
}
