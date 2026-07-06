<div>
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">تایید انتخاب مشاور</h4>
                <input type="text" wire:model.live.debounce.400ms="search" class="form-control" style="max-width:260px"
                       placeholder="جستجو بر اساس نام یا موبایل…">
            </div>
        </div>
    </div>

    {{-- در انتظار تایید --}}
    <div class="card mb-4">
        <div class="card-header"><h5 class="card-title mb-0">در انتظار تایید ({{ $pendingSelections->count() }})</h5></div>
        <div class="card-body">
            @forelse ($pendingSelections as $sel)
                @php
                    $advisor = $sel->advisor;
                    $cap = $advisor ? ($advisor->student_capacity ?? $defaultCapacity) : $defaultCapacity;
                    $count = $advisor->advised_students_count ?? 0;
                    $isFull = $count >= $cap;
                    $studentName = $sel->student?->user?->personalInformation?->name ?? $sel->student?->user?->name ?? 'دانش‌آموز';
                @endphp
                <div class="border rounded p-3 mb-3">
                    <div class="row align-items-center g-3">
                        <div class="col-md-3">
                            <div class="text-muted small">دانش‌آموز</div>
                            <div class="fw-bold">{{ $studentName }}</div>
                            <div class="small text-muted">{{ $sel->student?->user?->mobile ?? '—' }}</div>
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small">مشاورِ انتخابی</div>
                            <div class="fw-bold">{{ $advisor?->name ?? '—' }}</div>
                            <span class="badge {{ $isFull ? 'bg-danger' : 'bg-success' }}">{{ $count }} / {{ $cap }}</span>
                            @if ($advisor)
                                <button wire:click="openStudentsModal({{ $advisor->id }})" class="btn btn-link btn-sm p-0 ms-1">مشاهده</button>
                            @endif
                        </div>
                        <div class="col-md-3">
                            <div class="text-muted small">روز / ساعت</div>
                            <div class="fw-bold">{{ $days[$sel->weekly_day] ?? '—' }} @if($sel->preferred_hour) — {{ substr($sel->preferred_hour,0,5) }} @endif</div>
                            <div class="small text-muted">{{ $sel->mode === \App\Models\AdvisorSelection::MODE_RANDOM ? 'انتخابِ تصادفی' : 'انتخابِ دانش‌آموز' }}</div>
                        </div>
                        <div class="col-md-3 text-md-end">
                            <button wire:click="approve({{ $sel->id }})" @disabled($isFull) class="btn btn-success btn-sm">تایید</button>
                            <button wire:click="reject({{ $sel->id }})" wire:confirm="رد شود؟" class="btn btn-outline-danger btn-sm">رد</button>
                        </div>
                    </div>
                    <div class="row mt-3 g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small mb-1">جایگزینی با مشاورِ دیگر</label>
                            <select wire:model="overrideAdvisor.{{ $sel->id }}" class="form-select form-select-sm">
                                <option value="">— انتخاب —</option>
                                @foreach ($advisors as $adv)
                                    <option value="{{ $adv->id }}" @disabled($adv->isFull)>{{ $adv->name }} ({{ $adv->advised_students_count }}/{{ $adv->cap }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <button wire:click="assignOverride({{ $sel->id }})" class="btn btn-primary btn-sm">تخصیص جایگزین</button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-4">انتخابی در انتظار تایید وجود ندارد.</div>
            @endforelse
        </div>
    </div>

    {{-- تخصیص دستی --}}
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">دانش‌آموزانِ خریدکرده‌ی بدونِ انتخاب</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr><th>دانش‌آموز</th><th>موبایل</th><th>محصول</th><th style="min-width:200px">مشاور</th><th style="min-width:150px">روزِ جلسه</th><th></th></tr>
                    </thead>
                    <tbody>
                    @forelse ($unassignedStudents as $st)
                        <tr>
                            <td class="fw-bold">{{ $st->user?->personalInformation?->name ?? $st->user?->name ?? '—' }}</td>
                            <td>{{ $st->user?->mobile ?? '—' }}</td>
                            <td>{{ $st->product?->title ?? '—' }}</td>
                            <td>
                                <select wire:model="manualAdvisor.{{ $st->id }}" class="form-select form-select-sm">
                                    <option value="">انتخاب کنید…</option>
                                    @foreach ($advisors as $adv)
                                        <option value="{{ $adv->id }}" @disabled($adv->isFull)>{{ $adv->name }} ({{ $adv->advised_students_count }}/{{ $adv->cap }})</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <select wire:model="manualDay.{{ $st->id }}" class="form-select form-select-sm">
                                    <option value="">روز…</option>
                                    @foreach ($days as $d => $name)<option value="{{ $d }}">{{ $name }}</option>@endforeach
                                </select>
                            </td>
                            <td><button wire:click="assignManual({{ $st->id }})" class="btn btn-sm btn-primary">تخصیص</button></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">موردی نیست.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $unassignedStudents->links('layouts.manager.pagination') }}</div>
        </div>
    </div>

    {{-- مودال دانش‌آموزانِ مشاور --}}
    @if ($showStudentsModal && $modalAdvisor)
        <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,.5)">
            <div class="modal-dialog modal-dialog-scrollable modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">دانش‌آموزانِ {{ $modalAdvisor->name }} ({{ $modalAdvisor->advised_students_count }})</h5>
                        <button type="button" class="btn-close" wire:click="closeStudentsModal"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            @forelse ($modalAdvisor->advisedStudents as $s)
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>{{ $s->user?->personalInformation?->name ?? $s->user?->name ?? 'دانش‌آموز' }}</span>
                                    <span class="small text-muted">{{ $s->user?->mobile ?? '' }}</span>
                                </li>
                            @empty
                                <li class="list-group-item text-center text-muted">دانش‌آموزی ندارد.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="modal-footer"><button class="btn btn-secondary" wire:click="closeStudentsModal">بستن</button></div>
                </div>
            </div>
        </div>
    @endif
</div>
