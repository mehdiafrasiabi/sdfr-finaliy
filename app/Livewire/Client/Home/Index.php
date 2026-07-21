<?php

namespace App\Livewire\Client\Home;

use App\Models\ContactUs;
use App\Models\ExamPlanningSetting;
use App\Models\GradePrice;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class Index extends Component
{
    use SEOTools;

    public array $featuredGradePlans = [];
    public array $featuredGradeOptions = [];
    public ?string $selectedFeaturedGrade = null;

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
            ->setTitle('SDFR | اولین تیم مشاوره تحصیلی کنکور تخصصی و هوشمند درایران SDFR')
            ->setDescription('SDFR، اولین سامانه هوشمند وآنالیز دقیق تحصیلی درایران! با صرفه جویی دروقت و هزینه، پشتیبانی تحصیلی روزانه ،حس پیشرفت درامتحانات نهایی و کنکور را تجربه کنید!');
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
    }

    public function getSelectedFeaturedPlanProperty(): ?array
    {
        return collect($this->featuredGradePlans)
            ->first(fn (array $plan) => (string) $plan['grade'] === $this->selectedFeaturedGrade);
    }

    public function getTrialPlanCopyProperty(): array
    {
        return [
            'plan' => 'trial',
            'title' => 'هفته‌ی آزمایشی',
            'subtitle' => 'بدون نیاز به پرداخت، همین حالا شروع کن',
            'price' => 'رایگان',
            'duration' => '۷ روز کامل',
            'items' => [
                'دسترسی کامل به مدت ۷ روز',
                'تجربه یک هفته برنامه هوشمند',
                'برنامه درسی شخصی سازی شده',
                'ثبت ساعت مطالعه و گزارش درسی',
                'آشنایی با مشاور و پلتفرم',
            ],
            'cta' => 'شروع هفته‌ی آزمایشی',
        ];
    }

    public function getExamPlanCopyProperty(): array
    {
        $grades = ExamPlanningSetting::query()
            ->active()
            ->windowOpen(now())
            ->select('grade')
            ->distinct()
            ->orderBy('grade')
            ->pluck('grade')
            ->map(fn ($grade) => ExamPlanningSetting::GRADE_LABELS[(int) $grade] ?? "پایه {$grade}")
            ->values()
            ->all();

        return [
            'plan' => 'exam',
            'title' => 'برنامه امتحانی',
            'subtitle' => 'ویژه پایه‌هایی که بازه فعال امتحانات دارند',
            'price' => 'رایگان',
            'duration' => 'تا پایان امتحانات',
            'grades' => $grades,
            'items' => [
                'ساخت برنامه مخصوص امتحانات',
                'ثبت تقویم و روزهای امتحان',
                'ساعت‌دهی درس‌ها و فصل‌ها',
                'جمع بندی و نمونه سوال شب امتحان',
                'پشتیبانی تحصیلی روزانه تا پایان بازه امتحانات',
            ],
            'cta' => 'شروع برنامه امتحانی',
        ];
    }

    public function render()
    {
        $trialPlanCopy = $this->getTrialPlanCopyProperty();
        $examPlanCopy = $this->getExamPlanCopyProperty();

        return view('livewire.client.home.index', [
            'trialPlanCopy' => $trialPlanCopy,
            'examPlanCopy' => $examPlanCopy,
        ])->layout('layouts.client.app');
    }
}
