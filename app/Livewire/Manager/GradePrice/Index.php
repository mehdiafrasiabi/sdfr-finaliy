<?php

namespace App\Livewire\Manager\GradePrice;

use App\Models\GradePrice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

/**
 * قیمت‌گذاری «ماه ورود و تخفیف» — به‌ازای هر پایه (۹/۱۰/۱۱/۱۲) یک رکورد:
 *   - نرخ ماهانه (monthly_rate)
 *   - درصد پیش‌پرداخت (initial_percentage، پیش‌فرض ۳۰)
 *   - سالِ خدمت (سال شمسیِ تیر) → start_at = تیر۱، end_at = پایان خرداد سالِ بعد.
 *
 * پس از ذخیره، صفحهٔ Show باز می‌شود تا تخفیف زودهنگامِ هر ماه تنظیم شود.
 */
class Index extends Component
{
    public bool $showForm  = false;
    public ?int $editingId = null;

    public int    $grade             = 12;
    public int    $basePrice         = 19800000; // قیمت خام سالانه (قبل از تخفیف)
    public int    $initialPercentage = 30;
    public int    $serviceYear       = 0;   // سال شمسیِ تیر، مثل ۱۴۰۵
    public bool   $isActive          = true;

    public function mount(): void
    {
        // پیش‌فرض: سالِ خدمتِ جاری (اگر بعد از خرداد هستیم همین سال، وگرنه سال قبل).
        $now = Jalalian::now();
        $this->serviceYear = (int) $now->getMonth() >= 4 ? (int) $now->getYear() : (int) $now->getYear() - 1;
    }

    protected function rules(): array
    {
        return [
            'grade' => [
                'required', 'integer', 'in:9,10,11,12',
                Rule::unique('grade_prices', 'grade')->ignore($this->editingId),
            ],
            'basePrice'         => ['required', 'integer', 'min:1'],
            'initialPercentage' => ['required', 'integer', 'min:0', 'max:100'],
            'serviceYear'       => ['required', 'integer', 'min:1390', 'max:1450'],
            'isActive'          => ['boolean'],
        ];
    }

    protected array $messages = [
        'grade.unique'              => 'برای این پایه قبلاً قیمت تعریف شده است.',
        'basePrice.required'        => 'قیمت خام سالانه الزامی است.',
        'basePrice.min'             => 'قیمت خام باید بیشتر از صفر باشد.',
        'initialPercentage.required'=> 'درصد پیش‌پرداخت الزامی است.',
        'serviceYear.required'      => 'سال خدمت الزامی است.',
    ];

    public function openCreate(): void
    {
        $this->resetForm();
        $this->showForm  = true;
        $this->editingId = null;
    }

    public function openEdit(int $id): void
    {
        $price = GradePrice::findOrFail($id);
        $this->editingId         = $id;
        $this->grade             = (int) $price->grade;
        $this->basePrice         = $price->basePrice();
        $this->initialPercentage = (int) ($price->initial_percentage ?? 30);
        $this->serviceYear       = $price->serviceYear() ?? $this->serviceYear;
        $this->isActive          = (bool) $price->is_active;
        $this->showForm          = true;
        $this->resetErrorBag();
    }

    public function save(): void
    {
        $this->validate();

        $startAt = Jalalian::fromFormat('Y/m/d', sprintf('%d/04/01', $this->serviceYear))->toCarbon();
        $endAt   = Jalalian::fromFormat('Y/m/d', sprintf('%d/03/31', $this->serviceYear + 1))->toCarbon();

        $payload = [
            'grade'              => $this->grade,
            'base_price'         => $this->basePrice,
            'monthly_rate'       => (int) round($this->basePrice / GradePrice::SERVICE_MONTH_COUNT),
            'initial_percentage' => $this->initialPercentage,
            'total_amount'       => $this->basePrice, // ستون قدیمی NOT NULL — قیمت خام
            'start_at'           => $startAt->toDateString(),
            'end_at'             => $endAt->toDateString(),
            'is_active'          => $this->isActive,
            'created_by'         => Auth::guard('admin')->id() ?? Auth::guard('manager')->id() ?? Auth::id(),
        ];

        if ($this->editingId) {
            GradePrice::findOrFail($this->editingId)->update($payload);
            $id = $this->editingId;
            session()->flash('success', 'قیمت با موفقیت ویرایش شد.');
        } else {
            $price = GradePrice::create($payload);
            $id = $price->id;
            session()->flash('success', 'قیمت جدید ثبت شد. اکنون تخفیف هر ماه را تنظیم کنید.');
        }

        $this->closeForm();
        $this->redirectRoute('manager.grade-price.show', ['price' => $id], navigate: true);
    }

    public function toggleActive(int $id): void
    {
        $price = GradePrice::findOrFail($id);
        $price->update(['is_active' => ! $price->is_active]);
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
        $this->grade             = 12;
        $this->basePrice         = 19800000;
        $this->initialPercentage = 30;
        $this->isActive          = true;
        $this->resetErrorBag();
    }

    public function render()
    {
        $prices = GradePrice::with('createdBy')
            ->orderBy('grade')
            ->get();

        return view('livewire.manager.grade-price.index', [
            'prices' => $prices,
        ])->layout('layouts.manager.app');
    }
}
