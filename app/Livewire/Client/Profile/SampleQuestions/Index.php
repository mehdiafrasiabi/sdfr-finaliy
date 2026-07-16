<?php

namespace App\Livewire\Client\Profile\SampleQuestions;

use App\Models\ExamPlanningSetting;
use App\Models\ExamSampleQuestion;
use App\Services\ExamPlanningService;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use SEOTools, WithPagination;

    public string $search = '';
    public string $subjectFilter = '';
    public ?int $settingId = null;

    protected $queryString = ['search', 'subjectFilter'];

    public function mount(ExamPlanningService $service): void
    {
        $access = $service->resolveExamAccess(auth()->user());
        $setting = $access['setting'] ?? null;

        if (! $access['mode'] || ! $setting) {
            $this->redirect(route('client.profile.dashboard'), navigate: true);

            return;
        }

        $this->settingId = $setting->id;
        $this->seo()
            ->setTitle('نمونه سوالات تشریحی');
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingSubjectFilter(): void
    {
        $this->resetPage();
    }

    public function render(ExamPlanningService $service): View
    {
        $setting = $this->settingId
            ? ExamPlanningSetting::query()->find($this->settingId)
            : null;

        if (! $setting) {
            return view('livewire.client.profile.sample-questions.index', [
                'setting' => null,
                'books' => collect(),
                'questions' => collect(),
                'stats' => [
                    'total' => 0,
                    'books' => 0,
                    'main' => 0,
                    'timed' => 0,
                ],
            ])->layout('layouts.client.app');
        }

        $curriculum = $service->curriculumForSetting($setting);

        $books = collect($curriculum['general_subjects'] ?? [])
            ->merge($curriculum['specialized_subjects'] ?? [])
            ->unique('id')
            ->sortBy('name')
            ->values()
            ->map(fn (array $subject) => [
                'id' => (int) $subject['id'],
                'name' => $subject['name'],
                'type' => $subject['type'] ?? null,
            ]);

        $baseQuery = ExamSampleQuestion::query()
            ->with('subject')
            ->where('exam_planning_setting_id', $setting->id);

        $questionsQuery = (clone $baseQuery)
            ->when($this->search, function ($query) {
                $query->where(function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%')
                        ->orWhereHas('subject', function ($subjectQuery) {
                            $subjectQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->subjectFilter, function ($query) {
                $query->where('cc_subject_id', (int) $this->subjectFilter);
            });

        $questions = $questionsQuery
            ->orderByDesc('is_main')
            ->orderBy('cc_subject_id')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.client.profile.sample-questions.index', [
            'setting' => $setting,
            'books' => $books,
            'questions' => $questions,
            'stats' => [
                'total' => (clone $baseQuery)->count(),
                'books' => $books->count(),
                'main' => (clone $baseQuery)->where('is_main', true)->count(),
                'timed' => (clone $baseQuery)->whereNotNull('duration_minutes')->count(),
            ],
        ])->layout('layouts.client.app');
    }
}
