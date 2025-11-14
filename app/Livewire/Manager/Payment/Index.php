<?php

namespace App\Livewire\Manager\Payment;

use App\Models\PaymentMethod;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination,SEOTools;
    public $search = '';
    public $name;
    public $merchantCode;
    public $paymentId;

    public function mount()
    {
        $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()
            ->setTitle('درگاه پرداخت');
    }

    public function submit($formData, PaymentMethod $paymentMethod)
    {
        $validator = Validator::make($formData, [
            'name' => 'required|string|max:55',
            'merchantCode' => 'required|string|max:55',
        ], [
            '*.required' => 'فیلد ضروری است',
            '*.string' => 'فرمت نوشتاری شما اشتباه است ',
            '*.integer' => 'فرمت نوشتاری شما اشتباه است با اعداد اینگلیسی بنویسید',
            '*.max' => 'حداکثر نوشتن : 55 کارکتر',
        ]);
        $validator->validate();
        $paymentMethod->submit($formData, $this->paymentId);
        $this->reset();
        $this->dispatch('success', 'عملیات با موفقیت انجام شد');
    }

    public function edit($payment_id)
    {
        $payment = PaymentMethod::query()->where('id', $payment_id)->first();
        if ($payment) {
            $this->name = $payment->name;
            $this->merchantCode = $payment->merchant_id;
            $this->paymentId = $payment->id;
        }
    }

    public function delete($payment_id)
    {
        PaymentMethod::query()->where('id', $payment_id)->delete();
        $this->dispatch('success', 'با موفقیت حدف شد');
    }

    public function changeStatus(PaymentMethod $payment)
    {
        if (!$payment->active) {
            // اول همه درگاه‌ها رو غیرفعال کن
            PaymentMethod::query()->update(['active' => false]);

            // سپس درگاه انتخاب‌شده رو فعال کن
            $payment->update(['active' => true]);

            $this->dispatch('success', 'درگاه فعال شد و بقیه غیرفعال شدند');
        } else {
            $this->dispatch('warning', 'این درگاه قبلاً فعال بوده است');
        }
    }


    public function render()
    {

        $paymentMethod = PaymentMethod::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%');
                });
            })->paginate(10);
        return view('livewire.manager.payment.index', [
            'paymentMethod' => $paymentMethod
        ])->layout('layouts.manager.app');
    }
}
