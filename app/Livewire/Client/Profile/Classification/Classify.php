<?php
namespace App\Livewire\Client\Profile\Classification;

use App\Models\CcChapter;
use App\Models\CcField;
use App\Models\CcGrade;
use App\Models\CcSubject;
use App\Models\ClassificationProject;
use App\Models\PersonalInformation;
use App\Models\StudentClassification;
use App\Models\StudentClassificationSubmission;
use App\Models\TrialWeek;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class Classify extends Component
{
    use SEOTools;

    public ClassificationProject $project;
    public $selectedGrade;
    public $studentGrade;
    public $studentField;
    public $studentFieldSlug;

    public $availableTags = [];
    public $activeTag = null;

    /**
     * Each entry: ['type'=>'specialized'|'general', 'subject'=>['id','name'], 'chapters'=>[...]]
     * For 'specialized': chapters list is filled.
     * For 'general': chapters list is empty — rating is on subject itself.
     */
    public $subjects = [];

    /**
     * Keyed map: "chapter_{id}" => 1..4 or "subject_{id}" => 1..4
     */
    public $ratings = [];

    public $showSubmitModal = false;
    public $showMyTopics = false;

    public $totalTopics = 0;
    public $completedTopics = 0;

    public bool $isTrial = false;

    public function mount(ClassificationProject $project, $grade)
    {
        $this->project = $project;
        $this->selectedGrade = (int) $grade;
        $this->loadStudentInfo();
        $this->loadAvailableTags();
        $this->loadExistingRatings();
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()->setTitle('طبقه‌بندی مباحث - ' . $this->project->name);
    }

    protected function loadStudentInfo()
    {
        $userId = auth()->id();
        $personalInfo = PersonalInformation::where('user_id', $userId)->first();
        if ($personalInfo) {
            $this->studentGrade = (int) $personalInfo->grade;
            $this->studentField = $personalInfo->field;
        } else {
            $trial = TrialWeek::where('user_id', $userId)->latest()->first();
            if ($trial) {
                $this->isTrial = true;
                $this->studentGrade = $trial->grade >= 10 ? $trial->grade : 10;
                $this->studentField = $trial->field;
            }
        }

        $fieldMapping = ['math' => 'math', 'experimental' => 'experimental', 'human' => 'human'];
        $this->studentFieldSlug = $fieldMapping[$this->studentField] ?? null;
    }

    protected function loadAvailableTags()
    {
        // Trial project: synthetic settings — only student's own grade, both specialized and general.
        if ($this->project->is_trial) {
            $gradeNames = [10 => 'دهم', 11 => 'یازدهم', 12 => 'دوازدهم'];
            $g = $this->studentGrade ?: 10;
            $this->availableTags = [
                ['id' => $g . '_specialized', 'grade' => $g, 'type' => 'specialized', 'label' => ($gradeNames[$g] ?? $g) . ' (تخصصی)'],
                ['id' => $g . '_general',     'grade' => $g, 'type' => 'general',     'label' => ($gradeNames[$g] ?? $g) . ' (عمومی)'],
            ];
            $this->activeTag = $this->availableTags[0]['id'];
            $this->loadSubjects();
            return;
        }

        $settings = $this->project->gradeSettings()
            ->where('student_grade', $this->studentGrade)
            ->get();
        $gradeNames = [10 => 'دهم', 11 => 'یازدهم', 12 => 'دوازدهم'];

        foreach ($settings as $setting) {
            $this->availableTags[] = [
                'id'    => $setting->target_grade . '_specialized',
                'grade' => $setting->target_grade,
                'type'  => 'specialized',
                'label' => ($gradeNames[$setting->target_grade] ?? $setting->target_grade) . ' (تخصصی)',
            ];
            if ($setting->has_general) {
                $this->availableTags[] = [
                    'id'    => $setting->target_grade . '_general',
                    'grade' => $setting->target_grade,
                    'type'  => 'general',
                    'label' => ($gradeNames[$setting->target_grade] ?? $setting->target_grade) . ' (عمومی)',
                ];
            }
        }

        if (!empty($this->availableTags)) {
            $matchingTag = collect($this->availableTags)->first(function ($tag) {
                return (int) $tag['grade'] === $this->selectedGrade;
            });
            $this->activeTag = $matchingTag['id'] ?? $this->availableTags[0]['id'];
            $this->loadSubjects();
        }
    }

    protected function loadExistingRatings()
    {
        $rows = StudentClassification::where('user_id', auth()->id())
            ->where('classification_project_id', $this->project->id)
            ->get(['ratable_type', 'ratable_id', 'rating']);

        $map = [];
        foreach ($rows as $r) {
            $key = ($r->ratable_type === CcChapter::class ? 'chapter_' : 'subject_') . $r->ratable_id;
            $map[$key] = (int) $r->rating;
        }
        $this->ratings = $map;
        $this->completedTopics = count($map);
        $this->calculateTotalTopics();
    }

    protected function calculateTotalTopics()
    {
        $total = 0;
        $studentFieldId = $this->studentFieldSlug
            ? CcField::where('slug', $this->studentFieldSlug)->value('id')
            : null;

        $settings = $this->getEffectiveSettings();

        foreach ($settings as $setting) {
            $ccGrade = CcGrade::where('grade_number', $setting['target_grade'])->first();
            if (!$ccGrade) continue;

            // Specialized: count chapters
            $specSubjects = CcSubject::where('cc_grade_id', $ccGrade->id)
                ->where('type', 'specialized')
                ->where(function ($q) use ($studentFieldId) {
                    $q->where('cc_field_id', $studentFieldId)->orWhereNull('cc_field_id');
                })
                ->with(['chapters' => fn ($q) => $q->where('is_active', true)])
                ->get();
            foreach ($specSubjects as $subj) {
                $total += $subj->chapters->count();
            }

            // General: count subjects (only if has_general)
            if (!empty($setting['has_general'])) {
                $total += CcSubject::where('cc_grade_id', $ccGrade->id)
                    ->where('type', 'general')
                    ->count();
            }
        }
        $this->totalTopics = $total;
    }

    protected function getEffectiveSettings(): array
    {
        if ($this->project->is_trial) {
            return [[
                'target_grade' => $this->studentGrade ?: 10,
                'has_general'  => true,
            ]];
        }
        return $this->project->gradeSettings()
            ->where('student_grade', $this->studentGrade)
            ->get()
            ->map(fn ($s) => ['target_grade' => $s->target_grade, 'has_general' => (bool) $s->has_general])
            ->toArray();
    }

    public function selectTag($tagId)
    {
        $this->activeTag = $tagId;
        $this->showMyTopics = false;
        $this->loadSubjects();
    }

    public function showMyRatings()
    {
        $this->showMyTopics = true;
        $this->activeTag = null;
    }

    protected function loadSubjects()
    {
        if (!$this->activeTag) {
            $this->subjects = [];
            return;
        }

        $parts = explode('_', $this->activeTag);
        $gradeNumber = (int) $parts[0];
        $type = $parts[1];

        $ccGrade = CcGrade::where('grade_number', $gradeNumber)->first();
        if (!$ccGrade) {
            $this->subjects = [];
            return;
        }

        $studentFieldId = $this->studentFieldSlug
            ? CcField::where('slug', $this->studentFieldSlug)->value('id')
            : null;

        $query = CcSubject::where('cc_grade_id', $ccGrade->id)->where('type', $type);

        if ($type === 'specialized') {
            $query->where(function ($q) use ($studentFieldId) {
                $q->where('cc_field_id', $studentFieldId)->orWhereNull('cc_field_id');
            });
            $subjects = $query->with(['chapters' => fn ($q) => $q->active()->ordered()])
                ->ordered()->get();
            $this->subjects = $subjects->map(function ($s) {
                return [
                    'id'   => $s->id,
                    'name' => $s->name,
                    'type' => 'specialized',
                    'chapters' => $s->chapters->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])->values()->toArray(),
                ];
            })->toArray();
        } else {
            // general: subject-level rating, no chapters shown
            $subjects = $query->ordered()->get();
            $this->subjects = $subjects->map(function ($s) {
                return [
                    'id'   => $s->id,
                    'name' => $s->name,
                    'type' => 'general',
                    'chapters' => [],
                ];
            })->toArray();
        }
    }

    public function setRating($id, $rating, $kind)
    {
        $rating = max(1, min(4, (int) $rating));
        $id = (int) $id;
        $type = $kind === 'chapter' ? CcChapter::class : CcSubject::class;

        StudentClassification::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'classification_project_id' => $this->project->id,
                'ratable_type' => $type,
                'ratable_id'   => $id,
            ],
            ['rating' => $rating]
        );

        $this->ratings[($kind === 'chapter' ? 'chapter_' : 'subject_') . $id] = $rating;
        $this->completedTopics = count($this->ratings);
    }

    public function clearRating($id, $kind)
    {
        $id = (int) $id;
        $type = $kind === 'chapter' ? CcChapter::class : CcSubject::class;

        StudentClassification::where('user_id', auth()->id())
            ->where('classification_project_id', $this->project->id)
            ->where('ratable_type', $type)
            ->where('ratable_id', $id)
            ->delete();

        unset($this->ratings[($kind === 'chapter' ? 'chapter_' : 'subject_') . $id]);
        $this->completedTopics = count($this->ratings);
    }

    public function openSubmitModal()
    {
        $this->showSubmitModal = true;
    }

    public function submitClassification()
    {
        StudentClassificationSubmission::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'classification_project_id' => $this->project->id,
            ],
            [
                'is_completed' => true,
                'submitted_at' => now(),
            ]
        );

        $this->showSubmitModal = false;
        $this->dispatch('success', 'طبقه‌بندی شما با موفقیت ثبت شد!');
        return redirect()->route('client.profile.classification.projects');
    }

    public function getRatingLabel($rating)
    {
        return StudentClassification::RATINGS[$rating] ?? '';
    }

    public function render()
    {
        $myRated = collect();
        if ($this->showMyTopics) {
            $rows = StudentClassification::where('user_id', auth()->id())
                ->where('classification_project_id', $this->project->id)
                ->with('ratable')
                ->get();

            foreach ($rows as $r) {
                $ratable = $r->ratable;
                if (!$ratable) continue;
                if ($r->ratable_type === CcChapter::class) {
                    $subject = $ratable->subject;
                    $subjectName = $subject?->name ?? 'سایر';
                    $itemName = $ratable->name;
                    $context = 'فصل';
                } else {
                    $subjectName = 'دروس عمومی';
                    $itemName = $ratable->name;
                    $context = 'درس عمومی';
                }
                $myRated->push((object) [
                    'subjectName' => $subjectName,
                    'itemName'    => $itemName,
                    'context'     => $context,
                    'rating'      => $r->rating,
                ]);
            }
            $myRated = $myRated->groupBy('subjectName');
        }

        return view('livewire.client.profile.classification.classify', [
            'myRated' => $myRated,
        ])->layout('layouts.client.app');
    }
}
