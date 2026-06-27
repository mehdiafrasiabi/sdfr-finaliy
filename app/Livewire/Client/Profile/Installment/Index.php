<?php

namespace App\Livewire\Client\Profile\Installment;

use App\Contracts\PaymentGateWayInterface;
use App\Models\InstallmentPlan;
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
 * «اقساط من» — لیست اقساطِ طرحِ فعالِ دانش‌آموز با قاعدهٔ پرداختِ به‌ترتیب.
 *   - تنها «قسطِ جاری» (کم‌ترین sequenceِ پرداخت‌نشده) قابل پرداخت است.
 *   - تب «قابل پرداخت» و «پرداخت‌شده».
 *   - پرداخت → ساخت Payment(purpose=installment) → درگاه. نهایی‌سازی در callback.
 */
class Index extends Component
{
    use SEOTools;

    public string $tab = 'due'; // due | paid

    public function mount(): void
    {
        $this->seo()->setTitle('اقساط من');
    }

    /** طرحِ فعالِ جاری دانش‌آموز (active یا pending). */
    protected function plan(): ?InstallmentPlan
    {
        return Auth::user()?->student?->activeInstallmentPlan();
    }

    public function setTab(string $tab): void
    {
        $this->tab = in_array($tab, ['due', 'paid'], true) ? $tab : 'due';
    }

    public function payInstallment(int $installmentId, PaymentGateWayInterface $paymentGateway)
    {
        $user = Auth::user();
        if (! $user) {
            return $this->redirect(route('client.auth.login'), navigate: true);
        }

        $plan = $this->plan();
        if (! $plan) {
            session()->flash('error', 'طرح اقساطی فعالی یافت نشد.');
            return;
        }

        // قاعدهٔ پرداختِ به‌ترتیب: فقط قسطِ جاری قابل پرداخت است.
        $current = $plan->currentDue();
        if (! $current || $current->id !== $installmentId) {
            session()->flash('error', 'اقساط باید به‌ترتیب پرداخت شوند؛ ابتدا قسط جاری را پرداخت کنید.');
            return;
        }
        if ($current->isPaid()) {
            session()->flash('error', 'این قسط قبلاً پرداخت شده است.');
            return;
        }

        $pi = PersonalInformation::where('user_id', $user->id)->first();
        if (! $pi) {
            session()->flash('error', 'اطلاعات شخصی شما کامل نیست.');
            return;
        }

        $amount      = (int) $current->amount;
        $orderNumber = 'SDFR-' . Str::uuid()->toString();

        try {
            DB::transaction(function () use ($user, $pi, $plan, $current, $amount, $orderNumber) {
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
                    'price'    => $amount,
                    'order_id' => $order->id,
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
                    'installment_id'          => $current->id,
                ]);
            });
        } catch (\Throwable $e) {
            session()->flash('error', 'خطا در ثبت پرداخت قسط: ' . $e->getMessage());
            return;
        }

        return $paymentGateway->request($amount, $orderNumber);
    }

    public function render()
    {
        $plan    = $this->plan();
        $current = $plan?->currentDue();

        $installments = $plan
            ? $plan->installments()->orderBy('sequence')->get()
            : collect();

        $paid = $installments->where('status', 'paid');
        $due  = $installments->where('status', 'pending');

        return view('livewire.client.profile.installment.index', [
            'plan'         => $plan,
            'current'      => $current,
            'installments' => $installments,
            'paidList'     => $paid->values(),
            'dueList'      => $due->values(),
        ])->layout('layouts.client.app');
    }
}
