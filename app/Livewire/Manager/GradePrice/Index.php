<?php

namespace App\Livewire\Manager\GradePrice;

use App\Models\GradePrice;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

/**
 * D-2 (بازطراحی) — قیمت‌گذاری پایه‌ای.
 *
 * مدیر برای هر پایه (۹/۱۰/۱۱/۱۲) یک رکورد قیمت ثبت می‌کند:
 *   - مبلغ کل (تومان)
 *   - تاریخ شروع و پایان (شمسی، توسط jalalidatepicker)
 *   - وضعیت فعال/غیرفعال
 *
 * هر پایه فقط یک رکورد می‌تواند داشته باشد. پس از ذخیره، صفحهٔ Show باز می‌شود
 * تا مدیر برای هر ماه تخفیف خاص یا تخفیف روز خاص تعریف کند.
 */
class Index extends Component
{
    public bool $showForm   = false;
    public ?int $editingId  = null;

    public int    $grade        = 12;
    public int    $totalAmount  = 0;
    public string $startAtJ     = ''; // شمسی yyyy/mm/dd
    public string $endAtJ       = '';
    public bool   $isActive     = true;

    protected function rules(): array
    {
        return [
            'grade' => [
                'required', 'integer', 'in:9,10,11,12',
                Rule::unique('grade_prices', 'grade')->ignore($this->editingId),
            ],
            'totalAmount' => ['required', 'integer', 'min:1'],
            'startAtJ'    => ['required', 'string', 'regex:/^\d{4}\/\d{2}\/\d{2}$/'],
            'endAtJ'      => ['required', 'string', 'regex:/^\d{4}\/\d{2}\/\d{2}$/'],
            'isActive'    => ['boolean'],
        ];
    }

    protected array $messages = [
        'grade.unique'        => 'برای این پایه قبلاً قیمت تعریف شده است.',
        'totalAmount.required'=> 'مبلغ کل الزامی است.',
        'totalAmount.min'     => 'مبلغ کل باید بیشتر از صفر باشد.',
        'startAtJ.required'   => 'تاریخ شروع الزامی است.',
        'startAtJ.regex'      => 'فرمت تاریخ شروع باید yyyy/mm/dd شمسی باشد.',
        'endAtJ.required'     => 'تاریخ پایان الزامی است.',
        'endAtJ.regex'        => 'فرمت تاریخ پایان باید yyyy/mm/dd شمسی باشد.',
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
        $this->editingId   = $id;
        $this->grade       = (int) $price->grade;
        $this->totalAmount = (int) $price->total_amount;
        $this->startAtJ    = Jalalian::fromCarbon($price->start_at)->format('Y/m/d');
        $this->endAtJ      = $price->end_at
            ? Jalalian::fromCarbon($price->end_at)->format('Y/m/d')
            : '';
        $this->isActive    = (bool) $price->is_active;
        $this->showForm    = true;
    }

    public function save(): void
    {
        $this->validate();

        $startAt = $this->jalaliToCarbon($this->startAtJ);
        $endAt   = $this->jalaliToCarbon($this->endAtJ);

        if (! $startAt || ! $endAt) {
            $this->addError('startAtJ', 'تبدیل تاریخ شمسی به میلادی ناموفق بود.');
            return;
        }

        if ($endAt->lessThan($startAt)) {
            $this->addError('endAtJ', 'تاریخ پایان باید پس از تاریخ شروع باشد.');
            return;
        }

        $payload = [
            'grade'        => $this->grade,
            'total_amount' => $this->totalAmount,
            'start_at'     => $startAt->toDateString(),
            'end_at'       => $endAt->toDateString(),
            'is_active'    => $this->isActive,
            'created_by'   => Auth::guard('admin')->id() ?? Auth::id(),
        ];

        if ($this->editingId) {
            GradePrice::findOrFail($this->editingId)->update($payload);
            $id = $this->editingId;
            session()->flash('success', 'قیمت با موفقیت ویرایش شد.');
        } else {
            $price = GradePrice::create($payload);
            $id = $price->id;
            session()->flash('success', 'قیمت جدید با موفقیت ثبت شد.');
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
        $this->grade       = 12;
        $this->totalAmount = 0;
        $this->startAtJ    = '';
        $this->endAtJ      = '';
        $this->isActive    = true;
    }

    /**
     * تبدیل yyyy/mm/dd شمسی به Carbon میلادی.
     */
    private function jalaliToCarbon(string $jdate): ?Carbon
    {
        if (! preg_match('/^(\d{4})\/(\d{2})\/(\d{2})$/', $jdate, $m)) {
            return null;
        }
        try {
            return Jalalian::fromFormat('Y/m/d', $jdate)->toCarbon();
        } catch (\Throwable $e) {
            return null;
        }
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
