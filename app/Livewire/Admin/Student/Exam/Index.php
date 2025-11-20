<?php

namespace App\Livewire\Admin\Student\Exam;

use App\Models\Exam;
use App\Models\ExamAnalyses;
use App\Models\ExamAttemp;
use App\Models\Student;
use App\Models\StudentAnswer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public ?Exam $selectedExam = null;
    public array $assignedStudents = [];
    public bool $showStudentModal = false;
    public string $search = '';
    public string $levelFilter = 'all';
    public string $studentSearch = '';

    public array $levelOptions = [
        'easy' => 'آسان',
        'medium' => 'متوسط',
        'hard' => 'سخت',
        'comprehensive' => 'جامع',
    ];

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingLevelFilter(): void
    {
        $this->resetPage();
    }

    public function updatingStudentSearch(): void
    {
        $this->resetPage('studentsPage');
    }

    // متد برای فعال‌سازی یک آزمون
    public function activateExam(Exam $exam)
    {
        if ($exam->is_active) {
            session()->flash('error', 'این آزمون قبلا فعال شده است.');
            return;
        }

        $exam->update(['is_active' => true]);
        session()->flash('success', 'آزمون با موفقیت فعال شد.');
    }

    // متد باز کردن Modal برای اختصاص دانش‌آموزان
    public function openAssignStudentsModal(Exam $exam)
    {
        $this->selectedExam = $exam;
        $this->showStudentModal = true;

        // این بخش بسیار مهم است. ID دانش‌آموزان اختصاص داده شده را بارگذاری می‌کند.
        $this->assignedStudents = $exam->students->pluck('id')->toArray();
        $this->studentSearch = '';
        $this->resetPage('studentsPage');
    }

    // متد برای بستن Modal
    public function closeAssignStudentsModal()
    {
        $this->showStudentModal = false;
        $this->selectedExam = null;
        $this->reset(['assignedStudents', 'studentSearch']);
        $this->resetPage('studentsPage');
    }

    // متد برای اختصاص دانش‌آموزان به آزمون
    public function assignStudents()
    {
        $this->validate([
            'assignedStudents' => 'required|array|min:1'
        ], [
            'assignedStudents.required' => 'لطفاً حداقل یک دانش‌آموز را انتخاب کنید.',
            'assignedStudents.min' => 'لطفاً حداقل یک دانش‌آموز را انتخاب کنید.'
        ]);

        // ارتباط دانش‌آموزان انتخاب شده با آزمون
        $this->selectedExam?->students()->sync($this->assignedStudents);

        session()->flash('success', 'دانش‌آموزان با موفقیت به آزمون اختصاص داده شدند.');
        $this->closeAssignStudentsModal();
    }

    public function removeAssignment(int $studentId): void
    {
        if (!$this->selectedExam) {
            return;
        }

        DB::transaction(function () use ($studentId) {
            $examId = $this->selectedExam->id;

            StudentAnswer::where('exam_id', $examId)
                ->where('student_id', $studentId)
                ->delete();

            ExamAnalyses::where('exam_id', $examId)
                ->where('student_id', $studentId)
                ->delete();

            ExamAttemp::where('exam_id', $examId)
                ->where('student_id', $studentId)
                ->delete();

            $this->selectedExam->students()->detach($studentId);
        });

        $this->assignedStudents = array_values(array_diff($this->assignedStudents, [$studentId]));

        session()->flash('success', 'اختصاص دانش‌آموز و سوابق آزمون او حذف شد.');
    }

    protected function applyStudentSearch(Builder $query): Builder
    {
        $term = trim($this->studentSearch);

        if ($term === '') {
            return $query;
        }

        return $query->where(function (Builder $subQuery) use ($term) {
            $subQuery->whereHas('user.personalInformation', function (Builder $infoQuery) use ($term) {
                $infoQuery->where('name', 'like', "%{$term}%")
                    ->orWhere('code_mell', 'like', "%{$term}%");
            });

            if (is_numeric($term)) {
                $subQuery->orWhere('id', $term);
            }
        });
    }

    // متد render برای نمایش View
    public function render()
    {
        // نمایش آزمون‌هایی که توسط ادمین فعلی ساخته شده‌اند
        $exams = Exam::with(['user.personalInformation'])
            ->where('admin_id', 1)
            ->when($this->search !== '', function (Builder $query) {
                $query->where('title', 'like', "%{$this->search}%");
            })
            ->when($this->levelFilter !== 'all', function (Builder $query) {
                $query->where('level', $this->levelFilter);
            })
            ->latest()
            ->paginate(10);

        $studentsQuery = Student::query()
            ->with([
                'payment.order.orderItems.product',
                'payment.order.user',
                'user.personalInformation'
            ])
            ->when(auth()->id(), function (Builder $query, $supporterId) {
                $query->where('supporter_id', $supporterId);
            });

        $students = $this->applyStudentSearch($studentsQuery)
            ->latest()
            ->paginate(10, ['*'], 'studentsPage');

        return view('livewire.admin.student.exam.index', [
            'exams' => $exams,
            'students' => $students,
            'levelOptions' => $this->levelOptions,
        ])->layout('layouts.admin.app');
    }
}
