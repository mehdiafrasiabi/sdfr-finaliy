<div class="container-fluid"
     x-data="{ ok:'' }"
     x-on:success.window="ok = ($event.detail && ($event.detail[0] ?? $event.detail)) || ''; setTimeout(() => ok='', 3000)">

    <h4 class="mb-3">ثبت نمرات ماهانه</h4>

    <div x-show="ok" x-cloak class="alert alert-success py-2" x-text="ok"></div>

    {{-- مرحله ۱: انتخاب پایه --}}
    <div class="card mb-3">
        <div class="card-header"><strong>۱) انتخاب پایه</strong></div>
        <div class="card-body">
            @if($grades->isEmpty())
                <div class="text-muted small">پایه‌ای برای دانش‌آموزان مدرسه ثبت نشده.</div>
            @else
                <div class="d-flex flex-wrap gap-2">
                    @foreach($grades as $g)
                        <button wire:click="selectGrade('{{ $g }}')"
                                class="btn btn-sm {{ $selectedGrade === (string)$g ? 'btn-primary' : 'btn-outline-secondary' }}">
                            پایه {{ $g }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- مرحله ۲: انتخاب دانش‌آموز --}}
    @if($selectedGrade !== '')
        <div class="card mb-3">
            <div class="card-header"><strong>۲) انتخاب دانش‌آموز پایه {{ $selectedGrade }}</strong></div>
            <div class="card-body">
                @if($students->isEmpty())
                    <div class="text-muted small">دانش‌آموزی در این پایه نیست.</div>
                @else
                    <div class="d-flex flex-wrap gap-2">
                        @foreach($students as $s)
                            <button wire:click="selectStudent({{ $s->id }})"
                                    class="btn btn-sm {{ $selectedStudentId === $s->id ? 'btn-primary' : 'btn-outline-secondary' }}">
                                {{ $s->user?->name ?? '—' }}
                                <span class="opacity-75">({{ $s->field ?: 'بدون رشته' }})</span>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- مرحله ۳: فرم ثبت نمره --}}
    @if($selectedStudent)
        <div class="card mb-3">
            <div class="card-header"><strong>۳) ثبت نمره برای: {{ $selectedStudent->user?->name }}</strong></div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">درس</label>
                        <select wire:model.live="cc_subject_id" class="form-select">
                            <option value="">--</option>
                            @foreach($subjects as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->name }}</option>
                            @endforeach
                        </select>
                        @error('cc_subject_id') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">فصل (اختیاری)</label>
                        <select wire:model="cc_chapter_id" class="form-select" @if(!$cc_subject_id) disabled @endif>
                            <option value="">--</option>
                            @foreach($chapters as $ch)
                                <option value="{{ $ch->id }}">{{ $ch->name }}</option>
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
                        @error('recorded_at') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>
                    <div class="col-12">
                        <label class="form-label">یادداشت (اختیاری)</label>
                        <input type="text" wire:model="note" class="form-control" maxlength="255">
                    </div>
                </div>
                <div class="text-end mt-3">
                    <button wire:click="save" wire:loading.attr="disabled" class="btn btn-primary">
                        <span wire:loading.remove wire:target="save">ثبت نمره</span>
                        <span wire:loading wire:target="save">در حال ثبت...</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header"><strong>نمرات اخیر</strong></div>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>درس</th><th>فصل</th><th>نمره</th><th>تاریخ</th><th>یادداشت</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($recentGrades as $g)
                        <tr>
                            <td>{{ $g->subject?->name ?? '—' }}</td>
                            <td>{{ $g->chapter?->name ?? '—' }}</td>
                            <td><strong>{{ $g->score }}</strong>/{{ $g->scale }}</td>
                            <td>{{ jalali($g->recorded_at)->format('Y/m/d') }}</td>
                            <td>{{ $g->note ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">نمره‌ای ثبت نشده.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
