<?php

namespace App\Livewire\Admin\Consultant;

use App\Models\EmergencyCall;
use App\Models\Student;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * ثبت «تماس اورژانسی» توسط مشاور برای دانش‌آموزانِ تحت مشاورهٔ خودش.
 * این تماس‌ها در داشبورد مدیر مدرسه برای پیگیری نمایش داده می‌شوند.
 */
class EmergencyCalls extends Component
{
    use WithPagination;

    public ?int $selectedStudentId = null;
    public string $reason = '';

    protected $paginationTheme = 'bootstrap';

    public function mount(): void
    {
        $admin = auth('admin')->user();
        abort_unless($admin?->hasRole('مشاور تحصیلی') || $admin?->hasRole('super admin'), 403);
    }

    private function advisorId(): ?int
    {
        return auth('admin')->id();
    }

    public function save(): void
    {
        Validator::make(
            ['selectedStudentId' => $this->selectedStudentId, 'reason' => trim($this->reason)],
            [
                'selectedStudentId' => 'required|integer',
                'reason'            => 'required|string|min:3|max:1000',
            ],
            [],
            ['selectedStudentId' => 'دانش‌آموز', 'reason' => 'علت تماس']
        )->validate();

        // اطمینان از اینکه دانش‌آموز تحت مشاورهٔ همین مشاور است.
        $student = Student::where('advisor_id', $this->advisorId())->findOrFail($this->selectedStudentId);

        EmergencyCall::create([
            'student_id' => $student->id,
            'admin_id'   => $this->advisorId(),
            'reason'     => trim($this->reason),
            'status'     => EmergencyCall::STATUS_PENDING,
            'called_at'  => now(),
        ]);

        $this->reset(['selectedStudentId', 'reason']);
        $this->dispatch('success', 'تماس اورژانسی ثبت شد و برای پیگیری به مدیر مدرسه ارسال شد.');
    }

    public function render()
    {
        $students = Student::with('user')
            ->where('advisor_id', $this->advisorId())
            ->get();

        $calls = EmergencyCall::with(['student.user'])
            ->whereIn('student_id', $students->pluck('id'))
            ->latest('called_at')
            ->paginate(15);

        return view('livewire.admin.consultant.emergency-calls', [
            'students' => $students,
            'calls'    => $calls,
        ])->layout('layouts.admin.app');
    }
}
