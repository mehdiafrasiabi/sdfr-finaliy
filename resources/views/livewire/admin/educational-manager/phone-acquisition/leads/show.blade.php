<div class="em-page">
    @include('livewire.admin.educational-manager._styles')
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.educational-manager.phone-acquisition.history') }}">تاریخچهٔ تماس‌ها</a>
                </li>
                <li class="breadcrumb-item active">جزئیات شماره</li>
            </ol>
        </nav>
    </div>

    @php
        $colorFa = ['primary'=>'آبی','success'=>'سبز','warning'=>'زرد','danger'=>'قرمز','secondary'=>'خاکستری'];
    @endphp

    <section class="em-hero">
        <div class="em-hero-main"><span class="em-hero-icon"><i class="fi fi-rr-phone-call"></i></span><div><h3>{{ $lead->full_name ?: 'جزئیات شماره' }}</h3><p dir="ltr">{{ $lead->mobile }}</p></div></div>
        <span class="badge bg-{{ $lead->color }}">{{ $lead->status_label }}</span>
    </section>

    <div class="row g-3">
        {{-- اطلاعات شماره --}}
        <div class="col-md-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3" dir="ltr">{{ $lead->mobile }}</h5>
                    <ul class="list-unstyled small mb-0">
                        <li class="mb-2"><strong>نام:</strong> {{ $lead->full_name ?: '—' }}</li>
                        <li class="mb-2"><strong>پایه/رشته:</strong> {{ $lead->grade_label }} / {{ $lead->field_label }}</li>
                        <li class="mb-2"><strong>استان/شهر:</strong>
                            {{ $lead->state?->name ?? '—' }}{{ $lead->city ? '، ' . $lead->city->name : '' }}</li>
                        <li class="mb-2"><strong>وضعیت:</strong>
                            <span class="badge bg-{{ $lead->color }}">{{ $lead->status_label }}</span></li>
                        <li class="mb-2"><strong>تعداد تماس:</strong>
                            <span class="badge bg-{{ $lead->color }}">{{ $lead->attempts_count }} ({{ $colorFa[$lead->color] ?? '' }})</span></li>
                        <li class="mb-2"><strong>ثبت‌کننده:</strong> {{ $lead->creator?->name ?? '—' }}</li>
                    </ul>
                </div>
            </div>

            {{-- تاریخچهٔ اختصاص‌ها --}}
            <div class="card border-0 shadow-sm mt-3">
                <div class="card-body">
                    <h6 class="mb-3">تاریخچهٔ اختصاص</h6>
                    @forelse ($lead->assignments as $assignment)
                        <div class="border-bottom pb-2 mb-2 small">
                            <div><strong>مشاور:</strong> {{ $assignment->consultant?->name ?? '—' }}</div>
                            <div><strong>توسط:</strong> {{ $assignment->assignedBy?->name ?? '—' }}</div>
                            <div class="text-muted">
                                {{ jalali($assignment->assigned_at)->format('%d %B %Y') }}
                                <span class="badge bg-{{ $assignment->status === 'active' ? 'success' : 'secondary' }}">
                                    {{ $assignment->status === 'active' ? 'فعال' : 'انجام‌شده' }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">اختصاصی ثبت نشده است.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- تاریخچهٔ تماس‌ها --}}
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="mb-3">تاریخچهٔ تماس‌ها ({{ $calls->count() }})</h5>

                    @forelse ($calls as $call)
                        @php $callColor = \App\Models\PhoneLead::COLOR_BY_ATTEMPT[min($call->attempt_number, 5)] ?? 'secondary'; @endphp
                        <div class="card border-{{ $callColor }} mb-3" style="border-right-width:5px;">
                            <div class="card-body py-2">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-{{ $callColor }}">تماس #{{ $call->attempt_number }}</span>
                                    <small class="text-muted">
                                        {{ jalali($call->called_at)->format('%d %B %Y، ساعت %H:%M') }}
                                        — {{ $call->admin?->name ?? '—' }}
                                    </small>
                                </div>

                                @if ($call->connected)
                                    <div class="row small">
                                        <div class="col-md-6 mb-1"><strong>نتیجه:</strong>
                                            <span class="badge bg-success-subtle text-success">{{ $call->result_label }}</span></div>
                                        <div class="col-md-6 mb-1"><strong>صحبت با:</strong> {{ $call->spoke_with_label }}</div>
                                        @if ($call->result === \App\Models\PhoneCall::RESULT_FOLLOW_UP && $call->follow_up_at)
                                            <div class="col-md-6 mb-1"><strong>زمان پیگیری:</strong>
                                                {{ jalali($call->follow_up_at)->format('%d %B %Y، %H:%M') }}</div>
                                        @endif
                                        @if ($call->result === \App\Models\PhoneCall::RESULT_NO_INTEREST && $call->disinterest_reason)
                                            <div class="col-12 mb-1 text-danger"><strong>علت عدم تمایل:</strong> {{ $call->disinterest_reason }}</div>
                                        @endif
                                        @if ($call->summary)
                                            <div class="col-12 mt-1"><strong>خلاصهٔ گفتگو:</strong> {{ $call->summary }}</div>
                                        @endif
                                    </div>
                                @else
                                    <div class="small">
                                        <span class="badge bg-danger-subtle text-danger">ناموفق</span>
                                        <strong class="ms-2">علت:</strong> {{ $call->fail_label }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">تماسی ثبت نشده است.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
