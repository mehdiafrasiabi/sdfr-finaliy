<?php

namespace App\Livewire\Client\PercentCalculator;

use App\Models\PercentCalculatorSetting;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class Index extends Component
{
    use SEOTools;

    public function render()
    {
        $setting = PercentCalculatorSetting::first();

        if ($setting) {
            $this->seo()
                ->setTitle($setting->meta_title ?: $setting->title ?: 'درصد گیر')
                ->setDescription($setting->meta_description ?: $setting->subtitle ?: '');
        } else {
            $this->seo()->setTitle('درصد گیر');
        }

        return view('livewire.client.percent-calculator.index', [
            'setting' => $setting,
        ])->layout('layouts.client.app');
    }
}
