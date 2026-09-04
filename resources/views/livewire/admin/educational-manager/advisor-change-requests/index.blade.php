<div class="em-page">
    @include('livewire.admin.educational-manager._styles')
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item active">درخواست‌های جابجایی مشاور</li>
            </ol>
        </nav>
    </div>

    <section class="em-hero">
        <div class="em-hero-main"><span class="em-hero-icon"><i class="fi fi-rr-exchange"></i></span><div><h3>درخواست‌های جابه‌جایی مشاور</h3><p>بررسی درخواست دانش‌آموز، ظرفیت مشاور جدید و ثبت تصمیم نهایی.</p></div></div>
        <span class="badge bg-warning-subtle text-warning">{{ number_format($pendingCount) }} درخواست در انتظار</span>
    </section>

    <div class="row g-3 mb-4">
        <div class="col-lg-4">
            <div class="statbox widget box box-shadow h-100">
                <div class="widget-content widget-content-area">
                    <div class="text-muted small">درخواست‌های در انتظار</div>
                    <div class="fs-3 fw-bold mt-2">{{ $pendingCount }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="statbox widget box box-shadow h-100">
                <div class="widget-content widget-content-area">
                    <label class="form-label">جستجو</label>
                    <input type="text" wire:model.live.debounce.500ms="search" class="form-control"
                           placeholder="نام، موبایل، موضوع یا متن درخواست">
                </div>
            </div>
        </div>
    </div>

    @if($selectedRequest)
        <div class="statbox widget box box-shadow mb-4">
            <div class="widget-header d-flex align-items-center justify-content-between gap-3">
                <h5 class="mb-0">جزئیات درخواست #{{ $selectedRequest->id }}</h5>
                <button wire:click="closeDetail" class="btn btn-light btn-sm">بستن جزئیات</button>
            </div>
            <div class="widget-content widget-content-area">
                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="border rounded p-3 h-100">
                            <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-3">
                                <div>
                                    <div class="text-muted small">دانش‌آموز</div>
                                    <div class="fw-bold fs-6">{{ $selectedRequest->student?->user?->name ?? '—' }}</div>
                                    <div class="text-muted small mt-1">{{ $selectedRequest->student?->user?->mobile ?? '—' }}</div>
                                </div>
                                <span class="badge bg-{{ $selectedRequest->status_color }}">{{ $selectedRequest->status_label }}</span>
                            </div>

                            <div class="row g-3 small">
                                <div class="col-md-6">
                                    <div class="text-muted">مشاور فعلی/قبلی</div>
                                    <div class="fw-bold mt-1">{{ $selectedRequest->oldAdvisor?->name ?? '—' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-muted">مشاور جدید</div>
                                    <div class="fw-bold mt-1">{{ $selectedRequest->newAdvisor?->name ?? 'هنوز تعیین نشده' }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-muted">موضوع</div>
                                    <div class="fw-bold mt-1">{{ $selectedRequest->subject_label }}</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="text-muted">تاریخ ثبت</div>
                                    <div class="fw-bold mt-1">{{ \Morilog\Jalali\Jalalian::fromCarbon($selectedRequest->created_at)->format('Y/m/d H:i') }}</div>
                                </div>
                            </div>

                            <hr>
                            <div class="text-muted small mb-2">متن درخواست</div>
                            <p class="mb-0 lh-lg">{{ $selectedRequest->request_text }}</p>

                            @if($selectedRequest->reject_reason)
                                <div class="alert alert-danger mt-3 mb-0">
                                    <strong>علت رد:</strong>
                                    {{ $selectedRequest->reject_reason }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <div class="border rounded p-3 h-100">
                            <h6 class="fw-bold mb-3">تصمیم مدیر آموزشی</h6>

                            @if($selectedRequest->status === \App\Models\AdvisorChangeRequest::STATUS_PENDING)
                                <div class="d-grid gap-3">
                                    <button wire:click="cancelByStudent({{ $selectedRequest->id }})"
                                            wire:confirm="این درخواست به عنوان «لغو به درخواست دانش‌آموز» ثبت شود؟"
                                            class="btn btn-outline-secondary">
                                        لغو به درخواست دانش‌آموز
                                    </button>

                                    <div class="border rounded p-3">
                                        <label class="form-label">مشاور جدید</label>
                                        <select wire:model="newAdvisor.{{ $selectedRequest->id }}" class="form-select">
                                            <option value="">انتخاب مشاور...</option>
                                            @foreach($advisors as $advisor)
                                                <option value="{{ $advisor->id }}"
                                                        @disabled((int) $advisor->id === (int) $selectedRequest->old_advisor_id || $advisor->remaining <= 0)>
                                                    {{ $advisor->name }} - ظرفیت باقی‌مانده: {{ $advisor->remaining }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('newAdvisor.' . $selectedRequest->id) <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                                        <button wire:click="approve({{ $selectedRequest->id }})"
                                                wire:confirm="مشاور دانش‌آموز تغییر کند؟"
                                                class="btn btn-success w-100 mt-3">
                                            تایید تغییر مشاور
                                        </button>
                                    </div>

                                    <div class="border rounded p-3">
                                        <label class="form-label">علت رد درخواست</label>
                                        <textarea wire:model.blur="rejectReason.{{ $selectedRequest->id }}" rows="4"
                                                  class="form-control"
                                                  placeholder="علت رد را بنویسید..."></textarea>
                                        @error('rejectReason.' . $selectedRequest->id) <div class="text-danger small mt-2">{{ $message }}</div> @enderror
                                        <button wire:click="reject({{ $selectedRequest->id }})"
                                                wire:confirm="درخواست رد شود؟"
                                                class="btn btn-outline-danger w-100 mt-3">
                                            رد درخواست
                                        </button>
                                    </div>
                                </div>
                            @else
                                <div class="alert alert-light border mb-0">
                                    این درخواست بررسی شده و دیگر عملیات جدیدی ندارد.
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="statbox widget box box-shadow">
        <div class="widget-header">
            <h5 class="mb-0">لیست درخواست‌ها</h5>
        </div>
        <div class="widget-content widget-content-area">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>دانش‌آموز</th>
                            <th>موضوع</th>
                            <th>مشاور فعلی/قبلی</th>
                            <th>وضعیت</th>
                            <th>تاریخ</th>
                            <th style="width:120px">عملیات</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($requests as $request)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $request->student?->user?->name ?? '—' }}</div>
                                <div class="text-muted small">{{ $request->student?->user?->mobile ?? '—' }}</div>
                            </td>
                            <td>{{ $request->subject_label }}</td>
                            <td>{{ $request->oldAdvisor?->name ?? '—' }}</td>
                            <td>
                                <span class="badge bg-{{ $request->status_color }}">{{ $request->status_label }}</span>
                                @if($request->newAdvisor)
                                    <div class="text-muted small mt-1">جدید: {{ $request->newAdvisor->name }}</div>
                                @endif
                            </td>
                            <td class="small">{{ \Morilog\Jalali\Jalalian::fromCarbon($request->created_at)->format('Y/m/d H:i') }}</td>
                            <td>
                                <button wire:click="selectRequest({{ $request->id }})" class="btn btn-primary btn-sm">
                                    جزئیات
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">درخواستی ثبت نشده است.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">{{ $requests->links() }}</div>
        </div>
    </div>
</div>
