<?php


namespace App\Livewire\Admin\TypedExam;


use App\Models\CcChapter;

use App\Models\CcField;

use App\Models\CcGrade;

use App\Models\CcSubject;

use App\Models\CcTopic;

use App\Models\EducationLevel;

use App\Models\ExamPeriod;

use App\Models\Question;

use App\Models\TypedExam;

use App\Models\TypedExamRandomConfig;

use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\DB;

use Livewire\Component;

use Livewire\WithPagination;


class ExamWizard extends Component

{

    use WithPagination;


    public int $currentStep = 1;

    public ?int $examId = null;

    public bool $isEditMode = false;


    // Step 1: General Settings

    public string $title = '';

    public string $academic_year = '';

    public string $difficulty = 'medium';

    public bool $is_random_selection = false;


    // Random selection modal

    public bool $showRandomModal = false;

    public string $randomDifficulty = 'medium';

    public int $randomCount = 10;


    // Random selection hierarchical filters

    public string $randomEducationLevel = '';

    public string $randomGrade = '';

    public string $randomField = '';

    public string $randomSubject = '';

    public string $randomChapter = '';

    public string $randomTopic = '';


    // Random filter data

    public $randomGrades = [];

    public $randomFields = [];

    public $randomSubjects = [];

    public $randomChapters = [];

    public $randomTopics = [];


    // Step 2: Question Selection - Hierarchical Filters

    public string $filterEducationLevel = '';

    public string $filterGrade = '';

    public string $filterField = '';

    public string $filterSubject = '';

    public string $filterChapter = '';

    public string $filterTopic = '';

    public string $filterDifficulty = '';

    public string $filterCode = '';

    public string $sortOrder = 'desc';


    // Filter Data

    public $educationLevels = [];

    public $grades = [];

    public $fields = [];

    public $subjects = [];

    public $chapters = [];

    public $topics = [];


    // Selected questions IDs

    public array $selectedQuestions = [];


    // Step 3: Question Order

    public array $orderedQuestions = [];


    // آمار مشاور جاری

    public int $myExamsCount = 0;


    protected function rules(): array

    {

        return [

            'title' => 'required|string|max:255',

            'academic_year' => 'required|string',

            'difficulty' => 'required|in:easy,medium,hard,comprehensive',

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

        $this->educationLevels = EducationLevel::where('is_active', true)->orderBy('order')->get();


        // Set default academic year from first active exam period

        $defaultPeriod = ExamPeriod::active()->ordered()->first();

        if ($defaultPeriod) {

            $this->academic_year = $defaultPeriod->value;

        }


        $this->myExamsCount = TypedExam::ownedBy(Auth::guard('admin')->id())->count();


        if ($id) {

            $this->loadExam($id);

        }

    }


    protected function loadExam(int $id): void

    {

        $exam = TypedExam::with(['questions.content', 'randomConfigs'])
            ->ownedBy(Auth::guard('admin')->id())
            ->findOrFail($id);


        $this->isEditMode = true;

        $this->examId = $exam->id;

        $this->title = $exam->title;

        $this->academic_year = $exam->academic_year;

        $this->difficulty = $exam->difficulty;

        $this->is_random_selection = $exam->is_random_selection;


        // Load selected questions

        $this->selectedQuestions = $exam->questions->pluck('id')->toArray();


        // Load ordered questions

        $this->orderedQuestions = $exam->questions->sortBy('pivot.order')->values()->map(function ($q) {

            return [

                'id' => $q->id,

                'code' => $q->code,

                'topic' => $q->topic?->name ?? ($q->chapter?->name ? $q->chapter->name . ' - جامع' : null),

                'difficulty' => $q->difficulty,

                'image' => $q->content?->question_image_url,

            ];

        })->toArray();

    }


    // Main filter cascade methods

    public function updatedFilterEducationLevel($value): void

    {

        $this->reset(['filterGrade', 'filterField', 'filterSubject', 'filterChapter', 'filterTopic']);

        $this->grades = [];

        $this->fields = [];

        $this->subjects = [];

        $this->chapters = [];

        $this->topics = [];

        $this->resetPage();


        if ($value) {

            $this->grades = CcGrade::where('education_level_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();

        }

    }


    public function updatedFilterGrade($value): void

    {

        $this->reset(['filterField', 'filterSubject', 'filterChapter', 'filterTopic']);

        $this->fields = [];

        $this->subjects = [];

        $this->chapters = [];

        $this->topics = [];

        $this->resetPage();


        if ($value) {

            $grade = CcGrade::find($value);

            if ($grade && $grade->grade_number >= 10) {

                $this->fields = CcField::where('is_active', true)->orderBy('order')->get();

            } else {

                $this->subjects = CcSubject::where('cc_grade_id', $value)->orderBy('order')->get();

            }

        }

    }


    public function updatedFilterField($value): void

    {

        $this->reset(['filterSubject', 'filterChapter', 'filterTopic']);

        $this->subjects = [];

        $this->chapters = [];

        $this->topics = [];

        $this->resetPage();


        if ($value && $this->filterGrade) {

            $this->subjects = CcSubject::where('cc_grade_id', $this->filterGrade)
                ->where(function ($q) use ($value) {

                    $q->where('cc_field_id', $value)->orWhereNull('cc_field_id');

                })
                ->orderBy('order')
                ->get();

        }

    }


    public function updatedFilterSubject($value): void

    {

        $this->reset(['filterChapter', 'filterTopic']);

        $this->chapters = [];

        $this->topics = [];

        $this->resetPage();


        if ($value) {

            $this->chapters = CcChapter::where('cc_subject_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();

        }

    }


    public function updatedFilterChapter($value): void

    {

        $this->reset(['filterTopic']);

        $this->topics = [];

        $this->resetPage();


        if ($value) {

            $this->topics = CcTopic::where('cc_chapter_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();

        }

    }


    public function updatedFilterTopic(): void

    {

        $this->resetPage();

    }


    public function updatedFilterDifficulty(): void

    {

        $this->resetPage();

    }


    public function updatedFilterCode(): void

    {

        $this->resetPage();

    }


    // Random modal filter cascade methods

    public function updatedRandomEducationLevel($value): void

    {

        $this->reset(['randomGrade', 'randomField', 'randomSubject', 'randomChapter', 'randomTopic']);

        $this->randomGrades = [];

        $this->randomFields = [];

        $this->randomSubjects = [];

        $this->randomChapters = [];

        $this->randomTopics = [];


        if ($value) {

            $this->randomGrades = CcGrade::where('education_level_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();

        }

    }


    public function updatedRandomGrade($value): void

    {

        $this->reset(['randomField', 'randomSubject', 'randomChapter', 'randomTopic']);

        $this->randomFields = [];

        $this->randomSubjects = [];

        $this->randomChapters = [];

        $this->randomTopics = [];


        if ($value) {

            $grade = CcGrade::find($value);

            if ($grade && $grade->grade_number >= 10) {

                $this->randomFields = CcField::where('is_active', true)->orderBy('order')->get();

            } else {

                $this->randomSubjects = CcSubject::where('cc_grade_id', $value)->orderBy('order')->get();

            }

        }

    }


    public function updatedRandomField($value): void

    {

        $this->reset(['randomSubject', 'randomChapter', 'randomTopic']);

        $this->randomSubjects = [];

        $this->randomChapters = [];

        $this->randomTopics = [];


        if ($value && $this->randomGrade) {

            $this->randomSubjects = CcSubject::where('cc_grade_id', $this->randomGrade)
                ->where(function ($q) use ($value) {

                    $q->where('cc_field_id', $value)->orWhereNull('cc_field_id');

                })
                ->orderBy('order')
                ->get();

        }

    }


    public function updatedRandomSubject($value): void

    {

        $this->reset(['randomChapter', 'randomTopic']);

        $this->randomChapters = [];

        $this->randomTopics = [];


        if ($value) {

            $this->randomChapters = CcChapter::where('cc_subject_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();

        }

    }


    public function updatedRandomChapter($value): void

    {

        $this->reset(['randomTopic']);

        $this->randomTopics = [];


        if ($value) {

            $this->randomTopics = CcTopic::where('cc_chapter_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();

        }

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


    public function openRandomModal(): void

    {

        $this->showRandomModal = true;

    }


    public function selectRandomQuestions(): void

    {

        $query = Question::query();


        // Apply hierarchical filters

        if ($this->randomTopic) {

            $query->where('cc_topic_id', $this->randomTopic);

        } elseif ($this->randomChapter) {

            $query->where(function ($q) {

                $q->where('cc_chapter_id', $this->randomChapter)
                    ->orWhereHas('topic', function ($topicQuery) {
                        $topicQuery->where('cc_chapter_id', $this->randomChapter);
                    });

            });

        } elseif ($this->randomSubject) {

            $query->where(function ($q) {

                $q->where('subject_id', $this->randomSubject)
                    ->orWhereHas('topic.chapter', function ($chapterQuery) {
                        $chapterQuery->where('cc_subject_id', $this->randomSubject);
                    })
                    ->orWhereHas('chapter', function ($chapterQuery) {
                        $chapterQuery->where('cc_subject_id', $this->randomSubject);
                    });

            });

        } elseif ($this->randomGrade) {

            $query->where(function ($q) {

                $q->whereHas('topic.chapter.subject', function ($subjectQuery) {
                    $subjectQuery->where('cc_grade_id', $this->randomGrade);
                })->orWhereHas('chapter.subject', function ($subjectQuery) {
                    $subjectQuery->where('cc_grade_id', $this->randomGrade);
                });

            });

        } elseif ($this->randomEducationLevel) {

            $query->where(function ($q) {

                $q->whereHas('topic.chapter.subject.grade', function ($gradeQuery) {
                    $gradeQuery->where('education_level_id', $this->randomEducationLevel);
                })->orWhereHas('chapter.subject.grade', function ($gradeQuery) {
                    $gradeQuery->where('education_level_id', $this->randomEducationLevel);
                });

            });

        }


        if ($this->randomDifficulty) {

            $query->where('difficulty', $this->randomDifficulty);

        }


        $questions = $query->whereNotIn('id', $this->selectedQuestions)
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

        $questions = Question::with(['topic', 'chapter', 'content'])
            ->whereIn('id', $this->selectedQuestions)
            ->get();


        // Keep existing order if already ordered, add new ones at the end

        $existingIds = collect($this->orderedQuestions)->pluck('id')->toArray();

        $newQuestions = $questions->filter(fn($q) => !in_array($q->id, $existingIds));


        foreach ($newQuestions as $question) {

            $this->orderedQuestions[] = [

                'id' => $question->id,

                'code' => $question->code,

                'topic' => $question->topic?->name ?? ($question->chapter?->name ? $question->chapter->name . ' - جامع' : null),

                'difficulty' => $question->difficulty,

                'image' => $question->content?->question_image_url,

            ];

        }


        // Remove any questions that are no longer selected

        $this->orderedQuestions = array_filter($this->orderedQuestions, fn($q) => in_array($q['id'], $this->selectedQuestions));

        $this->orderedQuestions = array_values($this->orderedQuestions);

    }


    public function updateQuestionOrder(array $order): void

    {

        // فقط ترتیب شناسه‌ها از سمت کلاینت (درگ‌اند‌دراپ) می‌آید؛ بقیه‌ی اطلاعات هر سوال
        // (از جمله تصویر) از روی داده‌ی سمت سرور بازسازی می‌شود تا چیزی گم نشود.
        $orderedIds = collect($order)->pluck('id')->map(fn ($id) => (int) $id)->values();

        $byId = collect($this->orderedQuestions)->keyBy('id');

        $this->orderedQuestions = $orderedIds
            ->map(fn ($id) => $byId->get($id))
            ->filter()
            ->values()
            ->toArray();

    }


    public function moveQuestionUp(int $questionId): void

    {

        $this->swapQuestionOrder($questionId, -1);

    }


    public function moveQuestionDown(int $questionId): void

    {

        $this->swapQuestionOrder($questionId, 1);

    }


    protected function swapQuestionOrder(int $questionId, int $direction): void

    {

        $index = collect($this->orderedQuestions)->search(fn ($q) => $q['id'] === $questionId);

        if ($index === false) {

            return;

        }

        $targetIndex = $index + $direction;

        if ($targetIndex < 0 || $targetIndex >= count($this->orderedQuestions)) {

            return;

        }

        $temp = $this->orderedQuestions[$index];

        $this->orderedQuestions[$index] = $this->orderedQuestions[$targetIndex];

        $this->orderedQuestions[$targetIndex] = $temp;

        $this->orderedQuestions = array_values($this->orderedQuestions);

    }


    public function save(): void

    {

        $this->validate();


        if (empty($this->selectedQuestions)) {

            $this->addError('selectedQuestions', 'لطفاً حداقل یک سوال انتخاب کنید.');

            return;

        }


        $adminId = Auth::guard('admin')->id();


        // اطمینان از اینکه مشاور فقط آزمون خودش را ویرایش می‌کند

        if ($this->examId) {

            $owned = TypedExam::ownedBy($adminId)->whereKey($this->examId)->exists();

            if (!$owned) {

                abort(403);

            }

        }


        DB::transaction(function () use ($adminId) {

            // Create or update exam

            $exam = TypedExam::updateOrCreate(

                ['id' => $this->examId],

                [

                    'admin_id' => $adminId,

                    'title' => $this->title,

                    'academic_year' => $this->academic_year,

                    'difficulty' => $this->difficulty,

                    'is_random_selection' => $this->is_random_selection,

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

                        'cc_topic_id' => $this->randomTopic ?: null,

                        'cc_chapter_id' => $this->randomChapter ?: null,

                        'cc_subject_id' => $this->randomSubject ?: null,

                        'difficulty' => $this->randomDifficulty,

                        'count' => $this->randomCount,

                    ]

                );

            }


            $this->examId = $exam->id;

            $this->isEditMode = true;

        });


        $this->myExamsCount = TypedExam::ownedBy($adminId)->count();


        $this->dispatch('success', $this->isEditMode ? 'آزمون با موفقیت ذخیره شد.' : 'آزمون با موفقیت ایجاد شد.');

    }


    public function clearFilters(): void

    {

        $this->reset([

            'filterEducationLevel', 'filterGrade', 'filterField', 'filterSubject',

            'filterChapter', 'filterTopic', 'filterDifficulty', 'filterCode', 'sortOrder'

        ]);

        $this->grades = [];

        $this->fields = [];

        $this->subjects = [];

        $this->chapters = [];

        $this->topics = [];

        $this->sortOrder = 'desc';

        $this->resetPage();

    }


    public function clearRandomFilters(): void

    {

        $this->reset([

            'randomEducationLevel', 'randomGrade', 'randomField', 'randomSubject',

            'randomChapter', 'randomTopic', 'randomDifficulty'

        ]);

        $this->randomGrades = [];

        $this->randomFields = [];

        $this->randomSubjects = [];

        $this->randomChapters = [];

        $this->randomTopics = [];

        $this->randomDifficulty = 'medium';

    }


    public function render()

    {

        // Get exam periods from database

        $examPeriods = ExamPeriod::active()->ordered()->get();


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


        // Check if field select should be shown for main filters

        $showFieldFilter = false;

        if ($this->filterGrade) {

            $grade = CcGrade::find($this->filterGrade);

            $showFieldFilter = $grade && $grade->grade_number >= 10;

        }


        // Check if field select should be shown for random filters

        $showRandomFieldFilter = false;

        if ($this->randomGrade) {

            $grade = CcGrade::find($this->randomGrade);

            $showRandomFieldFilter = $grade && $grade->grade_number >= 10;

        }


        // For step 2: Question list with hierarchical filters

        $questions = null;

        $questionUsageCounts = [];

        if ($this->currentStep === 2) {

            $query = Question::with(['content', 'topic.chapter.subject.grade.educationLevel', 'chapter.subject.grade.educationLevel']);


            // Apply hierarchical filters

            if ($this->filterTopic) {

                $query->where('cc_topic_id', $this->filterTopic);

            } elseif ($this->filterChapter) {

                $query->where(function ($q) {

                    $q->where('cc_chapter_id', $this->filterChapter)
                        ->orWhereHas('topic', function ($topicQuery) {
                            $topicQuery->where('cc_chapter_id', $this->filterChapter);
                        });

                });

            } elseif ($this->filterSubject) {

                $query->where(function ($q) {

                    $q->where('subject_id', $this->filterSubject)
                        ->orWhereHas('topic.chapter', function ($chapterQuery) {
                            $chapterQuery->where('cc_subject_id', $this->filterSubject);
                        })
                        ->orWhereHas('chapter', function ($chapterQuery) {
                            $chapterQuery->where('cc_subject_id', $this->filterSubject);
                        });

                });

            } elseif ($this->filterGrade) {

                $query->where(function ($q) {

                    $q->whereHas('topic.chapter.subject', function ($subjectQuery) {
                        $subjectQuery->where('cc_grade_id', $this->filterGrade);
                    })->orWhereHas('chapter.subject', function ($subjectQuery) {
                        $subjectQuery->where('cc_grade_id', $this->filterGrade);
                    });

                });

            } elseif ($this->filterEducationLevel) {

                $query->where(function ($q) {

                    $q->whereHas('topic.chapter.subject.grade', function ($gradeQuery) {
                        $gradeQuery->where('education_level_id', $this->filterEducationLevel);
                    })->orWhereHas('chapter.subject.grade', function ($gradeQuery) {
                        $gradeQuery->where('education_level_id', $this->filterEducationLevel);
                    });

                });

            }


            if ($this->filterDifficulty) {

                $query->where('difficulty', $this->filterDifficulty);

            }

            if ($this->filterCode) {

                $query->where('code', 'like', "%{$this->filterCode}%");

            }


            $query->orderBy('created_at', $this->sortOrder);

            $questions = $query->paginate(10);


            // تعداد دفعاتی که هر سوال قبلاً در آزمون‌های خودِ همین مشاور استفاده شده
            // (آزمونی که در حال ویرایش آن هستیم از شمارش حذف می‌شود)

            $adminId = Auth::guard('admin')->id();

            $questionIds = $questions->pluck('id');

            if ($questionIds->isNotEmpty()) {

                $questionUsageCounts = DB::table('typed_exam_questions')
                    ->join('typed_exams', 'typed_exams.id', '=', 'typed_exam_questions.typed_exam_id')
                    ->where('typed_exams.admin_id', $adminId)
                    ->whereNull('typed_exams.deleted_at')
                    ->when($this->examId, function ($q) {
                        $q->where('typed_exams.id', '!=', $this->examId);
                    })
                    ->whereIn('typed_exam_questions.question_id', $questionIds)
                    ->select('typed_exam_questions.question_id', DB::raw('COUNT(DISTINCT typed_exams.id) as usage_count'))
                    ->groupBy('typed_exam_questions.question_id')
                    ->pluck('usage_count', 'question_id')
                    ->toArray();

            }

        }


        return view('livewire.admin.typed-exam.exam-wizard', compact(

            'examPeriods',

            'examDifficulties',

            'questionDifficulties',

            'showFieldFilter',

            'showRandomFieldFilter',

            'questions',

            'questionUsageCounts'

        ))->layout('layouts.admin.app');

    }

}
