<?php

namespace App\Livewire\Admin\TypedExam;

use App\Models\TypedExamAttempt;
use Illuminate\Support\Facades\File;
use Livewire\Component;

class StudentResult extends Component
{
    public int $examId;
    public int $attemptId;
    public ?TypedExamAttempt $attempt = null;

    public function mount(int $examId, int $attemptId): void
    {
        $this->examId   = $examId;
        $this->attemptId = $attemptId;

        $this->attempt = TypedExamAttempt::with([
            'student.user',
            'assignment.typedExam.questions.content',
            'assignment.typedExam.questions.options',
            'assignment.typedExam.questions.subject',
            'answers',
            'studentOrders',
            'analysisUploads', // مهم
        ])->findOrFail($attemptId);
    }

    /**
     * تایید تحلیل دانش‌آموز
     */
    public function approveAnalysis(): void
    {
        if (! $this->attempt) {
            return;
        }

        foreach ($this->attempt->analysisUploads as $upload) {

            // اگر file_path مثلا چیزی مثل "uploads/analysis/xxx.jpg" باشد
            if ($upload->file_path) {
                $fullPath = base_path('public_html/' . ltrim($upload->file_path, '/'));

                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                }
            }

            $upload->delete();
        }

        $this->attempt->analysis_status = 'approved';
        $this->attempt->save();

        $this->attempt->refresh();

        session()->flash('success', 'تحلیل دانش‌آموز تایید شد و تصاویر حذف شدند.');
    }

    /**
     * رد تحلیل دانش‌آموز و پاک کردن تصاویر
     */
    public function rejectAnalysis(): void
    {
        if (! $this->attempt) {
            return;
        }

        foreach ($this->attempt->analysisUploads as $upload) {

            if ($upload->file_path) {
                $fullPath = base_path('public_html/' . ltrim($upload->file_path, '/'));

                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                }
            }

            $upload->delete();
        }

        $this->attempt->analysis_status = 'rejected';
        $this->attempt->save();

        $this->attempt->refresh();

        session()->flash('success', 'تحلیل دانش‌آموز رد شد و تصاویر قبلی حذف شدند. دانش‌آموز می‌تواند مجدداً تحلیل آپلود کند.');
    }

    public function render()
    {
        $exam    = $this->attempt->assignment->typedExam;
        $answers = $this->attempt->answers->keyBy('question_id');

        $questionsWithAnswers = $exam->questions->map(function ($question) use ($answers) {
            $answer        = $answers->get($question->id);
            $correctOption = $question->options->firstWhere('is_correct', true);

            return [
                'question'              => $question,
                'selected_option'       => $answer?->selected_option,
                'is_correct'            => $answer?->is_correct,
                'correct_option_number' => $correctOption?->option_number,
            ];
        });

        $stats = [
            'correct'     => $this->attempt->correct_count,
            'wrong'       => $this->attempt->wrong_count,
            'unanswered'  => $this->attempt->unanswered_count,
            'score'       => $this->attempt->score,
            'duration'    => $this->attempt->formatted_duration,
        ];

        return view('livewire.admin.typed-exam.student-result', [
            'exam'                 => $exam,
            'questionsWithAnswers' => $questionsWithAnswers,
            'stats'                => $stats,
            'attempt'              => $this->attempt,
        ])->layout('layouts.admin.app');
    }
}
