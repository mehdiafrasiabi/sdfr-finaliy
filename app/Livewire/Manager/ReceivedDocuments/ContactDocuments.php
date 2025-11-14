<?php

namespace App\Livewire\Manager\ReceivedDocuments;

use App\Models\ContactDocumentation;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class ContactDocuments extends Component
{
    use WithPagination;

    public function render()
    {
        $receivedFiles = ContactDocumentation::with('sender')
            ->where('receiver_id', Auth::id())
            ->latest()
            ->paginate(10);
        return view('livewire.manager.received-documents.contact-documents',['receivedFiles' => $receivedFiles,])->layout('layouts.manager.app');
    }
}
