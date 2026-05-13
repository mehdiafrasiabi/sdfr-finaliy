<?php

namespace App\Livewire\Client\Profile;

use App\Contracts\PaymentGateWayInterface;
use App\Models\GiftCode;
use App\Models\WalletCharge;
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
        $this->seo()->setTitle('کیف پول');
    }

    public function chargeWallet(PaymentGateWayInterface $paymentGateway)
    {
        $validator = Validator::make(
            ['amount' => $this->chargeAmount],
            ['amount' => 'required|integer|min:10000|max:500000000'],
            [
                'amount.required' => 'وارد کردن مبلغ الزامی است.',
                'amount.integer'  => 'مبلغ باید عدد باشد.',
                'amount.min'      => 'حداقل مبلغ شارژ ۱۰,۰۰۰ تومان است.',
                'amount.max'      => 'حداکثر مبلغ شارژ ۵۰۰,۰۰۰,۰۰۰ تومان است.',
            ]
        );
        if ($validator->fails()) {
            $this->dispatch('warning', $validator->errors()->first());
            return;
        }

        $user = Auth::user();

        return DB::transaction(function () use ($user, $paymentGateway) {
            $charge = WalletCharge::create([
                'user_id'   => $user->id,
                'amount'    => $this->chargeAmount,
                'reference' => 'WC-' . Str::uuid()->toString(),
                'status'    => 'pending',
            ]);

            session([
                'payment_intent' => [
                    'kind' => 'wallet_charge',
                    'id'   => $charge->id,
                ],
            ]);

            return $paymentGateway->request(
                (int) $this->chargeAmount,
                route('client.payment.callback'),
                'شارژ کیف پول کاربر ' . $user->id
            );
        });
    }

    public function applyGiftCode()
    {
        $validator = Validator::make(
            ['code' => $this->giftCode],
            ['code' => 'required|string|max:50'],
            ['code.required' => 'کد هدیه را وارد کنید.']
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
            $this->dispatch('warning', 'این کد هدیه قابل استفاده نیست.');
            return;
        }

        DB::beginTransaction();
        try {
            $amount = $giftCode->use($user);
            $wallet = $user->getOrCreateWallet();
            $wallet->deposit($amount, 'شارژ با کد هدیه: ' . $giftCode->code, 'gift');
            DB::commit();
            $this->giftCode = '';
            $this->dispatch('success', 'کد هدیه با موفقیت اعمال شد. مبلغ ' . number_format($amount) . ' تومان به کیف پول اضافه شد.');
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
            'wallet'       => $wallet,
            'transactions' => $transactions,
        ])->layout('layouts.client.app');
    }
}
