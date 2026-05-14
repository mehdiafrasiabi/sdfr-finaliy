<?php

namespace App\Livewire\Manager\GradePrice;

use App\Models\GradePrice;
use App\Models\GradePriceDailyDiscount;
use Livewire\Component;

/**
 * D-2 — مدیریت تخفیف‌های روز-خاص یک رکورد قیمت پایه.
 */
class DailyDiscount extends Component
{
    public GradePrice $price;

    public bool $showForm   = false;
    public ?int $editingId  = null;

    public string $label              = '';
    public int    $discountPercentage = 10;
    public string $startsOn           = '';
    public string $endsOn             = '';
    public bool   $isActive           = true;

    public function mount(int $price): void
    {
        $this->price = GradePrice::findOrFail($price);
    }

    protected function rules(): array
    {
        return [
            'label'              => ['nullable', 'string', 'max:100'],
            'discountPercentage' => ['required', 'integer', 'min:1', 'max:100'],
            'startsOn'           => ['required', 'date'],
            'endsOn'             => ['required', 'date', 'after_or_equal:startsOn'],
            'isActive'           => ['boolean'],
        ];
    }

    protected array $messages = [
        'discountPercentage.required' => 'درصد تخفیف الزامی است.',
        'discountPercentage.min'      => 'درصد تخفیف باید حداقل ۱ باشد.',
        'discountPercentage.max'      => 'درصد تخفیف بیشتر از ۱۰۰ مجاز نیست.',
        'startsOn.required'           => 'تاریخ شروع الزامی است.',
        'endsOn.required'             => 'تاریخ پایان الزامی است.',
        'endsOn.after_or_equal'       => 'تاریخ پایان باید مساوی یا بعد از تاریخ شروع باشد.',
    ];

    public function openCreate(): void
    {
        $this->resetForm();
        $this->startsOn  = now()->toDateString();
        $this->endsOn    = now()->toDateString();
        $this->showForm  = true;
        $this->editingId = null;
    }

    public function openEdit(int $id): void
    {
        $d = GradePriceDailyDiscount::where('grade_price_id', $this->price->id)->findOrFail($id);
        $this->editingId           = $id;
        $this->label               = $d->label ?? '';
        $this->discountPercentage  = $d->discount_percentage;
        $this->startsOn            = $d->starts_on->toDateString();
        $this->endsOn              = $d->ends_on->toDateString();
        $this->isActive            = $d->is_active;
        $this->showForm            = true;
    }

    public function save(): void
    {
        $this->validate();

        $payload = [
            'grade_price_id'      => $this->price->id,
            'label'               => $this->label ?: null,
            'discount_percentage' => $this->discountPercentage,
            'starts_on'           => $this->startsOn,
            'ends_on'             => $this->endsOn,
            'is_active'           => $this->isActive,
        ];

        if ($this->editingId) {
            GradePriceDailyDiscount::where('grade_price_id', $this->price->id)
                ->findOrFail($this->editingId)
                ->update($payload);
            session()->flash('success', 'تخفیف با موفقیت ویرایش شد.');
        } else {
            GradePriceDailyDiscount::create($payload);
            session()->flash('success', 'تخفیف جدید با موفقیت ثبت شد.');
        }

        $this->closeForm();
    }

    public function toggleActive(int $id): void
    {
        $d = GradePriceDailyDiscount::where('grade_price_id', $this->price->id)->findOrFail($id);
        $d->update(['is_active' => ! $d->is_active]);
    }

    public function delete(int $id): void
    {
        GradePriceDailyDiscount::where('grade_price_id', $this->price->id)
            ->findOrFail($id)
            ->delete();
        session()->flash('success', 'تخفیف حذف شد.');
    }

    public function closeForm(): void
    {
        $this->showForm  = false;
        $this->editingId = null;
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->label              = '';
        $this->discountPercentage = 10;
        $this->startsOn           = '';
        $this->endsOn             = '';
        $this->isActive           = true;
    }

    public function render()
    {
        $discounts = $this->price->dailyDiscounts()->orderByDesc('starts_on')->get();

        return view('livewire.manager.grade-price.daily-discount', [
            'discounts' => $discounts,
        ])->layout('layouts.manager.app');
    }
}
