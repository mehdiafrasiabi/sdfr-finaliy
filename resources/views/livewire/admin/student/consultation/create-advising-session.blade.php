<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.advising-sessions') }}">جلسات مشاوره</a></li>
                <li class="breadcrumb-item active">تاریخچه‌ی دانش‌آموز</li>
            </ol>
        </nav>
    </div>

    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
            <h4 class="mb-0">
                <span class="text-muted fw-light">تاریخچه‌ی جلسات /</span>
                {{ $student->user?->personalInformation?->name ?? $student->user?->name ?? 'دانش‌آموز' }}
            </h4>
            <a href="{{ route('admin.advising-sessions') }}" class="btn btn-outline-secondary btn-sm">بازگشت</a>
        </div>

        <div class="card sessions-card">
            <div class="card-header">
                <h5 class="mb-0">لیست جلسات مشاوره</h5>
                <small class="text-muted">نمایشِ تاریخچه‌ی جلسات. ثبتِ نتیجه‌ی هر جلسه پس از برگزاری امکان‌پذیر است.</small>
            </div>

            <div class="card-datatable table-responsive pt-0">
                <table class="table table-striped sessions-table mb-0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>عنوان</th>
                        <th>تاریخ و ساعت</th>
                        <th>محل برگزاری</th>
                        <th>وضعیت</th>
                        <th>نتیجه جلسه</th>
                        <th>پیش‌جلسه</th>
                        <th class="text-nowrap">برنامه هفتگی</th>
                    </tr>
                    </thead>

                    <tbody>
                    @forelse($sessions as $session)
                        @php
                            $sessionDateTime = \Carbon\Carbon::parse($session->activation_date)->setTimeFromTimeString($session->session_time ? $session->session_time->format('H:i:s') : '00:00:00');
                            $canAccess = \Carbon\Carbon::now()->gte($sessionDateTime);
                        @endphp
                        <tr>
                            <td class="text-nowrap">{{ $loop->iteration + $sessions->firstItem() - 1 }}</td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-semibold">{{ $session->title }}</span>
                                    @if($session->description)
                                        <small class="text-muted">{{ Str::limit($session->description, 80) }}</small>
                                    @endif
                                    @unless($session->finalized)
                                        <small class="text-warning">پیش‌نویس (ثبت نهایی نشده)</small>
                                    @endunless
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class="text-nowrap">{{ jalali($session->activation_date)->format('%d %B %Y') }}</span>
                                    @if($session->session_time)
                                        <small class="text-muted text-nowrap">{{ \Carbon\Carbon::parse($session->session_time)->format('H:i') }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($session->location_type === 'online')
                                    <span class="badge bg-label-primary me-1">آنلاین</span>
                                    @if($session->skyroom_link)
                                        <a href="{{ $session->skyroom_link }}" target="_blank"
                                           class="d-block small text-decoration-underline mt-1 session-link">لینک جلسه</a>
                                    @endif
                                @else
                                    <span class="badge bg-label-success">حضوری</span>
                                @endif
                            </td>
                            <td>
                                @if($session->status === 'inactive')
                                    <span class="badge bg-secondary">در انتظار</span>
                                @elseif($session->status === 'active')
                                    <span class="badge bg-warning">در حال برگزاری</span>
                                @else
                                    <span class="badge bg-success">برگزار شده</span>
                                @endif
                            </td>
                            <td>
                                @if($canAccess && ($session->status === 'completed' || $session->status === 'active'))
                                    @php $programComplete = $this->isProgramComplete($session->id); @endphp
                                    <select wire:change="updateResultStatus({{$session->id}},$event.target.value)"
                                            class="form-select form-select-sm">
                                        <option value="">انتخاب کنید</option>
                                        <option value="held"
                                            {{ $session->result_status === 'held' ? 'selected' : '' }}
                                            {{ !$programComplete ? 'disabled' : '' }}>
                                            برگزار شد {{ !$programComplete ? '(برنامه تکمیل نشده)' : '' }}
                                        </option>
                                        <option value="advisor_absent" {{ $session->result_status === 'advisor_absent' ? 'selected' : '' }}>
                                            توسط مشاور برگزار نشد
                                        </option>
                                        <option value="student_absent" {{ $session->result_status === 'student_absent' ? 'selected' : '' }}>
                                            دانش‌آموز غیبت داشت
                                        </option>
                                    </select>
                                @else
                                    <span class="badge bg-label-secondary">قفل</span>
                                @endif
                            </td>
                            <td>
                                @if($session->preSession)
                                    @if($session->preSession->status === 'completed')
                                        <span class="badge bg-success">ثبت شده</span>
                                    @else
                                        <span class="badge bg-warning">در انتظار</span>
                                    @endif
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.student.weekly-program', ['student' => $student->id, 'session' => $session->id]) }}"
                                   class="btn btn-sm btn-outline-primary" title="برنامه هفتگی">
                                    برنامه هفتگی
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">هیچ جلسه‌ای ثبت نشده است</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="row p-3 g-2 align-items-center">
                <div class="col-sm-12 col-md-5"></div>
                <div class="col-sm-12 col-md-7 d-flex justify-content-md-end">
                    {{ $sessions->links('layouts.admin.pagination') }}
                </div>
            </div>
        </div>
    </div>
</div>
