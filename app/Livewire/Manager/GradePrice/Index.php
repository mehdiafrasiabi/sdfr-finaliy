<?php

namespace App\Livewire\Manager\GradePrice;

use App\Models\CcGrade;
use App\Models\GradePricing;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public ?int $editingId = null;
    public ?int $cc_grade_id = null;
    public ?string $total_price = null;
    public ?string $discount_amount = '0';
    public ?string $starts_on = null;
    public ?string $ends_on = null;

    public bool $showForm = false;

    protected function rules(): array
    {
        return [
            'cc_grade_id'     => ['required', 'exists:cc_grades,id'],
            'total_price'     => ['required', 'integer', 'min:0'],
            'discount_amount' => ['nullable', 'integer', 'min:0'],
            'starts_on'       => ['required', 'date'],
            'ends_on'         => ['required', 'date', 'after:starts_on'],
        ];
    }

    protected array $validationAttributes = [
        'cc_grade_id'     => 'پایه تحصیلی',
        'total_price'     => 'قیمت کل',
        'discount_amount' => 'تخفیف ثابت پایه',
        'starts_on'       => 'تاریخ شروع',
        'ends_on'         => 'تاریخ پایان',
    ];

    public function openCreate(?int $gradeId = null): void
    {
        $this->reset(['editingId', 'cc_grade_id', 'total_price', 'discount_amount', 'starts_on', 'ends_on']);
        $this->discount_amount = '0';
        $this->cc_grade_id = $gradeId;
        $this->showForm = true;
    }

    public function edit(int $id): void
    {
        $p = GradePricing::findOrFail($id);
        $this->editingId       = $p->id;
        $this->cc_grade_id     = $p->cc_grade_id;
        $this->total_price     = (string) $p->total_price;
        $this->discount_amount = (string) $p->discount_amount;
        $this->starts_on       = $p->starts_on?->format('Y-m-d');
        $this->ends_on         = $p->ends_on?->format('Y-m-d');
        $this->showForm = true;
    }

    public function close(): void
    {
        $this->showForm = false;
        $this->reset(['editingId', 'cc_grade_id', 'total_price', 'discount_amount', 'starts_on', 'ends_on']);
        $this->discount_amount = '0';
    }

    public function save(): void
    {
        $data = $this->validate();

        DB::transaction(function () use ($data) {
            if ($this->editingId) {
                $pricing = GradePricing::findOrFail($this->editingId);
                $pricing->update($data + ['is_active' => true]);
            } else {
                GradePricing::where('cc_grade_id', $data['cc_grade_id'])
                    ->where('is_active', true)
                    ->update(['is_active' => false]);

                GradePricing::create($data + ['is_active' => true]);
            }
        });

        $this->dispatch('success', 'قیمت‌گذاری با موفقیت ذخیره شد.');
        $this->close();
    }

    public function deactivate(int $id): void
    {
        GradePricing::where('id', $id)->update(['is_active' => false]);
        $this->dispatch('success', 'قیمت‌گذاری غیرفعال شد.');
    }

    public function render()
    {
        $grades = CcGrade::with(['educationLevel'])
            ->where('is_active', true)
            ->orderBy('level_id')
            ->orderBy('name')
            ->get();

        $activePricings = GradePricing::where('is_active', true)
            ->get()
            ->keyBy('cc_grade_id');

        return view('livewire.manager.grade-price.index', [
            'grades'         => $grades,
            'activePricings' => $activePricings,
        ])->layout('layouts.manager.app');
    }
}
