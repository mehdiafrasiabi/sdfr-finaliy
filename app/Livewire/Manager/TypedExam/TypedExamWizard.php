<?php


namespace App\Livewire\Manager\TypedExam;


use App\Models\Question;

use App\Models\Subject;

use App\Models\TypedExam;

use App\Models\TypedExamRandomConfig;

use App\Models\TypedExamSetting;

use Illuminate\Support\Facades\DB;

use Livewire\Component;

use Livewire\WithPagination;


class TypedExamWizard extends Component

{

    use WithPagination;


    public int $currentStep = 1;

    public ?int $examId = null;

    public bool $isEditMode = false;


    // Step 1: General Settings

    public string $title = '';

    public string $academic_year = '1404-1405';

    public string $difficulty = 'medium';

    public bool $is_random_selection = false;

    public string $result_visibility = 'after_exam_end';

    public string $answer_key_visibility = 'after_exam_end';

    public string $randomization_type = 'none';

    public string $description = '';


    // Random selection modal

    public bool $showRandomModal = false;

    public string $randomDifficulty = 'medium';

    public int $randomCount = 10;


    // Step 2: Question Selection

    public string $filterSubject = '';

    public string $filterDifficulty = '';

    public string $filterKeyword = '';

    public string $filterCode = '';

    public string $sortOrder = 'desc';


    // Selected questions IDs

    public array $selectedQuestions = [];


    // Step 3: Question Order

    public array $orderedQuestions = [];


    protected function rules(): array

    {

        return [

            'title' => 'required|string|max:255',

            'academic_year' => 'required|string',

            'difficulty' => 'required|in:easy,medium,hard,comprehensive',

            'result_visibility' => 'required|in:after_exam_end,immediately',

            'answer_key_visibility' => 'required|in:after_exam_end,immediately',

            'randomization_type' => 'required|in:none,questions_only,options_only,both',

        ];

    }


    protected function messages(): array

    {

        return [

            'title.required' => 'عنوان آزمون الزامی است.',

            'title.max' => 'عنوان آزمون نباید بیشتر از ۲۵۵ کاراکتر باشد.',

            'academic_year.required' => 'انتخاب دوره زمانی الزامی است.',

            'difficulty.required' => 'انتخاب درجه سختی آزمون الزامی است.',

        ];

    }


    public function mount(?int $id = null): void

    {

        if ($id) {

            $this->loadExam($id);

        }

    }


    protected function loadExam(int $id): void

    {

        $exam = TypedExam::with(['settings', 'questions', 'randomConfigs'])->findOrFail($id);


        $this->isEditMode = true;

        $this->examId = $exam->id;

        $this->title = $exam->title;

        $this->academic_year = $exam->academic_year;

        $this->difficulty = $exam->difficulty;

        $this->is_random_selection = $exam->is_random_selection;


        if ($exam->settings) {

            $this->result_visibility = $exam->settings->result_visibility;

            $this->answer_key_visibility = $exam->settings->answer_key_visibility;

            $this->randomization_type = $exam->settings->randomization_type;

            $this->description = $exam->settings->description ?? '';

        }


        // Load selected questions

        $this->selectedQuestions = $exam->questions->pluck('id')->toArray();


        // Load ordered questions

        $this->orderedQuestions = $exam->questions->sortBy('pivot.order')->values()->map(function ($q) {

            return [

                'id' => $q->id,

                'code' => $q->code,

                'subject' => $q->subject?->name,

                'difficulty' => $q->difficulty,

            ];

        })->toArray();

    }


    public function goToStep(int $step): void

    {

        if ($step === 1) {

            $this->currentStep = 1;

            return;

        }


        // Validate step 1 before going to step 2 or 3

        if ($step >= 2) {

            $this->validate();

        }


        // Validate at least one question selected before going to step 3

        if ($step === 3 && empty($this->selectedQuestions)) {

            $this->addError('selectedQuestions', 'لطفاً حداقل یک سوال انتخاب کنید.');

            return;

        }


        $this->currentStep = $step;


        if ($step === 3) {

            $this->prepareOrderedQuestions();

        }

    }


    public function nextStep(): void

    {

        $this->goToStep($this->currentStep + 1);

    }


    public function prevStep(): void

    {

        if ($this->currentStep > 1) {

            $this->currentStep--;

        }

    }


    public function toggleRandomSelection(): void

    {

        $this->is_random_selection = !$this->is_random_selection;


        if ($this->is_random_selection) {

            $this->showRandomModal = true;

        }

    }


    public function selectRandomQuestions(): void

    {

        $questions = Question::where('difficulty', $this->randomDifficulty)
            ->whereNotIn('id', $this->selectedQuestions)
            ->inRandomOrder()
            ->limit($this->randomCount)
            ->pluck('id')
            ->toArray();


        $this->selectedQuestions = array_merge($this->selectedQuestions, $questions);

        $this->showRandomModal = false;


        $this->dispatch('success', count($questions) . ' سوال به صورت تصادفی انتخاب شد.');

    }


    public function addQuestion(int $questionId): void

    {

        if (!in_array($questionId, $this->selectedQuestions)) {

            $this->selectedQuestions[] = $questionId;

        }

    }


    public function removeQuestion(int $questionId): void

    {

        $this->selectedQuestions = array_diff($this->selectedQuestions, [$questionId]);

        $this->orderedQuestions = array_filter($this->orderedQuestions, fn($q) => $q['id'] !== $questionId);

    }


    public function isQuestionSelected(int $questionId): bool

    {

        return in_array($questionId, $this->selectedQuestions);

    }


    protected function prepareOrderedQuestions(): void

    {

        $questions = Question::with('subject')
            ->whereIn('id', $this->selectedQuestions)
            ->get();


        // Keep existing order if already ordered, add new ones at the end

        $existingIds = collect($this->orderedQuestions)->pluck('id')->toArray();

        $newQuestions = $questions->filter(fn($q) => !in_array($q->id, $existingIds));


        foreach ($newQuestions as $question) {

            $this->orderedQuestions[] = [

                'id' => $question->id,

                'code' => $question->code,

                'subject' => $question->subject?->name,

                'difficulty' => $question->difficulty,

            ];

        }


        // Remove any questions that are no longer selected

        $this->orderedQuestions = array_filter($this->orderedQuestions, fn($q) => in_array($q['id'], $this->selectedQuestions));

        $this->orderedQuestions = array_values($this->orderedQuestions);

    }


    public function updateQuestionOrder(array $order): void

    {

        $this->orderedQuestions = $order;

    }


    public function save(): void

    {

        $this->validate();


        if (empty($this->selectedQuestions)) {

            $this->addError('selectedQuestions', 'لطفاً حداقل یک سوال انتخاب کنید.');

            return;

        }


        DB::transaction(function () {

            // Create or update exam

            $exam = TypedExam::updateOrCreate(

                ['id' => $this->examId],

                [

                    'title' => $this->title,

                    'academic_year' => $this->academic_year,

                    'difficulty' => $this->difficulty,

                    'is_random_selection' => $this->is_random_selection,

                ]

            );


            // Update settings

            TypedExamSetting::updateOrCreate(

                ['typed_exam_id' => $exam->id],

                [

                    'result_visibility' => $this->result_visibility,

                    'answer_key_visibility' => $this->answer_key_visibility,

                    'randomization_type' => $this->randomization_type,

                    'description' => $this->description,

                ]

            );


            // Sync questions with order

            $syncData = [];

            foreach ($this->orderedQuestions as $index => $question) {

                $syncData[$question['id']] = ['order' => $index + 1];

            }

            $exam->questions()->sync($syncData);


            // Save random config if applicable

            if ($this->is_random_selection) {

                TypedExamRandomConfig::updateOrCreate(

                    ['typed_exam_id' => $exam->id],

                    [

                        'difficulty' => $this->randomDifficulty,

                        'count' => $this->randomCount,

                    ]

                );

            }


            $this->examId = $exam->id;

            $this->isEditMode = true;

        });


        $this->dispatch('success', $this->isEditMode ? 'آزمون با موفقیت ذخیره شد.' : 'آزمون با موفقیت ایجاد شد.');

    }


    public function clearFilters(): void

    {

        $this->reset(['filterSubject', 'filterDifficulty', 'filterKeyword', 'filterCode', 'sortOrder']);

        $this->sortOrder = 'desc';

        $this->resetPage();

    }


    public function render()

    {

        $subjects = Subject::active()->orderBy('name')->get();


        $academicYears = [

            '1404-1405' => '۱۴۰۴-۱۴۰۵',

            '1405-1406' => '۱۴۰۵-۱۴۰۶',

        ];


        $examDifficulties = [

            'easy' => 'آسان',

            'medium' => 'متوسط',

            'hard' => 'سخت',

            'comprehensive' => 'جامع',

        ];


        $questionDifficulties = [

            'easy' => 'آسان',

            'medium' => 'متوسط',

            'hard' => 'سخت',

            'special' => 'ویژه',

        ];


        $resultVisibilities = [

            'after_exam_end' => 'بعد از زمان پایان آزمون',

            'immediately' => 'به محض پایان آزمون توسط کاربر',

        ];


        $randomizationTypes = [

            'none' => 'خیر',

            'questions_only' => 'بله، فقط سوالات',

            'options_only' => 'بله، فقط گزینه‌ها',

            'both' => 'بله، هم سوالات و هم گزینه‌ها',

        ];


        // For step 2: Question list

        $questions = null;

        if ($this->currentStep === 2) {

            $query = Question::with(['subject', 'content', 'options']);


            if ($this->filterSubject) {

                $query->where('subject_id', $this->filterSubject);

            }

            if ($this->filterDifficulty) {

                $query->where('difficulty', $this->filterDifficulty);

            }

            if ($this->filterKeyword) {

                $query->searchKeyword($this->filterKeyword);

            }

            if ($this->filterCode) {

                $query->where('code', 'like', "%{$this->filterCode}%");

            }


            $query->orderBy('created_at', $this->sortOrder);

            $questions = $query->paginate(10);

        }


        return view('livewire.manager.typed-exam.typed-exam-wizard', compact(

            'subjects',

            'academicYears',

            'examDifficulties',

            'questionDifficulties',

            'resultVisibilities',

            'randomizationTypes',

            'questions'

        ))->layout('layouts.manager.app');

    }

}
