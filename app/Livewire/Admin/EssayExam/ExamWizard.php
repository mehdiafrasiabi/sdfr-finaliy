<?php

namespace App\Livewire\Admin\EssayExam;

use App\Helpers\FileHelper;
use App\Models\CcChapter;
use App\Models\CcField;
use App\Models\CcGrade;
use App\Models\CcSubject;
use App\Models\CcTopic;
use App\Models\EssayExam;
use App\Models\EssayExamQuestion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;

class ExamWizard extends Component
{
    use WithFileUploads;

    public ?int $examId = null;

    // Step 1 - metadata
    public string $title = '';
    public ?int $cc_grade_id = null;
    public ?int $cc_field_id = null;
    public ?int $cc_subject_id = null;
    public ?int $cc_chapter_id = null;
    public ?int $cc_topic_id = null;

    // Step 1 - questions/scores
    public int $total_questions = 10;
    /** @var array<int, array{score:float, row_height:int}> */
    public array $questions = [];

    // Files
    public $question_pdf;
    public $answer_pdf;

    // existing paths when editing
    public ?string $question_pdf_path = null;
    public ?string $answer_pdf_path = null;

    public int $step = 1;

    /** @var float[] */
    public array $allowedScores = [
        0.25, 0.5, 0.75,
        1, 1.25, 1.5, 1.75,
        2, 2.25, 2.5, 2.75,
        3, 3.25, 3.5, 3.75,
        4, 4.25, 4.5, 4.75, 5,
    ];

    public function mount(?int $examId = null): void
    {
        $this->examId = $examId;
        if ($examId) {
            $this->loadExam($examId);
        } else {
            $this->resizeQuestions();
        }
    }

    protected function loadExam(int $examId): void
    {
        $admin = Auth::guard('admin')->user();
        $exam = EssayExam::where('admin_id', $admin->id)->findOrFail($examId);

        $this->title = $exam->title;
        $this->cc_topic_id = $exam->cc_topic_id;
        $this->question_pdf_path = $exam->question_pdf_path;
        $this->answer_pdf_path = $exam->answer_pdf_path;

        if ($exam->cc_topic_id) {
            $topic = CcTopic::with('chapter.subject.grade', 'chapter.subject.field')->find($exam->cc_topic_id);
            if ($topic) {
                $this->cc_chapter_id = $topic->cc_chapter_id;
                $this->cc_subject_id = $topic->chapter?->cc_subject_id;
                $this->cc_field_id = $topic->chapter?->subject?->cc_field_id;
                $this->cc_grade_id = $topic->chapter?->subject?->cc_grade_id;
            }
        }

        $existing = $exam->questions()->orderBy('question_number')->get();
        $this->total_questions = max(1, $existing->count() ?: 10);
        $this->questions = [];
        foreach ($existing as $q) {
            $this->questions[] = [
                'score' => (float) $q->score,
                'row_height' => (int) $q->row_height,
            ];
        }
        $this->resizeQuestions();
    }

    public function updatedTotalQuestions(): void
    {
        $this->total_questions = max(1, min(100, (int) $this->total_questions));
        $this->resizeQuestions();
    }

    protected function resizeQuestions(): void
    {
        $count = count($this->questions);
        if ($count < $this->total_questions) {
            for ($i = $count; $i < $this->total_questions; $i++) {
                $this->questions[] = ['score' => 1.0, 'row_height' => 110];
            }
        } elseif ($count > $this->total_questions) {
            $this->questions = array_slice($this->questions, 0, $this->total_questions);
        }
    }

    public function setRowHeight(int $index, int $height): void
    {
        if (!isset($this->questions[$index])) return;
        $this->questions[$index]['row_height'] = max(60, min(400, $height));
    }

    public function updatedCcGradeId(): void
    {
        $this->cc_field_id = null;
        $this->cc_subject_id = null;
        $this->cc_chapter_id = null;
        $this->cc_topic_id = null;
    }

    public function updatedCcFieldId(): void
    {
        $this->cc_subject_id = null;
        $this->cc_chapter_id = null;
        $this->cc_topic_id = null;
    }

    public function updatedCcSubjectId(): void
    {
        $this->cc_chapter_id = null;
        $this->cc_topic_id = null;
    }

    public function updatedCcChapterId(): void
    {
        $this->cc_topic_id = null;
    }

    protected function validateScore(float $value): bool
    {
        // accept multiples of 0.25 between 0.25 and 5
        $x = round($value * 4);
        return $x >= 1 && $x <= 20 && abs($value * 4 - $x) < 0.01;
    }

    public function save()
    {
        $this->validate([
            'title' => 'required|string|max:200',
            'cc_topic_id' => 'required|exists:cc_topics,id',
            'total_questions' => 'required|integer|min:1|max:100',
            'questions' => 'required|array|min:1',
            'questions.*.score' => 'required|numeric|min:0.25|max:5',
            'questions.*.row_height' => 'required|integer|min:60|max:400',
            'question_pdf' => $this->examId && $this->question_pdf_path ? 'nullable|file|mimes:pdf|max:20480' : 'required|file|mimes:pdf|max:20480',
            'answer_pdf' => $this->examId && $this->answer_pdf_path ? 'nullable|file|mimes:pdf|max:20480' : 'required|file|mimes:pdf|max:20480',
        ]);

        foreach ($this->questions as $idx => $q) {
            if (!$this->validateScore((float) $q['score'])) {
                $this->addError("questions.$idx.score", 'نمره باید مضربی از 0.25 باشد.');
                return;
            }
        }

        $admin = Auth::guard('admin')->user();
        $totalScore = array_sum(array_map(fn($q) => (float) $q['score'], $this->questions));

        $exam = DB::transaction(function () use ($admin, $totalScore) {
            $exam = $this->examId
                ? EssayExam::where('admin_id', $admin->id)->findOrFail($this->examId)
                : new EssayExam(['admin_id' => $admin->id]);

            $exam->title = $this->title;
            $exam->cc_topic_id = $this->cc_topic_id;
            $exam->total_score = $totalScore;

            if ($this->question_pdf) {
                $exam->question_pdf_path = FileHelper::uploadToPublicHtml(
                    $this->question_pdf,
                    "essay-exams/questions/" . $admin->id,
                    true
                );
            }
            if ($this->answer_pdf) {
                $exam->answer_pdf_path = FileHelper::uploadToPublicHtml(
                    $this->answer_pdf,
                    "essay-exams/answers/" . $admin->id,
                    true
                );
            }
            $exam->save();

            // Re-create questions
            $exam->questions()->delete();
            foreach ($this->questions as $idx => $q) {
                EssayExamQuestion::create([
                    'essay_exam_id'   => $exam->id,
                    'question_number' => $idx + 1,
                    'score'           => (float) $q['score'],
                    'row_height'      => (int) $q['row_height'],
                ]);
            }
            return $exam;
        });

        session()->flash('success', 'آزمون با موفقیت ذخیره شد.');
        return redirect()->route('admin.essay-exams.index');
    }

    public function render()
    {
        $grades   = CcGrade::where('is_active', true)->orderBy('order')->get();
        $fields   = $this->cc_grade_id
            ? CcField::where('is_active', true)->orderBy('order')->get()
            : collect();
        $subjects = $this->cc_field_id
            ? CcSubject::where('cc_grade_id', $this->cc_grade_id)
                ->where('cc_field_id', $this->cc_field_id)
                ->orderBy('order')->get()
            : collect();
        $chapters = $this->cc_subject_id
            ? CcChapter::where('cc_subject_id', $this->cc_subject_id)->where('is_active', true)->orderBy('order')->get()
            : collect();
        $topics   = $this->cc_chapter_id
            ? CcTopic::where('cc_chapter_id', $this->cc_chapter_id)->where('is_active', true)->orderBy('order')->get()
            : collect();

        $totalScorePreview = array_sum(array_map(fn($q) => (float) ($q['score'] ?? 0), $this->questions));

        $previewPdfUrl = $this->question_pdf_path
            ? rtrim(config('app.url'), '/') . '/' . ltrim($this->question_pdf_path, '/')
            : null;


        return view('livewire.admin.essay-exam.exam-wizard', [
            'grades' => $grades,
            'fields' => $fields,
            'subjects' => $subjects,
            'chapters' => $chapters,
            'topics' => $topics,
            'totalScorePreview' => $totalScorePreview,
            'previewPdfUrl' => $previewPdfUrl,
        ])->layout('layouts.admin.app');
    }
}
