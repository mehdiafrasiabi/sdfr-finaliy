<?php

namespace App\Livewire\Client\Purchase;

use App\Contracts\PaymentGateWayInterface;
use App\Models\Coupons;
use App\Models\CouponUsage;
use App\Models\GradePrice;
use App\Models\Installment;
use App\Models\InstallmentPlan;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PersonalInformation;
use Artesaos\SEOTools\Traits\SEOTools;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Morilog\Jalali\Jalalian;

/**
 * صفحهٔ پرداخت دوره با مدل «ماه ورود و تخفیف».
 *
 *   - قیمت بر اساس ماهِ خرید (امروز) محاسبه می‌شود: کل پرداختی سال، پیش‌پرداخت، اقساط.
 *   - دو حالت پرداخت:
 *       • پرداخت کامل: کل مبلغ سال یک‌جا (با امکان کوپن تخفیف).
 *       • اقساطی: پیش‌پرداخت ۳۰٪ همین حالا + اقساط ماهانه تا پایان خرداد.
 *   - پس از پرداخت موفق، دسترسی تا پایان خرداد فعال می‌شود (PurchaseFinalizer در callback).
 */
class Index extends Component
{
    use SEOTools;

    public string  $couponCode     = '';
    public ?string $couponError    = null;
    public ?string $couponNotice   = null;
    public int     $couponDiscount = 0; // درصد تخفیف کوپن (فقط روی پرداخت کامل)

    public function mount(): void
    {
        $this->seo()->setTitle('پرداخت دوره');

        $user = Auth::user();
        if (! $user) {
            return;
        }

        // فقط اگر دسترسیِ پرداختیِ فعال و منقضی‌نشده دارد به داشبورد برود
        // (تا کاربرِ منقضی‌شده بتواند تمدید کند).
        if ($user->student && $user->student->hasActivePaidAccess()) {
            $this->redirect(route('client.profile.dashboard'), navigate: true);
        }
    }

    protected function gradePrice(): ?GradePrice
    {
        $user = Auth::user();
        if (! $user) {
            return null;
        }
        $pi = PersonalInformation::where('user_id', $user->id)->first();
        if (! $pi || ! $pi->grade) {
            return null;
        }
        return GradePrice::activeFor((int) $pi->grade);
    }

    // ─────────────── کوپن (فقط روی پرداخت کامل) ───────────────

    public function applyCoupon(): void
    {
        $this->couponError    = null;
        $this->couponNotice   = null;
        $this->couponDiscount = 0;

        $code = trim($this->couponCode);
        if ($code === '') {
            $this->couponError = 'کد تخفیف را وارد کنید.';
            return;
        }

        $coupon = Coupons::where('code', $code)->where('is_active', true)->first();
        if (! $coupon) {
            $this->couponError = 'کد تخفیف نامعتبر است.';
            return;
        }

        $alreadyUsed = CouponUsage::where('user_id', Auth::id())
            ->where('coupon_id', $coupon->id)
            ->exists();
        if ($alreadyUsed) {
            $this->couponError = 'این کد تخفیف قبلاً استفاده شده است.';
            return;
        }

        $percent = (int) ($coupon->discount_percentage ?? $coupon->percentage ?? 0);
        $this->couponDiscount = max(0, min(100, $percent));
        $this->couponNotice = "تخفیف {$this->couponDiscount} درصدی روی پرداخت کامل اعمال شد.";
    }

    public function removeCoupon(): void
    {
        $this->couponCode     = '';
        $this->couponDiscount = 0;
        $this->couponError    = null;
        $this->couponNotice   = null;
    }

    // ─────────────── پرداخت کامل ───────────────

    public function pay(PaymentGateWayInterface $paymentGateway)
    {
        $user = Auth::user();
        if (! $user) {
            return $this->redirect(route('client.auth.login'), navigate: true);
        }

        $price = $this->gradePrice();
        $pi    = PersonalInformation::where('user_id', $user->id)->first();
        if (! $price || ! $pi) {
            session()->flash('error', 'قیمتی برای پایهٔ شما تعریف نشده یا اطلاعات شخصی کامل نیست.');
            return;
        }

        $i      = $price->entryMonthIndex();
        $amount = $this->finalFullPrice($price, $i);
        if ($amount <= 0) {
            session()->flash('error', 'مبلغ نهایی نامعتبر است.');
            return;
        }

        $orderNumber = 'SDFR-' . Str::uuid()->toString();

        try {
            DB::transaction(function () use ($user, $pi, $amount, $orderNumber) {
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
                    'purpose'                 => Payment::PURPOSE_COURSE_FULL,
                ]);

                $this->logCouponUsage($user->id);
            });
        } catch (\Throwable $e) {
            session()->flash('error', 'خطا در ثبت سفارش: ' . $e->getMessage());
            return;
        }

        return $paymentGateway->request($amount, $orderNumber);
    }

    // ─────────────── پرداخت اقساطی ───────────────

    public function payInstallment(PaymentGateWayInterface $paymentGateway)
    {
        $user = Auth::user();
        if (! $user) {
            return $this->redirect(route('client.auth.login'), navigate: true);
        }

        $price = $this->gradePrice();
        $pi    = PersonalInformation::where('user_id', $user->id)->first();
        if (! $price || ! $pi) {
            session()->flash('error', 'قیمتی برای پایهٔ شما تعریف نشده یا اطلاعات شخصی کامل نیست.');
            return;
        }

        $i     = $price->entryMonthIndex();
        $count = $price->installmentCount($i);
        if ($count < 1) {
            session()->flash('error', 'برای این ماه امکان پرداخت اقساطی وجود ندارد؛ لطفاً پرداخت کامل را انتخاب کنید.');
            return;
        }

        $total    = $price->totalFor($i);
        $initial  = $price->initialPayment($i);
        $monthly  = $price->installmentAmount($i);
        $purchase = Carbon::now();
        $orderNumber = 'SDFR-' . Str::uuid()->toString();

        try {
            return DB::transaction(function () use ($user, $pi, $price, $i, $count, $total, $initial, $monthly, $purchase, $orderNumber, $paymentGateway) {
                $plan = InstallmentPlan::create([
                    'user_id'           => $user->id,
                    'student_id'        => $user->student?->id,
                    'grade_price_id'    => $price->id,
                    'grade'             => (int) $pi->grade,
                    'entry_month_index' => $i,
                    'purchase_date'     => $purchase->toDateString(),
                    'total_amount'      => $total,
                    'initial_amount'    => $initial,
                    'installment_count' => $count,
                    'monthly_amount'    => $monthly,
                    'access_ends_at'    => $price->accessEndsAt(),
                    'status'            => InstallmentPlan::STATUS_PENDING,
                ]);

                // اقساط: قسط k در همان روزِ خرید، k ماه بعد (شمسی). آخرین قسط مابقیِ رُند را جذب می‌کند.
                $accumulated = 0;
                for ($k = 1; $k <= $count; $k++) {
                    $amount = ($k === $count)
                        ? ($total - $initial - $accumulated)
                        : $monthly;
                    $accumulated += $amount;

                    Installment::create([
                        'installment_plan_id' => $plan->id,
                        'sequence'            => $k,
                        'due_date'            => Jalalian::fromCarbon($purchase->copy())->addMonths($k)->toCarbon()->toDateString(),
                        'amount'              => max(0, $amount),
                        'status'              => Installment::STATUS_PENDING,
                    ]);
                }

                $order = Order::query()->create([
                    'amount'            => $initial,
                    'order_number'      => $orderNumber,
                    'user_id'           => $user->id,
                    'payment_method_id' => 1,
                    'paid_with_wallet'  => false,
                    'wallet_amount'     => 0,
                    'status'            => 'pending',
                ]);

                OrderItem::query()->create([
                    'price'      => $initial,
                    'order_id'   => $order->id,
                    'product_id' => (int) config('sdfr.course_product_id'),
                ]);

                Payment::query()->create([
                    'order_id'                => $order->id,
                    'user_id'                 => $user->id,
                    'amount'                  => $initial,
                    'order_number'            => $orderNumber,
                    'personal_information_id' => $pi->id,
                    'status'                  => 'pending',
                    'purpose'                 => Payment::PURPOSE_INSTALLMENT_INITIAL,
                    'installment_plan_id'     => $plan->id,
                ]);

                return $paymentGateway->request($initial, $orderNumber);
            });
        } catch (\Throwable $e) {
            session()->flash('error', 'خطا در ثبت طرح اقساطی: ' . $e->getMessage());
            return;
        }
    }

    protected function finalFullPrice(GradePrice $price, int $i): int
    {
        $base = $price->totalFor($i);
        if ($this->couponDiscount > 0) {
            $base = (int) round($base * (1 - $this->couponDiscount / 100));
        }
        return max(0, $base);
    }

    protected function logCouponUsage(int $userId): void
    {
        if ($this->couponDiscount > 0 && $this->couponCode) {
            $coupon = Coupons::where('code', trim($this->couponCode))->first();
            if ($coupon) {
                CouponUsage::create([
                    'coupon_id' => $coupon->id,
                    'user_id'   => $userId,
                    'used_at'   => now(),
                ]);
            }
        }
    }

    public function render()
    {
        $price = $this->gradePrice();

        $data = null;
        if ($price) {
            $i = $price->entryMonthIndex();
            $data = [
                'index'             => $i,
                'month_label'       => GradePrice::monthLabel($i),
                'discount'          => $price->discountFor($i),
                'effective_rate'    => $price->effectiveRate($i),
                'remaining_months'  => $price->remainingMonths($i),
                'total'             => $price->totalFor($i),
                'full_with_coupon'  => $this->finalFullPrice($price, $i),
                'initial'           => $price->initialPayment($i),
                'installment_count' => $price->installmentCount($i),
                'monthly'           => $price->installmentAmount($i),
                'access_ends_label' => $price->accessEndsAt()
                    ? Jalalian::fromCarbon($price->accessEndsAt())->format('Y/m/d')
                    : '—',
            ];
        }

        return view('livewire.client.purchase.index', [
            'price' => $price,
            'data'  => $data,
        ])->layout('layouts.client.app');
    }
}
