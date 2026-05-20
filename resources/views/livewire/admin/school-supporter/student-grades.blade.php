<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">نمرات: {{ $student->user?->name }}</h4>
        <a href="{{ route('admin.school-supporter.students', $school->id) }}" class="btn btn-sm btn-outline-secondary">بازگشت</a>
    </div>

    <div class="card mb-3">
        <div class="card-header"><strong>ثبت نمره جدید</strong></div>
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">درس</label>
                    <select wire:model.live="cc_subject_id" class="form-select">
                        <option value="">--</option>
                        @foreach($subjects as $s)
                            <option value="{{ $s->id }}">{{ $s->name }}</option>
                        @endforeach
                    </select>
                    @error('cc_subject_id') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">فصل (اختیاری)</label>
                    <select wire:model="cc_chapter_id" class="form-select" @if(!$cc_subject_id) disabled @endif>
                        <option value="">--</option>
                        @foreach($chapters as $c)
                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">نمره</label>
                    <input type="number" step="0.25" wire:model="score" class="form-control">
                    @error('score') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">مقیاس</label>
                    <select wire:model.live="scale" class="form-select">
                        <option value="20">از ۲۰</option>
                        <option value="100">از ۱۰۰</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">تاریخ</label>
                    <input type="date" wire:model="recorded_at" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label">یادداشت (اختیاری)</label>
                    <input type="text" wire:model="note" class="form-control" maxlength="255">
                </div>
            </div>
            <div class="text-end mt-3">
                <button wire:click="save" class="btn btn-primary">
                    <span wire:loading.remove>ثبت نمره</span>
                    <span wire:loading>در حال ثبت...</span>
                </button>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><strong>نمرات ثبت‌شده</strong></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr>
                        <th>ردیف</th>
                        <th>درس</th>
                        <th>فصل</th>
                        <th>نمره</th>
                        <th>تاریخ</th>
                        <th>یادداشت</th>
                        <th>ثبت‌کننده</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($grades as $g)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $g->subject?->name ?? '—' }}</td>
                            <td>{{ $g->chapter?->name ?? '—' }}</td>
                            <td><strong>{{ $g->score }}</strong> <small class="text-muted">/{{ $g->scale }}</small></td>
                            <td>{{ jalali($g->recorded_at)->format('Y/m/d') }}</td>
                            <td>{{ $g->note ?? '—' }}</td>
                            <td>{{ $g->recordedBy?->name ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">نمره‌ای ثبت نشده است.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            {{ $grades->links() }}
        </div>
    </div>
</div>
