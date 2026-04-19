<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">درخواست‌های جابجایی جلسه</li>
            </ol>
        </nav>
    </div>

    @if (session()->has('message')) <div class="alert alert-success">{{ session('message') }}</div> @endif
    @if (session()->has('error'))   <div class="alert alert-danger">{{ session('error') }}</div>   @endif

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row align-items-center">
                <div class="col-md-6"><h4 class="mb-0">درخواست‌های جابجایی جلسه</h4></div>
                <div class="col-md-6">
                    <select wire:model.live="statusFilter" class="form-select">
                        <option value="open">در جریان</option>
                        <option value="approved">تایید شده</option>
                        <option value="rejected">رد شده</option>
                        <option value="all">همه</option>
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
                        <th>مشاور</th>
                        <th>نوع</th>
                        <th>پیشنهاد دانش‌آموز</th>
                        <th>پیشنهاد مشاور</th>
                        <th>وضعیت</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($requests as $r)
                        <tr>
                            <td>{{ $r->id }}</td>
                            <td>{{ $r->student?->user?->name ?? '—' }}</td>
                            <td>{{ $r->advisor?->name ?? '—' }}</td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $r->type === 'exception' ? 'استثنا' : 'دائمی' }}
                                </span>
                            </td>
                            <td class="small">
                                {{ $days[$r->student_proposed_day] ?? '—' }}
                                @if ($r->student_proposed_time) — {{ substr($r->student_proposed_time, 0, 5) }} @endif
                                @if ($r->student_description)
                                    <div class="text-muted">{{ $r->student_description }}</div>
                                @endif
                            </td>
                            <td class="small">
                                @if ($r->consultant_proposed_day !== null)
                                    {{ $days[$r->consultant_proposed_day] ?? '' }}
                                    @if ($r->consultant_proposed_time) — {{ substr($r->consultant_proposed_time, 0, 5) }} @endif
                                    @if ($r->consultant_notes)
                                        <div class="text-muted">{{ $r->consultant_notes }}</div>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                            <td><span class="badge bg-info">{{ $r->status_label }}</span></td>
                            <td class="text-nowrap">
                                @if ($r->status === 'pending_manager_review')
                                    <button wire:click="openGrid({{ $r->id }})"
                                            class="btn btn-sm btn-primary">برنامه هفتگی / ارسال اسلات</button>
                                @endif
                                @if ($r->status === 'awaiting_manager_final')
                                    <button wire:click="finalizeApprove({{ $r->id }})"
                                            class="btn btn-sm btn-success">تایید نهایی</button>
                                @endif
                                @if ($r->status === 'consultant_change_requested')
                                    <button wire:click="reassignConsultant({{ $r->id }})"
                                            class="btn btn-sm btn-warning">بازگشت برای تعویض مشاور</button>
                                @endif
                                @if (!in_array($r->status, ['approved','rejected']))
                                    <button wire:click="reject({{ $r->id }})"
                                            wire:confirm="از رد این درخواست مطمئنید؟"
                                            class="btn btn-sm btn-outline-danger">رد</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted">درخواستی یافت نشد.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $requests->links() }}</div>
        </div>
    </div>

    {{-- Modal: weekly grid --}}
    @if ($activeRequest)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,.5);">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            برنامه هفتگی و انتخاب اسلات‌های خالی برای
                            {{ $activeRequest->student?->user?->name }}
                        </h5>
                        <button class="btn-close" wire:click="closeGrid"></button>
                    </div>
                    <div class="modal-body">
                        <p class="small text-muted">
                            اعداد داخل هر خانه تعداد دانش‌آموزانی است که در آن ساعت تثبیت شده‌اند.
                            روی خانه‌های خالی (صفر) کلیک کنید تا به‌عنوان اسلات خالی به مشاور پیشنهاد شود.
                        </p>
                        <div class="table-responsive">
                            <table class="table table-bordered text-center align-middle small">
                                <thead>
                                <tr>
                                    <th>ساعت</th>
                                    @foreach ($days as $d => $n)
                                        <th>{{ $n }}</th>
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>
                                @for ($h = 8; $h <= 20; $h++)
                                    <tr>
                                        <th>{{ sprintf('%02d:00', $h) }}</th>
                                        @foreach ($days as $d => $n)
                                            @php
                                                $count = $weeklyGrid[$d][$h] ?? 0;
                                                $selected = collect($selectedEmptySlots)
                                                    ->contains(fn($s) => $s['day'] === $d && (int)substr($s['start'], 0, 2) === $h);
                                            @endphp
                                            <td class="p-1
                                                @if($count === 0) bg-light @else bg-warning-subtle @endif
                                                @if($selected) bg-success text-white @endif">
                                                <button
                                                    type="button"
                                                    wire:click="toggleEmptySlot({{ $d }}, {{ $h }})"
                                                    class="btn btn-sm btn-link text-decoration-none p-0 w-100 {{ $selected ? 'text-white' : '' }}"
                                                    @disabled($count > 0)>
                                                    {{ $count > 0 ? $count : '—' }}
                                                </button>
                                            </td>
                                        @endforeach
                                    </tr>
                                @endfor
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="closeGrid">بستن</button>
                        <button class="btn btn-primary"
                                wire:click="sendSlotsToConsultant"
                            @disabled(empty($selectedEmptySlots))>
                            ارسال {{ count($selectedEmptySlots) }} اسلات خالی به مشاور
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

