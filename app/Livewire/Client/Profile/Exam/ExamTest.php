<?php

namespace App\Livewire\Client\Profile\Exam;

use App\Models\Exam;
use App\Models\ExamAttemp;
use Livewire\Component;
use Livewire\Attributes\On;

class ExamTest extends Component
{
    public Exam $exam;
    public ExamAttemp $attempt;
    public $answers = [];
    public $initialTimeInSeconds;
    public $startedAtTimestamp;
    public $remainingTime;

    #[\Livewire\Attributes\Computed]
    public function answeredCount()
    {
        return collect($this->answers)->filter(fn($answer) => $answer !== null)->count();
    }

    #[\Livewire\Attributes\Computed]
    public function unansweredCount()
    {
        return $this->exam->number_of_questions - $this->answeredCount();
    }

    public function mount(Exam $exam)
    {
        $this->exam = $exam;
        $student = auth()->user()->student;

        $this->attempt = ExamAttemp::firstOrCreate(
            ['exam_id' => $this->exam->id, 'student_id' => $student->id],
            ['started_at' => now()]
        );

        if ($this->attempt->is_finished) {
            return redirect()->route('client.profile.exam.result', $this->exam->id);
        }

        if ($this->attempt->submitted_at || ($this->attempt->answers && count($this->attempt->answers) > 0)) {
            return redirect()->route('client.profile.exam.result', $this->exam->id);
        }

        $this->initialTimeInSeconds = $this->exam->duration_minutes * 60;
        $this->startedAtTimestamp = $this->attempt->started_at->timestamp;
        $this->remainingTime = $this->calculateRemainingTime();

        $savedAnswers = $this->attempt->answers ?? [];
        $newAnswers = [];
        for ($i = 1; $i <= $this->exam->number_of_questions; $i++) {
            $newAnswers[$i] = $savedAnswers[$i] ?? null;
        }
        $this->answers = $newAnswers;
    }

    private function calculateRemainingTime()
    {
        $durationInSeconds = $this->exam->duration_minutes * 60;
        $elapsedTime = now()->diffInSeconds($this->attempt->started_at);
        $remaining = $durationInSeconds - $elapsedTime;

        return max(0, $remaining);
    }

    public function selectOption($questionNumber, $option)
    {
        // اگر کاربر دوباره روی همون گزینه زد، مقدار حذف بشه
        if (isset($this->answers[$questionNumber]) && $this->answers[$questionNumber] == $option) {
            $this->answers[$questionNumber] = null;
        } else {
            $this->answers[$questionNumber] = $option;
        }

        // ذخیره خودکار پاسخ‌ها
        $this->attempt->update([
            'answers' => $this->answers,
        ]);

        $this->dispatch('answerSelected');
    }

    public function submitExam()
    {
        $this->attempt->update([
            'answers' => $this->answers,
            'submitted_at' => now(),
        ]);

        return redirect()->route('client.profile.exam.result', $this->exam->id);
    }

    public function render()
    {
        return view('livewire.client.profile.exam.exam-test')->layout('layouts.client.app');
    }
}
