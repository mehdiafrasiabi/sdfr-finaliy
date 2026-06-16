<div class="container-fluid"
     x-data="{ ok:'' }"
     x-on:success.window="ok = ($event.detail && ($event.detail[0] ?? $event.detail)) || ''; setTimeout(() => ok='', 3500)">

    <h4 class="mb-3">ثبت نمرات کارنامهٔ ماهانه</h4>

    <div x-show="ok" x-cloak class="alert alert-success py-2" x-text="ok"></div>

    {{-- مرحله ۱: انتخاب ماه --}}
    <div class="card mb-3">
        <div class="card-header"><strong>۱) انتخاب ماه</strong></div>
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">سال</label>
                    <select wire:model.live="jalaliYear" class="form-select">
                        @foreach($yearOptions as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">ماه</label>
                    <select wire:model.live="jalaliMonth" class="form-select">
                        @foreach($monthNames as $num => $name)
                            <option value="{{ $num }}">{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <div class="text-muted small">
                        نمرات هر ماه مستقل است؛ نمرهٔ ثبت‌شده برای این ماه فقط برای همین ماه استفاده می‌شود.
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- مرحله ۲: انتخاب دانش‌آموز --}}
    <div class="card mb-3">
        <div class="card-header"><strong>۲) انتخاب دانش‌آموز (تحت مشاورهٔ شما)</strong></div>
        <div class="card-body">
            @if($students->isEmpty())
                <div class="text-muted small">دانش‌آموزی به شما تخصیص داده نشده است.</div>
            @else
                <div class="d-flex flex-wrap gap-2">
                    @foreach($students as $s)
                        <button wire:click="selectStudent({{ $s->id }})"
                                class="btn btn-sm {{ $selectedStudentId === $s->id ? 'btn-primary' : 'btn-outline-secondary' }}">
                            {{ $s->user?->name ?? '—' }}
                            <span class="opacity-75">(پایه {{ $s->grade }} - {{ $s->field ?: 'بدون رشته' }})</span>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- مرحله ۳: ثبت نمرات دروس --}}
    @if($selectedStudent)
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>۳) نمرات {{ $selectedStudent->user?->name }} — {{ $monthNames[$jalaliMonth] }} {{ $jalaliYear }}</strong>
            </div>
            <div class="card-body">
                @if($subjects->isEmpty())
                    <div class="text-muted small">درسی برای پایه/رشتهٔ این دانش‌آموز یافت نشد.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                            <tr>
                                <th style="min-width:180px">درس</th>
                                <th style="width:140px">فعالیت کلاسی (۲۰)</th>
                                <th style="width:140px">امتحان (۲۰)</th>
                                <th style="width:110px">معدل درس</th>
                                <th>نظر دبیر (اختیاری)</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($subjects as $sub)
                                <tr wire:key="subj-{{ $sub->id }}"
                                    x-data="{
                                        a: @entangle('rows.'.$sub->id.'.class_activity'),
                                        e: @entangle('rows.'.$sub->id.'.exam'),
                                        get avg() {
                                            const a = this.a === '' || this.a === null ? null : parseFloat(this.a);
                                            const e = this.e === '' || this.e === null ? null : parseFloat(this.e);
                                            if (a === null && e === null) return '—';
                                            if (a === null) return e.toFixed(2);
                                            if (e === null) return a.toFixed(2);
                                            return (((a * 1) + (e * 3)) / 4).toFixed(2);
                                        }
                                    }">
                                    <td>{{ $sub->name }}</td>
                                    <td>
                                        <input type="number" step="0.25" min="0" max="20"
                                               wire:model="rows.{{ $sub->id }}.class_activity"
                                               class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <input type="number" step="0.25" min="0" max="20"
                                               wire:model="rows.{{ $sub->id }}.exam"
                                               class="form-control form-control-sm">
                                    </td>
                                    <td class="text-center"><strong x-text="avg"></strong></td>
                                    <td>
                                        <input type="text" maxlength="500"
                                               wire:model="rows.{{ $sub->id }}.teacher_comment"
                                               class="form-control form-control-sm" placeholder="—">
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-3">
                        <button wire:click="save" wire:loading.attr="disabled" wire:target="save" class="btn btn-primary">
                            <span wire:loading.remove wire:target="save">ثبت نمرات</span>
                            <span wire:loading wire:target="save">در حال ثبت...</span>
                        </button>
                    </div>
                @endif
            </div>
        </div>
    @endif

    {{-- ثبت گروهی با اکسل --}}
    <div class="card">
        <div class="card-header"><strong>ثبت گروهی با اکسل ({{ $monthNames[$jalaliMonth] }} {{ $jalaliYear }})</strong></div>
        <div class="card-body">
            <div class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label">۱) دریافت قالب</label>
                    <div>
                        <button wire:click="exportTemplate" wire:loading.attr="disabled" wire:target="exportTemplate"
                                class="btn btn-outline-success">
                            <span wire:loading.remove wire:target="exportTemplate">دانلود لیست دانش‌آموزان و دروس</span>
                            <span wire:loading wire:target="exportTemplate">در حال آماده‌سازی...</span>
                        </button>
                    </div>
                </div>
                <div class="col-md-5">
                    <label class="form-label">۲) بارگذاری فایل تکمیل‌شده</label>
                    <input type="file" wire:model="importFile" class="form-control" accept=".xlsx,.xls,.csv">
                    @error('importFile') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-3">
                    <button wire:click="importGrades" wire:loading.attr="disabled" wire:target="importGrades,importFile"
                            class="btn btn-primary w-100" @if(!$importFile) disabled @endif>
                        <span wire:loading.remove wire:target="importGrades">ثبت گروهی</span>
                        <span wire:loading wire:target="importGrades,importFile">در حال پردازش...</span>
                    </button>
                </div>
            </div>

            @if(!empty($importInvalidRows))
                <div class="alert alert-warning mt-3 mb-0">
                    <strong>ردیف‌های نامعتبر ({{ count($importInvalidRows) }}):</strong>
                    <ul class="mb-0 small">
                        @foreach($importInvalidRows as $bad)
                            <li>ردیف {{ $bad['row'] }}: {{ implode('، ', $bad['errors']) }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
