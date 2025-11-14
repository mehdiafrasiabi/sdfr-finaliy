<?php

namespace App\Livewire\Manager\Map;

use App\Models\Country;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class State extends Component
{
    use WithPagination, SEOTools;
    public $search = '';
    public $name;
    public $countryId2;
    public $stateId;
    public $countries = [];

    public function mount()
    {
        $this->countries = Country::all();
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('استان ها');
    }

    public function submit($formData, \App\Models\State $state)
    {
        $validator = Validator::make($formData, [
            'name' => 'required|string|max:55',
            'countryId' => 'required|exists:countries,id',
        ], [
            '*.required' => 'فیلد ضروری است',
            '*.string' => 'فرمت نوشتاری شما اشتباه است ',
            '*.max' => 'حداکثر نوشتن : 55 کارکتر',
            'countryId.exists' => 'کشور نامعتبر است',
        ]);
        $validator->validate();
        $state->submit($formData, $this->stateId);
        $this->reset();
        $this->dispatch('success', 'عملیات با موفقیت انجام شد');
    }

    public function edit($state_id)
    {
        $state = \App\Models\State::query()->where('id', $state_id)->first();
        if ($state) {
            $this->name = $state->name;
            $this->stateId = $state->id;
            $this->countryId2 = $state->country_id;
        }
    }

    public function delete($state_id)
    {
        $state = \App\Models\State::withCount('cities')->findOrFail($state_id);

        if ($state->cities_count > 0) {
            $this->dispatch('error', 'نمی‌توان استان را حذف کرد، زیرا دارای شهر است.');
            return;
        }

        $state->delete();
        $this->dispatch('success', 'استان با موفقیت حذف شد.');
    }

    public function render()
    {
        $states = \App\Models\State::query()->with('country')
            ->when($this->search, fn($q)
            => $q->where('name', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);
        return view('livewire.manager.map.state', ['states' => $states])->layout('layouts.manager.app');
    }
}
