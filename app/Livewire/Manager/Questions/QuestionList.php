<?php


namespace App\Livewire\Manager\Questions;


use App\Models\CcChapter;

use App\Models\CcField;

use App\Models\CcGrade;

use App\Models\CcSubject;

use App\Models\CcTopic;

use App\Models\EducationLevel;

use App\Models\Question;

use Barryvdh\DomPDF\Facade\Pdf;

use Illuminate\Support\Facades\Storage;

use Livewire\Component;

use Livewire\WithPagination;


class QuestionList extends Component

{

    use WithPagination;


    // Main Filters

    public string $filterCode = '';

    public string $filterDifficulty = '';


    // Hierarchical Filters

    public string $filterEducationLevel = '';

    public string $filterGrade = '';

    public string $filterField = '';

    public string $filterSubject = '';

    public string $filterChapter = '';

    public string $filterTopic = '';


    // Filter Data

    public $educationLevels = [];

    public $grades = [];

    public $fields = [];

    public $subjects = [];

    public $chapters = [];

    public $topics = [];


    // For accordion state

    public array $expandedQuestions = [];


    // PDF Export Modal

    public bool $showPdfModal = false;

    public string $pdfEducationLevel = '';

    public string $pdfGrade = '';

    public string $pdfField = '';

    public string $pdfSubject = '';

    public string $pdfChapter = '';

    public string $pdfTopic = '';

    public string $pdfDifficulty = '';

    public bool $pdfExplanationAtEnd = true;

    public bool $pdfSeparateAnswer = false;


    // PDF Filter Data

    public $pdfGrades = [];

    public $pdfFields = [];

    public $pdfSubjects = [];

    public $pdfChapters = [];

    public $pdfTopics = [];


    protected $queryString = [

        'filterEducationLevel' => ['except' => ''],

        'filterGrade' => ['except' => ''],

        'filterField' => ['except' => ''],

        'filterSubject' => ['except' => ''],

        'filterChapter' => ['except' => ''],

        'filterTopic' => ['except' => ''],

        'filterDifficulty' => ['except' => ''],

        'filterCode' => ['except' => ''],

    ];


    public function mount(): void

    {

        $this->educationLevels = EducationLevel::where('is_active', true)->orderBy('order')->get();

    }


    // Main filter cascade

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


    // PDF Modal filter cascade

    public function updatedPdfEducationLevel($value): void

    {

        $this->reset(['pdfGrade', 'pdfField', 'pdfSubject', 'pdfChapter', 'pdfTopic']);

        $this->pdfGrades = [];

        $this->pdfFields = [];

        $this->pdfSubjects = [];

        $this->pdfChapters = [];

        $this->pdfTopics = [];


        if ($value) {

            $this->pdfGrades = CcGrade::where('education_level_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();

        }

    }


    public function updatedPdfGrade($value): void

    {

        $this->reset(['pdfField', 'pdfSubject', 'pdfChapter', 'pdfTopic']);

        $this->pdfFields = [];

        $this->pdfSubjects = [];

        $this->pdfChapters = [];

        $this->pdfTopics = [];


        if ($value) {

            $grade = CcGrade::find($value);

            if ($grade && $grade->grade_number >= 10) {

                $this->pdfFields = CcField::where('is_active', true)->orderBy('order')->get();

            } else {

                $this->pdfSubjects = CcSubject::where('cc_grade_id', $value)->orderBy('order')->get();

            }

        }

    }


    public function updatedPdfField($value): void

    {

        $this->reset(['pdfSubject', 'pdfChapter', 'pdfTopic']);

        $this->pdfSubjects = [];

        $this->pdfChapters = [];

        $this->pdfTopics = [];


        if ($value && $this->pdfGrade) {

            $this->pdfSubjects = CcSubject::where('cc_grade_id', $this->pdfGrade)
                ->where(function ($q) use ($value) {

                    $q->where('cc_field_id', $value)->orWhereNull('cc_field_id');

                })
                ->orderBy('order')
                ->get();

        }

    }


    public function updatedPdfSubject($value): void

    {

        $this->reset(['pdfChapter', 'pdfTopic']);

        $this->pdfChapters = [];

        $this->pdfTopics = [];


        if ($value) {

            $this->pdfChapters = CcChapter::where('cc_subject_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();

        }

    }


    public function updatedPdfChapter($value): void

    {

        $this->reset(['pdfTopic']);

        $this->pdfTopics = [];


        if ($value) {

            $this->pdfTopics = CcTopic::where('cc_chapter_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();

        }

    }


    public function toggleExpand(int $questionId): void

    {

        if (in_array($questionId, $this->expandedQuestions)) {

            $this->expandedQuestions = array_diff($this->expandedQuestions, [$questionId]);

        } else {

            $this->expandedQuestions[] = $questionId;

        }

    }


    public function deleteQuestion(int $questionId): void

    {

        $question = Question::with('typedExams')->find($questionId);


        if (!$question) {

            $this->dispatch('error', 'سوال یافت نشد.');

            return;

        }


        // Check if question is used in any exam

        if ($question->typedExams()->count() > 0) {

            $this->dispatch('error', 'این سوال در آزمون‌هایی استفاده شده است و قابل حذف نیست.');

            return;

        }


        $question->delete();

        $this->dispatch('success', 'سوال با موفقیت حذف شد.');

    }


    public function clearFilters(): void

    {

        $this->reset([

            'filterEducationLevel', 'filterGrade', 'filterField', 'filterSubject',

            'filterChapter', 'filterTopic', 'filterDifficulty', 'filterCode'

        ]);

        $this->grades = [];

        $this->fields = [];

        $this->subjects = [];

        $this->chapters = [];

        $this->topics = [];

        $this->resetPage();

    }


    public function openPdfModal(): void

    {

        $this->showPdfModal = true;

    }


    public function closePdfModal(): void

    {

        $this->showPdfModal = false;

    }


    public function generatePdf()

    {

        // Validate at least subject is selected

        if (!$this->pdfSubject) {

            $this->dispatch('error', 'انتخاب حداقل درس الزامی است.');

            return;

        }


        $query = Question::with(['content', 'topic.chapter.subject'])
            ->whereHas('topic.chapter.subject', function ($q) {

                $q->where('id', $this->pdfSubject);

            });


        if ($this->pdfChapter) {

            $query->whereHas('topic.chapter', function ($q) {

                $q->where('id', $this->pdfChapter);

            });

        }


        if ($this->pdfTopic) {

            $query->where('cc_topic_id', $this->pdfTopic);

        }


        if ($this->pdfDifficulty) {

            $query->where('difficulty', $this->pdfDifficulty);

        }


        $questions = $query->get();


        if ($questions->isEmpty()) {

            $this->dispatch('error', 'سوالی با این فیلترها یافت نشد.');

            return;

        }


        // Get subject info for PDF header

        $subject = CcSubject::with(['grade.educationLevel', 'field'])->find($this->pdfSubject);


        $data = [

            'questions' => $questions,

            'subject' => $subject,

            'explanationAtEnd' => $this->pdfExplanationAtEnd,

            'separateAnswer' => $this->pdfSeparateAnswer,

        ];


        if ($this->pdfSeparateAnswer) {

            // Generate two PDFs: questions and answers

            $questionsPdf = Pdf::loadView('pdf.questions-only', $data)
                ->setPaper('a4')
                ->setOption(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);


            $answersPdf = Pdf::loadView('pdf.answers-only', $data)
                ->setPaper('a4')
                ->setOption(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);


            // Save to temp and return as zip or sequential downloads

            $filename = 'questions_' . now()->format('Y-m-d_H-i-s');


            // For simplicity, return questions PDF and dispatch event for answers

            return response()->streamDownload(function () use ($questionsPdf) {

                echo $questionsPdf->output();

            }, $filename . '_questions.pdf');

        }



        $pdf = Pdf::loadView('pdf.questions', $data)
            ->setPaper('a4')
            ->setOption(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);


        $filename = 'questions_' . now()->format('Y-m-d_H-i-s') . '.pdf';


        $this->closePdfModal();


        return response()->streamDownload(function () use ($pdf) {

            echo $pdf->output();

        }, $filename);

    }


    public function render()

    {

        $query = Question::with(['content', 'topic.chapter.subject.grade.educationLevel'])
            ->latest();


        // Apply hierarchical filters

        if ($this->filterTopic) {

            $query->where('cc_topic_id', $this->filterTopic);

        } elseif ($this->filterChapter) {

            $query->whereHas('topic', function ($q) {

                $q->where('cc_chapter_id', $this->filterChapter);

            });

        } elseif ($this->filterSubject) {

            $query->whereHas('topic.chapter', function ($q) {

                $q->where('cc_subject_id', $this->filterSubject);

            });

        } elseif ($this->filterGrade) {

            $query->whereHas('topic.chapter.subject', function ($q) {

                $q->where('cc_grade_id', $this->filterGrade);

            });

        } elseif ($this->filterEducationLevel) {

            $query->whereHas('topic.chapter.subject.grade', function ($q) {

                $q->where('education_level_id', $this->filterEducationLevel);

            });

        }


        if ($this->filterDifficulty) {

            $query->where('difficulty', $this->filterDifficulty);

        }


        if ($this->filterCode) {

            $query->where('code', 'like', "%{$this->filterCode}%");

        }


        $questions = $query->paginate(10);


        $difficulties = [

            'easy' => 'آسان',

            'medium' => 'متوسط',

            'hard' => 'سخت',

            'special' => 'ویژه',

        ];


        // Check if field select should be shown

        $showFieldFilter = false;

        if ($this->filterGrade) {

            $grade = CcGrade::find($this->filterGrade);

            $showFieldFilter = $grade && $grade->grade_number >= 10;

        }


        $showPdfFieldFilter = false;

        if ($this->pdfGrade) {

            $grade = CcGrade::find($this->pdfGrade);

            $showPdfFieldFilter = $grade && $grade->grade_number >= 10;

        }


        return view('livewire.manager.questions.question-list', compact(

            'questions', 'difficulties', 'showFieldFilter', 'showPdfFieldFilter'

        ))->layout('layouts.manager.app');

    }

}
