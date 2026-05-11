<?php

namespace App\Livewire\Client\Profile\TrialWeek;

use App\Models\AdvisingPreSession;
use App\Models\TrialWeek;
use App\Services\TrialWeekService;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class SessionAnalysis extends Component
{
    use SEOTools;

    public ?TrialWeek $trialWeek   = null;
    public array       $analysis    = [];
    public bool        $showHoursModal = false;
    public int         $dailyStudyHours = 2;

    public function mount(TrialWeekService $service): void
    {
        $this->seo()->setTitle('تحلیل وضعیت آزمایشی');

        $this->trialWeek = TrialWeek::where('user_id', Auth::id())->latest()->first();

        if (!$this->trialWeek || !$this->trialWeek->canBuildProgram()) {
            if ($this->trialWeek?->status === TrialWeek::STATUS_PROGRAM_BUILT) {
                // اگر برنامه قبلاً ساخته شده، اجازه مشاهده داشته باشد
            } elseif (!$this->trialWeek || $this->trialWeek->step < 3) {
                redirect()->route('client.profile.trial.guide');
                return;
            }
        }

        $this->analysis = $service->getClassificationAnalysis(Auth::id());
    }

    public function openHoursModal(): void
    {
        $this->showHoursModal = true;
    }

    public function closeHoursModal(): void
    {
        $this->showHoursModal = false;
    }

    public function buildProgram(TrialWeekService $service): void
    {
        $this->validate(['dailyStudyHours' => ['required', 'integer', 'min:1', 'max:14']]);

        $program = $service->buildProgram($this->trialWeek, $this->dailyStudyHours);
        $this->trialWeek->refresh();
        $this->showHoursModal = false;

        redirect()->route('client.profile.consultation.weekly-program', $program->id);
    }

    public function getPreSessionProperty(): ?AdvisingPreSession
    {
        if (!$this->trialWeek?->advising_session_id) {
            return null;
        }
        return AdvisingPreSession::where('advising_session_id', $this->trialWeek->advising_session_id)
            ->first();
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.client.profile.trial-week.session-analysis',[
            'preSession'=> $this->preSession
        ])
            ->layout('layouts.client.app');
    }
}
