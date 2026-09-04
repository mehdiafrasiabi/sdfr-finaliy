<?php

namespace App\Livewire\Admin\EducationalManager\PhoneAcquisition;

use App\Services\AcquisitionAnalyticsService;
use Livewire\Attributes\Url;
use Livewire\Component;

/** داشبورد نظارتی مشترک جذب تلفنی و جذب یک‌هفته آزمایشی برای مدیر آموزشی. */
class Dashboard extends Component
{
    #[Url]
    public string $period = 'all';

    public function updatedPeriod(): void
    {
        if (! in_array($this->period, ['all', 'today', '7', '30'], true)) {
            $this->period = 'all';
        }
    }

    public function render(AcquisitionAnalyticsService $analytics)
    {
        return view('livewire.admin.educational-manager.phone-acquisition.dashboard', [
            'analytics' => $analytics->dashboard($this->period),
        ])->layout('layouts.admin.app');
    }
}
