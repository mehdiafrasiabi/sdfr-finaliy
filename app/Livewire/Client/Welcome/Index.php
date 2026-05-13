<?php

namespace App\Livewire\Client\Welcome;

use App\Models\Enrollment;
use App\Models\TrialWeek;
use App\Services\TrialWeekService;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    use SEOTools;

    public bool $showTrialForm = false;
    public int $grade = 10;
    public string $field = 'math';
    public string $fatherMobile = '';
    public string $motherMobile = '';

    protected function rules(): array
    {
        return [
            'grade'        => ['required', 'in:9,10,11,12'],
            'field'        => [
                $this->grade == 9 ? 'nullable' : 'required',
                'in:math,experimental,human',
            ],
            'fatherMobile' => ['required', 'regex:/^09[0-9]{9}$/'],
            'motherMobile' => ['required', 'regex:/^09[0-9]{9}$/'],
        ];
    }

    protected array $validationAttributes = [
        'grade'        => 'پایه تحصیلی',
        'field'        => 'رشته',
        'fatherMobile' => 'تلفن پدر',
        'motherMobile' => 'تلفن مادر',
    ];

    public function mount()
    {
        $this->seo()->setTitle('خوش آمدید');

        $user = Auth::user();

        // اگر هفته آزمایشی شروع شده، به guide ببر
        if (TrialWeek::where('user_id', $user->id)->exists()) {
            redirect()->route('client.profile.trial.guide');
            return;
        }

        // اگر enrollment paid + supporter داره، به داشبورد
        $hasActive = Enrollment::where('user_id', $user->id)
            ->where('status', Enrollment::STATUS_PAID)
            ->whereNotNull('supporter_id')
            ->exists();

        if ($hasActive) {
            redirect()->route('client.profile.dashboard');
            return;
        }

        // اگر enrollment paid ولی بدون supporter داره، به waiting
        $hasPending = Enrollment::where('user_id', $user->id)
            ->where('status', Enrollment::STATUS_PAID)
            ->whereNull('supporter_id')
            ->exists();

        if ($hasPending) {
            redirect()->route('client.profile.waiting-for-supporter');
        }
    }

    public function openTrialForm(): void
    {
        $this->showTrialForm = true;
    }

    public function closeTrialForm(): void
    {
        $this->showTrialForm = false;
    }

    public function startTrial(TrialWeekService $service): void
    {
        $this->validate();

        $user = Auth::user();

        if (TrialWeek::where('user_id', $user->id)->exists()) {
            $this->addError('general', 'شما قبلاً هفته آزمایشی را ثبت کرده‌اید.');
            return;
        }

        $service->start(
            $user,
            (int) $this->grade,
            $this->grade == 9 ? null : $this->field,
            $this->fatherMobile,
            $this->motherMobile,
        );

        redirect()->route('client.profile.trial.guide');
    }

    public function goToPurchase(): void
    {
        redirect()->route('client.purchase');
    }

    public function render()
    {
        return view('livewire.client.welcome.index')->layout('layouts.client.app');
    }
}
