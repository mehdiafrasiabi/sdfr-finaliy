<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">مرخصی مشاور</li>
            </ol>
        </nav>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <h5 class="mb-0">ثبتِ درخواستِ مرخصی</h5>
                    <p class="small text-muted mb-0">مرخصی باید حداقل ۷۲ ساعت قبل ثبت شود. پس از تاییدِ مدیر آموزشی، برای دانش‌آموزانِ آن روز جلسه‌ی جبرانی ساخته می‌شود.</p>
                </div>
                <div class="widget-content widget-content-area">
                    <form wire:submit.prevent="submit">
                        <div class="mb-3">
                            <label class="form-label">روزِ مرخصی</label>
                            <select wire:model="leaveDate" class="form-select">
                                <option value="">انتخاب روز…</option>
                                @foreach ($allowedDates as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('leaveDate') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">توضیحات (اختیاری)</label>
                            <textarea wire:model="reason" rows="3" class="form-control" placeholder="علتِ مرخصی…"></textarea>
                            @error('reason') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <span wire:loading.remove wire:target="submit">ثبت درخواست</span>
                            <span wire:loading wire:target="submit">در حال ثبت…</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="statbox widget box box-shadow">
                <div class="widget-header">
                    <h5 class="mb-0">درخواست‌های من</h5>
                </div>
                <div class="widget-content widget-content-area">
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>روزِ مرخصی</th>
                                    <th>توضیحات</th>
                                    <th>وضعیت</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse ($leaves as $leave)
                                <tr>
                                    <td>{{ \Morilog\Jalali\Jalalian::fromCarbon(\Carbon\Carbon::parse($leave->leave_date))->format('l Y/m/d') }}</td>
                                    <td class="small">{{ $leave->reason ?? '—' }}</td>
                                    <td>
                                        @php
                                            $cls = $leave->status === 'approved' ? 'bg-success' : ($leave->status === 'rejected' ? 'bg-danger' : 'bg-warning text-dark');
                                        @endphp
                                        <span class="badge {{ $cls }}">{{ $leave->status_label }}</span>
                                        @if ($leave->status === 'rejected' && $leave->reject_reason)
                                            <div class="small text-muted mt-1">علت رد: {{ $leave->reject_reason }}</div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-4">درخواستی ثبت نکرده‌اید.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-3">{{ $leaves->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</div>
