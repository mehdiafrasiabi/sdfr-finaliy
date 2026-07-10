<?php

namespace App\Livewire\Client\Profile;

use App\Models\AdvisingSession;
use App\Models\DailyReport;
use App\Models\DailyReportPart;
use App\Models\DailyReportDetail;
use App\Models\DailyReportFeedback;
use App\Models\SessionFeedback;
use App\Models\MakeupSession;
use App\Models\TrialWeek;
use App\Models\WeeklyProgram;
use App\Models\WeeklyProgramRestDay;
use App\Models\StudyPartSession;
use Artesaos\SEOTools\Traits\SEOTools;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    public array $completedStudyPartsMeta = [];
    public array $rejectedCheatPartIds = [];
    public array $alreadyCompensatedPartIds = [];
    // Makeup (extra-organization) sessions for current report day
    public array $currentDayMakeupSessions = [];


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
            ->orderBy('id', 'desc') // تساوی تاریخ: جدیدترین جلسهٔ برگزارشده انتخاب شود
            ->first();

        if ($this->currentSession) {
            $this->currentProgram = WeeklyProgram::where('advising_session_id', $this->currentSession->id)
                ->with(['parts' => function ($query) {
                    $query->orderBy('day_of_week')->orderBy('part_order');
                }])
                ->latest('id')->first();

            if ($this->currentProgram) {
                $this->loadCompletedStudyParts();
                $this->loadWeekDays();
            }
        }
    }

    protected function isActiveTrialReportFlow(): bool
    {
        $student = Auth::user()->student;
        $trialWeek = $student?->trialWeek;

        return (bool) (
            $student?->is_trial
            && $trialWeek
            && $trialWeek->status === TrialWeek::STATUS_PROGRAM_BUILT
            && ! $trialWeek->isExpired()
            && $trialWeek->acquisition_supporter_id
        );
    }

    protected function resolveReportRecipient(): array
    {
        $student = Auth::user()->student;
        $trialWeek = $student?->trialWeek;

        if ($this->isActiveTrialReportFlow() && $trialWeek?->acquisition_supporter_id) {
            return [
                'id' => (int) $trialWeek->acquisition_supporter_id,
                'label' => 'پشتیبان جذب',
            ];
        }

        $advisorId = $this->currentSession?->advisor_id
            ?? $this->currentProgram?->advisor_id
            ?? $student?->advisor_id;

        return [
            'id' => $advisorId ? (int) $advisorId : null,
            'label' => 'مشاور',
        ];
    }

    public function getReportRecipientLabelProperty(): string
    {
        return $this->resolveReportRecipient()['label'] ?: 'مسئول پیگیری';
    }

    protected function loadCompletedStudyParts()
    {
        $student = Auth::user()->student;
        if (!$student || !$this->currentProgram) return;

        $rows = StudyPartSession::where('student_id', $student->id)
            ->where('weekly_program_id', $this->currentProgram->id)
            ->where('is_completed', true)
            ->get(['program_part_id', 'is_early_finish', 'extra_seconds', 'extra_target_seconds', 'is_cheating', 'cheat_status', 'cheat_minutes']);

        $this->completedStudyParts = $rows->pluck('program_part_id')->unique()->values()->toArray();
        $this->completedStudyPartsMeta = $rows->groupBy('program_part_id')->map(function ($g) {
            $latestCheating = $g->where('is_cheating', true)->sortByDesc('id')->first();
            return [
                'is_early_finish'      => $g->contains(fn($r) => (bool)$r->is_early_finish),
                'extra_seconds'        => (int) $g->max('extra_seconds'),
                'extra_target_seconds' => (int) $g->max('extra_target_seconds'),
                'is_cheating'          => $latestCheating !== null,
                'cheat_status'         => $latestCheating?->cheat_status,
                'cheat_minutes'        => (int) ($latestCheating?->cheat_minutes ?? 0),
            ];
        })->toArray();

        // پارت‌هایی که مشاور تقلب آن‌ها را رد کرده — انتخاب اجباری و قفل
        $this->rejectedCheatPartIds = StudyPartSession::where('student_id', $student->id)
            ->where('weekly_program_id', $this->currentProgram->id)
            ->where('cheat_status', StudyPartSession::CHEAT_STATUS_REJECTED)
            ->pluck('program_part_id')->unique()->values()->toArray();

        foreach ($this->rejectedCheatPartIds as $pid) {
            if (!in_array($pid, $this->selectedParts)) {
                $this->selectedParts[] = $pid;
            }
        }
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
            if ($day['is_rest_day']) continue;
            if (!$this->isCompensatoryWindowOpen($day['date'])) continue;

            if ($day['is_locked'] && !$day['is_submitted']) {
                foreach ($day['parts'] as $part) {
                    if (in_array($part->id, $this->alreadyCompensatedPartIds)) continue;

                    $this->missedParts[] = [
                        'part'      => $part,
                        'day_index' => $dayIndex,
                        'day_name'  => $day['name'],
                        'jalali_date' => $day['jalali_short'],
                        'has_study' => in_array($part->id, $this->completedStudyParts), // ✅ اضافه شد
                    ];
                }
            } elseif ($day['is_submitted'] && $day['report']) {
                $unreadPartIds = DailyReportPart::where('daily_report_id', $day['report']->id)
                    ->where('is_read', false)
                    ->where('is_compensatory', false)
                    ->pluck('program_part_id')
                    ->toArray();

                foreach ($day['parts'] as $part) {
                    if (in_array($part->id, $unreadPartIds) && !in_array($part->id, $this->alreadyCompensatedPartIds)) {
                        $this->missedParts[] = [
                            'part'      => $part,
                            'day_index' => $dayIndex,
                            'day_name'  => $day['name'],
                            'jalali_date' => $day['jalali_short'],
                            'has_study' => in_array($part->id, $this->completedStudyParts), // ✅ اضافه شد
                        ];
                    }
                }
            }
        }
    }

    public function openReportModal(int $dayIndex)
    {
        try {
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
            // ✅ انتخاب خودکار پارت‌هایی که ساعت مطالعه برایشان ثبت شده
            $this->selectedParts = collect($day['parts'])
                ->filter(fn($part) => in_array($part->id, $this->completedStudyParts))
                ->pluck('id')
                ->values()
                ->toArray();
            $this->testsDone = [];
            $this->description = '';
            $this->missedPartsReason = '';
            // ✅ بارگذاری پارت‌های اضافه بر سازمان برای این روز
            $this->loadCurrentDayMakeupSessions($day['date']);
            $this->showReportModal = true;
        } catch (\Throwable $e) {
            \Log::error('Error in openReportModal: ' . $e->getMessage());
            $this->dispatch('error', 'خطایی در بارگذاری اطلاعات رخ داد. لطفا صفحه را رفرش کنید.');
            $this->closeReportModal();
        }
    }

    public function closeReportModal()
    {
        $this->showReportModal = false;
        $this->selectedDayIndex = null;
        $this->selectedParts = [];
        $this->testsDone = [];
        $this->description = '';
        $this->missedPartsReason = '';
        $this->currentDayMakeupSessions = [];

        $this->resetErrorBag();
    }
    /**
     * Load MakeupSessions (اضافه بر سازمان) for the given report date window.
     */
    protected function loadCurrentDayMakeupSessions(Carbon $date): void
    {
        $student = Auth::user()->student;
        if (!$student) {
            $this->currentDayMakeupSessions = [];
            return;
        }

        $start = $date->copy()->startOfDay();
        $end   = $date->copy()->addDay()->setHour(self::REPORT_CUTOFF_HOUR)->setMinute(0)->setSecond(0);

        $this->currentDayMakeupSessions = MakeupSession::where('student_id', $student->id)
            ->whereNotNull('ended_at')
            ->whereBetween('ended_at', [$start, $end])
            ->with(['ccChapter.subject', 'ccTopic.chapter.subject'])
            ->get()
            ->map(function ($ms) {
                // فصل از روی فیلد جدید، و برای رکوردهای قدیمی از روی مبحث
                $chapter = $ms->ccChapter ?? $ms->ccTopic?->chapter;
                $subject = $chapter?->subject;
                return [
                    'id'               => $ms->id,
                    'subject_name'     => $subject?->name,
                    'chapter_name'     => $chapter?->name,
                    'part_type_label'  => $ms->part_type_label,
                    'duration_minutes' => $ms->started_at && $ms->ended_at
                        ? (int) $ms->started_at->diffInMinutes($ms->ended_at)
                        : 0,
                ];
            })
            ->toArray();
    }
    public function togglePart(int $partId)
    {
        if (in_array($partId, $this->rejectedCheatPartIds)) {
            $this->dispatch('warning', 'این پارت به دلیل رد شدن گزارش تقلب توسط مشاور قابل تغییر نیست.');
            return;
        }

        if (in_array($partId, $this->selectedParts)) {
            // Parts with logged study hours cannot be deselected
            if (in_array($partId, $this->completedStudyParts)) {
                $this->dispatch('warning', 'پارت‌هایی که ساعت مطالعه برایشان ثبت شده قابل حذف از انتخاب نیستند.');
                return;
            }
            $this->selectedParts = array_values(array_diff($this->selectedParts, [$partId]));
        } else {
            // بررسی ثبت ساعت مطالعه قبل از انتخاب پارت
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
        try {
            if ($this->selectedDayIndex === null || !isset($this->weekDays[$this->selectedDayIndex])) {
                $this->dispatch('warning', 'روز گزارش مشخص نیست. لطفاً دوباره تلاش کنید.');
                return;
            }

            $student = Auth::user()->student;
            if (!$student || !$this->currentSession || !$this->currentProgram) {
                $this->dispatch('warning', 'اطلاعات برنامه یا جلسه برای ثبت گزارش کامل نیست.');
                return;
            }

            $day = $this->weekDays[$this->selectedDayIndex];
            $recipient = $this->resolveReportRecipient();

            if (!$recipient['id']) {
                $this->dispatch('warning', 'هنوز مسئول بررسی گزارش برای شما مشخص نشده است. لطفاً با پشتیبانی تماس بگیرید.');
                return;
            }

            $unreadCount = count($day['parts']) - count($this->selectedParts);

            // Validate test counts for selected parts that have tests
            foreach ($day['parts'] as $part) {
                if (in_array($part->id, $this->selectedParts) && ($part->test_count ?? 0) > 0) {
                    $val = $this->testsDone[$part->id] ?? null;
                    if ($val === null || $val === '') {
                        $this->addError('testsDone.' . $part->id, 'تعداد تست «' . $part->lesson_name . '» را وارد کنید (حداقل ۰).');
                        return;
                    }
                }
            }

            $rules = ['description' => 'nullable|string|max:350'];
            $messages = ['description.max' => 'توضیحات نمی‌تواند بیشتر از 350 کاراکتر باشد.'];

            // missedPartsReason is required when 2+ parts are unread
            if ($unreadCount >= 2) {
                $rules['missedPartsReason'] = 'required|string|min:20|max:500';
                $messages['missedPartsReason.required'] = 'چون بیشتر از یک پارت انجام نشده، وارد کردن علت عدم انجام پارت‌ها الزامی است.';
                $messages['missedPartsReason.min'] = 'علت عدم انجام پارت باید حداقل ۲۰ کاراکتر باشد.';
                $messages['missedPartsReason.max'] = 'علت عدم انجام پارت نمی‌تواند بیشتر از 500 کاراکتر باشد.';
            } else {
                $rules['missedPartsReason'] = 'nullable|string|max:500';
                $messages['missedPartsReason.max'] = 'علت عدم انجام پارت نمی‌تواند بیشتر از 500 کاراکتر باشد.';
            }

            $this->validate($rules, $messages);

            if (!$this->canSubmitForDate($day['date'])) {
                $this->dispatch('warning', 'مهلت ارسال گزارش این روز تمام شده است.');
                $this->closeReportModal();
                return;
            }

            $existingReport = DailyReport::where('student_id', $student->id)
                ->where('weekly_program_id', $this->currentProgram->id)
                ->whereDate('report_date', $day['date'])
                ->where('is_compensatory', false)
                ->exists();

            if ($existingReport) {
                $this->dispatch('warning', 'گزارش این روز قبلاً ثبت شده است.');
                $this->closeReportModal();
                $this->loadWeekDays();
                return;
            }

            // ✅ محاسبه امتیاز از session_feedbacks (1-10)
            $avgRating = $this->computedRating;

            DB::transaction(function () use ($student, $day, $avgRating, $recipient) {
                $dailyReport = DailyReport::create([
                    'student_id' => $student->id,
                    'admin_id' => $recipient['id'],
                    'session_id' => $this->currentSession->id,
                    'weekly_program_id' => $this->currentProgram->id,
                    'report_date' => $day['date'],
                    'day_of_week' => $day['day_of_week'],
                    'is_compensatory' => false,
                ]);

                DailyReportDetail::create([
                    'daily_report_id' => $dailyReport->id,
                    'phone_hours' => 0,
                    'description' => $this->description ?: null,
                    'missed_parts_reason' => $this->missedPartsReason ?: null,
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
            });

            $this->dispatch('success', 'گزارش با موفقیت ثبت شد و برای ' . $recipient['label'] . ' ارسال شد.');
            $this->closeReportModal();
            $this->loadWeekDays();
        } catch (\Throwable $e) {
            \Log::error('Error in submitReport', [
                'message' => $e->getMessage(),
                'student_id' => Auth::user()?->student?->id,
                'selected_day_index' => $this->selectedDayIndex,
            ]);
            $this->dispatch('error', 'خطایی در ثبت گزارش رخ داد. لطفا دوباره تلاش کنید.');
        }
    }

    public function openCompensatoryModal()
    {
        if (empty($this->missedParts)) {
            $this->dispatch('warning', 'پارت از دست رفته‌ای وجود ندارد.');
            return;
        }

        // ✅ به‌روزرسانی داده‌ها قبل از نمایش مودال
        $this->loadCompletedStudyParts();
        $this->loadMissedParts();

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
        try {
            if (empty($this->selectedCompensatoryParts)) {
                $this->dispatch('warning', 'لطفاً حداقل یک پارت را انتخاب کنید.');
                return;
            }

            $this->validate([
                'compensatoryMissedPartsReason' => 'nullable|string|max:2000',
            ], [
                'compensatoryMissedPartsReason.max' => 'علت عدم انجام پارت نمی‌تواند بیشتر از 2000 کاراکتر باشد.',
            ]);


            $student = Auth::user()->student;
            if (!$student || !$this->currentSession || !$this->currentProgram) {
                $this->dispatch('warning', 'اطلاعات برنامه یا جلسه برای ثبت گزارش جبرانی کامل نیست.');
                return;
            }

            $recipient = $this->resolveReportRecipient();
            if (!$recipient['id']) {
                $this->dispatch('warning', 'هنوز مسئول بررسی گزارش برای شما مشخص نشده است. لطفاً با پشتیبانی تماس بگیرید.');
                return;
            }

            // ✅ استفاده از تاریخ مؤثر (با احتساب بازه ۶ صبح) بجای تاریخ تقویمی
            $effectiveDate = $this->getEffectiveDate();

            $existingCompensatory = DailyReport::where('student_id', $student->id)
                ->whereDate('report_date', $effectiveDate)
                ->where('is_compensatory', true)
                ->exists();

            if ($existingCompensatory) {
                $this->dispatch('warning', 'گزارش جبرانی این بازه قبلاً ثبت شده است.');
                $this->closeCompensatoryModal();
                $this->loadWeekDays();
                return;
            }

            // ✅ محاسبه امتیاز از session_feedbacks برای پارت‌های جبرانی انتخاب شده (1-10)
            $spsList = StudyPartSession::where('student_id', $student->id)
                ->whereIn('program_part_id', $this->selectedCompensatoryParts)
                ->where('is_completed', true)
                ->with('feedback')
                ->get();

            $ratings = $spsList->filter(fn($sps) => $sps->feedback && $sps->feedback->rating > 0)
                ->map(fn($sps) => $sps->feedback->rating);

            $avgRating = $ratings->isNotEmpty() ? round($ratings->avg(), 1) : 0;

            DB::transaction(function () use ($student, $effectiveDate, $avgRating, $recipient) {
                $dailyReport = DailyReport::create([
                    'student_id' => $student->id,
                    'admin_id' => $recipient['id'],
                    'session_id' => $this->currentSession->id,
                    'weekly_program_id' => $this->currentProgram->id,
                    'report_date' => $effectiveDate,
                    'day_of_week' => jdate($effectiveDate)->getDayOfWeek(),
                    'is_compensatory' => true,
                ]);

                DailyReportDetail::create([
                    'daily_report_id' => $dailyReport->id,
                    'phone_hours' => 0,
                    'description' => null,
                    'missed_parts_reason' => $this->compensatoryMissedPartsReason ?: null,
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
            });

            $this->dispatch('success', 'گزارش جبرانی با موفقیت ثبت شد و برای ' . $recipient['label'] . ' ارسال شد.');
            $this->closeCompensatoryModal();
            $this->loadWeekDays();
        } catch (\Throwable $e) {
            \Log::error('Error in submitCompensatory', [
                'message' => $e->getMessage(),
                'student_id' => Auth::user()?->student?->id,
                'selected_compensatory_parts' => $this->selectedCompensatoryParts,
            ]);
            $this->dispatch('error', 'خطایی در ثبت گزارش جبرانی رخ داد. لطفا دوباره تلاش کنید.');
        }
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
            ->with(['reportParts.programPart', 'weeklyProgram.parts', 'detail', 'feedback']);

        // فقط گزارش‌های مربوط به جلسه مشاوره فعال فعلی را نمایش بده
        if ($this->currentSession) {
            $reportsQuery->where('session_id', $this->currentSession->id);
        } else {
            // اگر جلسه‌ای وجود ندارد، لیست خالی
            $reportsQuery->whereRaw('1 = 0');
        }

        $reports = $reportsQuery->orderBy('report_date', 'desc')->orderBy('created_at', 'desc')->paginate(10);

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
