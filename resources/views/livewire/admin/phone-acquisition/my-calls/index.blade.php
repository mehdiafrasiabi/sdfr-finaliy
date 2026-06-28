<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">تماس‌های من</li>
            </ol>
        </nav>
    </div>

    @php
        $colorFa = ['primary'=>'آبی','success'=>'سبز','warning'=>'زرد','danger'=>'قرمز','secondary'=>'خاکستری'];
    @endphp

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row align-items-center">
                <div class="col-md-5">
                    <h4 class="mb-0">تماس‌های من</h4>
                    <p class="small text-muted mb-0">تاریخچهٔ همهٔ تماس‌هایی که ثبت کرده‌ای.</p>
                </div>
                <div class="col-md-4">
                    <input type="text" wire:model.live.debounce.400ms="search" class="form-control"
                           placeholder="جستجو بر اساس نام یا موبایل…">
                </div>
                <div class="col-md-3">
                    <select wire:model.live="outcome" class="form-select">
                        <option value="all">همهٔ نتایج</option>
                        <option value="connected">پاسخ داده‌شده</option>
                        <option value="no_answer">عدم پاسخ</option>
                        <option value="rejected">رد تماس</option>
                        <option value="off">خاموش</option>
                        <option value="wrong">شماره اشتباه</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="widget-content widget-content-area">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>دانش‌آموز</th>
                            <th>موبایل</th>
                            <th>نتیجه</th>
                            <th>درصد تمایل</th>
                            <th>مدت مکالمه</th>
                            <th>تاریخ و ساعت</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($calls as $call)
                        @php $color = $call->color; @endphp
                        <tr>
                            <td>
                                <span class="badge bg-{{ $color }}" title="تماس {{ $call->attempt_number }} — {{ $colorFa[$color] ?? '' }}">
                                    {{ $call->attempt_number }}
                                </span>
                            </td>
                            <td>{{ $call->lead?->full_name ?: 'بدون نام' }}</td>
                            <td dir="ltr">{{ $call->lead?->mobile ?? '—' }}</td>
                            <td>
                                @if ($call->connected)
                                    @if ($call->result)
                                        <span class="badge bg-info-subtle text-info border border-info-subtle">{{ $call->result_label }}</span>
                                    @else
                                        <span class="badge bg-success-subtle text-success border border-success-subtle">پاسخ داده شد</span>
                                    @endif
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle">{{ $call->fail_label }}</span>
                                @endif
                            </td>
                            <td>{{ $call->connected && $call->willingness !== null ? $call->willingness . '٪' : '—' }}</td>
                            <td dir="ltr">{{ $call->talk_duration_label }}</td>
                            <td>{{ jalali($call->called_at)->format('%d %B %Y، %H:%M') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">تماسی ثبت نشده است.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $calls->links() }}</div>
        </div>
    </div>
</div>
