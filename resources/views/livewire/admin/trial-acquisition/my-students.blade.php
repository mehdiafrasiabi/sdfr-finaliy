<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.trial-acquisition.dashboard') }}">مشاوره جذب</a></li>
                <li class="breadcrumb-item active">دانش‌آموزان من</li>
            </ol>
        </nav>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
                <div>
                    <h5 class="mb-1">دانش‌آموزان من</h5>
                    <div class="small text-muted">اطلاعات دانش‌آموزان هفته آزمایشی که به شما تخصیص داده شده‌اند</div>
                </div>
                <div class="w-100 w-md-auto" style="max-width: 360px;">
                    <input type="text"
                           wire:model.live.debounce.400ms="search"
                           class="form-control"
                           placeholder="جستجو نام، موبایل، تلفن پدر یا مادر">
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                    <tr>
                        <th>نام</th>
                        <th>نام خانوادگی</th>
                        <th class="text-center">تلفن دانش‌آموز</th>
                        <th class="text-center">تلفن پدر</th>
                        <th class="text-center">تلفن مادر</th>
                        <th class="text-center">استان / شهر</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($students as $trial)
                        @php
                            $info = $trial->user?->personalInformation;
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $info?->name ?? $trial->user?->name ?? '—' }}</td>
                            <td>{{ $info?->name_full ?: '—' }}</td>
                            <td class="text-center" dir="ltr">{{ $trial->user?->mobile ?? '—' }}</td>
                            <td class="text-center" dir="ltr">{{ $trial->father_mobile ?? $info?->father_mobile ?? '—' }}</td>
                            <td class="text-center" dir="ltr">{{ $trial->mother_mobile ?? $info?->mother_mobile ?? '—' }}</td>
                            <td class="text-center">{{ ($info?->province?->name ?? '') . ($info?->city?->name ? ' / ' . $info->city->name : '') ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">دانش‌آموزی برای نمایش وجود ندارد.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($students->hasPages())
            <div class="card-footer bg-white">
                {{ $students->links('layouts.admin.pagination') }}
            </div>
        @endif
    </div>
</div>
