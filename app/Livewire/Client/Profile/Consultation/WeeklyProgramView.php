<?php

namespace App\Livewire\Client\Profile\Consultation;

use App\Models\AdvisingSession;
use App\Models\ProgramPart;
use App\Models\StudyPartSession;
use App\Models\WeeklyProgram;
use App\Models\WeeklyProgramRestDay;
use App\Models\PersonalInformation;
use App\Models\SessionFeedback;
use App\Models\CcChapter;
use App\Models\CcField;
use App\Models\CcGrade;
use App\Models\CcSubject;
use App\Models\CcTopic;
use App\Models\MakeupSession;
use Artesaos\SEOTools\Traits\SEOTools;
use Carbon\Carbon;
use Livewire\Component;

class WeeklyProgramView extends Component
{
    use SEOTools;

    private const EARLY_FINISH_THRESHOLD = 0.8;
    private const STUDY_MORE_MAX_SECONDS = 10800;

    public $programId;
    public $weeklyProgram = null;
    public $programParts = [];
    public bool $isActiveProgram = false;

    public string $selectedAlarm = 'Alarmclock';

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
    public $showMakeupFinishModal = false;
    public bool $showCancelConfirmModal = false;

    // وضعیت‌های کامل شده
    public $completedParts = [];
    public $completedPartsMeta = [];
    public $restDays = [];
    public $endsAtTs = null;
    public $pausedAtTs = null;

    // «زودتر تمام کردم»
    public bool $showEarlyFinishConfirmModal = false;
    public bool $pendingIsEarlyFinish = false;

    // «مطالعه بیشتر»
    public bool $showStudyMoreModal = false;
    public int  $studyMoreHours = 0;
    public int  $studyMoreMinutes = 30;
    public bool $showAlarmModal = false;

    // وضعیت فاز اضافی (اضافه بر مشاور)
    public ?int $pendingExtraTargetSeconds = null;
    public bool $isInExtraPhase = false;
    public ?int $extraStartedAtTs = null;
    public ?int $extraEndsAtTs = null;
    public int  $extraTargetSeconds = 0;
    public int  $extraLiveSeconds = 0;
    public int  $extraRemainingSeconds = 0;
    public ?int $extraSpsId = null;

    // پارت/تایمر pending (برای شروع خودکار بعد از دسترسی)
    public $pendingStartPartId = null;
    public $pendingStartMakeup = false;

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
    public $pendingFeedbackType = null;
    public $pendingFeedbackPartName = '';

    public function mount(WeeklyProgram $program)
    {
        $this->programId = $program->id;

        $student = auth()->user()?->student;
        if ($student && (int)$program->student_id !== (int)$student->id) {
            abort(403);
        }

        $this->permissionGranted = (bool)session('study_permission_granted', false);
        $this->loadStudentGradeField();
        $this->loadProgram();
        $this->restoreTimerState();
        $this->selectedAlarm = session('selected_alarm', 'Alarmclock');
        $this->syncTimers();
        $this->checkPendingFeedback();
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('برنامه درسی و ثبت ساعت مطالعه')
            ->setDescription('ابزاری برای حرفه ای ها');
    }

    protected function loadStudentGradeField()
    {
        $user = auth()->user();
        if (!$user) return;

        $personalInfo = PersonalInformation::where('user_id', $user->id)->first();
        if (!$personalInfo) return;

        $this->studentGradeNumber = $personalInfo->grade ? (int)$personalInfo->grade : null;
        $this->studentFieldSlug = $personalInfo->field;

        if ($this->studentFieldSlug) {
            $field = CcField::where('slug', $this->studentFieldSlug)->first();
            $this->studentFieldId = $field?->id;
        }

        if ($this->studentGradeNumber && $this->studentFieldId) {
            $this->allowedGradeIds = CcGrade::where('is_active', true)
                ->where('cc_field_id', $this->studentFieldId)
                ->where('grade_number', '<=', $this->studentGradeNumber)
                ->pluck('id')
                ->toArray();
        }
    }

    public function loadProgram()
    {
        $this->weeklyProgram = WeeklyProgram::find($this->programId);
        if (!$this->weeklyProgram) return;

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
        $this->checkIsActiveProgram();
    }

    protected function checkIsActiveProgram(): void
    {
        $student = auth()->user()?->student;
        if (!$student || !$this->weeklyProgram) {
            $this->isActiveProgram = false;
            return;
        }

        $latestSession = AdvisingSession::where('student_id', $student->id)
            ->where('result_status', AdvisingSession::RESULT_HELD)
            ->orderByDesc('activation_date')
            ->orderByDesc('session_time')
            ->first();

        if (!$latestSession) {
            $this->isActiveProgram = false;
            return;
        }

        $latestProgram = WeeklyProgram::where('advising_session_id', $latestSession->id)
            ->where('student_id', $student->id)
            ->first();

        $this->isActiveProgram = $latestProgram && (int)$latestProgram->id === (int)$this->weeklyProgram->id;
    }

    public function loadCompletedParts()
    {
        if (!auth()->user()->student) return;

        $studentId = auth()->user()->student->id;

        $rows = StudyPartSession::where('student_id', $studentId)
            ->where('is_completed', true)
            ->get(['program_part_id', 'is_early_finish', 'extra_seconds']);

        $this->completedParts = $rows->pluck('program_part_id')->unique()->values()->toArray();
        $this->completedPartsMeta = $rows->groupBy('program_part_id')->map(fn($g) => [
            'is_early_finish' => $g->contains(fn($r) => (bool)$r->is_early_finish),
            'extra_seconds'   => (int) $g->max('extra_seconds'),
        ])->toArray();
    }

    public function restoreTimerState()
    {
        if (!auth()->user()->student) return;

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

            $this->pendingIsEarlyFinish = (bool)($timerState['pendingIsEarlyFinish'] ?? false);
            $this->pendingExtraTargetSeconds = $timerState['pendingExtraTargetSeconds'] ?? null;
            $this->isInExtraPhase = (bool)($timerState['isInExtraPhase'] ?? false);
            $this->extraStartedAtTs = $timerState['extraStartedAtTs'] ?? null;
            $this->extraEndsAtTs = $timerState['extraEndsAtTs'] ?? null;
            $this->extraTargetSeconds = (int)($timerState['extraTargetSeconds'] ?? 0);
            $this->extraSpsId = $timerState['extraSpsId'] ?? null;

            // اگه تایمر تموم شده بود ولی ثبت نشده، مودال رو نشون بده
            $nowTs = now()->timestamp;

            // 🛡️ محافظت: اگر وضعیت تایمر خیلی قدیمی است (بیش از ۲۴ ساعت از پایانش گذشته)،
            // این یک نشستِ رهاشده‌ی قدیمی است (لایف‌تایم سشن یک‌ساله است) — پاکش کن تا
            // مودال «پایان» به‌خودیِ‌خود و بی‌دلیل باز نشود.
            $effectiveEnd = $this->isInExtraPhase ? ($this->extraEndsAtTs ?? $this->endsAtTs) : $this->endsAtTs;
            if ($effectiveEnd && ($nowTs - (int)$effectiveEnd) > 86400) {
                $this->resetTimer();
            } elseif ($this->isInExtraPhase) {
                if ($this->extraEndsAtTs && $nowTs >= $this->extraEndsAtTs && !$this->showFinishModal) {
                    $this->extraRemainingSeconds = 0;
                    $this->extraLiveSeconds = $this->extraTargetSeconds;
                    $this->isRunning = false;
                    $this->showFinishModal = true;
                }
            } elseif ($this->endsAtTs && $nowTs >= $this->endsAtTs && !$this->showFinishModal) {
                $this->remainingSeconds = 0;
                $this->liveSeconds = $this->targetSeconds;
                $this->isRunning = false;
                if ($this->pendingExtraTargetSeconds !== null) {
                    // در زمان غیبت کاربر، تایم اصلی تموم شده اما هنوز فاز ۲ شروع نشده — همان لحظه بازگشت می‌بایست شروع شود
                    $this->startExtraPhase();
                } else {
                    $this->showFinishModal = true;
                }
            }
        }

        $makeupTimerState = session('active_makeup_timer_state');
        if ($makeupTimerState && isset($makeupTimerState['chapterId'])) {
            $this->makeupChapterId = $makeupTimerState['chapterId'];
            $this->makeupPartType = $makeupTimerState['partType'];
            $this->makeupNote = $makeupTimerState['note'] ?? '';
            $this->makeupTargetSeconds = $makeupTimerState['targetSeconds'];
            $this->makeupStartedAt = Carbon::parse($makeupTimerState['startedAt']);
            $this->makeupEndsAtTs = $makeupTimerState['endsAtTs'];
            $this->makeupTimerRunning = $makeupTimerState['isRunning'];

            if (isset($makeupTimerState['pausedAtTs'])) {
                $this->makeupPausedAtTs = $makeupTimerState['pausedAtTs'];
            }

            $nowTs = now()->timestamp;
            // 🛡️ وضعیت جبرانیِ خیلی قدیمی (بیش از ۲۴ ساعت) را پاک کن
            if ($this->makeupEndsAtTs && ($nowTs - (int)$this->makeupEndsAtTs) > 86400) {
                $this->resetMakeupTimer();
            } elseif ($this->makeupEndsAtTs && $nowTs >= $this->makeupEndsAtTs && !$this->showMakeupFinishModal) {
                $this->makeupRemainingSeconds = 0;
                $this->makeupLiveSeconds = $this->makeupTargetSeconds;
                $this->makeupTimerRunning = false;
                $this->showMakeupFinishModal = true;
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
                'pendingIsEarlyFinish' => $this->pendingIsEarlyFinish,
                'pendingExtraTargetSeconds' => $this->pendingExtraTargetSeconds,
                'isInExtraPhase' => $this->isInExtraPhase,
                'extraStartedAtTs' => $this->extraStartedAtTs,
                'extraEndsAtTs' => $this->extraEndsAtTs,
                'extraTargetSeconds' => $this->extraTargetSeconds,
                'extraSpsId' => $this->extraSpsId,
                'selectedAlarm' => $this->selectedAlarm,
            ]]);
        }

        if ($this->makeupTimerRunning || $this->makeupPausedAtTs) {
            session(['active_makeup_timer_state' => [
                'chapterId' => $this->makeupChapterId,
                'partType' => $this->makeupPartType,
                'note' => $this->makeupNote,
                'targetSeconds' => $this->makeupTargetSeconds,
                'startedAt' => $this->makeupStartedAt,
                'endsAtTs' => $this->makeupEndsAtTs,
                'isRunning' => $this->makeupTimerRunning,
                'pausedAtTs' => $this->makeupPausedAtTs,
                'selectedAlarm' => $this->selectedAlarm,
            ]]);
        }
    }

    // ============ مدیریت دسترسی ============

    public function requestPermissions()
    {
        $this->showPermissionModal = true;
    }

    public function permissionUnderstood()
    {
        $this->showPermissionModal = false;
        $this->dispatch('request-permissions');
    }

    public function onPermissionsGranted()
    {
        $this->permissionGranted = true;
        session(['study_permission_granted' => true]);

        // شروع خودکار پارت یا تایمر جبرانی که منتظر دسترسی بود
        if ($this->pendingStartPartId) {
            $partId = $this->pendingStartPartId;
            $this->pendingStartPartId = null;
            $this->doStartPart($partId);
        } elseif ($this->pendingStartMakeup) {
            $this->pendingStartMakeup = false;
            $this->doStartMakeupTimer();
        }
    }

    // ============ پارت‌های عادی (برنامه) ============

    public function startPart($partId)
    {
        if (!$this->isActiveProgram) {
            $this->dispatch('error', 'فقط برنامه فعال هفته جاری قابل ثبت مطالعه است.');
            return;
        }

        if ($this->hasPendingFeedback()) {
            $this->dispatch('error', 'ابتدا بازخورد جلسه قبلی را ثبت کنید.');
            return;
        }

        if (!$this->permissionGranted) {
            $this->pendingStartPartId = $partId;
            $this->requestPermissions();
            return;
        }

        $this->doStartPart($partId);
    }

    private function doStartPart($partId)
    {
        $part = ProgramPart::find($partId);
        if (!$part || (int)$part->weekly_program_id !== (int)$this->weeklyProgram?->id) {
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
        if (!$this->isRunning) return;

        if ($this->isInExtraPhase) {
            if (!$this->extraEndsAtTs) return;
            $this->isRunning = false;
            $this->pausedAt = now();
            $this->pausedAtTs = now()->timestamp;
            $this->extraRemainingSeconds = max($this->extraEndsAtTs - $this->pausedAtTs, 0);
            $this->extraLiveSeconds = max($this->extraTargetSeconds - $this->extraRemainingSeconds, 0);
        } else {
            if (!$this->endsAtTs) return;
            $this->isRunning = false;
            $this->pausedAt = now();
            $this->pausedAtTs = now()->timestamp;
            $this->remainingSeconds = max($this->endsAtTs - $this->pausedAtTs, 0);
        }

        $this->saveTimerState();
        $this->dispatch('success', 'تایمر متوقف شد.');
    }

    public function resumePart()
    {
        if ($this->isRunning || !$this->currentPartId || !$this->pausedAtTs) return;

        $nowTs = now()->timestamp;
        $pausedDuration = $nowTs - $this->pausedAtTs;

        if ($this->isInExtraPhase) {
            if (!$this->extraEndsAtTs) return;
            $this->extraEndsAtTs += $pausedDuration;
        } else {
            if (!$this->endsAtTs) return;
            $this->endsAtTs += $pausedDuration;
        }

        $this->pausedAt = null;
        $this->pausedAtTs = null;
        $this->isRunning = true;

        $this->saveTimerState();
        $this->dispatch('success', 'ادامه مطالعه.');
    }

    // ============ زودتر تمام کردم ============

    public function getCanShowEarlyOrMoreProperty(): bool
    {
        return $this->currentPartId !== null
            && $this->targetSeconds > 0
            && !$this->isInExtraPhase
            && $this->pendingExtraTargetSeconds === null
            && !$this->showFinishModal
            && $this->remainingSeconds > 0
            && $this->liveSeconds >= (int) floor($this->targetSeconds * self::EARLY_FINISH_THRESHOLD);
    }

    public function setAlarm(string $alarmName): void
    {
        $this->selectedAlarm = $alarmName;
        session(['selected_alarm' => $alarmName]);
        $this->dispatch('alarm-selected', alarm: $alarmName);
    }

    public function openEarlyFinishConfirm(): void
    {
        if (!$this->canShowEarlyOrMore) return;
        $this->showEarlyFinishConfirmModal = true;
    }

    public function closeEarlyFinishConfirm(): void
    {
        $this->showEarlyFinishConfirmModal = false;
    }

    public function confirmEarlyFinish(): void
    {
        if (!$this->currentPartId) return;
        $this->isRunning = false;
        $this->pendingIsEarlyFinish = true;
        $this->showEarlyFinishConfirmModal = false;
        $this->showFinishModal = true;
        $this->saveTimerState();
        $this->dispatch('play-alarm');
    }

    // ============ مطالعه بیشتر ============

    public function openStudyMoreModal(): void
    {
        if (!$this->canShowEarlyOrMore) return;
        $this->studyMoreHours = 0;
        $this->studyMoreMinutes = 30;
        $this->showStudyMoreModal = true;
    }

    public function closeStudyMoreModal(): void
    {
        $this->showStudyMoreModal = false;
    }

    public function confirmStudyMore(): void
    {
        if (!$this->canShowEarlyOrMore) return;

        $secs = ((int)$this->studyMoreHours) * 3600 + ((int)$this->studyMoreMinutes) * 60;
        if ($secs < 60) {
            $this->dispatch('error', 'حداقل ۱ دقیقه را انتخاب کنید.');
            return;
        }
        if ($secs > self::STUDY_MORE_MAX_SECONDS) {
            $secs = self::STUDY_MORE_MAX_SECONDS;
        }

        $this->pendingExtraTargetSeconds = $secs;
        $this->showStudyMoreModal = false;
        $this->saveTimerState();
        $this->dispatch('success', 'تایم اضافی بعد از پایان مطالعه فعلی شروع می‌شه.');
    }

    private function startExtraPhase(): void
    {
        if (!$this->currentPartId || !$this->pendingExtraTargetSeconds) return;
        if (!auth()->user()->student || !$this->weeklyProgram) return;

        $studentId = auth()->user()->student->id;
        $part = ProgramPart::find($this->currentPartId);
        if (!$part) return;

        $sps = StudyPartSession::create([
            'student_id'           => $studentId,
            'program_part_id'      => $this->currentPartId,
            'weekly_program_id'    => $this->weeklyProgram->id,
            'started_at'           => $this->startedAt,
            'ended_at'             => now(),
            'duration_seconds'     => $this->targetSeconds,
            'planned_seconds'      => $this->targetSeconds,
            'is_completed'         => true,
            'completed_at'         => now(),
            'is_early_finish'      => false,
            'extra_target_seconds' => $this->pendingExtraTargetSeconds,
            'extra_started_at'     => now(),
        ]);

        $this->extraSpsId              = $sps->id;
        $this->extraTargetSeconds      = (int)$this->pendingExtraTargetSeconds;
        $this->extraStartedAtTs        = now()->timestamp;
        $this->extraEndsAtTs           = $this->extraStartedAtTs + $this->extraTargetSeconds;
        $this->extraLiveSeconds        = 0;
        $this->extraRemainingSeconds   = $this->extraTargetSeconds;
        $this->isInExtraPhase          = true;
        $this->isRunning               = true;
        $this->pausedAtTs              = null;
        $this->pendingExtraTargetSeconds = null;

        if (!in_array($this->currentPartId, $this->completedParts)) {
            $this->completedParts[] = $this->currentPartId;
        }
        $this->completedPartsMeta[$this->currentPartId] = [
            'is_early_finish' => false,
            'extra_seconds'   => 0,
        ];

        $this->saveTimerState();
        $this->dispatch('success', '🎓 تایم اصلی تموم شد! تایم اضافه بر مشاور شروع شد.');
    }

    public function finishPart()
    {
        if (!$this->currentPartId) return;

        $this->isRunning = false;
        $this->showFinishModal = true;
        $this->dispatch('play-alarm');
    }

    public function savePart()
    {
        if (!$this->currentPartId || !auth()->user()->student) return;

        $studentId = auth()->user()->student->id;
        $part = ProgramPart::find($this->currentPartId);

        if (!$part) {
            $this->dispatch('error', 'پارت یافت نشد.');
            return;
        }

        // مسیر A: پایان فاز ۲ (اضافه بر مشاور) — آپدیت همان رکورد فاز اول
        if ($this->isInExtraPhase && $this->extraSpsId) {
            $sps = StudyPartSession::find($this->extraSpsId);
            if ($sps) {
                $extraDuration = max($this->extraTargetSeconds - $this->extraRemainingSeconds, 0);
                $sps->update([
                    'extra_seconds'  => $extraDuration,
                    'extra_ended_at' => now(),
                ]);

                $this->completedPartsMeta[$this->currentPartId] = [
                    'is_early_finish' => false,
                    'extra_seconds'   => $extraDuration,
                ];

                $fullPath = $this->buildPartPath($part);
                $this->pendingFeedbackSpsId    = $sps->id;
                $this->pendingFeedbackType     = 'part';
                $this->pendingFeedbackPartName = $fullPath ?: $part->lesson_name;
                $this->feedbackRating          = 0;
                $this->feedbackComment         = '';
                $this->showFeedbackModal       = true;
            }
            $this->resetTimer();
            $this->showFinishModal = false;
            $this->dispatch('success', '✅ پارت و مطالعه اضافه بر مشاور ثبت شد!');
            return;
        }

        // مسیر B و C: مسیر معمولی یا «زودتر تمام کردم»
        $isEarly = (bool)$this->pendingIsEarlyFinish;
        $duration = max($this->targetSeconds - $this->remainingSeconds, 0);

        $session = StudyPartSession::create([
            'student_id'       => $studentId,
            'program_part_id'  => $this->currentPartId,
            'weekly_program_id'=> $this->weeklyProgram->id,
            'started_at'       => $this->startedAt,
            'ended_at'         => now(),
            'duration_seconds' => $duration,
            'planned_seconds'  => $this->targetSeconds,
            'is_completed'     => true,
            'completed_at'     => now(),
            'is_early_finish'  => $isEarly,
        ]);

        if (!in_array($this->currentPartId, $this->completedParts)) {
            $this->completedParts[] = $this->currentPartId;
        }
        $this->completedPartsMeta[$this->currentPartId] = [
            'is_early_finish' => $isEarly,
            'extra_seconds'   => 0,
        ];

        $this->resetTimer();
        $this->showFinishModal = false;

        $fullPath = $this->buildPartPath($part);

        $this->pendingFeedbackSpsId    = $session->id;
        $this->pendingFeedbackType     = 'part';
        $this->pendingFeedbackPartName = $fullPath ?: $part->lesson_name;
        $this->feedbackRating          = 0;
        $this->feedbackComment         = '';
        $this->showFeedbackModal       = true;

        $msg = $isEarly
            ? '✅ پارت زودتر تمام شد و ثبت گردید. لطفاً بازخورد را ثبت کنید.'
            : '✅ پارت ثبت شد! لطفاً بازخورد خود را ثبت کنید.';
        $this->dispatch('success', $msg);
    }

    protected function buildPartPath(ProgramPart $part): string
    {
        // برای دانش‌آموز فقط تا سطح فصل نمایش داده می‌شود (بدون مبحث)
        $part->load(['ccSubject.grade', 'ccChapter']);
        return collect([
            $part->ccSubject?->grade?->name,
            $part->ccSubject?->name,
            $part->ccChapter?->name,
            $part->lesson_name,
        ])->filter()->unique()->implode(' » ');
    }

    public function closeFinishModal()
    {
        $this->showFinishModal = false;
        $this->resetTimer();
    }

    public function openCancelConfirm()
    {
        $this->showCancelConfirmModal = true;
    }

    public function closeCancelConfirm()
    {
        $this->showCancelConfirmModal = false;
    }

    public function cancelPart()
    {
        $this->showCancelConfirmModal = false;
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

        // پاکسازی وضعیت‌های زودتر/اضافه
        $this->pendingIsEarlyFinish      = false;
        $this->pendingExtraTargetSeconds = null;
        $this->isInExtraPhase            = false;
        $this->extraStartedAtTs          = null;
        $this->extraEndsAtTs             = null;
        $this->extraTargetSeconds        = 0;
        $this->extraLiveSeconds          = 0;
        $this->extraRemainingSeconds     = 0;
        $this->extraSpsId                = null;
        $this->showEarlyFinishConfirmModal = false;
        $this->showStudyMoreModal        = false;
        $this->showCancelConfirmModal    = false;

        session()->forget('active_timer_state');
    }

    // ============ بازخورد ============

    public function hasPendingFeedback(): bool
    {
        if (!auth()->user()->student) return false;

        $studentId = auth()->user()->student->id;

        $lastSession = StudyPartSession::where('student_id', $studentId)
            ->where('is_completed', true)
            ->orderByDesc('id')
            ->first();

        if ($lastSession && !SessionFeedback::where('sps_id', $lastSession->id)->exists()) {
            return true;
        }

        $lastMakeup = MakeupSession::where('student_id', $studentId)
            ->whereNotNull('ended_at')
            ->orderByDesc('id')
            ->first();

        if ($lastMakeup && !SessionFeedback::where('makeup_session_id', $lastMakeup->id)->exists()) {
            return true;
        }

        return false;
    }

    public function checkPendingFeedback()
    {
        if (!auth()->user()->student) return;

        $studentId = auth()->user()->student->id;

        $lastSession = StudyPartSession::where('student_id', $studentId)
            ->where('is_completed', true)
            ->orderByDesc('id')
            ->first();

        if ($lastSession && !SessionFeedback::where('sps_id', $lastSession->id)->exists()) {
            $part = $lastSession->programPart;
            $partName = 'پارت مطالعه';
            if ($part) {
                $partName = $this->buildPartPath($part) ?: $part->lesson_name;
            }

            $this->pendingFeedbackSpsId = $lastSession->id;
            $this->pendingFeedbackType = 'part';
            $this->pendingFeedbackPartName = $partName;
            $this->feedbackRating = 0;
            $this->feedbackComment = '';
            $this->showFeedbackModal = true;
            $this->dispatch('open-feedback-modal');
            return;
        }

        $lastMakeup = MakeupSession::where('student_id', $studentId)
            ->whereNotNull('ended_at')
            ->orderByDesc('id')
            ->first();

        if ($lastMakeup && !SessionFeedback::where('makeup_session_id', $lastMakeup->id)->exists()) {
            $topic = CcTopic::with(['chapter.subject.grade'])->find($lastMakeup->cc_topic_id);
            $this->pendingFeedbackMakeupId = $lastMakeup->id;
            $this->pendingFeedbackType = 'makeup';
            $this->pendingFeedbackPartName = collect([
                $topic?->chapter?->subject?->name,
                $topic?->chapter?->name,
            ])->filter()->implode(' » ') ?: 'جلسه اضافه بر سازمان';
            $this->feedbackRating = 0;
            $this->feedbackComment = '';
            $this->showFeedbackModal = true;
            $this->dispatch('open-feedback-modal');
        }
    }

    public function submitFeedback()
    {
        if (!auth()->user()->student) return;

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

    public function openMakeupModal()
    {
        if (!$this->isActiveProgram) {
            $this->dispatch('error', 'فقط در برنامه فعال هفته جاری امکان ثبت وجود دارد.');
            return;
        }

        if ($this->hasPendingFeedback()) {
            $this->dispatch('error', 'ابتدا بازخورد جلسه قبلی را ثبت کنید.');
            return;
        }

        if (!$this->weeklyProgram) {
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
        if ($this->hasPendingFeedback()) {
            $this->dispatch('error', 'ابتدا بازخورد جلسه قبلی را ثبت کنید.');
            return;
        }

        if (!$this->permissionGranted) {
            $this->pendingStartMakeup = true;
            $this->requestPermissions();
            return;
        }

        $this->doStartMakeupTimer();
    }

    private function doStartMakeupTimer()
    {
        if (!$this->makeupChapterId || !$this->makeupPartType) {
            $this->dispatch('error', 'لطفاً فصل و نوع پارت را انتخاب کنید.');
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

    public function finishMakeup()
    {
        if (!$this->makeupChapterId) return;

        $this->makeupTimerRunning = false;
        $this->showMakeupFinishModal = true;
        $this->dispatch('play-alarm');
    }

    public function saveMakeupSession()
    {
        if (!auth()->user()->student || !$this->makeupChapterId) {
            $this->dispatch('error', 'لطفاً فصل مورد نظر را انتخاب کنید.');
            return;
        }

        $duration = max($this->makeupTargetSeconds - $this->makeupRemainingSeconds, 0);

        if ($duration < 60) {
            $this->dispatch('error', 'مدت زمان باید حداقل ۱ دقیقه باشد.');
            return;
        }

        $makeup = MakeupSession::create([
            'student_id' => auth()->user()->student->id,
            'cc_chapter_id' => $this->makeupChapterId,
            'part_type' => $this->makeupPartType,
            'duration_seconds' => $duration,
            'started_at' => $this->makeupStartedAt,
            'ended_at' => now(),
            'note' => $this->makeupNote ?: null,
            'status' => 'pending',
        ]);

        $chapter = CcChapter::with(['subject'])->find($this->makeupChapterId);

        $this->resetMakeupTimer();
        $this->showMakeupFinishModal = false;

        $this->pendingFeedbackMakeupId = $makeup->id;
        $this->pendingFeedbackType = 'makeup';
        $this->pendingFeedbackPartName = collect([
            $chapter?->subject?->name,
            $chapter?->name,
        ])->filter()->implode(' » ') ?: 'جلسه اضافه بر سازمان';
        $this->feedbackRating = 0;
        $this->feedbackComment = '';
        $this->showFeedbackModal = true;
        $this->dispatch('open-feedback-modal');

        $this->dispatch('success', 'جلسه جبرانی ثبت شد! لطفاً بازخورد خود را ثبت کنید.');
    }

    public function closeMakeupFinishModal()
    {
        $this->showMakeupFinishModal = false;
        $this->resetMakeupTimer();
    }

    public function cancelMakeup()
    {
        $this->showCancelConfirmModal = false;
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

    // ============ Computed Properties (فیلترهای آبشاری) ============

    public function getGradesProperty()
    {
        if (empty($this->allowedGradeIds)) return collect();

        return CcGrade::whereIn('id', $this->allowedGradeIds)
            ->where('is_active', true)
            ->orderBy('grade_number', 'desc')
            ->get();
    }

    public function getFieldsProperty()
    {
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

        if ($this->studentFieldId) {
            $query->where(function ($q) {
                $q->where('cc_field_id', $this->studentFieldId)
                    ->orWhereNull('cc_field_id');
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
        $results = [];
        $seen = [];

        // 1. Search subjects → show their chapters first
        $subjectQuery = CcSubject::where('name', 'like', "%{$term}%")
            ->with(['grade', 'chapters' => fn($q) => $q->where('is_active', true)->orderBy('order')]);

        if (!empty($this->allowedGradeIds)) {
            $subjectQuery->whereIn('cc_grade_id', $this->allowedGradeIds);
        }
        if ($this->studentFieldId) {
            $subjectQuery->where(function ($q) {
                $q->where('cc_field_id', $this->studentFieldId)->orWhereNull('cc_field_id');
            });
        }

        foreach ($subjectQuery->limit(5)->get() as $subject) {
            foreach ($subject->chapters as $chapter) {
                $key = 'chapter_' . $chapter->id;
                if (isset($seen[$key])) continue;
                $seen[$key] = true;
                $results[] = [
                    'type'       => 'chapter',
                    'sort'       => 1,
                    'id'         => $chapter->id,
                    'name'       => $chapter->name,
                    'label'      => $subject->name . ' / ' . $chapter->name,
                    'subject_id' => $subject->id,
                    'chapter_id' => $chapter->id,
                    'topic_id'   => null,
                ];
            }
        }

        // 2. Search chapters → show their topics
        $chapterQuery = CcChapter::where('is_active', true)
            ->where('name', 'like', "%{$term}%")
            ->with(['subject.grade', 'topics' => fn($q) => $q->where('is_active', true)->whereNull('parent_id')->orderBy('order')])
            ->whereHas('subject', function ($sq) {
                if (!empty($this->allowedGradeIds)) {
                    $sq->whereIn('cc_grade_id', $this->allowedGradeIds);
                }
                if ($this->studentFieldId) {
                    $sq->where(function ($fq) {
                        $fq->where('cc_field_id', $this->studentFieldId)->orWhereNull('cc_field_id');
                    });
                }
            });

        foreach ($chapterQuery->limit(5)->get() as $chapter) {
            $subject = $chapter->subject;
            if (!$subject) continue;

            $chapterKey = 'chapter_' . $chapter->id;
            if (!isset($seen[$chapterKey])) {
                $seen[$chapterKey] = true;
                $results[] = [
                    'type'       => 'chapter',
                    'sort'       => 1,
                    'id'         => $chapter->id,
                    'name'       => $chapter->name,
                    'label'      => $subject->name . ' / ' . $chapter->name,
                    'subject_id' => $subject->id,
                    'chapter_id' => $chapter->id,
                    'topic_id'   => null,
                ];
            }
        }

        return collect(array_slice($results, 0, 20));
    }

    public function selectSearchResult($type, $id)
    {
        if ($type === 'chapter') {
            $chapter = CcChapter::with(['subject.grade'])->find($id);
            if (!$chapter) return;
            $subject = $chapter->subject;
            if (!$subject) return;

            if (!in_array($subject->cc_grade_id, $this->allowedGradeIds ?? [])) {
                $this->dispatch('error', 'این فصل برای پایه شما مجاز نیست.');
                return;
            }
            $this->makeupGradeId   = $subject->cc_grade_id;
            $this->makeupSubjectId = $subject->id;
            $this->makeupChapterId = $chapter->id;
            $this->makeupTopicId   = '';
            $this->makeupSearch    = '';
        } else {
            $topic = CcTopic::with(['chapter.subject.grade'])->find($id);
            if (!$topic) return;
            $subjectGradeId = $topic->chapter->subject->cc_grade_id;
            $subjectFieldId = $topic->chapter->subject->cc_field_id;

            if (!in_array($subjectGradeId, $this->allowedGradeIds ?? [])) {
                $this->dispatch('error', 'این مبحث برای پایه شما مجاز نیست.');
                return;
            }

            if ($this->studentFieldId && $subjectFieldId && $subjectFieldId != $this->studentFieldId) {
                $this->dispatch('error', 'این مبحث برای رشته شما مجاز نیست.');
                return;
            }

            $this->makeupTopicId   = $topic->id;
            $this->makeupChapterId = $topic->chapter->id;
            $this->makeupSubjectId = $topic->chapter->subject->id;
            $this->makeupGradeId   = $subjectGradeId;
            $this->makeupFieldId   = $subjectFieldId ?? '';
            $this->makeupSearch    = '';
        }
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

    // ============ Helper Methods ============

    public function formatClock($seconds)
    {
        $seconds = max((int)$seconds, 0);
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $remainingSeconds);
    }

    public function formatDuration($seconds): string
    {
        $seconds = max((int)$seconds, 0);
        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);

        if ($hours > 0 && $minutes > 0) return "{$hours} ساعت و {$minutes} دقیقه";
        if ($hours > 0) return "{$hours} ساعت";
        return "{$minutes} دقیقه";
    }

    public function isPartCompleted($partId)
    {
        return in_array($partId, $this->completedParts);
    }

    public function getProgramDays(): array
    {
        if (!$this->weeklyProgram) return [];

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
                'total_hours' => round($dayParts->sum('duration_minutes') / 60, 1),
                'total_tests' => $dayParts->sum('test_count') ?? 0,
            ];
        }

        return $days;
    }

    public function syncTimers()
    {
        $nowTs = now()->timestamp;

        // فاز ۱ (تایمر اصلی)
        if (!$this->isInExtraPhase && $this->currentPartId && $this->targetSeconds && $this->endsAtTs) {
            if ($this->isRunning) {
                $this->remainingSeconds = max($this->endsAtTs - $nowTs, 0);
                $this->liveSeconds = max($this->targetSeconds - $this->remainingSeconds, 0);

                if ($this->remainingSeconds === 0) {
                    if ($this->pendingExtraTargetSeconds !== null) {
                        $this->startExtraPhase();
                    } elseif (!$this->showFinishModal) {
                        $this->isRunning = false;
                        $this->finishPart();
                    }
                }
            } elseif ($this->pausedAtTs) {
                $this->remainingSeconds = max($this->endsAtTs - $this->pausedAtTs, 0);
                $this->liveSeconds = max($this->targetSeconds - $this->remainingSeconds, 0);
            }
        }

        // فاز ۲ (اضافه بر مشاور)
        if ($this->isInExtraPhase && $this->extraEndsAtTs) {
            if ($this->isRunning) {
                $this->extraRemainingSeconds = max($this->extraEndsAtTs - $nowTs, 0);
                $this->extraLiveSeconds = max($this->extraTargetSeconds - $this->extraRemainingSeconds, 0);

                if ($this->extraRemainingSeconds === 0 && !$this->showFinishModal) {
                    $this->isRunning = false;
                    $this->showFinishModal = true;
                    $this->dispatch('play-alarm');
                }
            } elseif ($this->pausedAtTs) {
                $this->extraRemainingSeconds = max($this->extraEndsAtTs - $this->pausedAtTs, 0);
                $this->extraLiveSeconds = max($this->extraTargetSeconds - $this->extraRemainingSeconds, 0);
            }
        }

        // تایمر جبرانی
        if ($this->makeupTargetSeconds && $this->makeupEndsAtTs) {
            if ($this->makeupTimerRunning) {
                $this->makeupRemainingSeconds = max($this->makeupEndsAtTs - $nowTs, 0);
                $this->makeupLiveSeconds = max($this->makeupTargetSeconds - $this->makeupRemainingSeconds, 0);

                if ($this->makeupRemainingSeconds === 0) {
                    if (!$this->showMakeupFinishModal) {
                        $this->makeupTimerRunning = false;
                        $this->finishMakeup();
                    }
                }
            } elseif ($this->makeupPausedAtTs) {
                $this->makeupRemainingSeconds = max($this->makeupEndsAtTs - $this->makeupPausedAtTs, 0);
                $this->makeupLiveSeconds = max($this->makeupTargetSeconds - $this->makeupRemainingSeconds, 0);
            }
        }

        $this->saveTimerState();
    }

    // ============ آرشیو ============

    protected function buildArchiveData(WeeklyProgram $program): array
    {
        $student = auth()->user()?->student;
        if (!$student) {
            return ['parts' => collect(), 'makeups' => collect(), 'summary' => []];
        }

        $spsByPart = StudyPartSession::where('student_id', $student->id)
            ->whereIn('program_part_id', $program->parts->pluck('id'))
            ->with('feedback')
            ->orderBy('id')
            ->get()
            ->groupBy('program_part_id');

        $jalaliDayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
        $today = Carbon::today()->toDateString();

        $archiveParts = $program->parts
            ->sortBy(fn($p) => [(int)$p->day_of_week, (int)$p->part_order])
            ->values()
            ->map(function ($part) use ($spsByPart, $jalaliDayNames, $today) {
                $sessions = $spsByPart->get($part->id, collect());
                $session  = $sessions->sortByDesc('id')->first();
                $partDate = Carbon::parse($part->part_date)->toDateString();

                if ($session) {
                    $status = 'done';
                } elseif ($partDate < $today) {
                    $status = 'missed';
                } else {
                    $status = 'pending';
                }

                $jd = jdate($part->part_date);

                return [
                    'part'        => $part,
                    'day_name'    => $jalaliDayNames[$jd->getDayOfWeek()],
                    'jalali_date' => $jd->format('Y/m/d'),
                    'status'      => $status,
                    'session'     => $session,
                ];
            });

        $start = Carbon::parse($program->start_date)->startOfDay();
        $end   = ($program->end_date ? Carbon::parse($program->end_date) : Carbon::parse($program->start_date)->addDays(7))->endOfDay();

        $makeups = MakeupSession::where('student_id', $student->id)
            ->whereBetween('created_at', [$start, $end])
            ->with(['ccTopic.chapter.subject'])
            ->orderByDesc('created_at')
            ->get();

        $doneCount   = $archiveParts->where('status', 'done')->count();
        $missedCount = $archiveParts->where('status', 'missed')->count();

        $summary = [
            'total'           => $archiveParts->count(),
            'done'            => $doneCount,
            'missed'          => $missedCount,
            'pending'         => $archiveParts->where('status', 'pending')->count(),
            'studied_seconds' => (int)$archiveParts->sum(fn($r) => (int)($r['session']?->duration_seconds ?? 0) + (int)($r['session']?->extra_seconds ?? 0)),
            'makeup_seconds'  => (int)$makeups->sum('duration_seconds'),
            'makeup_count'    => $makeups->count(),
        ];

        return ['parts' => $archiveParts, 'makeups' => $makeups, 'summary' => $summary];
    }

    public function render()
    {
        $program = WeeklyProgram::with(['parts.lesson', 'parts.ccSubject', 'parts.ccChapter', 'parts.ccTopic', 'student.user.personalInformation', 'advisor'])
            ->find($this->programId);

        if (!$program) {
            return redirect()->route('client.profile.consultation.sessions')
                ->with('error', 'برنامه یافت نشد.');
        }

        // آمار برنامه
        $stats = [
            'totalHours' => $program->total_hours,
            'totalTests' => $program->total_tests,
            'totalParts' => $program->total_parts,
            'totalPlans' => $program->total_plans,
            'testParts' => $program->test_parts_count,
            'descriptiveParts' => $program->descriptive_parts_count,
            'videoParts' => $program->video_parts_count,
            'generalParts' => $program->general_parts_count,
            'specializedParts' => $program->specialized_parts_count,
            'grade10Parts' => $program->grade_10_parts_count,
            'grade11Parts' => $program->grade_11_parts_count,
            'grade12Parts' => $program->grade_12_parts_count,
        ];

        // آمار نوع منبع برای نمودار
        $sourceTypeColors = [
            'normal' => '#3B82F6',
            'class_qa' => '#06B6D4',
            'exam' => '#F59E0B',
            'homework' => '#EF4444',
            'daily_reading' => '#10B981',
            'pre_reading' => '#14B8A6',
            'classification' => '#64748B',
            'comprehensive_exam' => '#6B7280',
        ];
        $sourceTypeLabels = [
            'normal' => 'عادی',
            'class_qa' => 'پرسش و پاسخ کلاسی',
            'exam' => 'امتحانات',
            'homework' => 'تکالیف',
            'daily_reading' => 'روزخوانی',
            'pre_reading' => 'پیش‌خوانی',
            'classification' => 'طبقه‌بندی',
            'comprehensive_exam' => 'آزمون جامع',
        ];
        $allParts = $program->parts;
        $sourceTypeCounts = $allParts->groupBy('source_type')->map->count();
        $totalSourceParts = $allParts->count();
        $sourceTypeStats = [];
        foreach ($sourceTypeCounts as $type => $count) {
            $sourceTypeStats[] = [
                'type' => $type,
                'count' => $count,
                'percent' => $totalSourceParts > 0 ? round(($count / $totalSourceParts) * 100, 1) : 0,
                'color' => $sourceTypeColors[$type] ?? '#3B82F6',
                'label' => $sourceTypeLabels[$type] ?? 'عادی',
            ];
        }

        $student = $program->student;
        $advisorName = $student?->advisor?->name ?? '-';

        $archive = $this->buildArchiveData($program);

        return view('livewire.client.profile.consultation.weekly-program-view', [
            'program' => $program,
            'weekDays' => $this->getProgramDays(),
            'stats' => $stats,
            'sourceTypeStats' => $sourceTypeStats,
            'advisorName' => $advisorName,
            'archiveParts' => $archive['parts'],
            'archiveMakeups' => $archive['makeups'],
            'archiveSummary' => $archive['summary'],
        ])->layout('layouts.client.app');
    }
}
