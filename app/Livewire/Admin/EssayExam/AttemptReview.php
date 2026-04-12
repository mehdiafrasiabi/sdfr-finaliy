<?php

namespace App\Livewire\Admin\EssayExam;

use App\Models\EssayExamAttempt;
use App\Models\EssayExamQuestionScore;
use App\Models\Notification;
use App\Models\NotificationRecipient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AttemptReview extends Component
{
    public int $attemptId;
    public ?EssayExamAttempt $attempt = null;

    /** @var array<int, float> question_id => score */
    public array $scores = [];
    public string $consultant_message = '';

    public function mount(int $attemptId): void
    {
        $admin = Auth::guard('admin')->user();
        $this->attempt = EssayExamAttempt::with([
            'assignment.essayExam.questions',
            'assignment.student.user',
            'uploads',
            'questionScores',
        ])->findOrFail($attemptId);

        if ($this->attempt->assignment->admin_id !== $admin->id) {
            abort(403);
        }

        $this->attemptId = $attemptId;
        $this->consultant_message = $this->attempt->consultant_message ?? '';

        $existing = $this->attempt->questionScores->keyBy('essay_exam_question_id');
        foreach ($this->attempt->assignment->essayExam->questions as $q) {
            $this->scores[$q->id] = $existing->has($q->id)
                ? (float) $existing[$q->id]->score
                : 0.0;
        }
    }

    public function updatedScores(): void
    {
        // keep scores in [0, max]
        foreach ($this->attempt->assignment->essayExam->questions as $q) {
            $val = (float) ($this->scores[$q->id] ?? 0);
            $max = (float) $q->score;
            if ($val < 0) $val = 0;
            if ($val > $max) $val = $max;
            $this->scores[$q->id] = $val;
        }
    }

    public function saveDraft(): void
    {
        $this->persist(finalize: false);
        session()->flash('success', 'پیش‌نویس ذخیره شد.');
    }

    public function finalize(): void
    {
        $this->persist(finalize: true);
        session()->flash('success', 'تصحیح نهایی شد و به دانش‌آموز اعلان ارسال شد.');
    }

    protected function persist(bool $finalize): void
    {
        DB::transaction(function () use ($finalize) {
            $total = 0.0;
            foreach ($this->attempt->assignment->essayExam->questions as $q) {
                $val = (float) ($this->scores[$q->id] ?? 0);
                $max = (float) $q->score;
                if ($val < 0) $val = 0;
                if ($val > $max) $val = $max;
                $total += $val;

                EssayExamQuestionScore::updateOrCreate(
                    ['attempt_id' => $this->attempt->id, 'essay_exam_question_id' => $q->id],
                    ['score' => $val]
                );
            }

            $this->attempt->total_score = $total;
            $this->attempt->consultant_message = $this->consultant_message ?: null;
            if ($finalize) {
                $this->attempt->status = EssayExamAttempt::STATUS_GRADED;
                $this->attempt->assignment->update(['status' => 'graded']);
            }
            $this->attempt->save();

            if ($finalize) {
                $student = $this->attempt->assignment->student;
                $exam = $this->attempt->assignment->essayExam;
                if ($student?->user) {
                    $admin = Auth::guard('admin')->user();
                    $notification = Notification::create([
                        'admin_id'        => $admin->id,
                        'category'        => Notification::CATEGORY_ANNOUNCEMENT,
                        'target_type'     => Notification::TARGET_SINGLE,
                        'title'           => 'تصحیح آزمون تشریحی',
                        'body'            => 'آزمون «' . $exam->title . '» تصحیح شد. برای مشاهده نتیجه وارد شوید.',
                        'is_from_manager' => false,
                    ]);
                    NotificationRecipient::create([
                        'notification_id' => $notification->id,
                        'user_id'         => $student->user->id,
                        'is_read'         => false,
                    ]);
                }
            }
        });

        $this->attempt->refresh()->load(['uploads', 'questionScores', 'assignment.essayExam.questions']);
    }

    public function render()
    {
        return view('livewire.admin.essay-exam.attempt-review')
            ->layout('layouts.admin.app');
    }
}
