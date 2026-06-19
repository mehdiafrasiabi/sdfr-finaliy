<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">پیگیری‌های من</li>
            </ol>
        </nav>
    </div>

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-0">پیگیری‌های زمان‌بندی‌شده</h4>
                    <p class="small text-muted mb-0">شماره‌هایی که برای آن‌ها زمان پیگیری مجدد تعیین کرده‌ای.</p>
                </div>
                <div class="col-md-4 text-md-start">
                    <div class="form-check form-switch d-inline-block">
                        <input class="form-check-input" type="checkbox" id="dueOnly" wire:model.live="dueOnly">
                        <label class="form-check-label" for="dueOnly">فقط سررسیده‌ها</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="widget-content widget-content-area">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>موبایل</th>
                            <th>نام</th>
                            <th>پایه/رشته</th>
                            <th>زمان پیگیری</th>
                            <th>تعداد تماس</th>
                            <th>عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse ($leads as $lead)
                        @php $followAt = $lead->calls->first()?->follow_up_at; @endphp
                        <tr>
                            <td dir="ltr">{{ $lead->mobile }}</td>
                            <td>{{ $lead->full_name ?: '—' }}</td>
                            <td>{{ $lead->grade_label }} / {{ $lead->field_label }}</td>
                            <td>
                                @if ($followAt)
                                    {{ jalali($followAt)->format('%d %B %Y، ساعت %H:%M') }}
                                    @if ($followAt->lte($now))
                                        <span class="badge bg-danger ms-1">سررسید شده</span>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                            <td><span class="badge bg-{{ $lead->color }}">{{ $lead->attempts_count }}</span></td>
                            <td>
                                <button wire:click="openCallForm({{ $lead->id }})" class="btn btn-sm btn-primary">
                                    <i class="fi fi-rr-phone-call"></i> ثبت تماس مجدد
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">پیگیری‌ای ثبت نشده است.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $leads->links() }}</div>
        </div>
    </div>

    {{-- مودال ثبت تماس (مشترک) --}}
    @include('livewire.admin.phone-acquisition._call-form')
</div>
