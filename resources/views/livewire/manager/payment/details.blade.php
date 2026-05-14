<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('manager.dashboard.analytics') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">جزئیات پرداخت‌ها</li>
            </ol>
        </nav>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="row align-items-center mb-3">
                <div class="col-md-6">
                    <input type="text" wire:model.live.debounce.400ms="search" class="form-control"
                           placeholder="جستجو بر اساس کد سفارش / مرجع / کارت / نام / موبایل…">
                </div>
                <div class="col-md-3">
                    <select wire:model.live="statusFilter" class="form-select">
                        <option value="all">همه وضعیت‌ها</option>
                        <option value="completed">موفق</option>
                        <option value="failed">ناموفق</option>
                        <option value="pending">در انتظار</option>
                        <option value="cancelled">کنسل شده</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered align-middle small">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>تاریخ/ساعت</th>
                            <th>کاربر</th>
                            <th>درگاه</th>
                            <th>کد سفارش</th>
                            <th>کد مرجع</th>
                            <th>وضعیت</th>
                            <th>مبلغ (تومان)</th>
                            <th>کد تخفیف</th>
                            <th>محصول</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($payments as $p)
                        @php
                            $usages = $userCoupons[$p->user_id] ?? collect();
                            $latestUsage = $usages
                                ->filter(fn($u) => !$u->used_at || $u->used_at <= $p->created_at)
                                ->last();
                            $couponCode = $latestUsage?->coupon?->code ?? '—';
                            $statusColor = match ($p->status) {
                                'completed' => 'success',
                                'failed'    => 'danger',
                                'pending'   => 'warning',
                                'cancelled' => 'secondary',
                                default     => 'secondary',
                            };
                        @endphp
                        <tr>
                            <td>{{ $p->id }}</td>
                            <td>{{ \Morilog\Jalali\Jalalian::fromCarbon($p->created_at)->format('Y/m/d H:i:s') }}</td>
                            <td>
                                <strong>{{ $p->user?->name ?? '—' }}</strong>
                                <div class="text-muted">#{{ $p->user_id }} — {{ $p->user?->mobile ?? '—' }}</div>
                            </td>
                            <td>{{ $p->order?->paymentMethod?->name ?? '—' }}</td>
                            <td>{{ $p->order_number ?? '—' }}</td>
                            <td>{{ $p->refNumber ?? '—' }}</td>
                            <td>
                                <span class="badge bg-{{ $statusColor }}">
                                    {{ $this->statusLabel($p->status) }}
                                </span>
                            </td>
                            <td>{{ number_format($p->amount) }}</td>
                            <td>{{ $couponCode }}</td>
                            <td>
                                @if ($p->order?->orderItems?->isNotEmpty())
                                    @foreach ($p->order->orderItems as $item)
                                        <div>{{ $item->product?->title ?? '—' }}</div>
                                    @endforeach
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="10" class="text-center text-muted py-4">پرداختی یافت نشد.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $payments->links() }}</div>
        </div>
    </div>
</div>
