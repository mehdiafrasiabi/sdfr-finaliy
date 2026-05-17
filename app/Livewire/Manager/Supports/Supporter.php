<?php

namespace App\Livewire\Manager\Supports;

use App\Exports\SupportersExport;
use App\Models\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

/**
 * مدیریت «پشتیبان‌های جذب» — نقش پنل سوپرادمین.
 * (در بازطراحی، نقش «پشتیبان تحصیلی» حذف شد و این صفحه فقط
 * پشتیبان‌های جذب را نمایش می‌دهد.)
 */
class Supporter extends Component
{
    use WithPagination;

    public function export()
    {
        return Excel::download(new SupportersExport(), 'supporters.xlsx');
    }

    public function render()
    {
        $supporters = Admin::role('site acquisition')
            ->withCount('acquisitionTrialWeeks')
            ->paginate(10);

        return view('livewire.manager.supports.supporter', [
            'supporters' => $supporters,
        ])->layout('layouts.manager.app');
    }
}
