<div class="container-fluid">
    <h4 class="mb-3">تحلیل طبقه‌بندی دروس</h4>

    {{-- فیلترها --}}
    <div class="card mb-3"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">پایه</label>
                <select wire:model.live="gradeFilter" class="form-select form-select-sm">
                    <option value="">همه‌ی پایه‌ها</option>
                    <option value="10">پایه ۱۰</option>
                    <option value="11">پایه ۱۱</option>
                    <option value="12">پایه ۱۲</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label">رشته</label>
                <select wire:model.live="fieldFilter" class="form-select form-select-sm">
                    <option value="">همه‌ی رشته‌ها</option>
                    @foreach($fieldLabels as $slug => $label)<option value="{{ $slug }}">{{ $label }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-4">
                <div class="text-muted small">{{ $studentCount }} دانش‌آموز در این گروه</div>
            </div>
        </div>
    </div></div>

    <div class="row g-3">
        {{-- هشدار: دروس ضعیف (M6) --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-danger-subtle">
                    <strong class="text-danger">⚠ دروسی که دانش‌آموزان در آن‌ها ضعیف‌اند</strong>
                    <div class="text-muted small">کیفیت تدریس دبیران این دروس نیاز مند پیگیری میباشد</div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                        <tr>
                            <th>درس</th>
                            <th class="text-center">تعداد ضعیف</th>
                            <th class="text-center">درصد</th>
                            <th class="text-center">میانگین طبقه</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($weakSubjects as $row)
                            <tr>
                                <td>{{ $row['subject'] }}</td>
                                <td class="text-center">{{ $row['weak'] }} / {{ $row['total'] }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $row['percent'] >= 50 ? 'bg-danger' : 'bg-warning' }}">{{ $row['percent'] }}٪</span>
                                </td>
                                <td class="text-center">{{ \App\Support\ClassificationProgress::ratingLabel($row['avg']) }} ({{ $row['avg'] }})</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-4">درس ضعیفی یافت نشد.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- بیشترین پیشرفت (M7) --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-success-subtle">
                    <strong class="text-success">▲ دروسی با بیشترین پیشرفت</strong>
                    <div class="text-muted small">دبیران این دروس در پیشرفت دانش آموزان نقش داشتند</div>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light">
                        <tr>
                            <th>درس</th>
                            <th class="text-center">میانگین تغییر</th>
                            <th class="text-center">پیشرفت‌کرده</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($topProgress as $row)
                            <tr>
                                <td>{{ $row['subject'] }}</td>
                                <td class="text-center">
                                    @if($row['avg_delta'] > 0)
                                        <span class="badge bg-success">▲ {{ $row['avg_delta'] }}</span>
                                    @elseif($row['avg_delta'] < 0)
                                        <span class="badge bg-danger">▼ {{ abs($row['avg_delta']) }}</span>
                                    @else
                                        <span class="badge bg-secondary">۰</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $row['improved'] }} / {{ $row['total'] }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">داده‌ی کافی برای محاسبهٔ پیشرفت وجود ندارد (حداقل دو دورهٔ طبقه‌بندی لازم است).</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
