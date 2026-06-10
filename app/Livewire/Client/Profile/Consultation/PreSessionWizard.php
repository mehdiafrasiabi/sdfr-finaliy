<?php

namespace App\Livewire\Client\Profile\Consultation;

use App\Models\AdvisingSession;
use App\Models\AdvisingPreSession;
use App\Models\AdvisingPreSessionExam;
use App\Models\AdvisingPreSessionAssignment;
use App\Models\AdvisingPreSessionQa;
use App\Models\AdvisingPreSessionMisc;
use App\Models\AdvisingPreSessionRequestedPart;
use App\Models\Student;
use App\Models\CcGrade;
use App\Models\CcField;
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

    /**
     * اگر دانش‌آموز مدرسه نمی‌رود یا فارغ‌التحصیل است، بخش‌های مدرسه‌ای
     * (امتحانات، پرسش و پاسخ کلاسی، تکالیف) قفل می‌شوند و فقط
     * «پارت درخواستی» و «متفرقه» قابل ثبت هستند.
     */
    public bool $schoolLocked = false;

    // ─── حل مشکل پریدن مودال: openCard در Livewire نگه داشته میشه ───
    public $openCard = '';

    public $currentStep = 1;
    public $totalSteps  = 6;

    public $availableSubjects     = [];
    public $availableChapters     = [];
    public $availableGradeSubjects = [];
    public $requestedPartChapters = [];

    // ─── مرحله ۱: امتحانات ───
    public $exams    = [];
    public $examForm = [
        'subject'       => '',
        'cc_subject_id' => '',
        'cc_chapter_id' => '',
        'part_count'    => 1,
        'hours'         => 0,
        'minutes'       => 15,
        'exam_date'     => '',
    ];

    // ─── مرحله ۲: پرسش و پاسخ ───
    public $qas    = [];
    public $qaForm = [
        'subject'       => '',
        'cc_subject_id' => '',
        'cc_chapter_id' => '',
        'part_count'    => 1,
        'hours'         => 0,
        'minutes'       => 15,
        'qa_date'       => '',
    ];

    // ─── مرحله ۳: تکالیف ───
    public $assignments     = [];
    public $assignmentForm  = [
        'subject'       => '',
        'cc_subject_id' => '',
        'part_count'    => 1,
        'hours'         => 0,
        'minutes'       => 15,
        'due_date'      => '',
    ];

    // ─── مرحله ۴: متفرقه ───
    public $miscDescription = '';

    // ─── مرحله ۵: پارت درخواستی ───
    public $requestedParts    = [];
    public $requestedPartForm = [
        'subject'       => '',
        'cc_subject_id' => '',
        'cc_chapter_id' => '',
        'description'   => '',
        'part_count'    => 1,
        'hours'         => 0,
        'minutes'       => 15,
    ];

    public $minDate = '';
    public $maxDate = '';

    // ─── helper: تبدیل hours+minutes به دقیقه کل (حداقل ۱۵) ───
    protected function calcMinutes(int $hours, int $minutes): int
    {
        $total = max(0, $hours) * 60 + max(0, $minutes);
        return max(15, $total);
    }

    protected function messages(): array
    {
        return [
            'examForm.subject.required'            => 'نام درس الزامی است.',
            'examForm.part_count.required'         => 'تعداد پارت الزامی است.',
            'examForm.part_count.min'              => 'تعداد پارت باید حداقل ۱ باشد.',
            'examForm.exam_date.required'          => 'تاریخ امتحان الزامی است.',
            'qaForm.subject.required'              => 'نام درس الزامی است.',
            'qaForm.part_count.required'           => 'تعداد پارت الزامی است.',
            'qaForm.part_count.min'                => 'تعداد پارت باید حداقل ۱ باشد.',
            'qaForm.qa_date.required'              => 'تاریخ پرسش و پاسخ الزامی است.',
            'assignmentForm.subject.required'      => 'نام درس الزامی است.',
            'assignmentForm.part_count.required'   => 'تعداد پارت الزامی است.',
            'assignmentForm.part_count.min'        => 'تعداد پارت باید حداقل ۱ باشد.',
            'assignmentForm.due_date.required'     => 'تاریخ تکلیف الزامی است.',
            'requestedPartForm.subject.required'   => 'نام درس الزامی است.',
            'requestedPartForm.part_count.required'=> 'تعداد پارت الزامی است.',
            'requestedPartForm.part_count.min'     => 'تعداد پارت باید حداقل ۱ باشد.',
        ];
    }

    public function mount(AdvisingSession $session)
    {
        $this->sessionId = $session->id;
        $this->canEdit   = $session->canFillPreSession();
        $this->preSession = $session->preSession;
        $this->schoolLocked = $this->resolveSchoolLocked($session);

        if ($this->preSession) {
            $this->loadExistingData();
        }

        $this->loadStudentSubjects($session);
        $this->loadAllGradeSubjects($session);
        $this->setDateConstraints($session);
        $this->seoConfig();
    }

    public function seoConfig(): void
    {
        $this->seo()->setTitle('پیش جلسه مشاوره');
    }

    /**
     * تشخیص قفل بودن بخش‌های مدرسه‌ای: از روی هفتهٔ آزمایشی یا اطلاعات ثبت‌نام.
     */
    private function resolveSchoolLocked(AdvisingSession $session): bool
    {
        $user = $session->student?->user;
        if (!$user) {
            return false;
        }

        $trial = $user->trialWeek;
        if ($trial) {
            return ! $trial->needsClassSchedule();
        }

        $info = $user->personalInformation;
        if ($info) {
            return $info->is_graduate || ! $info->attends_school;
        }

        return false;
    }

    /** بخش‌هایی که برای دانش‌آموزِ بدون مدرسه قفل هستند. */
    private const SCHOOL_CARDS = ['exams', 'qas', 'assignments'];

    // ─── باز / بسته کردن مودال از سرور ───
    public function openModal(string $card): void
    {
        if ($this->schoolLocked && in_array($card, self::SCHOOL_CARDS, true)) {
            $this->dispatch('warning', 'چون مدرسه نمی‌روی، این بخش برای تو غیرفعال است.');
            return;
        }
        $this->openCard = $card;
    }

    public function closeModal(): void
    {
        $this->openCard = '';
    }

    protected function loadStudentSubjects(AdvisingSession $session): void
    {
        $student = $session->student;
        if (!$student) return;
        $user = $student->user;
        if (!$user) return;
        $personalInfo = $user->personalInformation;
        if (!$personalInfo) return;

        $grade   = $personalInfo->grade;
        $field   = $personalInfo->field;
        $ccField = $field ? CcField::where('slug', $field)->where('is_active', true)->first() : null;

        $ccGradeQuery = CcGrade::where('grade_number', $grade)->where('is_active', true);
        if ($ccField) {
            $ccGradeQuery->where('cc_field_id', $ccField->id);
        }
        $ccGrade = $ccGradeQuery->first();

        if ($ccGrade) {
            $ccFieldId = $ccGrade->cc_field_id ?? null;
            $this->availableSubjects = CcSubject::where('cc_grade_id', $ccGrade->id)
                ->when($ccFieldId, fn($q) => $q->where(function ($q2) use ($ccFieldId) {
                    $q2->where('cc_field_id', $ccFieldId)->orWhereNull('cc_field_id');
                }))
                ->orderBy('order')
                ->get()->toArray();
        } else {
            $ccGrades = CcGrade::where('grade_number', $grade)->where('is_active', true)->pluck('id');
            $this->availableSubjects = CcSubject::whereIn('cc_grade_id', $ccGrades)
                ->orderBy('order')->get()->toArray();
        }
    }

    protected function loadAllGradeSubjects(AdvisingSession $session): void
    {
        $student = $session->student;
        if (!$student) return;
        $user = $student->user;
        if (!$user) return;
        $personalInfo = $user->personalInformation;
        if (!$personalInfo) return;

        $grade   = (int) $personalInfo->grade;
        $field   = $personalInfo->field;
        $ccField = $field ? CcField::where('slug', $field)->where('is_active', true)->first() : null;

        $result = [];
        for ($g = 10; $g <= $grade; $g++) {
            $ccGradeQuery = CcGrade::where('grade_number', $g)->where('is_active', true);
            if ($ccField) $ccGradeQuery->where('cc_field_id', $ccField->id);
            $ccGrade = $ccGradeQuery->first()
                ?? CcGrade::where('grade_number', $g)->where('is_active', true)->first();

            if ($ccGrade) {
                $subjects = CcSubject::where('cc_grade_id', $ccGrade->id)
                    ->orderBy('order')->get()
                    ->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'type' => $s->type ?? 'specialized'])
                    ->toArray();

                if (count($subjects) > 0) {
                    $result[] = [
                        'grade_number' => $g,
                        'grade_label'  => 'پایه ' . $this->gradeLabel($g),
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
            10 => 'دهم', 11 => 'یازدهم', 12 => 'دوازدهم', default => (string) $grade,
        };
    }

    protected function setDateConstraints(AdvisingSession $session): void
    {
        $sessionDate = Carbon::parse($session->activation_date);
        $student = $session->student;

        if ($student) {
            $nextSession = AdvisingSession::where('student_id', $student->id)
                ->where('activation_date', '>', $session->activation_date)
                ->orderBy('activation_date')->first();
            $endDate = $nextSession
                ? Carbon::parse($nextSession->activation_date)->subDay()
                : $sessionDate->copy()->addDays(7);
        } else {
            $endDate = $sessionDate->copy()->addDays(7);
        }

        $this->minDate = jdate($sessionDate)->format('Y/m/d');
        $this->maxDate = jdate($endDate)->format('Y/m/d');
    }

    public function updatedExamFormCcSubjectId($value): void
    {
        $this->examForm['cc_chapter_id'] = '';
        $this->availableChapters = [];
        if ($value) {
            $subject = CcSubject::find($value);
            if ($subject) $this->examForm['subject'] = $subject->name;
            $this->availableChapters = CcChapter::where('cc_subject_id', $value)
                ->where('is_active', true)->orderBy('order')->get()->toArray();
        }
    }

    public function updatedQaFormCcSubjectId($value): void
    {
        $this->qaForm['cc_chapter_id'] = '';
        $this->availableChapters = [];
        if ($value) {
            $subject = CcSubject::find($value);
            if ($subject) $this->qaForm['subject'] = $subject->name;
            $this->availableChapters = CcChapter::where('cc_subject_id', $value)
                ->where('is_active', true)->orderBy('order')->get()->toArray();
        }
    }

    public function updatedRequestedPartFormCcSubjectId($value): void
    {
        $this->requestedPartForm['cc_chapter_id'] = '';
        $this->requestedPartChapters = [];
        if ($value) {
            $subject = CcSubject::find($value);
            if ($subject) $this->requestedPartForm['subject'] = $subject->name;
            $this->requestedPartChapters = CcChapter::where('cc_subject_id', $value)
                ->where('is_active', true)->orderBy('order')->get()->toArray();
        }
    }

    public function updatedAssignmentFormCcSubjectId($value): void
    {
        if ($value) {
            $subject = CcSubject::find($value);
            if ($subject) $this->assignmentForm['subject'] = $subject->name;
        }
    }

    protected function convertJalaliToGregorian($date): ?string
    {
        $normalized = strtr(trim((string)$date), [
            '۰'=>'0','۱'=>'1','۲'=>'2','۳'=>'3','۴'=>'4',
            '۵'=>'5','۶'=>'6','۷'=>'7','۸'=>'8','۹'=>'9',
            '٠'=>'0','١'=>'1','٢'=>'2','٣'=>'3','٤'=>'4',
            '٥'=>'5','٦'=>'6','٧'=>'7','٨'=>'8','٩'=>'9',
            '-'=>'/',
        ]);
        if (!$normalized) return null;
        try {
            return Jalalian::fromFormat('Y/m/d', $normalized)->toCarbon()->toDateString();
        } catch (\Throwable $e) {
            return null;
        }
    }

    protected function loadExistingData(): void
    {
        $this->exams          = $this->preSession->exams()->get()->toArray();
        $this->qas            = $this->preSession->qas()->get()->toArray();
        $this->assignments    = $this->preSession->assignments()->get()->toArray();
        $this->requestedParts = $this->preSession->requestedParts()->get()->toArray();
        $misc = $this->preSession->miscellaneous;
        $this->miscDescription = $misc ? $misc->description : '';
    }

    public function nextStep(): void
    {
        if ($this->currentStep < $this->totalSteps) $this->currentStep++;
    }

    public function prevStep(): void
    {
        if ($this->currentStep > 1) $this->currentStep--;
    }

    public function goToStep($step): void
    {
        if ($step >= 1 && $step <= $this->totalSteps) $this->currentStep = $step;
    }

    // ════════ مرحله ۱: امتحانات ════════

    public function addExam(): void
    {
        if (!$this->canEdit) { $this->dispatch('warning', 'امکان ویرایش وجود ندارد.'); return; }
        if ($this->schoolLocked) { $this->dispatch('warning', 'این بخش برای تو غیرفعال است.'); return; }

        if (!empty($this->examForm['cc_subject_id']) && empty($this->examForm['subject'])) {
            $s = CcSubject::find($this->examForm['cc_subject_id']);
            if ($s) $this->examForm['subject'] = $s->name;
        }

        $this->validate([
            'examForm.subject'    => 'required|string|max:255',
            'examForm.part_count' => 'required|integer|min:1',
            'examForm.exam_date'  => 'required|string',
        ], $this->messages());

        $examDate = $this->convertJalaliToGregorian($this->examForm['exam_date']);
        if (!$examDate) { $this->addError('examForm.exam_date', 'فرمت تاریخ معتبر نیست.'); return; }

        $timePerPart = $this->calcMinutes((int)$this->examForm['hours'], (int)$this->examForm['minutes']);

        AdvisingPreSessionExam::create([
            'pre_session_id' => $this->preSession->id,
            'subject'        => $this->examForm['subject'],
            'cc_subject_id'  => $this->examForm['cc_subject_id'] ?: null,
            'cc_chapter_id'  => $this->examForm['cc_chapter_id'] ?: null,
            'part_count'     => $this->examForm['part_count'],
            'time_per_part'  => $timePerPart,
            'exam_date'      => $examDate,
        ]);

        $this->resetExamForm();
        $this->loadExistingData();
        // اسکرول به بالای مودال
        $this->dispatch('scroll-modal-top');
        $this->dispatch('success', 'امتحان با موفقیت اضافه شد.');
    }

    public function deleteExam($examId): void
    {
        if (!$this->canEdit) { $this->dispatch('warning', 'امکان حذف وجود ندارد.'); return; }
        AdvisingPreSessionExam::find($examId)?->delete();
        $this->loadExistingData();
        $this->dispatch('success', 'امتحان حذف شد.');
    }

    public function resetExamForm(): void
    {
        $this->examForm = [
            'subject' => '', 'cc_subject_id' => '', 'cc_chapter_id' => '',
            'part_count' => 1, 'hours' => 0, 'minutes' => 15, 'exam_date' => '',
        ];
        $this->availableChapters = [];
    }

    // ════════ مرحله ۲: پرسش و پاسخ ════════

    public function addQa(): void
    {
        if (!$this->canEdit) { $this->dispatch('warning', 'امکان ویرایش وجود ندارد.'); return; }
        if ($this->schoolLocked) { $this->dispatch('warning', 'این بخش برای تو غیرفعال است.'); return; }

        if (!empty($this->qaForm['cc_subject_id']) && empty($this->qaForm['subject'])) {
            $s = CcSubject::find($this->qaForm['cc_subject_id']);
            if ($s) $this->qaForm['subject'] = $s->name;
        }

        $this->validate([
            'qaForm.subject'    => 'required|string|max:255',
            'qaForm.part_count' => 'required|integer|min:1',
            'qaForm.qa_date'    => 'required|string',
        ], $this->messages());

        $qaDate = $this->convertJalaliToGregorian($this->qaForm['qa_date']);
        if (!$qaDate) { $this->addError('qaForm.qa_date', 'فرمت تاریخ معتبر نیست.'); return; }

        $timePerPart = $this->calcMinutes((int)$this->qaForm['hours'], (int)$this->qaForm['minutes']);

        AdvisingPreSessionQa::create([
            'pre_session_id' => $this->preSession->id,
            'subject'        => $this->qaForm['subject'],
            'cc_subject_id'  => $this->qaForm['cc_subject_id'] ?: null,
            'cc_chapter_id'  => $this->qaForm['cc_chapter_id'] ?: null,
            'part_count'     => $this->qaForm['part_count'],
            'time_per_part'  => $timePerPart,
            'qa_date'        => $qaDate,
        ]);

        $this->resetQaForm();
        $this->loadExistingData();
        $this->dispatch('scroll-modal-top');
        $this->dispatch('success', 'پرسش و پاسخ با موفقیت اضافه شد.');
    }

    public function deleteQa($qaId): void
    {
        if (!$this->canEdit) { $this->dispatch('warning', 'امکان حذف وجود ندارد.'); return; }
        AdvisingPreSessionQa::find($qaId)?->delete();
        $this->loadExistingData();
        $this->dispatch('success', 'پرسش و پاسخ حذف شد.');
    }

    public function resetQaForm(): void
    {
        $this->qaForm = [
            'subject' => '', 'cc_subject_id' => '', 'cc_chapter_id' => '',
            'part_count' => 1, 'hours' => 0, 'minutes' => 15, 'qa_date' => '',
        ];
        $this->availableChapters = [];
    }

    // ════════ مرحله ۳: تکالیف ════════

    public function addAssignment(): void
    {
        if (!$this->canEdit) { $this->dispatch('warning', 'امکان ویرایش وجود ندارد.'); return; }
        if ($this->schoolLocked) { $this->dispatch('warning', 'این بخش برای تو غیرفعال است.'); return; }

        if (!empty($this->assignmentForm['cc_subject_id']) && empty($this->assignmentForm['subject'])) {
            $s = CcSubject::find($this->assignmentForm['cc_subject_id']);
            if ($s) $this->assignmentForm['subject'] = $s->name;
        }

        $this->validate([
            'assignmentForm.subject'    => 'required|string|max:255',
            'assignmentForm.part_count' => 'required|integer|min:1',
            'assignmentForm.due_date'   => 'required|string',
        ], $this->messages());

        $dueDate = $this->convertJalaliToGregorian($this->assignmentForm['due_date']);
        if (!$dueDate) { $this->addError('assignmentForm.due_date', 'فرمت تاریخ معتبر نیست.'); return; }

        $timePerPart = $this->calcMinutes((int)$this->assignmentForm['hours'], (int)$this->assignmentForm['minutes']);

        AdvisingPreSessionAssignment::create([
            'pre_session_id' => $this->preSession->id,
            'subject'        => $this->assignmentForm['subject'],
            'cc_subject_id'  => $this->assignmentForm['cc_subject_id'] ?: null,
            'part_count'     => $this->assignmentForm['part_count'],
            'time_per_part'  => $timePerPart,
            'due_date'       => $dueDate,
        ]);

        $this->resetAssignmentForm();
        $this->loadExistingData();
        $this->dispatch('scroll-modal-top');
        $this->dispatch('success', 'تکلیف با موفقیت اضافه شد.');
    }

    public function deleteAssignment($assignmentId): void
    {
        if (!$this->canEdit) { $this->dispatch('warning', 'امکان حذف وجود ندارد.'); return; }
        AdvisingPreSessionAssignment::find($assignmentId)?->delete();
        $this->loadExistingData();
        $this->dispatch('success', 'تکلیف حذف شد.');
    }

    public function resetAssignmentForm(): void
    {
        $this->assignmentForm = [
            'subject' => '', 'cc_subject_id' => '',
            'part_count' => 1, 'hours' => 0, 'minutes' => 15, 'due_date' => '',
        ];
    }

    // ════════ مرحله ۵: پارت درخواستی ════════

    public function addRequestedPart(): void
    {
        if (!$this->canEdit) { $this->dispatch('warning', 'امکان ویرایش وجود ندارد.'); return; }

        if (!empty($this->requestedPartForm['cc_subject_id']) && empty($this->requestedPartForm['subject'])) {
            $s = CcSubject::find($this->requestedPartForm['cc_subject_id']);
            if ($s) $this->requestedPartForm['subject'] = $s->name;
        }

        $this->validate([
            'requestedPartForm.subject'    => 'required|string|max:255',
            'requestedPartForm.part_count' => 'required|integer|min:1',
        ], $this->messages());

        $timePerPart = $this->calcMinutes(
            (int)$this->requestedPartForm['hours'],
            (int)$this->requestedPartForm['minutes']
        );

        AdvisingPreSessionRequestedPart::create([
            'pre_session_id' => $this->preSession->id,
            'subject'        => $this->requestedPartForm['subject'],
            'cc_subject_id'  => $this->requestedPartForm['cc_subject_id'] ?: null,
            'cc_chapter_id'  => $this->requestedPartForm['cc_chapter_id'] ?: null,
            'description'    => $this->requestedPartForm['description'] ?: null,
            'part_count'     => $this->requestedPartForm['part_count'],
            'time_per_part'  => $timePerPart,
        ]);

        $this->resetRequestedPartForm();
        $this->loadExistingData();
        $this->dispatch('scroll-modal-top');
        $this->dispatch('success', 'پارت درخواستی با موفقیت اضافه شد.');
    }

    public function deleteRequestedPart($partId): void
    {
        if (!$this->canEdit) { $this->dispatch('warning', 'امکان حذف وجود ندارد.'); return; }
        AdvisingPreSessionRequestedPart::find($partId)?->delete();
        $this->loadExistingData();
        $this->dispatch('success', 'پارت درخواستی حذف شد.');
    }

    public function resetRequestedPartForm(): void
    {
        $this->requestedPartForm = [
            'subject' => '', 'cc_subject_id' => '', 'cc_chapter_id' => '',
            'description' => '', 'part_count' => 1, 'hours' => 0, 'minutes' => 15,
        ];
        $this->requestedPartChapters = [];
    }

    // ════════ مرحله ۴: متفرقه ════════

    public function saveMiscellaneous(): void
    {
        if (!$this->canEdit) { $this->dispatch('warning', 'امکان ویرایش وجود ندارد.'); return; }
        AdvisingPreSessionMisc::updateOrCreate(
            ['pre_session_id' => $this->preSession->id],
            ['description'    => $this->miscDescription]
        );
        $this->closeModal();
        $this->dispatch('success', 'توضیحات متفرقه ذخیره شد.');
    }

    // ════════ ثبت نهایی ════════

    public function finalSubmit(\App\Services\TrialWeekService $trialService): void
    {
        if (!$this->canEdit) { $this->dispatch('warning', 'امکان ثبت وجود ندارد.'); return; }
        if ($this->miscDescription) $this->saveMiscellaneous();
        $this->preSession->update(['status' => 'completed']);
        $this->dispatch('success', 'پیش‌جلسه با موفقیت ثبت شد.');

        // دانش‌آموز آزمایشی: پیشروی خودکار + بازگشت به راهنما (بدون نیاز به تایید پشتیبان).
        $trial = \Illuminate\Support\Facades\Auth::user()?->trialWeek;
        if ($trial) {
            $this->maybeAdvanceTrial($trial, $trialService);
            redirect()->route('client.profile.trial.guide');
            return;
        }

        redirect()->route('client.profile.consultation.sessions');
    }

    /**
     * اگر هم پیش‌جلسه تکمیل شده و هم برنامهٔ کلاسی نهایی شده باشد،
     * هفتهٔ آزمایشی را خودکار به مرحلهٔ «ساخت برنامه» می‌برد.
     */
    private function maybeAdvanceTrial(\App\Models\TrialWeek $trial, \App\Services\TrialWeekService $trialService): void
    {
        if ($trial->status !== \App\Models\TrialWeek::STATUS_CLASSIFICATION_DONE) {
            return;
        }

        // دانش‌آموزی که مدرسه نمی‌رود یا فارغ‌التحصیل است، نیازی به برنامه کلاسی ندارد.
        if (! $trial->needsClassSchedule()) {
            $trialService->completePreSession($trial);
            return;
        }

        $hasFinalizedSchedule = \App\Models\ClassSchedule::where('student_id', $trial->student_id)
            ->where('is_finalized', true)
            ->exists();

        if ($hasFinalizedSchedule) {
            $trialService->completePreSession($trial);
        }
    }

    public function getAvailableDates(): array
    {
        if (!$this->minDate || !$this->maxDate) return [];
        $jalaliDayNames = ['شنبه','یکشنبه','دوشنبه','سه‌شنبه','چهارشنبه','پنج‌شنبه','جمعه'];
        $dates = [];
        try {
            $start   = Jalalian::fromFormat('Y/m/d', $this->minDate)->toCarbon();
            $end     = Jalalian::fromFormat('Y/m/d', $this->maxDate)->toCarbon();
            $current = $start->copy();
            while ($current->lte($end)) {
                $jalali  = jdate($current);
                $dates[] = [
                    'value'      => $jalali->format('Y/m/d'),
                    'day_name'   => $jalaliDayNames[$jalali->getDayOfWeek()] ?? '',
                    'day'        => $jalali->getDay(),
                    'month_name' => $jalali->format('%B'),
                ];
                $current->addDay();
            }
        } catch (\Throwable $e) {}
        return $dates;
    }

    public function render()
    {
        $session = AdvisingSession::with(['student.user.personalInformation', 'advisor'])
            ->find($this->sessionId);

        $stepTitles = [
            1 => 'امتحانات',
            2 => 'پرسش و پاسخ کلاسی',
            3 => 'تکالیف',
            4 => 'پارت درخواستی',
            5 => 'متفرقه',
            6 => 'نمایش نهایی',
        ];

        return view('livewire.client.profile.consultation.pre-session-wizard', [
            'session'        => $session,
            'stepTitles'     => $stepTitles,
            'availableDates' => $this->getAvailableDates(),
        ])->layout('layouts.client.app');
    }
}
