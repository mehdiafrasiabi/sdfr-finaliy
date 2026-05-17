<?php

namespace App\Livewire\Admin\SchoolSupporter;

use Livewire\Component;

class SchoolList extends Component
{
    public function render()
    {
        $admin = auth('admin')->user();

        $schools = $admin
            ->supportedSchools()
            ->withCount('students')
            ->orderBy('name')
            ->get();

        return view('livewire.admin.school-supporter.school-list', [
            'schools' => $schools,
        ])->layout('layouts.admin.app');
    }
}
