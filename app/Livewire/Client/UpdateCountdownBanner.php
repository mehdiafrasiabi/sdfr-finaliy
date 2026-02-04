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

        // تاریخ شروع: 1404/11/14 ساعت 11:10 صبح
        // میلادی: 2026-02-03 11:10:00
        $start = Carbon::create(2026, 2, 3, 11, 10, 0, $tz);

        // تاریخ پایان: 1404/11/19 ساعت 12 شب (نیمه‌شب)
        // میلادی: 2026-02-08 00:00:00
        $end = Carbon::create(2026, 2, 8, 0, 0, 0, $tz);

        // اگر هنوز شروع نشده
        if ($now->lessThan($start)) {
            $this->remainingSeconds = $start->diffInSeconds($end);
            $this->finished = false;
            return;
        }

        // اگر تموم شده
        if ($now->greaterThanOrEqualTo($end)) {
            $this->remainingSeconds = 0;
            $this->finished = true;
            return;
        }

        // در حال شمارش معکوس
        $this->remainingSeconds = max(0, $now->diffInSeconds($end, false));
        $this->finished = false;
    }

    public function render()
    {
        return view('livewire.client.update-countdown-banner')->layout('layouts.client.app');
    }
}
