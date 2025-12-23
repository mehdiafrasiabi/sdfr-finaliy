<?php


namespace App\Livewire\Client\Profile;


use App\Models\DailyReport;

use App\Models\AdvisingSession;

use App\Models\WeeklyProgram;

use App\Models\ProgramPart;

use App\Models\DailyReportPart;

use App\Models\ReportDetail;

use App\Models\ReportComment;

use Artesaos\SEOTools\Traits\SEOTools;

use Illuminate\Support\Facades\Auth;

use Livewire\Component;

use Livewire\WithPagination;

use Carbon\Carbon;

use Morilog\Jalali\Jalalian;


class DailyReportClient extends Component

{

    use WithPagination, SEOTools;


    // مودال ارسال گزارش

    public bool $reportModalOpen = false;

    public $selectedDate = null;

    public $selectedDayParts = []; // پارت‌های اون روز

    public $selectedParts = []; // آیدی پارت‌های انتخاب شده

    public $partTests = []; // [part_id => test_count]

    public $phoneNonStudyHours = 0;

    public $description = '';

    public $rating = 0;


    // مودال پارت جبرانی

    public bool $compensatoryModalOpen = false;

    public $missedParts = [];

    public $selectedCompensatoryParts = [];

    public $compensatoryPartTests = [];


    // مودال پاسخ به نظر

    public bool $replyModalOpen = false;

    public ?int $replyReportId = null;

    public string $studentReplyInput = '';

    public ?string $advisorCommentPreview = null;

    public ?string $studentReplyPreview = null;


    // داده‌های برنامه

    public ?AdvisingSession $lastSession = null;

    public ?WeeklyProgram $weeklyProgram = null;

    public $weekDays = [];


    public function mount()

    {

        $this->seo()->setTitle('گزارش روزانه من');

        $this->loadProgramData();

    }


    // بارگذاری اطلاعات برنامه براساس آخرین جلسه held

    public function loadProgramData()

    {

        $student = Auth::user()->student;

        if (!$student) return;


        // آخرین جلسه مشاوره برگزار شده

        $this->lastSession = AdvisingSession::where('student_id', $student->id)
            ->where('result_status', 'held')
            ->where('status', 'completed')
            ->latest('activation_date')
            ->first();


        if (!$this->lastSession) return;


        // برنامه هفتگی مرتبط

        $this->weeklyProgram = WeeklyProgram::where('advising_session_id', $this->lastSession->id)
            ->where('student_id', $student->id)
            ->where('is_active', true)
            ->first();


        if (!$this->weeklyProgram) return;


        $this->loadWeekDays();

        $this->loadMissedParts();

    }


    public function loadWeekDays()

    {

        if (!$this->weeklyProgram) return;


        $this->weekDays = [];

        $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنجشنبه', 'جمعه'];


        for ($i = 0; $i < 7; $i++) {

            $date = Carbon::parse($this->weeklyProgram->start_date)->addDays($i);

            $jalaliDate = Jalalian::fromCarbon($date);

            $isToday = $date->isToday();

            $isPast = $date->isPast() && !$isToday;

            $isFuture = $date->isFuture();


            // چک کردن گزارش

            $hasReport = DailyReport::where('student_id', Auth::user()->student->id)
                ->whereDate('report_date', $date->format('Y-m-d'))
                ->exists();


            // پارت‌های این روز

            $parts = ProgramPart::where('weekly_program_id', $this->weeklyProgram->id)
                ->whereDate('part_date', $date->format('Y-m-d'))
                ->orderBy('part_order')
                ->get();


            $this->weekDays[] = [

                'day_of_week' => $i,

                'name' => $dayNames[$i],

                'date' => $date,

                'jalali_date' => $jalaliDate->format('Y/m/d'),

                'jalali_day_month' => $jalaliDate->format('d F'),

                'is_today' => $isToday,

                'is_past' => $isPast,

                'is_future' => $isFuture,

                'has_report' => $hasReport,

                'can_submit' => $isToday && !$hasReport && Carbon::now()->lte($date->copy()->endOfDay()),

                'is_locked' => ($isPast || Carbon::now()->gt($date->copy()->endOfDay())) && !$hasReport,

                'parts' => $parts,

                'parts_count' => $parts->count(),

                'total_tests' => $parts->sum('test_count'),

            ];

        }

    }


    public function loadMissedParts()

    {

        if (!$this->weeklyProgram) return;


        $student = Auth::user()->student;

        $today = Carbon::today();


        // پارت‌های قبل از امروز

        $allPastParts = ProgramPart::where('weekly_program_id', $this->weeklyProgram->id)
            ->where('part_date', '<', $today->format('Y-m-d'))
            ->orderBy('part_date')
            ->orderBy('part_order')
            ->get();


        $this->missedParts = [];

        foreach ($allPastParts as $part) {

            $isDone = DailyReportPart::whereHas('dailyReport', function ($query) use ($student) {

                $query->where('student_id', $student->id);

            })
                ->where('part_id', $part->id)
                ->where('is_done', true)
                ->exists();


            if (!$isDone) {

                $this->missedParts[] = $part;

            }

        }

    }


    public function openReportModal($dateStr)

    {

        $date = Carbon::parse($dateStr);


        // فقط برای امروز

        if (!$date->isToday()) {

            $this->dispatch('error', 'فقط می‌توانید برای امروز گزارش ثبت کنید.');

            return;

        }


        // چک گزارش قبلی

        $hasReport = DailyReport::where('student_id', Auth::user()->student->id)
            ->whereDate('report_date', $date->format('Y-m-d'))
            ->exists();


        if ($hasReport) {

            $this->dispatch('error', 'شما قبلاً برای این روز گزارش ثبت کرده‌اید.');

            return;

        }


        // محدودیت زمانی

        $endOfDay = $date->copy()->endOfDay();

        if (Carbon::now()->greaterThan($endOfDay)) {

            $this->dispatch('error', 'مهلت ارسال گزارش این روز به پایان رسیده است.');

            return;

        }


        $this->selectedDate = $dateStr;


        // گرفتن پارت‌های این روز

        $this->selectedDayParts = ProgramPart::where('weekly_program_id', $this->weeklyProgram->id)
            ->whereDate('part_date', $date->format('Y-m-d'))
            ->orderBy('part_order')
            ->get()
            ->toArray();


        $this->resetForm();

        $this->reportModalOpen = true;

    }


    public function closeReportModal()

    {

        $this->reportModalOpen = false;

        $this->resetForm();

    }


    public function resetForm()

    {

        $this->selectedParts = [];

        $this->partTests = [];

        $this->phoneNonStudyHours = 0;

        $this->description = '';

        $this->rating = 0;

    }


    public function togglePart($partId)

    {

        if (in_array($partId, $this->selectedParts)) {

            $this->selectedParts = array_diff($this->selectedParts, [$partId]);

            unset($this->partTests[$partId]);

        } else {

            $this->selectedParts[] = $partId;

            $this->partTests[$partId] = 0;

        }

    }


    public function submitReport()

    {

        $this->validate([

            'phoneNonStudyHours' => 'required|integer|between:0,24',

            'description' => 'nullable|string|max:1000',

            'rating' => 'required|integer|between:1,5',

        ], [

            'phoneNonStudyHours.required' => 'ساعت استفاده از گوشی را وارد کنید.',

            'phoneNonStudyHours.between' => 'ساعت باید بین 0 تا 24 باشد.',

            'description.max' => 'توضیحات نمی‌تواند بیشتر از 1000 کاراکتر باشد.',

            'rating.required' => 'امتیاز را انتخاب کنید.',

            'rating.between' => 'امتیاز باید بین 1 تا 5 باشد.',

        ]);


        $student = Auth::user()->student;

        if (!$student || !$student->supporterStudent) {

            $this->dispatch('error', 'اطلاعات دانش‌آموز یافت نشد.');

            return;

        }


        // ایجاد گزارش اصلی

        $report = DailyReport::create([

            'student_id' => $student->id,

            'session_id' => $this->lastSession->id,

            'program_id' => $this->weeklyProgram->id,

            'report_date' => Carbon::parse($this->selectedDate)->format('Y-m-d'),

            'phone_hours' => $this->phoneNonStudyHours,

            'rating' => $this->rating,

            'status' => 'pending',

        ]);


        // ذخیره جزییات

        ReportDetail::create([

            'report_id' => $report->id,

            'description' => $this->description,

        ]);


        // ثبت پارت‌ها

        foreach ($this->selectedDayParts as $part) {

            $partId = $part['id'];

            $isDone = in_array($partId, $this->selectedParts);

            $testsDone = $isDone ? ($this->partTests[$partId] ?? 0) : 0;


            DailyReportPart::create([

                'report_id' => $report->id,

                'part_id' => $partId,

                'is_done' => $isDone,

                'tests_done' => $testsDone,

                'is_compensatory' => false,

            ]);

        }


        $this->dispatch('success', 'گزارش با موفقیت ثبت شد.');

        $this->closeReportModal();

        $this->loadWeekDays();

    }


    // مودال پارت جبرانی

    public function openCompensatoryModal()

    {

        if (empty($this->missedParts)) {

            $this->dispatch('info', 'شما پارت جبرانی ندارید.');

            return;

        }


        $this->selectedCompensatoryParts = [];

        $this->compensatoryPartTests = [];

        $this->compensatoryModalOpen = true;

    }


    public function closeCompensatoryModal()

    {

        $this->compensatoryModalOpen = false;

        $this->selectedCompensatoryParts = [];

        $this->compensatoryPartTests = [];

    }


    public function toggleCompensatoryPart($partId)

    {

        if (in_array($partId, $this->selectedCompensatoryParts)) {

            $this->selectedCompensatoryParts = array_diff($this->selectedCompensatoryParts, [$partId]);

            unset($this->compensatoryPartTests[$partId]);

        } else {

            $this->selectedCompensatoryParts[] = $partId;

            $this->compensatoryPartTests[$partId] = 0;

        }

    }


    public function submitCompensatory()

    {

        if (empty($this->selectedCompensatoryParts)) {

            $this->dispatch('error', 'لطفاً حداقل یک پارت انتخاب کنید.');

            return;

        }


        $student = Auth::user()->student;

        $today = Carbon::today();


        // گزارش امروز یا ایجاد جدید

        $report = DailyReport::where('student_id', $student->id)
            ->whereDate('report_date', $today->format('Y-m-d'))
            ->first();


        if (!$report) {

            $report = DailyReport::create([

                'student_id' => $student->id,

                'session_id' => $this->lastSession->id,

                'program_id' => $this->weeklyProgram->id,

                'report_date' => $today->format('Y-m-d'),

                'phone_hours' => 0,

                'rating' => 3,

                'status' => 'pending',

            ]);


            ReportDetail::create([

                'report_id' => $report->id,

                'description' => 'گزارش پارت‌های جبرانی',

            ]);

        }


        // ثبت پارت‌های جبرانی

        foreach ($this->selectedCompensatoryParts as $partId) {

            $testsDone = $this->compensatoryPartTests[$partId] ?? 0;


            DailyReportPart::create([

                'report_id' => $report->id,

                'part_id' => $partId,

                'is_done' => true,

                'tests_done' => $testsDone,

                'is_compensatory' => true,

            ]);

        }


        $this->dispatch('success', 'پارت‌های جبرانی ثبت شد.');

        $this->closeCompensatoryModal();

        $this->loadMissedParts();

    }


    // مودال پاسخ

    public function openReplyModal(int $reportId)

    {

        $report = DailyReport::with('comment')
            ->where('id', $reportId)
            ->where('student_id', Auth::user()->student->id)
            ->first();


        if (!$report || !$report->comment || empty($report->comment->advisor_comment)) {

            $this->dispatch('error', 'نظر مشاور یافت نشد.');

            return;

        }


        $this->replyReportId = $reportId;

        $this->advisorCommentPreview = $report->comment->advisor_comment;

        $this->studentReplyPreview = $report->comment->student_reply;

        $this->studentReplyInput = '';

        $this->replyModalOpen = true;

    }


    public function closeReplyModal()

    {

        $this->replyModalOpen = false;

        $this->replyReportId = null;

        $this->advisorCommentPreview = null;

        $this->studentReplyPreview = null;

        $this->studentReplyInput = '';

    }


    public function saveStudentReply()

    {

        if (!$this->replyReportId) return;


        $this->validate([

            'studentReplyInput' => 'required|string|max:1000',

        ], [

            'studentReplyInput.required' => 'متن پاسخ را وارد کنید.',

            'studentReplyInput.max' => 'پاسخ نمی‌تواند بیشتر از 1000 کاراکتر باشد.',

        ]);


        $report = DailyReport::with('comment')
            ->where('id', $this->replyReportId)
            ->where('student_id', Auth::user()->student->id)
            ->first();


        if (!$report || !$report->comment) {

            $this->dispatch('error', 'گزارش یافت نشد.');

            return;

        }


        if (!empty($report->comment->student_reply)) {

            $this->dispatch('error', 'پاسخ شما قبلاً ثبت شده است.');

            $this->closeReplyModal();

            return;

        }


        $report->comment->update([

            'student_reply' => $this->studentReplyInput,

            'replied_at' => now(),

        ]);


        $this->dispatch('success', 'پاسخ شما ثبت شد.');

        $this->closeReplyModal();

    }


    public function render()

    {

        $studentId = Auth::user()->student->id ?? null;


        $reports = DailyReport::query()
            ->where('student_id', $studentId)
            ->with(['parts.programPart', 'comment', 'details'])
            ->latest('report_date')
            ->paginate(10);


        return view('livewire.client.profile.daily-report-client', [

            'reports' => $reports,

        ])->layout('layouts.client.app');

    }

}
