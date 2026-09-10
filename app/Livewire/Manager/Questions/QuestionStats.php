<?php

namespace App\Livewire\Manager\Questions;

use App\Models\CcChapter;
use App\Models\CcField;
use App\Models\CcGrade;
use App\Models\CcSubject;
use App\Models\CcTopic;
use App\Models\EducationLevel;
use App\Models\Question;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * آمار سوالات: تعداد سوالات هر فصل/مبحث، پیدا کردن فصل‌ها/مباحث بدون سوال،
 * و مرتب‌سازی بر اساس کمترین/بیشترین تعداد سوال.
 */
class QuestionStats extends Component
{
    use WithPagination;

    // فیلترهای اختیاری برای محدودکردن دامنه (در همه حالت‌ها قابل استفاده‌اند)
    public string $filterEducationLevel = '';
    public string $filterGrade = '';
    public string $filterField = '';
    public string $filterSubject = '';

    // all | chapters_empty | topics_empty | fewest | most
    public string $viewMode = 'all';

    public $educationLevels = [];
    public $grades = [];
    public $fields = [];
    public $subjects = [];

    protected $queryString = [
        'filterEducationLevel' => ['except' => ''],
        'filterGrade' => ['except' => ''],
        'filterField' => ['except' => ''],
        'filterSubject' => ['except' => ''],
        'viewMode' => ['except' => 'all'],
    ];

    public function mount(): void
    {
        $this->educationLevels = EducationLevel::where('is_active', true)->orderBy('order')->get();
    }

    public function updatedFilterEducationLevel($value): void
    {
        $this->reset(['filterGrade', 'filterField', 'filterSubject']);
        $this->grades = [];
        $this->fields = [];
        $this->subjects = [];
        $this->resetPage();

        if ($value) {
            $needsField = CcGrade::where('education_level_id', $value)
                ->where('is_active', true)
                ->where('grade_number', '>=', 10)
                ->exists();
            if ($needsField) {
                $this->fields = CcField::where('is_active', true)->orderBy('order')->get();
            } else {
                $this->grades = CcGrade::where('education_level_id', $value)
                    ->where('is_active', true)
                    ->where(function ($query) {
                        $query->whereNull('cc_field_id')->orWhere('grade_number', '<', 10);
                    })
                    ->orderBy('order')
                    ->get();
            }
        }
    }

    public function updatedFilterField($value): void
    {
        $this->reset(['filterGrade', 'filterSubject']);
        $this->grades = [];
        $this->subjects = [];
        $this->resetPage();

        if ($value && $this->filterEducationLevel) {
            $this->grades = CcGrade::where('education_level_id', $this->filterEducationLevel)
                ->where('cc_field_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
        }
    }

    public function updatedFilterGrade($value): void
    {
        $this->reset(['filterSubject']);
        $this->subjects = [];
        $this->resetPage();

        if ($value) {
            $grade = CcGrade::find($value);
            if ($grade) {
                $this->subjects = CcSubject::where('cc_grade_id', $value)
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

    public function updatedFilterSubject(): void
    {
        $this->resetPage();
    }

    public function updatedViewMode(): void
    {
        $this->resetPage();
    }

    public function clearFilters(): void
    {
        $this->reset(['filterEducationLevel', 'filterGrade', 'filterField', 'filterSubject']);
        $this->grades = [];
        $this->fields = [];
        $this->subjects = [];
        $this->resetPage();
    }

    /**
     * فصل‌های فعالِ داخل دامنه فیلترهای فعلی، همراه با مباحثشان.
     */
    protected function chaptersInScope()
    {
        return CcChapter::query()
            ->where('is_active', true)
            ->with([
                'topics' => fn ($q) => $q->where('is_active', true)->orderBy('order'),
                'subject.grade.educationLevel',
                'subject.field',
            ])
            ->when($this->filterSubject, function ($q) {
                $q->where('cc_subject_id', $this->filterSubject);
            })
            ->when(!$this->filterSubject && $this->filterGrade, function ($q) {
                $q->whereHas('subject', function ($sq) {
                    $sq->where('cc_grade_id', $this->filterGrade);
                    if ($this->filterField) {
                        $sq->where(function ($fq) {
                            $fq->where('cc_field_id', $this->filterField)->orWhereNull('cc_field_id');
                        });
                    }
                });
            })
            ->when(!$this->filterGrade && $this->filterField, function ($q) {
                $q->whereHas('subject', function ($sq) {
                    $sq->where('cc_field_id', $this->filterField);
                });
            })
            ->when(!$this->filterGrade && !$this->filterField && $this->filterEducationLevel, function ($q) {
                $q->whereHas('subject.grade', function ($gq) {
                    $gq->where('education_level_id', $this->filterEducationLevel);
                });
            })
            ->orderBy('order')
            ->get();
    }

    public function render()
    {
        $chapters = $this->chaptersInScope();
        $chapterIds = $chapters->pluck('id');

        // سه شمارش گروهی و کارآمد به‌جای N+1 کوئری
        $chapterTotalCounts = Question::whereIn('cc_chapter_id', $chapterIds)
            ->select('cc_chapter_id', DB::raw('count(*) as cnt'))
            ->groupBy('cc_chapter_id')
            ->pluck('cnt', 'cc_chapter_id');

        $comprehensiveCounts = Question::whereIn('cc_chapter_id', $chapterIds)
            ->whereNull('cc_topic_id')
            ->select('cc_chapter_id', DB::raw('count(*) as cnt'))
            ->groupBy('cc_chapter_id')
            ->pluck('cnt', 'cc_chapter_id');

        $topicIds = $chapters->pluck('topics')->flatten()->pluck('id');
        $topicCounts = Question::whereIn('cc_topic_id', $topicIds)
            ->select('cc_topic_id', DB::raw('count(*) as cnt'))
            ->groupBy('cc_topic_id')
            ->pluck('cnt', 'cc_topic_id');

        // برای هر فصل، یک باکت «سوالات جامع» + یک باکت به‌ازای هر مبحث؛
        // این‌ها دقیقاً همان مقصدهایی هستند که در فرم ایجاد سوال قابل انتخابند.
        $buckets = collect();
        foreach ($chapters as $chapter) {
            $chapter->setAttribute('total_count', (int) ($chapterTotalCounts[$chapter->id] ?? 0));
            $chapter->setAttribute('comprehensive_count', (int) ($comprehensiveCounts[$chapter->id] ?? 0));

            $buckets->push([
                'type' => 'comprehensive',
                'count' => $chapter->comprehensive_count,
                'chapter' => $chapter,
                'topic' => null,
                'label' => 'سوالات جامع فصل',
            ]);

            foreach ($chapter->topics as $topic) {
                $topic->setAttribute('question_count', (int) ($topicCounts[$topic->id] ?? 0));
                $buckets->push([
                    'type' => 'topic',
                    'count' => $topic->question_count,
                    'chapter' => $chapter,
                    'topic' => $topic,
                    'label' => $topic->name,
                ]);
            }
        }

        $chaptersEmpty = $chapters->filter(fn ($c) => $c->total_count === 0)->values();
        $topicsEmptyBuckets = $buckets->filter(fn ($b) => $b['type'] === 'topic' && $b['count'] === 0)->values();
        $fewestBuckets = $buckets->sortBy('count')->values();
        $mostBuckets = $buckets->sortByDesc('count')->values();

        $perPage = 20;
        $page = Paginator::resolveCurrentPage();

        $listBuckets = match ($this->viewMode) {
            'topics_empty' => $topicsEmptyBuckets,
            'fewest' => $fewestBuckets,
            'most' => $mostBuckets,
            default => collect(),
        };

        $pagedBuckets = null;
        $pagedChaptersEmpty = null;

        if ($this->viewMode === 'chapters_empty') {
            $pagedChaptersEmpty = new LengthAwarePaginator(
                $chaptersEmpty->forPage($page, $perPage)->values(),
                $chaptersEmpty->count(),
                $perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        } elseif ($this->viewMode !== 'all') {
            $pagedBuckets = new LengthAwarePaginator(
                $listBuckets->forPage($page, $perPage)->values(),
                $listBuckets->count(),
                $perPage,
                $page,
                ['path' => request()->url(), 'query' => request()->query()]
            );
        }

        // نمای سلسله‌مراتبی «همه» فقط وقتی یک درس انتخاب شده معنادار است
        $showHierarchical = $this->viewMode === 'all' && (bool) $this->filterSubject;

        $showFieldFilter = false;
        if ($this->filterEducationLevel) {
            $showFieldFilter = CcGrade::where('education_level_id', $this->filterEducationLevel)
                ->where('is_active', true)
                ->where('grade_number', '>=', 10)
                ->exists();
        }

        return view('livewire.manager.questions.question-stats', [
            'chapters' => $chapters,
            'chaptersEmptyCount' => $chaptersEmpty->count(),
            'topicsEmptyCount' => $topicsEmptyBuckets->count(),
            'pagedChaptersEmpty' => $pagedChaptersEmpty,
            'pagedBuckets' => $pagedBuckets,
            'showHierarchical' => $showHierarchical,
            'showFieldFilter' => $showFieldFilter,
        ])->layout('layouts.manager.app');
    }
}
