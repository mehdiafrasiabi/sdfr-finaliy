<div class="em-page">
    @include('livewire.admin.educational-manager._styles')
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">مرخصی مشاوران</li>
            </ol>
        </nav>
    </div>

    <section class="em-hero">
        <div class="em-hero-main"><span class="em-hero-icon"><i class="fi fi-rr-calendar-clock"></i></span><div><h3>مرخصی مشاوران</h3><p>بررسی درخواست‌های مرخصی و مشاهده سابقه تصمیم‌های ثبت‌شده.</p></div></div>
        <span class="badge bg-warning-subtle text-warning">{{ number_format($pending->count()) }} درخواست در انتظار</span>
    </section>

    {{-- در انتظار تایید --}}
    <div class="statbox widget box box-shadow mb-4">
        <div class="widget-header">
            <h5 class="mb-0">در انتظار تایید ({{ $pending->count() }})</h5>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>مشاور</th>
                            <th>روزِ مرخصی</th>
                            <th>توضیحات</th>
                            <th style="min-width:260px">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($pending as $leave)
                        <tr>
                            <td class="fw-bold">{{ $leave->advisor?->name ?? '—' }}</td>
                            <td>{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($leave->leave_date))->format('l Y/m/d') }}</td>
                            <td class="small">{{ $leave->reason ?? '—' }}</td>
                            <td>
                                <div class="d-flex flex-column gap-2">
                                    <div class="d-flex gap-2">
                                        <button wire:click="approve({{ $leave->id }})"
                                                wire:confirm="مرخصی تایید شود؟ برای دانش‌آموزانِ آن روز جلسه‌ی جبرانی ساخته می‌شود."
                                                class="btn btn-success btn-sm">تایید</button>
                                        <button wire:click="reject({{ $leave->id }})"
                                                class="btn btn-outline-danger btn-sm">رد</button>
                                    </div>
                                    <input type="text" wire:model="rejectReason.{{ $leave->id }}"
                                           class="form-control form-control-sm" placeholder="علتِ رد (اختیاری)">
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">درخواستِ مرخصیِ در انتظاری وجود ندارد.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- تاریخچه --}}
    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <h5 class="mb-0">تاریخچه</h5>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>مشاور</th>
                            <th>روزِ مرخصی</th>
                            <th>وضعیت</th>
                            <th>بررسی‌کننده</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($history as $leave)
                        <tr>
                            <td>{{ $leave->advisor?->name ?? '—' }}</td>
                            <td>{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($leave->leave_date))->format('Y/m/d') }}</td>
                            <td>
                                @php $cls = $leave->status === 'approved' ? 'bg-success' : 'bg-danger'; @endphp
                                <span class="badge {{ $cls }}">{{ $leave->status_label }}</span>
                                @if ($leave->status === 'rejected' && $leave->reject_reason)
                                    <div class="small text-muted mt-1">{{ $leave->reject_reason }}</div>
                                @endif
                            </td>
                            <td class="small">{{ $leave->reviewer?->name ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">موردی نیست.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $history->links() }}</div>
        </div>
    </div>
</div>
