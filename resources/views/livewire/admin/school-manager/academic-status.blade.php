<div class="container-fluid">
    <h4 class="mb-3">وضعیت تحصیلی — نفرات برتر مدرسه</h4>

    {{-- فیلترها --}}
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
            <div class="col-md-3">
                <label class="form-label">پایه</label>
                <select wire:model.live="gradeFilter" class="form-select form-select-sm">
                    <option value="">همه‌ی پایه‌ها</option>
                    <option value="10">پایه ۱۰</option>
                    <option value="11">پایه ۱۱</option>
                    <option value="12">پایه ۱۲</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">رشته</label>
                <select wire:model.live="fieldFilter" class="form-select form-select-sm">
                    <option value="">همه‌ی رشته‌ها</option>
                    @foreach($fieldLabels as $slug => $label)<option value="{{ $slug }}">{{ $label }}</option>@endforeach
                </select>
            </div>
        </div>
    </div></div>

    <div class="row g-3">
        {{-- ۱) برترین ساعت مطالعه --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header"><strong>⏱ ۱۰ نفر برتر ساعت مطالعه (این ماه)</strong></div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light"><tr><th class="text-center">#</th><th>دانش‌آموز</th><th class="text-center">ساعت</th></tr></thead>
                        <tbody>
                        @forelse($topStudy as $i => $row)
                            <tr>
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td>
                                    @if($row['user'])
                                        <a href="{{ route('admin.student.studySession.detail', $row['user']) }}">{{ $row['name'] }}</a>
                                    @else {{ $row['name'] }} @endif
                                </td>
                                <td class="text-center"><strong>{{ $row['hours'] }}</strong></td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">ساعت مطالعه‌ای ثبت نشده.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ۲) برترین ارسال گزارش روزانه --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header"><strong>📄 ۱۰ نفر برتر ارسال گزارش روزانه</strong>
                    <span class="text-muted small">(هدف: {{ $reportTarget }} روز از {{ $monthDays }})</span>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light"><tr><th class="text-center">#</th><th>دانش‌آموز</th><th class="text-center">روزهای ارسال</th></tr></thead>
                        <tbody>
                        @forelse($topReports as $i => $row)
                            <tr>
                                <td class="text-center">{{ $i + 1 }}</td>
                                <td>{{ $row['name'] }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $row['days'] >= $reportTarget ? 'bg-success' : 'bg-secondary' }}">{{ $row['days'] }} / {{ $monthDays }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">گزارشی ثبت نشده.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ۳) برترین معدل --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header"><strong>🏆 برترین معدل کارنامه</strong></div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light"><tr><th class="text-center">#</th><th>دانش‌آموز</th><th class="text-center">معدل</th></tr></thead>
                        <tbody>
                        @forelse($topGrades as $i => $row)
                            <tr><td class="text-center">{{ $i + 1 }}</td><td>{{ $row['name'] }}</td><td class="text-center"><strong>{{ $row['avg'] }}</strong></td></tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">نمره‌ای ثبت نشده.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ۴) برترین میانگین تستی --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header"><strong>✅ برترین میانگین آزمون تستی</strong></div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light"><tr><th class="text-center">#</th><th>دانش‌آموز</th><th class="text-center">درصد</th></tr></thead>
                        <tbody>
                        @forelse($topTyped as $i => $row)
                            <tr><td class="text-center">{{ $i + 1 }}</td><td>{{ $row['name'] }}</td><td class="text-center"><strong>{{ $row['percent'] }}٪</strong></td></tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">آزمون تستی ثبت نشده.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ۵) برترین میانگین تشریحی --}}
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header"><strong>📝 برترین میانگین آزمون تشریحی</strong></div>
                <div class="table-responsive">
                    <table class="table table-hover mb-0 align-middle">
                        <thead class="table-light"><tr><th class="text-center">#</th><th>دانش‌آموز</th><th class="text-center">درصد</th></tr></thead>
                        <tbody>
                        @forelse($topEssay as $i => $row)
                            <tr><td class="text-center">{{ $i + 1 }}</td><td>{{ $row['name'] }}</td><td class="text-center"><strong>{{ $row['percent'] }}٪</strong></td></tr>
                        @empty
                            <tr><td colspan="3" class="text-center text-muted py-4">آزمون تشریحی ثبت نشده.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
