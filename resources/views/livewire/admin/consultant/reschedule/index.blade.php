<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">درخواست‌های جابجایی (مشاور)</li>
            </ol>
        </nav>
    </div>

    @if (session()->has('message')) <div class="alert alert-success">{{ session('message') }}</div> @endif

    <div class="statbox widget box box-shadow">
        <div class="widget-header"><h4 class="mb-0">درخواست‌های جابجایی مربوط به من</h4></div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>دانش‌آموز</th>
                        <th>نوع</th>
                        <th>پیشنهاد دانش‌آموز</th>
                        <th>اسلات‌های خالی مدیر آموزشی</th>
                        <th>وضعیت</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($requests as $r)
                        <tr>
                            <td>{{ $r->id }}</td>
                            <td>{{ $r->student?->user?->name ?? '—' }}</td>
                            <td>{{ $r->type === 'exception' ? 'استثنا' : 'دائمی' }}</td>
                            <td class="small">
                                {{ $days[$r->student_proposed_day] ?? '—' }}
                                @if ($r->student_proposed_time) — {{ substr($r->student_proposed_time, 0, 5) }} @endif
                                @if ($r->student_description)
                                    <div class="text-muted">{{ $r->student_description }}</div>
                                @endif
                            </td>
                            <td class="small">
                                @forelse (($r->manager_available_slots ?? []) as $slot)
                                    <div>{{ $days[$slot['day']] ?? '' }}: {{ $slot['start'] }} - {{ $slot['end'] ?? '' }}</div>
                                @empty
                                    —
                                @endforelse
                            </td>
                            <td><span class="badge bg-info">{{ $r->status_label }}</span></td>
                            <td class="text-nowrap">
                                @if ($r->status === 'awaiting_consultant_proposal')
                                    <button wire:click="openPropose({{ $r->id }})"
                                            class="btn btn-sm btn-primary">پیشنهاد زمان</button>
                                    <button wire:click="declineNoCapacity({{ $r->id }})"
                                            wire:confirm="ظرفیت ندارید و می‌خواهید درخواست تعویض مشاور بفرستید؟"
                                            class="btn btn-sm btn-outline-warning">ظرفیت ندارم</button>
                                @endif
                                @if ($r->status === 'awaiting_consultant_confirm')
                                    <span class="small text-muted d-block mb-1">
                                        انتخاب دانش‌آموز:
                                        {{ $days[$r->student_selected_day] ?? '' }}
                                        ساعت {{ $r->student_selected_time ? substr($r->student_selected_time, 0, 5) : '' }}
                                    </span>
                                    <button wire:click="confirmStudentChoice({{ $r->id }})"
                                            class="btn btn-sm btn-success">تایید</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">درخواستی یافت نشد.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $requests->links() }}</div>
        </div>
    </div>

    @if ($activeRequest)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.5);">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">پیشنهاد زمان جدید</h5>
                        <button class="btn-close" wire:click="closePropose"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-2 small">
                            اسلات‌های خالی ارسال‌شده توسط مدیر آموزشی:
                            @forelse (($activeRequest->manager_available_slots ?? []) as $slot)
                                <span class="badge bg-light text-dark me-1">
                                    {{ $days[$slot['day']] ?? '' }}: {{ $slot['start'] }} - {{ $slot['end'] ?? '' }}
                                </span>
                            @empty
                                <span class="text-muted">بدون اسلات</span>
                            @endforelse
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-2">
                                <label class="form-label">روز</label>
                                <select wire:model="proposedDay" class="form-select">
                                    <option value="">—</option>
                                    @foreach ($days as $d => $n)
                                        <option value="{{ $d }}">{{ $n }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label">ساعت</label>
                                <input type="time" wire:model="proposedTime" class="form-control">
                            </div>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">یادداشت برای مدیر آموزشی (اختیاری)</label>
                            <textarea wire:model="notes" rows="2" class="form-control"></textarea>
                        </div>
                        @error('proposedTime') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="closePropose">بستن</button>
                        <button class="btn btn-primary" wire:click="savePropose">ارسال پیشنهاد</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

