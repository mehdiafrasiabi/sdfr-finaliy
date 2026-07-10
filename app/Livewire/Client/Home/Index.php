<?php

namespace App\Livewire\Client\Home;

use App\Models\ContactUs;
use App\Models\GradePrice;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class Index extends Component
{
    use SEOTools;

    public array $featuredGradePlans = [];
    public array $featuredGradeOptions = [];
    public string $selectedFeaturedGrade = '12';

    public $contact_name;
    public $contact_phone;

    public $contact_message;

    public function mount()
    {
        $this->seoConfig();
        $this->loadFeaturedGradePlans();
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('وبسایت مشاور تحصیلی و آموزشی SDFR')
            ->setDescription('وبسایت مشاوره ای SDFR یکی از پرتلاش‌ترین و بروزترین وبسایت های آموزشی در سطح ایران است که همیشه تلاش کرده تا بتواند جدیدترین و بروزترین مقالات و دوره‌های آموزشی را در اختیار دانش آموزان ایرانی قرار دهد.');
    }

    public function submitContact()
    {
        $data = $this->validate([
            'contact_name'    => 'required|string|max:150',
            'contact_phone'   => ['required', 'regex:/^09\d{9}$/'],
            'contact_message' => 'required|string|max:1000',
        ], [
            'contact_name.required'    => 'وارد کردن نام الزامی است.',
            'contact_phone.required'   => 'وارد کردن شماره تماس الزامی است.',
            'contact_phone.regex'      => 'شماره موبایل را به‌درستی وارد کن (مثل ۰۹۱۲۳۴۵۶۷۸۹).',
            'contact_message.required' => 'نوشتن پیام الزامی است.',
            '*.max'                    => 'متن واردشده بیش از حد مجاز است.',
            '*.string'                 => 'فرمت نوشتاری اشتباه است.',
        ]);

        $text = '';
        $text .= "\n" . $data['contact_message'];

        ContactUs::query()->create([
            'name'   => $data['contact_name'],
            'mobile' => $data['contact_phone'],
            'text'   => trim($text),
        ]);

        $this->reset(['contact_name', 'contact_phone', 'contact_message']);

        session()->flash('contact_sent', true);
        $this->dispatch('success', 'پیامت با موفقیت ارسال شد. به‌زودی با تو تماس می‌گیریم.');
    }

    private function loadFeaturedGradePlans(): void
    {
        $gradeLabels = [
            9 => 'پایه نهم',
            10 => 'پایه دهم',
            11 => 'پایه یازدهم',
            12 => 'پایه دوازدهم',
        ];

        $this->featuredGradeOptions = collect($gradeLabels)
            ->map(fn (string $label, int $grade) => ['id' => (string) $grade, 'name' => $label])
            ->values()
            ->all();

        $this->featuredGradePlans = collect([9, 10, 11, 12])->map(function (int $grade) use ($gradeLabels) {
            $price = GradePrice::activeFor($grade)?->load('monthDiscounts');

            if (! $price) {
                return [
                    'grade' => $grade,
                    'label' => $gradeLabels[$grade],
                    'is_available' => false,
                    'has_discount' => false,
                    'discount' => 0,
                    'month_label' => null,
                    'duration_label' => $grade === 12
                        ? 'برای یک سال تحصیلی، تا پایان روز کنکور'
                        : 'برای یک سال تحصیلی، تا پایان امتحانات نوبت دوم',
                    'total' => null,
                    'original_total' => null,
                ];
            }

            $entryIndex = $price->entryMonthIndex(now());
            $row = collect($price->entryMonthsTable(now()))
                ->firstWhere('index', $entryIndex);

            return [
                'grade' => $grade,
                'label' => $gradeLabels[$grade],
                'is_available' => (bool) $row,
                'has_discount' => (bool) (($row['discount'] ?? 0) > 0),
                'discount' => (int) ($row['discount'] ?? 0),
                'month_label' => $row['label'] ?? null,
                'duration_label' => $grade === 12
                    ? 'برای یک سال تحصیلی، تا پایان روز کنکور'
                    : 'برای یک سال تحصیلی، تا پایان امتحانات نوبت دوم',
                'total' => $row['total'] ?? null,
                'original_total' => $row['original_total'] ?? null,
            ];
        })->all();

        if (! collect($this->featuredGradePlans)->contains(fn (array $plan) => (string) $plan['grade'] === $this->selectedFeaturedGrade)) {
            $this->selectedFeaturedGrade = '12';
        }
    }

    public function getSelectedFeaturedPlanProperty(): ?array
    {
        return collect($this->featuredGradePlans)
            ->first(fn (array $plan) => (string) $plan['grade'] === $this->selectedFeaturedGrade);
    }

    public function render()
    {
        return view('livewire.client.home.index')->layout('layouts.client.app');
    }
}
