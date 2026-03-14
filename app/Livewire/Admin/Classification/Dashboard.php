<?php
namespace App\Livewire\Admin\Classification;
use App\Models\ClassificationProject;
use App\Models\StudentClassificationSubmission;
use Artesaos\SEOTools\Traits\SEOTools;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
class Dashboard extends Component
{
    use WithPagination, SEOTools;
    public $search = '';
    public function mount()
    {
        $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()->setTitle('داشبورد طبقه‌بندی');
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }
    public function render()
    {
        // Statistics
        $upcomingProjects = ClassificationProject::active()->upcoming()->count();
        $endedProjects = ClassificationProject::active()->ended()->count();
        // Days to next project
        $nextProject = ClassificationProject::active()
            ->where('start_at', '>', now())
            ->orderBy('start_at')
            ->first();
        $daysToNext = $nextProject ? now()->diffInDays($nextProject->start_at) : null;
        // Projects list
        $projects = ClassificationProject::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);
        return view('livewire.admin.classification.dashboard', [
            'upcomingProjects' => $upcomingProjects,
            'endedProjects' => $endedProjects,
            'daysToNext' => $daysToNext,
            'projects' => $projects,
        ])->layout('layouts.admin.app');

    }

}
