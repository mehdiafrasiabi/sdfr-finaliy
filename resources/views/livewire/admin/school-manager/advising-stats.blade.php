<div class="container-fluid">
    <h4 class="mb-3">آمار جلسات مشاوره (ماهانه)</h4>

    {{-- انتخاب ماه --}}
    <div class="card mb-3"><div class="card-body">
        <div class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">سال</label>
                <select wire:model.live="jalaliYear" class="form-select form-select-sm">
                    @foreach($yearOptions as $y)<option value="{{ $y }}">{{ $y }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">ماه</label>
                <select wire:model.live="jalaliMonth" class="form-select form-select-sm">
                    @foreach($monthNames as $num => $name)<option value="{{ $num }}">{{ $name }}</option>@endforeach
                </select>
            </div>
            <div class="col-md-6">
                <div class="text-muted small">هدف: حداقل {{ $target }} جلسه (برنامه) در ماه برای هر دانش‌آموز.</div>
            </div>
        </div>
    </div></div>

    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card text-center"><div class="card-body py-3">
                <div class="text-muted small">کل جلسات این ماه</div>
                <h3 class="mb-0 mt-1">{{ $summary['total'] }}</h3>
            </div></div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card text-center"><div class="card-body py-3">
                <div class="text-muted small">برگزارشده</div>
                <h3 class="mb-0 mt-1 text-success">{{ $summary['held'] }}</h3>
            </div></div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card text-center"><div class="card-body py-3">
                <div class="text-muted small">هدف ماه ({{ $studentCount }}×{{ $target }})</div>
                <h3 class="mb-0 mt-1 text-primary">{{ $expected }}</h3>
            </div></div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card text-center"><div class="card-body py-3">
                <div class="text-muted small">به هدف رسیده</div>
                <h3 class="mb-0 mt-1">{{ $metCount }} / {{ $studentCount }}</h3>
            </div></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><strong>تفکیک به‌ازای دانش‌آموز</strong></div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                <tr>
                    <th>دانش‌آموز</th>
                    <th class="text-center">برگزارشده</th>
                    <th class="text-center">کل</th>
                    <th class="text-center">غیبت دانش‌آموز</th>
                    <th class="text-center">غیبت مشاور</th>
                    <th class="text-center">وضعیت هدف</th>
                </tr>
                </thead>
                <tbody>
                @forelse($perStudent as $row)
                    <tr>
                        <td>{{ $row['name'] }}</td>
                        <td class="text-center text-success"><strong>{{ $row['held'] }}</strong></td>
                        <td class="text-center">{{ $row['total'] }}</td>
                        <td class="text-center text-warning">{{ $row['student_absent'] }}</td>
                        <td class="text-center text-danger">{{ $row['advisor_absent'] }}</td>
                        <td class="text-center">
                            @if($row['met'])
                                <span class="badge bg-success">رسیده</span>
                            @else
                                <span class="badge bg-danger">کسری {{ $target - $row['held'] }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">دانش‌آموزی یافت نشد.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
