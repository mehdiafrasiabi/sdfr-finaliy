<?php

namespace App\Livewire\Client\Profile;

use App\Models\BarnamehView;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Barnameh extends Component
{
    use WithPagination, SEOTools;

    public function mount()
    {
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('برنامه و گزارش های من')
            ->setDescription('برنامه و گزارش های من');
    }

    public function markAsViewed($barnamehId)
    {
        $studentId = auth()->user()?->student?->id;

        if (!$studentId) return;

        BarnamehView::firstOrCreate([
            'barnameh_id' => $barnamehId,
            'student_id' => $studentId,
        ]);
    }

    public function render()
    {
        $studentId = Auth::user()->student->id ?? null;
        $plans = \App\Models\Barnameh::query()
            ->where('student_id', $studentId)
            ->latest()
            ->paginate(12);

        return view('livewire.client.profile.barnameh', [
            'plans' => $plans,
        ])->layout('layouts.client.app');
    }
}
