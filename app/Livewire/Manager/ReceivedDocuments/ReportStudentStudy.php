<?php

namespace App\Livewire\Manager\ReceivedDocuments;

use App\Models\ContactDocumentation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ReportStudentStudy extends Component
{
    use WithPagination;

    public function render()
    {
        $receivedFiles = \App\Models\ReportStudentStudy::with('sender')
            ->where('receiver_id', Auth::id())
            ->latest()
            ->paginate(10);
        return view('livewire.manager.received-documents.report-student-study',[
            'receivedFiles' => $receivedFiles
        ])->layout('layouts.manager.app')->title('گزارش درسی دانش اموزان');
    }
}
