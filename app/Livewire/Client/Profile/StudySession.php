<?php


namespace App\Livewire\Client\Profile;


use App\Models\AdvisingSession;
use App\Models\ProgramPart;
use App\Models\StudyPartSession;
use App\Models\WeeklyProgram;
use App\Models\WeeklyProgramRestDay;
use Artesaos\SEOTools\Traits\SEOTools;
use App\Models\SessionFeedback;
use App\Models\SpsTiming;
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
    // تایمر فعال
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
    // وضعیت‌های کامل شده
    public $completedParts = [];
    public $restDays = [];
    public $endsAt = null;
    public $pausedSeconds = 0;
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
    public $makeupDurationHours = 0;
    public $makeupDurationMinutes = 30;
    public $makeupNote = '';
    // ویرایش جبرانی
    public $editingMakeupId = null;
    // --- بازخورد ---
    public $showFeedbackModal = false;
    public $feedbackRating = 0;
    public $feedbackComment = '';
    public $pendingFeedbackSpsId = null;
    public function mount()
    {
        $this->permissionGranted = (bool) session('study_permission_granted', false);

        $this->loadLatestProgram();
        $this->checkPendingFeedback();
        $this->seoConfig();
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


        // آخرین جلسه مشاوره برگزار شده

        $latestSession = AdvisingSession::where('student_id', $studentId)
            ->where('result_status', AdvisingSession::RESULT_HELD)
            ->orderByDesc('activation_date')
            ->orderByDesc('session_time')
            ->first();


        if (!$latestSession) {

            return;

        }


        // برنامه مربوط به آخرین جلسه

        $this->weeklyProgram = WeeklyProgram::where('advising_session_id', $latestSession->id)
            ->where('student_id', $studentId)
            ->first();


        if ($this->weeklyProgram) {

            $this->programParts = ProgramPart::where('weekly_program_id', $this->weeklyProgram->id)
                ->orderBy('part_date')
                ->orderBy('part_order')
                ->get();

            // بارگذاری روزهای استراحت

            $this->restDays = WeeklyProgramRestDay::where('weekly_program_id', $this->weeklyProgram->id)
                ->pluck('day_index')
                ->toArray();
            // بارگذاری پارت‌های کامل شده

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

    public function startPart($partId)
    {
        // بررسی بازخورد معلق
        if ($this->hasPendingFeedback()) {
            $this->showFeedbackModal = true;
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
        $this->targetSeconds = (int) $part->duration_minutes * 60;

        $nowTs = now()->timestamp;

        $this->startedAt = now();
        $this->endsAtTs = $nowTs + $this->targetSeconds;

        $this->pausedAt = null;
        $this->pausedAtTs = null;

        $this->isRunning = true;

        $this->liveSeconds = 0;
        $this->remainingSeconds = $this->targetSeconds;

        $this->dispatch('success', 'مطالعه شروع شد.');
    }


    public function pausePart()
    {
        if (!$this->isRunning || !$this->endsAtTs) return;

        $this->isRunning = false;
        $this->pausedAt = now();
        $this->pausedAtTs = now()->timestamp;

        // محاسبه remaining دقیق
        $this->remainingSeconds = max($this->endsAtTs - $this->pausedAtTs, 0);

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
    }


    public function finishPart()
    {
        if (!$this->currentPartId) {
            return;
        }
        $this->isRunning = false;
        $this->showFinishModal = true;
        // پخش صدای الارم
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
        // ثبت جلسه مطالعه
        $duration = max($this->targetSeconds - $this->remainingSeconds, 0);
        StudyPartSession::create([
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
        // افزودن به لیست کامل شده‌ها
        $this->completedParts[] = $this->currentPartId;
        // ریست تایمر
        $this->resetTimer();
        $this->showFinishModal = false;
        $this->dispatch('part-completed');
        $this->dispatch('success', '✅ پارت با موفقیت ثبت شد!');

    }


    public function closeFinishModal()

    {

        $this->showFinishModal = false;

        $this->resetTimer();

    }

    public function hasPendingFeedback(): bool
    {
        if (!auth()->user()->student) return false;
        $studentId = auth()->user()->student->id;
        $lastSession = StudyPartSession::where('student_id', $studentId)
            ->where('is_completed', true)
            ->orderByDesc('id')
            ->first();
        if (!$lastSession) return false;
        $hasFeedback = SessionFeedback::where('sps_id', $lastSession->id)->exists();
        if (!$hasFeedback) {
            $this->pendingFeedbackSpsId = $lastSession->id;
            return true;
        }
        return false;
    }
    public function checkPendingFeedback()
    {
        if ($this->hasPendingFeedback()) {
            $this->feedbackRating = 0;
            $this->feedbackComment = '';
            $this->showFeedbackModal = true;
        }
    }
    public function submitFeedback()
    {
        if (!$this->pendingFeedbackSpsId || !auth()->user()->student) {
            return;
        }
        if ($this->feedbackRating < 1 || $this->feedbackRating > 10) {
            $this->dispatch('error', 'لطفاً امتیاز بین ۱ تا ۱۰ انتخاب کنید.');
            return;
        }
        SessionFeedback::create([
            'student_id' => auth()->user()->student->id,
            'sps_id' => $this->pendingFeedbackSpsId,
            'rating' => $this->feedbackRating,
            'comment' => $this->feedbackComment ?: null,
        ]);
        $this->showFeedbackModal = false;
        $this->pendingFeedbackSpsId = null;
        $this->feedbackRating = 0;
        $this->feedbackComment = '';
        $this->dispatch('success', 'بازخورد شما ثبت شد. ممنون!');
    }

    public function setFeedbackRating($rating)
    {
        $this->feedbackRating = (int) $rating;
    }
    // ============ مطالعه جبرانی ============
    public function allPartsCompletedToday(): bool
    {
        if (!$this->weeklyProgram) return false;
        $today = now()->toDateString();
        $todayParts = collect($this->programParts)
            ->filter(fn($p) => Carbon::parse($p->part_date)->toDateString() === $today);
        if ($todayParts->isEmpty()) return false;
        foreach ($todayParts as $part) {
            if (!in_array($part->id, $this->completedParts)) {
                return false;
            }
        }
        return true;
    }

    public function openMakeupModal()
    {
        if (!$this->allPartsCompletedToday()) {
            $this->dispatch('error', 'ابتدا همه پارت‌های امروز را تکمیل کنید.');
            return;
        }
        $this->resetMakeupForm();
        $this->editingMakeupId = null;
        $this->showMakeupModal = true;
    }
    public function closeMakeupModal()
    {
        $this->showMakeupModal = false;
        $this->resetMakeupForm();
    }

    public function resetMakeupForm()
    {
        $this->makeupSearch = '';
        $this->makeupGradeId = '';
        $this->makeupFieldId = '';
        $this->makeupSubjectId = '';
        $this->makeupChapterId = '';
        $this->makeupTopicId = '';
        $this->makeupDurationHours = 0;
        $this->makeupDurationMinutes = 30;
        $this->makeupNote = '';
    }
    // فیلترهای آبشاری - بارگذاری تنبل
    public function getGradesProperty()
    {
        return CcGrade::where('is_active', true)->orderBy('order')->get();
    }
    public function getFieldsProperty()
    {
        return CcField::where('is_active', true)->orderBy('order')->get();
    }

    public function getSubjectsProperty()
    {
        if (!$this->makeupGradeId) return collect();
        $query = CcSubject::where('cc_grade_id', $this->makeupGradeId)->orderBy('order');
        if ($this->makeupFieldId) {
            $query->where(function ($q) {
                $q->where('cc_field_id', $this->makeupFieldId)
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
        return CcTopic::where('is_active', true)
            ->where('name', 'like', "%{$term}%")
            ->with(['chapter.subject.grade'])
            ->limit(15)
            ->get();
    }
    public function selectSearchTopic($topicId)
    {
        $topic = CcTopic::with(['chapter.subject.grade'])->find($topicId);
        if (!$topic) return;
        $this->makeupTopicId = $topic->id;
        $this->makeupChapterId = $topic->chapter->id;
        $this->makeupSubjectId = $topic->chapter->subject->id;
        $this->makeupGradeId = $topic->chapter->subject->cc_grade_id;
        $this->makeupFieldId = $topic->chapter->subject->cc_field_id ?? '';
        $this->makeupSearch = '';
    }
    // وقتی پایه تغییر می‌کنه، زیرمجموعه‌ها ریست بشن
    public function updatedMakeupGradeId()
    {
        $this->makeupSubjectId = '';
        $this->makeupChapterId = '';
        $this->makeupTopicId = '';
    }
    public function updatedMakeupFieldId()
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
    public function saveMakeupSession()
    {
        if (!auth()->user()->student || !$this->makeupTopicId) {
            $this->dispatch('error', 'لطفاً مبحث مورد نظر را انتخاب کنید.');
            return;
        }
        $totalSeconds = ((int) $this->makeupDurationHours * 3600) + ((int) $this->makeupDurationMinutes * 60);
        if ($totalSeconds < 60) {
            $this->dispatch('error', 'مدت زمان باید حداقل ۱ دقیقه باشد.');
            return;
        }
        $data = [
            'student_id' => auth()->user()->student->id,
            'cc_topic_id' => $this->makeupTopicId,
            'duration_seconds' => $totalSeconds,
            'note' => $this->makeupNote ?: null,
        ];
        if ($this->editingMakeupId) {
            $makeup = MakeupSession::where('id', $this->editingMakeupId)
                ->where('student_id', auth()->user()->student->id)
                ->where('status', 'pending')
                ->first();
            if (!$makeup) {
                $this->dispatch('error', 'امکان ویرایش وجود ندارد.');
                return;
            }
            $makeup->update($data);
            $this->dispatch('success', 'جلسه جبرانی ویرایش شد.');
        } else {
            $data['status'] = 'pending';
            MakeupSession::create($data);
            $this->dispatch('success', 'جلسه جبرانی ثبت شد.');
        }
        $this->closeMakeupModal();
    }
    public function editMakeup($id)
    {
        $makeup = MakeupSession::where('id', $id)
            ->where('student_id', auth()->user()->student->id)
            ->where('status', 'pending')
            ->first();
        if (!$makeup) {
            $this->dispatch('error', 'امکان ویرایش وجود ندارد.');
            return;
        }
        $this->editingMakeupId = $makeup->id;
        $this->makeupTopicId = $makeup->cc_topic_id;
        $this->makeupNote = $makeup->note ?? '';
        $this->makeupDurationHours = floor($makeup->duration_seconds / 3600);
        $this->makeupDurationMinutes = floor(($makeup->duration_seconds % 3600) / 60);
        // بارگذاری فیلترهای آبشاری
        $topic = CcTopic::with(['chapter.subject'])->find($makeup->cc_topic_id);
        if ($topic) {
            $this->makeupChapterId = $topic->chapter->id;
            $this->makeupSubjectId = $topic->chapter->subject->id;
            $this->makeupGradeId = $topic->chapter->subject->cc_grade_id;
            $this->makeupFieldId = $topic->chapter->subject->cc_field_id ?? '';
        }
        $this->showMakeupModal = true;
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
    }


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

    /**
     * Get all 8 days of the program with rest day info
     */

    public function getProgramDays(): array
    {
        if (!$this->weeklyProgram) {
            return [];
        }
        $days = [];
        $jalaliDayNames = ['شنبه', 'یکشنبه', 'دوشنبه', 'سه‌شنبه', 'چهارشنبه', 'پنج‌شنبه', 'جمعه'];
        $startDate = $this->weeklyProgram->start_date;
        for ($i = 0; $i < 8; $i++) {
            $date = Carbon::parse($startDate)->addDays($i);
            $jalaliDate = jdate($date);
            $dayOfWeek = $jalaliDate->getDayOfWeek();
            $isRestDay = in_array($i, $this->restDays);
            $dayParts = collect($this->programParts)->filter(fn($p) => $p->day_of_week === $i);
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
    /**
     * Check if a date is a rest day
     */
    public function isRestDay(int $dayIndex): bool
    {
        return in_array($dayIndex, $this->restDays);
    }

    public function cancelPart()
    {
        $this->resetTimer();
        $this->dispatch('success', 'پارت لغو شد.');
    }


    public function render()

    {

        return view('livewire.client.profile.study-session')->layout('layouts.client.app');

    }

}
