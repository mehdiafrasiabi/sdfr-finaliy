<div class="container-fluid">
    <h4 class="mb-3">گزارش تماس با اولیا (ماهانه)</h4>

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
                <div class="text-muted small">هدف: حداقل {{ $target }} تماس در ماه برای هر دانش‌آموز.</div>
            </div>
        </div>
    </div></div>

    <div class="row g-3 mb-4">
        <div class="col-md-4 col-6">
            <div class="card text-center"><div class="card-body py-3">
                <div class="text-muted small">کل تماس‌های این ماه</div>
                <h3 class="mb-0 mt-1">{{ $totalCalls }}</h3>
            </div></div>
        </div>
        <div class="col-md-4 col-6">
            <div class="card text-center"><div class="card-body py-3">
                <div class="text-muted small">هدف ماه ({{ $studentCount }}×{{ $target }})</div>
                <h3 class="mb-0 mt-1 text-primary">{{ $expected }}</h3>
            </div></div>
        </div>
        <div class="col-md-4 col-12">
            <div class="card text-center"><div class="card-body py-3">
                <div class="text-muted small">به هدف رسیده</div>
                <h3 class="mb-0 mt-1">{{ $metCount }} / {{ $studentCount }}</h3>
            </div></div>
        </div>
    </div>

    <div class="card">
        <div class="card-header"><strong>تفکیک به‌ازای دانش‌آموز</strong>
            <span class="text-muted small">(کم‌ترین تماس در بالا)</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                <tr>
                    <th>دانش‌آموز</th>
                    <th class="text-center">تعداد تماس</th>
                    <th class="text-center">با پدر</th>
                    <th class="text-center">با مادر</th>
                    <th class="text-center">وضعیت هدف</th>
                    <th class="text-center">جزئیات</th>
                </tr>
                </thead>
                <tbody>
                @forelse($perStudent as $row)
                    <tr>
                        <td>{{ $row['name'] }}</td>
                        <td class="text-center"><strong>{{ $row['count'] }}</strong></td>
                        <td class="text-center">{{ $row['father'] }}</td>
                        <td class="text-center">{{ $row['mother'] }}</td>
                        <td class="text-center">
                            @if($row['met'])
                                <span class="badge bg-success">رسیده</span>
                            @elseif($row['count'] === 0)
                                <span class="badge bg-danger">تماس نگرفته</span>
                            @else
                                <span class="badge bg-warning">کسری {{ $target - $row['count'] }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            @if($row['count'] > 0)
                                <button wire:click="toggleStudent({{ $row['student_id'] }})" class="btn btn-sm btn-outline-secondary">
                                    {{ $expandedStudentId === $row['student_id'] ? 'بستن' : 'تماس‌ها' }}
                                </button>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                    @if($expandedStudentId === $row['student_id'] && $row['count'] > 0)
                        <tr>
                            <td colspan="6" class="bg-light">
                                <table class="table table-sm mb-0">
                                    <thead>
                                    <tr>
                                        <th>تاریخ</th>
                                        <th>با</th>
                                        <th>توسط</th>
                                        <th>یادداشت</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($row['calls'] as $call)
                                        <tr>
                                            <td class="small">{{ $call['date'] }}</td>
                                            <td>{{ $call['with'] }}</td>
                                            <td>{{ $call['by'] }}</td>
                                            <td class="small text-muted">{{ $call['notes'] ?: '—' }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    @endif
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">دانش‌آموزی یافت نشد.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
