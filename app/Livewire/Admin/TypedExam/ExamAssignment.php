<?php


namespace App\Livewire\Admin\TypedExam;


use App\Models\Student;

use App\Models\TypedExam;

use App\Models\TypedExamAssignment;

use App\Models\TypedExamAssignmentTime;

use App\Services\NotificationService;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

use Livewire\Component;

use Livewire\WithPagination;
use Morilog\Jalali\Jalalian;


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
    public int|string $durationMinutes = 60;


    // Visibility Settings

    public string $resultVisibility = 'after_exam_end';

    public string $answerKeyVisibility = 'after_exam_end';
    // Search

    public string $studentSearch = '';


    // Edit Modal

    public bool $showEditModal = false;

    public ?int $editingAssignmentId = null;

    public string $editStartDate = '';

    public string $editEndDate = '';

    public string $editStartTime = '';

    public string $editEndTime = '';
    public int|string $editDurationMinutes = 60;

    public string $editResultVisibility = 'after_exam_end';

    public string $editAnswerKeyVisibility = 'after_exam_end';

    protected function rules(): array

    {

        return [

            'selectedStudents' => 'required|array|min:1',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'startTime' => 'required',
            'endTime' => 'required',
            'durationMinutes' => 'required|integer|min:1|max:1440',
            'resultVisibility' => 'required|in:after_exam_end,immediately',
            'answerKeyVisibility' => 'required|in:after_exam_end,immediately',
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
            'durationMinutes.integer' => 'مدت آزمون باید به‌صورت عددی (دقیقه) وارد شود.',
            'durationMinutes.min' => 'مدت آزمون باید حداقل ۱ دقیقه باشد.',
            'durationMinutes.max' => 'مدت آزمون نمی‌تواند بیشتر از ۱۴۴۰ دقیقه باشد.',

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
        $this->reset(['selectedStudents', 'startTime', 'endTime', 'durationMinutes', 'resultVisibility', 'answerKeyVisibility']);
        $this->startDate = now()->format('Y-m-d');
        $this->endDate = now()->addDays(7)->format('Y-m-d');
        $this->startTime = '08:00';
        $this->endTime = '18:00';
        $this->durationMinutes = 60;
        $this->resultVisibility = 'after_exam_end';
        $this->answerKeyVisibility = 'after_exam_end';
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


        $assignedStudentIds = [];


        DB::transaction(function () use ($admin, &$assignedStudentIds) {


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

                    'result_visibility' => $this->resultVisibility,

                    'answer_key_visibility' => $this->answerKeyVisibility,


                ]);


                TypedExamAssignmentTime::create([

                    'assignment_id' => $assignment->id,

                    'start_date' => $this->startDate,

                    'end_date' => $this->endDate,

                    'start_time' => $this->startTime,

                    'end_time' => $this->endTime,

                    'duration_minutes' => $this->durationMinutes,

                ]);


                $assignedStudentIds[] = $studentId;


            }


        });


        // ارسال نوتیفیکیشن به دانش‌آموزان

        $this->sendExamAssignedNotifications($assignedStudentIds);


        $this->closeAssignModal();


        $this->dispatch('success', 'آزمون با موفقیت به دانش‌آموزان اختصاص یافت.');


    }


    /**
     * ارسال نوتیفیکیشن اختصاص آزمون به دانش‌آموزان
     */

    protected function sendExamAssignedNotifications(array $studentIds): void

    {

        if (empty($studentIds)) {

            return;

        }


        $students = Student::with('user')->whereIn('id', $studentIds)->get();

        $examTitle = $this->exam->title ?? 'آزمون';

        $startDateJalali = Jalalian::fromDateTime($this->startDate)->format('Y/m/d');

        $endDateJalali = Jalalian::fromDateTime($this->endDate)->format('Y/m/d');

        $durationMinutes = $this->durationMinutes;


        foreach ($students as $student) {

            $studentName = $student->user->name ?? 'دانش آموز';


            $message = "{$studentName} عزیز\nآزمون «{$examTitle}» برای شما اختصاص یافت.\n";

            $message .= "تاریخ شروع: {$startDateJalali}\n";

            $message .= "تاریخ پایان: {$endDateJalali}\n";

            $message .= "ساعت مجاز: {$this->startTime} تا {$this->endTime}\n";

            $message .= "مدت زمان آزمون: {$durationMinutes} دقیقه\n";

            $message .= "با تشکر";


            NotificationService::sendToStudent(

                $student->id,

                'اختصاص آزمون جدید',

                $message

            );

        }
    }


    public function openEditModal(int $assignmentId): void
    {
        $assignment = TypedExamAssignment::with('time')->findOrFail($assignmentId);

        $this->editingAssignmentId = $assignmentId;
        $this->editStartDate = $assignment->time?->start_date?->format('Y-m-d') ?? '';
        $this->editEndDate = $assignment->time?->end_date?->format('Y-m-d') ?? '';
        $this->editStartTime = $assignment->time?->start_time ?? '';
        $this->editEndTime = $assignment->time?->end_time ?? '';
        $this->editDurationMinutes = $assignment->time?->duration_minutes ?? 60;

        $this->editResultVisibility = $assignment->result_visibility ?? 'after_exam_end';

        $this->editAnswerKeyVisibility = $assignment->answer_key_visibility ?? 'after_exam_end';
        $this->showEditModal = true;
    }


    public function closeEditModal(): void
    {
        $this->showEditModal = false;
        $this->reset(['editingAssignmentId', 'editStartDate', 'editEndDate', 'editStartTime', 'editEndTime', 'editDurationMinutes', 'editResultVisibility', 'editAnswerKeyVisibility']);
    }


    public function updateAssignment(): void
    {
        $this->validate([
            'editStartDate' => 'required|date',
            'editEndDate' => 'required|date|after_or_equal:editStartDate',
            'editStartTime' => 'required',
            'editEndTime' => 'required',
            'editDurationMinutes' => 'required|integer|min:1|max:1440',
            'editResultVisibility' => 'required|in:after_exam_end,immediately',

            'editAnswerKeyVisibility' => 'required|in:after_exam_end,immediately',

        ]);


        $assignment = TypedExamAssignment::findOrFail($this->editingAssignmentId);
        // Update visibility settings on assignment
        $assignment->update([
            'result_visibility' => $this->editResultVisibility,
            'answer_key_visibility' => $this->editAnswerKeyVisibility,
        ]);
        $assignment->time()->updateOrCreate(
            ['assignment_id' => $assignment->id],
            [
                'start_date' => $this->editStartDate,
                'end_date' => $this->editEndDate,
                'start_time' => $this->editStartTime,
                'end_time' => $this->editEndTime,
                'duration_minutes' => $this->editDurationMinutes,
            ]
        );
        $this->closeEditModal();
        $this->dispatch('success', 'تنظیمات با موفقیت به‌روزرسانی شد.');
    }


    public function deleteAssignment(int $assignmentId): void
    {
        $assignment = TypedExamAssignment::with(['attempts.analysisUploads', 'time'])
            ->whereKey($assignmentId)
            ->where('typed_exam_id', $this->examId)
            ->where('admin_id', Auth::guard('admin')->id())
            ->first();

        if (!$assignment) {
            return;
        }

        $analysisPaths = $assignment->attempts
            ->flatMap(fn ($attempt) => $attempt->analysisUploads->pluck('full_path'))
            ->filter()
            ->values()
            ->all();

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

        File::delete($analysisPaths);

        $this->dispatch('success', 'اختصاص و آزمون‌های مرتبط با موفقیت حذف شد.');
    }


    public function render()
    {
        $admin = Auth::guard('admin')->user();

        // Get students for the admin (based on supporter/advisor relationship)
        $studentsQuery = Student::with('user')
            ->where(function ($q) use ($admin) {
                $q->where('advisor_id', $admin->id);
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
                $q->where('advisor_id', $admin->id);
            })
            ->latest()
            ->paginate(10);

        // 👉 لیست دانش‌آموزانی که همین آزمون به آن‌ها اختصاص داده شده
        $assignedStudentIds = TypedExamAssignment::where('typed_exam_id', $this->examId)
            ->whereHas('student', function ($q) use ($admin) {
                $q->where('advisor_id', $admin->id);
            })
            ->whereNull('deleted_at') // اگر soft delete داری
            ->pluck('student_id')
            ->toArray();
        $visibilityOptions = [
            'after_exam_end' => 'بعد از پایان آزمون',
            'immediately' => 'بلافاصله پس از ثبت پاسخ',
        ];
        return view('livewire.admin.typed-exam.exam-assignment', compact(
            'students',
            'assignments',
            'assignedStudentIds',
            'visibilityOptions'
        ))->layout('layouts.admin.app');
    }


}
