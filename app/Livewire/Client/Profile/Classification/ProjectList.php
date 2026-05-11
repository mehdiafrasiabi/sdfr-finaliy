<?php

namespace App\Livewire\Client\Profile\Classification;
use App\Models\ClassificationProject;
use App\Models\PersonalInformation;
use App\Models\StudentClassificationSubmission;
use App\Models\TrialWeek;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;
class ProjectList extends Component
{
    use SEOTools;
    public $selectedProject = null;
    public $showGradeModal = false;
    public $availableGrades = [];
    public $studentGrade = null;
    public $studentField = null;
    public bool $isTrialUser = false;
    public function mount()
    {
        $this->seoConfig();
        $this->loadStudentInfo();
    }
    public function seoConfig()
    {
        $this->seo()->setTitle('طبقه‌بندی دروس');
    }
    protected function loadStudentInfo()
    {
        $userId = auth()->id();
        $personalInfo = PersonalInformation::where('user_id', $userId)->first();
        if ($personalInfo) {
            $this->studentGrade = (int)$personalInfo->grade;
            $this->studentField = $personalInfo->field;
            return;
        }
        // برای کاربران آزمایشی که PersonalInformation ندارند
        $trial = TrialWeek::where('user_id', $userId)
            ->whereIn('status', [
                TrialWeek::STATUS_SUPPORTER_ASSIGNED,
                TrialWeek::STATUS_CLASSIFICATION_DONE,
                TrialWeek::STATUS_PRE_SESSION_DONE,
                TrialWeek::STATUS_PROGRAM_BUILT,
            ])
            ->latest()
            ->first();
        if ($trial) {
            $this->isTrialUser   = true;
            $this->studentGrade  = $trial->grade >= 10 ? $trial->grade : 10;
            $this->studentField  = $trial->field;
        }
    }
    public function selectProject($projectId)
    {
        $this->selectedProject = ClassificationProject::with('gradeSettings')
            ->findOrFail($projectId);
        // Get available grades for this student
        $this->availableGrades = $this->getAvailableGrades();
        $this->showGradeModal = true;
    }
    protected function getAvailableGrades()
    {
        if (!$this->selectedProject || !$this->studentGrade) {
            return [];
        }
        $grades = [];
        $settings = $this->selectedProject->gradeSettings()
            ->where('student_grade', $this->studentGrade)
            ->get();
        $gradeNames = [10 => 'دهم', 11 => 'یازدهم', 12 => 'دوازدهم'];
        foreach ($settings as $setting) {
            $grades[] = [
                'grade' => $setting->target_grade,
                'name' => $gradeNames[$setting->target_grade] ?? $setting->target_grade,
                'type' => $setting->type,
                'type_name' => $setting->type === 'progress' ? 'پیشروی' : 'جمع‌بندی',
                'has_general' => $setting->has_general,
            ];
        }
        return $grades;
    }
    public function startClassification($grade)
    {
        return redirect()->route('client.profile.classification.classify', [
            'project' => $this->selectedProject->id,
            'grade' => $grade,
        ]);
    }
    public function closeModal()
    {
        $this->showGradeModal = false;
        $this->selectedProject = null;
    }

    public function render()
    {
        // پروژه‌های فعال در حال اجرا
        $activeProjects = ClassificationProject::query()
            ->where('is_active', true)
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now())
            ->latest()
            ->get();
        // پروژه‌های در انتظار (هنوز شروع نشده)
        $upcomingProjects = ClassificationProject::query()
            ->where('is_active', true)
            ->where('start_at', '>', now())
            ->latest()
            ->get();
        // پروژه‌های تمام شده یا غیرفعال
        $endedProjects = ClassificationProject::query()
            ->where(function ($q) {
                $q->where('is_active', false)
                    ->orWhere('end_at', '<', now());
            })
            ->latest()
            ->get();
        // Get submission status for each project
        $submissions = StudentClassificationSubmission::where('user_id', auth()->id())
            ->pluck('is_completed', 'classification_project_id')
            ->toArray();
        return view('livewire.client.profile.classification.project-list', [
            'activeProjects' => $activeProjects,
            'upcomingProjects' => $upcomingProjects,
            'endedProjects' => $endedProjects,
            'submissions' => $submissions,
        ])->layout('layouts.client.app');
    }

}


