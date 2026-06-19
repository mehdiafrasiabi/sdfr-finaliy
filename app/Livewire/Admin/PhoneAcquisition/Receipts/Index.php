<?php

namespace App\Livewire\Admin\PhoneAcquisition\Receipts;

use App\Models\ChargeReceipt;
use App\Traits\NormalizesDigits;
use App\Traits\UploadFile;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

/**
 * ارسال رسید شارژ توسط مشاور جذب تلفنی و مشاهدهٔ وضعیت آن‌ها.
 */
class Index extends Component
{
    use WithPagination, WithFileUploads, UploadFile, NormalizesDigits;

    public $image;        // فایل آپلودی
    public $amount = '';  // مبلغ (اختیاری)

    public function submit(): void
    {
        $this->validate([
            'image'  => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'amount' => ['nullable'],
        ], [
            'image.required' => 'تصویر رسید را انتخاب کنید.',
            'image.image'    => 'فایل باید تصویر باشد.',
            'image.max'      => 'حجم تصویر نباید بیشتر از ۴ مگابایت باشد.',
        ]);

        $adminId = Auth::guard('admin')->id();

        // ذخیره در public/receipts/{admin_id}/{hash}.webp
        $filename = $this->uploadImageInWebpFormatSdfrStudent($this->image, $adminId, null, null, 'receipts');

        $amount = $this->amount !== '' && $this->amount !== null
            ? (int) $this->convertToEnglishDigits((string) $this->amount)
            : null;

        ChargeReceipt::create([
            'admin_id'   => $adminId,
            'image_path' => 'receipts/' . $adminId . '/' . $filename,
            'amount'     => $amount,
            'status'     => ChargeReceipt::STATUS_PENDING,
        ]);

        $this->reset(['image', 'amount']);
        $this->dispatch('success', 'رسید با موفقیت ارسال شد.');
    }

    public function render()
    {
        $receipts = ChargeReceipt::where('admin_id', Auth::guard('admin')->id())
            ->latest()
            ->paginate(12);

        return view('livewire.admin.phone-acquisition.receipts.index', [
            'receipts' => $receipts,
        ])->layout('layouts.admin.app');
    }
}
