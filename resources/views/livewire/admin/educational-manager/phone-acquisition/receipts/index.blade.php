<div class="em-page">
    @include('livewire.admin.educational-manager._styles')
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">رسیدهای شارژ</li>
            </ol>
        </nav>
    </div>

    <section class="em-hero">
        <div class="em-hero-main"><span class="em-hero-icon"><i class="fi fi-rr-receipt"></i></span><div><h3>رسیدهای شارژ مشاوران</h3><p>تصویر رسید، مبلغ و نتیجه بررسی هر درخواست شارژ را مدیریت کنید.</p></div></div>
        <span class="badge bg-primary-subtle text-primary">بررسی مالی جذب تلفنی</span>
    </section>

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-0">بررسی رسیدهای شارژ</h4>
                </div>
                <div class="col-md-4">
                    <select wire:model.live="statusFilter" class="form-select">
                        <option value="">همه</option>
                        <option value="pending">در انتظار بررسی</option>
                        <option value="approved">تایید شده</option>
                        <option value="rejected">رد شده</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="widget-content widget-content-area">
            <div class="row g-3">
                @forelse ($receipts as $receipt)
                    <div class="col-md-3 col-sm-6">
                        <div class="card border h-100">
                            <a href="{{ $receipt->image_url }}" target="_blank">
                                <img src="{{ $receipt->image_url }}" class="card-img-top" style="height:170px;object-fit:cover" alt="رسید">
                            </a>
                            <div class="card-body py-2">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <strong class="small">{{ $receipt->admin?->name ?? '—' }}</strong>
                                    <span class="badge bg-{{ $receipt->status_color }}">{{ $receipt->status_label }}</span>
                                </div>
                                @if ($receipt->amount)
                                    <div class="small">مبلغ: {{ number_format($receipt->amount) }} تومان</div>
                                @endif
                                <div class="text-muted small">{{ jalali($receipt->created_at)->format('%d %B %Y') }}</div>

                                @if ($receipt->status === \App\Models\ChargeReceipt::STATUS_REJECTED && $receipt->note)
                                    <div class="text-danger small mt-1">علت رد: {{ $receipt->note }}</div>
                                @endif

                                @if ($receipt->status === \App\Models\ChargeReceipt::STATUS_PENDING)
                                    <div class="d-flex gap-2 mt-2">
                                        <button wire:click="approveReceipt({{ $receipt->id }})"
                                                class="btn btn-sm btn-success flex-fill">تایید</button>
                                        <button wire:click="openReject({{ $receipt->id }})"
                                                class="btn btn-sm btn-outline-danger flex-fill">رد</button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted py-4">رسیدی یافت نشد.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-3">{{ $receipts->links() }}</div>
        </div>
    </div>

    {{-- مودال رد کردن --}}
    @if ($rejectingId)
        <div class="modal d-block" tabindex="-1" style="background:rgba(0,0,0,.4)">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">رد رسید</h5>
                        <button type="button" class="btn-close" wire:click="closeReject"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">علت رد کردن <span class="text-danger">*</span></label>
                        <textarea wire:model="rejectNote" rows="3" class="form-control"></textarea>
                        @error('rejectNote')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="closeReject">انصراف</button>
                        <button class="btn btn-danger" wire:click="rejectReceipt">ثبت رد</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
