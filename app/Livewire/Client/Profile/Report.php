<?php


namespace App\Livewire\Client\Profile;


use App\Models\AdvisingSession;

use App\Models\DailyReport;

use App\Models\DailyReportPart;

use App\Models\ProgramPart;

use App\Models\WeeklyProgram;

use Artesaos\SEOTools\Traits\SEOTools;

use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;

use Livewire\Component;

use Livewire\WithPagination;

use Morilog\Jalali\Jalalian;

use App\Models\WeeklyProgramRestDay;

class Report extends Component

{

    use WithPagination, SEOTools;


    // Modal states

    public bool $showReportModal = false;

    public bool $showCompensatoryModal = false;

    public bool $replyModalOpen = false;


    // Current report data

    public ?int $selectedDayIndex = null;

    public array $selectedParts = [];

    public array $testsDone = [];

    public int $phoneHours = 0;

    public string $description = '';

    public int $rating = 3;


    // Compensatory data
    public array $missedParts = [];

    public array $selectedCompensatoryParts = [];

    public array $compensatoryTestsDone = [];

    public int $compensatoryStep = 1; // 1 = انتخاب پارت، 2 = ثبت جزئیات

    public int $compensatoryPhoneHours = 0;

    public string $compensatoryDescription = '';

    public int $compensatoryRating = 3;


    // Rest days

    public array $restDays = [];

    // Reply modal data

    public ?int $replyReportId = null;

    public string $studentReplyInput = '';

    public ?string $advisorCommentPreview = null;

    public ?string $studentReplyPreview = null;


    // Current session and program data

    public ?AdvisingSession $currentSession = null;

    public ?WeeklyProgram $currentProgram = null;

    public array $weekDays = [];


    protected $paginationTheme = 'tailwind';


    public function mount()

    {

        $this->seo()->setTitle('گزارش های روزانه من');

        $this->loadCurrentSession();

    }


    protected function loadCurrentSession()

    {

        $student = Auth::user()->student;

        if (!$student) return;


        // Find the last held advising session

        $this->currentSession = AdvisingSession::where('student_id', $student->id)
            ->where('result_status', 'held')
            ->orderBy('activation_date', 'desc')
            ->first();


        if ($this->currentSession) {

            $this->currentProgram = WeeklyProgram::where('advising_session_id', $this->currentSession->id)
                ->with(['parts' => function ($query) {

                    $query->orderBy('day_of_week')->orderBy('part_order');

                }])
                ->first();


            if ($this->currentProgram) {

                $this->loadWeekDays();

            }

        }

    }


    protected function loadWeekDays()

    {

        $this->weekDays = [];

        // نام روزهای هفته شمسی

        $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];

        $student = Auth::user()->student;


        // Load rest days

        $this->restDays = WeeklyProgramRestDay::where('weekly_program_id', $this->currentProgram->id)
            ->pluck('day_index')
            ->toArray();


        for ($i = 0; $i < 8; $i++) {

            $date = Carbon::parse($this->currentProgram->start_date)->addDays($i);

            $jalaliDate = jdate($date);

            $actualDayOfWeek = $jalaliDate->getDayOfWeek();


            // پارت‌ها را بر اساس اندیس روز در برنامه فیلتر می‌کنیم

            $parts = $this->currentProgram->parts()->where('day_of_week', $i)->orderBy('part_order')->get();


            // Check if this is a rest day

            $isRestDay = in_array($i, $this->restDays);


            // Check if report already submitted

            $existingReport = DailyReport::where('student_id', $student->id)
                ->where('weekly_program_id', $this->currentProgram->id)
                ->whereDate('report_date', $date)
                ->where('is_compensatory', false)
                ->first();


            $isToday = $date->isToday();

            $isPast = $date->isPast() && !$isToday;

            $isFuture = $date->isFuture();


            $this->weekDays[$i] = [

                'day_of_week' => $actualDayOfWeek,

                'day_index' => $i,

                'name' => $dayNames[$actualDayOfWeek],

                'date' => $date,

                'jalali_date' => $jalaliDate->format('Y/m/d'),

                'jalali_short' => $jalaliDate->format('d F'),

                'parts' => $parts,

                'total_tests' => $parts->sum('test_count'),

                'report' => $existingReport,

                'can_submit' => $isToday && !$existingReport && !$isRestDay,

                'is_locked' => $isPast && !$existingReport && !$isRestDay,

                'is_future' => $isFuture,

                'is_submitted' => (bool)$existingReport,

                'is_rest_day' => $isRestDay,

            ];

        }


        $this->loadMissedParts();
    }


    protected function loadMissedParts()

    {

        $this->missedParts = [];

        $student = Auth::user()->student;


        foreach ($this->weekDays as $dayIndex => $day) {

            // Skip rest days - they don't have missed parts

            if ($day['is_rest_day']) {

                continue;

            }


            if ($day['is_locked'] && !$day['is_submitted']) {

                // Day is past and no report submitted - all parts are missed

                foreach ($day['parts'] as $part) {

                    $this->missedParts[] = [

                        'part' => $part,

                        'day_index' => $dayIndex,

                        'day_name' => $day['name'],

                        'jalali_date' => $day['jalali_short'],

                    ];

                }

            } elseif ($day['is_submitted'] && $day['report']) {

                // Check for unread parts in submitted reports

                $reportParts = DailyReportPart::where('daily_report_id', $day['report']->id)
                    ->where('is_read', false)
                    ->where('is_compensatory', false)
                    ->pluck('program_part_id')
                    ->toArray();


                foreach ($day['parts'] as $part) {

                    if (in_array($part->id, $reportParts)) {

                        $this->missedParts[] = [

                            'part' => $part,

                            'day_index' => $dayIndex,

                            'day_name' => $day['name'],

                            'jalali_date' => $day['jalali_short'],

                        ];

                    }

                }

            }

        }
    }


    public function openReportModal(int $dayIndex)

    {

        if (!isset($this->weekDays[$dayIndex])) return;


        $day = $this->weekDays[$dayIndex];


        if (!$day['can_submit']) {

            if ($day['is_submitted']) {

                $this->dispatch('warning', 'گزارش این روز قبلاً ثبت شده است.');

            } elseif ($day['is_locked']) {

                $this->dispatch('warning', 'مهلت ارسال گزارش این روز تمام شده است.');

            } elseif ($day['is_future']) {

                $this->dispatch('warning', 'هنوز امکان ارسال گزارش برای این روز وجود ندارد.');

            }

            return;

        }


        $this->selectedDayIndex = $dayIndex;

        $this->selectedParts = [];

        $this->testsDone = [];

        $this->phoneHours = 0;

        $this->description = '';

        $this->rating = 3;


        // Initialize test inputs

        foreach ($day['parts'] as $part) {

            $this->testsDone[$part->id] = 0;

        }


        $this->showReportModal = true;

    }


    public function closeReportModal()

    {

        $this->showReportModal = false;

        $this->selectedDayIndex = null;

        $this->selectedParts = [];

        $this->testsDone = [];

        $this->resetErrorBag();

    }


    public function togglePart(int $partId)

    {

        if (in_array($partId, $this->selectedParts)) {

            $this->selectedParts = array_values(array_diff($this->selectedParts, [$partId]));

        } else {

            $this->selectedParts[] = $partId;

        }

    }


    public function submitReport()

    {

        $this->validate([

            'phoneHours' => 'required|integer|min:0|max:24',

            'rating' => 'required|integer|min:1|max:5',

            'description' => 'nullable|string|max:1000',

        ], [

            'phoneHours.required' => 'ساعت استفاده از گوشی الزامی است.',

            'phoneHours.max' => 'ساعت استفاده از گوشی نمی‌تواند بیشتر از 24 باشد.',

            'rating.required' => 'امتیاز الزامی است.',

            'rating.min' => 'امتیاز باید حداقل 1 باشد.',

            'rating.max' => 'امتیاز باید حداکثر 5 باشد.',

            'description.max' => 'توضیحات نمی‌تواند بیشتر از 1000 کاراکتر باشد.',

        ]);


        $student = Auth::user()->student;

        $day = $this->weekDays[$this->selectedDayIndex];


        // Check deadline (end of day)

        if (!$day['date']->isToday()) {

            $this->dispatch('warning', 'مهلت ارسال گزارش این روز تمام شده است.');

            $this->closeReportModal();

            return;

        }


        // Create daily report

        $dailyReport = DailyReport::create([

            'student_id' => $student->id,

            'admin_id' => $student->supporter_id ?? $student->advisor_id,

            'session_id' => $this->currentSession->id,

            'weekly_program_id' => $this->currentProgram->id,

            'report_date' => $day['date'],

            'day_of_week' => $day['day_of_week'], // روز واقعی هفته شمسی
            'phone_hours' => $this->phoneHours,

            'description' => $this->description,

            'rating' => $this->rating,

            'is_compensatory' => false,

        ]);


        // Create report parts

        foreach ($day['parts'] as $part) {

            DailyReportPart::create([

                'daily_report_id' => $dailyReport->id,

                'program_part_id' => $part->id,

                'is_read' => in_array($part->id, $this->selectedParts),

                'tests_done' => $this->testsDone[$part->id] ?? 0,

                'is_compensatory' => false,

            ]);

        }


        $this->dispatch('success', 'گزارش با موفقیت ثبت شد.');

        $this->closeReportModal();

        $this->loadWeekDays();

    }


    public function openCompensatoryModal()
    {

        if (empty($this->missedParts)) {

            $this->dispatch('warning', 'پارت از دست رفته‌ای وجود ندارد.');

            return;

        }


        $this->selectedCompensatoryParts = [];

        $this->compensatoryTestsDone = [];

        $this->compensatoryStep = 1;

        $this->compensatoryPhoneHours = 0;

        $this->compensatoryDescription = '';

        $this->compensatoryRating = 3;


        foreach ($this->missedParts as $missed) {

            $this->compensatoryTestsDone[$missed['part']->id] = 0;

        }


        $this->showCompensatoryModal = true;

    }


    public function closeCompensatoryModal()

    {

        $this->showCompensatoryModal = false;

        $this->selectedCompensatoryParts = [];

        $this->compensatoryTestsDone = [];

        $this->compensatoryStep = 1;

        $this->compensatoryPhoneHours = 0;

        $this->compensatoryDescription = '';

        $this->compensatoryRating = 3;

        $this->resetErrorBag();

    }


    public function goToCompensatoryStep2()

    {

        if (empty($this->selectedCompensatoryParts)) {

            $this->dispatch('warning', 'لطفاً حداقل یک پارت را انتخاب کنید.');

            return;

        }

        $this->compensatoryStep = 2;

    }


    public function goToCompensatoryStep1()

    {

        $this->compensatoryStep = 1;
    }


    public function toggleCompensatoryPart(int $partId)

    {

        if (in_array($partId, $this->selectedCompensatoryParts)) {

            $this->selectedCompensatoryParts = array_values(array_diff($this->selectedCompensatoryParts, [$partId]));

        } else {

            $this->selectedCompensatoryParts[] = $partId;

        }

    }


    public function submitCompensatory()
    {

        if (empty($this->selectedCompensatoryParts)) {

            $this->dispatch('warning', 'لطفاً حداقل یک پارت را انتخاب کنید.');

            return;

        }


        $this->validate([

            'compensatoryPhoneHours' => 'required|integer|min:0|max:24',

            'compensatoryRating' => 'required|integer|min:1|max:5',

            'compensatoryDescription' => 'nullable|string|max:1000',

        ], [

            'compensatoryPhoneHours.required' => 'ساعت استفاده از گوشی الزامی است.',

            'compensatoryPhoneHours.max' => 'ساعت استفاده از گوشی نمی‌تواند بیشتر از 24 باشد.',

            'compensatoryRating.required' => 'امتیاز الزامی است.',

        ]);


        $student = Auth::user()->student;

        $today = Carbon::today();


        // Create compensatory report

        $dailyReport = DailyReport::create([

            'student_id' => $student->id,

            'admin_id' => $student->supporter_id ?? $student->advisor_id,

            'session_id' => $this->currentSession->id,

            'weekly_program_id' => $this->currentProgram->id,

            'report_date' => $today,

            'day_of_week' => jdate($today)->getDayOfWeek(),

            'phone_hours' => $this->compensatoryPhoneHours,

            'description' => $this->compensatoryDescription ?: 'گزارش جبرانی',

            'rating' => $this->compensatoryRating,

            'is_compensatory' => true,

        ]);


        // Create compensatory report parts

        foreach ($this->selectedCompensatoryParts as $partId) {

            DailyReportPart::create([

                'daily_report_id' => $dailyReport->id,

                'program_part_id' => $partId,

                'is_read' => true,

                'tests_done' => $this->compensatoryTestsDone[$partId] ?? 0,

                'is_compensatory' => true,

            ]);

        }


        $this->dispatch('success', 'گزارش جبرانی با موفقیت ثبت شد.');

        $this->closeCompensatoryModal();

        $this->loadWeekDays();

    }


    public function openReplyModal(int $reportId)

    {

        $studentId = Auth::user()->student->id ?? null;

        if (!$studentId) {

            $this->dispatch('warning', 'امکان دسترسی به گزارش وجود ندارد.');

            return;

        }


        $report = DailyReport::where('id', $reportId)
            ->where('student_id', $studentId)
            ->firstOrFail();


        if (empty($report->advisor_comment)) {

            $this->dispatch('warning', 'برای این گزارش هنوز نظری ثبت نشده است.');

            return;

        }


        $this->studentReplyPreview = $report->student_reply ?: null;

        $this->replyReportId = $reportId;

        $this->advisorCommentPreview = $report->advisor_comment;

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

        if (!$this->replyReportId) return;


        $studentId = Auth::user()->student->id ?? null;

        if (!$studentId) {

            $this->dispatch('warning', 'امکان دسترسی به گزارش وجود ندارد.');

            return;

        }


        $report = DailyReport::where('id', $this->replyReportId)
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
            'studentReplyInput.max' => 'طول پاسخ نمی‌تواند بیشتر از 1000 کاراکتر باشد.',
        ]);
        $report->update([
            'student_reply' => $validated['studentReplyInput'],
            'student_replied_at' => now(),
        ]);
        $this->dispatch('success', 'پاسخ شما ثبت شد.');
        $this->closeReplyModal();
    }

    public function getRatingLabel(int $rating): string
    {
        return DailyReport::RATINGS[$rating] ?? 'نامشخص';
    }

    public function render()
    {
        $studentId = Auth::user()->student->id ?? null;
        $reports = DailyReport::query()
            ->where('student_id', $studentId)
            ->with(['reportParts.programPart', 'weeklyProgram'])
            ->latest()
            ->paginate(10);
        return view('livewire.client.profile.report', [
            'reports' => $reports,
            'currentSession' => $this->currentSession,
            'currentProgram' => $this->currentProgram,
            'weekDays' => $this->weekDays,
            'missedParts' => $this->missedParts,
        ])->layout('layouts.client.app');
    }
}
