<?php

namespace App\Livewire\Client\Purchase;

use App\Contracts\PaymentGateWayInterface;
use App\Models\Coupons;
use App\Models\CouponUsage;
use App\Models\GradePrice;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\PersonalInformation;
use App\Models\TrialWeek;
use App\Services\TrialWeekService;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;

/**
 * B-2 (بازنویسی) — صفحهٔ پرداخت اختصاصی.
 *
 *   - دو دکمه: «شروع آزمایشی» و «پرداخت»
 *   - «شروع آزمایشی»: فقط TrialWeek می‌سازد (رایگان) و کاربر به صفحهٔ
 *     waiting-for-supporter منتقل می‌شود.
 *   - «پرداخت»: مشابه فلوی `Cart\Info::submit` یک Order + OrderItem + Payment
 *     در وضعیت pending ایجاد می‌کند و سپس کاربر را مستقیماً به درگاه زیبال
 *     redirect می‌کند (callback هندل می‌شود توسط `Payment\Callback`).
 *   - کوپن: یک‌بار-به-ازای-کاربر، با درصد تخفیف.
 *   - نمایش قیمت پلکانی این ماه + ماه بعد + جدول ماه‌به‌ماه.
 */
class Index extends Component
{
    use SEOTools;

    public string  $couponCode    = '';
    public ?string $couponError   = null;
    public ?string $couponNotice  = null;
    public int     $couponDiscount = 0; // درصد تخفیف کوپن

    public function mount(): void
    {
        $this->seo()->setTitle('پرداخت دوره');

        $user = Auth::user();
        if (! $user) {
            return;
        }

        // اگر پرداخت موفق دارد → داشبورد
        $hasPaid = $user->payments()->where('status', 'completed')->exists();
        if ($hasPaid) {
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

    // ─────────────── کوپن ───────────────

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

        $coupon = Coupons::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (! $coupon) {
            $this->couponError = 'کد تخفیف نامعتبر است.';
            return;
        }

        $userId = Auth::id();
        $alreadyUsed = CouponUsage::where('user_id', $userId)
            ->where('coupon_id', $coupon->id)
            ->exists();
        if ($alreadyUsed) {
            $this->couponError = 'این کد تخفیف قبلاً استفاده شده است.';
            return;
        }

        $percent = (int) ($coupon->discount_percentage ?? $coupon->percentage ?? 0);
        $this->couponDiscount = max(0, min(100, $percent));
        $this->couponNotice = "تخفیف {$this->couponDiscount} درصدی روی این ماه اعمال شد.";
    }

    public function removeCoupon(): void
    {
        $this->couponCode     = '';
        $this->couponDiscount = 0;
        $this->couponError    = null;
        $this->couponNotice   = null;
    }

    // ─────────────── شروع آزمایشی (رایگان) ───────────────

    public function startTrial(TrialWeekService $service)
    {
        $user = Auth::user();
        if (! $user) {
            return $this->redirect(route('client.auth.login'), navigate: true);
        }

        if ($user->trialWeek) {
            return $this->redirect(route('client.profile.waiting-for-supporter'), navigate: true);
        }

        $pi = PersonalInformation::where('user_id', $user->id)->first();
        if (! $pi || ! $pi->grade) {
            session()->flash('error', 'ابتدا اطلاعات شخصی خود را تکمیل کنید.');
            return;
        }

        $service->start(
            $user,
            (int) $pi->grade,
            $pi->field ?: null,
            $pi->father_mobile ?? '',
            $pi->mother_mobile ?? '',
        );

        return $this->redirect(route('client.profile.waiting-for-supporter'), navigate: true);
    }

    // ─────────────── خرید واقعی (Zibal) ───────────────

    /**
     * ایجاد Order/OrderItem/Payment و redirect مستقیم به درگاه زیبال.
     */
    public function pay(PaymentGateWayInterface $paymentGateway)
    {
        $user = Auth::user();
        if (! $user) {
            return $this->redirect(route('client.auth.login'), navigate: true);
        }

        $price = $this->gradePrice();
        if (! $price) {
            session()->flash('error', 'قیمتی برای پایهٔ شما تعریف نشده است.');
            return;
        }

        $pi = PersonalInformation::where('user_id', $user->id)->first();
        if (! $pi) {
            session()->flash('error', 'ابتدا اطلاعات شخصی خود را تکمیل کنید.');
            return;
        }

        $productId = (int) config('sdfr.course_product_id');
        if ($productId <= 0) {
            session()->flash('error', 'محصول دوره در سیستم تعریف نشده است.');
            return;
        }

        $finalPrice = $this->finalPriceForCurrentMonth($price);
        if ($finalPrice <= 0) {
            session()->flash('error', 'مبلغ نهایی نامعتبر است.');
            return;
        }

        $orderNumber = 'SDFR-' . Str::uuid()->toString();

        try {
            DB::transaction(function () use ($user, $pi, $finalPrice, $orderNumber, $productId) {
                $order = Order::query()->create([
                    'amount'            => $finalPrice,
                    'order_number'      => $orderNumber,
                    'user_id'           => $user->id,
                    'payment_method_id' => 1, // Zibal
                    'paid_with_wallet'  => false,
                    'wallet_amount'     => 0,
                    'status'            => 'pending',
                ]);

                OrderItem::query()->create([
                    'price'      => $finalPrice,
                    'order_id'   => $order->id,
                    'product_id' => $productId,
                ]);

                Payment::query()->create([
                    'order_id'                => $order->id,
                    'user_id'                 => $user->id,
                    'amount'                  => $finalPrice,
                    'order_number'            => $orderNumber,
                    'personal_information_id' => $pi->id,
                    'status'                  => 'pending',
                ]);

                if ($this->couponDiscount > 0 && $this->couponCode) {
                    $coupon = Coupons::where('code', trim($this->couponCode))->first();
                    if ($coupon) {
                        CouponUsage::create([
                            'coupon_id' => $coupon->id,
                            'user_id'   => $user->id,
                            'used_at'   => now(),
                        ]);
                    }
                }
            });
        } catch (\Throwable $e) {
            session()->flash('error', 'خطا در ثبت سفارش: ' . $e->getMessage());
            return;
        }

        return $paymentGateway->request($finalPrice, $orderNumber);
    }

    /**
     * قیمت ماه جاری پس از اعمال تخفیف ماهانه + تخفیف روز خاص + کوپن.
     */
    protected function finalPriceForCurrentMonth(GradePrice $price): int
    {
        $base = $price->effectivePrice();
        if ($this->couponDiscount > 0) {
            $base = (int) round($base * (1 - $this->couponDiscount / 100));
        }
        return max(0, $base);
    }

    public function render()
    {
        $price = $this->gradePrice();

        $monthsTable = [];
        $currentMonthIndex = null;
        if ($price) {
            $currentMonthIndex = $price->monthIndex();
            $months = $price->months_count;
            for ($i = 0; $i < $months; $i++) {
                [$start, $end] = $price->monthRange($i);
                $monthsTable[] = [
                    'index'           => $i,
                    'is_current'      => $i === $currentMonthIndex,
                    'stepped'         => $price->priceAtMonth($i),
                    'effective'       => $price->effectivePriceForMonth($i, $start),
                    'jalali_label'    => \Morilog\Jalali\Jalalian::fromCarbon($start)->format('F Y'),
                    'has_discount'    => optional($price->monthDiscountFor($i))->discount_percentage > 0,
                    'discount_pct'    => (int) optional($price->monthDiscountFor($i))->discount_percentage,
                ];
            }
        }

        $effectiveThisMonth = $price ? $this->finalPriceForCurrentMonth($price) : null;
        $nextMonthPrice     = $price ? $price->next_month_stepped_price : null;
        $activeDailyDiscount = $price?->activeDailyDiscount();

        $pendingTrial = TrialWeek::where('user_id', Auth::id())
            ->where('status', TrialWeek::STATUS_PENDING)
            ->latest()
            ->first();

        return view('livewire.client.purchase.index', [
            'price'               => $price,
            'monthsTable'         => $monthsTable,
            'currentMonthIndex'   => $currentMonthIndex,
            'effectiveThisMonth'  => $effectiveThisMonth,
            'nextMonthPrice'      => $nextMonthPrice,
            'activeDailyDiscount' => $activeDailyDiscount,
            'pendingTrial'        => $pendingTrial,
        ])->layout('layouts.client.app');
    }
}
