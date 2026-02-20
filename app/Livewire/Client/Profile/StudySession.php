<?php

namespace App\Livewire\Client\Profile;

use App\Models\AdvisingSession;
use App\Models\ProgramPart;
use App\Models\StudyPartSession;
use App\Models\WeeklyProgram;
use App\Models\WeeklyProgramRestDay;
use App\Models\PersonalInformation;

use Artesaos\SEOTools\Traits\SEOTools;
use App\Models\SessionFeedback;
use App\Models\CcChapter;
use App\Models\CcField;
use App\Models\CcGrade;
use App\Models\CcSubject;
use App\Models\CcTopic;
use App\Models\MakeupSession;
use Carbon\Carbon;
use Livewire\Component;

class StudySession extends Component
{
    use SEOTools;

    // نمایش برنامه
    public $showProgram = false;
    public $weeklyProgram = null;
    public $programParts = [];

    // تایمر فعال برای پارت‌های عادی
    public $currentPartId = null;
    public $isRunning = false;
    public $startedAt;
    public $pausedAt;
    public $liveSeconds = 0;
    public $remainingSeconds = 0;
    public $targetSeconds = 0;

    // مودال‌ها
    public $showPermissionModal = false;
    public $permissionGranted = false;
    public $showFinishModal = false;
    public $showMakeupFinishModal = false; // مودال جداگانه برای جبرانی

    // وضعیت‌های کامل شده
    public $completedParts = [];
    public $restDays = [];
    public $endsAtTs = null;
    public $pausedAtTs = null;
    public $dayFilter = 'all';

    // --- مطالعه جبرانی ---
    public $showMakeupModal = false;
    public $makeupSearch = '';
    public $makeupGradeId = '';
    public $makeupFieldId = '';
    public $makeupSubjectId = '';
    public $makeupChapterId = '';
    public $makeupTopicId = '';
    public $makeupPartType = 'descriptive';
    public $makeupDurationHours = 0;
    public $makeupDurationMinutes = 30;
    public $makeupNote = '';

    // --- اطلاعات پایه/رشته دانش‌آموز ---
    public $studentGradeNumber = null;
    public $studentFieldSlug = null;
    public $allowedGradeIds = [];
    public $studentFieldId = null;

    // تایمر جبرانی
    public $makeupTimerRunning = false;
    public $makeupStartedAt = null;
    public $makeupPausedAt = null;
    public $makeupTargetSeconds = 0;
    public $makeupLiveSeconds = 0;
    public $makeupRemainingSeconds = 0;
    public $makeupEndsAtTs = null;
    public $makeupPausedAtTs = null;

    // --- بازخورد ---
    public $showFeedbackModal = false;
    public $feedbackRating = 0;
    public $feedbackComment = '';
    public $pendingFeedbackSpsId = null;
    public $pendingFeedbackMakeupId = null;
    public $pendingFeedbackType = null; // 'part' or 'makeup'
    public $pendingFeedbackPartName = '';

    public function mount()
    {
        $this->permissionGranted = (bool)session('study_permission_granted', false);
        $this->loadStudentGradeField();
        $this->loadLatestProgram();
        $this->restoreTimerState();
        $this->checkPendingFeedback();
        $this->seoConfig();
    }

    protected function loadStudentGradeField()
    {
        $user = auth()->user();
        if (!$user) return;

        $personalInfo = PersonalInformation::where('user_id', $user->id)->first();
        if (!$personalInfo) return;

        $this->studentGradeNumber = $personalInfo->grade ? (int)$personalInfo->grade : null;
        $this->studentFieldSlug = $personalInfo->field;

        // تبدیل slug رشته به ID
        if ($this->studentFieldSlug) {
            $field = CcField::where('slug', $this->studentFieldSlug)->first();
            $this->studentFieldId = $field?->id;
        }

        // بارگذاری پایه‌های مجاز: فقط رشته خودش و پایه‌های مساوی یا پایین‌تر
        if ($this->studentGradeNumber && $this->studentFieldId) {
            $this->allowedGradeIds = CcGrade::where('is_active', true)
                ->where('cc_field_id', $this->studentFieldId) // فقط رشته خودش
                ->where('grade_number', '<=', $this->studentGradeNumber) // پایه‌های مساوی یا کمتر
                ->pluck('id')
                ->toArray();
        }
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('ثبت ساعت مطالعه')
            ->setDescription('ابزاری برای حرفه ای ها');
    }

    public function loadLatestProgram()
    {
        if (!auth()->user()->student) {
            return;
        }

        $studentId = auth()->user()->student->id;

        $latestSession = AdvisingSession::where('student_id', $studentId)
            ->where('result_status', AdvisingSession::RESULT_HELD)
            ->orderByDesc('activation_date')
            ->orderByDesc('session_time')
            ->first();

        if (!$latestSession) {
            return;
        }

        $this->weeklyProgram = WeeklyProgram::where('advising_session_id', $latestSession->id)
            ->where('student_id', $studentId)
            ->first();

        if ($this->weeklyProgram) {
            $this->programParts = ProgramPart::where('weekly_program_id', $this->weeklyProgram->id)
                ->with(['ccSubject', 'ccChapter', 'ccTopic'])
                ->orderBy('part_date')
                ->orderBy('part_order')
                ->get();

            $this->restDays = WeeklyProgramRestDay::where('weekly_program_id', $this->weeklyProgram->id)
                ->pluck('day_index')
                ->map(fn($d) => (int)$d)
                ->toArray();

            $this->loadCompletedParts();
        }
    }

    public function loadCompletedParts()
    {
        if (!auth()->user()->student) {
            return;
        }

        $studentId = auth()->user()->student->id;

        $completedSessions = StudyPartSession::where('student_id', $studentId)
            ->where('is_completed', true)
            ->pluck('program_part_id')
            ->toArray();

        $this->completedParts = $completedSessions;
    }

    // ============ بازیابی state تایمر (برای زمانی که صفحه reload میشه) ============

    public function restoreTimerState()
    {
        if (!auth()->user()->student) {
            return;
        }

        // بررسی تایمر عادی
        $timerState = session('active_timer_state');
        if ($timerState && isset($timerState['currentPartId'])) {
            $this->currentPartId = $timerState['currentPartId'];
            $this->targetSeconds = $timerState['targetSeconds'];
            $this->startedAt = Carbon::parse($timerState['startedAt']);
            $this->endsAtTs = $timerState['endsAtTs'];
            $this->isRunning = $timerState['isRunning'];

            if (isset($timerState['pausedAtTs'])) {
                $this->pausedAtTs = $timerState['pausedAtTs'];
            }
        }

        // بررسی تایمر جبرانی
        $makeupTimerState = session('active_makeup_timer_state');
        if ($makeupTimerState && isset($makeupTimerState['topicId'])) {
            $this->makeupTopicId = $makeupTimerState['topicId'];
            $this->makeupPartType = $makeupTimerState['partType'];
            $this->makeupNote = $makeupTimerState['note'] ?? '';
            $this->makeupTargetSeconds = $makeupTimerState['targetSeconds'];
            $this->makeupStartedAt = Carbon::parse($makeupTimerState['startedAt']);
            $this->makeupEndsAtTs = $makeupTimerState['endsAtTs'];
            $this->makeupTimerRunning = $makeupTimerState['isRunning'];

            if (isset($makeupTimerState['pausedAtTs'])) {
                $this->makeupPausedAtTs = $makeupTimerState['pausedAtTs'];
            }
        }
    }

    public function saveTimerState()
    {
        if ($this->currentPartId) {
            session(['active_timer_state' => [
                'currentPartId' => $this->currentPartId,
                'targetSeconds' => $this->targetSeconds,
                'startedAt' => $this->startedAt,
                'endsAtTs' => $this->endsAtTs,
                'isRunning' => $this->isRunning,
                'pausedAtTs' => $this->pausedAtTs,
            ]]);
        }

        if ($this->makeupTimerRunning) {
            session(['active_makeup_timer_state' => [
                'topicId' => $this->makeupTopicId,
                'partType' => $this->makeupPartType,
                'note' => $this->makeupNote,
                'targetSeconds' => $this->makeupTargetSeconds,
                'startedAt' => $this->makeupStartedAt,
                'endsAtTs' => $this->makeupEndsAtTs,
                'isRunning' => $this->makeupTimerRunning,
                'pausedAtTs' => $this->makeupPausedAtTs,
            ]]);
        }
    }

    public function clearTimerState()
    {
        session()->forget(['active_timer_state', 'active_makeup_timer_state']);
    }

    public function toggleProgram()
    {
        $this->showProgram = !$this->showProgram;
    }

    public function requestPermissions()
    {
        $this->showPermissionModal = true;
    }

    public function permissionUnderstood()
    {
        $this->showPermissionModal = false;
        $this->dispatch('request-permissions');
    }

    // ============ پارت‌های عادی (برنامه) ============

    public function startPart($partId)
    {
        // بررسی بازخورد معلق
        if ($this->hasPendingFeedback()) {
            $this->dispatch('error', 'ابتدا بازخورد جلسه قبلی را ثبت کنید.');
            return;
        }

        if (!$this->permissionGranted) {
            $this->requestPermissions();
            return;
        }

        $part = ProgramPart::find($partId);
        if (!$part) {
            $this->dispatch('error', 'پارت مورد نظر یافت نشد.');
            return;
        }

        if (in_array($partId, $this->completedParts)) {
            $this->dispatch('warning', 'این پارت قبلاً کامل شده است.');
            return;
        }

        $this->currentPartId = $partId;
        $this->targetSeconds = (int)$part->duration_minutes * 60;

        $nowTs = now()->timestamp;
        $this->startedAt = now();
        $this->endsAtTs = $nowTs + $this->targetSeconds;
        $this->pausedAt = null;
        $this->pausedAtTs = null;
        $this->isRunning = true;
        $this->liveSeconds = 0;
        $this->remainingSeconds = $this->targetSeconds;

        $this->saveTimerState();
        $this->dispatch('success', 'مطالعه شروع شد.');
    }

    public function pausePart()
    {
        if (!$this->isRunning || !$this->endsAtTs) return;

        $this->isRunning = false;
        $this->pausedAt = now();
        $this->pausedAtTs = now()->timestamp;
        $this->remainingSeconds = max($this->endsAtTs - $this->pausedAtTs, 0);

        $this->saveTimerState();
        $this->dispatch('success', 'تایمر متوقف شد.');
    }

    public function resumePart()
    {
        if ($this->isRunning || !$this->currentPartId || !$this->pausedAtTs || !$this->endsAtTs) return;

        $nowTs = now()->timestamp;
        $pausedDuration = $nowTs - $this->pausedAtTs;
        $this->endsAtTs += $pausedDuration;
        $this->pausedAt = null;
        $this->pausedAtTs = null;
        $this->isRunning = true;

        $this->saveTimerState();
        $this->dispatch('success', 'ادامه مطالعه.');
    }

    public function tick()
    {
        if (!$this->isRunning || !$this->currentPartId || !$this->endsAtTs) {
            return;
        }

        $nowTs = now()->timestamp;
        $this->remainingSeconds = max($this->endsAtTs - $nowTs, 0);
        $this->liveSeconds = max($this->targetSeconds - $this->remainingSeconds, 0);

        if ($this->remainingSeconds === 0) {
            $this->isRunning = false;
            $this->finishPart();
        }

        $this->saveTimerState();
    }

    public function finishPart()
    {
        if (!$this->currentPartId) {
            return;
        }

        $this->isRunning = false;
        $this->showFinishModal = true;
        $this->dispatch('play-alarm');
    }

    public function savePart()
    {
        if (!$this->currentPartId || !auth()->user()->student) {
            return;
        }

        $studentId = auth()->user()->student->id;
        $part = ProgramPart::find($this->currentPartId);

        if (!$part) {
            $this->dispatch('error', 'پارت یافت نشد.');
            return;
        }

        $duration = max($this->targetSeconds - $this->remainingSeconds, 0);

        $session = StudyPartSession::create([
            'student_id' => $studentId,
            'program_part_id' => $this->currentPartId,
            'weekly_program_id' => $this->weeklyProgram->id,
            'started_at' => $this->startedAt,
            'ended_at' => now(),
            'duration_seconds' => $duration,
            'planned_seconds' => $this->targetSeconds,
            'is_completed' => true,
            'completed_at' => now(),
        ]);

        $this->completedParts[] = $this->currentPartId;
        $this->resetTimer();
        $this->showFinishModal = false;

        // باز کردن مودال بازخورد (نمایش مسیر کامل)
        $part->load(['ccSubject.grade', 'ccChapter', 'ccTopic']);
        $fullPath = collect([
            $part->ccSubject?->grade?->name,
            $part->ccSubject?->name,
            $part->ccChapter?->name,
            $part->ccTopic?->name,
            $part->lesson_name,
        ])->filter()->unique()->implode(' » ');
        $this->pendingFeedbackSpsId = $session->id;
        $this->pendingFeedbackType = 'part';
        $this->pendingFeedbackPartName = $fullPath ?: $part->lesson_name;
        $this->feedbackRating = 0;
        $this->feedbackComment = '';
        $this->showFeedbackModal = true;

        $this->dispatch('part-completed');
        $this->dispatch('success', '✅ پارت ثبت شد! لطفاً بازخورد خود را ثبت کنید.');
    }

    public function closeFinishModal()
    {
        $this->showFinishModal = false;
        $this->resetTimer();
    }

    public function cancelPart()
    {
        $this->resetTimer();
        $this->dispatch('success', 'پارت لغو شد.');
    }

    public function resetTimer()
    {
        $this->currentPartId = null;
        $this->isRunning = false;
        $this->startedAt = null;
        $this->pausedAt = null;
        $this->pausedAtTs = null;
        $this->endsAtTs = null;
        $this->liveSeconds = 0;
        $this->remainingSeconds = 0;
        $this->targetSeconds = 0;

        session()->forget('active_timer_state');
    }

    // ============ بازخورد ============

    public function hasPendingFeedback(): bool
    {
        if (!auth()->user()->student) return false;

        $studentId = auth()->user()->student->id;

        // بررسی پارت‌های عادی
        $lastSession = StudyPartSession::where('student_id', $studentId)
            ->where('is_completed', true)
            ->orderByDesc('id')
            ->first();

        if ($lastSession) {
            $hasFeedback = SessionFeedback::where('sps_id', $lastSession->id)->exists();
            if (!$hasFeedback) {
                return true;
            }
        }

        // بررسی جلسات جبرانی
        $lastMakeup = MakeupSession::where('student_id', $studentId)
            ->whereNotNull('ended_at')
            ->orderByDesc('id')
            ->first();

        if ($lastMakeup) {
            $hasFeedback = SessionFeedback::where('makeup_session_id', $lastMakeup->id)->exists();
            if (!$hasFeedback) {
                return true;
            }
        }

        return false;
    }

    public function checkPendingFeedback()
    {
        if (!auth()->user()->student) return;

        $studentId = auth()->user()->student->id;

        // بررسی پارت عادی
        $lastSession = StudyPartSession::where('student_id', $studentId)
            ->where('is_completed', true)
            ->orderByDesc('id')
            ->first();

        if ($lastSession) {
            $hasFeedback = SessionFeedback::where('sps_id', $lastSession->id)->exists();
            if (!$hasFeedback) {
                $part = $lastSession->programPart;
                $partName = 'پارت مطالعه';
                if ($part) {
                    $part->load(['ccSubject.grade', 'ccChapter', 'ccTopic']);
                    $partName = collect([
                        $part->ccSubject?->grade?->name,
                        $part->ccSubject?->name,
                        $part->ccChapter?->name,
                        $part->ccTopic?->name,
                        $part->lesson_name,
                    ])->filter()->unique()->implode(' » ') ?: $part->lesson_name;
                }

                $this->pendingFeedbackSpsId = $lastSession->id;
                $this->pendingFeedbackType = 'part';
                $this->pendingFeedbackPartName = $partName;
                $this->feedbackRating = 0;
                $this->feedbackComment = '';
                $this->showFeedbackModal = true;
                return;
            }
        }

        // بررسی اضافه بر سازمان
        $lastMakeup = MakeupSession::where('student_id', $studentId)
            ->whereNotNull('ended_at')
            ->orderByDesc('id')
            ->first();

        if ($lastMakeup) {
            $hasFeedback = SessionFeedback::where('makeup_session_id', $lastMakeup->id)->exists();
            if (!$hasFeedback) {
                $topic = CcTopic::with(['chapter.subject.grade'])->find($lastMakeup->cc_topic_id);
                $this->pendingFeedbackMakeupId = $lastMakeup->id;
                $this->pendingFeedbackType = 'makeup';
                $this->pendingFeedbackPartName = $topic?->full_path ?? $lastMakeup->ccTopic?->name ?? 'جلسه اضافه بر سازمان';
                $this->feedbackRating = 0;
                $this->feedbackComment = '';
                $this->showFeedbackModal = true;
                return;
            }
        }
    }

    public function submitFeedback()
    {
        if (!auth()->user()->student) {
            return;
        }

        if ($this->feedbackRating < 1 || $this->feedbackRating > 10) {
            $this->dispatch('error', 'لطفاً امتیاز بین ۱ تا ۱۰ انتخاب کنید.');
            return;
        }

        $data = [
            'student_id' => auth()->user()->student->id,
            'rating' => $this->feedbackRating,
            'comment' => $this->feedbackComment ?: null,
        ];

        if ($this->pendingFeedbackType === 'part' && $this->pendingFeedbackSpsId) {
            $data['sps_id'] = $this->pendingFeedbackSpsId;
        } elseif ($this->pendingFeedbackType === 'makeup' && $this->pendingFeedbackMakeupId) {
            $data['makeup_session_id'] = $this->pendingFeedbackMakeupId;
        } else {
            $this->dispatch('error', 'خطا در ثبت بازخورد.');
            return;
        }

        SessionFeedback::create($data);

        $this->showFeedbackModal = false;
        $this->pendingFeedbackSpsId = null;
        $this->pendingFeedbackMakeupId = null;
        $this->pendingFeedbackType = null;
        $this->pendingFeedbackPartName = '';
        $this->feedbackRating = 0;
        $this->feedbackComment = '';

        $this->dispatch('success', 'بازخورد شما ثبت شد. ممنون!');
    }

    public function setFeedbackRating($rating)
    {
        $this->feedbackRating = (int)$rating;
    }

    // ============ مطالعه جبرانی ============

    public function canRecordMakeup(): bool
    {
        return (bool)$this->weeklyProgram;
    }

    private function getTodayDayIndex(): int
    {
        if (!$this->weeklyProgram) return -1;

        $today = now()->toDateString();
        $startDate = Carbon::parse($this->weeklyProgram->start_date);

        for ($i = 0; $i < 8; $i++) {
            $date = $startDate->copy()->addDays($i);
            if ($date->toDateString() === $today) {
                return $i;
            }
        }

        return -1;
    }

    public function openMakeupModal()
    {
        // بررسی بازخورد معلق
        if ($this->hasPendingFeedback()) {
            $this->dispatch('error', 'ابتدا بازخورد جلسه قبلی را ثبت کنید.');
            return;
        }

        if (!$this->canRecordMakeup()) {
            $this->dispatch('error', 'برنامه مطالعاتی فعالی وجود ندارد.');
            return;
        }

        $this->resetMakeupForm();

        $this->showMakeupModal = true;
    }

    public function closeMakeupModal()
    {
        $this->showMakeupModal = false;
        $this->resetMakeupForm();
    }

    public function startMakeupTimer()
    {
        // بررسی بازخورد معلق
        if ($this->hasPendingFeedback()) {
            $this->dispatch('error', 'ابتدا بازخورد جلسه قبلی را ثبت کنید.');
            return;
        }

        if (!$this->permissionGranted) {
            $this->requestPermissions();
            return;
        }

        if (!$this->makeupTopicId || !$this->makeupPartType) {
            $this->dispatch('error', 'لطفاً مبحث و نوع پارت را انتخاب کنید.');
            return;
        }

        $this->makeupTargetSeconds = ((int)$this->makeupDurationHours * 3600) + ((int)$this->makeupDurationMinutes * 60);

        if ($this->makeupTargetSeconds < 60) {
            $this->dispatch('error', 'مدت زمان باید حداقل ۱ دقیقه باشد.');
            return;
        }

        $nowTs = now()->timestamp;
        $this->makeupStartedAt = now();
        $this->makeupEndsAtTs = $nowTs + $this->makeupTargetSeconds;
        $this->makeupPausedAt = null;
        $this->makeupPausedAtTs = null;
        $this->makeupTimerRunning = true;
        $this->makeupLiveSeconds = 0;
        $this->makeupRemainingSeconds = $this->makeupTargetSeconds;

        $this->showMakeupModal = false;
        $this->saveTimerState();
        $this->dispatch('success', 'تایمر جبرانی شروع شد.');
    }

    public function pauseMakeup()
    {
        if (!$this->makeupTimerRunning || !$this->makeupEndsAtTs) return;

        $this->makeupTimerRunning = false;
        $this->makeupPausedAt = now();
        $this->makeupPausedAtTs = now()->timestamp;
        $this->makeupRemainingSeconds = max($this->makeupEndsAtTs - $this->makeupPausedAtTs, 0);

        $this->saveTimerState();
        $this->dispatch('success', 'تایمر جبرانی متوقف شد.');
    }

    public function resumeMakeup()
    {
        if ($this->makeupTimerRunning || !$this->makeupPausedAtTs || !$this->makeupEndsAtTs) return;

        $nowTs = now()->timestamp;
        $pausedDuration = $nowTs - $this->makeupPausedAtTs;
        $this->makeupEndsAtTs += $pausedDuration;
        $this->makeupPausedAt = null;
        $this->makeupPausedAtTs = null;
        $this->makeupTimerRunning = true;

        $this->saveTimerState();
        $this->dispatch('success', 'ادامه مطالعه جبرانی.');
    }

    public function tickMakeup()
    {
        if (!$this->makeupTimerRunning || !$this->makeupEndsAtTs) {
            return;
        }

        $nowTs = now()->timestamp;
        $this->makeupRemainingSeconds = max($this->makeupEndsAtTs - $nowTs, 0);
        $this->makeupLiveSeconds = max($this->makeupTargetSeconds - $this->makeupRemainingSeconds, 0);

        if ($this->makeupRemainingSeconds === 0) {
            $this->makeupTimerRunning = false;
            $this->finishMakeup();
        }

        $this->saveTimerState();
    }

    public function finishMakeup()
    {
        if (!$this->makeupTopicId) {
            return;
        }

        $this->makeupTimerRunning = false;
        $this->showMakeupFinishModal = true;
        $this->dispatch('play-alarm');
    }

    public function saveMakeupSession()
    {
        if (!auth()->user()->student || !$this->makeupTopicId) {
            $this->dispatch('error', 'لطفاً مبحث مورد نظر را انتخاب کنید.');
            return;
        }

        $duration = max($this->makeupTargetSeconds - $this->makeupRemainingSeconds, 0);

        if ($duration < 60) {
            $this->dispatch('error', 'مدت زمان باید حداقل ۱ دقیقه باشد.');
            return;
        }

        $makeup = MakeupSession::create([
            'student_id' => auth()->user()->student->id,
            'cc_topic_id' => $this->makeupTopicId,
            'part_type' => $this->makeupPartType,
            'duration_seconds' => $duration,
            'started_at' => $this->makeupStartedAt,
            'ended_at' => now(),
            'note' => $this->makeupNote ?: null,
            'status' => 'pending',
        ]);

        $this->resetMakeupTimer();
        $this->showMakeupFinishModal = false;

        // باز کردن مودال بازخورد (نمایش مسیر کامل)
        $topic = CcTopic::with(['chapter.subject.grade'])->find($this->makeupTopicId);
        $this->pendingFeedbackMakeupId = $makeup->id;
        $this->pendingFeedbackType = 'makeup';
        $this->pendingFeedbackPartName = $topic?->full_path ?? $topic?->name ?? 'جلسه اضافه بر سازمان';
        $this->feedbackRating = 0;
        $this->feedbackComment = '';
        $this->showFeedbackModal = true;

        $this->dispatch('success', 'جلسه جبرانی ثبت شد! لطفاً بازخورد خود را ثبت کنید.');
    }

    public function closeMakeupFinishModal()
    {
        $this->showMakeupFinishModal = false;
        $this->resetMakeupTimer();
    }

    public function cancelMakeup()
    {
        $this->resetMakeupTimer();
        $this->dispatch('success', 'تایمر جبرانی لغو شد.');
    }

    private function resetMakeupTimer()
    {
        $this->makeupTimerRunning = false;
        $this->makeupStartedAt = null;
        $this->makeupPausedAt = null;
        $this->makeupTargetSeconds = 0;
        $this->makeupLiveSeconds = 0;
        $this->makeupRemainingSeconds = 0;
        $this->makeupEndsAtTs = null;
        $this->makeupPausedAtTs = null;
        $this->resetMakeupForm();

        session()->forget('active_makeup_timer_state');
    }

    public function resetMakeupForm()
    {
        $this->makeupSearch = '';
        $this->makeupGradeId = '';
        $this->makeupFieldId = '';
        $this->makeupSubjectId = '';
        $this->makeupChapterId = '';
        $this->makeupTopicId = '';
        $this->makeupPartType = 'descriptive';
        $this->makeupDurationHours = 0;
        $this->makeupDurationMinutes = 30;
        $this->makeupNote = '';
    }

    // فیلترهای آبشاری (فیلتر شده بر اساس پایه و رشته دانش‌آموز)
    public function getGradesProperty()
    {
        if (empty($this->allowedGradeIds)) {
            return collect();
        }

        return CcGrade::whereIn('id', $this->allowedGradeIds)
            ->where('is_active', true)
            ->orderBy('grade_number', 'desc') // از بزرگ به کوچک (12، 11، 10)
            ->get();
    }

    public function getFieldsProperty()
    {
        // فقط رشته خود دانش‌آموز
        if ($this->studentFieldId) {
            return CcField::where('id', $this->studentFieldId)
                ->where('is_active', true)
                ->get();
        }

        return collect();
    }

    public function getSubjectsProperty()
    {
        if (!$this->makeupGradeId) return collect();

        $query = CcSubject::where('cc_grade_id', $this->makeupGradeId)
            ->orderBy('order');

        // فقط دروس مربوط به رشته دانش‌آموز یا دروس عمومی (بدون رشته)
        if ($this->studentFieldId) {
            $query->where(function ($q) {
                $q->where('cc_field_id', $this->studentFieldId)
                    ->orWhereNull('cc_field_id'); // دروس عمومی
            });
        }

        return $query->get();
    }

    public function getChaptersProperty()
    {
        if (!$this->makeupSubjectId) return collect();

        return CcChapter::where('cc_subject_id', $this->makeupSubjectId)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    public function getTopicsProperty()
    {
        if (!$this->makeupChapterId) return collect();

        return CcTopic::where('cc_chapter_id', $this->makeupChapterId)
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    public function getSearchResultsProperty()
    {
        if (mb_strlen($this->makeupSearch) < 2) return collect();

        $term = $this->makeupSearch;

        $query = CcTopic::where('is_active', true)
            ->where(function ($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                    ->orWhereHas('chapter', function ($cq) use ($term) {
                        $cq->where('name', 'like', "%{$term}%");
                    })
                    ->orWhereHas('chapter.subject', function ($sq) use ($term) {
                        $sq->where('name', 'like', "%{$term}%");
                    });
            })
            ->with(['chapter.subject.grade', 'chapter.subject.ccField']);

        // فیلتر بر اساس پایه‌های مجاز و رشته دانش‌آموز
        $query->whereHas('chapter.subject', function ($sq) {
            // محدود به پایه‌های مجاز
            if (!empty($this->allowedGradeIds)) {
                $sq->whereIn('cc_grade_id', $this->allowedGradeIds);
            }

            // محدود به رشته دانش‌آموز یا دروس عمومی
            if ($this->studentFieldId) {
                $sq->where(function ($fq) {
                    $fq->where('cc_field_id', $this->studentFieldId)
                        ->orWhereNull('cc_field_id'); // دروس عمومی
                });
            }
        });

        return $query->limit(20)->get();
    }

    public function selectSearchTopic($topicId)
    {
        $topic = CcTopic::with(['chapter.subject.grade', 'chapter.subject.ccField'])->find($topicId);
        if (!$topic) return;

        // بررسی اینکه این مبحث مجاز است یا نه
        $subjectGradeId = $topic->chapter->subject->cc_grade_id;
        $subjectFieldId = $topic->chapter->subject->cc_field_id;

        // بررسی پایه
        if (!in_array($subjectGradeId, $this->allowedGradeIds ?? [])) {
            $this->dispatch('error', 'این مبحث برای پایه شما مجاز نیست.');
            return;
        }

        // بررسی رشته (باید یا همان رشته باشد یا عمومی باشد)
        if ($this->studentFieldId && $subjectFieldId && $subjectFieldId != $this->studentFieldId) {
            $this->dispatch('error', 'این مبحث برای رشته شما مجاز نیست.');
            return;
        }

        // همه چیز OK، انتخاب کن
        $this->makeupTopicId = $topic->id;
        $this->makeupChapterId = $topic->chapter->id;
        $this->makeupSubjectId = $topic->chapter->subject->id;
        $this->makeupGradeId = $subjectGradeId;
        $this->makeupFieldId = $subjectFieldId ?? '';
        $this->makeupSearch = '';
    }

    public function updatedMakeupGradeId()
    {
        $this->makeupSubjectId = '';
        $this->makeupChapterId = '';
        $this->makeupTopicId = '';
    }


    public function updatedMakeupSubjectId()
    {
        $this->makeupChapterId = '';
        $this->makeupTopicId = '';
    }

    public function updatedMakeupChapterId()
    {
        $this->makeupTopicId = '';
    }

    public function deleteMakeup($id)
    {
        $makeup = MakeupSession::where('id', $id)
            ->where('student_id', auth()->user()->student->id)
            ->where('status', 'pending')
            ->first();

        if (!$makeup) {
            $this->dispatch('error', 'امکان حذف وجود ندارد.');
            return;
        }

        $makeup->delete();
        $this->dispatch('success', 'جلسه جبرانی حذف شد.');
    }

    public function getMakeupSessionsProperty()
    {
        if (!auth()->user()->student) return collect();

        return MakeupSession::where('student_id', auth()->user()->student->id)
            ->with(['ccTopic.chapter.subject'])
            ->orderByDesc('created_at')
            ->get();
    }

    // جلسات جبرانی امروز
    public function getTodayMakeupSessionsProperty()
    {
        if (!auth()->user()->student) return collect();

        $today = now()->toDateString();

        return MakeupSession::where('student_id', auth()->user()->student->id)
            ->whereDate('created_at', $today)
            ->with(['ccTopic.chapter.subject'])
            ->orderByDesc('created_at')
            ->get();
    }

    // ============ Helper Methods ============

    public function formatClock($seconds)
    {
        $seconds = max((int)$seconds, 0);
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $remainingSeconds);
    }

    public function isPartCompleted($partId)
    {
        return in_array($partId, $this->completedParts);
    }

    public function onPermissionsGranted()
    {
        $this->permissionGranted = true;
        session(['study_permission_granted' => true]);
    }

    public function groupedProgramParts()
    {
        $parts = collect($this->programParts);

        if ($this->dayFilter === 'today') {
            $today = now()->toDateString();
            $parts = $parts->filter(fn($p) => Carbon::parse($p->part_date)->toDateString() === $today);
        }

        if ($this->dayFilter === 'upcoming') {
            $today = now()->startOfDay();
            $parts = $parts->filter(fn($p) => Carbon::parse($p->part_date)->startOfDay()->gte($today));
        }

        return $parts
            ->groupBy(fn($p) => Carbon::parse($p->part_date)->toDateString())
            ->sortKeys();
    }

    public function getProgramDays(): array
    {
        if (!$this->weeklyProgram) {
            return [];
        }

        $days = [];
        $jalaliDayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
        $startDate = $this->weeklyProgram->start_date;
        $freshParts = ProgramPart::where('weekly_program_id', $this->weeklyProgram->id)
            ->with(['ccSubject', 'ccChapter', 'ccTopic'])
            ->orderBy('part_date')
            ->orderBy('part_order')
            ->get();
        for ($i = 0; $i < 8; $i++) {
            $date = Carbon::parse($startDate)->addDays($i);
            $jalaliDate = jdate($date);
            $dayOfWeek = $jalaliDate->getDayOfWeek();
            $isRestDay = in_array($i, $this->restDays);
            $dayParts = $freshParts->filter(fn($p) => (int)$p->day_of_week === $i);

            $days[] = [
                'index' => $i,
                'date' => $date->toDateString(),
                'name' => $jalaliDayNames[$dayOfWeek],
                'jalali_date' => $jalaliDate->format('Y/m/d'),
                'jalali_short' => $jalaliDate->format('d F'),
                'is_rest_day' => $isRestDay,
                'parts' => $dayParts,
                'parts_count' => $dayParts->count(),
            ];
        }

        return $days;
    }

    public function isRestDay(int $dayIndex): bool
    {
        return in_array($dayIndex, array_map('intval', $this->restDays));
    }

    public function render()
    {
        return view('livewire.client.profile.study-session')->layout('layouts.client.app');
    }
}
