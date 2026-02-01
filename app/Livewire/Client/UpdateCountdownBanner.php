<?php

namespace App\Livewire\Client;

use Livewire\Component;
use Carbon\Carbon;
class UpdateCountdownBanner extends Component
{
    public int $remainingSeconds = 0;
    public bool $finished = false;

    public function mount(): void
    {
        // تایم‌زون ایران
        $tz = 'Asia/Tehran';

        $now = Carbon::now($tz);

        // شروع ثابت: امروز 12:46
        $start = Carbon::today($tz)->setTime(12, 46, 0);

        // پایان: یک هفته بعد از شروع
        $end = (clone $start)->addWeek();

        // اگر الان بعد از پایان بود
        if ($now->greaterThanOrEqualTo($end)) {
            $this->remainingSeconds = 0;
            $this->finished = true;
            return;
        }

        // باقی‌مانده تا پایان (ثابت و مشترک برای همه)
        $this->remainingSeconds = max(0, $now->diffInSeconds($end, false));
        $this->finished = ($this->remainingSeconds <= 0);
    }


    public function render()
    {
        return view('livewire.client.update-countdown-banner')->layout('layouts.client.app');
    }
}
