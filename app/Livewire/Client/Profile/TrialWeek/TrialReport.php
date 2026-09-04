<?php

namespace App\Livewire\Client\Profile\TrialWeek;

use App\Models\Assessment;
use App\Models\StudentAssessmentAttempt;
use App\Models\TrialWeek;
use App\Models\WeeklyProgram;
use App\Services\AssessmentInterpretationService;
use App\Services\TrialWeekService;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

/**
 * کارنامهٔ پایان هفتهٔ آزمایشی — مشابه SmartReportCardShow ولی مبتنی بر:
 *   ۱) نتایج شخصیت (MBTI + مایندست)  ۲) تحلیل طبقه‌بندی درس‌ها  ۳) خلاصهٔ برنامهٔ ساخته‌شده.
 */
class TrialReport extends Component
{
    use SEOTools;

    public ?TrialWeek $trialWeek = null;

    public function mount(): void
    {
        $this->seo()->setTitle('کارنامه هفته آزمایشی');

        $this->trialWeek = TrialWeek::where('user_id', Auth::id())->latest()->first();

        if (Auth::user()?->student && ! Auth::user()->student->is_trial) {
            redirect()->route('client.profile.dashboard');
            return;
        }

        // فقط پس از ساخت برنامه قابل مشاهده است.
        if (!$this->trialWeek || $this->trialWeek->status !== TrialWeek::STATUS_PROGRAM_BUILT) {
            redirect()->route('client.profile.trial.guide');
        }
    }

    public function getPersonalitySummaryProperty(): array
    {
        $interpreter = app(AssessmentInterpretationService::class);

        $attempts = StudentAssessmentAttempt::where('user_id', Auth::id())
            ->where('status', StudentAssessmentAttempt::STATUS_COMPLETED)
            ->with('assessment')
            ->get();

        $summary = ['mbti' => null, 'vark' => null, 'custom' => [], 'flags' => []];

        foreach ($attempts as $attempt) {
            $kind = $attempt->assessment?->kind;
            $cr   = $attempt->computed_result;

            if ($kind === Assessment::KIND_MBTI) {
                $summary['mbti'] = $interpreter->interpretMbti($cr);
            } elseif ($kind === Assessment::KIND_VARK) {
                $summary['vark'] = $interpreter->interpretVark($cr);
            } else {
                $custom = $interpreter->interpretCustom($cr, $attempt->assessment);
                if (!empty($custom['facets'])) {
                    $summary['custom'][$attempt->assessment->name_fa] = $custom['facets'];
                }
                foreach ($custom['flags'] ?? [] as $flag) {
                    $summary['flags'][] = $flag;
                }
            }
        }

        return $summary;
    }

    public function render(TrialWeekService $service): \Illuminate\Contracts\View\View
    {
        $classification = $service->getClassificationAnalysis($this->trialWeek->user_id);

        $program = WeeklyProgram::where('student_id', $this->trialWeek->student_id)
            ->with('parts')
            ->where('is_active', true)
            ->latest('start_date')
            ->first();

        return view('livewire.client.profile.trial-week.trial-report', [
            'personality'    => $this->personalitySummary,
            'classification' => $classification,
            'program'        => $program,
        ])->layout('layouts.client.app');
    }
}
