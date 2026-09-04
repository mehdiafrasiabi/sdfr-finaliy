<?php

namespace App\Livewire\Admin\TypedExam;

use App\Models\TypedExamAttempt;
use Illuminate\Support\Facades\Auth;
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
            'studentOrders.question.content',
            'studentOrders.question.options',
            'studentOrders.question.subject',
            'analysisUploads', // مهم
        ])
            ->whereKey($attemptId)
            ->whereHas('assignment', fn ($query) => $query->where('typed_exam_id', $examId))
            ->whereHas('student', fn ($query) => $query->where('advisor_id', Auth::guard('admin')->id()))
            ->firstOrFail();

        foreach ($this->attempt->answers as $answer) {
            $answer->checkCorrectness();
        }
        $this->attempt->updateScore();
        $this->attempt->refresh()->load([
            'student.user',
            'assignment.typedExam.questions.content',
            'assignment.typedExam.questions.options',
            'assignment.typedExam.questions.subject',
            'answers',
            'studentOrders.question.content',
            'studentOrders.question.options',
            'studentOrders.question.subject',
            'analysisUploads',
        ]);
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

        $orders = $this->attempt->studentOrders->sortBy('question_order');
        $questionSource = $orders->isNotEmpty() ? $orders : $exam->questions;

        $questionsWithAnswers = $questionSource->map(function ($item) use ($answers, $orders) {
            $question = $orders->isNotEmpty() ? $item->question : $item;

            if (!$question) {
                return null;
            }

            $answer = $answers->get($question->id);
            $optionsOrder = $orders->isNotEmpty()
                ? array_map('intval', $item->options_order ?: [1, 2, 3, 4])
                : [1, 2, 3, 4];
            $correctOption = $answer?->correctOptionNumber() ?? $question->correct_option_number;
            $selectedPosition = $answer?->selected_option === null
                ? null
                : array_search((int) $answer->selected_option, $optionsOrder, true);
            $correctPosition = $correctOption === null
                ? null
                : array_search((int) $correctOption, $optionsOrder, true);
            $options = $question->options->keyBy('option_number');

            return [
                'question'              => $question,
                'selected_option'       => $answer?->selected_option,
                'selected_position'     => is_int($selectedPosition) ? $selectedPosition + 1 : null,
                'is_correct'            => $answer?->selected_option === null
                    ? null
                    : (int) $answer->selected_option === (int) $correctOption,
                'correct_option_number' => $correctOption,
                'correct_position'      => is_int($correctPosition) ? $correctPosition + 1 : null,
                'ordered_options'       => collect($optionsOrder)
                    ->map(fn ($number) => $options->get($number))
                    ->filter()
                    ->values(),
            ];
        })->filter()->values();

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
