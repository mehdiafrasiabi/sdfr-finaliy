<?php

namespace App\Livewire\Manager\Newsletter;

use App\Exports\NewsletterExport;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Index extends Component
{
    public string $search = '';
    public bool $includeStudentName = false;
    public string $exportType = 'email';

    public string $tab = 'all'; // all, email, phone,student, father, mother

    protected $queryString = [
        'search' => ['except' => ''],
        'tab' => ['except' => 'all'],
    ];

    public function mount()
    {
        if (!in_array($this->tab, ['all', 'email', 'student', 'father', 'mother'], true)) {
            $this->tab = 'all';
        }
    }

    public function setTab(string $tab): void
    {
        $this->tab = $tab;
    }

    public function updatingSearch()
    {
        // بدون pagination، نیازی به resetPage نیست
    }

    public function export()
    {
        return Excel::download(
            new NewsletterExport($this->exportType, $this->includeStudentName),
            "newsletter-{$this->exportType}.xlsx"
        );
    }

    public function render()
    {
        $allContacts = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%")
                        ->orWhere('mobile', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->take(10)
            ->get();

        $studentContacts = User::query()
            ->whereNotNull('mobile')
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', "%{$this->search}%")
                        ->orWhere('mobile', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->take(10)
            ->get();

        $fatherContacts = User::query()
            ->whereHas('personalInformation', fn($query) => $query->whereNotNull('father_mobile'))
            ->with('personalInformation')
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', "%{$this->search}%")
                        ->orWhereHas('personalInformation', fn($relation) => $relation->where('father_mobile', 'like', "%{$this->search}%"));
                });
            })
            ->latest()
            ->take(10)
            ->get();

        $motherContacts = User::query()
            ->whereHas('personalInformation', fn($query) => $query->whereNotNull('mother_mobile'))
            ->with('personalInformation')
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', "%{$this->search}%")
                        ->orWhereHas('personalInformation', fn($relation) => $relation->where('mother_mobile', 'like', "%{$this->search}%"));
                });
            })
            ->latest()
            ->take(10)
            ->get();

        $emailContacts = User::query()
            ->whereNotNull('email')
            ->when($this->search, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->where('name', 'like', "%{$this->search}%")
                        ->orWhere('email', 'like', "%{$this->search}%");
                });
            })
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.manager.newsletter.index', compact(
            'allContacts',
            'studentContacts',
            'fatherContacts',
            'motherContacts',
            'emailContacts'
        ))->layout('layouts.manager.app');
    }
}
