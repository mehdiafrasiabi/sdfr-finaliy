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
    public string $periodMonthFilter = '';
    public string $periodYearFilter = '';
    public ?int $settingId = null;

    protected $queryString = ['search', 'subjectFilter', 'periodMonthFilter', 'periodYearFilter'];

    public function mount(ExamPlanningService $service): void
    {
        $this->settingId = $service->resolveActiveSettingForUser(auth()->user())?->id;
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

    public function updatingPeriodMonthFilter(): void
    {
        $this->resetPage();
    }

    public function updatingPeriodYearFilter(): void
    {
        $this->resetPage();
    }

    public function render(ExamPlanningService $service): View
    {
        $profile = $service->resolveAcademicProfile(auth()->user());
        $setting = null;

        if ($profile && $this->settingId) {
            $candidateSetting = ExamPlanningSetting::query()->find($this->settingId);
            $setting = $candidateSetting && $this->settingMatchesProfile($candidateSetting, $profile)
                ? $candidateSetting
                : null;
        }

        $books = $profile
            ? $this->booksForProfile($service, $profile)
            : collect();
        $allowedBookIds = $books
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();

        if ($this->subjectFilter && ! in_array((int) $this->subjectFilter, $allowedBookIds, true)) {
            $this->subjectFilter = '';
        }

        $baseQuery = ExamSampleQuestion::query()
            ->with('subject')
            ->when(
                $profile && ! empty($allowedBookIds),
                function ($query) use ($profile, $setting, $allowedBookIds) {
                    $query
                        ->whereIn('cc_subject_id', $allowedBookIds)
                        ->whereHas('setting', fn ($settingQuery) => $this->constrainSettingToProfile($settingQuery, $profile))
                        ->when($setting, fn ($query) => $query->where('exam_planning_setting_id', $setting->id));
                },
                fn ($query) => $query->whereRaw('1 = 0')
            );

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
            })
            ->when($this->periodMonthFilter, function ($query) {
                $query->where('exam_period_month', (int) $this->periodMonthFilter);
            })
            ->when($this->periodYearFilter, function ($query) {
                $query->where('exam_period_year', (int) $this->periodYearFilter);
            });

        $questions = $questionsQuery
            ->orderByDesc('is_main')
            ->orderBy('cc_subject_id')
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.client.profile.sample-questions.index', [
            'setting' => $setting,
            'profileLabel' => $profile ? $this->profileLabel($profile) : null,
            'books' => $books,
            'questions' => $questions,
            'stats' => [
                'total' => (clone $baseQuery)->count(),
                'books' => $books->count(),
                'main' => (clone $baseQuery)->where('is_main', true)->count(),
                'timed' => (clone $baseQuery)->whereNotNull('duration_minutes')->count(),
            ],
            'periodMonths' => collect(ExamSampleQuestion::EXAM_PERIOD_MONTHS)
                ->map(fn (string $label, int $value) => ['id' => (string) $value, 'name' => $label])
                ->values(),
            'periodYears' => collect(array_reverse(ExamSampleQuestion::EXAM_PERIOD_YEARS))
                ->map(fn (int $year) => ['id' => (string) $year, 'name' => (string) $year])
                ->values(),
        ])->layout('layouts.client.app');
    }

    private function booksForSetting(ExamPlanningService $service, ExamPlanningSetting $setting)
    {
        $curriculum = $service->curriculumForSetting($setting);

        return $this->booksFromCurriculum($curriculum);
    }

    private function booksForProfile(ExamPlanningService $service, array $profile)
    {
        return $this->booksFromCurriculum($service->curriculumForProfile($profile));
    }

    private function booksFromCurriculum(array $curriculum)
    {
        return collect($curriculum['general_subjects'] ?? [])
            ->merge($curriculum['specialized_subjects'] ?? [])
            ->unique('id')
            ->sortBy('name')
            ->values()
            ->map(fn (array $subject) => [
                'id' => (int) $subject['id'],
                'name' => $subject['name'],
                'type' => $subject['type'] ?? null,
            ]);
    }

    private function settingMatchesProfile(ExamPlanningSetting $setting, array $profile): bool
    {
        $grade = (int) $profile['grade'];
        $field = $grade === 9 ? null : ($profile['field'] ?? null);

        return (int) $setting->grade === $grade
            && ($grade === 9 ? $setting->field === null : $setting->field === $field);
    }

    private function profileLabel(array $profile): string
    {
        $grade = (int) $profile['grade'];
        $field = $grade === 9 ? null : ($profile['field'] ?? null);
        $gradeLabel = ExamPlanningSetting::GRADE_LABELS[$grade] ?? 'پایه ' . $grade;
        $fieldLabel = $grade === 9
            ? 'بدون رشته'
            : (ExamPlanningSetting::FIELD_LABELS[$field] ?? 'بدون رشته');

        return $gradeLabel . ' / ' . $fieldLabel;
    }

    private function constrainSettingToProfile($query, array $profile): void
    {
        $grade = (int) $profile['grade'];
        $field = $grade === 9 ? null : ($profile['field'] ?? null);

        $query
            ->where('grade', $grade)
            ->when(
                $grade === 9,
                fn ($query) => $query->whereNull('field'),
                fn ($query) => $query->where('field', $field)
            );
    }
}
