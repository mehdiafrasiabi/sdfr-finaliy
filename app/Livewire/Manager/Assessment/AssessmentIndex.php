<?php

namespace App\Livewire\Manager\Assessment;

use App\Models\Assessment;
use Livewire\Component;
use Livewire\WithPagination;

class AssessmentIndex extends Component
{
    use WithPagination;

    public string $filterName = '';

    // create/edit modal state
    public bool $showForm = false;
    public ?int $editingId = null;
    public string $f_slug = '';
    public string $f_name_fa = '';
    public string $f_description_fa = '';
    public string $f_kind = Assessment::KIND_CUSTOM;
    public string $f_question_type = 'mixed';
    public string $f_audience = Assessment::AUDIENCE_STUDENT;
    public int $f_display_order = 0;
    public bool $f_is_active = true;
    public bool $f_is_required = true;
    public ?int $f_expected_count = null;

    public function updatingFilterName(): void
    {
        $this->resetPage();
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $a = Assessment::findOrFail($id);
        $this->editingId = $a->id;
        $this->f_slug = $a->slug;
        $this->f_name_fa = $a->name_fa;
        $this->f_description_fa = $a->description_fa ?? '';
        $this->f_kind = $a->kind;
        $this->f_question_type = $a->question_type;
        $this->f_audience = $a->audience;
        $this->f_display_order = $a->display_order;
        $this->f_is_active = (bool) $a->is_active;
        $this->f_is_required = (bool) $a->is_required;
        $this->f_expected_count = $a->expected_question_count;
        $this->showForm = true;
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function save(): void
    {
        $this->validate([
            'f_slug'          => ['required', 'string', 'max:80', 'regex:/^[a-z0-9\-]+$/'],
            'f_name_fa'       => ['required', 'string', 'max:150'],
            'f_kind'          => ['required', 'in:mbti,vark,custom'],
            'f_question_type' => ['required', 'in:mbti_binary,vark_multi,mixed,likert5,yes_no'],
            'f_audience'      => ['required', 'in:student'],
            'f_display_order' => ['required', 'integer', 'min:0'],
        ], [
            'f_slug.regex' => 'slug فقط می‌تواند شامل حروف انگلیسی کوچک، عدد و خط تیره باشد.',
        ]);

        $data = [
            'slug'                    => $this->f_slug,
            'name_fa'                 => $this->f_name_fa,
            'description_fa'          => $this->f_description_fa ?: null,
            'kind'                    => $this->f_kind,
            'question_type'           => $this->f_question_type,
            'audience'                => $this->f_audience,
            'display_order'           => $this->f_display_order,
            'is_active'               => $this->f_is_active,
            'is_required'             => $this->f_is_required,
            'expected_question_count' => $this->f_expected_count,
        ];

        if ($this->editingId) {
            Assessment::where('id', $this->editingId)->update($data);
            session()->flash('success', 'آزمون با موفقیت ویرایش شد.');
        } else {
            Assessment::create($data);
            session()->flash('success', 'آزمون با موفقیت ایجاد شد.');
        }

        $this->closeForm();
    }

    public function toggleActive(int $id): void
    {
        $a = Assessment::findOrFail($id);
        $a->update(['is_active' => ! $a->is_active]);
        session()->flash('success', $a->is_active ? 'آزمون فعال شد.' : 'آزمون غیرفعال شد.');
    }

    public function delete(int $id): void
    {
        $a = Assessment::findOrFail($id);
        if ($a->attempts()->exists()) {
            session()->flash('error', 'این آزمون توسط دانش‌آموزانی پاسخ داده شده و قابل حذف نیست. می‌توانید آن را غیرفعال کنید.');
            return;
        }
        $a->delete();
        session()->flash('success', 'آزمون حذف شد.');
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->f_slug = '';
        $this->f_name_fa = '';
        $this->f_description_fa = '';
        $this->f_kind = Assessment::KIND_CUSTOM;
        $this->f_question_type = 'mixed';
        $this->f_audience = Assessment::AUDIENCE_STUDENT;
        $this->f_display_order = 0;
        $this->f_is_active = true;
        $this->f_is_required = true;
        $this->f_expected_count = null;
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $query = Assessment::query()
            ->withCount('questions')
            ->ordered();

        if ($this->filterName !== '') {
            $query->where(function ($q) {
                $q->where('name_fa', 'like', "%{$this->filterName}%")
                    ->orWhere('slug', 'like', "%{$this->filterName}%");
            });
        }

        $assessments = $query->paginate(15);

        return view('livewire.manager.assessment.assessment-index', [
            'assessments' => $assessments,
        ])->layout('layouts.manager.app');
    }
}
