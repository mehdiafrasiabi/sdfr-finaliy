<?php
namespace App\Livewire\Admin\Classification;
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
            $this->studentGrade = (int)$this->personalInfo->grade;
            $this->studentField = $this->personalInfo->field;
            $fieldMapping = [
                'math' => 'math',
                'experimental' => 'experimental',
                'human' => 'human',
            ];
            $this->studentFieldSlug = $fieldMapping[$this->studentField] ?? null;
        }
    }

    protected function loadAvailableTags()
    {
        $settings = $this->project->gradeSettings()
            ->where('student_grade', $this->studentGrade)
            ->get();
        $gradeNames = [10 => 'دهم', 11 => 'یازدهم', 12 => 'دوازدهم'];
        foreach ($settings as $setting) {
            $this->availableTags[] = [
                'id' => $setting->target_grade . '_specialized',
                'grade' => $setting->target_grade,
                'grade_name' => $gradeNames[$setting->target_grade] ?? $setting->target_grade,
                'type' => 'specialized',
                'type_name' => 'تخصصی',
                'label' => $gradeNames[$setting->target_grade] . ' (تخصصی)',
            ];
            if ($setting->has_general) {
                $this->availableTags[] = [
                    'id' => $setting->target_grade . '_general',
                    'grade' => $setting->target_grade,
                    'grade_name' => $gradeNames[$setting->target_grade] ?? $setting->target_grade,
                    'type' => 'general',
                    'type_name' => 'عمومی',
                    'label' => $gradeNames[$setting->target_grade] . ' (عمومی)',
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

        $this->ratings = StudentClassification::where('user_id', $this->user->id)
            ->where('classification_project_id', $this->project->id)
            ->pluck('rating', 'cc_topic_id')
            ->toArray();
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
        $gradeNumber = (int)$parts[0];
        $type = $parts[1];
        $ccGrade = CcGrade::where('grade_number', $gradeNumber)->first();
        if (!$ccGrade) {
            $this->subjects = [];
            return;
        }
        $studentFieldId = CcField::where('slug', $this->studentFieldSlug)->value('id');
        $query = CcSubject::where('cc_grade_id', $ccGrade->id)
            ->where('type', $type);
        if ($type === 'specialized') {
            $query->where(function ($q) use ($studentFieldId) {
                $q->where('cc_field_id', $studentFieldId)
                    ->orWhereNull('cc_field_id');
            });
        }
        $this->subjects = $query->with(['chapters' => function ($q) {
            $q->active()->ordered()->with(['topics' => function ($q) {
                $q->active()->ordered()->mainTopics()->with(['children' => function ($subQ) {
                    $subQ->active()->ordered();
                }]);
            }]);

        }])->ordered()->get()->toArray();
    }
    public function getRatingLabel($rating)
    {
        $labels = [1 => 'D', 2 => 'D+', 3 => 'C', 4 => 'C+', 5 => 'B', 6 => 'B+', 7 => 'A', 8 => 'A+'];
        return $labels[$rating] ?? '';
    }
    public function getRatingColor($rating)
    {
        return match (true) {
            $rating >= 7 => 'success',
            $rating >= 5 => 'info',
            $rating >= 3 => 'warning',
            default => 'danger',
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
