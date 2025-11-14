<?php

namespace App\Livewire\Manager\Map;

use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class Country extends Component
{
    use WithPagination,SEOTools;
    public $search = '';
    public $name;
    public $countryId;

    public function mount()
    {
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('کشور ها');
    }

    public function submit($formData , \App\Models\Country $country)
    {
        $validator = Validator::make($formData,[
            'name'=>'required|string|max:55',
        ],[
            '*.required'=>'فیلد ضروری است',
            '*.string'=>'فرمت نوشتاری شما اشتباه است ',
            '*.max'=>'حداکثر نوشتن : 55 کارکتر',
        ]);
        $validator->validate();
        $country->submit($formData,$this->countryId);
        $this->reset();
        $this->dispatch('success','عملیات با موفقیت انجام شد');
    }
    public function edit($country_id)
    {
        $country = \App\Models\Country::query()->where('id',$country_id)->first();
        if ($country) {
            $this->name = $country->name;
            $this->countryId = $country->id;
        }
    }

    public function delete($country_id)
    {
        \App\Models\Country::query()->where('id',$country_id)->delete();
        $this->dispatch('success','با موفقیت حدف شد');
    }

    public function render()
    {
        $countries = \App\Models\Country::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);
        return view('livewire.manager.map.country',['countries' => $countries])->layout('layouts.manager.app');
    }
}
