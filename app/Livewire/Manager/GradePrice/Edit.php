<?php

namespace App\Livewire\Manager\GradePrice;

use App\Models\GradePrice;
use App\Models\GradePriceDiscount;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Edit extends Component
{
    public GradePrice $gradePrice;
    public array $monthPrices = [];
    public array $discounts = [];

    public function mount(GradePrice $gradePrice): void
    {
        $this->gradePrice = $gradePrice->load(['months', 'discounts']);
        foreach ($this->gradePrice->months as $m) {
            $this->monthPrices[$m->month_index] = $m->price;
        }
        foreach ($this->gradePrice->discounts as $d) {
            $this->discounts[$d->month_index] = $d->percent;
        }
    }

    public function save(): void
    {
        foreach ($this->gradePrice->months as $m) {
            $newPrice = (int) ($this->monthPrices[$m->month_index] ?? $m->price);
            $m->update(['price' => $newPrice]);
        }

        foreach ($this->discounts as $idx => $percent) {
            $percent = max(0, min(100, (int) $percent));
            if ($percent > 0) {
                GradePriceDiscount::updateOrCreate(
                    ['grade_price_id' => $this->gradePrice->id, 'month_index' => $idx],
                    ['percent' => $percent]
                );
            } else {
                GradePriceDiscount::where('grade_price_id', $this->gradePrice->id)
                    ->where('month_index', $idx)
                    ->delete();
            }
        }

        session()->flash('success', 'ذخیره شد.');
    }

    #[Layout('layouts.manager.app')]
    public function render()
    {
        return view('livewire.manager.grade-price.edit');
    }
}
