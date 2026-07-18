<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">داشبورد جذب آزمایشی</li>
            </ol>
        </nav>
    </div>

    <style>
        @keyframes ta-ring { 0%,100%{transform:rotate(0)} 20%{transform:rotate(14deg)} 40%{transform:rotate(-14deg)} 60%{transform:rotate(9deg)} 80%{transform:rotate(-9deg)} }
        @keyframes ta-pulse { 0%{box-shadow:0 0 0 0 rgba(13,110,253,.55)} 70%{box-shadow:0 0 0 24px rgba(13,110,253,0)} 100%{box-shadow:0 0 0 0 rgba(13,110,253,0)} }
        .ta-call-icon{ width:92px;height:92px;border-radius:50%;display:flex;align-items:center;justify-content:center;background:#0d6efd;color:#fff;font-size:36px;margin:0 auto;animation:ta-pulse 1.5s infinite; }
        .ta-call-icon i{ display:inline-block;animation:ta-ring 1s infinite; }
        .ta-call-modal-content{ background:#1f1f1f; border:0; color:#fff; overflow:hidden; }
        .ta-call-modal-header,
        .ta-call-modal-footer{ background:#1f1f1f; border-color:rgba(255,255,255,.08); }
        .ta-call-modal-body{ background:#1f1f1f; }
        .ta-call-muted{ color:rgba(255,255,255,.55); }
        [x-cloak]{ display:none !important; }
    </style>

    {{-- خلاصه --}}
    <div class="row g-3 mb-2">
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <div class="text-muted small">کل دانش‌آموزان من</div>
                <h2 class="mb-0">{{ number_format($total) }}</h2>
            </div></div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm border-start border-success border-4"><div class="card-body">
                <div class="text-muted small">تماس گرفته‌شده</div>
                <h2 class="mb-0 text-success">{{ number_format($calledStudents) }}</h2>
            </div></div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm border-start border-danger border-4"><div class="card-body">
                <div class="text-muted small">تماس گرفته‌نشده</div>
                <h2 class="mb-0 text-danger">{{ number_format($notCalled) }}</h2>
            </div></div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm"><div class="card-body">
                <div class="text-muted small">ثبت‌نام قطعی</div>
                <h2 class="mb-0 text-primary">{{ number_format($confirmed) }}</h2>
            </div></div>
        </div>
    </div>

    <div class="row g-3">
        {{-- آخرین اعلانات --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-primary-subtle d-flex justify-content-between align-items-center gap-2">
                    <strong class="text-primary">آخرین اعلانات</strong>
                    <span class="badge bg-primary">{{ number_format($notifications->total()) }}</span>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($notifications as $notification)
                            <div class="list-group-item {{ $notification->read_at ? '' : 'bg-primary-subtle' }}">
                                <div class="d-flex align-items-start gap-2">
                                    <span class="badge rounded-pill {{ $notification->read_at ? 'bg-secondary' : 'bg-primary' }} mt-1">
                                        <i class="fi fi-rr-bell"></i>
                                    </span>
                                    <div class="flex-grow-1">
                                        <div class="d-flex flex-wrap justify-content-between gap-2">
                                            <strong>{{ $notification->title }}</strong>
                                            <span class="small text-muted">{{ jdate($notification->created_at)->format('Y/m/d H:i') }}</span>
                                        </div>
                                        @if($notification->body)
                                            <div class="small text-muted mt-1">{{ $notification->body }}</div>
                                        @endif
                                        <div class="d-flex flex-wrap gap-2 mt-2">
                                            @if($notification->url)
                                                <a href="{{ $notification->url }}" class="btn btn-sm btn-outline-primary">مشاهده</a>
                                            @endif
                                            @if($notification->read_at)
                                                <span class="badge bg-success-subtle text-success border">خوانده شده</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger border">خوانده نشده</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-muted py-4">اعلانی برای نمایش وجود ندارد.</div>
                        @endforelse
                    </div>
                    @if($notifications->hasPages())
                        <div class="border-top p-3">
                            {{ $notifications->links('layouts.admin.pagination') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-secondary-subtle"><strong class="text-secondary">در انتظار تماس</strong></div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height:300px;overflow:auto">
                        <table class="table table-sm table-bordered mb-0 align-middle">
                            <thead><tr><th>دانش‌آموز</th><th>مرحله</th><th>از موعد گذشته</th><th>تماس</th></tr></thead>
                            <tbody>
                            @forelse($waitingForCall as $trial)
                                @php
                                    $student = $trial['trial'];
                                    $hasExamProgram = $student->student?->examSchedules?->isNotEmpty() ?? false;
                                    $daysOverdue = $trial['days_overdue'] ?? 0;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="fw-semibold">{{ $student->user?->name ?? '—' }}</div>
                                        <div class="small text-muted" dir="ltr">{{ $student->user?->mobile ?? '—' }}</div>
                                    </td>
                                    <td>
                                        @if($hasExamProgram)
                                            <span class="badge bg-warning text-dark">آزمونی</span>
                                        @endif
                                        <span class="badge bg-light text-dark border">{{ $student->grade_label }}</span>
                                        <div class="small text-muted mt-1">{{ $trial['stage_label'] }}</div>
                                    </td>
                                    <td>
                                        @if($trial['is_due_today'])
                                            <span class="badge bg-info text-dark">امروز موعد تماس</span>
                                        @else
                                            <span class="badge bg-danger">{{ $daysOverdue }} روز گذشته</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button"
                                                class="btn btn-sm btn-primary"
                                                wire:click="promptCall({{ $student->id }}, '{{ $trial['stage'] }}', '{{ $trial['subject'] ?? '' }}')"
                                                title="شروع تماس">
                                            <i class="fi fi-rr-phone-call"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center text-muted py-3">هیچ دانش‌آموزی با موعد تماس گذشته یا امروز پیدا نشد.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- یادآورها --}}
        <div class="col-md-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-warning-subtle"><strong class="text-warning-emphasis">یادآورها</strong></div>
                <div class="card-body p-0">
                    <div class="table-responsive" style="max-height:300px;overflow:auto">
                        <table class="table table-sm table-bordered mb-0 align-middle">
                            <thead><tr><th>دانش‌آموز</th><th>موبایل</th><th>زمان یادآور</th></tr></thead>
                            <tbody>
                            @forelse ($dueReminders as $t)
                                @php $due = $t->acq_reminder_at && $t->acq_reminder_at->lte($now); @endphp
                                <tr class="{{ $due ? 'table-danger' : '' }}">
                                    <td>{{ $t->user?->name ?? '—' }}</td>
                                    <td dir="ltr">{{ $t->user?->mobile ?? '—' }}</td>
                                    <td>{{ jalali($t->acq_reminder_at)->format('%d %B %Y، %H:%M') }}@if($due) <span class="badge bg-danger">سررسید</span>@endif</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-3">یادآوری ثبت نشده است.</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($showCallConfirmModal)
        <div class="modal d-block" tabindex="-1" style="background:rgba(0,0,0,.6)">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content ta-call-modal-content">
                    <div class="modal-header ta-call-modal-header border-0">
                        <h5 class="modal-title text-white">تأیید شروع تماس</h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="cancelCallPrompt"></button>
                    </div>
                    <div class="modal-body ta-call-modal-body py-5">
                        <div class="text-center">
                            <div class="ta-call-icon mb-4"><i class="fi fi-rr-phone-call"></i></div>
                            <h5 class="text-white mb-2">مطمئنی می‌خوای الان تماس بگیری؟</h5>
                            @if($pendingCallStage === 'day1' && $pendingCallSubject)
                                <div class="bg-body text-dark rounded-4 p-3 mt-3 text-end">
                                    <div class="fw-bold mb-2">
                                        موضوع تماس: {{ \App\Livewire\Admin\TrialAcquisition\Index::callSubjectOptions()[$pendingCallSubject] ?? '—' }}
                                    </div>
                                    <div class="small text-muted">
                                        {{ \App\Livewire\Admin\TrialAcquisition\Index::callSubjectInstructions()[$pendingCallSubject] ?? '' }}
                                    </div>
                                </div>
                            @else
                                <p class="ta-call-muted mb-0">با ادامه، وارد فرایند ثبت تماس همین دانش‌آموز می‌شوی.</p>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer ta-call-modal-footer border-0 justify-content-start gap-2">
                        <button type="button" class="btn btn-light" wire:click="cancelCallPrompt">لغو</button>
                        <button type="button" class="btn btn-success btn-lg" wire:click="continueCallPrompt">
                            <i class="fi fi-rr-phone-call"></i> ادامه تماس
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
