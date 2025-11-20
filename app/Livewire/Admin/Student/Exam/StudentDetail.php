<?php

namespace App\Livewire\Admin\Student\Exam;

use App\Models\Exam;
use App\Models\ExamAnalyses;
use App\Models\ExamAttemp;
use App\Models\Student;
use App\Traits\UploadFile;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithFileUploads;

class StudentDetail extends Component
{
    use WithFileUploads, UploadFile;

    public $analysisPhoto = [];
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
    public $solutionPdf;
    public $examPdf;
    public $uploadSuccess = false;

    #[Computed]
    public function allQuestions()
    {
        $questions = [];
        $studentAnswers = $this->attempt->answers ?? [];

        for ($i = 1; $i <= $this->exam->number_of_questions; $i++) {
            $studentAnswer = $studentAnswers[$i] ?? null;
            $keyAnswer = $this->examKeyAnswers->get($i);

            if ($studentAnswer === null) {
                $status = 'unanswered';
            } elseif ($keyAnswer && $studentAnswer == $keyAnswer->correct_option) {
                $status = 'correct';
            } else {
                $status = 'incorrect';
            }

            $questions[$i] = [
                'number' => $i,
                'status' => $status,
                'student_answer' => $studentAnswer,
                'correct_option' => $keyAnswer->correct_option ?? null,
                'description' => $keyAnswer->description ?? null,
            ];
        }

        return $questions;
    }

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

        $this->solutionPdf = $this->exam->solution_pdf_path ?? null;
        $this->examPdf = $this->exam->pdf_path ?? null;
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
            $decoded = @json_decode($analysis->image_path, true);
            if (is_array($decoded)) {
                $this->analysisImagePath = $decoded;
            } else {
                $this->analysisImagePath = [$analysis->image_path];
            }
        } elseif ($timeExpired) {
            $this->analysisStatus = 'expired';
        } else {
            $this->analysisStatus = 'not_sent';
        }
    }

    public function uploadAnalysis()
    {
        if ($this->analysisStatus === 'expired') {
            $this->dispatch('error', 'مهلت ارسال تحلیل به پایان رسیده است.');
            return;
        }

        $this->validate([
            'analysisPhoto' => 'required|array|min:1',
            'analysisPhoto.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $paths = [];

        foreach ($this->analysisPhoto as $file) {
            $filePath = $this->uploadImageInWebpFormatExamAnalisis(
                $file,
                $this->student->id,
                600,
                600,
                'examAnalysis'
            );

            if ($filePath) {
                $paths[] = $filePath;
            }
        }

        ExamAnalyses::updateOrCreate(
            [
                'exam_id' => $this->exam->id,
                'student_id' => $this->student->id,
            ],
            [
                'image_path' => json_encode($paths),
            ]
        );

        $this->analysisPhoto = [];
        $this->uploadSuccess = true;

        $this->checkAnalysisStatus();

        $this->dispatch('successUpload', delay: 3000);
        $this->dispatch('success', 'تحلیل با موفقیت آپلود شد!');
    }

    public function render()
    {
        return view('livewire.admin.student.exam.student-detail')
            ->layout('layouts.admin.app');
    }
}
