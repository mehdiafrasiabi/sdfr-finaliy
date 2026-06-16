<div class="container-fluid">
    <h4 class="mb-3">آمار آزمون‌های دانش‌آموزان</h4>

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
        </div>
    </div></div>

    {{-- کارت‌های خلاصه --}}
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card text-center"><div class="card-body py-3">
                <div class="text-muted small">آزمون‌های تستی</div>
                <h3 class="mb-0 mt-1 text-primary">{{ $typedCount }}</h3>
            </div></div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card text-center"><div class="card-body py-3">
                <div class="text-muted small">میانگین درصد تستی</div>
                <h3 class="mb-0 mt-1 text-primary">{{ $typedAvg }}٪</h3>
            </div></div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card text-center"><div class="card-body py-3">
                <div class="text-muted small">آزمون‌های تشریحی</div>
                <h3 class="mb-0 mt-1 text-success">{{ $essayCount }}</h3>
                <div class="text-muted small">({{ $essayGraded }} تصحیح‌شده)</div>
            </div></div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card text-center"><div class="card-body py-3">
                <div class="text-muted small">میانگین درصد تشریحی</div>
                <h3 class="mb-0 mt-1 text-success">{{ $essayAvg }}٪</h3>
            </div></div>
        </div>
    </div>

    {{-- جزئیات آزمون‌های تستی --}}
    <div class="card mb-4">
        <div class="card-header"><strong>جزئیات آزمون‌های تستی</strong>
            <span class="text-muted small">(حداکثر {{ $detailLimit }} مورد اخیر)</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                <tr>
                    <th>آزمون</th>
                    <th>دانش‌آموز</th>
                    <th class="text-center">درست</th>
                    <th class="text-center">غلط</th>
                    <th class="text-center">بی‌پاسخ</th>
                    <th class="text-center">درصد</th>
                    <th class="text-center">تاریخ</th>
                </tr>
                </thead>
                <tbody>
                @forelse($typedDetails as $row)
                    <tr>
                        <td>{{ $row['exam'] }}</td>
                        <td>{{ $row['student'] }}</td>
                        <td class="text-center text-success">{{ $row['correct'] }}</td>
                        <td class="text-center text-danger">{{ $row['wrong'] }}</td>
                        <td class="text-center text-muted">{{ $row['unanswered'] }}</td>
                        <td class="text-center"><strong>{{ $row['score'] !== null ? $row['score'] . '٪' : '—' }}</strong></td>
                        <td class="text-center small">{{ $row['date'] }}</td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">آزمون تستی ثبت نشده است.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- جزئیات آزمون‌های تشریحی --}}
    <div class="card">
        <div class="card-header"><strong>جزئیات آزمون‌های تشریحی</strong>
            <span class="text-muted small">(حداکثر {{ $detailLimit }} مورد اخیر)</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                <tr>
                    <th>آزمون</th>
                    <th>دانش‌آموز</th>
                    <th class="text-center">نمره</th>
                    <th class="text-center">درصد</th>
                    <th class="text-center">وضعیت</th>
                    <th class="text-center">تاریخ</th>
                </tr>
                </thead>
                <tbody>
                @forelse($essayDetails as $row)
                    <tr>
                        <td>{{ $row['exam'] }}</td>
                        <td>{{ $row['student'] }}</td>
                        <td class="text-center">
                            @if($row['score'] !== null)<strong>{{ $row['score'] }}</strong>@if($row['total']) / {{ $row['total'] }} @endif
                            @else — @endif
                        </td>
                        <td class="text-center">{{ $row['percent'] !== null ? $row['percent'] . '٪' : '—' }}</td>
                        <td class="text-center">
                            @if($row['status'] === 'graded')
                                <span class="badge bg-success">تصحیح‌شده</span>
                            @else
                                <span class="badge bg-warning">ارسال‌شده</span>
                            @endif
                        </td>
                        <td class="text-center small">{{ $row['date'] }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">آزمون تشریحی ثبت نشده است.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
