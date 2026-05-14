<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">دانش‌آموز جدید (خرید کرده)</li>
            </ol>
        </nav>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h4 class="mb-0">دانش‌آموزان جدید (پس از خرید)</h4>
                    <p class="small text-muted mb-0">برای هر دانش‌آموز یک «مشاور تحصیلی» انتخاب کنید.</p>
                </div>
                <div class="col-md-6">
                    <input type="text" wire:model.live.debounce.400ms="search" class="form-control"
                           placeholder="جستجو بر اساس نام یا موبایل…">
                </div>
            </div>
        </div>

        <div class="widget-content widget-content-area">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>دانش‌آموز</th>
                            <th>موبایل</th>
                            <th>محصول</th>
                            <th>تاریخ ثبت‌نام</th>
                            <th>مشاور تحصیلی</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>
                                <strong>{{ $user->personalInformation?->name ?? $user->name }}</strong>
                            </td>
                            <td>{{ $user->mobile ?? '—' }}</td>
                            <td>{{ $user->student?->product?->title ?? '—' }}</td>
                            <td class="small">
                                {{ \Morilog\Jalali\Jalalian::fromDateTime($user->created_at)->format('Y/m/d H:i') }}
                            </td>
                            <td style="min-width:220px">
                                <select wire:model="selectedAdvisor.{{ $user->id }}" class="form-select form-select-sm">
                                    <option value="">انتخاب کنید…</option>
                                    @foreach ($advisors as $advisor)
                                        <option value="{{ $advisor->id }}">
                                            {{ $advisor->name }} — {{ $advisor->mobile }}
                                        </option>
                                    @endforeach
                                </select>
                                @error("selectedAdvisor.{$user->id}")
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <button wire:click="assign({{ $user->id }})"
                                        class="btn btn-sm btn-primary">
                                    تخصیص
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                هیچ دانش‌آموز خریداری شده‌ای بدون مشاور وجود ندارد.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
