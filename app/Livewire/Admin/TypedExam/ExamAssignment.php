<?php


namespace App\Livewire\Admin\TypedExam;


use App\Models\Student;

use App\Models\TypedExam;

use App\Models\TypedExamAssignment;

use App\Models\TypedExamAssignmentTime;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Livewire\Component;

use Livewire\WithPagination;


class ExamAssignment extends Component

{

    use WithPagination;


    public int $examId;

    public ?TypedExam $exam = null;


    // Assignment Modal

    public bool $showAssignModal = false;

    public array $selectedStudents = [];

    public string $startDate = '';

    public string $endDate = '';

    public string $startTime = '08:00';

    public string $endTime = '18:00';
    public int|string $durationMinutes = 60; // 👈 مدت آزمون (دقیقه)

    // Search

    public string $studentSearch = '';


    // Edit Modal

    public bool $showEditModal = false;

    public ?int $editingAssignmentId = null;

    public string $editStartDate = '';

    public string $editEndDate = '';

    public string $editStartTime = '';

    public string $editEndTime = '';
    public int|string $editDurationMinutes = 60; // 👈 مدت آزمون در ویرایش

    protected function rules(): array

    {

        return [

            'selectedStudents' => 'required|array|min:1',

            'startDate' => 'required|date',

            'endDate' => 'required|date|after_or_equal:startDate',

            'startTime' => 'required',

            'endTime' => 'required',
            'durationMinutes' => 'required|integer|min:1|max:1440',

        ];

    }


    protected function messages(): array

    {

        return [

            'selectedStudents.required' => 'لطفاً حداقل یک دانش‌آموز انتخاب کنید.',

            'selectedStudents.min' => 'لطفاً حداقل یک دانش‌آموز انتخاب کنید.',

            'startDate.required' => 'تاریخ شروع الزامی است.',

            'endDate.required' => 'تاریخ پایان الزامی است.',

            'endDate.after_or_equal' => 'تاریخ پایان باید بعد از تاریخ شروع باشد.',

            'startTime.required' => 'ساعت شروع الزامی است.',

            'endTime.required' => 'ساعت پایان الزامی است.',
            'durationMinutes.required' => 'مدت آزمون الزامی است.',
            'durationMinutes.integer'  => 'مدت آزمون باید به‌صورت عددی (دقیقه) وارد شود.',
            'durationMinutes.min'      => 'مدت آزمون باید حداقل ۱ دقیقه باشد.',
            'durationMinutes.max'      => 'مدت آزمون نمی‌تواند بیشتر از ۱۴۴۰ دقیقه باشد.',

        ];

    }


    public function mount(int $examId): void

    {

        $this->examId = $examId;

        $this->exam = TypedExam::with('settings')->findOrFail($examId);


        // Set default dates

        $this->startDate = now()->format('Y-m-d');

        $this->endDate = now()->addDays(7)->format('Y-m-d');
        $this->durationMinutes = 60; // 👈 پیش‌فرض ۶۰ دقیقه
    }


    public function openAssignModal(): void

    {

        $this->showAssignModal = true;

    }


    public function closeAssignModal(): void
    {
        $this->showAssignModal = false;
        $this->reset(['selectedStudents', 'startTime', 'endTime', 'durationMinutes']);
        $this->startDate = now()->format('Y-m-d');
        $this->endDate = now()->addDays(7)->format('Y-m-d');
        $this->startTime = '08:00';
        $this->endTime = '18:00';
        $this->durationMinutes = 60;
    }



    public function toggleStudent(int $studentId): void

    {

        if (in_array($studentId, $this->selectedStudents)) {

            $this->selectedStudents = array_diff($this->selectedStudents, [$studentId]);

        } else {

            $this->selectedStudents[] = $studentId;

        }

    }


    public function assignExam(): void

    {

        $this->validate();


        $admin = Auth::guard('admin')->user();


        DB::transaction(function () use ($admin) {

            foreach ($this->selectedStudents as $studentId) {

                // Check if already assigned

                $exists = TypedExamAssignment::where('typed_exam_id', $this->examId)
                    ->where('student_id', $studentId)
                    ->whereNull('deleted_at')
                    ->exists();


                if ($exists) {

                    continue;

                }


                $assignment = TypedExamAssignment::create([

                    'typed_exam_id' => $this->examId,

                    'student_id' => $studentId,

                    'admin_id' => $admin->id,

                    'status' => 'pending',

                ]);


                TypedExamAssignmentTime::create([
                    'assignment_id' => $assignment->id,
                    'start_date' => $this->startDate,
                    'end_date' => $this->endDate,
                    'start_time' => $this->startTime,
                    'end_time' => $this->endTime,
                    'duration_minutes' => $this->durationMinutes,
                ]);


            }

        });


        $this->closeAssignModal();

        $this->dispatch('success', 'آزمون با موفقیت به دانش‌آموزان اختصاص یافت.');

    }


    public function openEditModal(int $assignmentId): void
    {
        $assignment = TypedExamAssignment::with('time')->findOrFail($assignmentId);

        $this->editingAssignmentId = $assignmentId;
        $this->editStartDate = $assignment->time?->start_date?->format('Y-m-d') ?? '';
        $this->editEndDate = $assignment->time?->end_date?->format('Y-m-d') ?? '';
        $this->editStartTime = $assignment->time?->start_time ?? '';
        $this->editEndTime = $assignment->time?->end_time ?? '';
        $this->editDurationMinutes = $assignment->time?->duration_minutes ?? 60; // 👈 این

        $this->showEditModal = true;
    }


    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->reset(['editingAssignmentId', 'editStartDate', 'editEndDate', 'editStartTime', 'editEndTime', 'editDurationMinutes']);
    }


    public function updateAssignment(): void
    {
        $this->validate([
            'editStartDate' => 'required|date',
            'editEndDate' => 'required|date|after_or_equal:editStartDate',
            'editStartTime' => 'required',
            'editEndTime' => 'required',
            'editDurationMinutes' => 'required|integer|min:1|max:1440',
        ]);

        $assignment = TypedExamAssignment::findOrFail($this->editingAssignmentId);

        $assignment->time()->updateOrCreate(
            ['assignment_id' => $assignment->id],
            [
                'start_date' => $this->editStartDate,
                'end_date' => $this->editEndDate,
                'start_time' => $this->editStartTime,
                'end_time' => $this->editEndTime,
                'duration_minutes' => $this->editDurationMinutes, // 👈 اینجا ذخیره می‌شود
            ]
        );

        $this->closeEditModal();
        $this->dispatch('success', 'زمان‌بندی با موفقیت به‌روزرسانی شد.');
    }



    public function deleteAssignment(int $assignmentId): void
    {
        $assignment = TypedExamAssignment::with(['attempts', 'time'])->find($assignmentId);

        if (!$assignment) {
            return;
        }

        DB::transaction(function () use ($assignment) {
            // حذف همه attempts مرتبط
            if ($assignment->attempts()->exists()) {
                $assignment->attempts()->delete(); // در صورت SoftDeletes در Attempt، soft می‌شود
            }

            // حذف زمان‌بندی
            if ($assignment->time) {
                $assignment->time()->delete();
            }

            // حذف خود اختصاص
            $assignment->delete(); // اگر SoftDeletes روی TypedExamAssignment داری، این soft delete است
        });

        $this->dispatch('success', 'اختصاص و آزمون‌های مرتبط با موفقیت حذف شد.');
    }





    public function render()
    {
        $admin = Auth::guard('admin')->user();

        // Get students for the admin (based on supporter/advisor relationship)
        $studentsQuery = Student::with('user')
            ->where(function ($q) use ($admin) {
                $q->where('supporter_id', $admin->id)
                    ->orWhere('advisor_id', $admin->id);
            });

        if ($this->studentSearch) {
            $studentsQuery->whereHas('user', function ($q) {
                $q->where('name', 'like', "%{$this->studentSearch}%")
                    ->orWhere('mobile', 'like', "%{$this->studentSearch}%");
            });
        }

        $students = $studentsQuery->get();

        // Get assignments for this exam
        $assignments = TypedExamAssignment::with(['student.user', 'time', 'latestAttempt'])
            ->where('typed_exam_id', $this->examId)
            ->whereHas('student', function ($q) use ($admin) {
                $q->where('supporter_id', $admin->id)
                    ->orWhere('advisor_id', $admin->id);
            })
            ->latest()
            ->paginate(10);

        // 👉 لیست دانش‌آموزانی که همین آزمون به آن‌ها اختصاص داده شده
        $assignedStudentIds = TypedExamAssignment::where('typed_exam_id', $this->examId)
            ->whereHas('student', function ($q) use ($admin) {
                $q->where('supporter_id', $admin->id)
                    ->orWhere('advisor_id', $admin->id);
            })
            ->whereNull('deleted_at') // اگر soft delete داری
            ->pluck('student_id')
            ->toArray();

        return view('livewire.admin.typed-exam.exam-assignment', compact(
            'students',
            'assignments',
            'assignedStudentIds'
        ))->layout('layouts.admin.app');
    }


}
