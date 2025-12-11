<?php



namespace App\Livewire\Admin\TypedExam;



use App\Models\Question;

use App\Models\TypedExam;

use App\Models\TypedExamAttempt;

use App\Models\TypedExamAttemptAnswer;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Livewire\Component;



class ExamStats extends Component

{

    public int $examId;

    public ?TypedExam $exam = null;



    // Statistics data

    public float $averageScore = 0;

    public array $topStudents = [];

    public array $scoreRanges = [];

    public array $answerStats = [];

    public array $questionStats = [];



    public function mount(int $examId): void

    {

        $this->examId = $examId;

        $this->exam = TypedExam::with(['settings', 'questions.options', 'questions.content', 'questions.subject'])

            ->findOrFail($examId);



        $this->calculateStats();

    }



    protected function calculateStats(): void

    {

        $admin = Auth::guard('admin')->user();



        // Get all attempts for students under this admin

        $attempts = TypedExamAttempt::whereHas('assignment', function ($q) use ($admin) {

            $q->where('typed_exam_id', $this->examId)

                ->whereHas('student', function ($sq) use ($admin) {

                    $sq->where('supporter_id', $admin->id)

                        ->orWhere('advisor_id', $admin->id);

                });

        })

            ->where('is_finished', true)

            ->with(['student.user', 'answers'])

            ->get();



        if ($attempts->isEmpty()) {

            return;

        }



        // Average Score

        $this->averageScore = round($attempts->avg('score') ?? 0, 2);



        // Top 3 Students

        $this->topStudents = $attempts->sortByDesc('score')

            ->take(3)

            ->values()

            ->map(function ($attempt, $index) {

                return [

                    'rank' => $index + 1,

                    'name' => $attempt->student?->user?->name ?? 'نامشخص',

                    'score' => $attempt->score ?? 0,

                ];

            })

            ->toArray();



        // Score Ranges

        $ranges = [

            '0-20' => ['min' => 0, 'max' => 20, 'label' => 'کمتر از ۲۰٪', 'count' => 0],

            '20-40' => ['min' => 20, 'max' => 40, 'label' => '۲۰ تا ۴۰٪', 'count' => 0],

            '40-60' => ['min' => 40, 'max' => 60, 'label' => '۴۰ تا ۶۰٪', 'count' => 0],

            '60-80' => ['min' => 60, 'max' => 80, 'label' => '۶۰ تا ۸۰٪', 'count' => 0],

            '80-100' => ['min' => 80, 'max' => 100, 'label' => '۸۰ تا ۱۰۰٪', 'count' => 0],

        ];



        foreach ($attempts as $attempt) {

            $score = $attempt->score ?? 0;

            foreach ($ranges as $key => $range) {

                if ($score >= $range['min'] && $score < $range['max']) {

                    $ranges[$key]['count']++;

                    break;

                }

                // Handle 100%

                if ($score == 100 && $key === '80-100') {

                    $ranges[$key]['count']++;

                }

            }

        }

        $this->scoreRanges = array_values($ranges);



        // Overall Answer Stats (correct, wrong, unanswered)

        $allAnswers = TypedExamAttemptAnswer::whereIn('attempt_id', $attempts->pluck('id'))->get();



        $this->answerStats = [

            'correct' => $allAnswers->where('is_correct', true)->count(),

            'wrong' => $allAnswers->where('is_correct', false)->whereNotNull('selected_option')->count(),

            'unanswered' => $allAnswers->whereNull('selected_option')->count(),

        ];



        // Per-Question Stats

        $this->questionStats = [];

        foreach ($this->exam->questions as $question) {

            $questionAnswers = $allAnswers->where('question_id', $question->id);



            $optionCounts = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 'unanswered' => 0];

            $total = $questionAnswers->count();



            foreach ($questionAnswers as $answer) {

                if ($answer->selected_option === null) {

                    $optionCounts['unanswered']++;

                } else {

                    $optionCounts[$answer->selected_option]++;

                }

            }



            $this->questionStats[] = [

                'id' => $question->id,

                'code' => $question->code,

                'subject' => $question->subject?->name,

                'body' => $question->content?->body,

                'options' => $question->options->map(function ($opt) {

                    return [

                        'number' => $opt->option_number,

                        'content' => $opt->content,

                        'is_correct' => $opt->is_correct,

                    ];

                })->toArray(),

                'stats' => [

                    'option_1' => ['count' => $optionCounts[1], 'percent' => $total > 0 ? round(($optionCounts[1] / $total) * 100, 1) : 0],

                    'option_2' => ['count' => $optionCounts[2], 'percent' => $total > 0 ? round(($optionCounts[2] / $total) * 100, 1) : 0],

                    'option_3' => ['count' => $optionCounts[3], 'percent' => $total > 0 ? round(($optionCounts[3] / $total) * 100, 1) : 0],

                    'option_4' => ['count' => $optionCounts[4], 'percent' => $total > 0 ? round(($optionCounts[4] / $total) * 100, 1) : 0],

                    'unanswered' => ['count' => $optionCounts['unanswered'], 'percent' => $total > 0 ? round(($optionCounts['unanswered'] / $total) * 100, 1) : 0],

                ],

                'total_responses' => $total,

            ];

        }

    }



    public function render()

    {

        return view('livewire.admin.typed-exam.exam-stats')

            ->layout('layouts.admin.app');

    }

}
