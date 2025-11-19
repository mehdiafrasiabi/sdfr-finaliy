<?php

namespace App\Livewire\Client\Profile\ProfessionalTools;

use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Attributes\On;
use Livewire\Component;
use App\Models\StudySession as StudySessionModel;
use Carbon\Carbon;

class StudySession extends Component
{
    use SEOTools;
    public $isRunning = false;
    public $startedAt;
    public $endedAt;
    public $durationSeconds;
    public $note = '';

    public $studyHours = 0;
    public $studyMinutes = 0;
    public $targetSeconds = 0;
    public $remainingSeconds = 0;

    public $sessions = [];

    public $liveSeconds = 0;
    public $showSetupModal = false;
    public $showConfirmFinishModal = false;
    public $showSuccessModal = false;
    public $alarmTriggered = false;

    protected $rules = [
        'studyHours' => 'required|integer|min:0|max:24',
        'studyMinutes' => 'required|integer|min:0|max:60',
        'note' => 'required|string|min:3|max:255',
    ];

    protected $messages = [
        'studyHours.required' => 'لطفاً تعداد ساعت مطالعه را انتخاب کنید.',
        'studyHours.integer' => 'ساعت مطالعه باید عددی باشد.',
        'studyHours.min' => 'حداقل مقدار ساعت صفر است.',
        'studyHours.max' => 'حداکثر می‌توانید ۲۴ ساعت انتخاب کنید.',
        'studyMinutes.required' => 'لطفاً تعداد دقیقه مطالعه را انتخاب کنید.',
        'studyMinutes.integer' => 'دقیقه مطالعه باید عددی باشد.',
        'studyMinutes.min' => 'حداقل مقدار دقیقه صفر است.',
        'studyMinutes.max' => 'حداکثر می‌توانید ۶۰ دقیقه انتخاب کنید.',
        'note.required' => 'لطفاً مبحث مطالعه را بنویسید.',
        'note.string' => 'مبحث مطالعه باید متن باشد.',
        'note.min' => 'مبحث مطالعه باید حداقل ۳ کاراکتر باشد.',
        'note.max' => 'حداکثر طول مجاز برای مبحث مطالعه ۲۵۵ کاراکتر است.',
    ];

    public function tick()
    {
        if ($this->isRunning && $this->startedAt) {
            $startTs = $this->startTimestamp();
            $this->liveSeconds = max(now()->timestamp - $startTs, 0);
            $this->remainingSeconds = $this->targetSeconds > 0
                ? max($this->targetSeconds - $this->liveSeconds, 0)
                : $this->liveSeconds;

            // زمانی که تایمر به صفر برسد، خودکار ذخیره شود
            if ($this->targetSeconds > 0 && $this->remainingSeconds === 0 && !$this->alarmTriggered) {
                $this->completeCountdownAndSave();
            }
        }
    }

    public function mount()
    {
        $this->fetchSessions();
        $this->remainingSeconds = 0;
        $this->seoConfig();

    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('ثبت ساعت مطالعه')
            ->setDescription('ابزاری برای حرفه ای ها');
    }
    public function openSetupModal()
    {
        $this->showSetupModal = true;
    }

    public function startTimer()
    {
        if (! $this->ensureStudent()) {
            return;
        }

        $this->validate();

        if ($this->studyHours == 0 && $this->studyMinutes == 0) {
            $this->addError('studyMinutes', 'لطفاً حداقل یک دقیقه برای مطالعه انتخاب کنید.');
            return;
        }

        $this->targetSeconds = ($this->studyHours * 3600) + ($this->studyMinutes * 60);
        $this->startedAt = now();
        $this->endedAt = null;
        $this->durationSeconds = null;
        $this->liveSeconds = 0;
        $this->remainingSeconds = $this->targetSeconds;
        $this->isRunning = true;
        $this->alarmTriggered = false;
        $this->showSetupModal = false;
        $this->showConfirmFinishModal = false;
        $this->showSuccessModal = false;

        $this->dispatch('success', 'مطالعه شما آغاز شد.');
    }

    public function pauseTimer()
    {
        if (!$this->isRunning || !$this->startedAt) return;

        $startTs = $this->startTimestamp();
        $this->liveSeconds = max(now()->timestamp - $startTs, 0);
        $this->durationSeconds = $this->liveSeconds;
        $this->remainingSeconds = $this->targetSeconds > 0
            ? max($this->targetSeconds - $this->liveSeconds, 0)
            : 0;
        $this->isRunning = false;

        $this->dispatch('success', '⏸ تایمر متوقف شد.');
    }

    public function resumeTimer()
    {
        if ($this->isRunning || ! $this->startedAt) {
            return;
        }

        $elapsed = $this->liveSeconds ?: 0;
        $this->startedAt = now()->subSeconds($elapsed);
        $this->isRunning = true;
        $this->remainingSeconds = $this->targetSeconds > 0
            ? max($this->targetSeconds - $elapsed, 0)
            : $elapsed;
        $this->alarmTriggered = false;

        $this->dispatch('success', 'ادامه مطالعه آغاز شد.');
    }

    public function stopTimer()
    {
        $this->pauseTimer();
    }

    public function requestFinish()
    {
        // اگر در حال اجراست و هنوز تایم باقی مانده، تأیید بگیریم
        if ($this->isRunning && $this->targetSeconds > 0 && $this->remainingSeconds > 0) {
            $this->showConfirmFinishModal = true;
            return;
        }

        // در غیر این صورت مستقیم ذخیره کن
        $this->finishAndSave();
    }

    public function cancelFinishRequest()
    {
        $this->showConfirmFinishModal = false;
    }

    public function finishAndSave()
    {
        if ($this->isRunning) {
            $this->pauseTimer();
        }

        if (! $this->startedAt) {
            $this->dispatch('warning', 'جلسه‌ای برای ثبت وجود ندارد.');
            return;
        }

        $studentId = auth()->user()->student->id;

        $currentSeconds = $this->liveSeconds ?: $this->durationSeconds ?: 0;
        if ($currentSeconds <= 0) {
            $this->dispatch('warning', 'زمانی برای ثبت وجود ندارد.');
            return;
        }

        if ($this->targetSeconds > 0) {
            $currentSeconds = min($currentSeconds, $this->targetSeconds);
        }

        $this->durationSeconds = $currentSeconds;

        if (! $this->endedAt) {
            if ($this->startedAt instanceof Carbon) {
                $this->endedAt = $this->startedAt->copy()->addSeconds($this->durationSeconds);
            } else {
                $this->endedAt = Carbon::parse($this->startedAt)->addSeconds($this->durationSeconds);
            }
        }

        StudySessionModel::create([
            'student_id' => $studentId,
            'started_at' => $this->startedAt,
            'ended_at' => $this->endedAt,
            'duration_seconds' => $this->durationSeconds,
            'planned_seconds' => $this->targetSeconds ?: null,
            'note' => $this->note,
        ]);

        // نمایش مودال موفقیت با کانفتی
        $this->showSuccessModal = true;
        $this->showConfirmFinishModal = false;
        $this->dispatch('study-saved-success');

        $this->resetTimer(true);
        $this->fetchSessions();
    }

    public function resetTimer($silent = false)
    {
        $this->isRunning = false;
        $this->startedAt = null;
        $this->endedAt = null;
        $this->durationSeconds = null;
        $this->note = '';
        $this->studyHours = 0;
        $this->studyMinutes = 0;
        $this->targetSeconds = 0;
        $this->remainingSeconds = 0;
        $this->liveSeconds = 0;
        $this->alarmTriggered = false;
        $this->showSetupModal = false;
        $this->showConfirmFinishModal = false;

        if (! $silent) {
            $this->dispatch('success','با موفقیت ریست شد ');
        }
    }

    #[On('timerAborted')]
    public function onAbort()
    {
        $this->resetTimer();
    }

    public function fetchSessions()
    {
        if (!auth()->user()->student) {
            $this->sessions = [];
            return;
        }

        $studentId = auth()->user()->student->id;
        $this->sessions = StudySessionModel::where('student_id',$studentId)
            ->orderByDesc('started_at')
            ->get();
    }

    function formatDuration($seconds)
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;

        return sprintf('%02d ساعت  %02d  دقیقه %02d ثانیه', $hours, $minutes, $remainingSeconds);
    }

    public function formatClock($seconds)
    {
        $seconds = max((int)$seconds, 0);

        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;

        return sprintf('%02d:%02d:%02d', $hours, $minutes, $remainingSeconds);
    }

    public function deleteSession($id)
    {
        $studentId = auth()->user()->student->id;
        $session = StudySessionModel::where('id', $id)
            ->where('student_id', $studentId)
            ->first();

        if ($session) {
            $session->delete();
            $this->dispatch('success','با موفقیت حذف شد ');
            $this->fetchSessions();
        }
    }

    protected function startTimestamp(): int
    {
        if ($this->startedAt instanceof Carbon) {
            return $this->startedAt->timestamp;
        }

        if (is_numeric($this->startedAt)) {
            return (int) $this->startedAt;
        }

        return strtotime($this->startedAt) ?: now()->timestamp;
    }

    /**
     * زمانی که countdown به پایان رسید، خودکار ذخیره می‌شود
     */
    protected function completeCountdownAndSave(): void
    {
        $this->isRunning = false;
        $this->durationSeconds = $this->targetSeconds;
        $this->liveSeconds = $this->targetSeconds;

        if ($this->startedAt instanceof Carbon) {
            $this->endedAt = $this->startedAt->copy()->addSeconds($this->durationSeconds);
        } else {
            $this->endedAt = Carbon::parse($this->startedAt)->addSeconds($this->durationSeconds);
        }

        $this->remainingSeconds = 0;
        $this->alarmTriggered = true;

        // صدای آلارم
        $this->dispatch('study-finished');

        // ذخیره خودکار در دیتابیس
        $studentId = auth()->user()->student->id;

        StudySessionModel::create([
            'student_id' => $studentId,
            'started_at' => $this->startedAt,
            'ended_at' => $this->endedAt,
            'duration_seconds' => $this->durationSeconds,
            'planned_seconds' => $this->targetSeconds,
            'note' => $this->note,
        ]);

        // نمایش مودال موفقیت
        $this->showSuccessModal = true;
        $this->dispatch('study-saved-success');

        // ریست تایمر
        $this->resetTimer(true);
        $this->fetchSessions();
    }

    protected function ensureStudent(): bool
    {
        if (!auth()->user()->student) {
            $this->dispatch('warning', 'شما به عنوان دانش‌آموز ثبت نشده‌اید.');
            return false;
        }

        return true;
    }

    public function render()
    {
        return view('livewire.client.profile.professional-tools.study-session')->layout('layouts.client.app');
    }
}
