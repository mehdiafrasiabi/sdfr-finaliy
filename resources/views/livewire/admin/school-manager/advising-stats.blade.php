<div class="container-fluid">
    <h4 class="mb-3">آمار جلسات مشاوره</h4>

    <div class="row g-3 mb-4">
        <div class="col-md-3 col-6">
            <div class="card text-center"><div class="card-body py-3">
                <div class="text-muted small">کل جلسات</div>
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
                <div class="text-muted small">غیبت دانش‌آموز</div>
                <h3 class="mb-0 mt-1 text-warning">{{ $summary['student_absent'] }}</h3>
            </div></div>
        </div>
        <div class="col-md-3 col-6">
            <div class="card text-center"><div class="card-body py-3">
                <div class="text-muted small">غیبت مشاور</div>
                <h3 class="mb-0 mt-1 text-danger">{{ $summary['advisor_absent'] }}</h3>
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
                    <th class="text-center">کل</th>
                    <th class="text-center">برگزارشده</th>
                    <th class="text-center">غیبت دانش‌آموز</th>
                    <th class="text-center">غیبت مشاور</th>
                </tr>
                </thead>
                <tbody>
                @forelse($perStudent as $row)
                    <tr>
                        <td>{{ $row['student']?->user?->name ?? '—' }}</td>
                        <td class="text-center">{{ $row['total'] }}</td>
                        <td class="text-center text-success">{{ $row['held'] }}</td>
                        <td class="text-center text-warning">{{ $row['student_absent'] }}</td>
                        <td class="text-center text-danger">{{ $row['advisor_absent'] }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">جلسه‌ای ثبت نشده.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
