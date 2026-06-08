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

    public $subjects = [];
    public $ratings  = [];

    public $showSubmitModal = false;

    public $totalTopics     = 0;
    public $completedTopics = 0;

    public bool $isTrial = false;

    public function mount(ClassificationProject $project, $grade)
    {
        $this->project       = $project;
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
        $userId       = auth()->id();
        $personalInfo = PersonalInformation::where('user_id', $userId)->first();
        if ($personalInfo) {
            $this->studentGrade = (int) $personalInfo->grade;
            $this->studentField = $personalInfo->field;
        } else {
            $trial = TrialWeek::where('user_id', $userId)->latest()->first();
            if ($trial) {
                $this->isTrial      = true;
                $this->studentGrade = $trial->grade >= 10 ? $trial->grade : 10;
                $this->studentField = $trial->field;
            }
        }
        $fieldMapping          = ['math' => 'math', 'experimental' => 'experimental', 'human' => 'human'];
        $this->studentFieldSlug = $fieldMapping[$this->studentField] ?? null;
    }

    protected function loadAvailableTags()
    {
        if ($this->project->is_trial) {
            $gradeNames = [10 => 'دهم', 11 => 'یازدهم', 12 => 'دوازدهم'];
            $studentG   = $this->studentGrade ?: 10;
            for ($g = 10; $g <= $studentG; $g++) {
                $this->availableTags[] = ['id' => $g . '_specialized', 'grade' => $g, 'type' => 'specialized', 'label' => ($gradeNames[$g] ?? $g) . ' (تخصصی)'];
                $this->availableTags[] = ['id' => $g . '_general',     'grade' => $g, 'type' => 'general',     'label' => ($gradeNames[$g] ?? $g) . ' (عمومی)'];
            }
            $matching        = collect($this->availableTags)->first(fn($t) => (int)$t['grade'] === $this->selectedGrade);
            $this->activeTag = $matching['id'] ?? $this->availableTags[0]['id'];
            $this->loadSubjects();
            return;
        }

        $settings   = $this->project->gradeSettings()->where('student_grade', $this->studentGrade)->get();
        $gradeNames = [10 => 'دهم', 11 => 'یازدهم', 12 => 'دوازدهم'];
        foreach ($settings as $setting) {
            $this->availableTags[] = ['id' => $setting->target_grade . '_specialized', 'grade' => $setting->target_grade, 'type' => 'specialized', 'label' => ($gradeNames[$setting->target_grade] ?? $setting->target_grade) . ' (تخصصی)'];
            if ($setting->has_general) {
                $this->availableTags[] = ['id' => $setting->target_grade . '_general', 'grade' => $setting->target_grade, 'type' => 'general', 'label' => ($gradeNames[$setting->target_grade] ?? $setting->target_grade) . ' (عمومی)'];
            }
        }
        if (!empty($this->availableTags)) {
            $matchingTag     = collect($this->availableTags)->first(fn($t) => (int)$t['grade'] === $this->selectedGrade);
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
            $key       = ($r->ratable_type === CcChapter::class ? 'chapter_' : 'subject_') . $r->ratable_id;
            $map[$key] = (int) $r->rating;
        }
        $this->ratings = $map;

        $this->calculateTotalTopics();
        $this->recalculateCompletedTopics();
    }

    protected function calculateTotalTopics()
    {
        $total          = 0;
        $studentFieldId = $this->studentFieldSlug
            ? CcField::where('slug', $this->studentFieldSlug)->value('id')
            : null;

        $settings = $this->getEffectiveSettings();

        foreach ($settings as $setting) {
            $ccGrade = CcGrade::where('grade_number', $setting['target_grade'])
                ->where('is_active', true)
                ->when($studentFieldId, fn($q) => $q->where('cc_field_id', $studentFieldId))
                ->first();

            if (!$ccGrade) {
                $ccGrade = CcGrade::where('grade_number', $setting['target_grade'])
                    ->where('is_active', true)
                    ->first();
            }

            if (!$ccGrade) continue;

            $specSubjects = CcSubject::where('cc_grade_id', $ccGrade->id)
                ->where('type', 'specialized')
                ->where(function ($q) use ($studentFieldId) {
                    $q->where('cc_field_id', $studentFieldId)->orWhereNull('cc_field_id');
                })
                ->with(['chapters' => fn($q) => $q->where('is_active', true)])
                ->get();

            foreach ($specSubjects as $subj) {
                $total += $subj->chapters->count();
            }

            if (!empty($setting['has_general'])) {
                $total += CcSubject::where('cc_grade_id', $ccGrade->id)
                    ->where('type', 'general')
                    ->count();
            }
        }

        $this->totalTopics = $total;
    }

    protected function recalculateCompletedTopics()
    {
        if (empty($this->ratings)) {
            $this->completedTopics = 0;
            return;
        }

        $studentFieldId = $this->studentFieldSlug
            ? CcField::where('slug', $this->studentFieldSlug)->value('id')
            : null;

        $validChapterIds = collect();
        $validSubjectIds = collect();

        foreach ($this->getEffectiveSettings() as $setting) {
            $ccGrade = CcGrade::where('grade_number', $setting['target_grade'])
                ->where('is_active', true)
                ->when($studentFieldId, fn($q) => $q->where('cc_field_id', $studentFieldId))
                ->first()
                ?? CcGrade::where('grade_number', $setting['target_grade'])->where('is_active', true)->first();

            if (!$ccGrade) continue;

            $specSubjectIds = CcSubject::where('cc_grade_id', $ccGrade->id)
                ->where('type', 'specialized')
                ->where(function ($q) use ($studentFieldId) {
                    $q->where('cc_field_id', $studentFieldId)->orWhereNull('cc_field_id');
                })
                ->pluck('id');

            $chapterIds = CcChapter::whereIn('cc_subject_id', $specSubjectIds)
                ->where('is_active', true)
                ->pluck('id');

            $validChapterIds = $validChapterIds->merge($chapterIds);

            if (!empty($setting['has_general'])) {
                $genSubjectIds = CcSubject::where('cc_grade_id', $ccGrade->id)
                    ->where('type', 'general')
                    ->pluck('id');
                $validSubjectIds = $validSubjectIds->merge($genSubjectIds);
            }
        }

        $count = 0;
        foreach ($this->ratings as $key => $rating) {
            if (str_starts_with($key, 'chapter_')) {
                $id = (int) str_replace('chapter_', '', $key);
                if ($validChapterIds->contains($id)) $count++;
            } elseif (str_starts_with($key, 'subject_')) {
                $id = (int) str_replace('subject_', '', $key);
                if ($validSubjectIds->contains($id)) $count++;
            }
        }

        $this->completedTopics = $count;
    }

    protected function getEffectiveSettings(): array
    {
        if ($this->project->is_trial) {
            $studentG = $this->studentGrade ?: 10;
            $out = [];
            for ($g = 10; $g <= $studentG; $g++) {
                $out[] = ['target_grade' => $g, 'has_general' => true];
            }
            return $out;
        }
        return $this->project->gradeSettings()
            ->where('student_grade', $this->studentGrade)
            ->get()
            ->map(fn($s) => ['target_grade' => $s->target_grade, 'has_general' => (bool)$s->has_general])
            ->toArray();
    }

    public function selectTag($tagId)
    {
        $this->activeTag = $tagId;
        $this->loadSubjects();
    }

    protected function loadSubjects()
    {
        if (!$this->activeTag) { $this->subjects = []; return; }

        $parts       = explode('_', $this->activeTag);
        $gradeNumber = (int) $parts[0];
        $type        = $parts[1];

        $studentFieldId = $this->studentFieldSlug
            ? CcField::where('slug', $this->studentFieldSlug)->value('id')
            : null;

        $ccGrade = CcGrade::where('grade_number', $gradeNumber)
            ->where('is_active', true)
            ->when($studentFieldId, fn($q) => $q->where('cc_field_id', $studentFieldId))
            ->first()
            ?? CcGrade::where('grade_number', $gradeNumber)->where('is_active', true)->first();

        if (!$ccGrade) { $this->subjects = []; return; }

        $query = CcSubject::where('cc_grade_id', $ccGrade->id)->where('type', $type);

        if ($type === 'specialized') {
            $query->where(function ($q) use ($studentFieldId) {
                $q->where('cc_field_id', $studentFieldId)->orWhereNull('cc_field_id');
            });
            $this->subjects = $query
                ->with(['chapters' => fn($q) => $q->active()->ordered()])
                ->ordered()->get()
                ->map(fn($s) => [
                    'id'       => $s->id,
                    'name'     => $s->name,
                    'type'     => 'specialized',
                    'chapters' => $s->chapters->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->values()->toArray(),
                ])->toArray();
        } else {
            $this->subjects = $query->ordered()->get()
                ->map(fn($s) => ['id' => $s->id, 'name' => $s->name, 'type' => 'general', 'chapters' => []])
                ->toArray();
        }
    }

    public function setRating($id, $rating, $kind)
    {
        $rating = max(1, min(4, (int) $rating));
        $id     = (int) $id;
        $type   = $kind === 'chapter' ? CcChapter::class : CcSubject::class;

        StudentClassification::updateOrCreate(
            ['user_id' => auth()->id(), 'classification_project_id' => $this->project->id, 'ratable_type' => $type, 'ratable_id' => $id],
            ['rating' => $rating]
        );

        $this->ratings[($kind === 'chapter' ? 'chapter_' : 'subject_') . $id] = $rating;
        $this->recalculateCompletedTopics();
    }

    public function clearRating($id, $kind)
    {
        $id   = (int) $id;
        $type = $kind === 'chapter' ? CcChapter::class : CcSubject::class;

        StudentClassification::where('user_id', auth()->id())
            ->where('classification_project_id', $this->project->id)
            ->where('ratable_type', $type)->where('ratable_id', $id)->delete();

        unset($this->ratings[($kind === 'chapter' ? 'chapter_' : 'subject_') . $id]);
        $this->recalculateCompletedTopics();
    }

    public function openSubmitModal()  { $this->showSubmitModal = true; }

    public function submitClassification()
    {
        StudentClassificationSubmission::updateOrCreate(
            ['user_id' => auth()->id(), 'classification_project_id' => $this->project->id],
            ['is_completed' => true, 'submitted_at' => now()]
        );

        if ($this->project->is_trial) {
            $trial = TrialWeek::where('user_id', auth()->id())->latest()->first();
            if ($trial) {
                $payload = ['classification_locked_at' => now()];
                if (in_array($trial->status, [TrialWeek::STATUS_PENDING, TrialWeek::STATUS_SUPPORTER_ASSIGNED], true)) {
                    $payload['status'] = TrialWeek::STATUS_CLASSIFICATION_DONE;
                }
                $trial->update($payload);
            }
        }

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
        return view('livewire.client.profile.classification.classify')
            ->layout('layouts.client.app');
    }
}
