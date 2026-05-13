<?php

namespace App\Livewire\Manager\Supports;

use App\Exports\SupportersExport;
use App\Models\Admin;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Supporter extends Component
{
    use WithPagination;
    public function export()
    {
        return Excel::download(new SupportersExport(), 'supporters.xlsx');
    }
    public function render()
    {
        $supporters = Admin::role('acquisition_supporter')->withCount('supportedStudents')->paginate(10);

        return view('livewire.manager.supports.supporter',['supporters'=>$supporters])->layout('layouts.manager.app');
    }
}
