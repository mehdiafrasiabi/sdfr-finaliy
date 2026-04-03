<?php

namespace App\Livewire\Client\ExamCountdown;

use App\Models\ExamCountdownSetting;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class Index extends Component
{
    use SEOTools;

    public function render()
    {
        $setting = ExamCountdownSetting::with('events')->first();

        $endTimestamp = null;
        if ($setting && $setting->end_date) {
            $endTimestamp = \Carbon\Carbon::parse($setting->end_date)->endOfDay()->timestamp;
        }

        if ($setting) {
            $this->seo()
                ->setTitle($setting->title)
                ->setDescription($setting->subtitle);
        } else {
            $this->seo()->setTitle('روز شمار کنکور');
        }

        return view('livewire.client.exam-countdown.index', [
            'setting'      => $setting,
            'endTimestamp' => $endTimestamp,
        ])->layout('layouts.client.app');
    }
}
