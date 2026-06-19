<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">اختصاص شماره به مشاور</li>
            </ol>
        </nav>
    </div>

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <h4 class="mb-0">اختصاص روزانهٔ شماره‌ها</h4>
                    <p class="small text-muted mb-0">مشاور را انتخاب و شماره‌ها را تیک بزن.</p>
                </div>
                <div class="col-md-4">
                    <select wire:model="selectedConsultant" class="form-select">
                        <option value="">— انتخاب مشاور جذب تلفنی —</option>
                        @foreach ($consultants as $consultant)
                            <option value="{{ $consultant->id }}">{{ $consultant->name }}</option>
                        @endforeach
                    </select>
                    @error('selectedConsultant')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-2">
                    <input type="text" wire:model.live.debounce.400ms="search" class="form-control" placeholder="جستجو…">
                </div>
                <div class="col-md-2 text-md-start">
                    <button class="btn btn-primary w-100" wire:click="assignBatch">
                        اختصاص ({{ count($selectedLeadIds) }})
                    </button>
                    @error('selectedLeadIds')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>
            </div>
        </div>

        <div class="widget-content widget-content-area">
            {{-- گزارش تکراری‌ها --}}
            @if (count($duplicateInfo) > 0)
                <div class="alert alert-warning">
                    <strong>شماره‌های تکراری (اختصاص فعال داشتند و نادیده گرفته شدند):</strong>
                    <ul class="mb-0 mt-2">
                        @foreach ($duplicateInfo as $dup)
                            <li><span dir="ltr">{{ $dup['mobile'] }}</span> — {{ $dup['times'] }} بار اختصاص داده شده</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th style="width:40px"></th>
                            <th>نام</th>
                            <th>موبایل</th>
                            <th>دفعات اختصاص</th>
                            <th>مشاور فعلی</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($leads as $lead)
                        @php $hasActive = (bool) $lead->activeAssignment; @endphp
                        <tr class="{{ $hasActive ? 'table-warning' : '' }}">
                            <td>
                                <input type="checkbox" class="form-check-input"
                                       value="{{ $lead->id }}" wire:model="selectedLeadIds">
                            </td>
                            <td>{{ $lead->full_name ?: '—' }}</td>
                            <td dir="ltr">{{ $lead->mobile }}</td>
                            <td>
                                @if ($lead->assignments_count > 0)
                                    <span class="badge bg-info-subtle text-info">{{ $lead->assignments_count }} بار</span>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>
                                @if ($hasActive)
                                    <span class="badge bg-warning text-dark">
                                        {{ $lead->activeAssignment->consultant?->name ?? '—' }} (تکراری)
                                    </span>
                                @else
                                    <span class="text-muted">آزاد</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">شماره‌ای برای اختصاص نیست.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $leads->links() }}</div>
        </div>
    </div>
</div>
