<div dir="rtl" class="container py-4">
    <div class="d-flex justify-content-between mb-4">
        <h3>قیمت‌گذاری پایه‌ها</h3>
        <a href="{{ route('manager.grade-prices.create') }}" class="btn btn-primary">+ قیمت جدید</a>
    </div>

    <div class="card">
        <table class="table mb-0">
            <thead class="table-light">
            <tr>
                <th>پایه</th>
                <th>قیمت پایه</th>
                <th>تعداد ماه</th>
                <th>بازه</th>
                <th>وضعیت</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @forelse ($prices as $p)
                <tr>
                    <td>پایه {{ $p->grade }}</td>
                    <td>{{ number_format($p->base_price) }} تومان</td>
                    <td>{{ $p->months_count }}</td>
                    <td>{{ $p->start_date->format('Y-m-d') }} → {{ $p->end_date->format('Y-m-d') }}</td>
                    <td>
                        <span class="badge bg-{{ $p->is_active ? 'success' : 'secondary' }}">
                            {{ $p->is_active ? 'فعال' : 'غیرفعال' }}
                        </span>
                    </td>
                    <td><a href="{{ route('manager.grade-prices.edit', $p->id) }}" class="btn btn-sm btn-outline-primary">ویرایش</a></td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">قیمتی تعریف نشده.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
