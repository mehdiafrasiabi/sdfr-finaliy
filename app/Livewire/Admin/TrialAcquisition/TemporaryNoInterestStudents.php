<?php

namespace App\Livewire\Admin\TrialAcquisition;

use App\Models\TrialWeek;

class TemporaryNoInterestStudents extends NoInterestStudents
{
    protected function status(): string
    {
        return TrialWeek::ACQ_DISINTEREST_TEMPORARY;
    }

    protected function title(): string
    {
        return 'دانش‌آموزان با عدم تمایل موقت';
    }
}
