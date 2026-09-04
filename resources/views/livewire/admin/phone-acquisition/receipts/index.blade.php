<div class="pa-page">
    @include('livewire.admin.phone-acquisition._styles')
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">رسیدهای شارژ</li>
            </ol>
        </nav>
    </div>

    <div class="row g-3">
        {{-- فرم ارسال رسید --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">ارسال رسید جدید</h5>

                    <form wire:submit.prevent="submit">
                        <div class="mb-3">
                            <label class="form-label">تصویر رسید <span class="text-danger">*</span></label>
                            <input type="file" wire:model="image" class="form-control" accept="image/*">
                            @error('image')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            <div wire:loading wire:target="image" class="text-muted small mt-1">در حال بارگذاری…</div>
                        </div>

                        @if ($image)
                            <div class="mb-3">
                                <img src="{{ $image->temporaryUrl() }}" class="img-fluid rounded border" alt="پیش‌نمایش">
                            </div>
                        @endif

                        <div class="mb-3">
                            <label class="form-label">مبلغ (تومان) — اختیاری</label>
                            <input type="text" wire:model="amount" class="form-control" placeholder="مثلاً ۵۰۰۰۰">
                            @error('amount')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled" wire:target="submit,image">
                            ارسال رسید
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- فهرست رسیدها --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">رسیدهای ارسالی من</h5>
                    <div class="row g-3">
                        @forelse ($receipts as $receipt)
                            <div class="col-md-6">
                                <div class="card border">
                                    <a href="{{ $receipt->image_url }}" target="_blank">
                                        <img src="{{ $receipt->image_url }}" class="card-img-top" style="height:160px;object-fit:cover" alt="رسید">
                                    </a>
                                    <div class="card-body py-2">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-{{ $receipt->status_color }}">{{ $receipt->status_label }}</span>
                                            <small class="text-muted">{{ jalali($receipt->created_at)->format('%d %B %Y') }}</small>
                                        </div>
                                        @if ($receipt->amount)
                                            <div class="small mt-1">مبلغ: {{ number_format($receipt->amount) }} تومان</div>
                                        @endif
                                        @if ($receipt->status === \App\Models\ChargeReceipt::STATUS_REJECTED && $receipt->note)
                                            <div class="text-danger small mt-1">علت رد: {{ $receipt->note }}</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <p class="text-center text-muted py-4">هنوز رسیدی ارسال نکرده‌اید.</p>
                            </div>
                        @endforelse
                    </div>
                    <div class="mt-3">{{ $receipts->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
