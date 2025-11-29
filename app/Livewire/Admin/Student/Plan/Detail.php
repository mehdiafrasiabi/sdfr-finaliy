<?php

namespace App\Livewire\Admin\Student\Plan;

use App\Exports\admin\PlanExportForAdmin;
use App\Models\Student;
use App\Models\User;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;
use Morilog\Jalali\Jalalian;
use App\Helpers\FileHelper;
use App\Services\NotificationService;

class Detail extends Component
{
    use WithPagination, WithFileUploads, SEOTools;

    public $title;
    public $barnameh;
    public $studentName;
    public $studentId;

    // فیلتر تاریخ شمسی به صورت "YYYY/MM/DD"
    public ?string $from = null;
    public ?string $to = null;

    protected $queryString = [
        'from' => ['except' => ''],
        'to' => ['except' => ''],
        'page' => ['except' => 1],
    ];

    public function mount(User $student)
    {
        $this->studentId = $student->student->id;

        // گرفتن نام دانش آموز (اولویت: اطلاعات شخصی → نام کاربر → مقدار پیش‌فرض)
        $this->studentName =
            $student->personalInformation->name
            ?? $student->name
            ?? 'دانش آموز عزیز';

        $this->seoConfig();
    }



    public function seoConfig()
    {
        $this->seo()->setTitle('برنامه های ' . $this->studentName);
    }

    public function submit($formData)
    {
        if ($this->barnameh) {
            $formData['barnameh'] = $this->barnameh;
        }

        $validator = Validator::make($formData, [
            'title'    => 'required|string|max:50',
            'barnameh' => 'required|mimes:pdf,png,jpeg|max:61440', // 60MB
        ], [
            '*.required'    => 'فیلد ضروری است.',
            '*.string'      => 'فرمت اشتباه است !',
            'barnameh.mimes'=> 'فرمت های مجاز آپلود فایل : pdf,png,jpeg !',
            'barnameh.max'  => 'سایز فایل ارسالی حداکثر : 60MB',
        ]);

        $validator->validate();
        $this->resetValidation();

        // هش کردن آیدی دانش‌آموز
        $hashedId  = md5('student-' . $this->studentId);
        $directory = "student/{$hashedId}/plan";

        // آپلود فایل
        $path = FileHelper::uploadToPublicHtml($this->barnameh, $directory);

        // ثبت در دیتابیس
        \App\Models\Barnameh::create([
            'title'      => $formData['title'],
            'barnameh'   => $path,
            'student_id' => $this->studentId,
            'admin_id'   => Auth::id(),
        ]);

        $student = Student::with('user.personalInformation')->find($this->studentId);

        $studentName =
            $student->personalInformation->name
            ?? $student->user->name
            ?? 'دانش آموز عزیز';

        NotificationService::sendToStudent(
            $this->studentId,
            'برنامه مشاوره ای جدید',
            "{$studentName}، برنامه تحصیلی شما با عنوان «{$formData['title']}» برای شما قرار گرفت."
        );

        // حذف فایل موقت Livewire (درصورت وجود)
        if ($this->barnameh?->getRealPath() && file_exists($this->barnameh->getRealPath())) {
            @unlink($this->barnameh->getRealPath());
        }

        // ریست کردن فیلدهای فرم بعد از ثبت
        $this->reset(['title', 'barnameh']);

        // اگر ورودی‌های دیگری هم داری که باید خالی شوند، همین‌جا اضافه‌شان کن

        $this->dispatch('success', 'برنامه دانش آموز با موفقیت اضافه شد.');
    }


    public function delete(\App\Models\Barnameh $barnameh)
    {
        try {
            // مسیر فایل اصلی در public_html
            $filePath = base_path('public_html/' . $barnameh->barnameh);

            if (file_exists($filePath)) {
                clearstatcache(true, $filePath);
                @unlink($filePath);
            }

            // حذف ردیف دیتابیس
            $barnameh->delete();

            $this->dispatch('success', 'با موفقیت حذف شد.');
        } catch (\Throwable $e) {
            $this->dispatch('error', 'خطا در حذف فایل: ' . $e->getMessage());
        }
    }

    public function exportExcel()
    {
        $student = Student::findOrFail($this->studentId);

        $fromCarbon = null;
        $toCarbon = null;

        try {
            if ($this->from) $fromCarbon = Jalalian::fromFormat('Y/m/d', $this->from)->toCarbon();
            if ($this->to) $toCarbon = Jalalian::fromFormat('Y/m/d', $this->to)->toCarbon();
        } catch (\Exception $e) {
            // ignore
        }

        $studentName = $this->studentName ?: 'student_' . $this->studentId;
        $safeName = Str::slug($studentName);
        $fileName = "{$safeName}_plans_" . jalali()->format('Ymd_His') . ".xlsx";

        return Excel::download(
            new PlanExportForAdmin($student, $fromCarbon, $toCarbon),
            $fileName
        );
    }

    public function render()
    {
        $query = \App\Models\Barnameh::query()
            ->where('student_id', $this->studentId)
            ->with(['views' => fn($q) => $q->where('student_id', $this->studentId)])
            ->latest();

        if ($this->from) {
            try {
                $fromCarbon = Jalalian::fromFormat('Y/m/d', $this->from)->toCarbon()->startOfDay();
                $query->where('created_at', '>=', $fromCarbon);
            } catch (\Exception $e) {}
        }

        if ($this->to) {
            try {
                $toCarbon = Jalalian::fromFormat('Y/m/d', $this->to)->toCarbon()->endOfDay();
                $query->where('created_at', '<=', $toCarbon);
            } catch (\Exception $e) {}
        }

        $plans = $query->paginate(10);

        return view('livewire.admin.student.plan.detail', ['plans' => $plans])
            ->layout('layouts.admin.app');
    }
}
