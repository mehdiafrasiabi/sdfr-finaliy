<?php

namespace App\Livewire\Admin\Student\Exam;

use App\Models\Exam;
use App\Models\ExamAnalyses;
use App\Models\ExamAttemp;
use App\Models\Student;
use Livewire\Component;

class StudentDetail extends Component
{
    public Student $student;
    public Exam $exam;
    public ExamAttemp $attempt;
    public $correctCount = 0;
    public $incorrectCount = 0;
    public $unansweredCount = 0;
    public $incorrectQuestions = [];
    public $examKeyAnswers;
    public $percentage = 0;

    // تحلیل
    public $analysisStatus;
    public $analysisImagePath;

    public function mount(Exam $exam, ExamAttemp $attempt)
    {
        $this->exam = $exam;
        $this->attempt = $attempt;
        $this->student = $attempt->student;

        $this->examKeyAnswers = $this->exam->keyAnswers()
            ->get()
            ->keyBy('question_number');

        $this->calculateResults();
        $this->checkAnalysisStatus();
    }

    private function calculateResults()
    {
        $studentAnswers = $this->attempt->answers ?? [];

        $this->correctCount = 0;
        $this->incorrectCount = 0;
        $this->unansweredCount = 0;
        $this->incorrectQuestions = [];

        // حلقه روی همه سوال‌ها
        for ($i = 1; $i <= $this->exam->number_of_questions; $i++) {
            $studentAnswer = $studentAnswers[$i] ?? null;
            $keyAnswer = $this->examKeyAnswers->get($i);

            if ($studentAnswer === null) {
                $this->unansweredCount++;
            } elseif ($keyAnswer && $studentAnswer == $keyAnswer->correct_option) {
                $this->correctCount++;
            } else {
                $this->incorrectCount++;
                $this->incorrectQuestions[$i] = [
                    'student_answer' => $studentAnswer,
                    'correct_option' => $keyAnswer->correct_option ?? null,
                    'description' => $keyAnswer->description ?? null,
                ];
            }
        }

        // ✅ درصد باید بعد از حلقه حساب بشه
        $totalQuestions = $this->exam->number_of_questions;
        if ($totalQuestions > 0) {
            $score = ($this->correctCount * 3) - $this->incorrectCount;
            $maxScore = $totalQuestions * 3;
            if ($maxScore > 0) {
                $this->percentage = round(($score / $maxScore) * 100, 2);
            }
        }
    }

    private function checkAnalysisStatus()
    {
        $analysis = ExamAnalyses::where('exam_id', $this->exam->id)
            ->where('student_id', $this->student->id)
            ->first();

        $timeExpired = $this->attempt->submitted_at
            ? now()->diffInHours($this->attempt->submitted_at) > 48
            : true;

        if ($analysis) {
            $this->analysisStatus = 'sent';
            $this->analysisImagePath = $analysis->image_path;
        } elseif ($timeExpired) {
            $this->analysisStatus = 'expired';
        } else {
            $this->analysisStatus = 'not_sent';
        }
    }

    public function render()
    {
        return view('livewire.admin.student.exam.student-detail')
            ->layout('layouts.admin.app');
    }
}
