<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">گزارش‌های {{ $student->user?->name }}</h4>
        <a href="{{ route('admin.school-supporter.students', $school->id) }}" class="btn btn-sm btn-outline-secondary">بازگشت</a>
    </div>

    <div class="card">
        <div class="card-body">
            @forelse($reports as $r)
                <div class="border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                        <div>
                            <strong>تاریخ:</strong> {{ jalali($r->report_date)->format('Y/m/d') }}
                            @php
                                $color = match($r->status) {
                                    'approved' => 'bg-success',
                                    'rejected' => 'bg-danger',
                                    default    => 'bg-warning',
                                };
                            @endphp
                            <span class="badge {{ $color }} ms-2">{{ $r->status_label }}</span>
                        </div>
                        <div>
                            <small class="text-muted">
                                مطالعه: {{ $r->total_study_minutes }} دقیقه
                                | موبایل: {{ $r->total_mobile_minutes }} دقیقه
                            </small>
                            <button class="btn btn-sm btn-soft-primary ms-2" wire:click="openModal({{ $r->id }})">نظر/وضعیت</button>
                        </div>
                    </div>
                    <table class="table table-sm mb-0">
                        <thead><tr><th>درس</th><th>فصل</th><th>مطالعه (دقیقه)</th><th>موبایل (دقیقه)</th></tr></thead>
                        <tbody>
                        @foreach($r->parts as $p)
                            <tr>
                                <td>{{ $p->subject?->name ?? '—' }}</td>
                                <td>{{ $p->chapter?->name ?? '—' }}</td>
                                <td>{{ $p->study_minutes }}</td>
                                <td>{{ $p->mobile_minutes }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    @if($r->advisor_comment)
                        <div class="mt-2 small text-muted">نظر فعلی: {{ $r->advisor_comment }}</div>
                    @endif
                </div>
            @empty
                <div class="text-center text-muted py-4">هنوز گزارشی ثبت نشده است.</div>
            @endforelse
            {{ $reports->links() }}
        </div>
    </div>

    @if($modalOpen)
        <div class="modal show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">بررسی گزارش</h5>
                        <button type="button" class="btn-close" wire:click="closeModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">وضعیت</label>
                            <select wire:model="modalStatus" class="form-select">
                                <option value="approved">تأیید</option>
                                <option value="rejected">رد</option>
                                <option value="pending">در انتظار</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">نظر شما (اختیاری)</label>
                            <textarea wire:model="advisor_comment" class="form-control" rows="3" maxlength="2000"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" wire:click="closeModal">انصراف</button>
                        <button class="btn btn-primary" wire:click="saveReview">ثبت</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
