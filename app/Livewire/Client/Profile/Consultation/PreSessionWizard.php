<?php

namespace App\Livewire\Client\Profile\Consultation;

use App\Models\AdvisingSession;
use App\Models\AdvisingPreSession;
use App\Models\AdvisingPreSessionExam;
use App\Models\AdvisingPreSessionAssignment;
use App\Models\AdvisingPreSessionQa;
use App\Models\AdvisingPreSessionMisc;
use App\Models\Student;
use App\Models\CcGrade;
use App\Models\CcField;
use App\Models\AdvisingPreSessionRequestedPart;
use App\Models\CcSubject;
use App\Models\CcChapter;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Validator;
use Morilog\Jalali\Jalalian;
use Livewire\Component;
use Carbon\Carbon;

class PreSessionWizard extends Component
{
    use SEOTools;
    public $sessionId;
    public $preSession;
    public $canEdit = true;

    // مرحله فعلی wizard
    public $currentStep = 1;
    public $totalSteps = 6;
    // Curriculum data for dropdowns
    public $availableSubjects = [];
    public $availableChapters = [];
    // Subjects grouped by grade for requested parts step
    public $availableGradeSubjects = [];
    // Chapters for selected subject in requested part form
    public $requestedPartChapters = [];
    // داده‌های مرحله 1 - امتحانات
    public $exams = [];
    public $examForm = [
        'subject' => '',
        'cc_subject_id' => '',
        'cc_chapter_id' => '',
        'part_count' => 1,
        'time_per_part' => 60,
        'exam_date' => '',
    ];
    // داده‌های مرحله 2 - پرسش و پاسخ
    public $qas = [];
    public $qaForm = [
        'subject' => '',
        'cc_subject_id' => '',
        'cc_chapter_id' => '',
        'part_count' => 1,
        'time_per_part' => 60,
        'qa_date' => '',
    ];


    // داده‌های مرحله 3 - تکالیف
    public $assignments = [];
    public $assignmentForm = [
        'subject' => '',
        'cc_subject_id' => '',
        'part_count' => 1,
        'time_per_part' => 60,
        'due_date' => '',
    ];


    // داده‌های مرحله 4 - متفرقه
    public $miscDescription = '';
    // Date constraints
    public $minDate = '';
    public $maxDate = '';

//داده‌های مرحله 5 - پارت در خواستی
    public $requestedParts = [];
    public $requestedPartForm = [
        'subject'       => '',
        'cc_subject_id' => '',
        'cc_chapter_id' => '',
        'description'   => '',
        'part_count'    => 1,
        'time_per_part' => 60,
    ];

    protected function messages()
    {
        return [
            'examForm.subject.required' => 'نام درس الزامی است.',
            'examForm.part_count.required' => 'تعداد پارت الزامی است.',
            'examForm.part_count.min' => 'تعداد پارت باید حداقل ۱ باشد.',
            'examForm.time_per_part.required' => 'زمان هر پارت الزامی است.',
            'examForm.time_per_part.min' => 'زمان هر پارت باید حداقل ۱ دقیقه باشد.',
            'examForm.exam_date.required' => 'تاریخ امتحان الزامی است.',
            'qaForm.subject.required' => 'نام درس الزامی است.',
            'qaForm.part_count.required' => 'تعداد پارت الزامی است.',
            'qaForm.part_count.min' => 'تعداد پارت باید حداقل ۱ باشد.',
            'qaForm.time_per_part.required' => 'زمان هر پارت الزامی است.',
            'qaForm.time_per_part.min' => 'زمان هر پارت باید حداقل ۱ دقیقه باشد.',
            'qaForm.qa_date.required' => 'تاریخ پرسش و پاسخ الزامی است.',
            'assignmentForm.subject.required' => 'نام درس الزامی است.',
            'assignmentForm.part_count.required' => 'تعداد پارت الزامی است.',
            'assignmentForm.part_count.min' => 'تعداد پارت باید حداقل ۱ باشد.',
            'assignmentForm.time_per_part.required' => 'زمان هر پارت الزامی است.',
            'assignmentForm.time_per_part.min' => 'زمان هر پارت باید حداقل ۱ دقیقه باشد.',
            'assignmentForm.due_date.required' => 'تاریخ تکلیف الزامی است.',
            'requestedPartForm.subject.required'       => 'نام درس الزامی است.',
            'requestedPartForm.part_count.required'    => 'تعداد پارت الزامی است.',
            'requestedPartForm.part_count.min'         => 'تعداد پارت باید حداقل ۱ باشد.',
            'requestedPartForm.time_per_part.required' => 'زمان هر پارت الزامی است.',
            'requestedPartForm.time_per_part.min'      => 'زمان هر پارت باید حداقل ۱ دقیقه باشد.',
        ];
    }


    public function mount(AdvisingSession $session)
    {
        $this->sessionId = $session->id;
        // بررسی امکان ویرایش
        $this->canEdit = $session->canFillPreSession();
        // بارگذاری پیش‌جلسه
        $this->preSession = $session->preSession;
        if ($this->preSession) {
            $this->loadExistingData();
        }
        // Load subjects based on student's grade and field
        $this->loadStudentSubjects($session);
        // Load subjects grouped by grade (for requested parts step)
        $this->loadAllGradeSubjects($session);

        // Set date constraints based on consultation period
        $this->setDateConstraints($session);
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('پیش جلسه مشاوره');
    }
    /**
     * Load subjects based on student's personal information (grade + field)
     */
    protected function loadStudentSubjects(AdvisingSession $session): void
    {
        $student = $session->student;
        if (!$student) return;

        $user = $student->user;
        if (!$user) return;
        $personalInfo = $user->personalInformation;
        if (!$personalInfo) return;

        $grade = $personalInfo->grade; // '10', '11', '12'
        $field = $personalInfo->field; // 'math', 'experimental', 'human'
        $ccField = $field ? CcField::where('slug', $field)->where('is_active', true)->first() : null;

        $ccGradeQuery = CcGrade::where('grade_number', $grade)
            ->where('is_active', true);

        if ($ccField) {
            $ccGradeQuery->where('cc_field_id', $ccField->id);
        }
        $ccGrade = $ccGradeQuery->first();

        if ($ccGrade) {
            // Get CcField if applicable
            $ccFieldId = $ccGrade->cc_field_id ?? null;

            $this->availableSubjects = CcSubject::where('cc_grade_id', $ccGrade->id)
                ->when($ccFieldId, fn($q) => $q->where(function ($q2) use ($ccFieldId) {
                    $q2->where('cc_field_id', $ccFieldId)->orWhereNull('cc_field_id');
                }))
                ->orderBy('order')
                ->get()
                ->toArray();
        } else {
            // Fallback: try to find grade without field
            $ccGrades = CcGrade::where('grade_number', $grade)
                ->where('is_active', true)
                ->pluck('id');

            $this->availableSubjects = CcSubject::whereIn('cc_grade_id', $ccGrades)
                ->orderBy('order')
                ->get()
                ->toArray();
        }
    }
    /**
     * Load subjects grouped by grade (grade 10 up to student's current grade)
     * Used for the "پارت در خواستی" step
     */
    protected function loadAllGradeSubjects(AdvisingSession $session): void
    {
        $student = $session->student;
        if (!$student) return;

        $user = $student->user;
        if (!$user) return;
        $personalInfo = $user->personalInformation;
        if (!$personalInfo) return;

        $grade = (int) $personalInfo->grade; // 10, 11, or 12
        $field = $personalInfo->field;
        $ccField = $field ? CcField::where('slug', $field)->where('is_active', true)->first() : null;

        $gradesToLoad = [];
        for ($g = 10; $g <= $grade; $g++) {
            $gradesToLoad[] = $g;
        }

        $result = [];
        foreach ($gradesToLoad as $gradeNumber) {
            $ccGradeQuery = CcGrade::where('grade_number', $gradeNumber)
                ->where('is_active', true);

            if ($ccField) {
                $ccGradeQuery->where('cc_field_id', $ccField->id);
            }
            $ccGrade = $ccGradeQuery->first();

            if (!$ccGrade) {
                // Fallback: try without field filter
                $ccGrade = CcGrade::where('grade_number', $gradeNumber)
                    ->where('is_active', true)
                    ->first();
            }

            if ($ccGrade) {
                $subjects = CcSubject::where('cc_grade_id', $ccGrade->id)
                    ->orderBy('order')
                    ->get()
                    ->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'type' => $s->type ?? 'specialized'])
                    ->toArray();

                if (count($subjects) > 0) {
                    $result[] = [
                        'grade_number' => $gradeNumber,
                        'grade_label'  => 'پایه ' . $this->gradeLabel($gradeNumber),
                        'subjects'     => $subjects,
                    ];
                }
            }
        }

        $this->availableGradeSubjects = $result;
    }

    private function gradeLabel(int $grade): string
    {
        return match($grade) {
            10 => 'دهم',
            11 => 'یازدهم',
            12 => 'دوازدهم',
            default => (string) $grade,
        };
    }
    /**
     * Set date constraints based on the consultation session period
     */
    protected function setDateConstraints(AdvisingSession $session): void
    {
        // Current session date
        $sessionDate = Carbon::parse($session->activation_date);

        // Find next session to determine end of period
        $student = $session->student;
        if ($student) {
            $nextSession = AdvisingSession::where('student_id', $student->id)
                ->where('activation_date', '>', $session->activation_date)
                ->orderBy('activation_date')
                ->first();

            if ($nextSession) {
                $endDate = Carbon::parse($nextSession->activation_date)->subDay();
            } else {
                // Default: 8 days from session
                $endDate = $sessionDate->copy()->addDays(7);
            }
        } else {
            $endDate = $sessionDate->copy()->addDays(7);
        }

        $this->minDate = jdate($sessionDate)->format('Y/m/d');
        $this->maxDate = jdate($endDate)->format('Y/m/d');
    }

    /**
     * When a subject is selected in the exam form, load chapters
     */
    public function updatedExamFormCcSubjectId($value): void
    {
        $this->examForm['cc_chapter_id'] = '';
        $this->availableChapters = [];

        if ($value) {
            $subject = CcSubject::find($value);
            if ($subject) {
                $this->examForm['subject'] = $subject->name;
            }
            $this->availableChapters = CcChapter::where('cc_subject_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get()
                ->toArray();
        }
    }

    /**
     * When a subject is selected in the QA form, load chapters
     */
    public function updatedQaFormCcSubjectId($value): void
    {
        $this->qaForm['cc_chapter_id'] = '';

        if ($value) {
            $subject = CcSubject::find($value);
            if ($subject) {
                $this->qaForm['subject'] = $subject->name;
            }
            $this->availableChapters = CcChapter::where('cc_subject_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get()
                ->toArray();
        } else {
            $this->availableChapters = [];
        }
    }

    /**
     * When a subject is selected in the requested part form, load chapters
     */
    public function updatedRequestedPartFormCcSubjectId($value): void
    {
        $this->requestedPartForm['cc_chapter_id'] = '';
        $this->requestedPartChapters = [];

        if ($value) {
            $subject = CcSubject::find($value);
            if ($subject) {
                $this->requestedPartForm['subject'] = $subject->name;
            }
            $this->requestedPartChapters = CcChapter::where('cc_subject_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get()
                ->toArray();
        }
    }

    /**
     * When a subject is selected in the assignment form
     */
    public function updatedAssignmentFormCcSubjectId($value): void
    {
        if ($value) {
            $subject = CcSubject::find($value);
            if ($subject) {
                $this->assignmentForm['subject'] = $subject->name;
            }
        }
    }
    protected function convertJalaliToGregorian($date)
    {
        $normalizedDate = strtr(trim((string)$date), [
            '۰' => '0', '۱' => '1', '۲' => '2', '۳' => '3', '۴' => '4',
            '۵' => '5', '۶' => '6', '۷' => '7', '۸' => '8', '۹' => '9',
            '٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4',
            '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9',
            '-' => '/',
        ]);

        if (!$normalizedDate) return null;
        try {
            return Jalalian::fromFormat('Y/m/d', $normalizedDate)->toCarbon()->toDateString();
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function loadExistingData()
    {
        // بارگذاری امتحانات
        $this->exams = $this->preSession->exams()->get()->toArray();
        // بارگذاری پرسش و پاسخ
        $this->qas = $this->preSession->qas()->get()->toArray();
        // بارگذاری تکالیف
        $this->assignments = $this->preSession->assignments()->get()->toArray();
        // بارگذاری متفرقه
        $misc = $this->preSession->miscellaneous;
        $this->miscDescription = $misc ? $misc->description : '';
    }


    public function nextStep()
    {
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }


    public function prevStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }


    public function goToStep($step)
    {
        if ($step >= 1 && $step <= $this->totalSteps) {
            $this->currentStep = $step;
        }
    }


    // ==================== مرحله 1: امتحانات ====================


    public function addExam()
    {
        if (!$this->canEdit) {
            $this->dispatch('warning', 'امکان ویرایش وجود ندارد.');
            return;
        }

// If cc_subject_id is set but subject name is empty, fill it
        if (!empty($this->examForm['cc_subject_id']) && empty($this->examForm['subject'])) {
            $subject = CcSubject::find($this->examForm['cc_subject_id']);
            if ($subject) {
                $this->examForm['subject'] = $subject->name;
            }
        }
        $this->validate([
            'examForm.subject' => 'required|string|max:255',
            'examForm.part_count' => 'required|integer|min:1',
            'examForm.time_per_part' => 'required|integer|min:1',
            'examForm.exam_date' => 'required|string',
        ], $this->messages());
        $examDate = $this->convertJalaliToGregorian($this->examForm['exam_date']);

        if (!$examDate) {
            $this->addError('examForm.exam_date', 'فرمت تاریخ معتبر نیست.');
            return;
        }
        AdvisingPreSessionExam::create([
            'pre_session_id' => $this->preSession->id,
            'subject' => $this->examForm['subject'],
            'cc_subject_id' => $this->examForm['cc_subject_id'] ?: null,
            'cc_chapter_id' => $this->examForm['cc_chapter_id'] ?: null,
            'part_count' => $this->examForm['part_count'],
            'time_per_part' => $this->examForm['time_per_part'],
            'exam_date' => $examDate,
        ]);

        $this->resetExamForm();
        $this->loadExistingData();
        $this->dispatch('success', 'امتحان با موفقیت اضافه شد.');

    }


    public function deleteExam($examId)
    {
        if (!$this->canEdit) {
            $this->dispatch('warning', 'امکان حذف وجود ندارد.');
            return;
        }
        AdvisingPreSessionExam::find($examId)?->delete();
        $this->loadExistingData();
        $this->dispatch('success', 'امتحان حذف شد.');
    }


    public function resetExamForm()
    {
        $this->examForm = [
            'subject' => '',
            'cc_subject_id' => '',
            'cc_chapter_id' => '',
            'part_count' => 1,
            'time_per_part' => 60,
            'exam_date' => '',
        ];
        $this->availableChapters = [];
    }


    // ==================== مرحله 2: پرسش و پاسخ ====================


    public function addQa()
    {
        if (!$this->canEdit) {
            $this->dispatch('warning', 'امکان ویرایش وجود ندارد.');
            return;
        }

        if (!empty($this->qaForm['cc_subject_id']) && empty($this->qaForm['subject'])) {
            $subject = CcSubject::find($this->qaForm['cc_subject_id']);
            if ($subject) {
                $this->qaForm['subject'] = $subject->name;
            }
        }
        $this->validate([
            'qaForm.subject' => 'required|string|max:255',
            'qaForm.part_count' => 'required|integer|min:1',
            'qaForm.time_per_part' => 'required|integer|min:1',
            'qaForm.qa_date' => 'required|string',
        ], $this->messages());
        $qaDate = $this->convertJalaliToGregorian($this->qaForm['qa_date']);
        if (!$qaDate) {
            $this->addError('qaForm.qa_date', 'فرمت تاریخ معتبر نیست.');
            return;
        }
        AdvisingPreSessionQa::create([
            'pre_session_id' => $this->preSession->id,
            'subject' => $this->qaForm['subject'],
            'cc_subject_id' => $this->qaForm['cc_subject_id'] ?: null,
            'cc_chapter_id' => $this->qaForm['cc_chapter_id'] ?: null,
            'part_count' => $this->qaForm['part_count'],
            'time_per_part' => $this->qaForm['time_per_part'],
            'qa_date' => $qaDate,
        ]);
        $this->resetQaForm();
        $this->loadExistingData();
        $this->dispatch('success', 'پرسش و پاسخ با موفقیت اضافه شد.');
    }


    public function deleteQa($qaId)
    {
        if (!$this->canEdit) {
            $this->dispatch('warning', 'امکان حذف وجود ندارد.');
            return;
        }
        AdvisingPreSessionQa::find($qaId)?->delete();
        $this->loadExistingData();
        $this->dispatch('success', 'پرسش و پاسخ حذف شد.');
    }


    public function resetQaForm()
    {
        $this->qaForm = [
            'subject' => '',
            'cc_subject_id' => '',
            'cc_chapter_id' => '',
            'part_count' => 1,
            'time_per_part' => 60,
            'qa_date' => '',
        ];
        $this->availableChapters = [];

    }


    // ==================== مرحله 3: تکالیف ====================


    public function addAssignment()
    {
        if (!$this->canEdit) {
            $this->dispatch('warning', 'امکان ویرایش وجود ندارد.');
            return;
        }
        if (!empty($this->assignmentForm['cc_subject_id']) && empty($this->assignmentForm['subject'])) {
            $subject = CcSubject::find($this->assignmentForm['cc_subject_id']);
            if ($subject) {
                $this->assignmentForm['subject'] = $subject->name;
            }
        }

        $this->validate([
            'assignmentForm.subject' => 'required|string|max:255',
            'assignmentForm.part_count' => 'required|integer|min:1',
            'assignmentForm.time_per_part' => 'required|integer|min:1',
            'assignmentForm.due_date' => 'required|string',
        ], $this->messages());

        $dueDate = $this->convertJalaliToGregorian($this->assignmentForm['due_date']);

        if (!$dueDate) {
            $this->addError('assignmentForm.due_date', 'فرمت تاریخ معتبر نیست.');
            return;
        }
        AdvisingPreSessionAssignment::create([
            'pre_session_id' => $this->preSession->id,
            'subject' => $this->assignmentForm['subject'],
            'cc_subject_id' => $this->assignmentForm['cc_subject_id'] ?: null,
            'part_count' => $this->assignmentForm['part_count'],
            'time_per_part' => $this->assignmentForm['time_per_part'],
            'due_date' => $dueDate,
        ]);

        $this->resetAssignmentForm();
        $this->loadExistingData();
        $this->dispatch('success', 'تکلیف با موفقیت اضافه شد.');
    }


    public function deleteAssignment($assignmentId)
    {
        if (!$this->canEdit) {
            $this->dispatch('warning', 'امکان حذف وجود ندارد.');
            return;
        }
        AdvisingPreSessionAssignment::find($assignmentId)?->delete();
        $this->loadExistingData();
        $this->dispatch('success', 'تکلیف حذف شد.');
    }


    public function resetAssignmentForm()
    {
        $this->assignmentForm = [
            'subject' => '',
            'cc_subject_id' => '',
            'part_count' => 1,
            'time_per_part' => 60,
            'due_date' => '',
        ];
    }

    // ==================== مرحله 5: پارت در خواستی ====================


    public function addRequestedPart()
    {
        if (!$this->canEdit) {
            $this->dispatch('warning', 'امکان ویرایش وجود ندارد.');
            return;
        }

        if (!empty($this->requestedPartForm['cc_subject_id']) && empty($this->requestedPartForm['subject'])) {
            $subject = CcSubject::find($this->requestedPartForm['cc_subject_id']);
            if ($subject) {
                $this->requestedPartForm['subject'] = $subject->name;
            }
        }

        $this->validate([
            'requestedPartForm.subject'       => 'required|string|max:255',
            'requestedPartForm.part_count'    => 'required|integer|min:1',
            'requestedPartForm.time_per_part' => 'required|integer|min:1',
        ], $this->messages());

        AdvisingPreSessionRequestedPart::create([
            'pre_session_id' => $this->preSession->id,
            'subject'        => $this->requestedPartForm['subject'],
            'cc_subject_id'  => $this->requestedPartForm['cc_subject_id'] ?: null,
            'cc_chapter_id'  => $this->requestedPartForm['cc_chapter_id'] ?: null,
            'description'    => $this->requestedPartForm['description'] ?: null,
            'part_count'     => $this->requestedPartForm['part_count'],
            'time_per_part'  => $this->requestedPartForm['time_per_part'],
        ]);

        $this->resetRequestedPartForm();
        $this->loadExistingData();
        $this->dispatch('success', 'پارت در خواستی با موفقیت اضافه شد.');
    }


    public function deleteRequestedPart($partId)
    {
        if (!$this->canEdit) {
            $this->dispatch('warning', 'امکان حذف وجود ندارد.');
            return;
        }
        AdvisingPreSessionRequestedPart::find($partId)?->delete();
        $this->loadExistingData();
        $this->dispatch('success', 'پارت در خواستی حذف شد.');
    }


    public function resetRequestedPartForm()
    {
        $this->requestedPartForm = [
            'subject'       => '',
            'cc_subject_id' => '',
            'cc_chapter_id' => '',
            'description'   => '',
            'part_count'    => 1,
            'time_per_part' => 60,
        ];
        $this->requestedPartChapters = [];
    }


    // ==================== مرحله 4: متفرقه ====================


    public function saveMiscellaneous()
    {
        if (!$this->canEdit) {
            $this->dispatch('warning', 'امکان ویرایش وجود ندارد.');
            return;
        }
        AdvisingPreSessionMisc::updateOrCreate(
            ['pre_session_id' => $this->preSession->id],
            ['description' => $this->miscDescription]
        );
        $this->dispatch('success', 'توضیحات متفرقه ذخیره شد.');
    }


    // ==================== ذخیره نهایی ====================


    public function finalSubmit()
    {
        if (!$this->canEdit) {
            $this->dispatch('warning', 'امکان ثبت وجود ندارد.');
            return;
        }
        // ذخیره متفرقه اگر وجود دارد
        if ($this->miscDescription) {
            $this->saveMiscellaneous();
        }
        // تغییر وضعیت پیش‌جلسه به تکمیل شده
        $this->preSession->update(['status' => 'completed']);
        $this->dispatch('success', 'پیش‌جلسه با موفقیت ثبت شد.');
        return redirect()->route('client.profile.consultation.sessions');
    }

    /**
     * Get available dates as buttons (Jalali) within the session range
     */
    public function getAvailableDates(): array
    {
        if (!$this->minDate || !$this->maxDate) return [];

        $jalaliDayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
        $dates = [];

        try {
            $start = Jalalian::fromFormat('Y/m/d', $this->minDate)->toCarbon();
            $end = Jalalian::fromFormat('Y/m/d', $this->maxDate)->toCarbon();

            $current = $start->copy();
            while ($current->lte($end)) {
                $jalali = jdate($current);
                $dayOfWeek = $jalali->getDayOfWeek();

                $dates[] = [
                    'value' => $jalali->format('Y/m/d'),
                    'day_name' => $jalaliDayNames[$dayOfWeek] ?? '',
                    'day' => $jalali->getDay(),
                    'month_name' => $jalali->format('%B'),
                ];

                $current->addDay();
            }
        } catch (\Throwable $e) {
            // Fallback if date parsing fails
        }

        return $dates;
    }
    public function render()
    {
        $session = AdvisingSession::with(['student.user.personalInformation', 'advisor'])->find($this->sessionId);
        $stepTitles = [
            1 => 'امتحانات',
            2 => 'پرسش و پاسخ کلاسی',
            3 => 'تکالیف',
            4 => 'پارت در خواستی',
            5=> 'متفرقه',
            6 => 'نمایش نهایی',
        ];
        $availableDates = $this->getAvailableDates();
        return view('livewire.client.profile.consultation.pre-session-wizard', [
            'session' => $session,
            'stepTitles' => $stepTitles,
            'availableDates' => $availableDates,
        ])->layout('layouts.client.app');
    }
}
