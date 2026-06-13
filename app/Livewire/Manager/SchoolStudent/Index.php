<?php

namespace App\Livewire\Manager\SchoolStudent;

use App\Exports\SchoolStudentTemplateExport;
use App\Imports\SchoolStudentsImport;
use App\Models\Admin;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    use WithPagination, WithFileUploads, SEOTools;

    // مدرسهٔ جاری (route-model-bound)
    public School $school;
    public $search = '';

    // فرم دانش‌آموز (دستی)
    public $studentId;
    public $form_advisor_id;
    public $name;
    public $mobile;
    public $national_code;
    public $father_mobile;
    public $mother_mobile;
    public $grade;
    public $field;
    public $educational_pursuer;

    // ایمپورت اکسل
    public $excel_file;
    public array $importValidRows = [];
    public array $importInvalidRows = [];
    public bool $importPreviewReady = false;

    public function mount(School $school): void
    {
        $this->school = $school;
        $this->seo()->setTitle('دانش‌آموزان مدرسهٔ ' . $school->name);

        // اگر مدرسه تنها ۱ مشاور داشت، به‌صورت پیش‌فرض انتخاب شود.
        $this->autoAssignAdvisor();
    }

    protected function autoAssignAdvisor(): void
    {
        $advisors = $this->school->advisors;
        if ($advisors->count() === 1) {
            $this->form_advisor_id = (string) $advisors->first()->id;
        }
    }

    public function submit(array $formData): void
    {
        // مشاور انتخابی از prop خوانده می‌شود (select تکی).
        $formData['advisor_id'] = $this->form_advisor_id ?: null;

        $rules = [
            'name'                => 'required|string|max:255',
            'mobile'              => 'required|regex:/^09\d{9}$/|unique:users,mobile',
            'national_code'       => 'required|digits:10|unique:students,national_code',
            'father_mobile'       => 'nullable|regex:/^0\d{10}$/',
            'mother_mobile'       => 'nullable|regex:/^0\d{10}$/',
            'grade'               => 'required|in:9,10,11,12',
            'field'               => 'nullable|required_unless:grade,9|in:math,experimental,human',
            'educational_pursuer' => 'required|in:father,mother',
            'advisor_id'          => 'nullable|exists:admins,id',
        ];

        if (!empty($this->studentId)) {
            $rules['mobile'] .= ',' . User::query()->where('id', Student::find($this->studentId)?->user_id)->value('id');
            $rules['national_code'] .= ',' . $this->studentId;
        }

        $messages = [
            '*.required'             => 'فیلد ضروری است',
            'field.required_unless'  => 'برای پایه‌های دهم تا دوازدهم، رشته الزامی است',
            'mobile.regex'           => 'فرمت تلفن دانش‌آموز نادرست است',
            'mobile.unique'          => 'این تلفن قبلاً در سامانه ثبت شده',
            'national_code.digits'   => 'کدملی باید ۱۰ رقم باشد',
            'national_code.unique'   => 'این کدملی قبلاً ثبت شده',
            'father_mobile.regex'    => 'فرمت تلفن پدر نادرست است',
            'mother_mobile.regex'    => 'فرمت تلفن مادر نادرست است',
        ];

        Validator::make($formData, $rules, $messages)->validate();

        // اعتبارسنجی: پیگیر آموزشی باید تلفن متناظر داشته باشد
        if ($formData['educational_pursuer'] === 'father' && empty($formData['father_mobile'])) {
            $this->addError('father_mobile', 'وقتی پیگیر «پدر» است، تلفن پدر باید پر باشد');
            return;
        }
        if ($formData['educational_pursuer'] === 'mother' && empty($formData['mother_mobile'])) {
            $this->addError('mother_mobile', 'وقتی پیگیر «مادر» است، تلفن مادر باید پر باشد');
            return;
        }

        // مشاور انتخابی باید جزو مشاوران همین مدرسه باشد.
        $advisorId = $formData['advisor_id'] ?? null;
        $schoolAdvisorIds = $this->school->advisors->pluck('id');
        if ($advisorId && !$schoolAdvisorIds->contains((int) $advisorId)) {
            $this->addError('form_advisor_id', 'مشاور انتخابی متعلق به این مدرسه نیست');
            return;
        }
        if (empty($advisorId) && $schoolAdvisorIds->count() === 1) {
            $advisorId = $schoolAdvisorIds->first();
        }

        // برای پایه نهم رشته معنا ندارد.
        $field = $formData['grade'] === '9' ? null : ($formData['field'] ?? null);

        DB::transaction(function () use ($formData, $advisorId, $field) {
            if ($this->studentId) {
                $student = Student::findOrFail($this->studentId);
                $student->user?->update([
                    'name'   => $formData['name'],
                    'mobile' => $formData['mobile'],
                ]);
                $student->update([
                    'school_id'           => $this->school->id,
                    'advisor_id'          => $advisorId,
                    'national_code'       => $formData['national_code'],
                    'father_mobile'       => $formData['father_mobile'] ?? null,
                    'mother_mobile'       => $formData['mother_mobile'] ?? null,
                    'grade'               => $formData['grade'],
                    'field'               => $field,
                    'educational_pursuer' => $formData['educational_pursuer'],
                ]);
            } else {
                $user = User::create([
                    'name'     => $formData['name'],
                    'mobile'   => $formData['mobile'],
                    'password' => Hash::make($formData['national_code']),
                ]);

                Student::create([
                    'user_id'             => $user->id,
                    'school_id'           => $this->school->id,
                    'advisor_id'          => $advisorId,
                    'national_code'       => $formData['national_code'],
                    'father_mobile'       => $formData['father_mobile'] ?? null,
                    'mother_mobile'       => $formData['mother_mobile'] ?? null,
                    'grade'               => $formData['grade'],
                    'field'               => $field,
                    'educational_pursuer' => $formData['educational_pursuer'],
                ]);
            }
        });

        $this->resetForm();
        $this->dispatch('success', 'دانش‌آموز با موفقیت ذخیره شد');
    }

    public function edit(int $studentId): void
    {
        $student = Student::with('user')->find($studentId);
        if (!$student) {
            return;
        }
        $this->studentId                  = $student->id;
        $this->form_advisor_id            = $student->advisor_id ? (string) $student->advisor_id : null;
        $this->name                       = $student->user?->name;
        $this->mobile                     = $student->user?->mobile;
        $this->national_code              = $student->national_code;
        $this->father_mobile              = $student->father_mobile;
        $this->mother_mobile              = $student->mother_mobile;
        $this->grade                      = $student->grade;
        $this->field                      = $student->field;
        $this->educational_pursuer        = $student->educational_pursuer;
    }

    public function delete(int $studentId): void
    {
        $student = Student::find($studentId);
        if (!$student) {
            return;
        }
        DB::transaction(function () use ($student) {
            $user = $student->user;
            $student->delete();
            $user?->delete();
        });
        $this->dispatch('success', 'دانش‌آموز حذف شد');
    }

    public function resetForm(): void
    {
        $this->reset([
            'studentId', 'form_advisor_id',
            'name', 'mobile', 'national_code', 'father_mobile', 'mother_mobile',
            'grade', 'field', 'educational_pursuer',
        ]);
        $this->autoAssignAdvisor();
    }

    public function downloadSample()
    {
        return Excel::download(new SchoolStudentTemplateExport(), 'school_students_sample.xlsx');
    }

    public function previewImport(): void
    {
        Validator::make(
            ['excel_file' => $this->excel_file],
            [
                'excel_file' => 'required|file|mimes:xlsx,xls|max:5120',
            ],
            ['*.required' => 'فیلد ضروری است', 'excel_file.mimes' => 'فقط فرمت xlsx/xls پذیرفته می‌شود']
        )->validate();

        $import = new SchoolStudentsImport($this->school->id);
        Excel::import($import, $this->excel_file->getRealPath());

        $this->importValidRows = $import->validRows;
        $this->importInvalidRows = $import->invalidRows;
        $this->importPreviewReady = true;
    }

    public function confirmImport(): void
    {
        if (!$this->importPreviewReady || empty($this->importValidRows)) {
            $this->dispatch('warning', 'ردیف معتبری برای ذخیره وجود ندارد');
            return;
        }

        // بازسازی import با همان داده‌های معتبر و save
        $import = new SchoolStudentsImport($this->school->id);
        $import->validRows = $this->importValidRows;
        $count = $import->save();

        $this->reset(['excel_file', 'importValidRows', 'importInvalidRows', 'importPreviewReady']);
        $this->dispatch('success', "{$count} دانش‌آموز با موفقیت ایمپورت شد");
    }

    public function cancelImport(): void
    {
        $this->reset(['excel_file', 'importValidRows', 'importInvalidRows', 'importPreviewReady']);
    }

    public function render()
    {
        // مشاوران فعال‌شدهٔ همین مدرسه برای انتخاب در فرم/ایمپورت
        $advisors = $this->school->advisors()->get(['admins.id', 'admins.name']);

        $students = Student::query()
            ->with(['user', 'school', 'advisor'])
            ->where('school_id', $this->school->id)
            ->when($this->search, function ($q) {
                $q->where(function ($q) {
                    $q->where('national_code', 'like', "%{$this->search}%")
                      ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$this->search}%")
                                                      ->orWhere('mobile', 'like', "%{$this->search}%"));
                });
            })
            ->latest()
            ->paginate(15);

        return view('livewire.manager.school-student.index', [
            'students' => $students,
            'advisors' => $advisors,
        ])->layout('layouts.manager.app');
    }
}
