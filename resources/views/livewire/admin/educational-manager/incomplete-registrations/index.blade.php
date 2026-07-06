<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">ثبت‌نام‌های ناقص</li>
            </ol>
        </nav>
    </div>

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <h4 class="mb-3">ثبت‌نام‌های ناقص</h4>

            <ul class="nav nav-pills gap-2">
                <li class="nav-item">
                    <button class="nav-link {{ $tab === 'not_started' ? 'active' : '' }}" wire:click="setTab('not_started')">
                        آزمون شروع‌نشده
                        <span class="badge bg-danger ms-1">{{ $counts['not_started'] }}</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link {{ $tab === 'abandoned' ? 'active' : '' }}" wire:click="setTab('abandoned')">
                        آزمون نیمه‌رها (۱ روز گذشته)
                        <span class="badge bg-warning text-dark ms-1">{{ $counts['abandoned'] }}</span>
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link {{ $tab === 'pre_session' ? 'active' : '' }}" wire:click="setTab('pre_session')">
                        منتظر تکمیل پیش‌جلسه
                        <span class="badge bg-info ms-1">{{ $counts['pre_session'] }}</span>
                    </button>
                </li>
            </ul>
        </div>

        <div class="widget-content widget-content-area">
            @php
                $hint = [
                    'not_started' => 'این افراد ثبت‌نام کرده‌اند اما هنوز هیچ آزمونی را شروع نکرده‌اند.',
                    'abandoned'   => 'این افراد آزمون را شروع کرده ولی نیمه‌کاره رها کرده‌اند و بیش از ۱ روز گذشته است.',
                    'pre_session' => 'این افراد آزمون‌ها را تکمیل کرده‌اند اما هنوز پیش‌جلسه را پر نکرده‌اند.',
                ][$tab];
            @endphp
            <p class="text-muted small">{{ $hint }}</p>

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>نام</th>
                            <th>موبایل</th>
                            <th>پایه/رشته</th>
                            <th>تاریخ ثبت‌نام</th>
                            <th>روزهای گذشته</th>
                            <th>وضعیت</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($trials as $trial)
                        <tr>
                            <td>{{ $trial->user?->name ?? '—' }}</td>
                            <td dir="ltr">{{ $trial->user?->mobile ?? '—' }}</td>
                            <td>{{ $trial->grade_label }} / {{ $trial->field_label }}</td>
                            <td>{{ jalali($trial->created_at)->format('%d %B %Y') }}</td>
                            <td>{{ (int) \Carbon\Carbon::parse($trial->created_at)->diffInDays(now()) }} روز</td>
                            <td><span class="badge bg-{{ $trial->status_color }}">{{ $trial->status_label }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">موردی در این دسته نیست.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $trials->links() }}</div>
        </div>
    </div>
</div>
