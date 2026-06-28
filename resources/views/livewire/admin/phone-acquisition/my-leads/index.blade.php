<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">دانش‌آموزان من</li>
            </ol>
        </nav>
    </div>

    @php
        $colorFa = ['primary'=>'آبی','success'=>'سبز','warning'=>'زرد','danger'=>'قرمز','secondary'=>'خاکستری'];
        $statusBadge = ['active'=>'success','closed'=>'info','dead'=>'secondary'];
    @endphp

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <h4 class="mb-0">دانش‌آموزان من</h4>
                    <p class="small text-muted mb-0">همهٔ شماره‌های تحت پوشش شما و وضعیت پیگیری آن‌ها.</p>
                </div>
                <div class="col-md-4">
                    <input type="text" wire:model.live.debounce.400ms="search" class="form-control"
                           placeholder="جستجو بر اساس نام یا موبایل…">
                </div>
                <div class="col-md-3">
                    <select wire:model.live="statusFilter" class="form-select">
                        <option value="all">همه</option>
                        <option value="active">در جریان</option>
                        <option value="closed">بسته‌شده</option>
                        <option value="dead">خاکستری</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="widget-content widget-content-area">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>دانش‌آموز</th>
                            <th>موبایل</th>
                            <th>پایه/رشته</th>
                            <th>تعداد تماس</th>
                            <th>وضعیت</th>
                            <th>تماس بعدی</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($leads as $lead)
                        <tr>
                            <td>{{ $lead->full_name ?: 'بدون نام' }}</td>
                            <td dir="ltr">{{ $lead->mobile }}</td>
                            <td>{{ $lead->grade_label }} / {{ $lead->field_label }}</td>
                            <td>
                                <span class="badge bg-{{ $lead->color }}">{{ $lead->calls_count }}</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $statusBadge[$lead->status] ?? 'light' }}">{{ $lead->status_label }}</span>
                                @if ($lead->status === 'dead' && $lead->grey_reason_label)
                                    <span class="badge bg-light text-dark border">{{ $lead->grey_reason_label }}</span>
                                @endif
                            </td>
                            <td>
                                @if ($lead->status === 'active' && $lead->next_call_at)
                                    {{ jalali($lead->next_call_at)->format('%d %B، %H:%M') }}
                                    @if ($lead->next_call_at->lte(now()))
                                        <span class="badge bg-danger ms-1">سررسید</span>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <button wire:click="openDetail({{ $lead->id }})" class="btn btn-sm btn-outline-primary">
                                    <i class="fi fi-rr-eye"></i> جزئیات
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">شماره‌ای تحت پوشش شما نیست.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $leads->links() }}</div>
        </div>
    </div>

    {{-- ───────── مودال جزئیات تماس‌ها ───────── --}}
    @if ($detailLead)
        <div class="modal d-block" tabindex="-1" style="background:rgba(0,0,0,.45)">
            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title mb-0">
                                {{ $detailLead->full_name ?: 'بدون نام' }} —
                                <span dir="ltr">{{ $detailLead->mobile }}</span>
                            </h5>
                            <small class="text-muted">
                                {{ $detailLead->grade_label }} / {{ $detailLead->field_label }}
                                · {{ $detailLead->status_label }}
                                @if ($detailLead->grey_reason_label) ({{ $detailLead->grey_reason_label }}) @endif
                            </small>
                        </div>
                        <button type="button" class="btn-close" wire:click="closeDetail"></button>
                    </div>
                    <div class="modal-body">
                        @forelse ($detailLead->calls as $call)
                            @php $color = $call->color; @endphp
                            <div class="border rounded p-3 mb-2 border-{{ $color }}" style="border-right-width:4px;">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <strong>
                                        تماس #{{ $call->attempt_number }}
                                        @if ($call->connected)
                                            @if ($call->result)
                                                <span class="badge bg-info">{{ $call->result_label }}</span>
                                            @else
                                                <span class="badge bg-success">پاسخ داده شد</span>
                                            @endif
                                        @else
                                            <span class="badge bg-danger">{{ $call->fail_label }}</span>
                                        @endif
                                    </strong>
                                    <span class="text-muted small">{{ jalali($call->called_at)->format('%d %B %Y، %H:%M') }}</span>
                                </div>

                                @if ($call->connected)
                                    <div class="small text-muted">
                                        طرف صحبت: {{ $call->spoke_with_label }}
                                        @if ($call->willingness !== null) · تمایل: {{ $call->willingness }}٪ @endif
                                        @if ($call->talk_duration_seconds) · مدت: <span dir="ltr">{{ $call->talk_duration_label }}</span> @endif
                                    </div>
                                    @if ($call->low_willingness_reason)
                                        <div class="small mt-1"><span class="text-danger">علت تمایل کم:</span> {{ $call->low_willingness_reason }}</div>
                                    @endif
                                    @if ($call->result === 'follow_up' && $call->follow_up_at)
                                        <div class="small mt-1"><span class="text-primary">پیگیری مجدد:</span> {{ jalali($call->follow_up_at)->format('%d %B %Y، %H:%M') }}</div>
                                    @endif
                                    @if ($call->summary)
                                        <div class="small mt-1 text-dark">{{ $call->summary }}</div>
                                    @endif
                                @endif

                                @if ($call->admin)
                                    <div class="small text-muted mt-1">ثبت توسط: {{ $call->admin->name }}</div>
                                @endif
                            </div>
                        @empty
                            <p class="text-center text-muted py-4">هنوز تماسی برای این شماره ثبت نشده است.</p>
                        @endforelse
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="closeDetail">بستن</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
