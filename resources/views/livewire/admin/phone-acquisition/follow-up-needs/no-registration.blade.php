<div class="pa-page">
    @include('livewire.admin.phone-acquisition._styles')
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.phone-acquisition.dashboard') }}">جذب تلفنی</a></li>
                <li class="breadcrumb-item active">بدون ثبت‌نام</li>
            </ol>
        </nav>
    </div>

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row align-items-center g-2 pa-toolbar">
                <div class="col-md-7">
                    <h4 class="mb-0">بدون ثبت‌نام</h4>
                    <p class="small text-muted mb-0">
                        {{ number_format($totalCount) }} لینک از روزهای قبل هنوز به ثبت‌نام نرسیده است.
                    </p>
                </div>
                <div class="col-md-5">
                    <input type="text" wire:model.live.debounce.400ms="search" class="form-control" placeholder="جستجو بر اساس نام یا موبایل…">
                </div>
            </div>
        </div>

        <div class="widget-content widget-content-area">
            <div class="row g-3">
                @forelse ($links as $link)
                    @php $lead = $link->lead; @endphp
                    @continue(! $lead)
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

                                <div class="mb-2 d-flex flex-wrap gap-1">
                                    <span class="badge bg-light text-dark border">لینک: {{ jalali($link->created_at)->format('%d %B، %H:%M') }}</span>
                                    <span class="badge bg-danger">بدون ثبت‌نام</span>
                                </div>

                                <button wire:click="promptCall({{ $lead->id }})"
                                        class="btn btn-sm btn-{{ $color }} w-100">
                                    <i class="fi fi-rr-phone-call"></i> تماس و پیگیری
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted py-4">موردی برای بدون ثبت‌نام وجود ندارد.</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-3">{{ $links->links() }}</div>
        </div>
    </div>

    @include('livewire.admin.phone-acquisition._call-form')
</div>
