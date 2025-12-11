<?php


namespace App\Livewire\Client\Profile\TypedExam;


use App\Models\Student;

use App\Models\TypedExamAssignment;

use App\Models\TypedExamAttempt;

use App\Models\TypedExamAttemptAnswer;

use App\Models\TypedExamStudentOrder;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Livewire\Component;


class TypedExamTest extends Component

{

    public int $assignmentId;

    public ?TypedExamAssignment $assignment = null;

    public ?TypedExamAttempt $attempt = null;


    public int $currentQuestionIndex = 0;

    public array $questionsOrder = [];

    public array $answers = [];

    public array $questionMarks = []; // 'minus', 'circle', 'close', or null


    // Timer

    public int $remainingSeconds = 0;

    public bool $showTimer = false;


    // View mode: 'questions' or 'answersheet'

    public string $viewMode = 'questions';


    // Filter: 'all', 'unanswered', 'minus', 'circle', 'close'

    public string $questionFilter = 'all';


    public function mount(int $assignmentId): void
    {
        $this->assignmentId = $assignmentId;

        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->firstOrFail();

        $this->assignment = TypedExamAssignment::with([
            'typedExam.questions.content',
            'typedExam.questions.options',
            'typedExam.settings',
            'time',
        ])
            ->where('id', $assignmentId)
            ->where('student_id', $student->id)
            ->firstOrFail();

        // Check if exam is accessible
        if (!$this->assignment->isWithinTimeWindow()) {
            abort(403, 'این آزمون در بازه زمانی مجاز نیست.');
        }

        // Check for existing attempt
        $this->attempt = TypedExamAttempt::where('assignment_id', $this->assignmentId)
            ->where('student_id', $student->id)
            ->first();

        if ($this->attempt && $this->attempt->is_finished) {
            $this->redirectRoute('client.profile.typed-exam.result', [
                'attemptId' => $this->attempt->id,
            ]);
            return; // فقط اجرای ادامه متد را متوقف می‌کنیم
        }

        if (!$this->attempt) {
            $this->startNewAttempt($student);
        } else {
            $this->loadExistingAttempt();
        }

        // Calculate remaining time
        $this->calculateRemainingTime();
    }


    protected function calculateRemainingTime(): void
    {
        // مدت آزمون به دقیقه (اول از assignment->time، بعد خود آزمون، بعد ۶۰ پیش‌فرض)
        $durationMinutes = $this->assignment->time?->duration_minutes
            ?? $this->assignment->typedExam->duration_minutes
            ?? 60;

        $durationMinutes = (int)$durationMinutes;
        $durationSeconds = $durationMinutes * 60;

        // اگر هنوز attempt ساخته نشده (به هر دلیل)، فقط مدت کامل را ست کن
        if (!$this->attempt || !$this->attempt->started_at) {
            $this->remainingSeconds = $durationSeconds;
            return;
        }

        // زمان شروع و الان به ثانیه از epoch
        $startTs = $this->attempt->started_at->getTimestamp();
        $nowTs = now()->getTimestamp();

        // زمان پایان این دانش‌آموز = شروع + مدت آزمون
        $endTs = $startTs + $durationSeconds;

        // زمان باقیمانده = پایان - الان (حداقل صفر)
        $this->remainingSeconds = max(0, $endTs - $nowTs);

        // اگر واقعاً تمام شده و هنوز بسته نشده، همین‌جا ببند
        if ($this->remainingSeconds === 0 && !$this->attempt->is_finished) {
            $this->timeExpired();
        }
    }


    protected function startNewAttempt(Student $student): void

    {

        DB::transaction(function () use ($student) {

            $this->attempt = TypedExamAttempt::create([

                'assignment_id' => $this->assignmentId,

                'student_id' => $student->id,

                'started_at' => now(),

                'is_finished' => false,

            ]);


            $this->assignment->update(['status' => 'started']);


            $exam = $this->assignment->typedExam;

            $settings = $exam->settings;

            $questions = $exam->questions;


            // Apply randomization

            $questionIds = $questions->pluck('id')->toArray();

            if ($settings && $settings->shouldRandomizeQuestions()) {

                shuffle($questionIds);

            }


            $order = 1;

            foreach ($questionIds as $questionId) {

                $question = $questions->firstWhere('id', $questionId);

                $optionsOrder = [1, 2, 3, 4];


                if ($settings && $settings->shouldRandomizeOptions()) {

                    shuffle($optionsOrder);

                }


                TypedExamStudentOrder::create([

                    'attempt_id' => $this->attempt->id,

                    'question_id' => $questionId,

                    'question_order' => $order,

                    'options_order' => $optionsOrder,

                ]);


                TypedExamAttemptAnswer::create([

                    'attempt_id' => $this->attempt->id,

                    'question_id' => $questionId,

                    'selected_option' => null,

                    'is_correct' => null,

                ]);


                $order++;

            }


            $this->loadExistingAttempt();

        });

    }


    protected function loadExistingAttempt(): void

    {

        $orders = TypedExamStudentOrder::where('attempt_id', $this->attempt->id)
            ->orderBy('question_order')
            ->get();


        $this->questionsOrder = $orders->map(function ($order) {

            return [

                'question_id' => $order->question_id,

                'options_order' => $order->options_order,

            ];

        })->toArray();


        $answers = TypedExamAttemptAnswer::where('attempt_id', $this->attempt->id)->get();

        $this->answers = $answers->keyBy('question_id')->map(fn($a) => $a->selected_option)->toArray();


        // Initialize question marks

        foreach ($this->questionsOrder as $q) {

            if (!isset($this->questionMarks[$q['question_id']])) {

                $this->questionMarks[$q['question_id']] = null;

            }

        }

    }


    public function selectAnswer(int $questionId, int $optionNumber): void
    {
        $current = $this->answers[$questionId] ?? null;

        // اگر همین گزینه دوباره کلیک شد → جواب پاک شود
        if ($current === $optionNumber) {
            $this->answers[$questionId] = null;

            TypedExamAttemptAnswer::where('attempt_id', $this->attempt->id)
                ->where('question_id', $questionId)
                ->update([
                    'selected_option' => null,
                    'answered_at' => null,
                ]);

            return;
        }

        // حالت عادی: ثبت / تغییر گزینه
        $this->answers[$questionId] = $optionNumber;

        TypedExamAttemptAnswer::where('attempt_id', $this->attempt->id)
            ->where('question_id', $questionId)
            ->update([
                'selected_option' => $optionNumber,
                'answered_at' => now(),
            ]);
    }


    public function setQuestionMark(int $questionId, ?string $mark): void

    {

        if ($this->questionMarks[$questionId] === $mark) {

            $this->questionMarks[$questionId] = null;

        } else {

            $this->questionMarks[$questionId] = $mark;

        }

    }


    public function goToQuestion(int $index): void

    {

        if ($index >= 0 && $index < count($this->questionsOrder)) {

            $this->currentQuestionIndex = $index;

        }

    }


    public function nextQuestion(): void

    {

        $filteredIndices = $this->getFilteredQuestionIndices();

        $currentPos = array_search($this->currentQuestionIndex, $filteredIndices);


        if ($currentPos !== false && $currentPos < count($filteredIndices) - 1) {

            $this->currentQuestionIndex = $filteredIndices[$currentPos + 1];

        }

    }


    public function prevQuestion(): void

    {

        $filteredIndices = $this->getFilteredQuestionIndices();

        $currentPos = array_search($this->currentQuestionIndex, $filteredIndices);


        if ($currentPos !== false && $currentPos > 0) {

            $this->currentQuestionIndex = $filteredIndices[$currentPos - 1];

        }

    }


    public function toggleTimer(): void

    {

        $this->showTimer = !$this->showTimer;

    }


    public function setViewMode(string $mode): void

    {

        $this->viewMode = $mode;

    }


    public function setQuestionFilter(string $filter): void

    {

        $this->questionFilter = $filter;


        // Reset to first filtered question

        $filteredIndices = $this->getFilteredQuestionIndices();

        if (!empty($filteredIndices) && !in_array($this->currentQuestionIndex, $filteredIndices)) {

            $this->currentQuestionIndex = $filteredIndices[0];

        }

    }


    protected function getFilteredQuestionIndices(): array

    {

        $indices = [];


        foreach ($this->questionsOrder as $index => $q) {

            $questionId = $q['question_id'];

            $isAnswered = isset($this->answers[$questionId]) && $this->answers[$questionId] !== null;

            $mark = $this->questionMarks[$questionId] ?? null;


            $include = match ($this->questionFilter) {

                'all' => true,

                'unanswered' => !$isAnswered,

                'minus' => $mark === 'minus',

                'circle' => $mark === 'circle',

                'close' => $mark === 'close',

                default => true,

            };


            if ($include) {

                $indices[] = $index;

            }

        }


        return $indices;

    }


    public function timeExpired(): void

    {

        $this->submitExam();

    }


    public function submitExam(): void
    {
        DB::transaction(function () {
            $answers = TypedExamAttemptAnswer::where('attempt_id', $this->attempt->id)->get();

            foreach ($answers as $answer) {
                $answer->checkCorrectness();
            }

            $this->attempt->update([
                'submitted_at' => now(),
                'is_finished' => true,
            ]);

            $this->attempt->updateScore();
            $this->assignment->update(['status' => 'completed']);
        });

        $this->redirectRoute('client.profile.typed-exam.result', [
            'attemptId' => $this->attempt->id,
        ]);
    }


    public function render()

    {

        $this->calculateRemainingTime();

        $exam = $this->assignment->typedExam;
        $questions = $exam->questions->keyBy('id');

        if ($this->attempt && $this->attempt->is_finished) {
            return redirect()->route('client.profile.typed-exam.result', ['attemptId' => $this->attempt->id]);
        }


        $exam = $this->assignment->typedExam;

        $questions = $exam->questions->keyBy('id');


        $currentQuestionData = null;

        if (isset($this->questionsOrder[$this->currentQuestionIndex])) {

            $qData = $this->questionsOrder[$this->currentQuestionIndex];

            $question = $questions->get($qData['question_id']);


            if ($question) {

                $orderedOptions = collect();

                foreach ($qData['options_order'] as $optNum) {

                    $opt = $question->options->firstWhere('option_number', $optNum);

                    if ($opt) {

                        $orderedOptions->push($opt);

                    }

                }


                $currentQuestionData = [

                    'question' => $question,

                    'options' => $orderedOptions,

                    'selected' => $this->answers[$question->id] ?? null,

                    'mark' => $this->questionMarks[$question->id] ?? null,

                ];

            }

        }


        $totalQuestions = count($this->questionsOrder);

        $answeredCount = collect($this->answers)->filter(fn($a) => $a !== null)->count();

        $filteredIndices = $this->getFilteredQuestionIndices();


        // Build question grid data

        $questionGrid = [];

        foreach ($this->questionsOrder as $index => $q) {

            $questionId = $q['question_id'];

            $questionGrid[] = [

                'index' => $index,

                'question_id' => $questionId,

                'is_answered' => isset($this->answers[$questionId]) && $this->answers[$questionId] !== null,

                'selected_option' => $this->answers[$questionId] ?? null,

                'mark' => $this->questionMarks[$questionId] ?? null,

                'is_current' => $this->currentQuestionIndex === $index,

                'is_filtered' => in_array($index, $filteredIndices),

            ];

        }

        $questionsForView = [];

        foreach ($filteredIndices as $index) {
            if (!isset($this->questionsOrder[$index])) {
                continue;
            }

            $qData = $this->questionsOrder[$index];
            $question = $questions->get($qData['question_id']);

            if (!$question) {
                continue;
            }

            $orderedOptions = collect();
            foreach ($qData['options_order'] as $optNum) {
                $opt = $question->options->firstWhere('option_number', $optNum);
                if ($opt) {
                    $orderedOptions->push($opt);
                }
            }

            $questionsForView[] = [
                'index' => $index,
                'question' => $question,
                'options' => $orderedOptions,
                'selected' => $this->answers[$question->id] ?? null,
                'mark' => $this->questionMarks[$question->id] ?? null,
            ];
        }

        return view('livewire.client.profile.typed-exam.typed-exam-test', [
            'exam' => $exam,
            'currentQuestionData' => $currentQuestionData,
            'totalQuestions' => $totalQuestions,
            'answeredCount' => $answeredCount,
            'questionGrid' => $questionGrid,
            'filteredIndices' => $filteredIndices,
            'questionsForView' => $questionsForView,
            'remainingSeconds' => $this->remainingSeconds,
        ])->layout('layouts.client.app');
    }

}
