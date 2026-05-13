<?php

namespace App\Livewire\Manager\GradePrice;

use App\Models\GradePrice;
use App\Models\GradePriceMonth;
use App\Services\GradePriceCalculator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Create extends Component
{
    public string $grade = '';
    public string $basePrice = '';
    public string $startDate = '';
    public string $endDate = '';
    public int $monthsCount = 12;

    public function save(): void
    {
        $this->validate([
            'grade'       => 'required|in:9,10,11,12',
            'basePrice'   => 'required|integer|min:0',
            'startDate'   => 'required|date',
            'endDate'     => 'required|date|after:startDate',
            'monthsCount' => 'required|integer|min:1|max:24',
        ]);

        $gp = GradePrice::create([
            'grade'        => (int) $this->grade,
            'base_price'   => (int) $this->basePrice,
            'start_date'   => $this->startDate,
            'end_date'     => $this->endDate,
            'months_count' => $this->monthsCount,
            'created_by'   => Auth::guard('manager')->id() ?? Auth::guard('admin')->id(),
            'is_active'    => true,
        ]);

        foreach (app(GradePriceCalculator::class)->generateMonthlyPrices($gp) as $row) {
            GradePriceMonth::create([
                'grade_price_id' => $gp->id,
                'month_index'    => $row['month_index'],
                'price'          => $row['price'],
            ]);
        }

        session()->flash('success', 'قیمت‌گذاری پایه با موفقیت ساخته شد.');
        $this->redirect(route('manager.grade-prices.edit', $gp->id));
    }

    #[Layout('layouts.manager.app')]
    public function render()
    {
        return view('livewire.manager.grade-price.create');
    }
}
