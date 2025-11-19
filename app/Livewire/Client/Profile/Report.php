<?php

namespace App\Livewire\Client\Profile;

use App\Models\Report as ReportModel;
use App\Traits\UploadFile;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Livewire\WithPagination;

class Report extends Component
{
    use WithFileUploads, UploadFile,WithPagination,SEOTools;

    public $report_file;
    public $complacent = 0;
    public $required_parts = 0;
    public $done_parts = 0;
    public $required_tests = '';
    public $done_tests = '';
    public $phone_study_hours = '';
    public $phone_nonstudy_hours = '';
    public $description = '';
    public bool $replyModalOpen = false;
    public ?int $replyReportId = null;
    public string $studentReplyInput = '';
    public ?string $advisorCommentPreview = null;
    public ?string $studentReplyPreview = null;
    // Analysis Box Properties

    public bool $showAnalysisBox = false;

    public array $analysisData = [];
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
            'complacent' => ['required', 'integer', 'between:1,10'],
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
            'complacent.integer' => 'امتیاز حس شما باید به‌صورت عددی ثبت شود.',
            'complacent.between' => 'لطفاً عددی بین ۱ تا ۱۰ انتخاب کن.',
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
        $this->generateAnalysis();



        ReportModel::create([

            'student_id' => $student->id,

            'admin_id' => $admin->id,

            'required_parts' => $this->required_parts,

            'done_parts' => $this->done_parts,

            'required_tests' => $this->required_tests === '' ? null : $this->required_tests,

            'done_tests' => $this->done_tests === '' ? null : $this->done_tests,

            'phone_study_hours' => $this->phone_study_hours,

            'phone_nonstudy_hours' => $this->phone_nonstudy_hours,

            'description' => $this->description,

            'complacent' => (int) $this->complacent,

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

        $this->showAnalysisBox = true;
        $this->dispatch('analysis-ready'); // جایگزین scrollToAnalysis
        $this->dispatch('success', 'گزارش با موفقیت ثبت شد.');

    }

    private function generateAnalysis()

    {

        // Parts Analysis

        $partsPercentage = $this->required_parts > 0

            ? ($this->done_parts / $this->required_parts) * 100

            : 0;



        if ($partsPercentage >= 100) {

            $partsMessage = 'آفرین! تو امروز تمام پارت‌هارو انجام دادی 🎉';

            $partsIcon = '🌟';

            $partsColor = 'success';

        } elseif ($partsPercentage >= 70) {

            $partsMessage = 'آفرین! خوب بود، سعی کن همیشه پارت‌هاتو کامل کنی 👍';

            $partsIcon = '✨';

            $partsColor = 'primary';

        } elseif ($partsPercentage >= 50) {

            $partsMessage = 'خوبه اما تلاشتو بیشتر کن، می‌تونی بهتر از این باشی! 💪';

            $partsIcon = '⚡';

            $partsColor = 'warning';

        } elseif ($partsPercentage >= 30) {

            $partsMessage = 'تعداد پارت‌های انجامی خیلی کمه! مشاورت در جریانه؟ 🤔';

            $partsIcon = '⚠️';

            $partsColor = 'danger';

        } else {

            $partsMessage = 'منتظر تماس مشاور باش! 📞';

            $partsIcon = '📞';

            $partsColor = 'danger';

        }



        // Tests Analysis

        $testsPercentage = 0;

        $testsMessage = '';

        $testsIcon = '';

        $testsColor = '';



        if ($this->required_tests > 0 && $this->done_tests !== '') {

            $testsPercentage = ($this->done_tests / $this->required_tests) * 100;



            if ($testsPercentage >= 100) {

                $testsMessage = 'عااالی! تمام تست‌هاتو زدی، این روحیه رو حفظ کن! 🔥';

                $testsIcon = '🎯';

                $testsColor = 'success';

            } elseif ($testsPercentage >= 80) {

                $testsMessage = 'خیلی خوبه! داری عالی پیش میری 🚀';

                $testsIcon = '💯';

                $testsColor = 'success';

            } elseif ($testsPercentage >= 50) {

                $testsMessage = 'نصف راه رو اومدی! ادامه بده، داری خوب پیش میری 🎯';

                $testsIcon = '📈';

                $testsColor = 'primary';

            } elseif ($testsPercentage >= 30) {

                $testsMessage = 'تعداد تست‌ها کمه، باید بیشتر وقت بذاری روی تست‌ها 📚';

                $testsIcon = '⏰';

                $testsColor = 'warning';

            } else {

                $testsMessage = 'تست‌های امروز خیلی کم بود، برنامه‌ریزی دوباره کن 📝';

                $testsIcon = '⚠️';

                $testsColor = 'danger';

            }

        } elseif ($this->done_tests == 0 && $this->required_tests == 0) {

            $testsMessage = 'تست موظفی نداشتی یا ثبت نکردی 📝';

            $testsIcon = '📋';

            $testsColor = 'muted';

        }



        // Phone Usage Analysis

        $totalPhoneHours = $this->phone_study_hours + $this->phone_nonstudy_hours;

        $phoneUsagePercentage = ($totalPhoneHours / 24) * 100;

        $studyRatio = $totalPhoneHours > 0 ? ($this->phone_study_hours / $totalPhoneHours) * 100 : 0;



        if ($totalPhoneHours <= 3) {

            $phoneMessage = 'استفاده از موبایلت عالیه! کنترل خوبی داری 👏';

            $phoneIcon = '✅';

            $phoneColor = 'success';

        } elseif ($totalPhoneHours <= 6) {

            if ($studyRatio >= 60) {

                $phoneMessage = 'خوبه که بیشتر برای درس استفاده می‌کنی، ادامه بده! 📱';

                $phoneIcon = '📚';

                $phoneColor = 'primary';

            } else {

                $phoneMessage = 'سعی کن استفاده غیردرسی از گوشی رو کمتر کنی 📵';

                $phoneIcon = '⚡';

                $phoneColor = 'warning';

            }

        } else {

            $phoneMessage = 'استفاده از موبایل خیلی زیاده! این موضوع رو جدی بگیر 🚫';

            $phoneIcon = '🚨';

            $phoneColor = 'danger';

        }



        // Description Analysis

        $descriptionMessage = '';

        $descriptionIcon = '';

        if (!empty($this->description)) {

            $descriptionMessage = 'ممنون که با توضیحات کاملت راه رو هموار می‌کنی ❤️';

            $descriptionIcon = '✍️';

        } else {

            $descriptionMessage = 'توضیحات می‌تونه به تحلیل بهتر کمک کنه 💭';

            $descriptionIcon = '💬';

        }



        // Feeling Analysis

        $feelingScore = (int) $this->complacent;

        if ($feelingScore >= 9) {
            $feelingMessage = 'عالیه! از گزارش امروزت حسابی راضی هستی و این انرژی قابل تحسینه 🤩';
            $feelingIcon = '🤩';
        } elseif ($feelingScore >= 7) {
            $feelingMessage = 'خیلی خوبه! حس مثبتی داری و همین بهترین سوخت حرکتیه 😊';
            $feelingIcon = '😄';
        } elseif ($feelingScore >= 5) {
            $feelingMessage = 'گزارش متوسط بود و جا برای رشد هست؛ فردا می‌تونی بهتر باشی ✨';
            $feelingIcon = '🙂';
        } elseif ($feelingScore >= 3) {
            $feelingMessage = 'می‌دونم راضی نیستی، اما همین که ارزیابی کردی یعنی در مسیر پیشرفتی 💪';
            $feelingIcon = '💪';
        } else {
            $feelingMessage = 'امروز سخت گذشت اما ناامید نشو؛ فردا شروعی تازه‌ست 💙';
            $feelingIcon = '🌱';
        }



        // Overall Analysis

        $overallScore = ($partsPercentage + $testsPercentage) / 2;



        if ($overallScore >= 80 && $totalPhoneHours <= 6) {

            $overallMessage = 'گزارش امروزت عااالی بود! داری فوق‌العاده پیش میری، افتخار می‌کنم بهت! 🌟';

            $overallIcon = '🏆';

            $overallColor = 'success';

        } elseif ($overallScore >= 60) {

            $overallMessage = 'گزارش خوبی بود! با یه کم تلاش بیشتر می‌تونی عالی بشی 💪';

            $overallIcon = '⭐';

            $overallColor = 'primary';

        } elseif ($overallScore >= 40) {

            $overallMessage = 'گزارش متوسطیه، می‌دونم می‌تونی بهتر از این باشی! باور دارم بهت 🚀';

            $overallIcon = '📊';

            $overallColor = 'warning';

        } else {

            $overallMessage = 'امروز خیلی خوب نبود، اما نگران نباش! فردا شروع تازه‌ایه، با برنامه‌ریزی بهتر می‌تونی موفق بشی 💙';

            $overallIcon = '🌱';

            $overallColor = 'danger';

        }



        $this->analysisData = [

            'parts' => [

                'done' => $this->done_parts,

                'required' => $this->required_parts,

                'percentage' => round($partsPercentage, 1),

                'message' => $partsMessage,

                'icon' => $partsIcon,

                'color' => $partsColor,

            ],

            'tests' => [

                'done' => $this->done_tests ?: 0,

                'required' => $this->required_tests ?: 0,

                'percentage' => round($testsPercentage, 1),

                'message' => $testsMessage,

                'icon' => $testsIcon,

                'color' => $testsColor,

            ],

            'phone' => [

                'study_hours' => $this->phone_study_hours,

                'nonstudy_hours' => $this->phone_nonstudy_hours,

                'total_hours' => $totalPhoneHours,

                'percentage' => round($phoneUsagePercentage, 1),

                'study_ratio' => round($studyRatio, 1),

                'message' => $phoneMessage,

                'icon' => $phoneIcon,

                'color' => $phoneColor,

            ],

            'description' => [

                'filled' => !empty($this->description),

                'message' => $descriptionMessage,

                'icon' => $descriptionIcon,

            ],

            'feeling' => [

                'score' => $feelingScore,

                'message' => $feelingMessage,

                'icon' => $feelingIcon,

            ],

            'overall' => [

                'score' => round($overallScore, 1),

                'message' => $overallMessage,

                'icon' => $overallIcon,

                'color' => $overallColor,

            ],

        ];

    }
    public function closeAnalysisBox()
    {

        $this->showAnalysisBox = false;

        $this->analysisData = [];

    }


    public function openReplyModal(int $reportId)
    {
        $studentId = Auth::user()->student->id ?? null;
        if (!$studentId) {
            $this->dispatch('warning', 'امکان دسترسی به گزارش وجود ندارد.');
            return;
        }

        $report = ReportModel::where('id', $reportId)
            ->where('student_id', $studentId)
            ->firstOrFail();

        if (empty($report->advisor_comment)) {
            $this->dispatch('warning', 'برای این گزارش هنوز نظری ثبت نشده است.');
            return;
        }

        // نمایش پاسخ اگر وجود دارد
        $this->studentReplyPreview = $report->student_reply ?: null;

        $this->replyReportId = $reportId;
        $this->advisorCommentPreview = $report->advisor_comment;

        // فقط اگر پاسخ وجود نداشته باشد، این ورودی را پاک می‌کنیم
        $this->studentReplyInput = '';

        $this->replyModalOpen = true;
    }




    public function closeReplyModal()
    {
        $this->replyModalOpen = false;
        $this->studentReplyPreview = null;

        $this->replyReportId = null;
        $this->studentReplyInput = '';
        $this->advisorCommentPreview = null;
        $this->resetErrorBag('studentReplyInput');
    }

    public function saveStudentReply()
    {
        if (!$this->replyReportId) {
            return;
        }

        $studentId = Auth::user()->student->id ?? null;
        if (!$studentId) {
            $this->dispatch('warning', 'امکان دسترسی به گزارش وجود ندارد.');
            return;
        }

        $report = ReportModel::where('id', $this->replyReportId)
            ->where('student_id', $studentId)
            ->firstOrFail();

        if (!empty($report->student_reply)) {
            $this->dispatch('warning', 'پاسخ شما قبلاً ثبت شده است.');
            $this->closeReplyModal();
            return;
        }

        $validated = $this->validate([
            'studentReplyInput' => 'required|string|max:1000',
        ], [
            'studentReplyInput.required' => 'متن پاسخ الزامی است.',
            'studentReplyInput.max' => 'طول پاسخ نمی‌تواند بیشتر از ۱۰۰۰ کاراکتر باشد.',
        ]);

        $report->update([
            'student_reply' => $validated['studentReplyInput'],
            'student_replied_at' => now(),
            'student_reply_seen_at' => null,
        ]);

        $this->dispatch('success', 'پاسخ شما ثبت شد.');
        $this->closeReplyModal();
    }


    public function render()
    {
        $studentId = Auth::user()->student->id ?? null;
        $reports = ReportModel::query()->where('student_id',$studentId)->latest()->paginate(10);
        return view('livewire.client.profile.report',['reports'=>$reports])
            ->layout('layouts.client.app');
    }
}
