<?php


namespace App\Livewire\Client\Profile\ProfessionalTools;


use Artesaos\SEOTools\Traits\SEOTools;

use Livewire\Attributes\On;

use Livewire\Component;

use App\Models\StudyPartSession;

use App\Models\AdvisingSession;

use App\Models\WeeklyProgram;

use App\Models\ProgramPart;

use Carbon\Carbon;
use App\Models\WeeklyProgramRestDay;

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
    public $restDays = []; // روزهای استراحت
    public $endsAt = null;       // زمان پایان تایمر
    public $pausedSeconds = 0;   // مجموع ثانیه‌های pause شده

    public $endsAtTs = null;   // timestamp پایان
    public $pausedAtTs = null; // timestamp شروع pause
    public $dayFilter = 'all'; // all | today | upcoming

    public function mount()
    {
        $this->permissionGranted = (bool)session('study_permission_granted', false);

        $this->loadLatestProgram();
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

        $this->startedAt = now();              // فقط برای ذخیره در DB/نمایش
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

        return view('livewire.client.profile.professional-tools.study-session')->layout('layouts.client.app');

    }

}
