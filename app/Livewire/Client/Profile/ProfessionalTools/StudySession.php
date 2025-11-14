<?php

namespace App\Livewire\Client\Profile\ProfessionalTools;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\StudySession as StudySessionModel;
use Carbon\Carbon;

class StudySession extends Component
{
    public $isRunning = false;
    public $startedAt;
    public $endedAt;
    public $durationSeconds;
    public $note;

    public $sessions = [];

    public $liveSeconds = 0;

    public function tick()
    {
        // اگر در حال اجراست، زمان زنده را محاسبه کن
        if ($this->isRunning && $this->startedAt) {
            // startedAt ممکنه در hydraization به رشته تبدیل شده باشه — با انعطاف باهاش کار می‌کنیم
            if ($this->startedAt instanceof Carbon) {
                $startTs = $this->startedAt->timestamp;
            } elseif (is_numeric($this->startedAt)) {
                $startTs = (int) $this->startedAt;
            } else {
                $startTs = strtotime($this->startedAt) ?: now()->timestamp;
            }

            $this->liveSeconds = max(now()->timestamp - $startTs, 0);
        }
        // اگر پاز شده باشه، liveSeconds رو نگه می‌داریم (در pause() مقداردهی می‌شود)
    }

    public function mount()
    {
        $this->fetchSessions();
    }

    public function startTimer()
    {
        if (!auth()->user()->student) {
            $this->dispatch('warning', 'شما به عنوان دانش‌آموز ثبت نشده‌اید.');
            return;
        }

        // اگر قبلاً پاز شده بود، ادامه از همان مقدار
        if (!$this->isRunning && $this->durationSeconds) {
            // زمان جدید = الان منهای مقدار قبلی (برای ادامه)
            $this->startedAt = now()->subSeconds($this->durationSeconds);
        } else {
            // اگر کاملاً جدید شروع می‌کند
            $this->startedAt = now();
            $this->durationSeconds = 0;
        }

        $this->isRunning = true;
        $this->endedAt = null;
        $this->dispatch('success', 'با موفقیت استارت شد.');
    }


    // این متد فقط تایمر را متوقف (پاز) می‌کند — ذخیره در دیتابیس انجام نمی‌شود
    public function pauseTimer()
    {
        if (!$this->isRunning || !$this->startedAt) return;

        $startTs = $this->startedAt instanceof Carbon
            ? $this->startedAt->timestamp
            : (is_numeric($this->startedAt) ? (int)$this->startedAt : strtotime($this->startedAt));

        $this->endedAt = now();
        $this->durationSeconds = max($this->endedAt->timestamp - $startTs, 0);
        $this->isRunning = false;
        $this->liveSeconds = $this->durationSeconds;

        $this->dispatch('success', '⏸ تایمر متوقف شد. برای ادامه مجدداً "شروع مطالعه" را بزنید.');
    }

    // compatibility: اگر جایی stopTimer فراخوانی شد، آن را به pause نگاشت می‌کنیم
    public function stopTimer()
    {
        $this->pauseTimer();
    }

    // این متد نهایی: پایان جلسه و ذخیره در دیتابیس
    public function finishAndSave()
    {
        // اگر هنوز تایمر در حال اجراست، اول آن را متوقف کن
        if ($this->isRunning) {
            $this->pauseTimer();
        }

        if (! $this->startedAt) {
            $this->dispatch('warning', 'جلسه‌ای برای ثبت وجود ندارد.');
            return;
        }

        // student id
        $studentId = auth()->user()->student->id;

        // اگر endedAt و durationSeconds محاسبه نشده بود، محاسبه کن
        if (! $this->endedAt) {
            $this->endedAt = now();
            if ($this->startedAt instanceof Carbon) {
                $startTs = $this->startedAt->timestamp;
            } elseif (is_numeric($this->startedAt)) {
                $startTs = (int) $this->startedAt;
            } else {
                $startTs = strtotime($this->startedAt) ?: now()->timestamp;
            }
            $this->durationSeconds = max($this->endedAt->timestamp - $startTs, 0);
        }

        // ذخیره در دیتابیس
        StudySessionModel::create([
            'student_id' => $studentId,
            'started_at' => $this->startedAt,
            'ended_at' => $this->endedAt,
            'duration_seconds' => $this->durationSeconds,
            'note' => $this->note,
        ]);

        // بازنشانی (می‌تونید بسته به نیازتان این رفتار را تغییر دهید — فعلاً پاک می‌کنیم)
        $this->note = null;
        $this->isRunning = false;
        $this->startedAt = null;
        $this->endedAt = null;
        $this->durationSeconds = null;
        $this->liveSeconds = 0;

        $this->fetchSessions();
        $this->dispatch('success','جلسه با موفقیت ثبت شد.');
    }

    public function resetTimer()
    {
        $this->isRunning = false;
        $this->startedAt = null;
        $this->endedAt = null;
        $this->durationSeconds = null;
        $this->note = null;
        $this->liveSeconds = 0;

        $this->dispatch('success','با موفقیت ریست شد ');
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

    public function deleteSession($id)
    {
        // اصلاح: مقایسه با student_id واقعی کاربر
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

    public function render()
    {
        return view('livewire.client.profile.professional-tools.study-session')->layout('layouts.client.app');
    }
}
