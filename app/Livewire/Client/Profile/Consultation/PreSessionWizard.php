<?php


namespace App\Livewire\Client\Profile\Consultation;


use App\Models\AdvisingSession;

use App\Models\AdvisingPreSession;

use App\Models\AdvisingPreSessionExam;

use App\Models\AdvisingPreSessionAssignment;

use App\Models\AdvisingPreSessionQa;

use App\Models\AdvisingPreSessionMisc;

use App\Models\Student;

use Illuminate\Support\Facades\Validator;

use Livewire\Component;


class PreSessionWizard extends Component

{

    public $sessionId;

    public $preSession;

    public $canEdit = true;


    // مرحله فعلی wizard (1: امتحانات، 2: پرسش و پاسخ، 3: تکالیف، 4: متفرقه، 5: نمایش نهایی)

    public $currentStep = 1;

    public $totalSteps = 5;


    // داده‌های مرحله 1 - امتحانات

    public $exams = [];

    public $examForm = [

        'subject' => '',

        'part_count' => 1,

        'time_per_part' => 60,

        'exam_date' => '',

    ];


    // داده‌های مرحله 2 - پرسش و پاسخ

    public $qas = [];

    public $qaForm = [

        'subject' => '',

        'part_count' => 1,

        'time_per_part' => 60,

        'qa_date' => '',

    ];


    // داده‌های مرحله 3 - تکالیف

    public $assignments = [];

    public $assignmentForm = [

        'subject' => '',

        'part_count' => 1,

        'time_per_part' => 60,

        'due_date' => '',

    ];


    // داده‌های مرحله 4 - متفرقه

    public $miscDescription = '';


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


        $this->validate([

            'examForm.subject' => 'required|string|max:255',

            'examForm.part_count' => 'required|integer|min:1',

            'examForm.time_per_part' => 'required|integer|min:1',

            'examForm.exam_date' => 'required|date',

        ], $this->messages());


        AdvisingPreSessionExam::create([

            'pre_session_id' => $this->preSession->id,

            'subject' => $this->examForm['subject'],

            'part_count' => $this->examForm['part_count'],

            'time_per_part' => $this->examForm['time_per_part'],

            'exam_date' => $this->examForm['exam_date'],

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

            'part_count' => 1,

            'time_per_part' => 60,

            'exam_date' => '',

        ];

    }


    // ==================== مرحله 2: پرسش و پاسخ ====================


    public function addQa()

    {

        if (!$this->canEdit) {

            $this->dispatch('warning', 'امکان ویرایش وجود ندارد.');

            return;

        }


        $this->validate([

            'qaForm.subject' => 'required|string|max:255',

            'qaForm.part_count' => 'required|integer|min:1',

            'qaForm.time_per_part' => 'required|integer|min:1',

            'qaForm.qa_date' => 'required|date',

        ], $this->messages());


        AdvisingPreSessionQa::create([

            'pre_session_id' => $this->preSession->id,

            'subject' => $this->qaForm['subject'],

            'part_count' => $this->qaForm['part_count'],

            'time_per_part' => $this->qaForm['time_per_part'],

            'qa_date' => $this->qaForm['qa_date'],

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

            'part_count' => 1,

            'time_per_part' => 60,

            'qa_date' => '',

        ];

    }


    // ==================== مرحله 3: تکالیف ====================


    public function addAssignment()

    {

        if (!$this->canEdit) {

            $this->dispatch('warning', 'امکان ویرایش وجود ندارد.');

            return;

        }


        $this->validate([

            'assignmentForm.subject' => 'required|string|max:255',

            'assignmentForm.part_count' => 'required|integer|min:1',

            'assignmentForm.time_per_part' => 'required|integer|min:1',

            'assignmentForm.due_date' => 'required|date',

        ], $this->messages());


        AdvisingPreSessionAssignment::create([

            'pre_session_id' => $this->preSession->id,

            'subject' => $this->assignmentForm['subject'],

            'part_count' => $this->assignmentForm['part_count'],

            'time_per_part' => $this->assignmentForm['time_per_part'],

            'due_date' => $this->assignmentForm['due_date'],

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

            'part_count' => 1,

            'time_per_part' => 60,

            'due_date' => '',

        ];

    }


    // ==================== مرحله 4: متفرقه ====================


    public function saveMiscellaneous()

    {

        if (!$this->canEdit) {

            $this->dispatch('warning', 'امکان ویرایش وجود ندارد.');

            return;

        }


        $misc = AdvisingPreSessionMisc::updateOrCreate(

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


    public function render()

    {

        $session = AdvisingSession::with(['student.user.personalInformation', 'advisor'])->find($this->sessionId);


        $stepTitles = [

            1 => 'امتحانات',

            2 => 'پرسش و پاسخ کلاسی',

            3 => 'تکالیف',

            4 => 'متفرقه',

            5 => 'نمایش نهایی',

        ];


        return view('livewire.client.profile.consultation.pre-session-wizard', [

            'session' => $session,

            'stepTitles' => $stepTitles,

        ])->layout('layouts.client.app');

    }

}
