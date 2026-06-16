<div class="container-fluid"
     x-data="{ ok:'' }"
     x-on:success.window="ok = ($event.detail && ($event.detail[0] ?? $event.detail)) || ''; setTimeout(() => ok='', 3500)">

    <h4 class="mb-3">تماس اورژانسی</h4>
    <div x-show="ok" x-cloak class="alert alert-success py-2" x-text="ok"></div>

    <div class="card mb-3">
        <div class="card-header"><strong>ثبت تماس اورژانسی جدید</strong></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">دانش‌آموز</label>
                    <select wire:model="selectedStudentId" class="form-select">
                        <option value="">انتخاب کنید…</option>
                        @foreach($students as $s)
                            <option value="{{ $s->id }}">{{ $s->user?->name ?? '—' }}</option>
                        @endforeach
                    </select>
                    @error('selectedStudentId') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-8">
                    <label class="form-label">علت تماس اورژانسی</label>
                    <textarea wire:model="reason" rows="2" class="form-control" maxlength="1000"
                              placeholder="شرح مختصر موضوع برای پیگیری مدیر مدرسه"></textarea>
                    @error('reason') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="text-end mt-3">
                <button wire:click="save" wire:loading.attr="disabled" wire:target="save" class="btn btn-danger">
                    <span wire:loading.remove wire:target="save">ثبت تماس اورژانسی</span>
                    <span wire:loading wire:target="save">در حال ثبت...</span>
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><strong>تماس‌های اورژانسی ثبت‌شده</strong></div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                <tr>
                    <th>دانش‌آموز</th>
                    <th>علت</th>
                    <th class="text-center">تاریخ</th>
                    <th class="text-center">وضعیت پیگیری</th>
                </tr>
                </thead>
                <tbody>
                @forelse($calls as $call)
                    <tr>
                        <td>{{ $call->student?->user?->name ?? '—' }}</td>
                        <td class="small">{{ $call->reason }}</td>
                        <td class="text-center small">{{ jdate($call->called_at)->format('Y/m/d H:i') }}</td>
                        <td class="text-center">
                            @if($call->status === \App\Models\EmergencyCall::STATUS_RESOLVED)
                                <span class="badge bg-success">پیگیری شد</span>
                            @else
                                <span class="badge bg-warning">در انتظار پیگیری</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted py-4">تماس اورژانسی‌ای ثبت نشده است.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
        <div class="p-3">{{ $calls->links() }}</div>
    </div>
</div>
