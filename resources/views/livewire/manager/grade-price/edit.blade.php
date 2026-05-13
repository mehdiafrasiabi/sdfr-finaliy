<div dir="rtl" class="container py-4">
    <h3 class="mb-4">ویرایش قیمت پایه {{ $gradePrice->grade }}</h3>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form wire:submit.prevent="save" class="card p-4">
        <table class="table">
            <thead class="table-light">
            <tr>
                <th>ماه</th>
                <th>قیمت (تومان)</th>
                <th>تخفیف (%)</th>
                <th>قیمت نهایی</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($gradePrice->months as $m)
                @php
                    $price = (int) ($monthPrices[$m->month_index] ?? $m->price);
                    $discount = (int) ($discounts[$m->month_index] ?? 0);
                    $final = (int) round($price * (100 - $discount) / 100);
                @endphp
                <tr>
                    <td>ماه {{ $m->month_index + 1 }}</td>
                    <td><input type="number" wire:model.live="monthPrices.{{ $m->month_index }}" class="form-control form-control-sm"></td>
                    <td><input type="number" min="0" max="100" wire:model.live="discounts.{{ $m->month_index }}" class="form-control form-control-sm" placeholder="0"></td>
                    <td><strong>{{ number_format($final) }}</strong></td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-success align-self-start">ذخیره</button>
    </form>
</div>
