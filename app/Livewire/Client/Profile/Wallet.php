<?php
namespace App\Livewire\Client\Profile;
use App\Contracts\PaymentGateWayInterface;
use App\Models\GiftCode;
use App\Models\Order;
use App\Models\Payment;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithPagination;
class Wallet extends Component
{
    use SEOTools, WithPagination;
    public $chargeAmount = '';
    public $giftCode = '';
    public function mount()
    {
        $this->seoConfig();
    }
    public function seoConfig()
    {
        $this->seo()->setTitle('کیف پول');
    }
    public function chargeWallet(PaymentGateWayInterface $paymentGateway)
    {
        $validator = Validator::make(
            ['amount' => $this->chargeAmount],
            ['amount' => 'required|integer|min:10000|max:500000000'],
            [
                'amount.required' => 'وارد کردن مبلغ الزامی است.',
                'amount.integer' => 'مبلغ باید عدد باشد.',
                'amount.min' => 'حداقل مبلغ شارژ ۱۰,۰۰۰ تومان است.',
                'amount.max' => 'حداکثر مبلغ شارژ ۵۰۰,۰۰۰,۰۰۰ تومان است.',
            ]
        );
        if ($validator->fails()) {
            $this->dispatch('warning', $validator->errors()->first());
            return;
        }
        $user = Auth::user();
        $orderNumber = 'WALLET-' . Str::uuid()->toString();
        DB::beginTransaction();
        try {
            $order = Order::create([
                'amount' => $this->chargeAmount,
                'order_number' => $orderNumber,
                'user_id' => $user->id,
                'payment_method_id' => 1,
                'status' => 'pending',
            ]);
            $personalInfo = $user->personalInformation;
            if (!$personalInfo) {
                $personalInfo = \App\Models\PersonalInformation::create([
                    'name' => $user->name,
                    'user_id' => $user->id,
                    'address' => '-',
                    'place_of_birth' => '-',
                    'father_name' => '-',
                    'code_mell' => '0000000000',
                    'father_mobile' => $user->mobile ?? '09000000000',
                    'mother_mobile' => $user->mobile ?? '09000000000',
                    'birth_date' => now(),
                    'grade' => '10',
                    'field' => 'math',
                    'state_id' => 1,
                    'city_id' => 1,
                ]);
            }
            Payment::create([
                'order_id' => $order->id,
                'user_id' => $user->id,
                'amount' => $this->chargeAmount,
                'order_number' => $orderNumber,
                'personal_information_id' => $personalInfo->id,
            ]);
            session(['wallet_charge' => true, 'wallet_charge_amount' => $this->chargeAmount]);
            DB::commit();
            return $paymentGateway->request($this->chargeAmount, $orderNumber);
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            $this->dispatch('warning', 'خطا در فرآیند شارژ کیف پول');
        }
    }
    public function applyGiftCode()
    {
        $validator = Validator::make(
            ['code' => $this->giftCode],
            ['code' => 'required|string|max:50'],
            [
                'code.required' => 'کد هدیه را وارد کنید.',
            ]
        );
        if ($validator->fails()) {
            $this->dispatch('warning', $validator->errors()->first());
            return;
        }
        $giftCode = GiftCode::where('code', $this->giftCode)->first();
        if (!$giftCode) {
            $this->dispatch('warning', 'کد هدیه یافت نشد.');
            return;
        }
        $user = Auth::user();
        if (!$giftCode->canBeUsedBy($user)) {
            if (!$giftCode->is_active) {
                $this->dispatch('warning', 'این کد هدیه غیرفعال است.');
            } elseif ($giftCode->expires_at->isPast()) {
                $this->dispatch('warning', 'این کد هدیه منقضی شده است.');
            } elseif ($giftCode->type === 'for_one' && $giftCode->user_id !== $user->id) {
                $this->dispatch('warning', 'این کد هدیه برای شما نیست.');
            } elseif ($giftCode->usages()->where('user_id', $user->id)->exists()) {
                $this->dispatch('warning', 'شما قبلا از این کد هدیه استفاده کرده‌اید.');
            } else {
                $this->dispatch('warning', 'این کد هدیه قابل استفاده نیست.');
            }
            return;
        }
        DB::beginTransaction();
        try {
            $amount = $giftCode->use($user);
            $wallet = $user->getOrCreateWallet();
            $wallet->deposit($amount, 'شارژ با کد هدیه: ' . $giftCode->code, 'gift');
            DB::commit();
            $this->giftCode = '';
            $this->dispatch('success', 'کد هدیه با موفقیت اعمال شد. مبلغ ' . number_format($amount) . ' تومان به کیف پول شما اضافه شد.');
        } catch (\Throwable $e) {
            DB::rollBack();
            report($e);
            $this->dispatch('warning', $e->getMessage());
        }
    }
    public function render()
    {
        $user = Auth::user();
        $wallet = $user->getOrCreateWallet();
        $transactions = $wallet->transactions()->latest()->paginate(10);
        return view('livewire.client.profile.wallet', [
            'wallet' => $wallet,
            'transactions' => $transactions,
        ])->layout('layouts.client.app');
    }
}
