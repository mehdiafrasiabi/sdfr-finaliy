<?php

namespace App\Livewire\Client\Profile\Installment;

use App\Contracts\PaymentGateWayInterface;
use App\Models\Installment;
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

class Index extends Component
{
    use SEOTools;

    public string $tab = 'due';
    public array $selectedInstallments = [];
    public bool $showConfirmationModal = false;
    public bool $groupPaymentMode = false;

    public function mount(): void
    {
        $this->seo()->setTitle('اقساط من');
    }

    public function getInstallmentDescription(Installment $installment): string
    {
        if (! $installment->isPaid()) {
            return '—'; // Or any other placeholder for unpaid
        }

        // Assuming a direct relationship 'payment' exists on Installment model
        // Or we might need to query it based on the payment logic
        $payment = Payment::query()
            ->where('status', 'completed')
            ->where(function ($query) use ($installment) {
                $query->where('installment_id', $installment->id)
                    ->orWhereJsonContains('installment_ids', $installment->id);
            })
            ->first();

        if (! $payment) {
            return 'پرداخت تک قسط'; // Default if payment not found but installment is paid
        }

        if ($payment->purpose === Payment::PURPOSE_INSTALLMENT_BULK) {
            return 'پرداخت گروهی';
        }

        return 'پرداخت تک قسط';
    }


    public function toggleGroupPaymentMode(): void
    {
        $this->groupPaymentMode = !$this->groupPaymentMode;
        $this->selectedInstallments = [];
        $this->resetErrorBag();
    }

    public function updatedSelectedInstallments($value): void
    {
        $plan = $this->plan();
        if (!$plan || count($this->selectedInstallments) === 0) {
            return;
        }

        $dueInstallments = $plan->installments()
                                ->where('status', 'pending')
                                ->orderBy('sequence')
                                ->get();

        $selected = $dueInstallments->whereIn('id', $this->selectedInstallments)->sortBy('sequence');

        $currentDue = $dueInstallments->first();
        if ($selected->isNotEmpty() && $selected->first()->id !== $currentDue->id) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'انتخاب باید از اولین قسطِ در صف شروع شود.']);
            $this->selectedInstallments = [];
            return;
        }

        $lastSeq = null;
        foreach ($selected as $item) {
            if ($lastSeq !== null && $item->sequence !== $lastSeq + 1) {
                $this->dispatch('show-toast', ['type' => 'error', 'message' => 'اقساط باید به صورت متوالی انتخاب شوند.']);
                array_pop($this->selectedInstallments);
                return;
            }
            $lastSeq = $item->sequence;
        }

        if (count($this->selectedInstallments) > 5) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'شما می‌توانید حداکثر ۵ قسط را همزمان پرداخت کنید.']);
            array_pop($this->selectedInstallments);
        }
    }

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
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'طرح اقساطی فعالی یافت نشد.']);
            return;
        }

        $current = $plan->currentDue();
        if (! $current || $current->id !== $installmentId) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'اقساط باید به‌ترتیب پرداخت شوند؛ ابتدا قسط جاری را پرداخت کنید.']);
            return;
        }
        if ($current->isPaid()) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'این قسط قبلاً پرداخت شده است.']);
            return;
        }

        $pi = PersonalInformation::where('user_id', $user->id)->first();
        if (! $pi) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'اطلاعات شخصی شما کامل نیست.']);
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
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'خطا در ثبت پرداخت قسط: ' . $e->getMessage()]);
            return;
        }

        return $paymentGateway->request($amount, $orderNumber);
    }

    public function paySelectedInstallments(PaymentGateWayInterface $paymentGateway)
    {
        $user = Auth::user();
        if (! $user) {
            return $this->redirect(route('client.auth.login'), navigate: true);
        }

        $plan = $this->plan();
        if (!$plan || empty($this->selectedInstallments)) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'هیچ قسطی برای پرداخت انتخاب نشده است.']);
            return;
        }

        $installments = $plan->installments()
                             ->whereIn('id', $this->selectedInstallments)
                             ->where('status', 'pending')
                             ->orderBy('sequence')
                             ->get();

        if ($installments->count() !== count($this->selectedInstallments)) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'یک یا چند قسطِ انتخاب‌شده نامعتبر است. لطفاً دوباره تلاش کنید.']);
            $this->selectedInstallments = [];
            return;
        }

        $pi = PersonalInformation::where('user_id', $user->id)->first();
        if (! $pi) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'اطلاعات شخصی شما کامل نیست.']);
            return;
        }

        $amount      = (int) $installments->sum('amount');
        $orderNumber = 'SDFR-' . Str::uuid()->toString();

        try {
            DB::transaction(function () use ($user, $pi, $plan, $installments, $amount, $orderNumber) {
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
                    'purpose'                 => Payment::PURPOSE_INSTALLMENT_BULK,
                    'installment_plan_id'     => $plan->id,
                    'installment_ids'         => $installments->pluck('id')->toArray(),
                ]);
            });
        } catch (\Throwable $e) {
            $this->dispatch('show-toast', ['type' => 'error', 'message' => 'خطا در ثبت پرداخت گروهی: ' . $e->getMessage()]);
            return;
        }

        $this->showConfirmationModal = false;
        return $paymentGateway->request($amount, $orderNumber);
    }

    public function render()
    {
        $plan = $this->plan();

        $installments = $plan
            ? $plan->installments()->with('payment')->orderBy('sequence')->get()
            : collect();

        $paid = $installments->where('status', 'paid');
        $due = $installments->where('status', 'pending');
        $current = $due->first();


        return view('livewire.client.profile.installment.index', [
            'plan'         => $plan,
            'current'      => $current,
            'installments' => $installments,
            'paidList'     => $paid->values(),
            'dueList'      => $due->values(),
        ])->layout('layouts.client.app');
    }
}
