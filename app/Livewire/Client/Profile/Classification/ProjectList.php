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

    public $studentGrade = null;
    public $studentField = null;
    public bool $isTrialUser = false;
    public bool $hasNormalClassificationAccess = false;

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
        $user = auth()->user();
        $userId = $user->id;
        $personalInfo = PersonalInformation::where('user_id', $userId)->first();
        if ($personalInfo) {
            $this->studentGrade = (int) $personalInfo->grade;
            $this->studentField = $personalInfo->field;
        }

        $student = $user->student;
        $this->hasNormalClassificationAccess = $user->isSchoolStudent()
            || ($student && ! $student->is_trial && $student->hasActivePaidAccess());

        $trial = TrialWeek::where('user_id', $userId)->latest()->first();

        if ($trial && ! $this->hasNormalClassificationAccess && (! $trial->expires_at || $trial->expires_at->isFuture())) {
            $this->isTrialUser = ! $student || $student->is_trial;
            if (!$this->studentGrade) {
                $this->studentGrade = $trial->grade >= 10 ? $trial->grade : 10;
                $this->studentField = $trial->field;
            }
        }
    }

    public function selectProject($projectId)
    {
        $project = ClassificationProject::findOrFail($projectId);

        if ($this->isTrialUser || $project->is_trial) {
            $this->dispatch('warning', 'این طبقه‌بندی برای حساب شما در دسترس نیست.');
            return;
        }

        $grade = $this->studentGrade ?: 10;
        return redirect()->route('client.profile.classification.classify', [
            'project' => $project->id,
            'grade'   => $grade,
        ]);
    }

    public function goTrial()
    {
        if (! $this->isTrialUser) {
            $this->dispatch('warning', 'طبقه‌بندی آزمایشی برای حساب شما در دسترس نیست.');
            return;
        }

        $project = ClassificationProject::where('is_trial', true)->where('is_active', true)->first();
        if (!$project) {
            $this->dispatch('warning', 'طبقه‌بندی آزمایشی هنوز پیکربندی نشده است.');
            return;
        }
        $grade = $this->studentGrade ?: 10;
        return redirect()->route('client.profile.classification.classify', [
            'project' => $project->id,
            'grade'   => $grade,
        ]);
    }

    public function render()
    {
        $trialProject = $this->isTrialUser
            ? ClassificationProject::where('is_trial', true)->where('is_active', true)->first()
            : null;

        $base = ClassificationProject::query()->where('is_trial', false);

        $activeProjects = $this->isTrialUser
            ? collect()
            : (clone $base)
                ->where('is_active', true)
                ->where('start_at', '<=', now())
                ->where('end_at', '>=', now())
                ->latest()->get();

        $upcomingProjects = $this->isTrialUser
            ? collect()
            : (clone $base)
                ->where('is_active', true)
                ->where('start_at', '>', now())
                ->latest()->get();

        $endedProjects = $this->isTrialUser
            ? collect()
            : (clone $base)
                ->where(function ($q) {
                    $q->where('is_active', false)->orWhere('end_at', '<', now());
                })
                ->latest()->get();

        $submissions = StudentClassificationSubmission::where('user_id', auth()->id())
            ->pluck('is_completed', 'classification_project_id')
            ->toArray();

        $trialSubmitted = $trialProject
            ? (bool) ($submissions[$trialProject->id] ?? false)
            : false;

        return view('livewire.client.profile.classification.project-list', [
            'activeProjects'   => $activeProjects,
            'upcomingProjects' => $upcomingProjects,
            'endedProjects'    => $endedProjects,
            'submissions'      => $submissions,
            'trialProject'     => $trialProject,
            'trialSubmitted'   => $trialSubmitted,
        ])->layout('layouts.client.app');
    }
}
