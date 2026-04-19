<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">لیست پشتیبانان</li>
            </ol>
        </nav>
    </div>

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row align-items-center">
                <div class="col-md-6"><h4 class="mb-0">لیست پشتیبانان تحصیلی</h4></div>
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="جستجو: نام، ایمیل، موبایل..."
                           wire:model.live.debounce.400ms="search">
                </div>
            </div>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>نام</th>
                        <th>اطلاعات تماس</th>
                        <th class="text-center">تعداد دانش‌آموزان فعال</th>
                        <th>نقش</th>
                        <th>دسترسی‌ها</th>
                        <th>برنامه کاری</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($supporters as $supporter)
                        <tr>
                            <td>{{ $loop->iteration + ($supporters->currentPage() - 1) * $supporters->perPage() }}</td>
                            <td><strong>{{ $supporter->name }}</strong></td>
                            <td>
                                <div class="small">{{ $supporter->email }}</div>
                                <div class="small text-muted">{{ $supporter->mobile }}</div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary rounded-pill">
                                    {{ $supporter->supported_students_count }}
                                </span>
                            </td>
                            <td>
                                @foreach ($supporter->roles as $role)
                                    <span class="badge bg-info-subtle text-info">{{ $role->name }}</span>
                                @endforeach
                            </td>
                            <td>
                                @php
                                    $perms = $supporter->roles->pluck('permissions')->flatten()->pluck('name')->unique();
                                @endphp
                                <div style="max-height:120px;overflow:auto;" class="small">
                                    @foreach ($perms as $p) <div>{{ $p }}</div> @endforeach
                                </div>
                            </td>
                            <td>
                                @php
                                    $schedules = $supporter->workSchedules->where('is_active', true)->sortBy('day_of_week');
                                    $days = \App\Models\AdminWorkSchedule::DAYS;
                                @endphp
                                @forelse ($schedules as $s)
                                    <div class="small">
                                        {{ $days[$s->day_of_week] ?? '' }}:
                                        {{ substr($s->start_time, 0, 5) }} تا {{ substr($s->end_time, 0, 5) }}
                                    </div>
                                @empty
                                    <span class="small text-muted">تعریف نشده</span>
                                @endforelse
                            </td>
                            <td>
                                <a href="{{ route('admin.admin-user.work-schedule', $supporter->id) }}"
                                   class="btn btn-sm btn-outline-primary">برنامه کاری</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted">پشتیبانی یافت نشد.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $supporters->links() }}</div>
        </div>
    </div>
</div>

