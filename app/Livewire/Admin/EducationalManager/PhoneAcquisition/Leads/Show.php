<?php

namespace App\Livewire\Admin\EducationalManager\PhoneAcquisition\Leads;

use App\Models\PhoneLead;
use Livewire\Component;

/**
 * مدیر آموزشی — جزئیات یک شماره و تاریخچهٔ کامل تماس‌های آن.
 */
class Show extends Component
{
    public PhoneLead $lead;

    public function mount(PhoneLead $lead): void
    {
        $this->lead = $lead;
    }

    public function render()
    {
        $this->lead->load([
            'state:id,name',
            'city:id,name',
            'creator:id,name',
            'calls.admin:id,name',
            'assignments.consultant:id,name',
            'assignments.assignedBy:id,name',
        ]);

        return view('livewire.admin.educational-manager.phone-acquisition.leads.show', [
            'lead'  => $this->lead,
            'calls' => $this->lead->calls,
        ])->layout('layouts.admin.app');
    }
}
