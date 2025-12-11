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
    public string $exportType = 'email';

    public string $tab = 'all'; // all, email, phone

    protected $queryString = [
        'search' => ['except' => ''],
        'tab' => ['except' => 'all'],
    ];

    public function mount()
    {
        if (!in_array($this->tab, ['all', 'email', 'phone'], true)) {
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
            new NewsletterExport($this->exportType),
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

        $mobileContacts = User::query()
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
            'mobileContacts',
            'emailContacts'
        ))->layout('layouts.manager.app');
    }
}
