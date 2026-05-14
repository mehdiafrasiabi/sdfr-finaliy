<?php

namespace App\Livewire\Manager\GradePrice;

use App\Models\GradePrice;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Index extends Component
{
    public bool   $showForm       = false;
    public ?int   $editingId      = null;

    public int    $grade          = 10;
    public string $field          = '';
    public string $label          = '';
    public int    $totalAmount    = 0;
    public int    $months         = 12;
    public int    $discountPct    = 0;
    public string $startAt        = '';
    public string $endAt          = '';
    public bool   $isActive       = true;

    protected function rules(): array
    {
        return [
            'grade'       => ['required', 'integer', 'in:9,10,11,12'],
            'field'       => ['nullable', 'in:,math,experimental,human'],
            'label'       => ['nullable', 'string', 'max:100'],
            'totalAmount' => ['required', 'integer', 'min:1'],
            'months'      => ['required', 'integer', 'min:1', 'max:36'],
            'discountPct' => ['required', 'integer', 'min:0', 'max:100'],
            'startAt'     => ['required', 'date'],
            'endAt'       => ['nullable', 'date', 'after_or_equal:startAt'],
            'isActive'    => ['boolean'],
        ];
    }

    protected array $messages = [
        'totalAmount.required' => 'مبلغ کل الزامی است.',
        'totalAmount.min'      => 'مبلغ کل باید بیشتر از صفر باشد.',
        'months.required'      => 'تعداد ماه الزامی است.',
        'startAt.required'     => 'تاریخ شروع الزامی است.',
        'endAt.after_or_equal' => 'تاریخ پایان باید بعد از تاریخ شروع باشد.',
    ];

    public function openCreate(): void
    {
        $this->resetForm();
        $this->startAt   = now()->toDateString();
        $this->showForm  = true;
        $this->editingId = null;
    }

    public function openEdit(int $id): void
    {
        $price = GradePrice::findOrFail($id);
        $this->editingId     = $id;
        $this->grade         = $price->grade;
        $this->field         = $price->field ?? '';
        $this->label         = $price->label ?? '';
        $this->totalAmount   = $price->total_amount;
        $this->months        = $price->months;
        $this->discountPct   = $price->discount_percentage;
        $this->startAt       = $price->start_at->toDateString();
        $this->endAt         = $price->end_at?->toDateString() ?? '';
        $this->isActive      = $price->is_active;
        $this->showForm      = true;
    }

    public function save(): void
    {
        $data = $this->validate();

        $payload = [
            'grade'               => $this->grade,
            'field'               => $this->field ?: null,
            'label'               => $this->label ?: null,
            'total_amount'        => $this->totalAmount,
            'months'              => $this->months,
            'discount_percentage' => $this->discountPct,
            'start_at'            => $this->startAt,
            'end_at'              => $this->endAt ?: null,
            'is_active'           => $this->isActive,
            'created_by'          => Auth::guard('admin')->id(),
        ];

        if ($this->editingId) {
            GradePrice::findOrFail($this->editingId)->update($payload);
            session()->flash('success', 'قیمت با موفقیت ویرایش شد.');
        } else {
            GradePrice::create($payload);
            session()->flash('success', 'قیمت جدید با موفقیت ثبت شد.');
        }

        $this->closeForm();
    }

    public function toggleActive(int $id): void
    {
        $price = GradePrice::findOrFail($id);
        $price->update(['is_active' => !$price->is_active]);
    }

    public function delete(int $id): void
    {
        GradePrice::findOrFail($id)->delete();
        session()->flash('success', 'قیمت حذف شد.');
    }

    public function closeForm(): void
    {
        $this->showForm  = false;
        $this->editingId = null;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->grade       = 10;
        $this->field       = '';
        $this->label       = '';
        $this->totalAmount = 0;
        $this->months      = 12;
        $this->discountPct = 0;
        $this->startAt     = '';
        $this->endAt       = '';
        $this->isActive    = true;
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        $prices = GradePrice::with('createdBy')
            ->orderBy('grade')
            ->orderByDesc('start_at')
            ->get();

        return view('livewire.manager.grade-price.index', compact('prices'))
            ->layout('layouts.manager.app');
    }
}
