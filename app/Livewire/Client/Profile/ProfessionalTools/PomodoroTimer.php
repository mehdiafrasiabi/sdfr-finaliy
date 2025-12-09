<?php

namespace App\Livewire\Client\Profile\ProfessionalTools;

use App\Models\PomodoroSessions;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PomodoroTimer extends Component
{
    protected $listeners = ['timerAborted' => 'stop'];

    public $focusLength = 25;     // minutes
    public $shortBreakLength = 5; // minutes
    public $longBreakLength = 20; // minutes
    public $autoStartNext = true;

    public $timeLeft;
    public $mode = 'focus';  // 'focus', 'shortBreak', 'longBreak'
    public $rounds = 0;      // تعداد دورهای مطالعه کامل شده
    public $cycles = 0;      // تعداد چرخه‌های کامل شده (۴ دور مطالعه + استراحت طولانی)
    public $isRunning = false;

    public function mount()
    {
        $this->setTimeByMode();
    }

    public function setTimeByMode()
    {
        $this->timeLeft = match ($this->mode) {
            'focus' => $this->focusLength * 60,
            'shortBreak' => $this->shortBreakLength * 60,
            'longBreak' => $this->longBreakLength * 60,
            default => $this->focusLength * 60,
        };
    }

    public function start()
    {
        $this->isRunning = true;
    }

    public function stop()
    {
        $this->isRunning = false;
    }

    public function resetTimer()
    {
        $this->mode = 'focus';
        $this->rounds = 0;
        $this->cycles = 0;
        $this->isRunning = false;
        $this->setTimeByMode();
    }

    public function skipPhase()
    {
        $this->isRunning = false;

        if ($this->mode === 'focus') {
            $this->mode = $this->determineBreakAfterFocus();
        } else {
            $this->mode = 'focus';
        }

        $this->setTimeByMode();
    }

    public function tick()
    {
        if (!$this->isRunning) {
            return;
        }

        if ($this->timeLeft > 0) {
            $this->timeLeft--;
        } else {
            $this->handleEnd();
        }
    }

    protected function handleEnd()
    {
        $completedMode = $this->mode;
        $studentId = Auth::user()->student->id ?? null;
        if (!$studentId) {
            // اگر دانش‌آموز لاگین نیست، تایمر رو متوقف کن
            $this->isRunning = false;
            return;
        }

        // ثبت جلسه در دیتابیس با دقت بیشتر
        PomodoroSessions::create([
            'student_id' => $studentId,
            'session_type' => $this->mode,
            'duration' => (int)($this->timeDurationMinutes()),
            'started_at' => Carbon::now()->subSeconds($this->timeDurationSeconds()),
            'ended_at' => Carbon::now(),
            'status' => 'completed',
        ]);

        if ($completedMode === 'focus') {
            $this->rounds++;

            if ($this->rounds % 4 === 0) {
                $this->mode = 'longBreak';
                $this->cycles++;
            } else {
                $this->mode = 'shortBreak';
            }
        } else {
            // بعد از استراحت برمی‌گردیم به مطالعه
            $this->mode = 'focus';
        }

        $this->setTimeByMode();

        if ($completedMode === 'longBreak') {
            $this->dispatchBrowserEvent('study-finished');
        }

        $this->dispatchBrowserEvent('pomodoro-alarm');

        $this->isRunning = $this->autoStartNext;
    }

    protected function timeDurationSeconds()
    {
        return match ($this->mode) {
            'focus' => $this->focusLength * 60,
            'shortBreak' => $this->shortBreakLength * 60,
            'longBreak' => $this->longBreakLength * 60,
            default => $this->focusLength * 60,
        };
    }

    protected function timeDurationMinutes()
    {
        return $this->timeDurationSeconds() / 60;
    }

    protected function determineBreakAfterFocus(): string
    {
        return (($this->rounds + 1) % 4 === 0) ? 'longBreak' : 'shortBreak';
    }

    public function render()
    {
        return view('livewire.client.profile.professional-tools.pomodoro-timer')->layout('layouts.client.app');
    }
}
