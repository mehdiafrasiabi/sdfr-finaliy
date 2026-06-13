<?php

namespace App\Livewire\Client\Profile\Installment;

use App\Contracts\PaymentGateWayInterface;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PersonalInformation;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

/**
 * صفحهٔ اقساط دانش‌آموز: نمایش طرح اقساطیِ فعال و امکان پرداخت «قسطِ جاری» (به‌ترتیب).
 * فقط قسطِ سررسیدِ بعدی قابل پرداخت است؛ نمی‌توان قسطی را جا انداخت.
 */
class Installment extends Component
{
    use SEOTools;

    public function mount(): void
    {
        $this->seo()->setTitle('اقساط شهریه');
    }

    public function payCurrent(PaymentGateWayInterface $paymentGateway)
    {
        $user = Auth::user();
        $student = $user?->student;
        $plan = $student?->activeInstallmentPlan();

        if (! $plan) {
            session()->flash('error', 'طرح اقساطی فعالی ندارید.');
            return;
        }

        $due = $plan->currentDue();
        if (! $due) {
            session()->flash('error', 'قسط پرداخت‌نشده‌ای وجود ندارد.');
            return;
        }

        $pi = PersonalInformation::where('user_id', $user->id)->first();
        if (! $pi) {
            session()->flash('error', 'اطلاعات شخصی شما کامل نیست.');
            return;
        }

        $orderNumber = 'SDFR-' . Str::uuid()->toString();
        $amount = (int) $due->amount;

        try {
            return DB::transaction(function () use ($user, $pi, $plan, $due, $amount, $orderNumber, $paymentGateway) {
                $order = Order::query()->create([
                    'amount'            => $amount,
                    'order_number'      => $orderNumber,
                    'user_id'           => $user->id,
                    'payment_method_id' => 1,
                    'paid_with_wallet'  => false,
                    'wallet_amount'     => 0,
                    'status'            => 'pending',
                ]);

                OrderItem::query()->create([
                    'price'      => $amount,
                    'order_id'   => $order->id,
                    'product_id' => (int) config('sdfr.course_product_id'),
                ]);

                Payment::query()->create([
                    'order_id'                => $order->id,
                    'user_id'                 => $user->id,
                    'amount'                  => $amount,
                    'order_number'            => $orderNumber,
                    'personal_information_id' => $pi->id,
                    'status'                  => 'pending',
                    'purpose'                 => Payment::PURPOSE_INSTALLMENT,
                    'installment_plan_id'     => $plan->id,
                    'installment_id'          => $due->id,
                ]);

                return $paymentGateway->request($amount, $orderNumber);
            });
        } catch (\Throwable $e) {
            session()->flash('error', 'خطا در ثبت پرداخت قسط: ' . $e->getMessage());
            return;
        }
    }

    public function render()
    {
        $student = Auth::user()?->student;
        $plan = $student?->activeInstallmentPlan()
            ?? $student?->installmentPlans()->latest('id')->first();

        $installments = $plan ? $plan->installments()->get() : collect();
        $currentDue = $plan ? $plan->currentDue() : null;

        return view('livewire.client.profile.installment.installment', [
            'plan'         => $plan,
            'installments' => $installments,
            'currentDue'   => $currentDue,
        ])->layout('layouts.client.app');
    }
}
