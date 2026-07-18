<?php

namespace App\Livewire\Manager\StudentExams;

use App\Helpers\FileHelper;
use App\Models\ExamPlanningSetting;
use App\Models\ExamSampleQuestion;
use App\Services\ExamPlanningService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class SampleQuestions extends Component
{
    use WithFileUploads;

    public ExamPlanningSetting $setting;

    public string $subjectFilter = '';
    public ?int $editingId = null;
    public string $cc_subject_id = '';
    public int $exam_period_month = 3;
    public int $exam_period_year = 1405;
    public string $title = '';
    public int $duration_minutes = 30;
    public bool $is_main = true;
    public $file = null;

    public function mount(int $settingId): void
    {
        $this->setting = ExamPlanningSetting::query()->findOrFail($settingId);
        $this->exam_period_year = $this->defaultExamPeriodYear();
        $this->title = $this->generatedTitle();
        $this->normalizeMainFiles();
    }

    public function updatedExamPeriodMonth(): void
    {
        $this->title = $this->generatedTitle();
    }

    public function updatedExamPeriodYear(): void
    {
        $this->title = $this->generatedTitle();
    }

    public function save(): void
    {
        $allowedSubjectIds = $this->allowedSubjectIds();

        $this->validate([
            'cc_subject_id' => ['required', 'integer', Rule::in($allowedSubjectIds)],
            'exam_period_month' => ['required', 'integer', Rule::in(array_keys(ExamSampleQuestion::EXAM_PERIOD_MONTHS))],
            'exam_period_year' => ['required', 'integer', Rule::in(ExamSampleQuestion::EXAM_PERIOD_YEARS)],
            'title' => ['required', 'string', 'max:200'],
            'duration_minutes' => ['required', 'integer', 'min:1', 'max:720'],
            'file' => [$this->editingId ? 'nullable' : 'required', 'file', 'mimes:pdf', 'max:20480'],
            'is_main' => ['boolean'],
        ], [
            'cc_subject_id.required' => 'کتاب را انتخاب کنید.',
            'cc_subject_id.in' => 'کتاب انتخاب‌شده برای این پایه و رشته معتبر نیست.',
            'exam_period_month.required' => 'زمان امتحان را انتخاب کنید.',
            'exam_period_year.required' => 'سال امتحان را انتخاب کنید.',
            'title.required' => 'عنوان نمونه سوال الزامی است.',
            'duration_minutes.required' => 'مدت زمان آزمون الزامی است.',
            'file.required' => 'فایل PDF الزامی است.',
            'file.mimes' => 'فقط فایل PDF مجاز است.',
            'file.max' => 'حجم فایل نباید بیشتر از 20 مگابایت باشد.',
        ]);

        $subjectId = (int) $this->cc_subject_id;
        $question = $this->editingId ? $this->questionForSetting($this->editingId) : null;
        $oldSubjectId = $question ? (int) $question->cc_subject_id : null;
        $requestedMain = (bool) $this->is_main;
        $shouldBeMain = $requestedMain || ! $this->mainExistsForSubject($subjectId, $question?->id);
        $keptMainBecauseSingle = ! $requestedMain && $shouldBeMain;

        DB::transaction(function () use ($subjectId, $oldSubjectId, $shouldBeMain, $question) {
            if ($shouldBeMain) {
                $this->clearMainForSubject($subjectId, $question?->id);
            }

            $payload = [
                'exam_planning_setting_id' => $this->setting->id,
                'cc_subject_id' => $subjectId,
                'title' => trim($this->title),
                'duration_minutes' => (int) $this->duration_minutes,
                'exam_period_month' => (int) $this->exam_period_month,
                'exam_period_year' => (int) $this->exam_period_year,
                'is_main' => $shouldBeMain,
            ];

            if ($this->file) {
                $payload['pdf_path'] = FileHelper::uploadToPublicHtml(
                    $this->file,
                    'exam-sample-questions/setting-' . $this->setting->id,
                    true
                );
            }

            if ($question) {
                $oldPdfQuestion = $this->file ? $question->replicate() : null;

                $question->update($payload);

                if ($oldPdfQuestion) {
                    $this->deletePhysicalFile($oldPdfQuestion);
                }

                $this->ensureSingleMainForSubject($subjectId, $question->id);

                if ($oldSubjectId && $oldSubjectId !== $subjectId) {
                    $this->ensureSingleMainForSubject($oldSubjectId);
                }

                return;
            }

            $payload['sort_order'] = $this->nextSortOrder();
            $createdQuestion = ExamSampleQuestion::query()->create($payload);
            $this->ensureSingleMainForSubject($subjectId, $createdQuestion->id);
        });

        if ($keptMainBecauseSingle) {
            $this->dispatch('warning', 'برای هر درس باید یک فایل اصلی باقی بماند. اگر می‌خواهید فایل دیگری اصلی شود، همان فایل را اصلی کنید.');
        } else {
            $this->dispatch('success', $this->editingId ? 'نمونه سوال با موفقیت ویرایش شد.' : 'نمونه سوال با موفقیت ذخیره شد.');
        }

        $this->resetUploadForm();
    }

    public function editQuestion(int $questionId): void
    {
        $question = $this->questionForSetting($questionId);

        $this->editingId = $question->id;
        $this->cc_subject_id = (string) $question->cc_subject_id;
        $this->exam_period_month = (int) ($question->exam_period_month ?: 3);
        $this->exam_period_year = (int) ($question->exam_period_year ?: $this->defaultExamPeriodYear());
        $this->title = $question->title;
        $this->duration_minutes = (int) $question->duration_minutes;
        $this->is_main = (bool) $question->is_main;
        $this->file = null;
        $this->resetValidation();
    }

    public function cancelEdit(): void
    {
        $this->resetUploadForm();
    }

    public function makeMain(int $questionId): void
    {
        $question = $this->questionForSetting($questionId);
        $subjectId = (int) $question->cc_subject_id;

        DB::transaction(function () use ($question, $subjectId) {
            $this->clearMainForSubject($subjectId);
            $question->update(['is_main' => true]);
            $this->ensureSingleMainForSubject($subjectId, $question->id);
        });

        $this->dispatch('success', 'فایل اصلی این کتاب به‌روزرسانی شد.');
    }

    public function deleteQuestion(int $questionId): void
    {
        $question = $this->questionForSetting($questionId);

        $subjectId = (int) $question->cc_subject_id;

        DB::transaction(function () use ($question, $subjectId) {
            $this->deletePhysicalFile($question);
            $question->delete();
            $this->ensureSingleMainForSubject($subjectId);
        });

        $this->dispatch('success', 'نمونه سوال حذف شد.');
    }

    public function render(ExamPlanningService $service): View
    {
        $subjects = $this->subjects($service);

        $baseQuery = ExamSampleQuestion::query()
            ->where('exam_planning_setting_id', $this->setting->id);

        $questions = (clone $baseQuery)
            ->with('subject')
            ->when($this->subjectFilter, fn ($query) => $query->where('cc_subject_id', (int) $this->subjectFilter))
            ->orderBy('cc_subject_id')
            ->orderByDesc('is_main')
            ->orderByDesc('exam_period_year')
            ->orderBy('exam_period_month')
            ->orderByDesc('id')
            ->get();

        return view('livewire.manager.student-exams.sample-questions', [
            'subjects' => $subjects,
            'questions' => $questions,
            'monthOptions' => ExamSampleQuestion::EXAM_PERIOD_MONTHS,
            'yearOptions' => array_reverse(ExamSampleQuestion::EXAM_PERIOD_YEARS),
            'stats' => [
                'total' => (clone $baseQuery)->count(),
                'books' => $subjects->count(),
                'main' => (clone $baseQuery)->where('is_main', true)->count(),
                'categorized' => (clone $baseQuery)
                    ->whereNotNull('exam_period_month')
                    ->whereNotNull('exam_period_year')
                    ->count(),
            ],
        ])->layout('layouts.manager.app');
    }

    private function generatedTitle(): string
    {
        return ExamSampleQuestion::defaultTitle((int) $this->exam_period_month, (int) $this->exam_period_year);
    }

    private function defaultExamPeriodYear(): int
    {
        $year = (int) jdate(now())->format('Y');

        return max(1398, min(1405, $year));
    }

    private function resetUploadForm(): void
    {
        $this->editingId = null;
        $this->cc_subject_id = '';
        $this->title = $this->generatedTitle();
        $this->duration_minutes = 30;
        $this->is_main = true;
        $this->file = null;
        $this->resetValidation();
    }

    private function subjects(?ExamPlanningService $service = null)
    {
        $curriculum = ($service ?? app(ExamPlanningService::class))->curriculumForSetting($this->setting);

        return collect($curriculum['general_subjects'] ?? [])
            ->map(fn (array $subject) => [
                'id' => (int) $subject['id'],
                'name' => $subject['name'],
                'type' => 'general',
            ])
            ->concat(
                collect($curriculum['specialized_subjects'] ?? [])
                    ->map(fn (array $subject) => [
                        'id' => (int) $subject['id'],
                        'name' => $subject['name'],
                        'type' => 'specialized',
                    ])
            )
            ->unique('id')
            ->sortBy('name')
            ->values();
    }

    private function allowedSubjectIds(): array
    {
        return $this->subjects()
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    private function mainExistsForSubject(int $subjectId, ?int $exceptQuestionId = null): bool
    {
        return ExamSampleQuestion::query()
            ->where('exam_planning_setting_id', $this->setting->id)
            ->where('cc_subject_id', $subjectId)
            ->where('is_main', true)
            ->when($exceptQuestionId, fn ($query) => $query->whereKeyNot($exceptQuestionId))
            ->exists();
    }

    private function clearMainForSubject(int $subjectId, ?int $exceptQuestionId = null): void
    {
        ExamSampleQuestion::query()
            ->where('exam_planning_setting_id', $this->setting->id)
            ->where('cc_subject_id', $subjectId)
            ->where('is_main', true)
            ->when($exceptQuestionId, fn ($query) => $query->whereKeyNot($exceptQuestionId))
            ->update(['is_main' => false]);
    }

    private function ensureSingleMainForSubject(int $subjectId, ?int $preferredQuestionId = null): void
    {
        $questions = ExamSampleQuestion::query()
            ->where('exam_planning_setting_id', $this->setting->id)
            ->where('cc_subject_id', $subjectId)
            ->when(
                $preferredQuestionId,
                fn ($query) => $query->orderByRaw('id = ? desc', [$preferredQuestionId]),
                fn ($query) => $query->orderByDesc('is_main')
            )
            ->orderByDesc('id')
            ->get();

        if ($questions->isEmpty()) {
            return;
        }

        $mainQuestion = $questions->firstWhere('is_main', true) ?? $questions->first();

        ExamSampleQuestion::query()
            ->whereKey($mainQuestion->id)
            ->update(['is_main' => true]);

        ExamSampleQuestion::query()
            ->where('exam_planning_setting_id', $this->setting->id)
            ->where('cc_subject_id', $subjectId)
            ->whereKeyNot($mainQuestion->id)
            ->where('is_main', true)
            ->update(['is_main' => false]);
    }

    private function nextSortOrder(): int
    {
        return ((int) ExamSampleQuestion::query()
            ->where('exam_planning_setting_id', $this->setting->id)
            ->max('sort_order')) + 1;
    }

    private function questionForSetting(int $questionId): ExamSampleQuestion
    {
        return ExamSampleQuestion::query()
            ->where('exam_planning_setting_id', $this->setting->id)
            ->findOrFail($questionId);
    }

    private function normalizeMainFiles(): void
    {
        ExamSampleQuestion::query()
            ->where('exam_planning_setting_id', $this->setting->id)
            ->where('is_main', true)
            ->orderByDesc('id')
            ->get()
            ->groupBy('cc_subject_id')
            ->each(function ($questions) {
                $questions->skip(1)->each(fn (ExamSampleQuestion $question) => $question->update(['is_main' => false]));
            });
    }

    private function deletePhysicalFile(ExamSampleQuestion $question): void
    {
        $root = realpath(base_path('public_html'));
        $path = base_path('public_html/' . ltrim((string) $question->pdf_path, '/'));
        $realPath = realpath($path);

        $isInsidePublicHtml = $root
            && $realPath
            && ($realPath === $root || str_starts_with($realPath, $root . DIRECTORY_SEPARATOR));

        if ($isInsidePublicHtml && File::isFile($realPath)) {
            File::delete($realPath);
        }
    }
}
