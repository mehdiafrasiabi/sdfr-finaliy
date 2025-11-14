<?php

namespace App\Livewire\Client\Profile;

use App\Models\Report as ReportModel;
use App\Traits\UploadFile;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;
use Morilog\Jalali\CalendarUtils;

class Report extends Component
{
    use WithFileUploads, UploadFile,WithPagination,SEOTools;

    public $report_file, $complacent;
    public $required_parts = '';
    public $done_parts = '';
    public $required_tests = '';
    public $done_tests = '';
    public $phone_study_hours = '';
    public $phone_nonstudy_hours = '';
    public $description = '';
    public function mount()
    {
        $this->seo()->setTitle('گزارش های روزانه من');

    }
    protected $listeners = [
        'jalaliDateChanged' => 'setJalaliDate',
    ];

    public function setJalaliDate($date)
    {
        // اگر خواستی بلافاصله اعتبارسنجی‌ش کنی:
        $this->validateOnly('jalali_date');
    }

    public function submit()
    {
        $this->validate([
            'required_parts' => ['required', 'integer', 'between:0,10'],
            'done_parts' => ['required', 'integer', 'between:0,10'],
            'required_tests' => ['nullable', 'integer', 'min:0'],
            'done_tests' => ['nullable', 'integer', 'min:0'],
            'phone_study_hours' => ['required', 'integer', 'between:0,24'],
            'phone_nonstudy_hours' => ['required', 'integer', 'between:0,24'],
            'report_file' => ['nullable', 'file', 'max:10240', 'mimes:jpg,jpeg,png,webp'],
            'description' => ['nullable', 'string', 'max:1000'],
            'complacent' => ['required', Rule::in([0,1, '0', '1'])],
        ], [

            'required_parts.required' => 'تعداد پارت موظفی امروز را انتخاب کن.',
            'required_parts.integer' => 'تعداد پارت موظفی باید عدد باشد.',
            'required_parts.between' => 'تعداد پارت موظفی باید بین ۰ تا ۱۰ باشد.',
            'done_parts.required' => 'تعداد پارت انجام‌شده امروز را انتخاب کن.',
            'done_parts.integer' => 'تعداد پارت انجام‌شده باید عدد باشد.',
            'done_parts.between' => 'تعداد پارت انجام‌شده باید بین ۰ تا ۱۰ باشد.',

            'required_tests.integer' => 'تعداد کل تست‌های موظفی باید عدد باشد.',
            'required_tests.min' => 'تعداد کل تست‌های موظفی نمی‌تواند منفی باشد.',
            'done_tests.integer' => 'تعداد تست‌های زده‌شده باید عدد باشد.',
            'done_tests.min' => 'تعداد تست‌های زده‌شده نمی‌تواند منفی باشد.',
            'phone_study_hours.required' => 'ساعات درگیر با گوشی (درسی) را وارد کن.',
            'phone_study_hours.integer' => 'ساعات درگیر با گوشی (درسی) باید عدد باشد.',
            'phone_study_hours.between' => 'ساعات درسی باید بین ۰ تا ۲۴ باشد.',
            'phone_nonstudy_hours.required' => 'ساعات درگیر با گوشی (غیر درسی) را وارد کن.',
            'phone_nonstudy_hours.integer' => 'ساعات درگیر با گوشی (غیر درسی) باید عدد باشد.',
            'phone_nonstudy_hours.between' => 'ساعات غیر درسی باید بین ۰ تا ۲۴ باشد.',
            'report_file.mimes' => 'فرمت فایل مجاز نیست.',
            'report_file.max' => 'حجم فایل نباید بیشتر از ۱۰ مگابایت باشد.',
            'description.string' => 'توضیحات باید متن باشد.',
            'description.max' => 'توضیحات نمی‌تواند بیشتر از ۱۰۰۰ کاراکتر باشد.',
            'complacent.required' => 'رضایت شما الزامی است.',
            'complacent.in' => 'گزینهٔ معتبر را انتخاب کن.',
        ]);

        if (!auth()->user()->student) {
            $this->dispatch('warning', 'شما به عنوان دانش‌آموز ثبت نشده‌اید.');
            return;
        }

        $student = Auth::user()->student;

        // اینجا بررسی می‌کنیم که آیا دانش‌آموز پشتیبان دارد یا خیر
        if (!$student->supporterStudent) {
            // می‌توانید یک پیام خطا نمایش دهید
            $this->dispatch('warning', 'برای شما پشتیبان تعیین نشده است.');
            return;
        }

        $admin = $student->supporterStudent; // به جای admin از supporterStudent استفاده کنید

        $filePath = null;

        if ($this->report_file) {
            $filePath = $this->uploadImageInWebpFormatProfileReport(
                $this->report_file,
                $student->id,
                600,
                600,
                'reportsDaily'
            );
        }


        ReportModel::create([
            'student_id' => $student->id,
            'admin_id' => $admin->id,
            'required_parts' => $this->required_parts,
            'done_parts' => $this->done_parts,
            'required_tests' => $this->required_tests,
            'done_tests' => $this->done_tests,
            'phone_study_hours' => $this->phone_study_hours,
            'phone_nonstudy_hours' => $this->phone_nonstudy_hours,
            'description' => $this->description,
            'complacent' => $this->complacent,
            'report_file' => $filePath,
        ]);

        $this->reset([
            'report_file',
            'complacent',
            'required_parts',
            'done_parts',
            'required_tests',
            'done_tests',
            'phone_study_hours',
            'phone_nonstudy_hours',
            'description',
        ]);

        $this->mount(); // اگر می‌خواهی تاریخ مجدداً مقداردهی اولیه شمسی بگیرد

        $this->dispatch('success', 'گزارش با موفقیت ارسال شد.');
    }

    public function render()
    {
        $studentId = Auth::user()->student->id ?? null;
        $reports = ReportModel::query()->where('student_id',$studentId)->latest()->paginate(10);
        return view('livewire.client.profile.report',['reports'=>$reports])
            ->layout('layouts.client.app');
    }
}
