<div>
    <div class="container-xxl flex-grow-1 container-p-y">

        {{-- عنوان صفحه --}}
        <h4 class="py-3 mb-4">
            <span class="text-muted fw-light">اتاق مشاوره /</span>
            ایجاد جلسه مشاوره
        </h4>

        <div class="row g-4 mb-4">
            {{-- فرم ایجاد جلسه --}}
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-1">افزودن جلسه مشاوره جدید</h5>
                            <small class="text-muted">لطفاً اطلاعات جلسه را کامل کنید.</small>
                        </div>
                        <div>
                            <span class="badge bg-label-success rounded-pill">
                                {{ $student->user->name ?? '---' }}
                            </span>
                        </div>
                    </div>

                    <div class="card-body">
                        <form wire:submit="createSession(Object.fromEntries(new FormData($event.target)))">
                            {{-- عنوان جلسه --}}
                            <div class="mb-3">
                                <label class="form-label">عنوان جلسه</label>
                                <input
                                    type="text"
                                    name="title"
                                    wire:model="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    placeholder="مثال: جلسه مشاوره هفتگی"
                                >
                                @error('title')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- توضیحات --}}
                            <div class="mb-3">
                                <label class="form-label">توضیحات جلسه</label>
                                <textarea
                                    name="description"
                                    wire:model="description"
                                    rows="3"
                                    class="form-control @error('description') is-invalid @enderror"
                                    placeholder="توضیحات مربوط به جلسه..."
                                ></textarea>
                                @error('description')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- تاریخ و ساعت --}}
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">تاریخ برگزاری</label>
                                    <input
                                        type="date"
                                        name="activation_date"
                                        wire:model="activation_date"
                                        class="form-control @error('activation_date') is-invalid @enderror"
                                    >
                                    @error('activation_date')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">ساعت برگزاری</label>
                                    <input
                                        type="time"
                                        name="session_time"
                                        wire:model="session_time"
                                        class="form-control @error('session_time') is-invalid @enderror"
                                    >
                                    @error('session_time')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            {{-- محل برگزاری --}}
                            <div class="mb-3">
                                <label class="form-label">محل برگزاری</label>
                                <select
                                    name="location_type"
                                    wire:model.live="location_type"
                                    class="form-select @error('location_type') is-invalid @enderror"
                                >
                                    <option value="online">آنلاین</option>
                                    <option value="in_person">حضوری</option>
                                </select>
                                @error('location_type')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- لینک جلسه آنلاین --}}
                            @if($location_type === 'online')
                                <div class="mb-3">
                                    <label class="form-label">لینک جلسه آنلاین</label>
                                    <input
                                        type="url"
                                        name="skyroom_link"
                                        wire:model="skyroom_link"
                                        class="form-control @error('skyroom_link') is-invalid @enderror"
                                        placeholder="https://..."
                                    >
                                    @error('skyroom_link')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            @endif

                            {{-- دکمه‌ها --}}
                            <div class="d-flex flex-wrap gap-2 mt-4">
                                <a
                                    href="{{ route('admin.advising-sessions') }}"
                                    class="btn btn-label-danger waves-effect"
                                >
                                    بازگشت
                                </a>

                                <button
                                    type="submit"
                                    class="btn btn-success waves-effect waves-light"
                                >
                                    <span wire:loading.remove>ثبت جلسه</span>
                                    <span wire:loading>در حال ثبت...</span>
                                </button>

                                <a
                                    href="{{ route('admin.student.weekly-program', $student->id) }}"
                                    class="btn btn-primary waves-effect waves-light"
                                >
                                    آپلود برنامه تحصیلی
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- دسترسی سریع --}}
            <div class="col-lg-4">
                <div class="card mb-4">
                    <h5 class="card-header">دسترسی سریع</h5>
                    <div class="card-body">
                        <div class="row g-3">

                            <div class="col-6">
                                <a
                                    href="{{ route('admin.student.reportStudent.detail', $student->id) }}"
                                    class="d-block border rounded-3 p-3 h-100 text-center text-reset text-decoration-none hover-card-shadow"
                                >
                                    <i class="material-symbols-outlined d-block mb-2 text-primary">assessment</i>
                                    <span class="d-block small fw-medium">کارنامه وضعیت</span>
                                </a>
                            </div>

                            <div class="col-6">
                                <a
                                    href="{{ route('admin.student.studySession.detail', $student->id) }}"
                                    class="d-block border rounded-3 p-3 h-100 text-center text-reset text-decoration-none hover-card-shadow"
                                >
                                    <i class="material-symbols-outlined d-block mb-2 text-success">schedule</i>
                                    <span class="d-block small fw-medium">ساعت مطالعه</span>
                                </a>
                            </div>

                            <div class="col-6">
                                <a
                                    href="{{ route('admin.student.reportDailyActivities.detail', $student->id) }}"
                                    class="d-block border rounded-3 p-3 h-100 text-center text-reset text-decoration-none hover-card-shadow"
                                >
                                    <i class="material-symbols-outlined d-block mb-2 text-info">summarize</i>
                                    <span class="d-block small fw-medium">گزارش</span>
                                </a>
                            </div>

                            <div class="col-6">
                                <a
                                    href="#"
                                    class="d-block border rounded-3 p-3 h-100 text-center text-reset text-decoration-none hover-card-shadow"
                                >
                                    <i class="material-symbols-outlined d-block mb-2 text-warning">quiz</i>
                                    <span class="d-block small fw-medium">آزمون‌ها</span>
                                </a>
                            </div>

                            <div class="col-12">
                                <a
                                    href="#"
                                    class="d-block border rounded-3 p-3 h-100 text-center text-reset text-decoration-none hover-card-shadow"
                                >
                                    <i class="material-symbols-outlined d-block mb-2 text-teal">category</i>
                                    <span class="d-block small fw-medium">طبقه‌بندی فعال</span>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- لیست جلسات --}}
        <div class="card">
            <h5 class="card-header">لیست جلسات مشاوره</h5>

            <div class="card-datatable table-responsive pt-0">
                <table class="table table-striped mb-0">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>عنوان</th>
                        <th>تاریخ و ساعت</th>
                        <th>محل برگزاری</th>
                        <th>وضعیت</th>
                        <th>نتیجه جلسه</th>
                        <th>عملیات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($sessions as $session)
                        <tr>
                            <td>{{ $loop->iteration + $sessions->firstItem() - 1 }}</td>

                            <td>
                                <div class="d-flex flex-column">
                                    <span class="fw-medium">{{ $session->title }}</span>
                                    @if($session->description)
                                        <small class="text-muted">
                                            {{ Str::limit($session->description, 80) }}
                                        </small>
                                    @endif
                                </div>
                            </td>

                            <td>
                                <div class="d-flex flex-column">
                                    <span>{{ jalali($session->activation_date)->format('%d %B %Y') }}</span>
                                    @if($session->session_time)
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($session->session_time)->format('H:i') }}
                                        </small>
                                    @endif
                                </div>
                            </td>

                            <td>
                                @if($session->location_type === 'online')
                                    <span class="badge bg-label-primary me-1">آنلاین</span>
                                    @if($session->skyroom_link)
                                        <a
                                            href="{{ $session->skyroom_link }}"
                                            target="_blank"
                                            class="d-block small text-primary text-decoration-underline mt-1"
                                        >
                                            لینک جلسه
                                        </a>
                                    @endif
                                @else
                                    <span class="badge bg-label-success">حضوری</span>
                                @endif
                            </td>

                            <td>
                                @if($session->status === 'inactive')
                                    <span class="badge bg-label-secondary">مانده به برگزاری</span>
                                @elseif($session->status === 'active')
                                    <span class="badge bg-label-warning">در حال برگزاری</span>
                                @else
                                    <span class="badge bg-label-success">برگزار شده</span>
                                @endif
                            </td>

                            <td>
                                @if($session->status === 'completed' || $session->status === 'active')
                                    <select
                                        wire:change="updateResultStatus({{ $session->id }}, $event.target.value)"
                                        class="form-select form-select-sm"
                                    >
                                        <option value="">انتخاب کنید</option>
                                        <option value="held" {{ $session->result_status === 'held' ? 'selected' : '' }}>
                                            برگزار شد
                                        </option>
                                        <option
                                            value="advisor_absent" {{ $session->result_status === 'advisor_absent' ? 'selected' : '' }}>
                                            توسط مشاور برگزار نشد
                                        </option>
                                        <option
                                            value="student_absent" {{ $session->result_status === 'student_absent' ? 'selected' : '' }}>
                                            دانش‌آموز غیبت داشت
                                        </option>
                                    </select>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>

                            <td>
                                <div class="d-flex align-items-center gap-1">
                                    <a
                                        href="{{ route('admin.student.weekly-program', ['student' => $student->id, 'session' => $session->id]) }}"
                                        class="btn btn-sm btn-icon btn-text-primary rounded-pill waves-effect"
                                        title="برنامه تحصیلی"
                                    >
                                        <i class="material-symbols-outlined">calendar_month</i>
                                    </a>

                                    <button
                                        wire:confirm="آیا مطمئن هستید؟"
                                        wire:click="deleteSession({{ $session->id }})"
                                        class="btn btn-sm btn-icon btn-text-danger rounded-pill waves-effect"
                                        title="حذف جلسه"
                                    >
                                        <i class="material-symbols-outlined">delete</i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="text-muted">
                                    <i class="material-symbols-outlined mb-2" style="font-size: 32px;">event_busy</i>
                                    <div>هیچ جلسه‌ای ثبت نشده است</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            {{-- paginate --}}
            <div class="row p-3">
                <div class="col-sm-12 col-md-5">
                </div>
                <div class="col-sm-12 col-md-7 d-flex justify-content-md-end">
                    {{ $sessions->links('layouts.admin.pagination') }}
                </div>
            </div>
        </div>
    </div>
</div>
