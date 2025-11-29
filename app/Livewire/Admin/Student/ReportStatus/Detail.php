<?php

namespace App\Livewire\Admin\Student\ReportStatus;

use App\Helpers\FileHelper;
use App\Models\User;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class Detail extends Component
{
    use WithPagination,WithFileUploads,SEOTools;
    public $title;
    public $reportMonthly;
    public $studentName;
    public $studentId;

    public function mount(User $student)
    {
        $this->studentId = $student->student->id;
        $this->studentName = $student->personalInformation->name;
        $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()
            ->setTitle('گزارش های ماهانه '.$this->studentName);
    }
    public function submit($formData)
    {

        if ($this->reportMonthly) {
            $formData['report'] = $this->reportMonthly;
        }

        $validator = Validator::make($formData, [
            'title' => 'required|string|max:50',
            'report' => 'required|mimes:pdf,png,jpeg|max:61440',//60MB
        ], [
            '*.required' => 'فیلد ضروری است.',
            '*.string' => 'فرمت اشتباه است !',
            'report.mimes' => 'فرمت های مجاز آپلود گزارش : pdf,png,jpeg !',
            'report.max' => 'سایز فایل ارسالی حداکثر : ! 2MB',
        ]);

        $validator->validate();
        $this->resetValidation();

        // 🔹 هش کردن آیدی دانش‌آموز برای امنیت
        $hashedId = md5('student-' . $this->studentId);
        $directory = "student/{$hashedId}/report_monthly";

        // 🔹 آپلود فایل
        $path = FileHelper::uploadToPublicHtml($this->reportMonthly, $directory);

        \App\Models\ReportMonthly::query()->create([
            'title' => $formData['title'],
            'report' => $path,
            'student_id' => $this->studentId,
            'admin_id' => Auth::id(),

        ]);

        // حذف فایل موقت Livewire
        if ($this->reportMonthly?->getRealPath() && file_exists($this->reportMonthly->getRealPath())) {
            @unlink($this->reportMonthly->getRealPath());
        }

        $this->reset();
        $this->dispatch('success','با موفقیت اضافه شد .');
    }


    public function delete(\App\Models\ReportMonthly $report)
    {
        try {
            // مسیر فایل اصلی در public_html
            $filePath = base_path('public_html/' . $report->report);

            if (file_exists($filePath)) {
                clearstatcache(true, $filePath);
                @unlink($filePath);
            }

            // حذف ردیف دیتابیس
            $report->delete();

            $this->dispatch('success', 'با موفقیت حذف شد.');
        } catch (\Throwable $e) {
            $this->dispatch('error', 'خطا در حذف فایل: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $reports = \App\Models\ReportMonthly::query()->paginate(10);
        return view('livewire.admin.student.report-status.detail',['reports'=>$reports])->layout('layouts.admin.app');
    }
}
