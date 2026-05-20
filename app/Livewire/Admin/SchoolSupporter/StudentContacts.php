<?php

namespace App\Livewire\Admin\SchoolSupporter;

use App\Models\School;
use App\Models\SchoolParentContact;
use App\Models\Student;
use Hekmatinasser\Verta\Verta;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class StudentContacts extends Component
{
    use WithPagination;

    public School $school;
    public Student $student;

    public string $contacted_with = 'father';
    public string $notes = '';

    public function mount(School $school, Student $student): void
    {
        $admin = auth('admin')->user();
        if (!$admin->hasRole('super admin')) {
            abort_unless($admin->supportedSchools()->where('schools.id', $school->id)->exists(), 403);
        }
        abort_unless($student->school_id === $school->id, 404);

        $this->school = $school;
        $this->student = $student;
    }

    public function save(): void
    {
        Validator::make([
            'contacted_with' => $this->contacted_with,
            'notes'          => $this->notes,
        ], [
            'contacted_with' => 'required|in:father,mother',
            'notes'          => 'nullable|string|max:2000',
        ])->validate();

        SchoolParentContact::create([
            'student_id'     => $this->student->id,
            'admin_id'       => auth('admin')->id(),
            'contacted_with' => $this->contacted_with,
            'notes'          => $this->notes ?: null,
            'contacted_at'   => now(),
        ]);

        $this->reset(['notes']);
        $this->contacted_with = 'father';
        $this->dispatch('success', 'تماس ثبت شد');
    }

    public function render()
    {
        $contacts = SchoolParentContact::where('student_id', $this->student->id)
            ->with('admin')
            ->latest('contacted_at')
            ->paginate(15);

        $v = Verta::now();
        $year = $v->year;
        $month = $v->month;
        $jStart = Verta::parse(sprintf('%04d-%02d-01 00:00:00', $year, $month))->datetime();
        $jEnd   = (clone $jStart)->modify('+1 month -1 second');

        $monthCount = SchoolParentContact::where('student_id', $this->student->id)
            ->whereBetween('contacted_at', [$jStart, $jEnd])
            ->count();

        return view('livewire.admin.school-supporter.student-contacts', [
            'contacts'   => $contacts,
            'monthCount' => $monthCount,
        ])->layout('layouts.admin.app');
    }
}
