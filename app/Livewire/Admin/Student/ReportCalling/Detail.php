<?php

namespace App\Livewire\Admin\Student\ReportCalling;

use App\Models\User;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;
use Morilog\Jalali\CalendarUtils;

class Detail extends Component
{
    use SEOTools,WithPagination;
    public $studentName;
    public $studentId;
    public ?string $title = null;
    public ?string $description = null;
    public string $answer = 'mather';
    public ?string $call_date = null;
    protected $listeners = [
        'jalaliDateChanged' => 'setJalaliDate',
    ];

    public function setJalaliDate($date)
    {
        $this->call_date = $date;
        // اگر خواستی بلافاصله اعتبارسنجی‌ش کنی:
        $this->validateOnly('call_date');
    }
    private function normalizePersianNumbers(string $input): string
    {
        $persian = ['۰','۱','۲','۳','۴','۵','۶','۷','۸','۹'];
        $latin   = ['0','1','2','3','4','5','6','7','8','9'];
        return str_replace($persian, $latin, $input);
    }
    protected function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'answer' => ['required', 'in:mather,father,student'],
            'call_date' => ['required', 'regex:/^[0-9۰-۹]{4}\/[0-9۰-۹]{1,2}\/[0-9۰-۹]{1,2}$/u'],

        ];
    }

    protected function messages(): array
    {
        return [
            '*.string' => ' باید متن باشد.',
            '*.max' => ' نباید بیشتر از :max کاراکتر باشد.',
            'description.string' => 'توضیحات باید متن باشد.',
            '*.required' => 'فیلد الزامی است',
            'answer.in' => 'پاسخ‌دهنده انتخاب‌شده معتبر نیست.',
            'call_date.required' => 'تاریخ تماس الزامی است.',
            'call_date.regex' => 'فرمت تاریخ باید به شکل صحیح شمسی باشد. مثال: ۱۴۰۴/۰۵/۱۰.',
        ];
    }

    public function mount(User $student)
    {
        $this->studentId = $student->id;
        $this->studentName = $student->name;
    }
    public function updated($propertyName): void
    {
        $this->validateOnly($propertyName);
    }

    public function submit(): void
    {
        $validated = $this->validate();
        $normalizedJalali = $this->normalizePersianNumbers(trim($this->call_date));
        [$gy, $gm, $gd] = CalendarUtils::toGregorian(...explode('/', $normalizedJalali));
        $executionDate = sprintf('%04d-%02d-%02d', $gy, $gm, $gd);
        \App\Models\ReportCallingStudent::create([
            'student_id' => $this->studentId,
            'admin_id' => auth()->id(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'answer' => $validated['answer'],
            'call_date' => $executionDate,
        ]);

        $this->reset(['title', 'description', 'answer', 'call_date']);
        $this->answer = 'mather'; // default دوباره تنظیم بشه

        $this->dispatch('success','با موفقیت اضافه شد ');
    }


    public function delete($report_id)
    {
        \App\Models\ReportCallingStudent::query()->where('id', $report_id)->delete();
        $this->dispatch('success', 'با موفقیت حذف شد');
    }
    public function render()
    {
        $studentId = Auth::user()->student->id ?? null;

        $ReportCallingStudent = \App\Models\ReportCallingStudent::query()->where('student_id', $this->studentId)->latest()->paginate(10);
        return view('livewire.admin.student.report-calling.detail',['ReportCallingStudent'=>$ReportCallingStudent])->layout('layouts.admin.app');
    }
}
