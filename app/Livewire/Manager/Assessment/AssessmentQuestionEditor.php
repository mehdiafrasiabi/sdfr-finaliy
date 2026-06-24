<?php

namespace App\Livewire\Manager\Assessment;

use App\Models\Assessment;
use App\Models\AssessmentQuestion;
use App\Models\AssessmentQuestionOption;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class AssessmentQuestionEditor extends Component
{
    public Assessment $assessment;

    public bool $showForm = false;
    public ?int $editingId = null;

    public string $f_text = '';
    public string $f_type = AssessmentQuestion::TYPE_LIKERT5;
    public int $f_order = 1;
    public bool $f_is_active = true;

    // MBTI meta
    public string $f_mbti_axis = 'EI';
    public string $f_mbti_a_pole = 'E';
    public string $f_mbti_b_pole = 'I';

    // Custom meta
    public string $f_facet = '';
    public bool $f_reverse = false;

    // Options editor — array of {label, value, weight_v, weight_a, weight_r, weight_k}
    public array $options = [];

    // ویرایش تفسیر داینامیک (ستون JSON «interpretation»)
    public bool $showInterpretation = false;
    public string $interpretationJson = '';

    public function mount(int $assessment): void
    {
        $this->assessment = Assessment::with('questions')->findOrFail($assessment);
        $this->loadInterpretationJson();
    }

    private function loadInterpretationJson(): void
    {
        $data = $this->assessment->interpretation;
        $this->interpretationJson = $data
            ? json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)
            : '';
    }

    public function toggleInterpretation(): void
    {
        $this->showInterpretation = ! $this->showInterpretation;
        if ($this->showInterpretation) {
            $this->loadInterpretationJson();
        }
    }

    /**
     * ذخیرهٔ JSON تفسیر — اگر JSON نامعتبر باشد ذخیره نمی‌شود و خطا می‌دهد.
     * مقدار خالی یعنی حذف تفسیر (null) که سیستم آن را با کارنامهٔ خام مدیریت می‌کند.
     */
    public function saveInterpretation(): void
    {
        $raw = trim($this->interpretationJson);

        if ($raw === '') {
            $this->assessment->update(['interpretation' => null]);
            session()->flash('success', 'تفسیر حذف شد (خالی).');
            return;
        }

        $decoded = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE || ! is_array($decoded)) {
            $this->addError('interpretationJson', 'JSON نامعتبر است: ' . json_last_error_msg());
            return;
        }

        $this->resetErrorBag('interpretationJson');
        $this->assessment->update(['interpretation' => $decoded]);
        $this->loadInterpretationJson();
        session()->flash('success', 'تفسیر آزمون با موفقیت ذخیره شد.');
    }

    public function openCreate(): void
    {
        $this->resetForm();
        $this->f_order = ($this->assessment->questions()->max('order') ?? 0) + 1;
        $this->seedDefaultOptions();
        $this->showForm = true;
    }

    public function openEdit(int $id): void
    {
        $q = AssessmentQuestion::with('options')->findOrFail($id);
        $this->editingId = $q->id;
        $this->f_text = $q->question_text_fa;
        $this->f_type = $q->type;
        $this->f_order = $q->order;
        $this->f_is_active = (bool) $q->is_active;

        $meta = $q->scoring_meta ?? [];
        $this->f_mbti_axis = $meta['axis'] ?? 'EI';
        $this->f_mbti_a_pole = $meta['a_pole'] ?? 'E';
        $this->f_mbti_b_pole = $meta['b_pole'] ?? 'I';
        $this->f_facet = $meta['facet'] ?? '';
        $this->f_reverse = (bool) ($meta['reverse'] ?? false);

        $this->options = $q->options->map(fn ($o) => [
            'id'       => $o->id,
            'label'    => $o->label_fa,
            'value'    => $o->value,
            'order'    => $o->order,
            'weight_v' => $o->weights['V'] ?? 0,
            'weight_a' => $o->weights['A'] ?? 0,
            'weight_r' => $o->weights['R'] ?? 0,
            'weight_k' => $o->weights['K'] ?? 0,
        ])->toArray();

        if (empty($this->options)) {
            $this->seedDefaultOptions();
        }

        $this->showForm = true;
    }

    public function updatedFType(): void
    {
        $this->seedDefaultOptions();
    }

    private function seedDefaultOptions(): void
    {
        switch ($this->f_type) {
            case AssessmentQuestion::TYPE_LIKERT5:
                $labels = ['کاملاً مخالفم', 'مخالفم', 'بی‌نظرم', 'موافقم', 'کاملاً موافقم'];
                $this->options = [];
                foreach ($labels as $i => $l) {
                    $this->options[] = [
                        'id' => null, 'label' => $l, 'value' => (string) ($i + 1), 'order' => $i + 1,
                        'weight_v' => 0, 'weight_a' => 0, 'weight_r' => 0, 'weight_k' => 0,
                    ];
                }
                break;
            case AssessmentQuestion::TYPE_YES_NO:
                $this->options = [
                    ['id' => null, 'label' => 'بله', 'value' => 'yes', 'order' => 1, 'weight_v'=>0,'weight_a'=>0,'weight_r'=>0,'weight_k'=>0],
                    ['id' => null, 'label' => 'خیر', 'value' => 'no',  'order' => 2, 'weight_v'=>0,'weight_a'=>0,'weight_r'=>0,'weight_k'=>0],
                ];
                break;
            case AssessmentQuestion::TYPE_MBTI_BINARY:
                $this->options = [
                    ['id' => null, 'label' => 'گزینه الف', 'value' => 'A', 'order' => 1, 'weight_v'=>0,'weight_a'=>0,'weight_r'=>0,'weight_k'=>0],
                    ['id' => null, 'label' => 'گزینه ب',  'value' => 'B', 'order' => 2, 'weight_v'=>0,'weight_a'=>0,'weight_r'=>0,'weight_k'=>0],
                ];
                break;
            case AssessmentQuestion::TYPE_VARK_MULTI:
                $this->options = [
                    ['id' => null, 'label' => 'گزینه دیداری',   'value' => 'V', 'order' => 1, 'weight_v'=>1,'weight_a'=>0,'weight_r'=>0,'weight_k'=>0],
                    ['id' => null, 'label' => 'گزینه شنیداری',  'value' => 'A', 'order' => 2, 'weight_v'=>0,'weight_a'=>1,'weight_r'=>0,'weight_k'=>0],
                    ['id' => null, 'label' => 'گزینه خواندنی',  'value' => 'R', 'order' => 3, 'weight_v'=>0,'weight_a'=>0,'weight_r'=>1,'weight_k'=>0],
                    ['id' => null, 'label' => 'گزینه حرکتی',    'value' => 'K', 'order' => 4, 'weight_v'=>0,'weight_a'=>0,'weight_r'=>0,'weight_k'=>1],
                ];
                break;
        }
    }

    public function addOption(): void
    {
        $this->options[] = [
            'id' => null, 'label' => '', 'value' => '', 'order' => count($this->options) + 1,
            'weight_v' => 0, 'weight_a' => 0, 'weight_r' => 0, 'weight_k' => 0,
        ];
    }

    public function removeOption(int $index): void
    {
        unset($this->options[$index]);
        $this->options = array_values($this->options);
    }

    public function save(): void
    {
        $this->validate([
            'f_text'  => ['required', 'string'],
            'f_type'  => ['required', 'in:likert5,yes_no,mbti_binary,vark_multi'],
            'f_order' => ['required', 'integer', 'min:1'],
        ]);

        if (count($this->options) < 2) {
            $this->addError('options', 'حداقل دو گزینه لازم است.');
            return;
        }

        $meta = null;
        if ($this->f_type === AssessmentQuestion::TYPE_MBTI_BINARY) {
            $meta = [
                'axis'   => $this->f_mbti_axis,
                'a_pole' => $this->f_mbti_a_pole,
                'b_pole' => $this->f_mbti_b_pole,
            ];
        } elseif (in_array($this->f_type, [AssessmentQuestion::TYPE_LIKERT5, AssessmentQuestion::TYPE_YES_NO], true)) {
            $meta = array_filter([
                'facet'   => $this->f_facet ?: null,
                'reverse' => $this->f_reverse ?: null,
            ], fn ($v) => $v !== null);
            if (empty($meta)) {
                $meta = null;
            }
        }

        DB::transaction(function () use ($meta) {
            $payload = [
                'assessment_id'    => $this->assessment->id,
                'order'            => $this->f_order,
                'question_text_fa' => $this->f_text,
                'type'             => $this->f_type,
                'scoring_meta'     => $meta,
                'is_active'        => $this->f_is_active,
            ];

            if ($this->editingId) {
                $q = AssessmentQuestion::findOrFail($this->editingId);
                $q->update($payload);
            } else {
                $q = AssessmentQuestion::create($payload);
            }

            // sync options: حذف option های قبلی که در فرم نیامده‌اند
            $keptIds = collect($this->options)->pluck('id')->filter()->all();
            $q->options()->whereNotIn('id', $keptIds)->delete();

            foreach ($this->options as $i => $opt) {
                if (trim((string) $opt['label']) === '' || trim((string) $opt['value']) === '') {
                    continue;
                }
                $weights = array_filter([
                    'V' => (int) $opt['weight_v'],
                    'A' => (int) $opt['weight_a'],
                    'R' => (int) $opt['weight_r'],
                    'K' => (int) $opt['weight_k'],
                ], fn ($w) => $w !== 0);
                $data = [
                    'question_id' => $q->id,
                    'order'       => (int) ($opt['order'] ?? ($i + 1)),
                    'label_fa'    => $opt['label'],
                    'value'       => $opt['value'],
                    'weights'     => $weights ?: null,
                ];
                if (!empty($opt['id'])) {
                    AssessmentQuestionOption::where('id', $opt['id'])->update($data);
                } else {
                    AssessmentQuestionOption::create($data);
                }
            }
        });

        session()->flash('success', 'سوال با موفقیت ذخیره شد.');
        $this->closeForm();
    }

    public function deleteQuestion(int $id): void
    {
        $q = AssessmentQuestion::findOrFail($id);
        $hasAnswers = \App\Models\StudentAssessmentAnswer::where('question_id', $id)->exists();
        if ($hasAnswers) {
            session()->flash('error', 'این سوال پاسخ‌هایی دارد؛ غیرفعال کنید به جای حذف.');
            return;
        }
        $q->delete();
        session()->flash('success', 'سوال حذف شد.');
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->f_text = '';
        $this->f_type = AssessmentQuestion::TYPE_LIKERT5;
        $this->f_order = 1;
        $this->f_is_active = true;
        $this->f_mbti_axis = 'EI';
        $this->f_mbti_a_pole = 'E';
        $this->f_mbti_b_pole = 'I';
        $this->f_facet = '';
        $this->f_reverse = false;
        $this->options = [];
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $questions = $this->assessment->questions()->with('options')->orderBy('order')->get();

        return view('livewire.manager.assessment.assessment-question-editor', [
            'questions' => $questions,
        ])->layout('layouts.manager.app');
    }
}
