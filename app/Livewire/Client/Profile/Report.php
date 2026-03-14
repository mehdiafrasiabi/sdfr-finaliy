<?php

namespace App\Livewire\Client\Profile;

use App\Models\AdvisingSession;
use App\Models\DailyReport;
use App\Models\DailyReportPart;
use App\Models\DailyReportDetail;
use App\Models\DailyReportFeedback;
use App\Models\SessionFeedback;
use App\Models\WeeklyProgram;
use App\Models\WeeklyProgramRestDay;
use App\Models\StudyPartSession;
use Artesaos\SEOTools\Traits\SEOTools;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Report extends Component
{
    use WithPagination, SEOTools;

    const REPORT_CUTOFF_HOUR = 6;

    // Modal states
    public bool $showReportModal = false;
    public bool $showCompensatoryModal = false;
    public bool $replyModalOpen = false;

    // Current report data
    public ?int $selectedDayIndex = null;
    public array $selectedParts = [];
    public array $testsDone = [];
    public string $description = '';
    public string $missedPartsReason = '';

    // Compensatory data
    public array $missedParts = [];
    public array $selectedCompensatoryParts = [];
    public array $compensatoryTestsDone = [];
    public string $compensatoryDescription = '';
    public string $compensatoryMissedPartsReason = '';
    public int $compensatoryStep = 1;

    // Rest days
    public array $restDays = [];

    // Completed study parts (parts with logged study hours)
    public array $completedStudyParts = [];
    public array $alreadyCompensatedPartIds = [];


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

    /**
     * Get the "effective date" considering 6:00 AM cutoff.
     */
    protected function getEffectiveDate(): Carbon
    {
        $now = Carbon::now();
        if ($now->hour < self::REPORT_CUTOFF_HOUR) {
            return Carbon::yesterday();
        }
        return Carbon::today();
    }

    /**
     * Check if a specific date's report can still be submitted.
     */
    protected function canSubmitForDate(Carbon $date): bool
    {
        $now = Carbon::now();
        $reportDeadline = $date->copy()->addDay()->setHour(self::REPORT_CUTOFF_HOUR)->setMinute(0)->setSecond(0);
        $reportStart = $date->copy()->startOfDay();

        return $now->gte($reportStart) && $now->lt($reportDeadline);
    }

    /**
     * Check if compensatory window is open (after 6:00 AM of next day).
     */
    protected function isCompensatoryWindowOpen(Carbon $date): bool
    {
        $now = Carbon::now();
        $compensatoryStart = $date->copy()->addDay()->setHour(self::REPORT_CUTOFF_HOUR)->setMinute(0)->setSecond(0);

        return $now->gte($compensatoryStart);
    }

    protected function loadCurrentSession()
    {
        $student = Auth::user()->student;
        if (!$student) return;

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
                $this->loadCompletedStudyParts();
                $this->loadWeekDays();
            }
        }
    }
    protected function loadCompletedStudyParts()
    {
        $student = Auth::user()->student;
        if (!$student || !$this->currentProgram) return;

        $this->completedStudyParts = StudyPartSession::where('student_id', $student->id)
            ->where('weekly_program_id', $this->currentProgram->id)
            ->where('is_completed', true)
            ->pluck('program_part_id')
            ->toArray();
    }

    protected function loadWeekDays()
    {
        $this->weekDays = [];
        $dayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
        $student = Auth::user()->student;

        // ✅ پارت‌هایی که قبلاً گزارش جبرانی برایشان ثبت شده
        $this->alreadyCompensatedPartIds = DailyReportPart::whereHas('dailyReport', function ($q) use ($student) {
            $q->where('student_id', $student->id)
                ->where('weekly_program_id', $this->currentProgram->id)
                ->where('is_compensatory', true);
        })
            ->where('is_compensatory', true)
            ->where('is_read', true)
            ->pluck('program_part_id')
            ->toArray();

        $this->restDays = WeeklyProgramRestDay::where('weekly_program_id', $this->currentProgram->id)
            ->pluck('day_index')
            ->toArray();

        $now = Carbon::now();

        // ✅ تعیین روز "فعال" برای ثبت گزارش
        // اگر قبل از 6 صبح باشیم → روز دیروز فعاله
        // اگر بعد از 6 صبح باشیم → امروز فعاله
        $activeReportDate = $now->hour < self::REPORT_CUTOFF_HOUR
            ? Carbon::yesterday()
            : Carbon::today();

        for ($i = 0; $i < 8; $i++) {
            $date = Carbon::parse($this->currentProgram->start_date)->addDays($i);
            $jalaliDate = jdate($date);
            $actualDayOfWeek = $jalaliDate->getDayOfWeek();

            $parts = $this->currentProgram->parts()->where('day_of_week', $i)->with(['ccSubject', 'ccChapter', 'ccTopic'])->orderBy('part_order')->get();
            $isRestDay = in_array($i, $this->restDays);

            // بررسی گزارش موجود
            $existingReport = DailyReport::where('student_id', $student->id)
                ->where('weekly_program_id', $this->currentProgram->id)
                ->whereDate('report_date', $date)
                ->where('is_compensatory', false)
                ->first();

            // ✅ منطق جدید: فقط یک روز می‌تونه باز باشه
            $canSubmit = false;
            $isLocked = false;
            $isFuture = false;

            if ($isRestDay) {
                // روز استراحت
                $canSubmit = false;
                $isLocked = false;
            } elseif ($date->isSameDay($activeReportDate)) {
                // ✅ این روز "فعال" برای ثبت گزارش است
                $canSubmit = !$existingReport;
                $isLocked = false;
            } elseif ($date->lt($activeReportDate)) {
                // ✅ روزهای گذشته که گزارش ثبت نشده → قفل
                $canSubmit = false;
                $isLocked = !$existingReport;
            } else {
                // ✅ روزهای آینده → نمایش "در انتظار"
                $canSubmit = false;
                $isLocked = false;
                $isFuture = true;
            }

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
                'can_submit' => $canSubmit,
                'is_locked' => $isLocked,
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
            // Skip rest days
            if ($day['is_rest_day']) {
                continue;
            }

            // ✅ فقط اگر پنجره جبرانی باز شده باشد (بعد از 6 صبح روز بعد)
            if (!$this->isCompensatoryWindowOpen($day['date'])) {
                continue;
            }

            if ($day['is_locked'] && !$day['is_submitted']) {
                // همه پارت‌ها از دست رفته
                foreach ($day['parts'] as $part) {
                    if (in_array($part->id, $this->alreadyCompensatedPartIds)) {
                        continue;
                    }

                    $this->missedParts[] = [
                        'part' => $part,
                        'day_index' => $dayIndex,
                        'day_name' => $day['name'],
                        'jalali_date' => $day['jalali_short'],
                    ];
                }
            } elseif ($day['is_submitted'] && $day['report']) {
                // پارت‌های خوانده نشده در گزارش ثبت شده
                $unreadPartIds = DailyReportPart::where('daily_report_id', $day['report']->id)
                    ->where('is_read', false)
                    ->where('is_compensatory', false)
                    ->pluck('program_part_id')
                    ->toArray();

                foreach ($day['parts'] as $part) {
                    if (in_array($part->id, $unreadPartIds) && !in_array($part->id, $this->alreadyCompensatedPartIds)) {
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
        $this->description = '';
        $this->missedPartsReason = '';
        $this->showReportModal = true;
    }

    public function closeReportModal()
    {
        $this->showReportModal = false;
        $this->selectedDayIndex = null;
        $this->selectedParts = [];
        $this->testsDone = [];
        $this->description = '';
        $this->missedPartsReason = '';
        $this->resetErrorBag();
    }

    public function togglePart(int $partId)
    {
        if (in_array($partId, $this->selectedParts)) {
            $this->selectedParts = array_values(array_diff($this->selectedParts, [$partId]));
        } else {
            // ✅ بررسی ثبت ساعت مطالعه قبل از انتخاب پارت
            if (!in_array($partId, $this->completedStudyParts)) {
                $this->dispatch('warning', 'شما هنوز ساعت مطالعه این پارت را ثبت نکرده‌اید. ابتدا از بخش «ثبت ساعت مطالعه» اقدام کنید.');
                return;
            }
            $this->selectedParts[] = $partId;
        }
    }

    /**
     * Compute average rating from session_feedbacks for the selected day's parts (1-10 scale).
     */
    public function getComputedRatingProperty(): float
    {
        if (is_null($this->selectedDayIndex) || !isset($this->weekDays[$this->selectedDayIndex])) {
            return 0;
        }
        $student = Auth::user()->student;
        if (!$student) return 0;

        $day = $this->weekDays[$this->selectedDayIndex];
        $partIds = collect($day['parts'])->pluck('id')->toArray();

        if (empty($partIds)) return 0;

        $spsList = StudyPartSession::where('student_id', $student->id)
            ->whereIn('program_part_id', $partIds)
            ->where('is_completed', true)
            ->with('feedback')
            ->get();

        $ratings = $spsList->filter(fn($sps) => $sps->feedback && $sps->feedback->rating > 0)
            ->map(fn($sps) => $sps->feedback->rating);

        if ($ratings->isEmpty()) return 0;

        return round($ratings->avg(), 1);
    }

    /**
     * Count of parts not selected (unread) for the current day.
     */
    public function getUnreadPartsCountProperty(): int
    {
        if (is_null($this->selectedDayIndex) || !isset($this->weekDays[$this->selectedDayIndex])) {
            return 0;
        }
        $totalParts = count($this->weekDays[$this->selectedDayIndex]['parts']);
        return max(0, $totalParts - count($this->selectedParts));
    }

    public function submitReport()
    {
        $this->validate([
            'description' => 'nullable|string|max:350',
            'missedPartsReason' => 'nullable|string|max:500',
        ], [
            'description.max' => 'توضیحات نمی‌تواند بیشتر از 350 کاراکتر باشد.',
            'missedPartsReason.max' => 'علت عدم انجام پارت نمی‌تواند بیشتر از 500 کاراکتر باشد.',
        ]);

        $student = Auth::user()->student;
        $day = $this->weekDays[$this->selectedDayIndex];

        // ✅ بررسی اجباری بودن علت عدم انجام پارت
        $unreadCount = count($day['parts']) - count($this->selectedParts);
        if ($unreadCount > 1 && empty(trim($this->missedPartsReason))) {
            $this->addError('missedPartsReason', 'چون بیشتر از یک پارت انجام نشده، توضیح دادن علت عدم انجام پارت‌ها الزامی است.');
            return;
        }

        if (!$this->canSubmitForDate($day['date'])) {
            $this->dispatch('warning', 'مهلت ارسال گزارش این روز تمام شده است.');
            $this->closeReportModal();
            return;
        }
        // ✅ محاسبه امتیاز از session_feedbacks (1-10)
        $avgRating = $this->computedRating;

        $dailyReport = DailyReport::create([
            'student_id' => $student->id,
            'admin_id' => $student->supporter_id ?? $student->advisor_id,
            'session_id' => $this->currentSession->id,
            'weekly_program_id' => $this->currentProgram->id,
            'report_date' => $day['date'],
            'day_of_week' => $day['day_of_week'],
            'is_compensatory' => false,
        ]);

        DailyReportDetail::create([
            'daily_report_id' => $dailyReport->id,
            'phone_hours' => 0,
            'description' => $this->description,
            'rating' => $avgRating,
            'status' => 'pending',
        ]);

        DailyReportFeedback::create([
            'daily_report_id' => $dailyReport->id,
        ]);

        foreach ($day['parts'] as $part) {
            DailyReportPart::create([
                'daily_report_id' => $dailyReport->id,
                'program_part_id' => $part->id,
                'is_read' => in_array($part->id, $this->selectedParts),
                'tests_done' => $this->testsDone[$part->id] ?? 0,
                'part_rating' => null,
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
        $this->compensatoryMissedPartsReason = '';
        $this->compensatoryDescription = '';

        $this->showCompensatoryModal = true;
    }

    public function closeCompensatoryModal()
    {
        $this->showCompensatoryModal = false;
        $this->selectedCompensatoryParts = [];
        $this->compensatoryTestsDone = [];
        $this->compensatoryStep = 1;
        $this->compensatoryMissedPartsReason = '';
        $this->compensatoryDescription = '';
        $this->resetErrorBag();
    }

    public function toggleCompensatoryPart(int $partId)
    {
        if (in_array($partId, $this->selectedCompensatoryParts)) {
            $this->selectedCompensatoryParts = array_values(array_diff($this->selectedCompensatoryParts, [$partId]));
        } else {
            // ✅ بررسی ثبت ساعت مطالعه قبل از انتخاب پارت جبرانی
            if (!in_array($partId, $this->completedStudyParts)) {
                $this->dispatch('warning', 'شما هنوز ساعت مطالعه این پارت را ثبت نکرده‌اید. ابتدا از بخش «ثبت ساعت مطالعه» اقدام کنید.');
                return;
            }
            $this->selectedCompensatoryParts[] = $partId;
        }
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

    public function submitCompensatory()
    {
        if (empty($this->selectedCompensatoryParts)) {
            $this->dispatch('warning', 'لطفاً حداقل یک پارت را انتخاب کنید.');
            return;
        }

        $this->validate([
            'compensatoryDescription' => 'nullable|string|max:350',
            'compensatoryMissedPartsReason' => 'nullable|string|max:2000',

        ], [
            'compensatoryDescription.max' => 'توضیحات نمی‌تواند بیشتر از 350 کاراکتر باشد.',
            'compensatoryMissedPartsReason.max' => 'علت عدم انجام پارت نمی‌تواند بیشتر از 500 کاراکتر باشد.',
        ]);


        $student = Auth::user()->student;
        // ✅ استفاده از تاریخ مؤثر (با احتساب بازه ۶ صبح) بجای تاریخ تقویمی
        $effectiveDate = $this->getEffectiveDate();

        // ✅ محاسبه امتیاز از session_feedbacks برای پارت‌های جبرانی انتخاب شده (1-10)
        $spsList = StudyPartSession::where('student_id', $student->id)
            ->whereIn('program_part_id', $this->selectedCompensatoryParts)
            ->where('is_completed', true)
            ->with('feedback')
            ->get();

        $ratings = $spsList->filter(fn($sps) => $sps->feedback && $sps->feedback->rating > 0)
            ->map(fn($sps) => $sps->feedback->rating);

        $avgRating = $ratings->isNotEmpty() ? round($ratings->avg(), 1) : 0;

        $dailyReport = DailyReport::create([
            'student_id' => $student->id,
            'admin_id' => $student->supporter_id ?? $student->advisor_id,
            'session_id' => $this->currentSession->id,
            'weekly_program_id' => $this->currentProgram->id,
            'report_date' => $effectiveDate,
            'day_of_week' => jdate($effectiveDate)->getDayOfWeek(),
            'is_compensatory' => true,
        ]);

        DailyReportDetail::create([
            'daily_report_id' => $dailyReport->id,
            'phone_hours' => 0,
            'description' => $this->compensatoryDescription ?: 'گزارش جبرانی',
            'rating' => $avgRating,
            'status' => 'pending',
        ]);

        DailyReportFeedback::create([
            'daily_report_id' => $dailyReport->id,
        ]);

        foreach ($this->selectedCompensatoryParts as $partId) {
            DailyReportPart::create([
                'daily_report_id' => $dailyReport->id,
                'program_part_id' => $partId,
                'is_read' => true,
                'tests_done' => $this->compensatoryTestsDone[$partId] ?? 0,
                'part_rating' => null,
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

        $report = DailyReport::with('feedback')
            ->where('id', $reportId)
            ->where('student_id', $studentId)
            ->firstOrFail();

        if (empty($report->feedback->advisor_comment)) {
            $this->dispatch('warning', 'برای این گزارش هنوز نظری ثبت نشده است.');
            return;
        }

        $this->replyReportId = $reportId;
        $this->advisorCommentPreview = $report->feedback->advisor_comment;
        $this->studentReplyPreview = $report->feedback->student_reply;
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

        $report = DailyReport::with('feedback')
            ->where('id', $this->replyReportId)
            ->where('student_id', $studentId)
            ->firstOrFail();

        if (!empty($report->feedback->student_reply)) {
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

        $report->feedback->update([
            'student_reply' => $validated['studentReplyInput'],
            'student_replied_at' => now(),
        ]);

        $this->dispatch('success', 'پاسخ شما ثبت شد.');
        $this->closeReplyModal();
    }

    public function getRatingLabel(float $rating): string
    {
        return match (true) {
            $rating >= 9 => 'عالی',
            $rating >= 7 => 'خوب',
            $rating >= 5 => 'متوسط',
            $rating >= 3 => 'ضعیف',
            $rating > 0  => 'خیلی ضعیف',
            default      => 'ثبت نشده',
        };
    }

    public function getRatingColor(float $rating): string
    {
        return match (true) {
            $rating >= 9 => 'emerald',
            $rating >= 7 => 'blue',
            $rating >= 5 => 'yellow',
            $rating >= 3 => 'orange',
            $rating > 0  => 'red',
            default      => 'gray',
        };
    }

    public function render()
    {
        $studentId = Auth::user()->student->id ?? null;
        $reportsQuery = DailyReport::query()
            ->where('student_id', $studentId)
            ->with(['reportParts.programPart', 'weeklyProgram', 'detail', 'feedback']);

        // فقط گزارش‌های مربوط به جلسه مشاوره فعال فعلی را نمایش بده
        if ($this->currentSession) {
            $reportsQuery->where('session_id', $this->currentSession->id);
        } else {
            // اگر جلسه‌ای وجود ندارد، لیست خالی
            $reportsQuery->whereRaw('1 = 0');
        }

        $reports = $reportsQuery->latest()->paginate(10);

        return view('livewire.client.profile.report', [
            'reports' => $reports,
            'currentSession' => $this->currentSession,
            'currentProgram' => $this->currentProgram,
            'weekDays' => $this->weekDays,
            'missedParts' => $this->missedParts,
            'completedStudyParts' => $this->completedStudyParts,
        ])->layout('layouts.client.app');
    }
}
