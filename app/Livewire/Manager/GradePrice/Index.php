<?php

namespace App\Livewire\Manager\GradePrice;

use App\Models\GradePrice;
use Livewire\Attributes\Layout;
use Livewire\Component;

class Index extends Component
{
    #[Layout('layouts.manager.app')]
    public function render()
    {
        $prices = GradePrice::with(['months', 'discounts'])->orderBy('grade')->get();
        return view('livewire.manager.grade-price.index', compact('prices'));
    }
}
