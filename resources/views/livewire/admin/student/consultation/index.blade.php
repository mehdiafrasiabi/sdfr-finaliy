<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">جلسات مشاوره</li>
            </ol>
        </nav>
    </div>

    @php
        $renderStudentName = fn($st) => $st->user?->personalInformation?->name
            ?? $st->user?->personalInformation?->name_full
            ?? $st->user?->name ?? 'دانش‌آموز';
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h4 class="mb-0">جلسات مشاوره</h4>
            <p class="small text-muted mb-0">
                دانش‌آموزانِ هر روز در باکسِ خود نمایش داده می‌شوند. فقط برای <strong>فردا</strong> می‌توانید تماس بگیرید و ساعتِ جلسه را تعیین کنید.
            </p>
        </div>
        <input type="text" wire:model.live.debounce.400ms="search" class="form-control" style="max-width:260px"
               placeholder="جستجوی دانش‌آموز…">
    </div>

    {{-- ───────────── باکسِ فردا (قابلِ عملیات) ───────────── --}}
    <div class="statbox widget box box-shadow mb-4 border border-primary">
        <div class="widget-header bg-primary-subtle">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="mb-0">
                    <i class="ri-calendar-event-line"></i>
                    فردا — {{ $days[$tomorrowDow] ?? '' }} ({{ $tomorrowTitle }})
                    <span class="badge bg-primary">{{ $tomorrowStudents->count() }} دانش‌آموز</span>
                </h5>
                @if ($canFinalize)
                    <button wire:click="finalizeAll" wire:confirm="جلساتِ فردا ثبتِ نهایی شود؟ پس از آن به دانش‌آموزان اطلاع داده می‌شود."
                            class="btn btn-success btn-sm">
                        <i class="ri-check-double-line"></i> ثبت نهاییِ جلساتِ فردا
                    </button>
                @endif
            </div>
        </div>
        <div class="widget-content widget-content-area">
            @forelse ($tomorrowStudents as $st)
                @php
                    $profile = $st->user?->profile;
                    $called  = $calledIds->has($st->id);
                    $session = $tomorrowSessions->get($st->id);
                    $isFinal = $session && $session->finalized;
                @endphp
                <div class="border rounded p-3 mb-2">
                    <div class="row align-items-center g-2">
                        <div class="col-md-4 d-flex align-items-center gap-2">
                            @if ($profile && $profile->picture)
                                <img src="{{ asset('user/img/' . $st->user->id . '/' . $profile->picture) }}"
                                     class="rounded-circle" width="42" height="42" style="object-fit:cover" alt="">
                            @else
                                <div class="rounded-circle bg-secondary-subtle d-flex align-items-center justify-content-center"
                                     style="width:42px;height:42px">{{ mb_substr($renderStudentName($st), 0, 1) }}</div>
                            @endif
                            <div>
                                <div class="fw-bold">
                                    {{ $renderStudentName($st) }}
                                    @if ($session && $session->is_makeup)
                                        <span class="badge bg-warning text-dark">جبرانی</span>
                                    @endif
                                </div>
                                <div class="small text-muted" dir="ltr">{{ $st->user?->mobile ?? '' }}</div>
                                <a href="{{ route('admin.student.advising-sessions.create', $st->user_id) }}"
                                   class="small text-decoration-none">جزئیات / تاریخچه</a>
                            </div>
                        </div>

                        <div class="col-md-8">
                            @if ($isFinal)
                                <span class="badge bg-success">
                                    <i class="ri-check-double-line"></i>
                                    ثبتِ نهایی — ساعت {{ $session->session_time?->format('H:i') }}
                                </span>
                            @elseif (! $called)
                                <button wire:click="openCall({{ $st->id }})" class="btn btn-outline-primary btn-sm">
                                    <i class="ri-phone-line"></i> تماس و هماهنگی
                                </button>
                                <span class="small text-muted ms-2">برای تعیینِ ساعت، ابتدا تماسِ موفق ثبت کنید.</span>
                            @else
                                <div class="row g-2 align-items-end">
                                    <div class="col-auto">
                                        <span class="badge bg-success-subtle text-success"><i class="ri-phone-line"></i> تماس انجام شد</span>
                                    </div>
                                    <div class="col-auto">
                                        <label class="form-label small mb-0">ساعت</label>
                                        <input type="number" min="0" max="23" class="form-control form-control-sm" style="width:80px"
                                               wire:model="schedule.{{ $st->id }}.hour" placeholder="HH">
                                    </div>
                                    <div class="col-auto">
                                        <label class="form-label small mb-0">دقیقه</label>
                                        <input type="number" min="0" max="59" class="form-control form-control-sm" style="width:80px"
                                               wire:model="schedule.{{ $st->id }}.minute" placeholder="MM">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label small mb-0">لینک جلسه آنلاین</label>
                                        <input type="url" class="form-control form-control-sm" dir="ltr"
                                               wire:model="schedule.{{ $st->id }}.link" placeholder="https://...">
                                    </div>
                                    <div class="col-auto">
                                        <button wire:click="saveSchedule({{ $st->id }})" class="btn btn-primary btn-sm">
                                            <i class="ri-save-line"></i> ذخیره
                                        </button>
                                    </div>
                                    @if ($session)
                                        <div class="col-12">
                                            <span class="small text-success"><i class="ri-checkbox-circle-line"></i> ساعت ذخیره شد (در انتظار ثبت نهایی).</span>
                                        </div>
                                    @endif
                                </div>
                                @error("schedule.{$st->id}.hour") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                @error("schedule.{$st->id}.minute") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                                @error("schedule.{$st->id}.link") <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-4">برای فردا دانش‌آموزی ندارید.</div>
            @endforelse
        </div>
    </div>

    {{-- ───────────── همه‌ی دانش‌آموزانِ من ───────────── --}}
    <div class="statbox widget box box-shadow mb-4">
        <div class="widget-header">
            <h5 class="mb-0">همه‌ی دانش‌آموزانِ من ({{ $allStudents->count() }})</h5>
            <p class="small text-muted mb-0">فهرستِ کاملِ دانش‌آموزانِ تحتِ مشاوره‌ی شما به‌همراه روزِ جلسه‌ی هفتگی.</p>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr><th style="width:50px">#</th><th>دانش‌آموز</th><th>موبایل</th><th>روزِ جلسه</th><th></th></tr>
                    </thead>
                    <tbody>
                    @forelse ($allStudents as $st)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-bold">{{ $renderStudentName($st) }}</td>
                            <td dir="ltr">{{ $st->user?->mobile ?? '—' }}</td>
                            <td>
                                @if ($st->session_day !== null)
                                    <span class="badge bg-light text-dark border">{{ $days[$st->session_day] ?? '—' }}</span>
                                @else
                                    <span class="badge bg-warning text-dark">تعیین‌نشده</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.student.advising-sessions.create', $st->user_id) }}"
                                   class="btn btn-sm btn-outline-primary">جزئیات / تاریخچه</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">دانش‌آموزی ندارید.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ───────────── جلسات جبرانیِ در انتظارِ تعیینِ روز (مرخصی) ───────────── --}}
    @if ($pendingMakeups->isNotEmpty())
        <div class="statbox widget box box-shadow mb-4 border border-warning">
            <div class="widget-header bg-warning-subtle">
                <h5 class="mb-0"><i class="ri-calendar-todo-line"></i> جلسات جبرانیِ در انتظارِ تعیینِ روز ({{ $pendingMakeups->count() }})</h5>
                <p class="small text-muted mb-0">ناشی از مرخصیِ تاییدشده — برای هر دانش‌آموز روزِ جلسه‌ی جبرانی را تعیین کنید.</p>
            </div>
            <div class="widget-content widget-content-area">
                @foreach ($pendingMakeups as $mk)
                    <div class="row align-items-center g-2 border rounded p-2 mb-2">
                        <div class="col-md-5 fw-bold">
                            {{ $mk->student?->user?->personalInformation?->name ?? $mk->student?->user?->name ?? 'دانش‌آموز' }}
                        </div>
                        <div class="col-md-4">
                            <select wire:model="makeupDay.{{ $mk->id }}" class="form-select form-select-sm">
                                <option value="">روزِ جبرانی…</option>
                                @foreach ($days as $d => $name)
                                    <option value="{{ $d }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button wire:click="assignMakeupDay({{ $mk->id }})" class="btn btn-warning btn-sm">تعیین روز</button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ───────────── باکس‌های سایرِ روزها (فقط نمایش) ───────────── --}}
    <div class="row g-3">
        @foreach ($days as $d => $dayName)
            @if ($d === $tomorrowDow)
                @continue
            @endif
            @php $dayStudents = collect($grouped->get($d, collect())); @endphp
            <div class="col-md-6 col-lg-4">
                <div class="statbox widget box box-shadow h-100">
                    <div class="widget-header">
                        <h6 class="mb-0">{{ $dayName }} <span class="badge bg-light text-dark border">{{ $dayStudents->count() }}</span></h6>
                    </div>
                    <div class="widget-content widget-content-area">
                        @forelse ($dayStudents as $st)
                            <div class="d-flex align-items-center justify-content-between py-1 border-bottom">
                                <a href="{{ route('admin.student.advising-sessions.create', $st->user_id) }}"
                                   class="small text-decoration-none">{{ $renderStudentName($st) }}</a>
                                <span class="small text-muted" dir="ltr">{{ $st->user?->mobile ?? '' }}</span>
                            </div>
                        @empty
                            <div class="text-center text-muted small py-3">دانش‌آموزی ندارد.</div>
                        @endforelse
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- دانش‌آموزانِ بدونِ روزِ مشخص (در انتظارِ تعیینِ روز توسط مدیر) --}}
    @if ($noDayStudents->isNotEmpty())
        <div class="statbox widget box box-shadow mt-4">
            <div class="widget-header">
                <h6 class="mb-0 text-warning"><i class="ri-error-warning-line"></i> بدونِ روزِ مشخص ({{ $noDayStudents->count() }})</h6>
                <p class="small text-muted mb-0">برای این دانش‌آموزان هنوز روزِ ثابتِ هفتگی تعیین نشده است.</p>
            </div>
            <div class="widget-content widget-content-area">
                @foreach ($noDayStudents as $st)
                    <div class="d-flex align-items-center justify-content-between py-1 border-bottom">
                        <span class="small">{{ $renderStudentName($st) }}</span>
                        <span class="small text-muted" dir="ltr">{{ $st->user?->mobile ?? '' }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ───────────── مودالِ تماس ───────────── --}}
    @include('livewire.admin.student.consultation.partials.call-modal')
</div>
