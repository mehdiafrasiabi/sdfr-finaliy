<?php
namespace App\Livewire\Admin\Classification;

use App\Models\CcChapter;
use App\Models\CcField;
use App\Models\CcGrade;
use App\Models\CcSubject;
use App\Models\ClassificationProject;
use App\Models\PersonalInformation;
use App\Models\StudentClassification;
use App\Models\User;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class StudentDetail extends Component
{
    use SEOTools;

    public ClassificationProject $project;
    public User $user;
    public $studentGrade;
    public $studentField;
    public $studentFieldSlug;
    public $personalInfo;
    public $availableTags = [];
    public $activeTag = null;
    public $subjects = [];

    /** keyed: "chapter_{id}" | "subject_{id}" => 1..4 */
    public $ratings = [];

    public function mount(ClassificationProject $project, User $user)
    {
        $this->project = $project;
        $this->user = $user;
        $this->loadStudentInfo();
        $this->loadAvailableTags();
        $this->loadRatings();
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()->setTitle('جزئیات طبقه‌بندی - ' . ($this->personalInfo->name ?? $this->user->name));
    }

    protected function loadStudentInfo()
    {
        $this->personalInfo = PersonalInformation::where('user_id', $this->user->id)->first();
        if ($this->personalInfo) {
            $this->studentGrade = (int) $this->personalInfo->grade;
            $this->studentField = $this->personalInfo->field;
            $this->studentFieldSlug = ['math' => 'math', 'experimental' => 'experimental', 'human' => 'human'][$this->studentField] ?? null;
        }
    }

    protected function loadAvailableTags()
    {
        $settings = $this->project->is_trial
            ? collect([(object) ['target_grade' => $this->studentGrade ?: 10, 'has_general' => true]])
            : $this->project->gradeSettings()->where('student_grade', $this->studentGrade)->get();

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
            $this->activeTag = $this->availableTags[0]['id'];
            $this->loadSubjects();
        }
    }

    protected function loadRatings()
    {
        $rows = StudentClassification::where('user_id', $this->user->id)
            ->where('classification_project_id', $this->project->id)
            ->get(['ratable_type', 'ratable_id', 'rating']);

        $map = [];
        foreach ($rows as $r) {
            $key = ($r->ratable_type === CcChapter::class ? 'chapter_' : 'subject_') . $r->ratable_id;
            $map[$key] = (int) $r->rating;
        }
        $this->ratings = $map;
    }

    public function selectTag($tagId)
    {
        $this->activeTag = $tagId;
        $this->loadSubjects();
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
        if (!$ccGrade) { $this->subjects = []; return; }

        $studentFieldId = $this->studentFieldSlug
            ? CcField::where('slug', $this->studentFieldSlug)->value('id')
            : null;

        $query = CcSubject::where('cc_grade_id', $ccGrade->id)->where('type', $type);

        if ($type === 'specialized') {
            $query->where(function ($q) use ($studentFieldId) {
                $q->where('cc_field_id', $studentFieldId)->orWhereNull('cc_field_id');
            });
            $subjects = $query->with(['chapters' => fn ($q) => $q->active()->ordered()])->ordered()->get();
            $this->subjects = $subjects->map(fn ($s) => [
                'id' => $s->id, 'name' => $s->name, 'type' => 'specialized',
                'chapters' => $s->chapters->map(fn ($c) => ['id' => $c->id, 'name' => $c->name])->values()->toArray(),
            ])->toArray();
        } else {
            $subjects = $query->ordered()->get();
            $this->subjects = $subjects->map(fn ($s) => [
                'id' => $s->id, 'name' => $s->name, 'type' => 'general', 'chapters' => [],
            ])->toArray();
        }
    }

    public function getRatingLabel($rating)
    {
        return StudentClassification::RATINGS[$rating] ?? '';
    }

    public function getRatingColor($rating)
    {
        return match ((int) $rating) {
            4 => 'success',
            3 => 'info',
            2 => 'warning',
            1 => 'danger',
            default => 'secondary',
        };
    }

    public function render()
    {
        $gradeNames = ['10' => 'دهم', '11' => 'یازدهم', '12' => 'دوازدهم'];
        $fieldNames = ['math' => 'ریاضی', 'experimental' => 'تجربی', 'human' => 'انسانی'];
        return view('livewire.admin.classification.student-detail', [
            'gradeNames' => $gradeNames,
            'fieldNames' => $fieldNames,
        ])->layout('layouts.admin.app');
    }
}
