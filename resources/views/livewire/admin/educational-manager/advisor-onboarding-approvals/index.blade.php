<div class="em-page">
    @include('livewire.admin.educational-manager._styles')
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">تایید لینک گروه بله</li>
            </ol>
        </nav>
    </div>

    <section class="em-hero">
        <div class="em-hero-main"><span class="em-hero-icon"><i class="fi fi-rr-shield-check"></i></span><div><h3>تأیید لینک گروه بله</h3><p>کنترل لینک ارسالی مشاور و فعال‌سازی ادامه فرایند مشاوره دانش‌آموز.</p></div></div>
        <span class="badge bg-warning-subtle text-warning">{{ number_format($pending->count()) }} مورد در انتظار</span>
    </section>

    <div class="statbox widget box box-shadow mb-4">
        <div class="widget-header">
            <h5 class="mb-0">در انتظار تایید ({{ $pending->count() }})</h5>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                    <tr>
                        <th>دانش‌آموز</th>
                        <th>مشاور</th>
                        <th>لینک گروه بله</th>
                        <th>تماس اتمام حجت</th>
                        <th style="min-width:280px">عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($pending as $onboarding)
                        <tr>
                            <td class="fw-bold">
                                {{ $onboarding->student?->user?->personalInformation?->name ?? $onboarding->student?->user?->name ?? 'دانش‌آموز' }}
                                <div class="small text-muted" dir="ltr">{{ $onboarding->student?->user?->mobile ?? '—' }}</div>
                            </td>
                            <td>{{ $onboarding->advisor?->name ?? '—' }}</td>
                            <td>
                                <a href="{{ $onboarding->group_link }}" target="_blank" dir="ltr" class="text-decoration-none">
                                    {{ $onboarding->group_link }}
                                </a>
                                <div class="small text-muted mt-1">
                                    ارسال: {{ $onboarding->submitted_at ? jalali($onboarding->submitted_at)->format('%d %B %Y H:i') : '—' }}
                                </div>
                            </td>
                            <td>
                                @if($onboarding->call)
                                    <span class="badge bg-success-subtle text-success">ثبت شده</span>
                                    <div class="small text-muted mt-1" dir="ltr">{{ $onboarding->call->talk_duration_label }}</div>
                                @else
                                    <span class="badge bg-secondary">بدون رکورد</span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-2">
                                    <div class="d-flex gap-2">
                                        <button wire:click="approve({{ $onboarding->id }})"
                                                wire:confirm="لینک گروه بله تایید شود؟ بعد از تایید، جلسه و تماس عادی برای دانش‌آموز فعال می‌شود."
                                                class="btn btn-success btn-sm">
                                            <i class="ri-check-line ms-1"></i> تایید
                                        </button>
                                        <button wire:click="reject({{ $onboarding->id }})"
                                                class="btn btn-outline-danger btn-sm">
                                            <i class="ri-close-line ms-1"></i> رد
                                        </button>
                                    </div>
                                    <input type="text"
                                           wire:model="rejectReason.{{ $onboarding->id }}"
                                           class="form-control form-control-sm"
                                           placeholder="علت رد برای مشاور">
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">درخواستی برای تایید وجود ندارد.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <h5 class="mb-0">تاریخچه تایید و رد</h5>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                    <tr>
                        <th>دانش‌آموز</th>
                        <th>مشاور</th>
                        <th>وضعیت</th>
                        <th>بررسی‌کننده</th>
                        <th>لینک</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($history as $onboarding)
                        <tr>
                            <td>{{ $onboarding->student?->user?->personalInformation?->name ?? $onboarding->student?->user?->name ?? 'دانش‌آموز' }}</td>
                            <td>{{ $onboarding->advisor?->name ?? '—' }}</td>
                            <td>
                                <span class="badge {{ $onboarding->status === \App\Models\AdvisorOnboarding::STATUS_APPROVED ? 'bg-success' : 'bg-danger' }}">
                                    {{ $onboarding->status_label }}
                                </span>
                                @if($onboarding->reject_reason)
                                    <div class="small text-muted mt-1">{{ $onboarding->reject_reason }}</div>
                                @endif
                            </td>
                            <td class="small">{{ $onboarding->reviewer?->name ?? '—' }}</td>
                            <td>
                                @if($onboarding->group_link)
                                    <a href="{{ $onboarding->group_link }}" target="_blank" dir="ltr" class="text-decoration-none">{{ $onboarding->group_link }}</a>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">موردی نیست.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $history->links('layouts.admin.pagination') }}</div>
        </div>
    </div>
</div>
