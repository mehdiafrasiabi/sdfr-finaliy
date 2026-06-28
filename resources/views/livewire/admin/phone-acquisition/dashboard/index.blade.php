<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">داشبورد جذب تلفنی</li>
            </ol>
        </nav>
    </div>

    @php
        $colorFa = ['primary'=>'آبی','success'=>'سبز','warning'=>'زرد','danger'=>'قرمز','secondary'=>'خاکستری'];
    @endphp

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="mb-0">تماس‌های سررسیده</h4>
                    <p class="small text-muted mb-0">شماره‌هایی که موعد تماس مجدد آن‌ها رسیده است.</p>
                </div>
                <div class="col-md-4 text-md-start">
                    <span class="badge bg-danger fs-6">{{ $dueCount }} مورد سررسیده</span>
                </div>
            </div>
        </div>

        <div class="widget-content widget-content-area">
            <div class="row g-3">
                @forelse ($leads as $lead)
                    @php $color = $lead->color; @endphp
                    <div class="col-md-4 col-sm-6">
                        <div class="card h-100 border-{{ $color }}" style="border-right-width:5px;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="mb-0" dir="ltr">{{ $lead->mobile }}</h5>
                                    <span class="badge bg-{{ $color }}">تماس {{ $lead->attempts_count }}</span>
                                </div>
                                <div class="text-muted small mb-1">
                                    <i class="fi fi-rr-user"></i> {{ $lead->full_name ?: 'بدون نام' }}
                                </div>
                                <div class="text-muted small mb-1">
                                    {{ $lead->grade_label }} / {{ $lead->field_label }}
                                </div>

                                @if ($lead->last_outcome)
                                    <div class="mb-1">
                                        <span class="badge bg-light text-dark border">
                                            آخرین نتیجه:
                                            {{ \App\Models\PhoneCall::FAIL_LABELS[$lead->last_outcome]
                                                ?? \App\Models\PhoneCall::RESULT_LABELS[$lead->last_outcome]
                                                ?? $lead->last_outcome }}
                                        </span>
                                    </div>
                                @endif

                                <div class="mb-2 small">
                                    <i class="fi fi-rr-calendar-clock text-danger"></i>
                                    موعد: {{ jalali($lead->next_call_at)->format('%d %B، %H:%M') }}
                                </div>

                                <button wire:click="openCallForm({{ $lead->id }})"
                                        class="btn btn-sm btn-{{ $color }} w-100">
                                    <i class="fi fi-rr-phone-call"></i> ثبت تماس
                                </button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-center text-muted py-4">در حال حاضر تماس سررسیده‌ای ندارید. 🎉</p>
                    </div>
                @endforelse
            </div>

            <div class="mt-3">{{ $leads->links() }}</div>
        </div>
    </div>

    {{-- مودال ثبت تماس (مشترک) --}}
    @include('livewire.admin.phone-acquisition._call-form')
</div>
