<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex align-items-center flex-wrap gap-2">
                    <h4 class="card-title mb-0 flex-grow-1">سفارشات و تراکنش‌ها</h4>
                    <form wire:submit.prevent="export">
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="ri-download-2-fill align-middle me-1"></i> دانلود اکسل
                        </button>
                    </form>
                </div>

                <div class="card-body border-bottom border-bottom-dashed">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label small text-muted mb-1">جستجو</label>
                            <div class="search-box">
                                <input wire:model.live.debounce.400ms="search" type="text"
                                       class="form-control" placeholder="شماره سفارش، مرجع، کارت، نام یا موبایل...">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted mb-1">وضعیت</label>
                            <div class="btn-group flex-wrap" role="group">
                                @foreach (['all' => 'همه'] + \App\Livewire\Manager\Transaction\Index::STATUS_LABELS as $key => $label)
                                    <button type="button" wire:click="setStatus('{{ $key }}')"
                                            class="btn btn-sm {{ $status === $key ? 'btn-primary' : 'btn-outline-primary' }}">
                                        {{ $label }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted mb-1">نوع تراکنش</label>
                            <select wire:model.live="purpose" class="form-select form-select-sm">
                                <option value="all">همه</option>
                                @foreach (\App\Livewire\Manager\Transaction\Index::PURPOSE_LABELS as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table class="table align-middle table-nowrap mb-0">
                            <thead class="table-light">
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th>شماره سفارش</th>
                                <th>کاربر</th>
                                <th>موبایل</th>
                                <th>نوع</th>
                                <th>وضعیت</th>
                                <th>مبلغ</th>
                                <th>مرجع درگاه</th>
                                <th>تاریخ</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($transactions as $item)
                                @php $shortNo = \Illuminate\Support\Str::afterLast($item->order_number, '-'); @endphp
                                <tr>
                                    <th scope="row">{{ $loop->iteration + $transactions->firstItem() - 1 }}</th>
                                    <td><span class="text-muted">…{{ $shortNo }}</span></td>
                                    <td>{{ $item->order->user->name ?? $item->user->name ?? '—' }}</td>
                                    <td>{{ $item->order->user->mobile ?? $item->user->mobile ?? '—' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $this->purposeColor($item->purpose) }}-subtle text-{{ $this->purposeColor($item->purpose) }}">
                                            {{ \App\Livewire\Manager\Transaction\Index::PURPOSE_LABELS[$item->purpose] ?? 'خرید دوره' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $this->statusColor($item->status) }}-subtle text-{{ $this->statusColor($item->status) }}">
                                            {{ \App\Livewire\Manager\Transaction\Index::STATUS_LABELS[$item->status] ?? $item->status }}
                                        </span>
                                    </td>
                                    <td class="fw-semibold">{{ number_format($item->amount) }} ت</td>
                                    <td>{{ $item->refNumber ?? '—' }}</td>
                                    <td>{{ jalali($item->created_at)->format('%Y/%m/%d - H:i') }}</td>
                                    <td>
                                        <button wire:click="showDetail({{ $item->id }})" class="btn btn-soft-primary btn-sm">
                                            <i class="ri-eye-line align-bottom"></i> جزئیات
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted py-5">
                                        تراکنشی با این فیلترها یافت نشد.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {{ $transactions->links('layouts.admin.pagination') }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══ مودال جزئیات سفارش/تراکنش ═══ --}}
    @if($selected)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,.5);" wire:key="tx-detail-{{ $selected->id }}">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">جزئیات تراکنش</h5>
                        <button type="button" class="btn-close" wire:click="closeDetail"></button>
                    </div>
                    <div class="modal-body">
                        {{-- اطلاعات پرداخت --}}
                        <h6 class="text-muted text-uppercase fw-semibold mb-2">پرداخت</h6>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6"><small class="text-muted">شماره سفارش:</small> <span dir="ltr">{{ $selected->order_number }}</span></div>
                            <div class="col-md-6"><small class="text-muted">مبلغ:</small> <strong>{{ number_format($selected->amount) }} تومان</strong></div>
                            <div class="col-md-6"><small class="text-muted">وضعیت:</small>
                                <span class="badge bg-{{ $this->statusColor($selected->status) }}-subtle text-{{ $this->statusColor($selected->status) }}">
                                    {{ \App\Livewire\Manager\Transaction\Index::STATUS_LABELS[$selected->status] ?? $selected->status }}
                                </span>
                            </div>
                            <div class="col-md-6"><small class="text-muted">نوع:</small>
                                <span class="badge bg-{{ $this->purposeColor($selected->purpose) }}-subtle text-{{ $this->purposeColor($selected->purpose) }}">
                                    {{ \App\Livewire\Manager\Transaction\Index::PURPOSE_LABELS[$selected->purpose] ?? 'خرید دوره' }}
                                </span>
                            </div>
                            <div class="col-md-6"><small class="text-muted">مرجع درگاه:</small> {{ $selected->refNumber ?? '—' }}</div>
                            <div class="col-md-6"><small class="text-muted">شماره کارت:</small> {{ $selected->cardNumber ?? '—' }}</div>
                            <div class="col-md-6"><small class="text-muted">تاریخ:</small> {{ jalali($selected->created_at)->format('%Y/%m/%d - H:i') }}</div>
                        </div>

                        {{-- کاربر --}}
                        <h6 class="text-muted text-uppercase fw-semibold mb-2">کاربر</h6>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6"><small class="text-muted">نام:</small> {{ $selected->user->name ?? $selected->order->user->name ?? '—' }}</div>
                            <div class="col-md-6"><small class="text-muted">موبایل:</small> {{ $selected->user->mobile ?? $selected->order->user->mobile ?? '—' }}</div>
                        </div>

                        {{-- سفارش و اقلام --}}
                        @if($selected->order)
                            <h6 class="text-muted text-uppercase fw-semibold mb-2">سفارش</h6>
                            <div class="mb-2"><small class="text-muted">وضعیت سفارش:</small> {{ $selected->order->status }}</div>
                            @if($selected->order->orderItems && $selected->order->orderItems->count())
                                <ul class="list-group mb-3">
                                    @foreach($selected->order->orderItems as $oi)
                                        <li class="list-group-item d-flex justify-content-between">
                                            <span>خرید دوره</span>
                                            <span class="fw-semibold">{{ number_format($oi->price) }} ت</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        @endif

                        {{-- طرح اقساطی مرتبط --}}
                        @if($selected->installmentPlan)
                            @php $plan = $selected->installmentPlan; @endphp
                            <h6 class="text-muted text-uppercase fw-semibold mb-2">طرح اقساطی مرتبط</h6>
                            <div class="row g-2">
                                <div class="col-md-4"><small class="text-muted">کل:</small> {{ number_format($plan->total_amount) }} ت</div>
                                <div class="col-md-4"><small class="text-muted">پیش‌پرداخت:</small> {{ number_format($plan->initial_amount) }} ت</div>
                                <div class="col-md-4"><small class="text-muted">هر قسط:</small> {{ number_format($plan->monthly_amount) }} ت</div>
                                <div class="col-md-4"><small class="text-muted">تعداد اقساط:</small> {{ $plan->installment_count }}</div>
                                <div class="col-md-4"><small class="text-muted">پرداخت‌شده:</small> {{ $plan->paidCount() }} از {{ $plan->installment_count }}</div>
                                <div class="col-md-4"><small class="text-muted">وضعیت طرح:</small> {{ $plan->status }}</div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" wire:click="closeDetail">بستن</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
