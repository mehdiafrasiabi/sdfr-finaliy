<?php

namespace App\Livewire\Manager\GradePrice;

use App\Models\GradePrice;
use App\Models\GradePriceDailyDiscount;
use App\Models\GradePriceMonthDiscount;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

/**
 * D-2 (بازطراحی) — صفحهٔ جزئیات قیمت یک پایه.
 *
 * - تعداد ماه‌ها از تفاضل start_at و end_at محاسبه می‌شود.
 * - برای هر ماه یک رکورد در `grade_price_month_discounts` upsert می‌شود.
 * - مدیر برای هر ماه درصد تخفیف ماهانه را وارد می‌کند.
 * - برای هر ماه می‌تواند تخفیف‌های روز خاص (یک‌روزه) هم اضافه کند که فقط در
 *   آن روز اعمال می‌شود.
 */
class Show extends Component
{
    public GradePrice $price;

    /** درصد تخفیف هر ماه — کلید: month_index */
    public array $monthDiscounts = [];

    /** فرم تخفیف روز خاص */
    public bool   $showDailyForm        = false;
    public ?int   $editingDailyId       = null;
    public ?int   $targetMonthIndex     = null;
    public string $dailyLabel           = '';
    public int    $dailyPercentage      = 10;
    public string $dailyDateJ           = ''; // شمسی yyyy/mm/dd
    public bool   $dailyActive          = true;

    public function mount(GradePrice|int $price): void
    {
        $this->price = $price instanceof GradePrice
            ? $price->load(['monthDiscounts', 'dailyDiscounts'])
            : GradePrice::with(['monthDiscounts', 'dailyDiscounts'])->findOrFail($price);

        $this->seedMonthRows();
        $this->loadMonthDiscounts();
    }

    /**
     * upsert یک رکورد per month طبق تعداد ماه‌های پلن.
     */
    protected function seedMonthRows(): void
    {
        $months = $this->price->months_count;
        for ($i = 0; $i < $months; $i++) {
            [$start, $end] = $this->price->monthRange($i);

            GradePriceMonthDiscount::updateOrCreate(
                ['grade_price_id' => $this->price->id, 'month_index' => $i],
                [
                    'month_starts_on' => $start->toDateString(),
                    'month_ends_on'   => $end->toDateString(),
                ]
            );
        }
        $this->price->load('monthDiscounts');
    }

    protected function loadMonthDiscounts(): void
    {
        $this->monthDiscounts = [];
        foreach ($this->price->monthDiscounts as $md) {
            $this->monthDiscounts[$md->month_index] = (int) $md->discount_percentage;
        }
    }

    public function saveMonthDiscount(int $monthIndex): void
    {
        $pct = (int) ($this->monthDiscounts[$monthIndex] ?? 0);
        if ($pct < 0 || $pct > 100) {
            $this->addError('month_'.$monthIndex, 'درصد باید بین ۰ تا ۱۰۰ باشد.');
            return;
        }

        GradePriceMonthDiscount::where('grade_price_id', $this->price->id)
            ->where('month_index', $monthIndex)
            ->update(['discount_percentage' => $pct]);

        session()->flash('success', 'تخفیف ماه ذخیره شد.');
        $this->price->load('monthDiscounts');
    }

    // ─────────────── تخفیف روز خاص ───────────────

    public function openDailyForm(int $monthIndex): void
    {
        $this->targetMonthIndex = $monthIndex;
        $this->editingDailyId   = null;
        $this->dailyLabel       = '';
        $this->dailyPercentage  = 10;
        $this->dailyDateJ       = '';
        $this->dailyActive      = true;
        $this->showDailyForm    = true;
        $this->resetErrorBag();
    }

    public function openEditDaily(int $id, int $monthIndex): void
    {
        $d = GradePriceDailyDiscount::where('grade_price_id', $this->price->id)
            ->findOrFail($id);

        $this->targetMonthIndex = $monthIndex;
        $this->editingDailyId   = $id;
        $this->dailyLabel       = $d->label ?? '';
        $this->dailyPercentage  = (int) $d->discount_percentage;
        $this->dailyDateJ       = Jalalian::fromCarbon($d->starts_on)->format('Y/m/d');
        $this->dailyActive      = (bool) $d->is_active;
        $this->showDailyForm    = true;
        $this->resetErrorBag();
    }

    public function closeDailyForm(): void
    {
        $this->showDailyForm    = false;
        $this->editingDailyId   = null;
        $this->targetMonthIndex = null;
    }

    public function saveDaily(): void
    {
        $this->validate([
            'dailyLabel'      => ['nullable', 'string', 'max:100'],
            'dailyPercentage' => ['required', 'integer', 'min:1', 'max:100'],
            'dailyDateJ'      => ['required', 'regex:/^\d{4}\/\d{2}\/\d{2}$/'],
            'dailyActive'     => ['boolean'],
        ], [
            'dailyPercentage.required' => 'درصد تخفیف الزامی است.',
            'dailyDateJ.required'      => 'تاریخ روز خاص الزامی است.',
            'dailyDateJ.regex'         => 'فرمت تاریخ باید yyyy/mm/dd شمسی باشد.',
        ]);

        try {
            $date = Jalalian::fromFormat('Y/m/d', $this->dailyDateJ)->toCarbon();
        } catch (\Throwable $e) {
            throw ValidationException::withMessages(['dailyDateJ' => 'تاریخ شمسی نامعتبر است.']);
        }

        // باید روز در بازهٔ ماه مقصد بیفتد
        if ($this->targetMonthIndex !== null) {
            [$mStart, $mEnd] = $this->price->monthRange($this->targetMonthIndex);
            if (! $date->between($mStart, $mEnd)) {
                throw ValidationException::withMessages([
                    'dailyDateJ' => 'این تاریخ در بازهٔ ماه انتخاب‌شده نیست.',
                ]);
            }
        }

        $payload = [
            'grade_price_id'      => $this->price->id,
            'label'               => $this->dailyLabel ?: null,
            'discount_percentage' => $this->dailyPercentage,
            'starts_on'           => $date->toDateString(),
            'ends_on'             => $date->toDateString(),
            'is_active'           => $this->dailyActive,
        ];

        if ($this->editingDailyId) {
            GradePriceDailyDiscount::where('grade_price_id', $this->price->id)
                ->findOrFail($this->editingDailyId)
                ->update($payload);
            session()->flash('success', 'تخفیف روز خاص ویرایش شد.');
        } else {
            GradePriceDailyDiscount::create($payload);
            session()->flash('success', 'تخفیف روز خاص ثبت شد.');
        }

        $this->closeDailyForm();
        $this->price->load('dailyDiscounts');
    }

    public function toggleDaily(int $id): void
    {
        $d = GradePriceDailyDiscount::where('grade_price_id', $this->price->id)->findOrFail($id);
        $d->update(['is_active' => ! $d->is_active]);
        $this->price->load('dailyDiscounts');
    }

    public function deleteDaily(int $id): void
    {
        GradePriceDailyDiscount::where('grade_price_id', $this->price->id)
            ->findOrFail($id)
            ->delete();
        session()->flash('success', 'تخفیف روز خاص حذف شد.');
        $this->price->load('dailyDiscounts');
    }

    public function render()
    {
        // برای هر ماه: محدوده، قیمت ثابت، لیست تخفیف‌های روز خاص داخل بازه
        $rows = [];
        $months = $this->price->months_count;
        for ($i = 0; $i < $months; $i++) {
            [$start, $end] = $this->price->monthRange($i);
            $dailyDiscounts = $this->price->dailyDiscounts
                ->filter(fn($d) => $d->starts_on->between($start->copy()->startOfDay(), $end->copy()->endOfDay()))
                ->values();

            $rows[] = [
                'index'       => $i,
                'start'       => $start,
                'end'         => $end,
                'jalali_label'=> Jalalian::fromCarbon($start)->format('F Y'),
                'stepped'     => $this->price->priceAtMonth($i),
                'monthly_pct' => $this->monthDiscounts[$i] ?? 0,
                'dailies'     => $dailyDiscounts,
            ];
        }

        return view('livewire.manager.grade-price.show', [
            'rows' => $rows,
        ])->layout('layouts.manager.app');
    }
}
