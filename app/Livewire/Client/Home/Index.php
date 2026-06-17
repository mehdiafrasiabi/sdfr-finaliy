<?php

namespace App\Livewire\Client\Home;

use App\Models\ContactUs;
use Artesaos\SEOTools\Traits\SEOTools;
use Livewire\Component;

class Index extends Component
{
    use SEOTools;

    /**
     * قیمت نقدی ماهانه (تومان). فعلاً null → در صفحه «به‌زودی» نمایش داده می‌شود.
     * برای اتصال بعدی به دیتابیس:
     *   $this->monthlyPrice = \App\Models\GradePrice::where('is_active', true)->min('monthly_rate');
     */
    public $monthlyPrice = null;

    public $contact_name;
    public $contact_email;
    public $contact_phone;
    public $contact_subject;
    public $contact_message;

    public function mount()
    {
        $this->seoConfig();
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
            'contact_email'   => 'nullable|email|max:150',
            'contact_subject' => 'nullable|string|max:150',
            'contact_message' => 'required|string|max:1000',
        ], [
            'contact_name.required'    => 'وارد کردن نام الزامی است.',
            'contact_phone.required'   => 'وارد کردن شماره تماس الزامی است.',
            'contact_phone.regex'      => 'شماره موبایل را به‌درستی وارد کن (مثل ۰۹۱۲۳۴۵۶۷۸۹).',
            'contact_email.email'      => 'ایمیل واردشده معتبر نیست.',
            'contact_message.required' => 'نوشتن پیام الزامی است.',
            '*.max'                    => 'متن واردشده بیش از حد مجاز است.',
            '*.string'                 => 'فرمت نوشتاری اشتباه است.',
        ]);

        $text = '';
        if (! empty($data['contact_subject'])) {
            $text .= 'موضوع: ' . $data['contact_subject'] . "\n";
        }
        if (! empty($data['contact_email'])) {
            $text .= 'ایمیل: ' . $data['contact_email'] . "\n";
        }
        $text .= "\n" . $data['contact_message'];

        ContactUs::query()->create([
            'name'   => $data['contact_name'],
            'mobile' => $data['contact_phone'],
            'text'   => trim($text),
        ]);

        $this->reset(['contact_name', 'contact_email', 'contact_phone', 'contact_subject', 'contact_message']);

        session()->flash('contact_sent', true);
        $this->dispatch('success', 'پیامت با موفقیت ارسال شد. به‌زودی با تو تماس می‌گیریم.');
    }

    public function render()
    {
        return view('livewire.client.home.index')->layout('layouts.client.app');
    }
}
