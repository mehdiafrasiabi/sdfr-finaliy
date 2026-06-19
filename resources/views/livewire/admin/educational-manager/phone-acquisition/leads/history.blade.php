<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">تاریخچهٔ تماس‌ها</li>
            </ol>
        </nav>
    </div>

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-0">شماره‌هایی که با آن‌ها تماس گرفته شده</h4>
                </div>
                <div class="col-md-4">
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
                            <th>نام</th>
                            <th>موبایل</th>
                            <th>تعداد تماس</th>
                            <th>وضعیت</th>
                            <th>آخرین نتیجه</th>
                            <th>جزئیات</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($leads as $lead)
                        <tr>
                            <td>{{ $lead->full_name ?: '—' }}</td>
                            <td dir="ltr">{{ $lead->mobile }}</td>
                            <td><span class="badge bg-{{ $lead->color }}">{{ $lead->calls_count }}</span></td>
                            <td><span class="badge bg-light text-dark border">{{ $lead->status_label }}</span></td>
                            <td>
                                @if ($lead->last_outcome)
                                    {{ \App\Models\PhoneCall::FAIL_LABELS[$lead->last_outcome]
                                        ?? \App\Models\PhoneCall::RESULT_LABELS[$lead->last_outcome]
                                        ?? $lead->last_outcome }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.educational-manager.phone-acquisition.history.show', $lead->id) }}"
                                   class="btn btn-sm btn-outline-primary">مشاهده</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">هنوز تماسی ثبت نشده است.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $leads->links() }}</div>
        </div>
    </div>
</div>
