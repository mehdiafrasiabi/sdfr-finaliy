<?php

namespace App\Livewire\Client\Purchase;

use App\Contracts\PaymentGateWayInterface;
use App\Models\CcGrade;
use App\Models\Coupons;
use App\Models\Enrollment;
use App\Models\EnrollmentPayment;
use App\Models\GradePricing;
use App\Models\Student;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Checkout extends Component
{
    use SEOTools;

    public ?int $selectedGradeId = null;
    public string $couponCode = '';
    public ?array $appliedCoupon = null;

    public function mount(): void
    {
        $this->seo()->setTitle('خرید دوره');

        $user = Auth::user();

        // اگر enrollment paid + supporter داره، به داشبورد
        $hasActive = Enrollment::where('user_id', $user->id)
            ->where('status', Enrollment::STATUS_PAID)
            ->whereNotNull('supporter_id')
            ->exists();

        if ($hasActive) {
            redirect()->route('client.profile.dashboard');
            return;
        }

        // اگر paid ولی منتظر supporter
        $hasPending = Enrollment::where('user_id', $user->id)
            ->where('status', Enrollment::STATUS_PAID)
            ->whereNull('supporter_id')
            ->exists();

        if ($hasPending) {
            redirect()->route('client.profile.waiting-for-supporter');
        }
    }

    public function applyCoupon(): void
    {
        $code = trim($this->couponCode);
        if ($code === '') {
            $this->appliedCoupon = null;
            return;
        }

        $coupon = Coupons::where('code', $code)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->first();

        if (! $coupon) {
            $this->appliedCoupon = null;
            $this->dispatch('warning', 'کد تخفیف معتبر نیست یا منقضی شده است.');
            return;
        }

        if (! $coupon->is_public && $coupon->user_id !== Auth::id()) {
            $this->appliedCoupon = null;
            $this->dispatch('warning', 'این کد تخفیف برای شما نیست.');
            return;
        }

        $this->appliedCoupon = [
            'id'    => $coupon->id,
            'code'  => $coupon->code,
            'type'  => $coupon->type,
            'value' => (int) $coupon->value,
        ];
    }

    public function removeCoupon(): void
    {
        $this->appliedCoupon = null;
        $this->couponCode = '';
    }

    protected function computeCouponDiscount(int $afterStepped): int
    {
        if (! $this->appliedCoupon) return 0;
        if ($this->appliedCoupon['type'] === 'percent') {
            return (int) floor($afterStepped * min(100, $this->appliedCoupon['value']) / 100);
        }
        return min($afterStepped, (int) $this->appliedCoupon['value']);
    }

    public function pay(PaymentGateWayInterface $gateway)
    {
        $user = Auth::user();

        if (! $this->selectedGradeId) {
            $this->dispatch('warning', 'پایه تحصیلی را انتخاب کنید.');
            return;
        }

        $pricing = GradePricing::where('cc_grade_id', $this->selectedGradeId)
            ->where('is_active', true)
            ->latest('id')
            ->first();

        if (! $pricing) {
            $this->dispatch('warning', 'برای این پایه قیمتی تعریف نشده است.');
            return;
        }

        $breakdown = $pricing->computeFor();

        $afterStepped = max(0, $breakdown['base_price'] - $breakdown['stepped_discount'] - $breakdown['grade_discount']);
        $couponDiscount = $this->computeCouponDiscount($afterStepped);
        $final = max(0, $afterStepped - $couponDiscount);

        $student = Student::firstOrCreate(
            ['user_id' => $user->id],
            ['is_trial' => false]
        );

        $enrollment = DB::transaction(function () use ($user, $student, $pricing, $breakdown, $couponDiscount, $final) {
            $enrollment = Enrollment::create([
                'user_id'          => $user->id,
                'student_id'       => $student->id,
                'cc_grade_id'      => $pricing->cc_grade_id,
                'grade_pricing_id' => $pricing->id,
                'coupon_id'        => $this->appliedCoupon['id'] ?? null,
                'base_price'       => $breakdown['base_price'],
                'monthly_step'     => $breakdown['monthly_step'],
                'elapsed_months'   => $breakdown['elapsed_months'],
                'stepped_discount' => $breakdown['stepped_discount'],
                'grade_discount'   => $breakdown['grade_discount'],
                'coupon_discount'  => $couponDiscount,
                'final_amount'     => $final,
                'status'           => Enrollment::STATUS_PENDING,
            ]);

            return $enrollment;
        });

        // اگر مبلغ صفر است (تخفیف ۱۰۰٪)، مستقیم paid کن
        if ($final === 0) {
            $enrollment->update([
                'status'  => Enrollment::STATUS_PAID,
                'paid_at' => now(),
            ]);

            session()->flash('paymentSuccess', 'ثبت‌نام شما با تخفیف کامل ثبت شد.');
            return redirect()->route('client.profile.waiting-for-supporter');
        }

        $payment = EnrollmentPayment::create([
            'enrollment_id' => $enrollment->id,
            'gateway'       => config('services.payment.default', 'zarinpal'),
            'authority'     => 'ENR-' . $enrollment->id . '-' . uniqid(),
            'amount'        => $final,
            'status'        => EnrollmentPayment::STATUS_PENDING,
        ]);

        session([
            'payment_intent' => [
                'kind' => 'enrollment',
                'id'   => $payment->id,
            ],
        ]);

        return $gateway->request(
            $final,
            route('client.payment.callback'),
            'ثبت‌نام دوره - پایه ' . optional($pricing->ccGrade)->name
        );
    }

    public function render()
    {
        $grades = CcGrade::where('is_active', true)
            ->with('educationLevel')
            ->orderBy('level_id')
            ->orderBy('name')
            ->get();

        $pricing = null;
        $breakdown = null;
        $couponDiscount = 0;
        $finalAmount = 0;

        if ($this->selectedGradeId) {
            $pricing = GradePricing::where('cc_grade_id', $this->selectedGradeId)
                ->where('is_active', true)
                ->latest('id')
                ->first();

            if ($pricing) {
                $breakdown = $pricing->computeFor();
                $afterStepped = max(0, $breakdown['base_price'] - $breakdown['stepped_discount'] - $breakdown['grade_discount']);
                $couponDiscount = $this->computeCouponDiscount($afterStepped);
                $finalAmount = max(0, $afterStepped - $couponDiscount);
            }
        }

        return view('livewire.client.purchase.checkout', [
            'grades'         => $grades,
            'pricing'        => $pricing,
            'breakdown'      => $breakdown,
            'couponDiscount' => $couponDiscount,
            'finalAmount'    => $finalAmount,
        ])->layout('layouts.client.app');
    }
}
