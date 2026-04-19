<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">درخواست‌های تعیین وقت</li>
            </ol>
        </nav>
    </div>

    @if (session()->has('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row align-items-center">
                <div class="col-md-6"><h4 class="mb-0">درخواست‌های تعیین وقت دانش‌آموزان</h4></div>
                <div class="col-md-6">
                    <select wire:model.live="statusFilter" class="form-select">
                        <option value="pending">در انتظار بررسی</option>
                        <option value="approved">تایید شده</option>
                        <option value="all">همه</option>
                    </select>
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
                        <th>تاریخ ارسال</th>
                        <th>اسلات‌های انتخابی</th>
                        <th>وضعیت</th>
                        <th>مشاور</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse ($preferences as $p)
                        <tr>
                            <td>{{ $p->id }}</td>
                            <td>
                                <strong>{{ $p->student?->user?->name ?? '—' }}</strong>
                                <div class="small text-muted">{{ $p->student?->user?->mobile }}</div>
                            </td>
                            <td class="small">
                                {{ $p->submitted_at ? \Morilog\Jalali\Jalalian::fromDateTime($p->submitted_at)->format('Y/m/d H:i') : '—' }}
                            </td>
                            <td class="small">
                                @foreach ($p->times as $t)
                                    <div>
                                        {{ $days[$t->day_of_week] ?? '' }}:
                                        {{ substr($t->start_time, 0, 5) }} - {{ substr($t->end_time, 0, 5) }}
                                    </div>
                                @endforeach
                            </td>
                            <td>
                                @switch($p->status)
                                    @case('pending') <span class="badge bg-warning">در انتظار</span> @break
                                    @case('approved') <span class="badge bg-success">تایید شده</span> @break
                                    @case('replaced') <span class="badge bg-secondary">جایگزین شده</span> @break
                                @endswitch
                                @if ($p->change_index > 0)
                                    <span class="badge bg-info-subtle text-info">تغییر #{{ $p->change_index }}</span>
                                @endif
                            </td>
                            <td>{{ $p->assignedAdvisor?->name ?? '—' }}</td>
                            <td>
                                @if ($p->status === 'pending')
                                    <button wire:click="openAssign({{ $p->id }})"
                                            class="btn btn-sm btn-primary">پیشنهاد مشاور</button>
                                    <button wire:click="reject({{ $p->id }})"
                                            wire:confirm="از رد این درخواست مطمئن هستید؟"
                                            class="btn btn-sm btn-outline-danger">رد</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted">درخواستی یافت نشد.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $preferences->links() }}</div>
        </div>
    </div>

    {{-- Modal: matched consultants --}}
    @if ($selectedPref)
        <div class="modal fade show d-block" tabindex="-1"
             style="background:rgba(0,0,0,.5);" wire:click.self="closeAssign">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">انتخاب مشاور برای {{ $selectedPref->student?->user?->name }}</h5>
                        <button class="btn-close" wire:click="closeAssign"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3 small">
                            اسلات‌های انتخابی دانش‌آموز:
                            @foreach ($selectedPref->times as $t)
                                <span class="badge bg-light text-dark me-1">
                                    {{ $days[$t->day_of_week] ?? '' }}:
                                    {{ substr($t->start_time, 0, 5) }} - {{ substr($t->end_time, 0, 5) }}
                                </span>
                            @endforeach
                        </div>

                        @if (count($matchedConsultants) === 0)
                            <div class="alert alert-warning">هیچ مشاوری با این بازه‌ها تطابق ندارد.</div>
                        @else
                            <table class="table table-bordered align-middle">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>مشاور</th>
                                    <th class="text-center">تعداد دانش‌آموز فعال</th>
                                    <th>بازه تطابق</th>
                                    <th>دقیقه تطابق</th>
                                    <th></th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($matchedConsultants as $i => $c)
                                    <tr class="{{ $i === 0 ? 'table-success' : '' }}">
                                        <td>
                                            {{ $i + 1 }}
                                            @if ($i === 0)
                                                <div class="small text-success">بهترین تطابق</div>
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $c['name'] }}</strong>
                                            <div class="small text-muted">{{ $c['mobile'] }}</div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-primary">{{ $c['active_students'] }}</span>
                                        </td>
                                        <td class="small">
                                            @foreach ($c['matched_days'] as $md)
                                                <div>
                                                    {{ $days[$md['day']] ?? '' }}:
                                                    {{ $md['start'] }} - {{ $md['end'] }}
                                                </div>
                                            @endforeach
                                        </td>
                                        <td>{{ $c['overlap_minutes'] }} دقیقه</td>
                                        <td>
                                            <button class="btn btn-sm btn-success"
                                                    wire:click="assignConsultant({{ $c['id'] }})">
                                                انتخاب
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

