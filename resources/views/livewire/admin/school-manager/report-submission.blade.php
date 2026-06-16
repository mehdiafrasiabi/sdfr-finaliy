<div class="container-fluid">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 gap-2">
        <h4 class="mb-0">گزارش ارسال گزارش‌های روزانه</h4>
        <div class="d-flex align-items-center gap-2">
            <button wire:click="prevWeek" class="btn btn-sm btn-outline-secondary">هفتهٔ قبل ›</button>
            <span class="badge bg-light text-dark">{{ $weekLabel }}</span>
            <button wire:click="nextWeek" class="btn btn-sm btn-outline-secondary" @if($isCurrentWeek) disabled @endif>‹ هفتهٔ بعد</button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4 col-12">
            <div class="card text-center"><div class="card-body py-3">
                <div class="text-muted small">دانش‌آموزان مدرسه</div>
                <h3 class="mb-0 mt-1">{{ $total }}</h3>
            </div></div>
        </div>
        <div class="col-md-8 col-12">
            <div class="card text-center"><div class="card-body py-3">
                <div class="text-muted small">میانگین ارسال در این هفته (روز-دانش‌آموز)</div>
                <h3 class="mb-0 mt-1 text-primary">{{ $overallPercent }}٪</h3>
            </div></div>
        </div>
    </div>

    {{-- تفکیک روزانه --}}
    <div class="card mb-4">
        <div class="card-header"><strong>تفکیک روزانه (جبرانی محاسبه نشده)</strong></div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                <tr>
                    <th>روز</th>
                    <th class="text-center">ارسال‌کرده</th>
                    <th class="text-center">ارسال‌نکرده</th>
                    <th class="text-center">درصد ارسال</th>
                </tr>
                </thead>
                <tbody>
                @foreach($days as $day)
                    <tr>
                        <td>{{ $day['jalali'] }}</td>
                        <td class="text-center text-success">{{ $day['sent'] }}</td>
                        <td class="text-center text-danger">{{ $day['not_sent'] }}</td>
                        <td class="text-center">
                            <span class="badge {{ $day['percent'] >= 70 ? 'bg-success' : ($day['percent'] >= 40 ? 'bg-warning' : 'bg-danger') }}">{{ $day['percent'] }}٪</span>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- وضعیت هر دانش‌آموز در این هفته --}}
    <div class="card">
        <div class="card-header"><strong>وضعیت دانش‌آموزان (تعداد روزهای دارای گزارش از ۷)</strong></div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                <tr>
                    <th>دانش‌آموز</th>
                    <th class="text-center">روزهای دارای گزارش</th>
                    <th class="text-center">وضعیت</th>
                </tr>
                </thead>
                <tbody>
                @forelse($studentRows as $row)
                    <tr>
                        <td>{{ $row['name'] }}</td>
                        <td class="text-center">{{ $row['days'] }} / ۷</td>
                        <td class="text-center">
                            @if($row['days'] === 0)
                                <span class="badge bg-danger">هیچ گزارشی نداده</span>
                            @elseif($row['days'] < 4)
                                <span class="badge bg-warning">کم</span>
                            @else
                                <span class="badge bg-success">خوب</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted py-4">دانش‌آموزی یافت نشد.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
