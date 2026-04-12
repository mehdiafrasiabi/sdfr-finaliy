<?php

namespace App\Livewire\Client\Profile\EssayExam;

use App\Models\EssayExamAttempt;
use App\Models\Student;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class EssayExamResult extends Component
{
    public int $attemptId;
    public ?EssayExamAttempt $attempt = null;

    public function mount(int $attemptId): void
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        $this->attempt = EssayExamAttempt::with([
            'assignment.essayExam.questions',
            'uploads',
            'questionScores',
        ])
            ->whereHas('assignment', fn($q) => $q->where('student_id', $student->id))
            ->findOrFail($attemptId);

        $this->attemptId = $attemptId;
    }

    public function render()
    {
        return view('livewire.client.profile.essay-exam.essay-exam-result')
            ->layout('layouts.client.app');
    }
}
