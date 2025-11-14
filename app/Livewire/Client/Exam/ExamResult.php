<?php

namespace App\Livewire\Client\Exam;

use Livewire\Component;

class ExamResult extends Component
{
    public function render()
    {
        return view('livewire.client.exam.exam-result')->layout('layouts.client.app');
    }
}
