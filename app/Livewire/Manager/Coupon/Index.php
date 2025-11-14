<?php

namespace App\Livewire\Manager\Coupon;
use App\Models\Coupons;
use App\Models\User;
use Artesaos\SEOTools\Traits\SEOTools;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination,SEOTools;

    public $coupon_id, $code, $type, $value, $limit, $min_purchase, $expires_at;
    public $is_active = true, $user_id = null, $is_public = true, $search = '';

    public function mount()
    {
        $this->seoConfig();
    }

    public function seoConfig()
    {
        $this->seo()
            ->setTitle('کد تخفیف');
    }
    public function submit($formData)
    {
        $formData['user_id'] = !empty($formData['user_id']) ? (int)$formData['user_id'] : null;

        $validator = Validator::make($formData,
            [
                'code' => 'required|string|unique:coupons,code,max:20' . $this->coupon_id,
                'type' => 'required|in:fixed,percent',
                'value' => 'required|integer|min:1',
                'limit' => 'required|integer|min:1',
                'min_purchase' => 'required|integer|min:0',
                'expires_at' => 'required|date|after:today',
                'is_active' => 'required|boolean',
                'is_public' => 'required|boolean',
                'user_id' => 'nullable|exists:users,id',
            ],
            [
                'code.required' => 'وارد کردن کد تخفیف الزامی است.',
                'code.max' => 'حداکثر کارکتر : 20',
                'code.string' => 'کد تخفیف باید متن باشد.',
                'code.unique' => 'این کد قبلاً ثبت شده است.',
                'type.required' => 'نوع تخفیف الزامی است.',
                'type.in' => 'نوع تخفیف باید نقدی یا درصدی باشد.',
                'value.required' => 'مقدار تخفیف الزامی است.',
                'value.integer' => 'مقدار تخفیف باید عدد باشد.',
                'limit.required' => 'تعداد دفعات استفاده الزامی است.',
                'limit.integer' => 'تعداد دفعات استفاده باید عدد باشد.',
                'min_purchase.required' => 'حداقل خرید الزامی است.',
                'min_purchase.integer' => 'حداقل خرید باید عدد باشد.',
                'expires_at.required' => 'تاریخ انقضا الزامی است.',
                'expires_at.date' => 'فرمت تاریخ معتبر نیست.',
                'expires_at.after' => 'تاریخ انقضا باید بعد از امروز باشد.',
                'is_active.required' => 'وضعیت فعال بودن الزامی است.',
                'is_public.required' => 'مشخص‌کردن نوع عمومی یا خصوصی بودن الزامی است.',
            ]
        );
        $validator->validate();
        Coupons::updateOrCreate(
            ['id' => $this->coupon_id],
            [
                'code' => $formData['code'],
                'type' => $formData['type'],
                'value' => $formData['value'],
                'limit' => $formData['limit'],
                'min_purchase' => $formData['min_purchase'],
                'expires_at' => Carbon::parse($formData['expires_at']),
                'is_active' => $formData['is_active'],
                'is_public' => $formData['is_public'],
                'user_id' => $formData['user_id'],
            ]
        );

        $this->dispatch('success', 'کد تخفیف با موفقیت ذخیره شد.');
        $this->reset();
        $this->resetPage();
    }

    public function edit($id)
    {
        $coupon = Coupons::findOrFail($id);
        $this->coupon_id = $coupon->id;
        $this->code = $coupon->code;
        $this->type = $coupon->type;
        $this->value = $coupon->value;
        $this->limit = $coupon->limit;
        $this->min_purchase = $coupon->min_purchase;
        $this->expires_at = $coupon->expires_at;
        $this->is_active = $coupon->is_active;
        $this->is_public = $coupon->is_public;
        $this->user_id = $coupon->user_id;
    }

    public function delete($id)
    {
        Coupons::findOrFail($id)->delete();
        $this->dispatch('success', 'کد تخفیف حذف شد.');
        $this->reset();
    }

    public function render()
    {
        $coupons = Coupons::query()->with('user')
            ->when($this->search, fn($q) => $q->where('code', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate(10);

        return view('livewire.manager.coupon.index', [
            'users' => User::select('id', 'name')->get(),
            'coupons' => $coupons,
        ])->layout('layouts.manager.app');
    }
}
