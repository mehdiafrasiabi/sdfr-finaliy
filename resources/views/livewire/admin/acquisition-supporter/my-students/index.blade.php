<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">دانش‌آموزان من</li>
            </ol>
        </nav>
    </div>

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row align-items-center">
                <div class="col-md-6"><h4 class="mb-0">دانش‌آموزان من</h4></div>
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
                            <th>پایه</th>
                            <th>رشته</th>
                            <th>وضعیت</th>
                            <th>مشاور تحصیلی</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($trials as $trial)
                        <tr>
                            <td>{{ $trial->id }}</td>
                            <td><strong>{{ $trial->user?->personalInformation?->name ?? $trial->user?->name ?? '—' }}</strong></td>
                            <td>{{ $trial->user?->mobile ?? '—' }}</td>
                            <td>{{ $trial->grade_label }}</td>
                            <td>{{ $trial->field_label }}</td>
                            <td>
                                <span class="badge bg-{{ $trial->status_color }}-subtle text-{{ $trial->status_color }}">
                                    {{ $trial->status_label }}
                                </span>
                            </td>
                            <td>{{ $trial->student?->advisor?->name ?? '—' }}</td>
                            <td>
                                <a href="{{ route('admin.acquisition-supporter.student', $trial->id) }}"
                                   class="btn btn-sm btn-primary">
                                    جزئیات دانش‌آموز
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                دانش‌آموزی برای شما تخصیص داده نشده است.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $trials->links() }}</div>
        </div>
    </div>
</div>
