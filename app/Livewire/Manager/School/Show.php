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
    public array $selectedSupporters = [];

    public function mount(School $school): void
    {
        $this->school = $school->load(['manager', 'deputy', 'supporters']);
        $this->selectedSupporters = $this->school->supporters->pluck('id')->map(fn($i) => (string) $i)->toArray();
        $this->seo()->setTitle('مدرسه: ' . $school->name);
    }

    public function syncSupporters(): void
    {
        $ids = collect($this->selectedSupporters)
            ->map(fn($i) => (int) $i)
            ->filter()
            ->unique()
            ->values()
            ->all();

        // فقط ادمین‌های دارای نقش «پشتیبان مدرسه» مجاز هستند
        $validIds = Admin::role('school-supporter')
            ->whereIn('id', $ids)
            ->pluck('id')
            ->all();

        $this->school->supporters()->sync($validIds);
        $this->school->load('supporters');

        $this->dispatch('success', 'پشتیبان‌های مدرسه به‌روزرسانی شد');
    }

    public function render()
    {
        $availableSupporters = Admin::role('school-supporter')->orderBy('name')->get(['id', 'name', 'mobile']);

        $students = $this->school->students()
            ->with(['user', 'schoolSupporter'])
            ->latest()
            ->limit(50)
            ->get();

        return view('livewire.manager.school.show', [
            'availableSupporters' => $availableSupporters,
            'students' => $students,
        ])->layout('layouts.manager.app');
    }
}
