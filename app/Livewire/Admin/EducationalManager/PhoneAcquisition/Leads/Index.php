<?php

namespace App\Livewire\Admin\EducationalManager\PhoneAcquisition\Leads;

use App\Models\City;
use App\Models\PhoneLead;
use App\Models\State;
use App\Models\TrialWeek;
use App\Traits\NormalizesDigits;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

/**
 * مدیر آموزشی — افزودن (دستی و گروهی با اکسل) و فهرست شماره‌های جذب تلفنی.
 */
class Index extends Component
{
    use WithPagination, WithFileUploads, NormalizesDigits;

    public string $search = '';
    public string $statusFilter = '';

    // فرم افزودن شماره
    public bool $showForm = false;
    public string $fullName = '';
    public string $mobile = '';
    public $grade = '';
    public string $field = '';
    public $stateId = '';
    public $cityId = '';

    // ایمپورت اکسل
    public bool $showImport = false;
    public $excelFile = null;

    public $states = [];
    public $cities = [];

    public function mount(): void
    {
        $this->states = State::query()->select('id', 'name')->orderBy('name')->get();
        $this->cities = collect();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedStateId($value): void
    {
        $this->cityId = '';
        $this->cities = $value
            ? City::query()->where('state_id', $value)->select('id', 'name')->orderBy('name')->get()
            : collect();
    }

    public function openForm(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->resetForm();
    }

    protected function resetForm(): void
    {
        $this->reset(['fullName', 'mobile', 'grade', 'field', 'stateId', 'cityId', 'showForm']);
        $this->cities = collect();
        $this->resetErrorBag();
    }

    public function addLead(): void
    {
        $this->mobile = $this->convertToEnglishDigits($this->mobile);
        $this->mobile = preg_replace('/\s+/', '', $this->mobile);

        $this->validate([
            'fullName' => ['nullable', 'string', 'max:150'],
            'mobile'   => ['required', 'string', 'regex:/^09\d{9}$/'],
            'grade'    => ['nullable', 'in:9,10,11,12,13'],
            'field'    => ['nullable', 'in:math,experimental,human'],
            'stateId'  => ['nullable', 'exists:states,id'],
            'cityId'   => ['nullable', 'exists:cities,id'],
        ], [
            'mobile.required' => 'شمارهٔ موبایل اجباری است.',
            'mobile.regex'    => 'شمارهٔ موبایل باید با ۰۹ شروع شود و ۱۱ رقم باشد.',
        ]);

        // هشدار تکراری (بدون جلوگیری از ثبت)
        $duplicate = PhoneLead::where('mobile', $this->mobile)->exists();

        PhoneLead::create([
            'full_name'  => $this->fullName ?: null,
            'mobile'     => $this->mobile,
            'grade'      => $this->grade ?: null,
            'field'      => $this->field ?: null,
            'state_id'   => $this->stateId ?: null,
            'city_id'    => $this->cityId ?: null,
            'created_by' => Auth::guard('admin')->id(),
        ]);

        $this->resetForm();

        if ($duplicate) {
            $this->dispatch('warning', 'این شماره از قبل در سیستم وجود دارد (تکراری) — با این حال ثبت شد.');
        } else {
            $this->dispatch('success', 'شماره با موفقیت اضافه شد.');
        }
    }

    public function toggleImport(): void
    {
        $this->showImport = ! $this->showImport;
        $this->excelFile  = null;
        $this->resetErrorBag();
    }

    /**
     * ایمپورت گروهی شماره‌ها از فایل اکسل.
     * ستون‌ها (با یا بدون سطر عنوان): نام | موبایل | پایه | رشته
     */
    public function importExcel(): void
    {
        $this->validate([
            'excelFile' => ['required', 'file', 'mimes:xlsx,xls,csv,txt', 'max:5120'],
        ], [
            'excelFile.required' => 'فایل اکسل را انتخاب کنید.',
            'excelFile.mimes'    => 'فقط فایل xlsx، xls یا csv مجاز است.',
        ]);

        $rows = Excel::toCollection(null, $this->excelFile->getRealPath())->first() ?? collect();

        $gradeMap = array_flip(TrialWeek::GRADE_LABELS); // 'نهم' => 9 ...
        $fieldMap = array_flip(TrialWeek::FIELD_LABELS); // 'ریاضی' => 'math' ...

        $created = 0;
        $duplicate = 0;
        $skipped = 0;
        $adminId = Auth::guard('admin')->id();

        foreach ($rows as $i => $row) {
            $cells = $row->values()->all();

            $name   = isset($cells[0]) ? trim((string) $cells[0]) : '';
            $mobile = isset($cells[1]) ? $this->convertToEnglishDigits((string) $cells[1]) : '';
            $mobile = preg_replace('/\D+/', '', $mobile);

            // سطر عنوان یا خالی را رد کن
            if ($mobile === '' || ! preg_match('/^09\d{9}$/', $mobile)) {
                // اگر اولین سطر عنوان بود، آن را «نادیده» می‌گیریم نه «ردشده»
                if ($i === 0 && ! ctype_digit($mobile)) {
                    continue;
                }
                $skipped++;
                continue;
            }

            $gradeRaw = isset($cells[2]) ? trim((string) $cells[2]) : '';
            $grade = match (true) {
                ctype_digit($gradeRaw) && in_array((int) $gradeRaw, [9, 10, 11, 12, 13], true) => (int) $gradeRaw,
                isset($gradeMap[$gradeRaw]) => $gradeMap[$gradeRaw],
                default => null,
            };

            $fieldRaw = isset($cells[3]) ? trim((string) $cells[3]) : '';
            $field = match (true) {
                in_array($fieldRaw, ['math', 'experimental', 'human'], true) => $fieldRaw,
                isset($fieldMap[$fieldRaw]) => $fieldMap[$fieldRaw],
                default => null,
            };

            if (PhoneLead::where('mobile', $mobile)->exists()) {
                $duplicate++;
            }

            PhoneLead::create([
                'full_name'  => $name ?: null,
                'mobile'     => $mobile,
                'grade'      => $grade,
                'field'      => $field,
                'created_by' => $adminId,
            ]);

            $created++;
        }

        $this->excelFile  = null;
        $this->showImport = false;
        $this->resetPage();

        $this->dispatch('success', "ایمپورت انجام شد: {$created} مورد ثبت شد"
            . ($duplicate ? "، {$duplicate} تکراری" : '')
            . ($skipped ? "، {$skipped} نامعتبر رد شد" : '') . '.');
    }

    public function render()
    {
        $leads = PhoneLead::query()
            ->with(['state:id,name', 'city:id,name', 'activeAssignment.consultant:id,name'])
            ->when($this->search, fn ($q) => $q->where(function ($s) {
                $s->where('full_name', 'like', "%{$this->search}%")
                    ->orWhere('mobile', 'like', "%{$this->search}%");
            }))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->withCount(['calls', 'assignments'])
            ->latest()
            ->paginate(15);

        return view('livewire.admin.educational-manager.phone-acquisition.leads.index', [
            'leads'        => $leads,
            'gradeOptions' => TrialWeek::GRADE_LABELS,
            'fieldOptions' => TrialWeek::FIELD_LABELS,
        ])->layout('layouts.admin.app');
    }
}
