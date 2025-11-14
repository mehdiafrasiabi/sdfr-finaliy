<?php

namespace App\Livewire\Client\Profile\ProfessionalTools;

use App\Models\PomodoroSessions;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PomodoroTimer extends Component
{
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
            'focus' => 25 * 60,
            'shortBreak' => 5 * 60,
            'longBreak' => 20 * 60,
            default => 25 * 60,
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

    public function tick()
    {
        if (!$this->isRunning) return;

        if ($this->timeLeft > 0) {
            $this->timeLeft--;
        } else {
            $this->handleEnd();
        }
    }

    protected function handleEnd()
    {
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

        if ($this->mode === 'focus') {
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

        $this->dispatchBrowserEvent('pomodoro-alarm');
    }

    protected function timeDurationSeconds()
    {
        return match ($this->mode) {
            'focus' => 25 * 60,
            'shortBreak' => 5 * 60,
            'longBreak' => 20 * 60,
            default => 25 * 60,
        };
    }

    protected function timeDurationMinutes()
    {
        return $this->timeDurationSeconds() / 60;
    }
    public function render()
    {
        return view('livewire.client.profile.professional-tools.pomodoro-timer')->layout('layouts.client.app');
    }
}
