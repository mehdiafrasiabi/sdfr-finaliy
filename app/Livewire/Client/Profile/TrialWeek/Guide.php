<?php

namespace App\Livewire\Client\Profile\TrialWeek;

use App\Models\ClassificationProject;
use App\Models\StudentClassification;
use App\Models\StudentClassificationSubmission;
use App\Models\TrialWeek;
use App\Services\TrialWeekService;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Guide extends Component
{
    use SEOTools;

    public ?TrialWeek $trialWeek = null;

    public bool $showLockConfirm = false;
    public bool $showHoursModal = false;
    public int $dailyStudyHours = 2;
    public bool $isLocking = false;

    public function mount(): void
    {
        $this->seo()->setTitle('هفته آزمایشی');
        $this->trialWeek = TrialWeek::where('user_id', Auth::id())->latest()->first();

        if (!$this->trialWeek) {
            redirect()->route('client.profile.dashboard');
        }
    }

    // بررسی تکمیل شدن طبقه‌بندی
    public function getClassificationDoneProperty(): bool
    {
        if (!$this->trialWeek) {
            return false;
        }
        $project = $this->activeProject; // اینجا تغییر بده
        if (!$project) {
            return false;
        }
        return StudentClassificationSubmission::where('user_id', Auth::id())
            ->where('classification_project_id', $project->id)
            ->exists();
    }

    public function getActiveProjectProperty(): ?ClassificationProject
    {
        return ClassificationProject::where('is_active', true)->first();
    }

    // قفل طبقه‌بندی + تایید
    public function openLockConfirm(): void
    {
        if (!$this->classificationDone) {
            $this->dispatch('warning', 'ابتدا باید طبقه‌بندی را تکمیل کنید.');
            return;
        }
        $this->showLockConfirm = true;
    }

    public function closeLockConfirm(): void
    {
        $this->showLockConfirm = false;
    }

    public function lockClassification(TrialWeekService $service): void
    {
        $this->isLocking = true;

        if (!$this->classificationDone) {
            $this->addError('lock', 'ابتدا باید طبقه‌بندی را کامل کنید.');
            $this->isLocking = false;
            return;
        }

        $service->lockClassification($this->trialWeek);
        $this->trialWeek->refresh();
        $this->showLockConfirm = false;
        $this->isLocking = false;
    }

    // ساخت برنامه
    public function openHoursModal(): void
    {
        if ($this->trialWeek->status !== TrialWeek::STATUS_PRE_SESSION_DONE) {
            return;
        }
        $this->showHoursModal = true;
    }

    public function closeHoursModal(): void
    {
        $this->showHoursModal = false;
    }

    public function buildProgram(TrialWeekService $service): void
    {
        $this->validate([
            'dailyStudyHours' => ['required', 'integer', 'min:1', 'max:12'],
        ]);

        $service->buildProgram($this->trialWeek, $this->dailyStudyHours);
        $this->trialWeek->refresh();
        $this->showHoursModal = false;

        $this->dispatch('success', 'برنامه مطالعاتی شما با موفقیت ساخته شد!');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.client.profile.trial-week.guide', [
            'activeProject' => $this->activeProject,
        ])->layout('layouts.client.app');
    }
}
