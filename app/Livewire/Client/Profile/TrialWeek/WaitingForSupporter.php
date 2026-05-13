<?php

namespace App\Livewire\Client\Profile\TrialWeek;

use App\Models\Enrollment;
use App\Models\TrialWeek;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class WaitingForSupporter extends Component
{
    use SEOTools;

    public string $mode = 'trial'; // trial | enrollment

    public function mount(): void
    {
        $this->seo()->setTitle('در انتظار تخصیص پشتیبان');

        $user = Auth::user();

        $student = $user->student;

        // اگر supporter تخصیص یافته، مسیر بر اساس وضعیت
        if ($student && $student->supporter_id) {
            $hasTrial = TrialWeek::where('user_id', $user->id)->exists();
            if ($hasTrial) {
                redirect()->route('client.profile.trial.guide');
            } else {
                redirect()->route('client.profile.dashboard');
            }
            return;
        }

        // تعیین mode
        $hasPaidEnrollment = Enrollment::where('user_id', $user->id)
            ->where('status', Enrollment::STATUS_PAID)
            ->exists();

        $this->mode = $hasPaidEnrollment ? 'enrollment' : 'trial';
    }

    public function render()
    {
        return view('livewire.client.profile.trial-week.waiting-for-supporter')
            ->layout('layouts.client.app');
    }
}
