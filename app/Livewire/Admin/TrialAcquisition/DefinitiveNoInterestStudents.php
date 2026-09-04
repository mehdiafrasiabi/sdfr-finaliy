<?php

namespace App\Livewire\Admin\TrialAcquisition;

use App\Models\TrialWeek;

class DefinitiveNoInterestStudents extends NoInterestStudents
{
    protected function status(): string
    {
        return TrialWeek::ACQ_DISINTEREST_DEFINITIVE;
    }

    protected function title(): string
    {
        return 'دانش‌آموزان با عدم تمایل قطعی';
    }
}
