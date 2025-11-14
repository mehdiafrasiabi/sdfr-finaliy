<?php

namespace App\Livewire\Client\Profile\Exam;

use App\Models\Exam;
use App\Models\ExamAttemp;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ExamList extends Component
{

    public $easyExams = [];
    public $mediumExams = [];
    public $hardExams = [];
    public $comprehensiveExams = [];
    public $confirmingExamId = null;

    public function mount()
    {
        $this->loadExams();
    }

    private function loadExams()
    {
        $student = auth()->user()->student;

        if ($student) {
            $exams = $student->exams()->get();
            $this->easyExams = $exams->where('level', 'easy');
            $this->mediumExams = $exams->where('level', 'medium');
            $this->hardExams = $exams->where('level', 'hard');
            $this->comprehensiveExams = $exams->where('level', 'comprehensive');
        }
    }

    public function confirmEntry(int $examId)
    {
        $this->confirmingExamId = $examId;
    }

    public function enterExam()
    {
        $exam = Exam::findOrFail($this->confirmingExamId);
        $student = auth()->user()->student;

        // بررسی می‌کنیم که آیا قبلا اقدام به این آزمون انجام شده یا خیر
        $attempt = ExamAttemp::where('exam_id', $exam->id)
            ->where('student_id', $student->id)
            ->first();

        // اگر قبلاً تلاشی وجود نداشت، یک رکورد جدید ایجاد می‌کنیم.
        if (!$attempt) {
            ExamAttemp::create([
                'exam_id' => $exam->id,
                'student_id' => $student->id,
                'started_at' => now(),
            ]);
        }

        // در این مرحله، دانش‌آموز به صفحه آزمون منتقل می‌شود.
        // شما باید روت مربوط به نمایش سوالات را ایجاد کنید.
        return redirect()->route('client.profile.exam.test', $exam->id);
    }

    public function render()
    {


        return view('livewire.client.profile.exam.exam-list')->layout('layouts.client.app');
    }
}
