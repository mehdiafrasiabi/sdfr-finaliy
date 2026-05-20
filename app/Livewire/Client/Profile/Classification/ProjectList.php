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
            $this->studentGrade = (int) $personalInfo->grade;
            $this->studentField = $personalInfo->field;
        }

        // Anyone with a TrialWeek record is treated as a trial user — the trial card
        // is always shown for them, even if they also have a PersonalInformation row.
        $trial = TrialWeek::where('user_id', $userId)->latest()->first();
        if ($trial) {
            $this->isTrialUser = true;
            if (!$this->studentGrade) {
                $this->studentGrade = $trial->grade >= 10 ? $trial->grade : 10;
                $this->studentField = $trial->field;
            }
        }
    }

    public function selectProject($projectId)
    {
        $project = ClassificationProject::findOrFail($projectId);
        $grade = $this->studentGrade ?: 10;
        return redirect()->route('client.profile.classification.classify', [
            'project' => $project->id,
            'grade'   => $grade,
        ]);
    }

    public function goTrial()
    {
        $project = ClassificationProject::where('is_trial', true)->first();
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
        $trialProject = ClassificationProject::where('is_trial', true)->first();

        $base = ClassificationProject::query()->where('is_trial', false);

        $activeProjects = (clone $base)
            ->where('is_active', true)
            ->where('start_at', '<=', now())
            ->where('end_at', '>=', now())
            ->latest()->get();

        $upcomingProjects = (clone $base)
            ->where('is_active', true)
            ->where('start_at', '>', now())
            ->latest()->get();

        $endedProjects = (clone $base)
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
