<?php

namespace App\Livewire\Client\Schools;

use App\Models\City;
use App\Models\State;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use App\Models\SchoolCooperationRequest;

class Index extends Component
{
    use SEOTools;

    public string $full_name = '';
    public string $mobile = '';
    public $state_id = '';
    public $city_id = '';
    public $states = [];
    public $cities = [];

    // نمایش پیام موفقیت بدون redirect
    public bool $submitted = false;

    public function mount()
    {
        $this->seoConfig();
        $this->states = State::query()->orderBy('name')->get(['id', 'name']);
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('قرارداد همکاری مدارس | SDFR')
            ->setDescription('پلتفرم تخصصی پایش، برنامه‌ریزی و گزارش‌گیری مطالعه دانش‌آموزان؛ همراه مدیران مدارس برای ارتقای کیفیت آموزشی.');
    }

    public function updatedStateId($value)
    {
        $this->city_id = '';
        $this->cities = City::query()
            ->where('state_id', $value)
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    protected function convertToEnglishDigits($value)
    {
        return preg_replace_callback('/[۰-۹٠-٩]/u', function ($match) {
            $map = [
                '۰' => '0','۱' => '1','۲' => '2','۳' => '3','۴' => '4',
                '۵' => '5','۶' => '6','۷' => '7','۸' => '8','۹' => '9',
                '٠' => '0','١' => '1','٢' => '2','٣' => '3','٤' => '4',
                '٥' => '5','٦' => '6','٧' => '7','٨' => '8','٩' => '9',
            ];
            return $map[$match[0]] ?? $match[0];
        }, $value);
    }

    public function updatedMobile($value)
    {
        $this->mobile = $this->convertToEnglishDigits($value);
    }

    public function submit()
    {
        $data = [
            'full_name' => trim($this->full_name),
            'mobile'    => $this->convertToEnglishDigits($this->mobile),
            'state_id'  => $this->state_id,
            'city_id'   => $this->city_id,
        ];

        $validated = Validator::make(
            $data,
            [
                'full_name' => 'required|string|min:3|max:150',
                'mobile'    => ['required', 'regex:/^09\d{9}$/'],
                'state_id'  => 'required|integer|exists:states,id',
                'city_id'   => 'required|integer|exists:cities,id',
            ],
            [
                'full_name.required' => 'وارد کردن نام و نام خانوادگی الزامی است.',
                'full_name.min'      => 'نام باید حداقل ۳ کاراکتر باشد.',
                'mobile.required'    => 'وارد کردن شماره تلفن الزامی است.',
                'mobile.regex'       => 'شماره تلفن همراه را به‌درستی وارد کنید.',
                'state_id.required'  => 'انتخاب استان الزامی است.',
                'state_id.exists'    => 'استان انتخاب‌شده معتبر نیست.',
                'city_id.required'   => 'انتخاب شهر الزامی است.',
                'city_id.exists'     => 'شهر انتخاب‌شده معتبر نیست.',
            ]
        )->validate();

        SchoolCooperationRequest::create([
            'full_name' => $validated['full_name'],
            'mobile'    => $validated['mobile'],
            'state_id'  => $validated['state_id'],
            'city_id'   => $validated['city_id'],
        ]);

        $this->reset(['full_name', 'mobile', 'state_id', 'city_id', 'cities']);

        // فقط submitted رو true میکنیم — بدون redirect، بدون scroll jump
        $this->submitted = true;
    }

    public function render()
    {
        return view('livewire.client.schools.index', [
            'pricingTiers' => SchoolCooperationRequest::TIERS,
            'basePrice'    => SchoolCooperationRequest::BASE_PRICE_PER_STUDENT,
        ])->layout('layouts.client.app');
    }
}
