<?php


namespace App\Livewire\Manager\Questions;
use App\Models\CcChapter;
use App\Models\CcField;
use App\Models\CcGrade;
use App\Models\CcSubject;
use App\Models\CcTopic;
use App\Models\EducationLevel;
use App\Models\Question;
use App\Models\QuestionContent;
use App\Traits\ResolvesLegacySubject;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithPagination;

class QuestionList extends Component
{

    use WithPagination, ResolvesLegacySubject;
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
    // اصلاحات سوالات: لینک‌کردن سوالات تک‌مقصدی موجود به یک مقصد دوم مشترک،
    // با استفاده از همان عکس‌های فعلی (بدون آپلود/کپی دوباره)
    public bool $correctionMode = false;
    public array $selectedQuestionIds = [];
    public string $correctionEducationLevelId = '';
    public string $correctionGradeId = '';
    public string $correctionFieldId = '';
    public string $correctionSubjectId = '';
    public string $correctionChapterId = '';
    public string $correctionTopicId = '';
    public bool $correctionIsComprehensive = false;
    public $correctionGrades = [];
    public $correctionFields = [];
    public $correctionSubjects = [];
    public $correctionChapters = [];
    public $correctionTopics = [];
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
    // ==================== اصلاحات سوالات (مقصد دوم) ====================
    public function toggleCorrectionMode(): void
    {
        $this->correctionMode = !$this->correctionMode;
        $this->selectedQuestionIds = [];
        $this->resetCorrectionTarget();
    }

    public function updatedCorrectionEducationLevelId($value): void
    {
        $this->reset(['correctionGradeId', 'correctionFieldId', 'correctionSubjectId', 'correctionChapterId', 'correctionTopicId', 'correctionIsComprehensive']);
        $this->correctionGrades = [];
        $this->correctionFields = [];
        $this->correctionSubjects = [];
        $this->correctionChapters = [];
        $this->correctionTopics = [];

        if ($value) {
            $needsField = CcGrade::where('education_level_id', $value)
                ->where('is_active', true)
                ->where('grade_number', '>=', 10)
                ->exists();
            if ($needsField) {
                $this->correctionFields = CcField::where('is_active', true)->orderBy('order')->get();
            } else {
                $this->correctionGrades = CcGrade::where('education_level_id', $value)
                    ->where('is_active', true)
                    ->where(function ($query) {
                        $query->whereNull('cc_field_id')->orWhere('grade_number', '<', 10);
                    })
                    ->orderBy('order')
                    ->get();
            }
        }
    }

    public function updatedCorrectionFieldId($value): void
    {
        $this->reset(['correctionGradeId', 'correctionSubjectId', 'correctionChapterId', 'correctionTopicId', 'correctionIsComprehensive']);
        $this->correctionGrades = [];
        $this->correctionSubjects = [];
        $this->correctionChapters = [];
        $this->correctionTopics = [];

        if ($value && $this->correctionEducationLevelId) {
            $this->correctionGrades = CcGrade::where('education_level_id', $this->correctionEducationLevelId)
                ->where('cc_field_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
        }
    }

    public function updatedCorrectionGradeId($value): void
    {
        $this->reset(['correctionSubjectId', 'correctionChapterId', 'correctionTopicId', 'correctionIsComprehensive']);
        $this->correctionSubjects = [];
        $this->correctionChapters = [];
        $this->correctionTopics = [];

        if ($value) {
            $grade = CcGrade::find($value);
            if ($grade) {
                $this->correctionSubjects = CcSubject::where('cc_grade_id', $value)
                    ->when($grade->cc_field_id, function ($query) use ($grade) {
                        $query->where(function ($q) use ($grade) {
                            $q->where('cc_field_id', $grade->cc_field_id)->orWhereNull('cc_field_id');
                        });
                    })
                    ->orderBy('order')
                    ->get();
            }
        }
    }

    public function updatedCorrectionSubjectId($value): void
    {
        $this->reset(['correctionChapterId', 'correctionTopicId', 'correctionIsComprehensive']);
        $this->correctionChapters = [];
        $this->correctionTopics = [];

        if ($value) {
            $this->correctionChapters = CcChapter::where('cc_subject_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
        }
    }

    public function updatedCorrectionChapterId($value): void
    {
        $this->reset(['correctionTopicId']);
        $this->correctionTopics = [];

        if ($value) {
            $this->correctionTopics = CcTopic::where('cc_chapter_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
        }
    }

    public function updatedCorrectionTopicId($value): void
    {
        if ($value) {
            $this->correctionIsComprehensive = false;
        }
    }

    public function setCorrectionComprehensiveMode(): void
    {
        $this->correctionIsComprehensive = true;
        $this->correctionTopicId = '';
    }

    public function setCorrectionTopicMode(): void
    {
        $this->correctionIsComprehensive = false;
    }

    protected function resetCorrectionTarget(): void
    {
        $this->correctionEducationLevelId = '';
        $this->correctionGradeId = '';
        $this->correctionFieldId = '';
        $this->correctionSubjectId = '';
        $this->correctionChapterId = '';
        $this->correctionTopicId = '';
        $this->correctionIsComprehensive = false;
        $this->correctionGrades = [];
        $this->correctionFields = [];
        $this->correctionSubjects = [];
        $this->correctionChapters = [];
        $this->correctionTopics = [];
    }

    /**
     * لینک‌کردن سوالات انتخاب‌شده به یک مقصد دوم مشترک، با استفاده از همان
     * عکس‌های موجود (بدون آپلود/کپی دوباره). برای سوالاتی که قبلا (اشتباها)
     * فقط برای یک رشته/مبحث ثبت شده‌اند ولی محتوایشان با رشته دیگر مشترک است.
     */
    public function applyQuestionCorrection(): void
    {
        if (empty($this->selectedQuestionIds)) {
            $this->dispatch('error', 'حداقل یک سوال را انتخاب کنید.');
            return;
        }
        if (!$this->correctionSubjectId || !$this->correctionChapterId) {
            $this->dispatch('error', 'انتخاب درس و فصل مقصد الزامی است.');
            return;
        }
        if (!$this->correctionIsComprehensive && !$this->correctionTopicId) {
            $this->dispatch('error', 'انتخاب مبحث مقصد الزامی است (یا حالت سوالات جامع را انتخاب کنید).');
            return;
        }

        $targetChapterId = (int) $this->correctionChapterId;
        $targetTopicId = $this->correctionIsComprehensive ? null : (int) $this->correctionTopicId;
        $targetSubjectId = $this->resolveLegacySubjectId((int) $this->correctionSubjectId);

        $created = 0;
        $skipped = 0;

        DB::transaction(function () use (&$created, &$skipped, $targetChapterId, $targetTopicId, $targetSubjectId) {
            $questions = Question::with('content')->whereIn('id', $this->selectedQuestionIds)->get();

            foreach ($questions as $question) {
                $content = $question->content;
                if (!$content || !$content->question_image || !$content->folder_hash) {
                    $skipped++;
                    continue;
                }

                // همین حالا هم به همین مقصد اشاره می‌کند
                if ((int) $question->cc_chapter_id === $targetChapterId
                    && (int) ($question->cc_topic_id ?? 0) === (int) ($targetTopicId ?? 0)) {
                    $skipped++;
                    continue;
                }

                // قبلا (توسط همین ابزار یا آپلود دو درسه) به این مقصد لینک شده
                $alreadyLinked = Question::where('cc_chapter_id', $targetChapterId)
                    ->where('cc_topic_id', $targetTopicId)
                    ->whereHas('content', function ($q) use ($content) {
                        $q->where('folder_hash', $content->folder_hash);
                    })
                    ->exists();
                if ($alreadyLinked) {
                    $skipped++;
                    continue;
                }

                $newQuestion = Question::create([
                    'code' => Question::generateUniqueCode(),
                    'subject_id' => $targetSubjectId,
                    'cc_chapter_id' => $targetChapterId,
                    'cc_topic_id' => $targetTopicId,
                    'difficulty' => $question->difficulty,
                    'correct_option' => $question->correct_option,
                ]);
                $this->synchronizeOptionKeys($newQuestion, (int) $question->correct_option_number);

                QuestionContent::create([
                    'question_id' => $newQuestion->id,
                    'question_image' => $content->question_image,
                    'folder_hash' => $content->folder_hash,
                    'explanation_image' => $content->explanation_image,
                    'body' => '',
                    'explanation' => '',
                ]);

                $created++;
            }
        });

        if ($created > 0) {
            $message = "{$created} سوال با موفقیت به مقصد دوم لینک شد.";
            if ($skipped > 0) {
                $message .= " ({$skipped} مورد رد شد چون عکس نداشت یا از قبل لینک شده بود.)";
            }
            $this->dispatch('success', $message);
        } else {
            $this->dispatch('error', 'هیچ سوالی لینک نشد؛ همه موارد انتخاب‌شده یا از قبل لینک شده بودند یا عکس نداشتند.');
        }

        $this->selectedQuestionIds = [];
        $this->resetCorrectionTarget();
    }

    protected function synchronizeOptionKeys(Question $question, int $correctOption): void
    {
        $question->options()->update(['is_correct' => false]);
        $question->options()
            ->where('option_number', $correctOption)
            ->update(['is_correct' => true]);
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
        $query = Question::with(['content', 'topic.chapter.subject', 'chapter.subject'])
            ->where(function ($q) {
                $q->where('subject_id', $this->pdfSubject)
                    ->orWhereHas('topic.chapter.subject', function ($subjectQuery) {
                        $subjectQuery->where('id', $this->pdfSubject);
                    })
                    ->orWhereHas('chapter.subject', function ($subjectQuery) {
                        $subjectQuery->where('id', $this->pdfSubject);
                    });
            });
        if ($this->pdfChapter) {
            $query->where(function ($q) {
                $q->where('cc_chapter_id', $this->pdfChapter)
                    ->orWhereHas('topic.chapter', function ($chapterQuery) {
                        $chapterQuery->where('id', $this->pdfChapter);
                    });
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
                ->setPaper('a4')->setOption(['isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true]);

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
        $query = Question::with([
            'content',
            'topic.chapter.subject.grade.educationLevel',
            'chapter.subject.grade.educationLevel',
        ])
            ->latest();
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
        $questions = $query->paginate(10);
        $difficulties = [
            'easy' => 'آسان',
            'medium' => 'متوسط',
            'hard' => 'سخت',
            'special' => 'ویژه',
            'combined' => 'ترکیبی',
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
        $showCorrectionFieldFilter = false;
        if ($this->correctionEducationLevelId) {
            $showCorrectionFieldFilter = CcGrade::where('education_level_id', $this->correctionEducationLevelId)
                ->where('is_active', true)
                ->where('grade_number', '>=', 10)
                ->exists();
        }
        // برای نشان‌دادن اینکه یک سوال از قبل با سوال دیگری عکس مشترک دارد (لینک شده)
        $linkedFolderHashes = [];
        foreach ($questions as $question) {
            $hash = $question->content->folder_hash ?? null;
            if ($hash && Question::whereHas('content', fn ($q) => $q->where('folder_hash', $hash))->count() > 1) {
                $linkedFolderHashes[] = $hash;
            }
        }

        return view('livewire.manager.questions.question-list', compact(
            'questions', 'difficulties', 'showFieldFilter', 'showPdfFieldFilter',
            'showCorrectionFieldFilter', 'linkedFolderHashes'
        ))->layout('layouts.manager.app');
    }
}
