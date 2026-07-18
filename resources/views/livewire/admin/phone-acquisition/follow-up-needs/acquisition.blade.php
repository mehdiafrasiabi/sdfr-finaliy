<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.phone-acquisition.dashboard') }}">جذب تلفنی</a></li>
                <li class="breadcrumb-item active">نیاز پیگیری مجدد جذب تلفنی</li>
            </ol>
        </nav>
    </div>

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row align-items-center g-2">
                <div class="col-md-7">
                    <h4 class="mb-0">نیاز پیگیری مجدد جذب تلفنی</h4>
                    <p class="small text-muted mb-0">
                        {{ number_format($totalCount) }} شماره در این دسته هستند.
                        {{ number_format($dueCount) }} مورد سررسید شده‌اند.
                    </p>
                </div>
                <div class="col-md-5">
                    <input type="text" wire:model.live.debounce.400ms="search" class="form-control" placeholder="جستجو نام یا موبایل…">
                </div>
            </div>
            <div class="d-flex flex-wrap gap-2 mt-3">
                <label class="form-check form-switch mb-0">
                    <input class="form-check-input" type="checkbox" wire:model.live="dueOnly">
                    <span class="form-check-label">فقط سررسیده‌ها</span>
                </label>
                <span class="badge bg-primary">آبی: {{ number_format($colorCounts['primary'] ?? 0) }}</span>
                <span class="badge bg-success">سبز: {{ number_format($colorCounts['success'] ?? 0) }}</span>
                <span class="badge bg-warning text-dark">زرد: {{ number_format($colorCounts['warning'] ?? 0) }}</span>
                <span class="badge bg-danger">قرمز: {{ number_format($colorCounts['danger'] ?? 0) }}</span>
                <span class="badge bg-secondary">خاکستری: {{ number_format($colorCounts['secondary'] ?? 0) }}</span>
            </div>
        </div>

        <div class="widget-content widget-content-area">
            <div class="row g-3">
                @forelse ($leads as $lead)
                    @php $color = $lead->color; @endphp
                    <div class="col-md-4 col-sm-6">
                        <div class="card h-100 border-{{ $color }}" style="border-right-width:5px;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
                                    <h5 class="mb-0" dir="ltr">{{ $lead->mobile }}</h5>
                                    <span class="badge bg-{{ $color }}">تماس {{ $lead->attempts_count }}</span>
                                </div>
                                <div class="text-muted small mb-1">
                                    <i class="fi fi-rr-user"></i> {{ $lead->full_name ?: 'بدون نام' }}
                                </div>
                                <div class="text-muted small mb-1">
                                    {{ $lead->grade_label }} / {{ $lead->field_label }}
                                </div>
                                <div class="text-muted small mb-2">
                                    {{ $lead->state?->name ?? '—' }}{{ $lead->city ? '، ' . $lead->city->name : '' }}
                                </div>
                                @if ($lead->next_call_at)
                                    <div class="mb-2 small">
                                        <i class="fi fi-rr-calendar-clock text-danger"></i>
                                        موعد: {{ jalali($lead->next_call_at)->format('%d %B، %H:%M') }}
                                    </div>
                                @endif
                                @if ($lead->calls->first())
                                    <div class="mb-2">
                                        <span class="badge bg-light text-dark border">
                                            آخرین نتیجه: نیاز به پیگیری مجدد جذب تلفنی
                                        </span>
                                    </div>
                                @endif

                                <button wire:click="openCallForm({{ $lead->id }})"
                                        class="btn btn-sm btn-{{ $color }} w-100">
                                    <i class="fi fi-rr-phone-call"></i> ثبت تماس مجدد
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted py-4">موردی برای پیگیری مجدد جذب تلفنی نیست.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-3">{{ $leads->links() }}</div>
        </div>
    </div>

    @include('livewire.admin.phone-acquisition._call-form')
</div>
