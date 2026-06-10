<?php

namespace App\Livewire\Client\Profile\TrialWeek;

use App\Models\TrialWeek;
use App\Services\TrialWeekService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Start extends Component
{
    public bool $showConfirmModal = false;
    public bool $showFormModal    = false;
    public bool $isLoading        = false;

    public int    $grade         = 10;
    public string $field         = 'math';
    public string $fatherMobile  = '';
    public string $motherMobile  = '';

    protected function rules(): array
    {
        return [
            'grade'        => ['required', 'in:9,10,11,12'],
            'field'        => [
                'nullable',
                $this->grade == 9 ? 'sometimes' : 'required',
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

    public function openConfirm(): void
    {
        $this->showConfirmModal = true;
    }

    public function closeConfirm(): void
    {
        $this->showConfirmModal = false;
    }

    public function confirm(): void
    {
        $this->showConfirmModal = false;
        $this->showFormModal    = true;
    }

    public function closeForm(): void
    {
        $this->showFormModal = false;
        $this->reset(['grade', 'field', 'fatherMobile', 'motherMobile']);
    }

    public function submit(TrialWeekService $service): void
    {
        $this->validate();

        $user = Auth::user();

        // جلوگیری از ثبت مجدد
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
            (bool) ($user->personalInformation?->attends_school ?? true),
        );

        $this->showFormModal = false;
        $this->dispatch('trial-started');

        redirect()->route('client.profile.trial.guide');
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('livewire.client.profile.trial-week.start');
    }
}
