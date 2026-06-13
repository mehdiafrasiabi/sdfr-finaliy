<?php

namespace App\Livewire\Manager\School;

use App\Models\Admin;
use App\Models\School;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class Show extends Component
{
    use SEOTools;

    public School $school;
    public array $selectedAdvisors = [];

    public function mount(School $school): void
    {
        $this->school = $school->load(['manager', 'deputy', 'advisors']);
        $this->selectedAdvisors = $this->school->advisors->pluck('id')->map(fn($i) => (string) $i)->toArray();
        $this->seo()->setTitle('مدرسه: ' . $school->name);
    }

    public function syncAdvisors(): void
    {
        $ids = collect($this->selectedAdvisors)
            ->map(fn($i) => (int) $i)
            ->filter()
            ->unique()
            ->values()
            ->all();

        // فقط ادمین‌های دارای نقش «مشاور تحصیلی» مجاز هستند
        $validIds = Admin::role('مشاور تحصیلی')
            ->whereIn('id', $ids)
            ->pluck('id')
            ->all();

        $this->school->advisors()->sync($validIds);
        $this->school->load('advisors');

        $this->dispatch('success', 'مشاوران مدرسه به‌روزرسانی شد');
    }

    public function render()
    {
        $availableAdvisors = Admin::role('مشاور تحصیلی')->orderBy('name')->get(['id', 'name', 'mobile']);

        $students = $this->school->students()
            ->with(['user', 'advisor'])
            ->latest()
            ->limit(50)
            ->get();

        return view('livewire.manager.school.show', [
            'availableAdvisors' => $availableAdvisors,
            'students' => $students,
        ])->layout('layouts.manager.app');
    }
}
