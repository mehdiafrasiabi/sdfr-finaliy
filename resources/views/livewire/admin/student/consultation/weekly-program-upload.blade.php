<div class="student-ui student-ui-auto-collapse">
    @include('livewire.admin.student._styles')
    {{-- ====== GLOBAL LOADING INDICATORS ====== --}}
    @push('link')
        <style>
            @keyframes wpu-progress { 0% { transform: translateX(-100%); } 50% { transform: translateX(0); } 100% { transform: translateX(100%); } }
            html.modal-open-custom,
            body.modal-open-custom {
                overflow: hidden !important;
                overscroll-behavior: none !important;
            }
            body.modal-open-custom {
                position: fixed !important;
                inset: 0 0 auto 0 !important;
                width: 100% !important;
            }
        </style>
    @endpush
    {{-- نوار پیشرفت بالای صفحه (برای هر درخواست) --}}
    <div wire:loading.block
         class="position-fixed top-0 start-0 w-100 overflow-hidden" style="z-index:2000;height:3px;pointer-events:none;">
        <div class="h-100 bg-primary" style="width:50%;animation:wpu-progress 1.1s ease-in-out infinite;"></div>
    </div>
    {{-- نشانگر شناور «در حال پردازش» --}}
    <div wire:loading.flex wire:target="savePart,deletePart,editPart,openPartModal,selectPartMode,finalSave,copyPrevWeekPartsToProgram,pastePartsToDays,cutPartsToDay,openClassificationModal,openClassScheduleModal,openPrevProgramModal,openPrevReportModal,openExamAssignmentModal,selectExamAssignmentExam,assignExamFromWeeklyProgram"
         class="position-fixed align-items-center gap-2 px-3 py-2 rounded-pill shadow bg-body border"
         style="z-index:2001;bottom:18px;left:18px;pointer-events:none;">
        <span class="spinner-border spinner-border-sm text-primary"></span>
        <span class="small fw-semibold">در حال پردازش...</span>
    </div>

    {{-- ====== BODY SCROLL LOCK WHEN MODAL OPEN ====== --}}
    @if($showPartModal || $showPrevProgramModal || $showPrevReportModal || $showClassificationModal ||
        $showClassScheduleModal || $showNoScheduleModal || $showRestDayConfirmModal || $showExamDayConfirmModal ||
        $showExamPartModal || $showExamDaySelectModal || $showExamAssignmentModal || $showDistributeHomeworkModal || $showDistributeExamModal ||
        $showDistributeQaModal || $showBulkDeleteConfirmModal || $showZeroTimeWarningModal)
        @push('link')
            <style>html, body { overflow: hidden !important; overscroll-behavior: none !important; }</style>
        @endpush
    @endif

    {{-- ====== HERO ====== --}}
    <div class="card mb-4 border-0 text-white rounded-4 overflow-hidden"
         style="background: linear-gradient(135deg,#0ea5e9 0%,#2563eb 50%,#1d4ed8 100%);">
        <div class="card-body py-4">
            <div class="row gy-3 align-items-center">
                <div class="col-md-5 d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-10 rounded-4 p-3 border border-white border-opacity-10">
                        <span class="fs-4 fw-bold text-white">SDFR</span>
                    </div>
                    <div>
                        <h1 class="h4 mb-1 fw-bold">برنامه درسی هفتگی</h1>
                        <small class="text-white-50">به سبک SDFR</small>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="bg-white bg-opacity-10 border border-white border-opacity-25 rounded-4 px-4 py-3">
                        <div class="row g-3 text-center">
                            <div class="col-6 col-md-3">
                                <div class="text-white-50 small mb-1">نام دانش‌آموز</div>
                                <div class="fw-bold text-white small">{{ $student->user->name ?? '---' }}</div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-white-50 small mb-1">مشاور</div>
                                <div class="fw-bold text-white small">{{ $advisorName }}</div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="text-white-50 small mb-1">تاریخ شروع</div>
                                <div class="fw-bold text-white small">
                                    {{ $start_date ? jdate($start_date)->format('Y/m/d') : '---' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ====== QUICK ACCESS ====== --}}
    <div class="card mb-4 border rounded-4 shadow-sm" x-data="{
        iframeModal: false, iframeUrl: '', iframeTitle: '', iframeColor: '#2563eb',
        openIframe(url, title, color) { this.iframeUrl=url; this.iframeTitle=title; this.iframeColor=color||'#2563eb'; this.iframeModal=true; }
    }">
        {{-- Iframe Modal --}}
        <template x-teleport="body">
            <div x-show="iframeModal" x-cloak
                 class="position-fixed top-0 start-0 w-100 h-100"
                 style="z-index:1080;background:rgba(2,6,23,.65);backdrop-filter:blur(4px);"
                 @keydown.escape.window="iframeModal=false">
                <div class="d-flex align-items-center justify-content-center w-100 h-100 p-3">
                    <div class="rounded-4 overflow-hidden shadow d-flex flex-column bg-body"
                         style="width:100%;max-width:1200px;height:90vh;">
                        <div class="d-flex align-items-center justify-content-between px-4 py-3 text-white"
                             :style="'background:'+iframeColor">
                            <h5 class="mb-0 fw-bold d-flex align-items-center gap-2">
                                <i class="material-symbols-outlined">open_in_new</i>
                                <span x-text="iframeTitle"></span>
                            </h5>
                            <div class="d-flex gap-2 align-items-center">
                                <a :href="iframeUrl" target="_blank" class="btn btn-sm btn-light d-flex align-items-center gap-1">
                                    <i class="material-symbols-outlined" style="font-size:16px;">open_in_new</i>
                                    تب جدید
                                </a>
                                <button @click="iframeModal=false" class="btn-close btn-close-white"></button>
                            </div>
                        </div>
                        <div class="flex-fill overflow-hidden">
                            <iframe :src="iframeUrl" class="w-100 h-100 border-0" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div class="card-header d-flex align-items-center justify-content-between rounded-top-4">
            <div class="d-flex align-items-center gap-2">
                <i class="material-symbols-outlined text-primary">bolt</i>
                <h5 class="mb-0">دسترسی سریع</h5>
            </div>
            <small class="text-muted">میانبرهای پرکاربرد</small>
        </div>

        <div class="card-body">
            <div class="row g-3 mb-3">
                <div class="col-6 col-md-2">
                    <a href="#"
                       @click.prevent="openIframe('{{ route('admin.student.studySession.detail', $student->user_id) }}','ساعت مطالعه','linear-gradient(135deg,#059669,#10b981)')"
                       class="d-block p-3 text-center text-reset text-decoration-none border rounded-4 h-100 bg-body shadow-sm">
                        <i class="material-symbols-outlined d-block mb-2 text-success" style="font-size:28px;">schedule</i>
                        <span class="small fw-semibold d-block">ساعت مطالعه</span>
                        <span class="small text-muted">جلسات مطالعه</span>
                    </a>
                </div>
                <div class="col-6 col-md-2">
                    <a href="#"
                       @click.prevent="openIframe('{{ route('admin.student.reportDailyActivities.detail', $student->user_id) }}','گزارش فعالیت روزانه','linear-gradient(135deg,#0891b2,#06b6d4)')"
                       class="d-block p-3 text-center text-reset text-decoration-none border rounded-4 h-100 bg-body shadow-sm">
                        <i class="material-symbols-outlined d-block mb-2 text-info" style="font-size:28px;">summarize</i>
                        <span class="small fw-semibold d-block">گزارش</span>
                        <span class="small text-muted">فعالیت روزانه</span>
                    </a>
                </div>
                <div class="col-6 col-md-2">
                    <a href="#"
                       @click.prevent="openIframe('{{ route('admin.typed-exams.index') }}','آزمون‌ها','linear-gradient(135deg,#d97706,#f59e0b)')"
                       class="d-block p-3 text-center text-reset text-decoration-none border rounded-4 h-100 bg-body shadow-sm">
                        <i class="material-symbols-outlined d-block mb-2 text-warning" style="font-size:28px;">quiz</i>
                        <span class="small fw-semibold d-block">آزمون‌ها</span>
                        <span class="small text-muted">مرور آزمون</span>
                    </a>
                </div>
                <div class="col-6 col-md-2">
                    <a href="#" wire:click.prevent="openClassificationModal"
                       class="d-block p-3 text-center text-reset text-decoration-none border rounded-4 h-100 bg-body shadow-sm">
                        <i class="material-symbols-outlined d-block mb-2 text-secondary" style="font-size:28px;">category</i>
                        <span class="small fw-semibold d-block">طبقه‌بندی</span>
                        <span class="small text-muted">دسته‌بندی موارد</span>
                    </a>
                </div>
                <div class="col-6 col-md-2">
                    <a href="#" wire:click.prevent="openClassScheduleModal"
                       class="d-block p-3 text-center text-reset text-decoration-none border rounded-4 h-100 bg-body shadow-sm">
                        <i class="material-symbols-outlined d-block mb-2 text-danger" style="font-size:28px;">menu_book</i>
                        <span class="small fw-semibold d-block">برنامه کلاسی</span>
                        <span class="small text-muted">مشاهده برنامه</span>
                    </a>
                </div>
            </div>

            <hr class="my-3">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="material-symbols-outlined text-primary">quiz</i>
                <h6 class="mb-0 fw-bold">آزمون‌ها</h6>
                <small class="text-muted">اختصاص مستقیم از همین صفحه</small>
            </div>
            <div class="row g-3">
                <div class="col-6">
                    <button type="button" wire:click="openExamAssignmentModal('essay')"
                            class="w-100 d-flex flex-column align-items-center justify-content-center p-4 text-center border border-warning border-opacity-25 rounded-4 h-100 bg-warning bg-opacity-10 text-reset"
                            style="min-height:100px;">
                        <i class="material-symbols-outlined d-block mb-2 text-warning" style="font-size:32px;">description</i>
                        <span class="fw-semibold d-block">آزمون تشریحی</span>
                        <span class="small text-muted mt-1">انتخاب آزمون و زمان‌بندی</span>
                    </button>
                </div>
                <div class="col-6">
                    <button type="button" wire:click="openExamAssignmentModal('typed')"
                            class="w-100 d-flex flex-column align-items-center justify-content-center p-4 text-center border border-primary border-opacity-25 rounded-4 h-100 bg-primary bg-opacity-10 text-reset"
                            style="min-height:100px;">
                        <i class="material-symbols-outlined d-block mb-2 text-primary" style="font-size:32px;">check_box</i>
                        <span class="fw-semibold d-block">آزمون تستی</span>
                        <span class="small text-muted mt-1">اختصاص با تاریخ جلالی</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4 border rounded-4 shadow-sm">
        <div class="card-header d-flex align-items-center justify-content-between rounded-top-4">
            <div class="d-flex align-items-center gap-2">
                <i class="material-symbols-outlined text-primary">psychology</i>
                <h5 class="mb-0">خلاصه ارزیابی‌ها</h5>
            </div>
            <small class="text-muted">{{ $assessmentSummary ? 'برگرفته از ارزیابی‌های دانش‌آموز' : 'بدون داده' }}</small>
        </div>
        <div class="card-body">
            @if($assessmentSummary)
                <div class="row g-3">
                    @if(!empty($assessmentSummary['vark']['profile']))
                        <div class="col-12">
                            <div class="border rounded-4 p-3 h-100 bg-body">
                                <div class="d-flex align-items-center justify-content-between gap-2 mb-3">
                                    <h6 class="mb-0 fw-bold">سبک یادگیری (VARK)</h6>
                                    <span class="badge bg-primary-subtle text-primary rounded-pill">{{ $assessmentSummary['vark']['profile'] }}</span>
                                </div>
                                <div class="row g-3">
                                    @foreach(($assessmentSummary['vark']['modalities'] ?? []) as $modality)
                                        <div class="col-md-6 col-xl-3">
                                            <div class="border rounded-3 p-3 h-100 {{ !empty($modality['dominant']) ? 'border-primary border-opacity-50 bg-primary bg-opacity-10' : '' }}">
                                                <div class="d-flex align-items-center justify-content-between small mb-1">
                                                    <span class="fw-semibold">{{ $modality['title'] }}</span>
                                                    <span>{{ $modality['percent'] }}%</span>
                                                </div>
                                                <div class="progress mb-2" style="height:7px;">
                                                    <div class="progress-bar bg-primary" role="progressbar" style="width: {{ $modality['percent'] }}%"></div>
                                                </div>
                                                @if(!empty($modality['tip']))
                                                    <p class="small text-muted mb-0">{{ $modality['tip'] }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @foreach(($assessmentSummary['custom'] ?? []) as $testName => $facets)
                        <div class="col-lg-6">
                            <div class="border rounded-4 p-3 h-100 bg-body">
                                <h6 class="mb-3 fw-bold">{{ $testName }}</h6>
                                @foreach($facets as $facet)
                                    @php
                                        $barClass = match($facet['level'] ?? 'medium') {
                                            'low' => 'bg-success',
                                            'high' => 'bg-danger',
                                            default => 'bg-warning',
                                        };
                                    @endphp
                                    <div class="mb-3">
                                        <div class="d-flex align-items-center justify-content-between small mb-1">
                                            <span class="fw-semibold">{{ $facet['label'] }}</span>
                                            <span>{{ $facet['percent'] }}%</span>
                                        </div>
                                        <div class="progress" style="height:7px;">
                                            <div class="progress-bar {{ $barClass }}" role="progressbar" style="width: {{ $facet['percent'] }}%"></div>
                                        </div>
                                        @if(!empty($facet['text']) && $facet['text'] !== '—')
                                            <p class="small text-muted mb-0 mt-2">{{ $facet['text'] }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    @if(!empty($assessmentSummary['flags']))
                        <div class="col-12">
                            <div class="border rounded-4 p-3 bg-body-tertiary">
                                <h6 class="mb-3 fw-bold">هشدارهای مهم</h6>
                                <div class="row g-2">
                                    @foreach($assessmentSummary['flags'] as $flag)
                                        @php
                                            $flagClass = ($flag['severity'] ?? 'info') === 'critical'
                                                ? 'border-danger bg-danger bg-opacity-10'
                                                : (($flag['severity'] ?? 'info') === 'warning'
                                                    ? 'border-warning bg-warning bg-opacity-10'
                                                    : 'border-info bg-info bg-opacity-10');
                                        @endphp
                                        <div class="col-12">
                                            <div class="border rounded-3 p-3 {{ $flagClass }}">
                                                <div class="fw-semibold mb-1">{{ $flag['title'] ?? '' }}</div>
                                                @if(!empty($flag['text']))
                                                    <p class="small mb-0 text-muted">{{ $flag['text'] }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="material-symbols-outlined d-block mb-2" style="font-size:34px;">psychology_alt</i>
                    هنوز ارزیابیِ تکمیل‌شده‌ای برای این دانش‌آموز ثبت نشده است.
                </div>
            @endif
        </div>
    </div>

    {{-- ====== PRE-SESSIONS ====== --}}
    @if($preSessions->count() > 0)
        @foreach($preSessions as $preSession)
            <div class="card mb-4 border rounded-4 shadow-sm">
                <div class="card-header d-flex justify-content-between align-items-center rounded-top-4">
                    <div>
                        <h5 class="mb-1">پیش‌جلسه: {{ $preSession->title }}</h5>
                        <small class="text-muted">اطلاعات ثبت شده توسط دانش‌آموز</small>
                    </div>
                    <span class="badge rounded-pill px-3 py-2 {{ $preSession->status === 'completed' ? 'bg-success' : 'bg-warning text-white' }}">
                        {{ $preSession->status_label }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row g-4">

                        {{-- امتحانات --}}
                        <div class="col-lg-6">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="fw-bold text-primary mb-3 d-flex align-items-center gap-2">
                                    <i class="material-symbols-outlined">quiz</i>
                                    امتحانات
                                    <span class="badge bg-primary-subtle text-primary rounded-pill">{{ $preSession->exams->count() }}</span>
                                </h6>
                                @if($preSession->exams->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm align-middle mb-0">
                                            <thead class="table-light">
                                            <tr><th>درس</th><th>پارت</th><th>زمان</th><th>تاریخ</th><th class="text-center">وضعیت</th></tr>
                                            </thead>
                                            <tbody>
                                            @foreach($preSession->exams as $examIdx => $exam)
                                                <tr>
                                                    <td class="fw-semibold small">{{ $exam->subject }}</td>
                                                    <td class="small">{{ $exam->part_count }}</td>
                                                    <td class="small">{{ $exam->time_per_part }} دقیقه</td>
                                                    <td class="small">{{ jalali($exam->exam_date)->format('%d %B') }}</td>
                                                    <td class="text-center">
                                                        @if($this->isExamRegistered($examIdx))
                                                            <span class="badge bg-success-subtle text-success border border-success border-opacity-25 small">
                                                                <i class="material-symbols-outlined" style="font-size:12px;">check_circle</i> ثبت شده
                                                            </span>
                                                            <button wire:click="revertExamParts({{ $examIdx }})"
                                                                    wire:confirm="آیا مطمئنید؟"
                                                                    class="btn btn-sm btn-outline-danger mt-1 d-flex align-items-center gap-1">
                                                                <i class="material-symbols-outlined" style="font-size:13px;">undo</i> بازگرداندن
                                                            </button>
                                                        @else
                                                            <button wire:click="openExamDaySelect({{ $examIdx }})"
                                                                    class="btn btn-sm btn-outline-primary d-flex align-items-center gap-1">
                                                                <i class="material-symbols-outlined" style="font-size:13px;">event</i> انتخاب روز
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted small mb-0">هیچ امتحانی ثبت نشده</p>
                                @endif
                            </div>
                        </div>

                        {{-- پرسش و پاسخ --}}
                        <div class="col-lg-6">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="fw-bold text-info mb-3 d-flex align-items-center gap-2">
                                    <i class="material-symbols-outlined">forum</i>
                                    پرسش و پاسخ کلاسی
                                    <span class="badge bg-info-subtle text-info rounded-pill">{{ $preSession->qas->count() }}</span>
                                </h6>
                                @if($preSession->qas->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm align-middle mb-0">
                                            <thead class="table-light">
                                            <tr><th>درس</th><th>پارت</th><th>زمان</th><th>تاریخ</th><th class="text-center">وضعیت</th></tr>
                                            </thead>
                                            <tbody>
                                            @foreach($preSession->qas as $qaIdx => $qa)
                                                <tr>
                                                    <td class="fw-semibold small">{{ $qa->subject }}</td>
                                                    <td class="small">{{ $qa->part_count }}</td>
                                                    <td class="small">{{ $qa->time_per_part }} دقیقه</td>
                                                    <td class="small">{{ jalali($qa->qa_date)->format('%d %B') }}</td>
                                                    <td class="text-center">
                                                        @if($this->isQaRegistered($qaIdx))
                                                            <span class="badge bg-success-subtle text-success border border-success border-opacity-25 small">
                                                                <i class="material-symbols-outlined" style="font-size:12px;">check_circle</i> ثبت شده
                                                            </span>
                                                            <button wire:click="revertQaParts({{ $qaIdx }})" wire:confirm="آیا مطمئنید؟"
                                                                    class="btn btn-sm btn-outline-danger mt-1">
                                                                <i class="material-symbols-outlined" style="font-size:13px;">undo</i> بازگرداندن
                                                            </button>
                                                        @else
                                                            <button wire:click="previewQaDistribution"
                                                                    class="btn btn-sm btn-outline-info d-flex align-items-center gap-1">
                                                                <i class="material-symbols-outlined" style="font-size:13px;">auto_fix_high</i> ثبت
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted small mb-0">هیچ پرسش و پاسخی ثبت نشده</p>
                                @endif
                            </div>
                        </div>

                        {{-- تکالیف --}}
                        <div class="col-lg-6">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="fw-bold text-warning mb-3 d-flex align-items-center gap-2">
                                    <i class="material-symbols-outlined">assignment</i>
                                    تکالیف
                                    <span class="badge bg-warning-subtle text-warning rounded-pill">{{ $preSession->assignments->count() }}</span>
                                </h6>
                                @if($preSession->assignments->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm align-middle mb-0">
                                            <thead class="table-light">
                                            <tr><th>درس</th><th>پارت</th><th>زمان</th><th>مهلت</th><th class="text-center">وضعیت</th></tr>
                                            </thead>
                                            <tbody>
                                            @foreach($preSession->assignments as $aIdx => $assignment)
                                                <tr>
                                                    <td class="fw-semibold small">{{ $assignment->subject }}</td>
                                                    <td class="small">{{ $assignment->part_count }}</td>
                                                    <td class="small">{{ $assignment->time_per_part }} دقیقه</td>
                                                    <td class="small">{{ jalali($assignment->due_date)->format('%d %B') }}</td>
                                                    <td class="text-center">
                                                        @if($this->isAssignmentRegistered($aIdx))
                                                            <span class="badge bg-success-subtle text-success border border-success border-opacity-25 small">
                                                                <i class="material-symbols-outlined" style="font-size:12px;">check_circle</i> ثبت شده
                                                            </span>
                                                            <button wire:click="revertAssignmentParts({{ $aIdx }})" wire:confirm="آیا مطمئنید؟"
                                                                    class="btn btn-sm btn-outline-danger mt-1">
                                                                <i class="material-symbols-outlined" style="font-size:13px;">undo</i> بازگرداندن
                                                            </button>
                                                        @else
                                                            <button wire:click="previewHomeworkDistribution"
                                                                    class="btn btn-sm btn-outline-warning d-flex align-items-center gap-1">
                                                                <i class="material-symbols-outlined" style="font-size:13px;">auto_fix_high</i> ثبت
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @else
                                    <p class="text-muted small mb-0">هیچ تکلیفی ثبت نشده</p>
                                @endif
                            </div>
                        </div>

                        {{-- متفرقه --}}
                        <div class="col-lg-6">
                            <div class="border rounded-3 p-3 h-100">
                                <h6 class="fw-bold text-success mb-3 d-flex align-items-center gap-2">
                                    <i class="material-symbols-outlined">notes</i> متفرقه
                                </h6>
                                @if($preSession->miscellaneous)
                                    <div class="bg-body-tertiary rounded-3 p-3 border">
                                        <p class="mb-0 small" style="white-space:pre-line;">{{ $preSession->miscellaneous->description }}</p>
                                    </div>
                                @else
                                    <p class="text-muted small mb-0">متفرقه‌ای ثبت نشده</p>
                                @endif
                            </div>
                        </div>

                        {{-- پارت درخواستی --}}
                        @if($preSession->requestedParts->count() > 0)
                            <div class="col-12">
                                <div class="border rounded-3 p-3">
                                    <h6 class="fw-bold mb-3 d-flex align-items-center gap-2" style="color:#ea580c;">
                                        <i class="material-symbols-outlined">playlist_add</i>
                                        پارت درخواستی
                                        <span class="badge rounded-pill px-2" style="background:rgba(234,88,12,.12);color:#ea580c;">
                                            {{ $preSession->requestedParts->count() }}
                                        </span>
                                    </h6>
                                    <div class="table-responsive">
                                        <table class="table table-sm align-middle mb-0">
                                            <thead class="table-light">
                                            <tr><th>درس</th><th>پارت</th><th>زمان</th><th>توضیحات</th></tr>
                                            </thead>
                                            <tbody>
                                            @foreach($preSession->requestedParts as $rp)
                                                <tr>
                                                    <td class="fw-semibold small">{{ $rp->subject }}</td>
                                                    <td class="small">{{ $rp->part_count }}</td>
                                                    <td class="small">{{ $rp->time_per_part }} دقیقه</td>
                                                    <td class="text-muted small">{{ $rp->description ?? '—' }}</td>
                                                </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        @endforeach
    @endif

    {{-- ====== PREVIOUS WEEK PREVIEW (TIMETABLE) ====== --}}
    @if(!empty($prevWeekPreview['exists']))
        @php $pw = $prevWeekPreview; @endphp
        <div class="card mb-4 border rounded-4 shadow-sm">
            <div class="card-header rounded-top-4"
                 style="background:linear-gradient(135deg,#7c3aed 0%,#6366f1 50%,#2563eb 100%);">
                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 text-white">
                    <div class="d-flex align-items-center gap-2">
                        <i class="material-symbols-outlined">event_available</i>
                        <div>
                            <h5 class="mb-0 fw-bold">پیش‌نمایش برنامه هفته قبل</h5>
                            <small class="text-white-50">
                                جلسه قبلی: {{ $pw['session_date'] ?? '---' }}
                                · شروع: {{ $pw['program_start_date'] }}
                                · پایان: {{ $pw['program_end_date'] }}
                            </small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        @if(!$prevWeekPreviewCollapsed)
                            <button type="button" wire:click="togglePrevWeekSelectMode"
                                    class="btn btn-sm {{ $prevWeekSelectMode ? 'btn-warning' : 'btn-outline-light' }} d-flex align-items-center gap-1">
                                <i class="material-symbols-outlined" style="font-size:15px;">{{ $prevWeekSelectMode ? 'close' : 'content_copy' }}</i>
                                {{ $prevWeekSelectMode ? 'لغو انتخاب' : 'کپی پارت‌ها' }}
                            </button>
                        @endif
                        <button type="button" wire:click="togglePrevWeekPreview"
                                class="btn btn-sm btn-light d-flex align-items-center gap-1">
                            <i class="material-symbols-outlined" style="font-size:16px;">
                                {{ $prevWeekPreviewCollapsed ? 'expand_more' : 'expand_less' }}
                            </i>
                            {{ $prevWeekPreviewCollapsed ? 'نمایش' : 'بستن' }}
                        </button>
                    </div>
                </div>
            </div>

            @if(!$prevWeekPreviewCollapsed)
                @php
                    $fmtMins = function($mins) {
                        $mins = (int) $mins;
                        $h = intdiv($mins, 60);
                        $m = $mins % 60;
                        if ($h > 0 && $m > 0) return $h . ' ساعت و ' . $m . ' دقیقه';
                        if ($h > 0) return $h . ' ساعت';
                        return $m . ' دقیقه';
                    };
                @endphp
                <div class="card-body" x-data="{
                    fq: '',
                    fs: '',
                    fl: '',
                    fr: '',
                    matchPart(rating, isStudied, lessonType) {
                        if (this.fq === 'excellent' && (rating === null || rating < 8)) return false;
                        if (this.fq === 'good' && (rating === null || rating < 5 || rating >= 8)) return false;
                        if (this.fq === 'poor' && (rating === null || rating >= 5)) return false;
                        if (this.fs === 'not_studied' && isStudied) return false;
                        if (this.fs === 'no_rating' && rating !== null) return false;
                        if (this.fl !== '' && lessonType !== this.fl) return false;
                        return true;
                    },
                    matchRow(reportStatus, detailStatus) {
                        if (this.fr === '') return true;
                        if (this.fr === 'sent') return reportStatus === 'sent';
                        if (this.fr === 'not_sent') return reportStatus === 'not_sent';
                        if (this.fr === 'rejected') return reportStatus === 'sent' && detailStatus === 'rejected';
                        return true;
                    }
                }">
                    {{-- Summary chips --}}
                    <div class="row g-2 mb-3">
                        <div class="col-6 col-md-3 col-lg-2">
                            <div class="rounded-3 p-2 border bg-primary bg-opacity-10 text-center">
                                <div class="fs-6 fw-bold text-primary">{{ $fmtMins($pw['total_minutes']) }}</div>
                                <div class="small text-muted">زمان برنامه‌ریزی‌شده</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <div class="rounded-3 p-2 border bg-success bg-opacity-10 text-center">
                                <div class="fs-6 fw-bold text-success">{{ $fmtMins($pw['total_study_minutes']) }}</div>
                                <div class="small text-muted">زمان مطالعه ثبت شده</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <div class="rounded-3 p-2 border bg-info bg-opacity-10 text-center">
                                <div class="fs-5 fw-bold text-info">{{ $pw['total_parts'] }}</div>
                                <div class="small text-muted">تعداد پارت‌ها</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <div class="rounded-3 p-2 border bg-success bg-opacity-10 text-center">
                                <div class="fs-5 fw-bold text-success">{{ $pw['sent_reports_count'] }}</div>
                                <div class="small text-muted">گزارش ارسال‌شده</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <div class="rounded-3 p-2 border bg-secondary bg-opacity-10 text-center">
                                <div class="fs-5 fw-bold text-secondary">{{ $pw['missing_reports_count'] }}</div>
                                <div class="small text-muted">گزارش ارسال نشده</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <div class="rounded-3 p-2 border bg-danger bg-opacity-10 text-center">
                                <div class="fs-5 fw-bold text-danger">{{ $pw['rejected_reports_count'] }}</div>
                                <div class="small text-muted">گزارش رد شده</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <div class="rounded-3 p-2 border bg-warning bg-opacity-10 text-center">
                                <div class="fs-5 fw-bold text-warning">{{ $pw['compensatory_parts_count'] }}</div>
                                <div class="small text-muted">پارت‌های جبرانی</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <div class="rounded-3 p-2 border bg-dark bg-opacity-10 text-center">
                                <div class="fs-5 fw-bold text-dark">{{ $pw['extra_parts_count'] }}</div>
                                <div class="small text-muted">اضافه بر سازمان</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <div class="rounded-3 p-2 border text-center" style="background:rgba(124,58,237,.10);">
                                @php
                                    $emTotal = (int)($pw['total_extra_minutes'] ?? 0);
                                    $emH = intdiv($emTotal, 60);
                                    $emM = $emTotal % 60;
                                @endphp
                                <div class="fs-5 fw-bold" style="color:#7c3aed;">{{ $emH > 0 ? $emH . ':' . str_pad($emM, 2, '0', STR_PAD_LEFT) : $emTotal . 'د' }}</div>
                                <div class="small text-muted">اضافه بر مشاور</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <div class="rounded-3 p-2 border bg-success bg-opacity-10 text-center">
                                <div class="fs-5 fw-bold text-success">{{ $pw['rest_days_count'] }}</div>
                                <div class="small text-muted">روز استراحت</div>
                            </div>
                        </div>
                        <div class="col-6 col-md-3 col-lg-2">
                            <div class="rounded-3 p-2 border bg-danger bg-opacity-10 text-center">
                                <div class="fs-5 fw-bold text-danger">{{ $pw['exam_days_count'] }}</div>
                                <div class="small text-muted">روز آزمون</div>
                            </div>
                        </div>
                    </div>

                    {{-- Filter / Sort row --}}
                    <div class="d-flex flex-wrap gap-2 align-items-center mb-3 p-2 rounded-3 bg-body-tertiary border">
                        <span class="small fw-semibold text-muted d-flex align-items-center gap-1">
                            <i class="material-symbols-outlined" style="font-size:15px;">filter_list</i> فیلتر:
                        </span>
                        <select x-model="fq" class="form-select form-select-sm w-auto">
                            <option value="">امتیاز مطالعه (همه)</option>
                            <option value="excellent">مطالعه عالی (8-10)</option>
                            <option value="good">مطالعه با کیفیت (5-7)</option>
                            <option value="poor">مطالعه بی‌کیفیت (کمتر از 5)</option>
                        </select>
                        <select x-model="fs" class="form-select form-select-sm w-auto">
                            <option value="">پارت مطالعاتی (همه)</option>
                            <option value="not_studied">ثبت نشده</option>
                            <option value="no_rating">امتیاز داده نشده</option>
                        </select>
                        <select x-model="fl" class="form-select form-select-sm w-auto">
                            <option value="">نوع درس (همه)</option>
                            <option value="general">عمومی</option>
                            <option value="specialized">تخصصی</option>
                        </select>
                        <select wire:model.live="prevWeekSortByRating" class="form-select form-select-sm w-auto">
                            <option value="">امتیاز (پیش‌فرض)</option>
                            <option value="asc">از کم به زیاد</option>
                            <option value="desc">از زیاد به کم</option>
                        </select>
                        <select x-model="fr" class="form-select form-select-sm w-auto">
                            <option value="">وضعیت گزارش (همه)</option>
                            <option value="sent">ارسال شده</option>
                            <option value="not_sent">ارسال نشده</option>
                            <option value="rejected">رد شده</option>
                        </select>
                        <button type="button"
                                @click="fq='';fs='';fl='';fr=''"
                                x-show="fq!=='' || fs!=='' || fl!=='' || fr!==''"
                                class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1" style="font-size:11px;">
                            <i class="material-symbols-outlined" style="font-size:13px;">close</i> پاک کردن فیلترها
                        </button>
                    </div>

                    {{-- Selection panel for copying prev week parts --}}
                    @if($prevWeekSelectMode)
                        <div class="border border-warning rounded-3 p-3 mb-3 bg-warning bg-opacity-10">
                            <div class="d-flex flex-wrap align-items-center gap-2 mb-2">
                                <i class="material-symbols-outlined text-warning">content_copy</i>
                                <span class="fw-semibold">
                                    @if(count($prevWeekSelectedPartIds) > 0)
                                        {{ count($prevWeekSelectedPartIds) }} پارت انتخاب شده
                                    @else
                                        روی پارت‌ها کلیک کنید تا انتخاب شوند
                                    @endif
                                </span>
                            </div>
                            @if(count($prevWeekSelectedPartIds) > 0)
                                <div class="mb-2">
                                    <div class="small fw-semibold mb-1">روزهای مقصد در برنامه جاری:</div>
                                    <div class="d-flex flex-wrap gap-2">
                                        @foreach($weekDays as $wd)
                                            @if(!$wd['is_rest_day'] && !$wd['is_exam_day'])
                                                @php $isChecked = in_array((string)$wd['index'], array_map('strval', $prevWeekCopyTargetDays)); @endphp
                                                <label class="border rounded-pill px-2 py-1 small d-flex align-items-center gap-1 {{ $isChecked ? 'bg-primary text-white border-primary' : 'bg-body-tertiary' }}" style="cursor:pointer;">
                                                    <input type="checkbox" wire:model.live="prevWeekCopyTargetDays" value="{{ $wd['index'] }}" class="form-check-input mb-0" style="width:13px;height:13px;">
                                                    {{ $wd['name'] }}
                                                    <span class="text-muted" style="font-size:10px;">{{ $wd['jalali_date'] }}</span>
                                                </label>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <button wire:click="copyPrevWeekPartsToProgram"
                                        class="btn btn-sm btn-primary d-flex align-items-center gap-1"
                                    {{ empty($prevWeekCopyTargetDays) ? 'disabled' : '' }}>
                                    <span wire:loading.remove wire:target="copyPrevWeekPartsToProgram">
                                        <i class="material-symbols-outlined" style="font-size:15px;">content_copy</i>
                                        کپی به برنامه جاری
                                    </span>
                                    <span wire:loading wire:target="copyPrevWeekPartsToProgram">در حال کپی...</span>
                                </button>
                            @endif
                        </div>
                    @endif

                    {{-- Timetable --}}
                    <div class="border rounded-4 overflow-hidden bg-body">
                        <div class="overflow-x-auto">
                            @php $pwCols = max($pw['max_parts_per_day'], 1); @endphp
                            <table class="table table-bordered align-middle mb-0"
                                   style="min-width:{{ 520 + ($pwCols * 220) }}px;table-layout:fixed;border-collapse:separate;border-spacing:0;">
                                <thead>
                                <tr style="background:linear-gradient(90deg,#6d28d9,#4f46e5);">
                                    <th class="text-center text-white fw-bold" style="width:150px;position:sticky;right:0;z-index:6;background:linear-gradient(90deg,#6d28d9,#4f46e5);">روز / تاریخ</th>
                                    <th class="text-center text-white fw-bold" style="width:130px;position:sticky;right:150px;z-index:6;background:linear-gradient(90deg,#6d28d9,#4f46e5);">ساعت</th>
                                    <th class="text-center text-white fw-bold" style="width:210px;position:sticky;right:280px;z-index:6;background:linear-gradient(90deg,#6d28d9,#4f46e5);">وضعیت گزارش</th>
                                    @for($i = 1; $i <= $pwCols; $i++)
                                        <th class="text-center text-white fw-bold" style="width:220px;min-width:200px;">پارت {{ $i }}</th>
                                    @endfor
                                </tr>
                                </thead>
                                <tbody x-data="{ openDay: null }">
                                @foreach($pw['days'] as $dayIdx => $day)
                                    <tr class="{{ $day['is_rest_day'] ? 'table-success' : ($day['is_exam_day'] ? 'table-danger' : '') }}"
                                        x-show="matchRow('{{ $day['report_status'] }}', '{{ $day['report_detail_status'] ?? '' }}')"
                                        style="display:table-row;">
                                        {{-- روز / تاریخ --}}
                                        <td class="text-center" style="position:sticky;right:0;z-index:2;background-color:var(--bs-body-bg);">
                                            <span class="badge rounded-pill fw-bold d-block mb-1 {{ $day['is_rest_day'] ? 'bg-success' : ($day['is_exam_day'] ? 'bg-danger' : 'bg-primary') }}">
                                                {{ $day['name'] }}
                                            </span>
                                            <div class="small text-muted">{{ $day['jalali_date'] }}</div>
                                        </td>

                                        {{-- ساعت --}}
                                        <td class="text-center" style="position:sticky;right:150px;z-index:2;background-color:var(--bs-body-bg);">
                                            @if($day['is_rest_day'])
                                                <span class="text-success fw-bold">-</span>
                                            @else
                                                <div class="fw-bold text-primary small">برنامه: {{ $fmtMins($day['total_minutes']) }}</div>
                                                <div class="fw-bold text-success small">مطالعه: {{ $fmtMins($day['total_study_minutes']) }}</div>
                                                @if($day['day_avg_rating'] !== null)
                                                    @php
                                                        $rc = $day['day_avg_rating'] >= 8 ? 'success' : ($day['day_avg_rating'] >= 5 ? 'info' : 'danger');
                                                    @endphp
                                                    <span class="badge bg-{{ $rc }}-subtle text-{{ $rc }} mt-1" style="font-size:10px;">
                                                        میانگین {{ $day['day_avg_rating'] }}
                                                    </span>
                                                @endif
                                            @endif
                                        </td>

                                        {{-- وضعیت گزارش --}}
                                        <td class="text-center" style="position:sticky;right:280px;z-index:2;background-color:var(--bs-body-bg);box-shadow:-4px 0 10px rgba(0,0,0,.05);">
                                            <div class="d-flex flex-column gap-1 align-items-center">
                                                @if($day['is_rest_day'])
                                                    <span class="badge bg-success-subtle text-success fw-bold d-flex align-items-center gap-1">
                                                        <i class="material-symbols-outlined" style="font-size:13px;">self_improvement</i>
                                                        روز استراحت
                                                    </span>
                                                @else
                                                    <span class="badge bg-{{ $day['report_status_color'] }}-subtle text-{{ $day['report_status_color'] }} fw-bold">
                                                        {{ $day['report_status_label'] }}
                                                    </span>
                                                    @if($day['is_exam_day'])
                                                        <span class="badge bg-danger-subtle text-danger" style="font-size:10px;">روز آزمون</span>
                                                    @endif
                                                    @if($day['report_status'] === 'sent')
                                                        <small class="text-muted">
                                                            انجام شده: {{ $day['report_done_parts'] }}/{{ $day['parts_count'] }}
                                                        </small>
                                                        @if($day['report_compensatory_parts'] > 0)
                                                            <span class="badge bg-warning-subtle text-warning" style="font-size:10px;">
                                                                جبرانی: {{ $day['report_compensatory_parts'] }}
                                                            </span>
                                                        @endif
                                                        @if($day['report_rating'])
                                                            <span class="badge bg-info-subtle text-info" style="font-size:10px;">
                                                                امتیاز {{ $day['report_rating'] }}
                                                            </span>
                                                        @endif
                                                    @endif

                                                    {{-- دکمه جزییات --}}
                                                    @if($day['report_status'] === 'sent' || !empty($day['report_detail_parts']) || !empty($day['report_compensatory_reports']))
                                                        <div class="w-100">
                                                            <button @click="openDay = (openDay === {{ $dayIdx }}) ? null : {{ $dayIdx }}"
                                                                    class="btn btn-xs btn-outline-secondary w-100 d-flex align-items-center justify-content-center gap-1 mt-1"
                                                                    style="font-size:11px;padding:2px 6px;">
                                                                <i class="material-symbols-outlined" style="font-size:12px;">info</i>
                                                                جزییات
                                                                <i class="material-symbols-outlined" style="font-size:12px;" x-text="openDay === {{ $dayIdx }} ? 'expand_less' : 'expand_more'">expand_more</i>
                                                            </button>
                                                            <div x-show="openDay === {{ $dayIdx }}" x-cloak
                                                                 class="text-start mt-1 border rounded-3 p-2 bg-body shadow-sm"
                                                                 style="min-width:200px;max-width:280px;font-size:11px;z-index:10;position:relative;">
                                                                @if(!empty($day['report_missed_reason']))
                                                                    <div class="mb-2 p-2 rounded-2 bg-warning bg-opacity-10 border border-warning border-opacity-25">
                                                                        <div class="fw-semibold text-warning mb-1">علت عدم انجام:</div>
                                                                        <div class="text-muted" style="white-space:pre-line;">{{ $day['report_missed_reason'] }}</div>
                                                                    </div>
                                                                @endif
                                                                @if(!empty($day['report_detail_parts']))
                                                                    <div class="fw-semibold mb-1">پارت‌های ارسال شده:</div>
                                                                    @foreach($day['report_detail_parts'] as $dp)
                                                                        <div class="d-flex align-items-center gap-1 py-1 border-bottom border-opacity-25">
                                                                            <i class="material-symbols-outlined {{ $dp['is_read'] ? 'text-success' : 'text-danger' }}" style="font-size:13px;">
                                                                                {{ $dp['is_read'] ? 'check_circle' : 'cancel' }}
                                                                            </i>
                                                                            <span class="flex-fill text-truncate">{{ $dp['lesson_name'] }}</span>
                                                                            <span class="text-muted">{{ $fmtMins($dp['duration_minutes']) }}</span>
                                                                            @if($dp['is_compensatory'])
                                                                                <span class="badge bg-warning-subtle text-warning" style="font-size:9px;">جبرانی</span>
                                                                            @endif
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                                @if(!empty($day['report_compensatory_reports']))
                                                                    <div class="fw-semibold mt-2 mb-1 text-warning">گزارشات جبرانی:</div>
                                                                    @foreach($day['report_compensatory_reports'] as $cr)
                                                                        <div class="border rounded-2 p-1 mb-1 bg-warning bg-opacity-10">
                                                                            @if($cr['description'])
                                                                                <div class="text-muted mb-1" style="white-space:pre-line;">{{ Str::limit($cr['description'], 60) }}</div>
                                                                            @endif
                                                                            @foreach($cr['parts'] as $cp)
                                                                                <div class="d-flex align-items-center gap-1 py-1">
                                                                                    <i class="material-symbols-outlined {{ $cp['is_read'] ? 'text-success' : 'text-danger' }}" style="font-size:12px;">
                                                                                        {{ $cp['is_read'] ? 'check_circle' : 'cancel' }}
                                                                                    </i>
                                                                                    <span class="flex-fill text-truncate">{{ $cp['lesson_name'] }}</span>
                                                                                    <span class="text-muted">{{ $fmtMins($cp['duration_minutes']) }}</span>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                        </td>

                                        {{-- پارت‌ها --}}
                                        @for($i = 0; $i < $pwCols; $i++)
                                            <td style="vertical-align:top;padding:6px;">
                                                @if($day['is_rest_day'])
                                                    @if($i === 0)
                                                        <div class="rounded-3 border border-success bg-success bg-opacity-10 d-flex align-items-center justify-content-center p-3" style="min-height:130px;">
                                                            <div class="text-center">
                                                                <i class="material-symbols-outlined text-success" style="font-size:32px;">self_improvement</i>
                                                                <div class="small text-success fw-semibold mt-1">استراحت</div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="rounded-3 border border-success border-opacity-25 bg-success bg-opacity-10" style="min-height:130px;"></div>
                                                    @endif
                                                @elseif(isset($day['parts'][$i]))
                                                    @php
                                                        $p = $day['parts'][$i];
                                                        $pwIsSel = $prevWeekSelectMode && in_array($p['id'], $prevWeekSelectedPartIds);
                                                    @endphp
                                                    <div class="rounded-3 border p-2 position-relative h-100 {{ $pwIsSel ? 'border-primary border-2 bg-primary bg-opacity-10' : '' }}"
                                                         style="min-height:130px;
                                                                {{ (!$pwIsSel && $p['avg_color']) ? 'border-color:var(--bs-' . $p['avg_color'] . ')!important;border-width:2px!important;' : '' }}
                                                                {{ $prevWeekSelectMode ? 'cursor:pointer;' : '' }}"
                                                         :class="{ 'opacity-25': !matchPart({{ $p['avg_rating'] ?? 'null' }}, {{ $p['is_studied'] ? 'true' : 'false' }}, '{{ $p['lesson_type'] }}') }"
                                                         @if($prevWeekSelectMode) wire:click="togglePrevWeekPartSelection({{ $p['id'] }})" @endif>

                                                        {{-- Checkbox overlay when select mode --}}
                                                        @if($prevWeekSelectMode)
                                                            <i class="material-symbols-outlined position-absolute top-0 end-0 m-1 {{ $pwIsSel ? 'text-primary' : 'text-muted' }}" style="font-size:18px;">
                                                                {{ $pwIsSel ? 'check_box' : 'check_box_outline_blank' }}
                                                            </i>
                                                        @endif

                                                        {{-- Header: lesson_name + grade --}}
                                                        <div class="d-flex align-items-start gap-1 mb-1 {{ $prevWeekSelectMode ? 'pe-4' : '' }}">
                                                            <span class="fw-semibold small flex-fill">{{ $p['lesson_name'] }}</span>
                                                            @if($p['grade_label'])
                                                                <span class="badge bg-body-tertiary text-body border" style="font-size:10px;">
                                                                    {{ $p['grade_label'] }}
                                                                </span>
                                                            @endif
                                                        </div>

                                                        {{-- Badges: part_type, source_type, extra --}}
                                                        <div class="d-flex flex-wrap gap-1 mb-1">
                                                            <span class="badge bg-secondary-subtle text-secondary" style="font-size:10px;">
                                                                {{ $p['part_type_label'] }}
                                                            </span>
                                                            <span class="badge bg-body-tertiary text-body border" style="font-size:10px;">
                                                                {{ $p['lesson_type_label'] }}
                                                            </span>
                                                            @if($p['is_extra'])
                                                                <span class="badge bg-{{ $p['source_type_color'] }}-subtle text-{{ $p['source_type_color'] }}" style="font-size:10px;">
                                                                    {{ $p['source_type_label'] }}
                                                                </span>
                                                            @endif
                                                        </div>

                                                        {{-- فصل --}}
                                                        <div class="small d-flex align-items-start gap-1 mb-1">
                                                            <i class="material-symbols-outlined text-muted" style="font-size:13px;">menu_book</i>
                                                            <span class="flex-fill">
                                                                @if(($p['part_mode'] ?? 'normal') === 'review')
                                                                    @if(!empty($p['review_chapters']))
                                                                        @foreach($p['review_chapters'] as $rc)
                                                                            <span class="badge bg-warning-subtle text-warning" style="font-size:10px;">{{ $rc['name'] }}</span>
                                                                        @endforeach
                                                                    @else
                                                                        <span class="text-muted">بدون فصل</span>
                                                                    @endif
                                                                @elseif(($p['part_mode'] ?? 'normal') === 'whole_book')
                                                                    <span class="text-success">کل کتاب</span>
                                                                @elseif(!empty($p['chapter_name']))
                                                                    {{ $p['chapter_name'] }}
                                                                @else
                                                                    <span class="text-muted">بدون فصل</span>
                                                                @endif
                                                            </span>
                                                        </div>

                                                        {{-- Planned duration + test count --}}
                                                        <div class="small text-muted d-flex align-items-center gap-1 mb-1">
                                                            <i class="material-symbols-outlined" style="font-size:13px;">schedule</i>
                                                            {{ $fmtMins($p['duration_minutes']) }}
                                                            @if($p['test_count'])
                                                                · <i class="material-symbols-outlined" style="font-size:13px;">quiz</i> {{ $p['test_count'] }}
                                                            @endif
                                                        </div>

                                                        {{-- Study time registered --}}
                                                        <div class="small d-flex align-items-center gap-1 {{ $p['is_studied'] ? 'text-success' : 'text-danger' }}">
                                                            <i class="material-symbols-outlined" style="font-size:13px;">timer</i>
                                                            @if($p['is_studied'])
                                                                {{ $fmtMins($p['study_minutes']) }} مطالعه
                                                            @else
                                                                ثبت نشده
                                                            @endif
                                                        </div>

                                                        {{-- Cheat warning --}}
                                                        @if(!empty($p['is_cheat']))
                                                            <div class="mt-1 rounded-2 px-2 py-1 d-flex align-items-center gap-1"
                                                                 style="background:rgba(220,38,38,.1);border:1px solid rgba(220,38,38,.3);"
                                                                 title="تقلب: ثبت پارت {{ $p['cheat_minutes'] }} دقیقه دیرتر از زمان پایان مورد انتظار">
                                                                <i class="material-symbols-outlined text-danger" style="font-size:13px;">warning</i>
                                                                <span class="small text-danger fw-semibold">تقلب: {{ $p['cheat_minutes'] }} دقیقه دیر</span>
                                                            </div>
                                                        @endif

                                                        {{-- Avg rating --}}
                                                        @if($p['avg_rating'] !== null)
                                                            <div class="mt-1 pt-1 border-top d-flex align-items-center justify-content-between gap-1">
                                                                <span class="badge bg-{{ $p['avg_color'] }}-subtle text-{{ $p['avg_color'] }}" style="font-size:10px;">
                                                                    {{ $p['avg_label'] }}
                                                                </span>
                                                                <span class="small fw-bold text-{{ $p['avg_color'] }}">
                                                                    {{ $p['avg_rating'] }}/10
                                                                </span>
                                                            </div>
                                                        @endif

                                                        {{-- بدج‌های زودتر/اضافه بر مشاور/تقلب --}}
                                                        @if(($p['is_early_finish'] ?? false) || ($p['has_extra_time'] ?? false) || ($p['is_cheat'] ?? false))
                                                            <div class="mt-1 d-flex flex-wrap gap-1" @if(!empty($p['cheat_reason'])) title="علت تقلب: {{ $p['cheat_reason'] }}" @endif>
                                                                <x-study-session-badges
                                                                    :is-early-finish="(bool)($p['is_early_finish'] ?? false)"
                                                                    :extra-seconds="(int)($p['extra_seconds'] ?? 0)"
                                                                    :extra-target-seconds="(int)($p['extra_target_seconds'] ?? 0)"
                                                                    :is-cheating="(bool)($p['is_cheat'] ?? false)"
                                                                    :cheat-status="$p['cheat_status'] ?? null"
                                                                    :cheat-minutes="(int)($p['cheat_minutes'] ?? 0)"
                                                                    style="bootstrap" />
                                                                @if(($p['has_extra_time'] ?? false) && ($p['extra_target_min'] ?? 0) > 0 && ($p['extra_target_min'] ?? 0) !== ($p['extra_minutes'] ?? 0))
                                                                    <span class="small text-muted" style="font-size:10px;">
                                                                        (هدف: {{ $p['extra_target_min'] }}د)
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                @else
                                                    <div class="rounded-3" style="min-height:130px;background:var(--bs-tertiary-bg);border:1px dashed var(--bs-border-color);"></div>
                                                @endif
                                            </td>
                                        @endfor
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-3 mt-3 small text-muted">
                        <span class="d-flex align-items-center gap-1 text-danger fw-semibold">
                            <i class="material-symbols-outlined" style="font-size:14px;">warning</i>
                            تقلب: ثبت پارت بیش از ۱۰ دقیقه دیرتر از زمان پایان مورد انتظار
                            <span class="text-muted fw-normal">(شروع + مدت پارت)</span>
                        </span>
                        <span class="d-flex align-items-center gap-1">
                            <span class="d-inline-block rounded" style="width:12px;height:12px;background:var(--bs-success);"></span>
                            مطالعه عالی (8+)
                        </span>
                        <span class="d-flex align-items-center gap-1">
                            <span class="d-inline-block rounded" style="width:12px;height:12px;background:var(--bs-info);"></span>
                            مطالعه با کیفیت (5-7)
                        </span>
                        <span class="d-flex align-items-center gap-1">
                            <span class="d-inline-block rounded" style="width:12px;height:12px;background:var(--bs-danger);"></span>
                            مطالعه بی‌کیفیت (کمتر از 5)
                        </span>
                    </div>
                </div>
            @endif
        </div>
    @endif


    {{-- ====== ACTION BAR ====== --}}
    <div class="d-flex align-items-center flex-wrap gap-2 p-2 mb-2 border rounded-3 bg-body shadow-sm sticky-top"
         x-data="{}"
         @keydown.delete.window="if ($wire.bulkDeleteMode && $wire.bulkDeleteSelectedIds.length > 0) $wire.openBulkDeleteConfirm()"
         @keydown.backspace.window="if ($wire.bulkDeleteMode && $wire.bulkDeleteSelectedIds.length > 0) $wire.openBulkDeleteConfirm()"
         style="z-index:10;top:0;">
        @if($partSelectMode)
            @if(count($selectedPartIds) > 0)
                {{-- پارت انتخاب شده: فقط لغو نشان بده --}}
                <button wire:click="clearPartSelection"
                        class="btn btn-sm {{ $cutMode ? 'btn-danger' : 'btn-warning' }} d-flex align-items-center gap-1">
                    <i class="material-symbols-outlined" style="font-size:16px;">close</i> لغو
                </button>
                <span class="fw-semibold small {{ $cutMode ? 'text-danger' : 'text-warning' }}">
                    {{ count($selectedPartIds) }} پارت انتخاب شده
                </span>

                <div class="w-100 pt-2 mt-1 border-top d-flex flex-wrap gap-2 align-items-center">
                    <span class="small fw-semibold">روز{{ $cutMode ? '' : 'های' }} مقصد:</span>
                    @foreach($weekDays as $day)
                        @if(!$day['is_rest_day'] && !$day['is_exam_day'])
                            @if($cutMode)
                                @php $isChecked = (string)$copyTargetDay === (string)$day['index']; @endphp
                                <label class="border rounded-pill px-2 py-1 small d-flex align-items-center gap-1 {{ $isChecked ? 'bg-danger text-white border-danger' : 'bg-body-tertiary' }}" style="cursor:pointer;">
                                    <input type="radio" wire:model.live="copyTargetDay" value="{{ $day['index'] }}" class="form-check-input mb-0" style="width:13px;height:13px;">
                                    {{ $day['name'] }}
                                </label>
                            @else
                                @php $isChecked = in_array((string)$day['index'], array_map('strval', $copyTargetDays)); @endphp
                                <label class="border rounded-pill px-2 py-1 small d-flex align-items-center gap-1 {{ $isChecked ? 'bg-warning text-white border-warning' : 'bg-body-tertiary' }}" style="cursor:pointer;">
                                    <input type="checkbox" wire:model.live="copyTargetDays" value="{{ $day['index'] }}" class="form-check-input mb-0" style="width:13px;height:13px;">
                                    {{ $day['name'] }}
                                </label>
                            @endif
                        @endif
                    @endforeach
                    @if($cutMode)
                        <button wire:click="cutSelectedParts" class="btn btn-sm btn-danger" {{ $copyTargetDay === null ? 'disabled' : '' }}>
                            <span wire:loading.remove wire:target="cutSelectedParts"><i class="material-symbols-outlined" style="font-size:15px;">content_cut</i> کات</span>
                            <span wire:loading wire:target="cutSelectedParts">...</span>
                        </button>
                    @else
                        <button wire:click="copySelectedParts" class="btn btn-sm btn-warning" {{ empty($copyTargetDays) ? 'disabled' : '' }}>
                            <span wire:loading.remove wire:target="copySelectedParts"><i class="material-symbols-outlined" style="font-size:15px;">content_copy</i> کپی</span>
                            <span wire:loading wire:target="copySelectedParts">...</span>
                        </button>
                    @endif
                </div>
            @else
                {{-- هیچ پارتی انتخاب نشده: خروج از کپی/کات نشان بده --}}
                <button wire:click="togglePartSelectMode({{ $cutMode ? 'true' : 'false' }})"
                        class="btn btn-sm {{ $cutMode ? 'btn-danger' : 'btn-warning' }} d-flex align-items-center gap-1">
                    <i class="material-symbols-outlined" style="font-size:16px;">close</i>
                    خروج از {{ $cutMode ? 'کات' : 'کپی' }}
                </button>
                <span class="small text-muted">روی پارت‌ها کلیک کنید</span>
            @endif

        @elseif($bulkDeleteMode)
            @if(count($bulkDeleteSelectedIds) > 0)
                {{-- پارت انتخاب شده: فقط لغو + حذف --}}
                <button wire:click="toggleBulkDeleteMode" class="btn btn-sm btn-danger d-flex align-items-center gap-1">
                    <i class="material-symbols-outlined" style="font-size:16px;">close</i> لغو
                </button>
                <span class="fw-semibold text-danger small">{{ count($bulkDeleteSelectedIds) }} پارت</span>
                <button wire:click="openBulkDeleteConfirm" class="btn btn-sm btn-danger">
                    <i class="material-symbols-outlined" style="font-size:15px;">delete_forever</i>
                    حذف {{ count($bulkDeleteSelectedIds) }} پارت
                </button>
            @else
                {{-- هیچ پارتی انتخاب نشده: خروج از حذف گروهی --}}
                <button wire:click="toggleBulkDeleteMode" class="btn btn-sm btn-danger d-flex align-items-center gap-1">
                    <i class="material-symbols-outlined" style="font-size:16px;">close</i> خروج از حذف گروهی
                </button>
                <span class="small text-muted">روی پارت‌ها کلیک کنید</span>
            @endif

        @else
            <button wire:click="togglePartSelectMode(false)" class="btn btn-sm btn-outline-success d-flex align-items-center gap-1">
                <i class="material-symbols-outlined" style="font-size:15px;">content_copy</i> کپی پارت
            </button>
            <button wire:click="togglePartSelectMode(true)" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
                <i class="material-symbols-outlined" style="font-size:15px;">content_cut</i> کات پارت
            </button>
            <button wire:click="toggleBulkDeleteMode" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1">
                <i class="material-symbols-outlined" style="font-size:15px;">delete_sweep</i> حذف گروهی
            </button>
        @endif
    </div>

    {{-- ====== WEEKLY TABLE ====== --}}
    <div class="border rounded-4 shadow-sm overflow-hidden mb-4 bg-body">
        <div class="overflow-x-auto">
            @php
                $maxParts = 10;
                foreach($weekDays as $wd) { $c = count($wd['parts']); if($c >= $maxParts) $maxParts = $c + 2; }
                $maxParts = max($maxParts, 10);
            @endphp
            <table class="table table-bordered align-middle mb-0"
                   style="min-width:1800px;table-layout:fixed;border-collapse:separate;border-spacing:0;">
                <thead>
                <tr style="background:linear-gradient(90deg,#1d4ed8,#2563eb);">
                    <th class="text-center text-white fw-bold" style="width:130px;position:sticky;right:0;z-index:6;background:linear-gradient(90deg,#1d4ed8,#2563eb);">روز / تاریخ</th>
                    <th class="text-center text-white fw-bold" style="width:110px;position:sticky;right:130px;z-index:6;background:linear-gradient(90deg,#1d4ed8,#2563eb);">ساعت</th>
                    <th class="text-center text-white fw-bold" style="width:155px;position:sticky;right:240px;z-index:6;background:linear-gradient(90deg,#1d4ed8,#2563eb);">وضعیت روز</th>
                    @for($i = 1; $i <= $maxParts; $i++)
                        <th class="text-center text-white fw-bold" style="width:220px;min-width:200px;">پلن {{ $i }}</th>
                    @endfor
                </tr>
                </thead>
                <tbody>
                @foreach($weekDays as $day)
                    <tr data-day-index="{{ $day['index'] }}" data-sortable-row="1"
                        class="{{ $day['is_rest_day'] ? 'table-success' : ($day['is_exam_day'] ? 'table-danger' : '') }}">

                        {{-- روز / تاریخ --}}
                        <td class="text-center" style="position:sticky;right:0;z-index:2;background-color:var(--bs-body-bg);">
                            <span class="badge rounded-pill fw-bold d-block mb-1 {{ $day['is_rest_day'] ? 'bg-success' : ($day['is_exam_day'] ? 'bg-danger' : 'bg-primary') }}">
                                {{ $day['name'] }}
                            </span>
                            <div class="small text-muted">{{ $day['jalali_date'] }}</div>
                        </td>

                        {{-- ساعت --}}
                        <td class="text-center" style="position:sticky;right:130px;z-index:2;background-color:var(--bs-body-bg);">
                            @if(!$day['is_rest_day'])
                                <div class="fw-bold small">{{ $this->fmtDuration($day['total_minutes']) }}</div>
                            @else
                                <span class="text-success fw-bold">-</span>
                            @endif
                        </td>

                        {{-- وضعیت روز --}}
                        <td class="text-center" style="position:sticky;right:240px;z-index:2;background-color:var(--bs-body-bg);box-shadow:-4px 0 10px rgba(0,0,0,.07);">
                            <div class="d-flex flex-column gap-1 align-items-center">
                                @if(!$day['is_rest_day'])
                                    <span class="badge bg-warning-subtle text-warning fw-bold">
                                        <i class="material-symbols-outlined" style="font-size:12px;">quiz</i>
                                        {{ $day['total_tests'] }}
                                    </span>
                                @endif
                                <div class="d-flex align-items-center gap-1">
                                    <small class="{{ $day['is_rest_day'] ? 'text-success fw-semibold' : 'text-muted' }}">استراحت</small>
                                    <div class="form-check form-switch mb-0">
                                        <input type="checkbox" class="form-check-input" style="cursor:pointer;"
                                               wire:click="toggleRestDay({{ $day['index'] }})"
                                            {{ $day['is_rest_day'] ? 'checked' : '' }}>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-1">
                                    <small class="{{ $day['is_exam_day'] ? 'text-danger fw-semibold' : 'text-muted' }}">آزمون</small>
                                    <div class="form-check form-switch mb-0">
                                        <input type="checkbox" class="form-check-input" style="cursor:pointer;"
                                               wire:click="toggleExamDay({{ $day['index'] }})"
                                            {{ $day['is_exam_day'] ? 'checked' : '' }}
                                            {{ $day['is_rest_day'] ? 'disabled' : '' }}>
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- پلن‌ها --}}
                        @for($i = 0; $i < $maxParts; $i++)
                            <td class="{{ (!$day['is_rest_day'] && !$day['is_exam_day'] && isset($day['parts'][$i])) ? 'plan-part-cell' : '' }}"
                                data-part-id="{{ (!$day['is_rest_day'] && !$day['is_exam_day'] && isset($day['parts'][$i])) ? $day['parts'][$i]->id : '' }}"
                                style="vertical-align:top;padding:6px;">

                                @if($day['is_rest_day'])
                                    @if($i === 0)
                                        <div class="rounded-3 border border-success bg-success bg-opacity-10 d-flex align-items-center justify-content-center p-3" style="min-height:110px;">
                                            <div class="text-center">
                                                <i class="material-symbols-outlined text-success" style="font-size:32px;">self_improvement</i>
                                                <div class="small text-success fw-semibold mt-1">استراحت</div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="rounded-3 border border-success border-opacity-25 bg-success bg-opacity-10" style="min-height:110px;"></div>
                                    @endif

                                @elseif($day['is_exam_day'])
                                    @if(isset($day['parts'][$i]))
                                        @php $part = $day['parts'][$i]; @endphp
                                        <div class="rounded-3 border p-2 {{ $part->part_type === 'exam_analysis' ? 'border-warning bg-warning bg-opacity-10' : 'border-danger bg-danger bg-opacity-10' }}"
                                             wire:click="editExamPart({{ $part->id }})" style="cursor:pointer;min-height:110px;">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <span class="fw-semibold small">{{ $part->lesson_name }}</span>
                                                <span class="badge {{ $part->part_type === 'exam_analysis' ? 'bg-warning' : 'bg-danger' }} text-white" style="font-size:10px;">
                                                    {{ $part->part_type_label }}
                                                </span>
                                            </div>
                                            <p class="small mb-1 text-muted">{{ Str::limit($part->description, 50) }}</p>
                                            <div class="small text-muted d-flex align-items-center gap-1">
                                                <i class="material-symbols-outlined" style="font-size:13px;">schedule</i>
                                                {{ $this->fmtDuration($part->duration_minutes) }}
                                            </div>
                                            <button class="btn btn-sm btn-outline-danger mt-1 w-100"
                                                    wire:click.stop="deleteExamPart({{ $part->id }})"
                                                    wire:confirm="آیا از حذف این آزمون اطمینان دارید؟">
                                                <i class="material-symbols-outlined" style="font-size:13px;">delete</i>
                                            </button>
                                        </div>
                                    @elseif($i === count($day['parts']))
                                        <div class="rounded-3 border border-dashed border-danger bg-danger bg-opacity-10 d-flex align-items-center justify-content-center"
                                             style="min-height:110px;cursor:pointer;border-style:dashed!important;"
                                             wire:click="openExamPartModal({{ $day['index'] }})">
                                            <div class="text-center">
                                                <i class="material-symbols-outlined text-danger">add</i>
                                                <div class="small text-danger mt-1">افزودن آزمون</div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="rounded-3 border border-danger border-opacity-10 bg-danger bg-opacity-10" style="min-height:110px;"></div>
                                    @endif

                                @elseif(isset($day['parts'][$i]))
                                    @php $part = $day['parts'][$i]; @endphp

                                    @if($bulkDeleteMode)
                                        @php $isSel = in_array($part->id, $bulkDeleteSelectedIds); @endphp
                                        <div class="rounded-3 border p-2 position-relative {{ $isSel ? 'border-danger border-2 bg-danger bg-opacity-10' : '' }}"
                                             wire:click="toggleBulkDeleteSelection({{ $part->id }})"
                                             style="cursor:pointer;min-height:110px;">
                                            <i class="material-symbols-outlined position-absolute top-0 end-0 m-1 {{ $isSel ? 'text-danger' : 'text-muted' }}" style="font-size:18px;">
                                                {{ $isSel ? 'check_box' : 'check_box_outline_blank' }}
                                            </i>
                                            <div class="d-flex align-items-start gap-1 mb-1 pe-4">
                                                <span class="fw-semibold small flex-fill">{{ $part->lesson_name }}</span>
                                                @if($part->grade_label)
                                                    <span class="badge bg-body-tertiary text-body border" style="font-size:10px;">{{ $part->grade_label }}</span>
                                                @endif
                                            </div>
                                            @if($part->source_type && $part->source_type !== 'normal')
                                                <span class="badge bg-{{ $part->source_type_color }}-subtle text-{{ $part->source_type_color }}" style="font-size:10px;">{{ $part->source_type_label }}</span>
                                            @endif
                                            @include('livewire.admin.student.consultation.partials.part-chapter-line', ['part' => $part])
                                            <div class="small text-muted d-flex align-items-center gap-1">
                                                <i class="material-symbols-outlined" style="font-size:13px;">schedule</i>
                                                {{ $this->fmtDuration($part->duration_minutes) }}
                                                @if($part->test_count)
                                                    · <i class="material-symbols-outlined" style="font-size:13px;">quiz</i> {{ $part->test_count }}
                                                @endif
                                            </div>
                                        </div>

                                    @elseif($partSelectMode)
                                        @php $isSel = in_array($part->id, $selectedPartIds); @endphp
                                        <div class="rounded-3 border p-2 position-relative {{ $isSel ? ($cutMode ? 'border-danger border-2' : 'border-warning border-2') : '' }}"
                                             wire:click="togglePartSelection({{ $part->id }})"
                                             style="cursor:pointer;min-height:110px;">
                                            <i class="material-symbols-outlined position-absolute top-0 end-0 m-1 {{ $isSel ? ($cutMode ? 'text-danger' : 'text-warning') : 'text-muted' }}" style="font-size:18px;">
                                                {{ $isSel ? 'check_box' : 'check_box_outline_blank' }}
                                            </i>
                                            <div class="d-flex align-items-start gap-1 mb-1 pe-4">
                                                <span class="fw-semibold small flex-fill">{{ $part->lesson_name }}</span>
                                                @if($part->grade_label)
                                                    <span class="badge bg-body-tertiary text-body border" style="font-size:10px;">{{ $part->grade_label }}</span>
                                                @endif
                                            </div>
                                            @if($part->source_type && $part->source_type !== 'normal')
                                                <span class="badge bg-{{ $part->source_type_color }}-subtle text-{{ $part->source_type_color }}" style="font-size:10px;">{{ $part->source_type_label }}</span>
                                            @endif
                                            @include('livewire.admin.student.consultation.partials.part-chapter-line', ['part' => $part])
                                            <div class="small text-muted d-flex align-items-center gap-1">
                                                <i class="material-symbols-outlined" style="font-size:13px;">schedule</i>
                                                {{ $this->fmtDuration($part->duration_minutes) }}
                                                @if($part->test_count)
                                                    · <i class="material-symbols-outlined" style="font-size:13px;">quiz</i> {{ $part->test_count }}
                                                @endif
                                            </div>
                                        </div>

                                    @else
                                        <div class="rounded-3 border p-2 position-relative {{ $part->color_class ?? '' }}"
                                             wire:click="editPart({{ $part->id }})"
                                             style="cursor:pointer;min-height:110px;">
                                            <div class="d-flex align-items-start gap-1 mb-1">
                                                <i class="material-symbols-outlined text-muted drag-handle" style="font-size:15px;cursor:grab;" wire:click.stop>drag_handle</i>
                                                <span class="fw-semibold small flex-fill">{{ $part->lesson_name }}</span>
                                                <span class="badge bg-body-tertiary text-body border" style="font-size:10px;">{{ $part->grade_label }}</span>
                                            </div>
                                            @if($part->source_type && $part->source_type !== 'normal')
                                                <span class="badge bg-{{ $part->source_type_color }}-subtle text-{{ $part->source_type_color }}" style="font-size:10px;">{{ $part->source_type_label }}</span>
                                            @endif
                                            @if(($part->part_mode ?? 'normal') === 'whole_book')
                                                <span class="badge bg-success-subtle text-success" style="font-size:10px;">کل کتاب</span>
                                            @elseif(($part->part_mode ?? 'normal') === 'review')
                                                <span class="badge bg-warning-subtle text-warning" style="font-size:10px;">پارت مروری</span>
                                            @endif
                                            @include('livewire.admin.student.consultation.partials.part-chapter-line', ['part' => $part])
                                            <div class="small text-muted d-flex align-items-center gap-1">
                                                <i class="material-symbols-outlined" style="font-size:13px;">schedule</i>
                                                {{ $this->fmtDuration($part->duration_minutes) }}
                                                @if($part->test_count)
                                                    · <i class="material-symbols-outlined" style="font-size:13px;">quiz</i> {{ $part->test_count }}
                                                @endif
                                            </div>
                                        </div>
                                    @endif

                                @elseif($i === count($day['parts']))
                                    <div class="rounded-3 d-flex align-items-center justify-content-center"
                                         style="min-height:110px;cursor:pointer;border:1px dashed var(--bs-border-color);background:var(--bs-tertiary-bg);"
                                         wire:click="openPartModal({{ $day['index'] }})">
                                        <div class="text-center">
                                            <i class="material-symbols-outlined text-primary">add_circle</i>
                                            <div class="small text-primary mt-1">افزودن پارت</div>
                                        </div>
                                    </div>
                                @else
                                    <div class="rounded-3" style="min-height:110px;background:var(--bs-tertiary-bg);border:1px dashed var(--bs-border-color);"></div>
                                @endif
                            </td>
                        @endfor
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- ====== STATS ====== --}}
    @if($weeklyProgram && $weeklyProgram->parts->count() > 0)
        <div class="card mb-4 border rounded-4 shadow-sm">
            <div class="card-header d-flex align-items-center gap-2 rounded-top-4">
                <i class="material-symbols-outlined text-info">analytics</i>
                <h5 class="mb-0">خلاصه برنامه</h5>
            </div>
            <div class="card-body">
                <div class="row g-3 mb-3">
                    <div class="col-6 col-md-3">
                        <div class="rounded-3 p-3 border bg-primary bg-opacity-10 text-center">
                            <div class="fs-5 fw-bold text-primary">{{ $this->fmtDuration($weeklyProgram->parts->sum('duration_minutes')) }}</div>
                            <div class="small text-muted">زمان کل</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="rounded-3 p-3 border bg-warning bg-opacity-10 text-center">
                            <div class="fs-3 fw-bold text-warning">{{ $weeklyProgram->total_tests }}</div>
                            <div class="small text-muted">تست کل</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="rounded-3 p-3 border bg-success bg-opacity-10 text-center">
                            <div class="fs-3 fw-bold text-success">{{ $weeklyProgram->total_parts }}</div>
                            <div class="small text-muted">تعداد پارت</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="rounded-3 p-3 border bg-secondary bg-opacity-10 text-center">
                            <div class="fs-3 fw-bold text-secondary">{{ $weeklyProgram->total_plans }}</div>
                            <div class="small text-muted">پلن درسی</div>
                        </div>
                    </div>
                </div>
                <div class="row g-2">
                    @foreach([
                        ['label'=>'پارت تستی','value'=>$weeklyProgram->test_parts_count,'color'=>'primary'],
                        ['label'=>'پارت تشریحی','value'=>$weeklyProgram->descriptive_parts_count,'color'=>'warning'],
                        ['label'=>'پارت ویدئو','value'=>$weeklyProgram->video_parts_count,'color'=>'secondary'],
                        ['label'=>'پارت دهم','value'=>$weeklyProgram->grade_10_parts_count,'color'=>'success'],
                        ['label'=>'پارت یازدهم','value'=>$weeklyProgram->grade_11_parts_count,'color'=>'danger'],
                        ['label'=>'پارت دوازدهم','value'=>$weeklyProgram->grade_12_parts_count,'color'=>'info'],
                    ] as $stat)
                        <div class="col-6 col-md-2">
                            <div class="rounded-3 p-2 border bg-body-tertiary text-center">
                                <div class="fs-4 fw-bold text-{{ $stat['color'] }}">{{ $stat['value'] }}</div>
                                <div class="small text-muted">{{ $stat['label'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- ====== SAVE BUTTON ====== --}}
    <div class="d-flex justify-content-end gap-2 mb-4">
        <a href="{{ route('admin.advising-sessions') }}" class="btn btn-outline-secondary">بازگشت</a>
        <button wire:click="finalSave" class="btn btn-primary">
            <span wire:loading.remove>ذخیره برنامه</span>
            <span wire:loading>در حال ذخیره...</span>
        </button>
    </div>

    {{-- ================================================================
         MODALS
    ================================================================ --}}

    {{-- ====== MODAL: افزودن/ویرایش پارت ====== --}}
    @if($showPartModal)
        <div class="modal  fade show d-block" tabindex="-1" style="background:rgba(2,6,23,.6);backdrop-filter:blur(4px);z-index:1055;">
            <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header text-white rounded-top-4" style="background:linear-gradient(135deg,#1d4ed8,#2563eb,#0ea5e9);">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">{{ $editingPartId ? 'edit' : 'add_circle' }}</i>
                            {{ $editingPartId ? 'ویرایش پارت' : 'افزودن پارت جدید' }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closePartModal"></button>
                    </div>
                    <div class="modal-body">
                        @if($showPartModeStep)
                            {{-- ====== مرحله انتخاب حالت پارت ====== --}}
                            <p class="text-muted small mb-3 d-flex align-items-center gap-1">
                                <i class="material-symbols-outlined" style="font-size:18px;">tune</i>
                                نوع پارت مورد نظر را انتخاب کنید:
                            </p>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <button type="button" wire:click="selectPartMode('normal')"
                                            class="w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-2 p-4 border rounded-4 bg-body text-reset">
                                        <i class="material-symbols-outlined text-primary" style="font-size:34px;">menu_book</i>
                                        <span class="fw-bold">حالت عادی</span>
                                        <span class="small text-muted text-center">درس و یک فصل مشخص</span>
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" wire:click="selectPartMode('whole_book')"
                                            class="w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-2 p-4 border rounded-4 bg-body text-reset">
                                        <i class="material-symbols-outlined text-success" style="font-size:34px;">auto_stories</i>
                                        <span class="fw-bold">کل کتاب</span>
                                        <span class="small text-muted text-center">فقط درس، بدون فصل</span>
                                    </button>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" wire:click="selectPartMode('review')"
                                            class="w-100 h-100 d-flex flex-column align-items-center justify-content-center gap-2 p-4 border rounded-4 bg-body text-reset">
                                        <i class="material-symbols-outlined text-warning" style="font-size:34px;">checklist</i>
                                        <span class="fw-bold">پارت مروری</span>
                                        <span class="small text-muted text-center">چند فصل با یک زمان کلی</span>
                                    </button>
                                </div>
                            </div>
                        @else
                            {{-- ====== نوار حالت انتخاب‌شده ====== --}}
                            @php
                                $modeMeta = [
                                    'normal'     => ['label' => 'حالت عادی', 'color' => 'primary', 'icon' => 'menu_book'],
                                    'whole_book' => ['label' => 'کل کتاب',   'color' => 'success', 'icon' => 'auto_stories'],
                                    'review'     => ['label' => 'پارت مروری','color' => 'warning', 'icon' => 'checklist'],
                                ][$partForm['part_mode']] ?? ['label' => 'حالت عادی', 'color' => 'primary', 'icon' => 'menu_book'];
                            @endphp
                            <div class="d-flex align-items-center justify-content-between mb-3 p-2 rounded-3 bg-body-tertiary border">
                            <span class="badge bg-{{ $modeMeta['color'] }}-subtle text-{{ $modeMeta['color'] }} d-flex align-items-center gap-1 px-3 py-2">
                                <i class="material-symbols-outlined" style="font-size:16px;">{{ $modeMeta['icon'] }}</i>
                                {{ $modeMeta['label'] }}
                            </span>
                                @if(!$editingPartId)
                                    <button type="button" wire:click="backToPartModeStep"
                                            class="btn btn-sm btn-link text-decoration-none d-flex align-items-center gap-1">
                                        <i class="material-symbols-outlined" style="font-size:16px;">swap_horiz</i>
                                        تغییر حالت
                                    </button>
                                @endif
                            </div>
                            {{-- جستجوی سریع --}}
                            <div class="mb-3 position-relative">
                                <label class="form-label fw-semibold">
                                    <i class="material-symbols-outlined text-primary align-middle">search</i> جستجوی سریع
                                </label>
                                <input type="text" wire:model.live.debounce.300ms="globalSearch"
                                       class="form-control" placeholder="نام درس، فصل یا مبحث..." autocomplete="off">
                                <div wire:loading wire:target="updatedGlobalSearch" class="position-absolute top-50 translate-middle-y" style="left:12px;">
                                    <span class="spinner-border spinner-border-sm text-primary"></span>
                                </div>
                                @if(count($globalSearchResults) > 0)
                                    <div class="list-group position-absolute w-100 mt-1 shadow rounded-3 border overflow-auto" style="z-index:1060;max-height:280px;">
                                        @foreach($globalSearchResults as $idx => $result)
                                            <button type="button" wire:click="selectGlobalResult({{ $idx }})"
                                                    class="list-group-item list-group-item-action py-2 px-3 d-flex align-items-center gap-2 flex-wrap">
                                                @if($result['type'] === 'topic')
                                                    <span class="badge bg-success-subtle text-success small">مبحث</span>
                                                @elseif($result['type'] === 'chapter')
                                                    <span class="badge bg-info-subtle text-info small">فصل</span>
                                                @else
                                                    <span class="badge bg-warning-subtle text-warning small">درس</span>
                                                @endif
                                                @if(!empty($result['grade_name']))
                                                    <span class="badge bg-primary-subtle text-primary small">پایه {{ $result['grade_name'] }}</span>
                                                @endif
                                                <span class="small text-truncate flex-fill">{{ $result['label'] }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                @endif
                                <small class="text-muted">با جستجو تمام فیلدها خودکار پر می‌شوند</small>
                            </div>
                            <hr>
                            {{-- دوره و پایه --}}
                            <div class="row g-3 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">دوره تحصیلی <span class="text-danger">*</span></label>
                                    <div wire:ignore>
                                        <select id="education-level-select" class="form-select select2-modal">
                                            <option value="">انتخاب کنید</option>
                                            @foreach($educationLevels as $level)
                                                <option value="{{ $level->id }}" {{ $partForm['education_level_id'] == $level->id ? 'selected' : '' }}>{{ $level->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">پایه تحصیلی <span class="text-danger">*</span></label>
                                    <div wire:ignore>
                                        <select id="grade-select" class="form-select select2-modal" {{ empty($grades) ? 'disabled' : '' }}>
                                            <option value="">{{ empty($grades) ? 'ابتدا دوره را انتخاب کنید' : 'انتخاب کنید' }}</option>
                                            @foreach($grades as $grade)
                                                <option value="{{ $grade->id }}" {{ $partForm['cc_grade_id'] == $grade->id ? 'selected' : '' }}>{{ $grade->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            {{-- درس --}}
                            <div class="row g-3 mb-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">درس <span class="text-danger">*</span></label>
                                    <div wire:ignore>
                                        <select id="subject-select" class="form-select select2-modal @error('partForm.cc_subject_id') is-invalid @enderror" {{ empty($subjects) ? 'disabled' : '' }}>
                                            <option value="">{{ empty($subjects) ? 'ابتدا پایه را انتخاب کنید' : 'انتخاب کنید' }}</option>
                                            @foreach($subjects as $subject)
                                                <option value="{{ $subject->id }}" {{ $partForm['cc_subject_id'] == $subject->id ? 'selected' : '' }}>
                                                    {{ $subject->name }} ({{ $subject->type === 'general' ? 'عمومی' : 'تخصصی' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('partForm.cc_subject_id')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                            </div>
                            {{-- فصل (بسته به حالت پارت) --}}
                            @if($partForm['part_mode'] === 'normal')
                                <div class="row g-3 mb-3">
                                    <div class="col-12">
                                        <label class="form-label fw-semibold">فصل <span class="text-danger">*</span></label>
                                        <div wire:ignore>
                                            <select id="chapter-select" class="form-select select2-modal @error('partForm.cc_chapter_id') is-invalid @enderror" {{ empty($chapters) ? 'disabled' : '' }}>
                                                <option value="">{{ empty($chapters) ? 'ابتدا درس را انتخاب کنید' : 'انتخاب کنید' }}</option>
                                                @foreach($chapters as $chapter)
                                                    <option value="{{ $chapter->id }}" {{ $partForm['cc_chapter_id'] == $chapter->id ? 'selected' : '' }}>{{ $chapter->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('partForm.cc_chapter_id')<div class="text-danger small">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            @elseif($partForm['part_mode'] === 'review')
                                {{-- پارت مروری: انتخاب چند فصل --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold d-flex align-items-center gap-1">
                                        فصل‌ها <span class="text-danger">*</span>
                                        <span class="badge bg-warning-subtle text-warning">{{ count($reviewChapterIds) }} انتخاب‌شده</span>
                                    </label>
                                    @if(empty($chapters))
                                        <div class="border rounded-3 p-3 text-center text-muted small bg-body-tertiary">
                                            ابتدا درس را انتخاب کنید
                                        </div>
                                    @else
                                        <div class="border rounded-3 p-2 overflow-auto @error('reviewChapterIds') border-danger @enderror" style="max-height:220px;">
                                            @foreach($chapters as $chapter)
                                                @php $isChecked = in_array($chapter->id, $reviewChapterIds); @endphp
                                                <label class="d-flex align-items-center gap-2 px-2 py-2 rounded-3 mb-1 {{ $isChecked ? 'bg-warning bg-opacity-10 border border-warning border-opacity-25' : '' }}" style="cursor:pointer;">
                                                    <input type="checkbox" class="form-check-input mt-0" wire:model.live="reviewChapterIds" value="{{ $chapter->id }}">
                                                    <span class="small flex-fill">{{ $chapter->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                    @endif
                                    @error('reviewChapterIds')<div class="text-danger small">{{ $message }}</div>@enderror
                                    <small class="text-muted">عنوان این پارت «پارت مروری» ثبت می‌شود و زمان واردشده، زمان کلی مرور است.</small>
                                </div>
                            @endif
                            {{-- توضیحات --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">توضیحات</label>
                                <textarea wire:model="partForm.description" rows="3" class="form-control" placeholder="مسیر انتخاب شده یا توضیحات دلخواه"></textarea>
                            </div>
                            {{-- نوع / زمان / تست --}}
                            @php $isModeNormal = ($partForm['part_mode'] ?? 'normal') === 'normal'; @endphp
                            <div class="row g-3"
                                 x-data="{
                                        isNormalMode: {{ $isModeNormal ? 'true' : 'false' }},
                                        partType: $wire.entangle('partForm.part_type'),
                                        totalMinutes: $wire.entangle('partForm.duration_minutes'),
                                        hours:0, minutes:0,
                                        get needsTest(){ return this.isNormalMode && ['test','topic_exam'].includes(this.partType); },
                                        get durCol(){ if(!this.isNormalMode) return 'col-12'; return this.needsTest ? 'col-md-4' : 'col-md-8'; },
                                        init(){ let v=parseInt(this.totalMinutes)||0; this.hours=Math.floor(v/60); this.minutes=v%60; this.$watch('totalMinutes',(v)=>{ let val=parseInt(v)||0; this.hours=Math.floor(val/60); this.minutes=val%60; }); },
                                        update(){ let h=Math.min(Math.max(parseInt(this.hours)||0,0),24); let m=Math.min(Math.max(parseInt(this.minutes)||0,0),59); this.hours=h; this.minutes=m; this.totalMinutes=(h*60)+m; }
                                     }" x-init="init()">
                                @if($isModeNormal)
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">نوع پارت <span class="text-danger">*</span></label>
                                        <select x-model="partType" class="form-select">
                                            <option value="descriptive">تشریحی</option>
                                            <option value="test">تستی</option>
                                            <option value="video">ویدئو</option>
                                            <option value="topic_exam">آزمون مبحثی</option>
                                        </select>
                                    </div>
                                @endif
                                <div :class="durCol">
                                    <label class="form-label fw-semibold">مدت زمان <span class="text-danger">*</span></label>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="position-relative flex-fill">
                                            <input type="number" min="0" max="24" x-model.number="hours" @input="update()" class="form-control text-center" placeholder="0">
                                            <small class="position-absolute top-50 translate-middle-y text-muted" style="left:6px;font-size:10px;">ساعت</small>
                                        </div>
                                        <span class="fw-bold text-muted">:</span>
                                        <div class="position-relative flex-fill">
                                            <input type="number" min="0" max="59" x-model.number="minutes" @input="update()" class="form-control text-center" placeholder="0">
                                            <small class="position-absolute top-50 translate-middle-y text-muted" style="left:6px;font-size:10px;">دقیقه</small>
                                        </div>
                                    </div>
                                    <small class="text-muted" x-show="totalMinutes>0">
                                        مجموع:
                                        <span x-text="hours>0 ? hours + ' ساعت' + (minutes>0 ? ' و ' + minutes + ' دقیقه' : '') : minutes + ' دقیقه'"></span>
                                    </small>
                                    @error('partForm.duration_minutes')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                                @if($isModeNormal)
                                    <div class="col-md-4" x-show="needsTest" x-cloak>
                                        <label class="form-label fw-semibold">تعداد تست <span class="text-danger">*</span></label>
                                        <input type="number" min="1" wire:model="partForm.test_count" class="form-control" placeholder="تعداد تست">
                                        @error('partForm.test_count')<div class="text-danger small">{{ $message }}</div>@enderror
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer bg-body-tertiary rounded-bottom-4">
                        @if($showPartModeStep)
                            <button type="button" class="btn btn-outline-secondary" wire:click="closePartModal">انصراف</button>
                        @else
                            @if($editingPartId)
                                <button type="button" class="btn btn-outline-danger"
                                        wire:click="deletePart({{ $editingPartId }})"
                                        wire:confirm="آیا از حذف این پارت اطمینان دارید؟">حذف</button>
                            @endif
                            <button type="button" class="btn btn-outline-secondary" wire:click="closePartModal">انصراف</button>
                            <button type="button" class="btn btn-primary" wire:click="savePart">
                                <span wire:loading.remove wire:target="savePart">ذخیره</span>
                                <span wire:loading wire:target="savePart">در حال ذخیره...</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ====== MODAL: برنامه درسی جلسه قبلی ====== --}}
    @if($showPrevProgramModal)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(2,6,23,.65);backdrop-filter:blur(4px);z-index:1060;">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header text-white rounded-top-4" style="background:linear-gradient(135deg,#d97706,#f59e0b,#fbbf24);">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">history</i> برنامه درسی جلسه قبلی
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closePrevProgramModal"></button>
                    </div>
                    <div class="modal-body">
                        @if(count($prevSessionParts) > 0)
                            {{-- Sort --}}
                            <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                                <span class="small fw-semibold text-muted">مرتب‌سازی:</span>
                                <select wire:model.live="prevProgramSortField" class="form-select form-select-sm w-auto">
                                    <option value="day">روز</option>
                                    <option value="rating_desc">امتیاز: زیاد به کم</option>
                                    <option value="rating_asc">امتیاز: کم به زیاد</option>
                                </select>
                                <select wire:model.live="prevProgramFilterGrade" class="form-select form-select-sm w-auto">
                                    <option value="">همه پایه‌ها</option>
                                    <option value="12">دوازدهم</option>
                                    <option value="11">یازدهم</option>
                                    <option value="10">دهم</option>
                                    <option value="9">نهم</option>
                                </select>
                                <select wire:model.live="prevProgramFilterType" class="form-select form-select-sm w-auto">
                                    <option value="">عمومی/تخصصی (همه)</option>
                                    <option value="general">فقط عمومی</option>
                                    <option value="specialized">فقط تخصصی</option>
                                </select>
                                <select wire:model.live="prevProgramFilterDay" class="form-select form-select-sm w-auto">
                                    <option value="">همه روزها</option>
                                    @foreach(($prevWeekDays ?: $weekDays) as $wd)
                                        <option value="{{ $wd['index'] }}">{{ $wd['name'] }} ({{ $wd['jalali_date'] }})</option>
                                    @endforeach
                                </select>
                                <select wire:model.live="prevFilterStudyQuality" class="form-select form-select-sm w-auto">
                                    <option value="">امتیاز مطالعه (همه)</option>
                                    <option value="excellent">مطالعه عالی (8-10)</option>
                                    <option value="good">مطالعه با کیفیت (5-7)</option>
                                    <option value="poor">مطالعه بی‌کیفیت (زیر 5)</option>
                                </select>
                                <select wire:model.live="prevFilterStudyStatus" class="form-select form-select-sm w-auto">
                                    <option value="">وضعیت مطالعه (همه)</option>
                                    <option value="not_studied">ثبت نشده</option>
                                    <option value="no_rating">امتیاز داده نشده</option>
                                </select>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th style="width:36px;">#</th>
                                        <th>درس / فصل</th>
                                        <th class="text-center">روز</th>
                                        <th class="text-center">تایم</th>
                                        <th class="text-center">نوع</th>
                                        <th class="text-center">امتیاز</th>
                                        <th class="text-center">افزودن</th>
                                    </tr>
                                    </thead>

                                    <tbody>
                                    @forelse($filteredPrevParts as $idx => $pPart)
                                        <tr class="{{ isset($copiedFromPrevPartIds[$pPart['id']]) ? 'table-success bg-success bg-opacity-10' : '' }}">
                                            <td class="text-muted small">{{ $idx + 1 }}</td>
                                            <td>
                                                <div class="fw-semibold small">{{ $pPart['lesson_name'] }}</div>
                                                <div class="small text-muted">
                                                    @if($pPart['subject_name']){{ $pPart['subject_name'] }} › @endif
                                                    @if(($pPart['part_mode'] ?? 'normal') === 'review')
                                                        @if(!empty($pPart['review_chapters']))
                                                            @foreach($pPart['review_chapters'] as $rc)<span class="badge bg-warning-subtle text-warning" style="font-size:10px;">{{ $rc['name'] }}</span> @endforeach
                                                        @else
                                                            <span class="text-muted">بدون فصل</span>
                                                        @endif
                                                    @elseif(($pPart['part_mode'] ?? 'normal') === 'whole_book')
                                                        <span class="text-success">کل کتاب</span>
                                                    @elseif(!empty($pPart['chapter_name']))
                                                        {{ $pPart['chapter_name'] }}
                                                    @else
                                                        <span class="text-muted">بدون فصل</span>
                                                    @endif
                                                </div>
                                                @if($pPart['grade_label'])
                                                    <span class="badge bg-primary-subtle text-primary" style="font-size:10px;">{{ $pPart['grade_label'] }}</span>
                                                @endif
                                                <span class="badge bg-body-tertiary text-body border" style="font-size:10px;">{{ $pPart['lesson_type_label'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-body-tertiary text-body border rounded-pill small">{{ $pPart['day_name'] }}</span>
                                                @if(!empty($pPart['day_date']))
                                                    <div class="text-muted" style="font-size:10px;">{{ $pPart['day_date'] }}</div>
                                                @endif
                                            </td>
                                            <td class="text-center small fw-semibold">{{ $this->fmtDuration($pPart['duration_minutes']) }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-body-tertiary text-body border small">{{ $pPart['part_type_label'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                @if($pPart['avg_rating'] !== null)
                                                    <span class="fw-bold {{ $pPart['avg_rating']>=8?'text-success':($pPart['avg_rating']>=5?'text-info':'text-danger') }}">
                    {{ $pPart['avg_rating'] }}/10
                </span>
                                                    <span class="badge d-block mt-1 {{ $pPart['avg_rating']>=8?'bg-success-subtle text-success':($pPart['avg_rating']>=5?'bg-info-subtle text-info':'bg-danger-subtle text-danger') }}" style="font-size:10px;">
                    {{ $pPart['avg_label'] }}
                </span>
                                                @else
                                                    <span class="text-muted small">ثبت نشده</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                @if(isset($copiedFromPrevPartIds[$pPart['id']]))
                                                    <span class="badge bg-success text-white d-block mb-1" style="font-size:10px;">
                    <i class="material-symbols-outlined" style="font-size:11px;">check_circle</i> اضافه شده
                </span>
                                                    <button class="btn btn-sm btn-outline-danger w-100"
                                                            wire:click="revertAddedPrevPart({{ $pPart['id'] }})">
                                                        <i class="material-symbols-outlined" style="font-size:12px;">undo</i> برگشت
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline-warning"
                                                            wire:click="showPrevPartInlineAddForm({{ $pPart['id'] }})">
                                                        <i class="material-symbols-outlined" style="font-size:13px;">add_circle</i> افزودن
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                        {{-- Inline form زیر همین ردیف --}}
                                        @if($showPrevPartInlineForm && $prevPartInlineSelectedId === $pPart['id'])
                                            <tr class="bg-body-tertiary">
                                                <td colspan="7">
                                                    <div class="p-3 border rounded-3">
                                                        <div class="row g-3 align-items-start">
                                                            {{-- انتخاب روز (چند انتخابی) A1 --}}

                                                            <div class="col-md-3">
                                                                <label class="form-label small fw-semibold mb-1">روز(های) مقصد <span class="text-danger">*</span></label>
                                                                <div class="border rounded p-2" style="max-height:160px;overflow-y:auto;">
                                                                    @foreach($weekDays as $wd)
                                                                        @if(!$wd['is_rest_day'])
                                                                            <div class="form-check form-check-sm mb-1">
                                                                                <input class="form-check-input" type="checkbox"
                                                                                       wire:model="prevPartInlineForm.day_indices"
                                                                                       value="{{ $wd['index'] }}"
                                                                                       id="prev_day_{{ $wd['index'] }}_{{ $pPart['id'] }}">
                                                                                <label class="form-check-label small" for="prev_day_{{ $wd['index'] }}_{{ $pPart['id'] }}">
                                                                                    {{ $wd['name'] }} <span class="text-muted">({{ $wd['jalali_date'] }})</span>
                                                                                </label>
                                                                            </div>
                                                                        @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label small fw-semibold mb-1">نوع پارت</label>
                                                                <select class="form-select form-select-sm" wire:model.live="prevPartInlineForm.part_type">
                                                                    <option value="descriptive">تشریحی</option>
                                                                    <option value="test">تستی</option>
                                                                    <option value="video">ویدئو</option>
                                                                    <option value="topic_exam">آزمون مبحثی</option>
                                                                </select>
                                                                @if(in_array($prevPartInlineForm['part_type'] ?? 'descriptive', ['test','topic_exam']))
                                                                    <label class="form-label small fw-semibold mb-1 mt-2">تعداد تست</label>
                                                                    <input type="number" min="1" class="form-control form-control-sm text-center"
                                                                           wire:model="prevPartInlineForm.test_count" placeholder="0">
                                                                @endif
                                                            </div>
                                                            <div class="col-md-1">
                                                                <label class="form-label small fw-semibold mb-1">ساعت</label>
                                                                <input type="number" min="0" max="24" class="form-control form-control-sm text-center"
                                                                       wire:model="prevPartInlineForm.duration_hours" placeholder="0">
                                                            </div>
                                                            <div class="col-md-1">
                                                                <label class="form-label small fw-semibold mb-1">دقیقه</label>
                                                                <input type="number" min="0" max="59" class="form-control form-control-sm text-center"
                                                                       wire:model="prevPartInlineForm.duration_minutes" placeholder="0">
                                                            </div>
                                                            <div class="col d-flex gap-2">
                                                                {{-- توضیحات (A2) --}}
                                                                <div class="col-md-3">
                                                                    <label class="form-label small fw-semibold mb-1">توضیحات <span class="text-muted">(اختیاری)</span></label>
                                                                    <textarea class="form-control form-control-sm" rows="3"
                                                                              wire:model="prevPartInlineForm.description"
                                                                              placeholder="در صورت خالی بودن، توضیحات پارت قبلی استفاده می‌شود"></textarea>
                                                                </div>
                                                                <div class="col-md-2 d-flex flex-column gap-2 justify-content-end">
                                                                    <button type="button" class="btn btn-sm btn-warning text-white"
                                                                            wire:click="addSinglePrevPartToProgram">
                                                                            <span wire:loading.remove wire:target="addSinglePrevPartToProgram">
                                                                                <i class="material-symbols-outlined" style="font-size:13px;">check</i> ثبت
                                                                            </span>

                                                                        <span wire:loading wire:target="addSinglePrevPartToProgram">...</span>
                                                                    </button>
                                                                    <button type="button" class="btn btn-sm btn-outline-secondary"
                                                                            wire:click="hidePrevPartInlineAddForm">
                                                                        <i class="material-symbols-outlined" style="font-size:13px;">close</i> لغو
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center text-muted py-4">
                                                <i class="material-symbols-outlined d-block mb-2" style="font-size:40px;">filter_list_off</i>
                                                موردی یافت نشد
                                            </td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>


                        @else
                            <div class="text-center py-5">
                                <i class="material-symbols-outlined text-muted" style="font-size:48px;">inbox</i>
                                <p class="text-muted mt-2">برنامه‌ای برای جلسه قبلی ثبت نشده</p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer bg-body-tertiary rounded-bottom-4">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closePrevProgramModal">بستن</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ====== MODAL: گزارش / ساعت مطالعه جلسه قبلی ====== --}}
    @if($showPrevReportModal)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(2,6,23,.65);backdrop-filter:blur(4px);z-index:1062;">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header text-white rounded-top-4"
                         style="background:{{ $prevReportType === 'study' ? 'linear-gradient(135deg,#059669,#10b981)' : 'linear-gradient(135deg,#7c3aed,#a855f7)' }};">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">{{ $prevReportType === 'study' ? 'schedule' : 'summarize' }}</i>
                            {{ $prevReportData['type_label'] ?? '' }} — جلسه قبلی
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closePrevReportModal"></button>
                    </div>
                    <div class="modal-body">
                        @if(!empty($prevReportData))
                            {{-- Info --}}
                            <div class="d-flex flex-wrap gap-3 mb-3 small">
                                <span>تاریخ جلسه: <strong>{{ $prevReportData['session_date'] ?? '—' }}</strong></span>
                                <span class="badge bg-success text-white">{{ $prevReportData['result_status'] ?? '' }}</span>
                                @if($prevReportType === 'study' && !empty($prevReportData['total_label']))
                                    <span>جمع کل: <strong class="text-primary">{{ $prevReportData['total_label'] }}</strong></span>
                                @endif
                            </div>

                            {{-- Summary --}}
                            <div class="row g-3 mb-3">
                                @if($prevReportType === 'report')
                                    <div class="col-6 col-md-3">
                                        <div class="rounded-3 border bg-success bg-opacity-10 text-center p-3">
                                            <div class="fw-bold fs-4 text-success">{{ $prevReportData['sent_days_count'] ?? 0 }}</div>
                                            <div class="small text-success">روز ارسال شده</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="rounded-3 border bg-danger bg-opacity-10 text-center p-3">
                                            <div class="fw-bold fs-4 text-danger">{{ $prevReportData['not_sent_days_count'] ?? 0 }}</div>
                                            <div class="small text-danger">روز ارسال نشده</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="rounded-3 border bg-body-tertiary text-center p-3">
                                            <div class="fw-bold fs-4 text-secondary">{{ $prevReportData['rest_days_count'] ?? 0 }}</div>
                                            <div class="small text-secondary">روز استراحت</div>
                                        </div>
                                    </div>
                                @elseif($prevReportType === 'study')
                                    <div class="col-6 col-md-3">
                                        <div class="rounded-3 border bg-primary bg-opacity-10 text-center p-3">
                                            <div class="fw-bold fs-4 text-primary">{{ $prevReportData['total_assigned_parts'] ?? 0 }}</div>
                                            <div class="small text-primary">پارت برنامه</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="rounded-3 border bg-success bg-opacity-10 text-center p-3">
                                            <div class="fw-bold fs-4 text-success">{{ $prevReportData['total_done_parts'] ?? 0 }}</div>
                                            <div class="small text-success">پارت ثبت شده</div>
                                        </div>
                                    </div>
                                    <div class="col-6 col-md-3">
                                        <div class="rounded-3 border bg-danger bg-opacity-10 text-center p-3">
                                            <div class="fw-bold fs-4 text-danger">{{ max(0, ($prevReportData['total_assigned_parts'] ?? 0) - ($prevReportData['total_done_parts'] ?? 0)) }}</div>
                                            <div class="small text-danger">پارت ثبت نشده</div>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            @if(!empty($prevReportData['items']))
                                @if($prevReportType === 'study')
                                    <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                                        <span class="small fw-semibold text-muted">مرتب‌سازی:</span>

                                        <select wire:model.live="prevStudySortField" class="form-select form-select-sm w-auto">
                                            <option value="day">روز</option>
                                            <option value="rating_desc">امتیاز: زیاد به کم</option>
                                            <option value="rating_asc">امتیاز: کم به زیاد</option>
                                            <option value="not_registered">ثبت نشده اول</option>
                                        </select>

                                        <select wire:model.live="prevStudyFilterTypeField" class="form-select form-select-sm w-auto">
                                            <option value="">عمومی/تخصصی (همه)</option>
                                            <option value="general">فقط عمومی</option>
                                            <option value="specialized">فقط تخصصی</option>
                                        </select>

                                        <select wire:model.live="prevStudyFilterDayField" class="form-select form-select-sm w-auto">
                                            <option value="">همه روزها</option>
                                            @foreach(($prevWeekDays ?: $weekDays) as $wd)
                                                <option value="{{ $wd['index'] }}">{{ $wd['name'] }} ({{ $wd['jalali_date'] }})</option>

                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                                <div class="table-responsive">
                                    <table class="table table-hover align-middle mb-0 small">
                                        <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>روز</th>
                                            <th>تاریخ</th>
                                            @if($prevReportType === 'study')
                                                <th>درس</th>
                                                <th class="text-center">برنامه</th>
                                                <th class="text-center">واقعی</th>
                                                <th class="text-center">ساعت</th>
                                                <th class="text-center">امتیاز</th>
                                                <th>بازخورد</th>
                                            @else
                                                <th class="text-center">پارت انجام شده</th>
                                                <th>وضعیت</th>
                                                <th>علت عدم انجام</th>
                                            @endif
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach(($prevReportType === 'study' ? $filteredStudyItems : ($prevReportData['items'] ?? [])) as $i => $item)
                                            @php
                                                $isRestDay = $item['is_rest_day'] ?? false;
                                                $isSent = $item['is_sent'] ?? ($prevReportType === 'study' ? ($item['is_registered'] ?? false) : true);
                                                $rowClass = '';
                                                if($prevReportType === 'study') {
                                                    $rowClass = !$isSent ? 'table-secondary opacity-75' : (($item['is_suspicious'] ?? false) ? 'table-danger bg-danger bg-opacity-10' : '');
                                                } else {
                                                    if ($isRestDay) {
                                                        $rowClass = 'table-success';
                                                    } elseif (!$isSent) {
                                                        $rowClass = 'table-secondary opacity-75';
                                                    }
                                                }
                                            @endphp
                                            <tr class="{{ $rowClass }}">
                                                <td class="text-muted">{{ $i + 1 }}</td>
                                                <td>
                                                    <span class="badge rounded-pill {{ $isRestDay ? 'bg-success text-white' : ($isSent ? 'bg-body-tertiary text-body border' : 'bg-danger-subtle text-danger') }}">
                                                        {{ $item['day_name'] }}
                                                    </span>
                                                </td>
                                                <td>{{ $item['date'] ?: '—' }}</td>
                                                @if($prevReportType === 'study')
                                                    <td>
                                                        <div class="fw-semibold">{{ $item['subject'] }}</div>
                                                        @if(!$isSent)
                                                            <span class="badge bg-danger-subtle text-danger" style="font-size:10px;">ثبت نشده</span>
                                                        @endif
                                                        @if(!empty($item['grade_label']))
                                                            <span class="badge bg-primary-subtle text-primary" style="font-size:10px;">{{ $item['grade_label'] }}</span>
                                                        @endif
                                                        @if(($item['is_early_finish'] ?? false) || ($item['extra_seconds'] ?? 0) > 0 || ($item['is_cheating'] ?? false))
                                                            <div class="mt-1">
                                                                <x-study-session-badges
                                                                    :is-early-finish="(bool)($item['is_early_finish'] ?? false)"
                                                                    :extra-seconds="(int)($item['extra_seconds'] ?? 0)"
                                                                    :extra-target-seconds="(int)($item['extra_target_seconds'] ?? 0)"
                                                                    :is-cheating="(bool)($item['is_cheating'] ?? false)"
                                                                    :cheat-status="$item['cheat_status'] ?? null"
                                                                    :cheat-minutes="(int)($item['cheat_minutes'] ?? 0)"
                                                                    style="bootstrap" />
                                                            </div>
                                                        @endif
                                                        @if(!empty($item['cheat_reason']))
                                                            <div class="small text-muted mt-1" style="font-size:10px;" title="{{ $item['cheat_reason'] }}">
                                                                علت: {{ Str::limit($item['cheat_reason'], 40) }}
                                                            </div>
                                                        @endif
                                                        @if(($item['extra_started_at'] ?? null) && ($item['extra_ended_at'] ?? null))
                                                            <div class="small text-muted mt-1" style="font-size:10px;">
                                                                اضافه: {{ $item['extra_started_at'] }} — {{ $item['extra_ended_at'] }}
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">{{ $item['planned_minutes'] ?? 0 }} دقیقه</td>
                                                    <td class="text-center {{ ($item['is_suspicious'] ?? false) ? 'text-danger fw-bold' : '' }}">
                                                        {{ $item['duration_label'] ?? '—' }}
                                                        @if(($item['extra_seconds'] ?? 0) > 0)
                                                            <div class="small" style="color:#7c3aed; font-size:10px;">
                                                                +{{ $item['extra_minutes'] }}د اضافه بر مشاور
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if(!empty($item['started_at']))
                                                            {{ $item['started_at'] }}@if(!empty($item['ended_at'])) — {{ $item['ended_at'] }}@endif
                                                        @else
                                                            <span class="text-muted">—</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-center">
                                                        @if(isset($item['rating']) && $item['rating'] !== null)
                                                            @php $rc = $item['rating'] >= 8 ? 'success' : ($item['rating'] >= 5 ? 'info' : 'danger'); @endphp
                                                            <span class="badge bg-{{ $rc }}-subtle text-{{ $rc }} fw-bold">{{ $item['rating'] }}/10</span>
                                                        @else
                                                            <span class="badge bg-warning-subtle text-warning">—</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-muted">{{ Str::limit($item['feedback'] ?? '', 50) }}</td>
                                                @else
                                                    <td class="text-center fw-bold">
                                                        @if($isRestDay)
                                                            <span class="badge bg-success-subtle text-success">
                                                                        <i class="material-symbols-outlined" style="font-size:12px;">self_improvement</i>
                                                                        استراحت
                                                                    </span>
                                                        @elseif($isSent)
                                                            <span class="{{ ($item['parts_done'] ?? 0) >= ($item['parts_total'] ?? 0) ? 'text-success' : 'text-warning' }}">
                                                                        {{ $item['parts_done'] ?? 0 }}/{{ $item['parts_total'] ?? 0 }}
                                                                    </span>
                                                        @else
                                                            <span class="text-muted">ارسال نشده</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($isRestDay)
                                                            <span class="badge bg-success-subtle text-success">روز استراحت</span>
                                                        @elseif($isSent)
                                                            <span class="badge bg-{{ $item['status_color'] ?? 'warning' }}-subtle text-{{ $item['status_color'] ?? 'warning' }}">{{ $item['status_label'] ?? '—' }}</span>
                                                        @else
                                                            <span class="badge bg-danger-subtle text-danger">ارسال نشده</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-muted small">{{ $isRestDay ? '' : Str::limit($item['missed_reason'] ?? '', 80) }}</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        @endif
                    </div>
                    <div class="modal-footer bg-body-tertiary rounded-bottom-4">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closePrevReportModal">بستن</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ====== MODAL: طبقه‌بندی ====== --}}
    @if($showClassificationModal)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(2,6,23,.6);backdrop-filter:blur(4px);z-index:1058;">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header text-white rounded-top-4" style="background:linear-gradient(135deg,#475569,#64748b,#94a3b8);">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">category</i>
                            طبقه‌بندی مباحث
                            @if($classificationProjectName)
                                <span class="badge bg-white bg-opacity-25 fw-normal small">{{ $classificationProjectName }}</span>
                            @endif
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeClassificationModal"></button>
                    </div>
                    <div class="modal-body">
                        @if(count($classificationTopics) > 0)
                            {{-- Sort --}}
                            <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
                                <span class="small fw-semibold text-muted">مرتب‌سازی:</span>
                                <select wire:model.live="classificationSort" class="form-select form-select-sm w-auto">
                                    <option value="rating">رتبه (زیاد به کم)</option>
                                    <option value="grade">پایه</option>
                                    <option value="lesson_type">عمومی/تخصصی</option>
                                </select>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>درس</th>
                                        <th>فصل / عنوان</th>
                                        <th class="text-center">پایه</th>
                                        <th class="text-center">نوع</th>
                                        <th class="text-center">رتبه</th>
                                        <th class="text-center">عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($classificationTopics as $idx => $item)
                                        <tr class="{{ $item['is_added'] ? 'table-success bg-success bg-opacity-10' : '' }}">
                                            <td class="text-muted small">{{ $idx + 1 }}</td>
                                            <td class="fw-semibold small">{{ $item['subject_name'] }}</td>
                                            <td class="small text-muted">{{ $item['kind'] === 'chapter' ? $item['item_name'] : 'کل درس عمومی' }}</td>
                                            <td class="text-center">
                                                            <span class="badge bg-primary-subtle text-primary" style="font-size:10px;">
                                                                @if($item['grade'] == 12)
                                                                    دوازدهم
                                                                @elseif($item['grade'] == 11)
                                                                    یازدهم
                                                                @elseif($item['grade'] == 10)
                                                                    دهم
                                                                @endif

                                                            </span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-body-tertiary text-body border" style="font-size:10px;">{{ $item['lesson_type_label'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-{{ $item['rating_color'] }}-subtle text-{{ $item['rating_color'] }} fw-bold">{{ $item['rating_label'] }}</span>
                                            </td>
                                            <td class="text-center">
                                                <div class="d-flex gap-1 justify-content-center flex-wrap">
                                                    @if($item['is_added'])
                                                        <span class="badge bg-success text-white d-block mb-1" style="font-size:10px;">
                                                                    <i class="material-symbols-outlined" style="font-size:11px;">check_circle</i> اضافه شده
                                                                </span>
                                                        <button type="button" class="btn btn-sm btn-outline-danger w-100 mb-1"
                                                                wire:click="revertClassificationPart({{ $item['ratable_id'] }}, '{{ $item['kind'] }}')">
                                                                    <span wire:loading.remove wire:target="revertClassificationPart">
                                                                        <i class="material-symbols-outlined" style="font-size:12px;">undo</i> برگشت
                                                                    </span>
                                                            <span wire:loading wire:target="revertClassificationPart">...</span>
                                                        </button>
                                                    @else
                                                        <button type="button" class="btn btn-sm btn-outline-primary"
                                                                wire:click="showClassificationInlineAdd({{ $item['ratable_id'] }}, '{{ $item['kind'] }}')">
                                                            <i class="material-symbols-outlined" style="font-size:12px;">add_circle</i> افزودن
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                        {{-- Inline Add --}}
                                        @if($showClassificationAddForm && $classificationSelectedTopicId === $item['ratable_id'] && $classificationSelectedKind === $item['kind'])
                                            <tr class="bg-body-tertiary">
                                                <td colspan="7">
                                                    <div class="p-3 rounded-3 border">
                                                        <div class="row g-3 align-items-start">
                                                            {{-- انتخاب روز (چند انتخابی) A1 --}}
                                                            <div class="col-md-3">
                                                                <label class="form-label small fw-semibold mb-1">روز(های) هدف <span class="text-danger">*</span></label>
                                                                <div class="border rounded p-2" style="max-height:160px;overflow-y:auto;">

                                                                    @foreach($weekDays as $wd)
                                                                        @if(!$wd['is_rest_day'])
                                                                            <div class="form-check form-check-sm mb-1">
                                                                                <input class="form-check-input" type="checkbox"
                                                                                       wire:model="classificationAddForm.day_indices"
                                                                                       value="{{ $wd['index'] }}"
                                                                                       id="clf_day_{{ $wd['index'] }}_{{ $item['key'] }}">
                                                                                <label class="form-check-label small" for="clf_day_{{ $wd['index'] }}_{{ $item['key'] }}">
                                                                                    {{ $wd['name'] }} <span class="text-muted">({{ $wd['jalali_date'] }})</span>
                                                                                </label>
                                                                            </div>                                                                                @endif
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                            <div class="col-md-2">
                                                                <label class="form-label small fw-semibold mb-1">نوع</label>
                                                                <select class="form-select form-select-sm" wire:model.live="classificationAddForm.part_type">
                                                                    <option value="descriptive">تشریحی</option>
                                                                    <option value="test">تستی</option>
                                                                    <option value="video">ویدئو</option>
                                                                    <option value="topic_exam">آزمون مبحثی</option>
                                                                </select>
                                                                @if(in_array($classificationAddForm['part_type'] ?? '', ['test','topic_exam']))
                                                                    <label class="form-label small fw-semibold mb-1 mt-2">تعداد تست</label>
                                                                    <input type="number" min="0" class="form-control form-control-sm text-center" wire:model="classificationAddForm.test_count">
                                                                @endif
                                                            </div>
                                                            <div class="col-md-1">
                                                                <label class="form-label small fw-semibold mb-1">ساعت</label>
                                                                <input type="number" min="0" max="24" class="form-control form-control-sm text-center" wire:model="classificationAddForm.duration_hours">
                                                            </div>
                                                            <div class="col-md-1">
                                                                <label class="form-label small fw-semibold mb-1">دقیقه</label>
                                                                <input type="number" min="0" max="59" class="form-control form-control-sm text-center" wire:model="classificationAddForm.duration_minutes">
                                                            </div>
                                                            {{-- توضیحات (A2) --}}
                                                            <div class="col-md-3">
                                                                <label class="form-label small fw-semibold mb-1">توضیحات <span class="text-muted">(اختیاری)</span></label>
                                                                <textarea class="form-control form-control-sm" rows="3"
                                                                          wire:model="classificationAddForm.description"
                                                                          placeholder="در صورت خالی بودن، مسیر مبحث استفاده می‌شود"></textarea>
                                                            </div>
                                                            <div class="col-md-2 d-flex flex-column gap-2 justify-content-end">
                                                                <button type="button" class="btn btn-sm btn-success" wire:click="addClassificationToProgram">

                                                                    <span wire:loading.remove wire:target="addClassificationToProgram"><i class="material-symbols-outlined" style="font-size:13px;">check</i> ثبت</span>
                                                                    <span wire:loading wire:target="addClassificationToProgram">...</span>
                                                                </button>
                                                                <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="hideClassificationInlineAdd">
                                                                    <i class="material-symbols-outlined" style="font-size:13px;">close</i> لغو
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center py-5">
                                <i class="material-symbols-outlined text-muted" style="font-size:56px;">category</i>
                                <p class="text-muted mt-2">
                                    {{ $classificationProjectName ? 'هیچ مبحثی برای این دانش‌آموز در پروژه «'.$classificationProjectName.'» ثبت نشده' : 'پروژه طبقه‌بندی فعالی یافت نشد' }}
                                </p>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer bg-body-tertiary rounded-bottom-4">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeClassificationModal">بستن</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ====== MODAL: برنامه کلاسی ====== --}}
    @if($showClassScheduleModal && $classScheduleData['schedule'])
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(2,6,23,.6);backdrop-filter:blur(4px);z-index:1056;">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header text-white rounded-top-4" style="background:linear-gradient(135deg,#7c3aed,#a855f7,#c084fc);">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">menu_book</i> برنامه کلاسی دانش‌آموز
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeClassScheduleModal"></button>
                    </div>
                    <div class="modal-body">
                        {{-- جدول برنامه کلاسی --}}
                        <div class="table-responsive mb-4">
                            <table class="table table-bordered align-middle mb-0">
                                <thead>
                                <tr style="background:linear-gradient(90deg,#7c3aed,#a855f7);">
                                    <th class="text-center text-white fw-bold" style="width:110px;">روز</th>
                                    @for($p = 1; $p <= 5; $p++)
                                        <th class="text-center text-white fw-bold">پارت {{ $p }}</th>
                                    @endfor
                                </tr>
                                </thead>
                                <tbody>
                                @for($d = 0; $d < 7; $d++)
                                    <tr>
                                        <td class="text-center">
                                            <span class="badge rounded-pill {{ in_array($d,[5,6]) ? 'bg-secondary' : 'bg-primary' }} fw-bold">
                                                {{ \App\Models\ClassSchedule::getDayName($d) }}
                                            </span>
                                        </td>
                                        @for($p = 1; $p <= 5; $p++)
                                            @php $part = $classScheduleData['days'][$d]['parts']->where('part_order',$p)->first(); @endphp
                                            <td class="text-center">
                                                @if($part)
                                                    <span class="badge bg-success-subtle text-success rounded-pill px-2">{{ $part->lesson_name }}</span>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                        @endfor
                                    </tr>
                                @endfor
                                </tbody>
                            </table>
                        </div>

                        <hr>
                        {{-- پیش‌خوانی / روزخوانی --}}
                        <div class="mb-3">
                            <p class="small text-muted mb-2">
                                <i class="material-symbols-outlined align-middle" style="font-size:15px;">info</i>
                                پیش‌خوانی و روزخوانی بر اساس برنامه کلاسی و تایم‌های جلسه قبلی محاسبه می‌شوند.
                            </p>

                            @if(empty($weeklyReadingsPreview))
                                <button wire:click="previewWeeklyReadings" class="btn btn-outline-secondary d-flex align-items-center gap-2">
                                    <i class="material-symbols-outlined" style="font-size:18px;">refresh</i> بارگذاری پیش‌نمایش
                                    <span wire:loading wire:target="previewWeeklyReadings"><span class="spinner-border spinner-border-sm"></span></span>
                                </button>
                            @else
                                <div class="d-flex align-items-center justify-content-between mb-2 flex-wrap gap-2">
                                    <h6 class="fw-bold mb-0">تایم پیش‌خوانی و روزخوانی</h6>
                                    <div class="d-flex gap-2 align-items-center">
                                        <select wire:model.live="readingTypeFilter" class="form-select form-select-sm w-auto">
                                            <option value="">همه</option>
                                            <option value="daily">روزخوانی</option>
                                            <option value="pre">پیش‌خوانی</option>
                                        </select>
                                        <button wire:click="previewWeeklyReadings" class="btn btn-sm btn-outline-secondary">
                                            <i class="material-symbols-outlined" style="font-size:15px;">refresh</i>
                                            <span wire:loading wire:target="previewWeeklyReadings"><span class="spinner-border spinner-border-sm"></span></span>
                                        </button>
                                    </div>
                                </div>

                                <div class="alert alert-info small py-2 mb-2">
                                    <i class="material-symbols-outlined align-middle" style="font-size:14px;">info</i>
                                    برای هر درس یک تایم وارد کنید. اگر تایم ۰ باشد، آن درس اضافه نمی‌شود.
                                </div>

                                <div class="table-responsive mb-3" style="max-height:300px;overflow-y:auto;">
                                    <table class="table table-sm align-middle mb-0">
                                        <thead class="table-light sticky-top">
                                        <tr>
                                            <th>نوع</th><th>درس</th><th>روزهای اعمال</th><th class="text-center" style="width:90px;">دقیقه</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($weeklyReadingsPreview as $idx => $item)
                                            @if($readingTypeFilter === '' || $item['type'] === $readingTypeFilter)
                                                <tr>
                                                    <td>
                                                        <span class="badge {{ $item['type'] === 'daily' ? 'bg-primary-subtle text-primary' : 'bg-info-subtle text-info' }}">
                                                            {{ $item['type'] === 'daily' ? 'روزخوانی' : 'پیش‌خوانی' }}
                                                        </span>
                                                    </td>
                                                    <td class="fw-semibold small">{{ $item['subject'] }}</td>
                                                    <td>
                                                        <div class="d-flex flex-wrap gap-1">
                                                            @foreach($item['day_names'] as $dn)
                                                                <span class="badge bg-body-tertiary text-body border rounded-pill" style="font-size:10px;">{{ $dn }}</span>
                                                            @endforeach
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <input type="number" wire:model.lazy="weeklyReadingsPreview.{{ $idx }}.duration_minutes"
                                                               class="form-control form-control-sm text-center mx-auto {{ $item['duration_minutes'] == 0 ? 'border-warning' : '' }}"
                                                               min="0" max="300" style="width:70px;">
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="d-flex flex-wrap gap-2 justify-content-center">
                                    @if($this->hasDailyReadings())
                                        <button wire:click="revertDailyReadings" wire:confirm="آیا مطمئنید؟" class="btn btn-outline-danger d-flex align-items-center gap-1">
                                            <span wire:loading.remove wire:target="revertDailyReadings"><i class="material-symbols-outlined" style="font-size:17px;">undo</i> بازگرداندن روزخوانی</span>
                                            <span wire:loading wire:target="revertDailyReadings">...</span>
                                        </button>
                                    @else
                                        <button wire:click="applyOnlyDailyReadings" class="btn btn-outline-primary d-flex align-items-center gap-1">
                                            <span wire:loading.remove wire:target="applyOnlyDailyReadings"><i class="material-symbols-outlined" style="font-size:17px;">today</i> افزودن روزخوانی</span>
                                            <span wire:loading wire:target="applyOnlyDailyReadings">...</span>
                                        </button>
                                    @endif
                                    @if($this->hasPreReadings())
                                        <button wire:click="revertPreReadings" wire:confirm="آیا مطمئنید؟" class="btn btn-outline-danger d-flex align-items-center gap-1">
                                            <span wire:loading.remove wire:target="revertPreReadings"><i class="material-symbols-outlined" style="font-size:17px;">undo</i> بازگرداندن پیش‌خوانی</span>
                                            <span wire:loading wire:target="revertPreReadings">...</span>
                                        </button>
                                    @else
                                        <button wire:click="applyOnlyPreReadings" class="btn btn-outline-info d-flex align-items-center gap-1">
                                            <span wire:loading.remove wire:target="applyOnlyPreReadings"><i class="material-symbols-outlined" style="font-size:17px;">upcoming</i> افزودن پیش‌خوانی</span>
                                            <span wire:loading wire:target="applyOnlyPreReadings">...</span>
                                        </button>
                                    @endif
                                    <button wire:click="applyWeeklyReadings" class="btn btn-outline-success d-flex align-items-center gap-1">
                                        <span wire:loading.remove wire:target="applyWeeklyReadings"><i class="material-symbols-outlined" style="font-size:17px;">auto_fix_high</i> افزودن هر دو</span>
                                        <span wire:loading wire:target="applyWeeklyReadings">...</span>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer bg-body-tertiary rounded-bottom-4">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeClassScheduleModal">بستن</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ====== MODAL: اختصاص آزمون تستی / تشریحی ====== --}}
    @if($showExamAssignmentModal)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(2,6,23,.6);backdrop-filter:blur(4px);z-index:1060;">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header text-white rounded-top-4" style="background:{{ $examAssignmentType === 'typed' ? 'linear-gradient(135deg,#2563eb,#1d4ed8,#0ea5e9)' : 'linear-gradient(135deg,#d97706,#f59e0b,#fbbf24)' }};">
                        <div>
                            <h5 class="modal-title d-flex align-items-center gap-2 mb-1">
                                <i class="material-symbols-outlined">{{ $examAssignmentType === 'typed' ? 'check_box' : 'description' }}</i>
                                {{ $examAssignmentType === 'typed' ? 'اختصاص آزمون تستی' : 'اختصاص آزمون تشریحی' }}
                            </h5>
                            <small class="text-white-50">
                                مرحله {{ $examAssignmentStep }} از 2
                                @if($selectedExamForAssignment)
                                    - {{ $selectedExamForAssignment->title }}
                                @endif
                            </small>
                        </div>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeExamAssignmentModal"></button>
                    </div>
                    <div class="modal-body">
                        @if($examAssignmentStep === 1)
                            <div class="row g-3 mb-3">
                                <div class="col-lg-8">
                                    <label class="form-label fw-semibold">جستجوی آزمون</label>
                                    <input type="text" wire:model.live.debounce.300ms="examAssignmentSearch" class="form-control" placeholder="نام آزمون را جستجو کنید...">
                                </div>
                                <div class="col-lg-4">
                                    <label class="form-label fw-semibold">دانش‌آموز</label>
                                    <div class="form-control bg-body-tertiary">{{ $student->user->name ?? '---' }}</div>
                                </div>
                            </div>

                            @php
                                $examItems = $examAssignmentType === 'typed' ? $typedExamsForAssignment : $essayExamsForAssignment;
                                $assignedIds = $examAssignmentType === 'typed' ? $typedAssignedExamIds : $essayAssignedExamIds;
                            @endphp

                            <div class="row g-3">
                                @forelse($examItems as $exam)
                                    @php $isAssigned = in_array($exam->id, $assignedIds, true); @endphp
                                    <div class="col-md-6 col-xl-4">
                                        <button type="button"
                                                wire:click="selectExamAssignmentExam({{ $exam->id }})"
                                                class="w-100 text-start border rounded-4 p-3 h-100 bg-body {{ $isAssigned ? 'border-success border-opacity-50' : 'border-opacity-25' }}">
                                            <div class="d-flex align-items-start justify-content-between gap-2 mb-2">
                                                <h6 class="mb-0 fw-bold">{{ $exam->title }}</h6>
                                                @if($isAssigned)
                                                    <span class="badge bg-success-subtle text-success">قبلاً اختصاص داده شده</span>
                                                @endif
                                            </div>
                                            <div class="small text-muted d-flex flex-wrap gap-2">
                                                @if(isset($exam->questions_count))
                                                    <span>{{ $exam->questions_count }} سوال</span>
                                                @endif
                                                @if($examAssignmentType === 'typed' && isset($exam->difficulty_label))
                                                    <span>سطح {{ $exam->difficulty_label }}</span>
                                                @endif
                                            </div>
                                            <div class="small mt-3 text-primary fw-semibold d-flex align-items-center gap-1">
                                                <i class="material-symbols-outlined" style="font-size:16px;">arrow_back</i>
                                                انتخاب و ادامه
                                            </div>
                                        </button>
                                    </div>
                                @empty
                                    <div class="col-12">
                                        <div class="text-center py-5 text-muted border rounded-4 bg-body-tertiary">
                                            <i class="material-symbols-outlined d-block mb-2" style="font-size:36px;">search_off</i>
                                            آزمونی برای نمایش پیدا نشد.
                                        </div>
                                    </div>
                                @endforelse
                            </div>
                        @else
                            <div class="alert alert-info small d-flex align-items-center gap-2">
                                <i class="material-symbols-outlined" style="font-size:18px;">info</i>
                                زمان‌بندی آزمون را با تاریخ جلالی مشخص کنید.
                            </div>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">تاریخ شروع <span class="text-danger">*</span></label>
                                    <input type="text"
                                           id="exam_assignment_start_date"
                                           wire:model.lazy="examAssignmentForm.start_date_jalali"
                                           data-jdp data-jdp-only-date
                                           autocomplete="off"
                                           placeholder="1405/01/01"
                                           class="form-control text-center @error('examAssignmentForm.start_date_jalali') is-invalid @enderror"
                                           dir="ltr">
                                    @error('examAssignmentForm.start_date_jalali')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">تاریخ پایان <span class="text-danger">*</span></label>
                                    <input type="text"
                                           id="exam_assignment_end_date"
                                           wire:model.lazy="examAssignmentForm.end_date_jalali"
                                           data-jdp data-jdp-only-date
                                           autocomplete="off"
                                           placeholder="1405/01/08"
                                           class="form-control text-center @error('examAssignmentForm.end_date_jalali') is-invalid @enderror"
                                           dir="ltr">
                                    @error('examAssignmentForm.end_date_jalali')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">ساعت شروع <span class="text-danger">*</span></label>
                                    <input type="time" wire:model="examAssignmentForm.start_time" class="form-control @error('examAssignmentForm.start_time') is-invalid @enderror">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">ساعت پایان <span class="text-danger">*</span></label>
                                    <input type="time" wire:model="examAssignmentForm.end_time" class="form-control @error('examAssignmentForm.end_time') is-invalid @enderror">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">مدت آزمون (دقیقه) <span class="text-danger">*</span></label>
                                    <input type="number" min="1" max="1440" wire:model="examAssignmentForm.duration_minutes" class="form-control @error('examAssignmentForm.duration_minutes') is-invalid @enderror">
                                    @error('examAssignmentForm.duration_minutes')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">عنوان آزمون</label>
                                    <div class="form-control bg-body-tertiary">{{ $selectedExamForAssignment->title ?? '---' }}</div>
                                </div>

                                @if($examAssignmentType === 'typed')
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">زمان نمایش کارنامه</label>
                                        <select wire:model="examAssignmentForm.result_visibility" class="form-select">
                                            @foreach($visibilityOptions as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">زمان نمایش پاسخنامه</label>
                                        <select wire:model="examAssignmentForm.answer_key_visibility" class="form-select">
                                            @foreach($visibilityOptions as $value => $label)
                                                <option value="{{ $value }}">{{ $label }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer bg-body-tertiary rounded-bottom-4">
                        @if($examAssignmentStep === 2)
                            <button type="button" class="btn btn-outline-secondary" wire:click="backToExamAssignmentList">مرحله قبل</button>
                        @endif
                        <button type="button" class="btn btn-outline-dark" wire:click="closeExamAssignmentModal">انصراف</button>
                        @if($examAssignmentStep === 2)
                            <button type="button" class="btn {{ $examAssignmentType === 'typed' ? 'btn-primary' : 'btn-warning text-white' }}"
                                    wire:click="assignExamFromWeeklyProgram">
                                <span wire:loading.remove wire:target="assignExamFromWeeklyProgram">ثبت اختصاص</span>
                                <span wire:loading wire:target="assignExamFromWeeklyProgram">در حال ثبت...</span>
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ====== MODAL: آزمون جامع (افزودن/ویرایش) ====== --}}
    @if($showExamPartModal)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(2,6,23,.6);backdrop-filter:blur(4px);z-index:1060;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header text-white rounded-top-4" style="background:linear-gradient(135deg,#dc2626,#ef4444,#f87171);">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">{{ $editingExamPartId ? 'edit' : 'add_circle' }}</i>
                            {{ $editingExamPartId ? 'ویرایش آزمون' : 'افزودن آزمون جامع' }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeExamPartModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">نام آزمون <span class="text-danger">*</span></label>
                            <input type="text" wire:model="examPartForm.exam_name" class="form-control @error('examPartForm.exam_name') is-invalid @enderror" placeholder="مثال: آزمون جامع ریاضی">
                            @error('examPartForm.exam_name')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3" x-data="{
                            totalMinutes: $wire.entangle('examPartForm.duration_minutes'),
                            hours:0, minutes:0,
                            init(){ let v=parseInt(this.totalMinutes)||0; this.hours=Math.floor(v/60); this.minutes=v%60; this.$watch('totalMinutes',(v)=>{ let val=parseInt(v)||0; this.hours=Math.floor(val/60); this.minutes=val%60; }); },
                            update(){ let h=Math.min(Math.max(parseInt(this.hours)||0,0),24); let m=Math.min(Math.max(parseInt(this.minutes)||0,0),59); this.hours=h; this.minutes=m; this.totalMinutes=(h*60)+m; }
                        }" x-init="init()">
                            <label class="form-label fw-semibold">مدت زمان <span class="text-danger">*</span></label>
                            <div class="d-flex gap-2 align-items-center">
                                <div class="position-relative flex-fill">
                                    <input type="number" min="0" max="24" x-model.number="hours" @input="update()" class="form-control text-center" placeholder="0">
                                    <small class="position-absolute top-50 translate-middle-y text-muted" style="left:6px;font-size:10px;">ساعت</small>
                                </div>
                                <span class="fw-bold text-muted">:</span>
                                <div class="position-relative flex-fill">
                                    <input type="number" min="0" max="59" x-model.number="minutes" @input="update()" class="form-control text-center" placeholder="0">
                                    <small class="position-absolute top-50 translate-middle-y text-muted" style="left:6px;font-size:10px;">دقیقه</small>
                                </div>
                            </div>
                            @error('examPartForm.duration_minutes')<div class="text-danger small">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">توضیحات</label>
                            <textarea wire:model="examPartForm.description" rows="3" class="form-control" placeholder="توضیحات آزمون..."></textarea>
                        </div>
                        <div class="border rounded-4 p-3 bg-body-tertiary">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <i class="material-symbols-outlined text-warning">analytics</i>
                                <h6 class="mb-0 fw-bold">تحلیل آزمون</h6>
                            </div>
                            <div class="mb-3" x-data="{
                                totalMinutes: $wire.entangle('examPartForm.analysis_duration_minutes'),
                                hours:0, minutes:0,
                                init(){ let v=parseInt(this.totalMinutes)||0; this.hours=Math.floor(v/60); this.minutes=v%60; this.$watch('totalMinutes',(v)=>{ let val=parseInt(v)||0; this.hours=Math.floor(val/60); this.minutes=val%60; }); },
                                update(){ let h=Math.min(Math.max(parseInt(this.hours)||0,0),24); let m=Math.min(Math.max(parseInt(this.minutes)||0,0),59); this.hours=h; this.minutes=m; this.totalMinutes=(h*60)+m; }
                            }" x-init="init()">
                                <label class="form-label fw-semibold">مدت زمان تحلیل <span class="text-danger">*</span></label>
                                <div class="d-flex gap-2 align-items-center">
                                    <div class="position-relative flex-fill">
                                        <input type="number" min="0" max="24" x-model.number="hours" @input="update()" class="form-control text-center" placeholder="0">
                                        <small class="position-absolute top-50 translate-middle-y text-muted" style="left:6px;font-size:10px;">ساعت</small>
                                    </div>
                                    <span class="fw-bold text-muted">:</span>
                                    <div class="position-relative flex-fill">
                                        <input type="number" min="0" max="59" x-model.number="minutes" @input="update()" class="form-control text-center" placeholder="0">
                                        <small class="position-absolute top-50 translate-middle-y text-muted" style="left:6px;font-size:10px;">دقیقه</small>
                                    </div>
                                </div>
                                @error('examPartForm.analysis_duration_minutes')<div class="text-danger small">{{ $message }}</div>@enderror
                            </div>
                            <div>
                                <label class="form-label fw-semibold">توضیحات تحلیل</label>
                                <textarea wire:model="examPartForm.analysis_description" rows="3" class="form-control" placeholder="توضیحات تحلیل آزمون..."></textarea>
                            </div>
                        </div>
                        <div class="alert alert-info small py-2 mb-0">
                            <i class="material-symbols-outlined align-middle" style="font-size:14px;">info</i>
                            با ذخیره آزمون، پارت «تحلیل آزمون {{ $examPartForm['exam_name'] ?: '...' }}» هم ساخته یا به‌روزرسانی می‌شود.
                        </div>
                    </div>
                    <div class="modal-footer bg-body-tertiary rounded-bottom-4">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeExamPartModal">انصراف</button>
                        <button type="button" class="btn btn-danger" wire:click="saveExamPart">
                            <span wire:loading.remove wire:target="saveExamPart">ذخیره</span>
                            <span wire:loading wire:target="saveExamPart">در حال ذخیره...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ====== MODAL: انتخاب روز امتحان کلاسی ====== --}}
    @if($showExamDaySelectModal)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(2,6,23,.6);backdrop-filter:blur(4px);z-index:1060;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header text-white rounded-top-4" style="background:linear-gradient(135deg,#d97706,#f59e0b,#fbbf24);">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">event</i> انتخاب روز امتحان کلاسی
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeExamDaySelectModal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="fw-semibold mb-1">درس: {{ $examDaySelectData['subject'] ?? '' }}</p>
                        <p class="small text-muted mb-3">
                            {{ $examDaySelectData['part_count'] ?? 0 }} پارت — {{ $examDaySelectData['time_per_part'] ?? 0 }} دقیقه هر پارت
                            — تاریخ امتحان: {{ isset($examDaySelectData['exam_date']) ? jalali($examDaySelectData['exam_date'])->format('%d %B %Y') : '' }}
                        </p>
                        <div class="d-flex flex-wrap gap-2 mb-3 small">
                            <span class="d-flex align-items-center gap-1"><span class="badge bg-success">&nbsp;</span> قبل از امتحان</span>
                            <span class="d-flex align-items-center gap-1"><span class="badge bg-warning">&nbsp;</span> روز امتحان</span>
                            <span class="d-flex align-items-center gap-1"><span class="badge bg-danger">&nbsp;</span> بعد از امتحان</span>
                        </div>
                        <div class="d-flex flex-wrap gap-2">
                            @if(isset($weekDays))
                                @php $examDateCarbon = \Carbon\Carbon::parse($examDaySelectData['exam_date'] ?? null)->startOfDay(); @endphp
                                @foreach($weekDays as $day)
                                    @if(!$day['is_rest_day'])
                                        @php
                                            $dayDate = $day['date']->copy()->startOfDay();
                                            $isExamDate = $dayDate->isSameDay($examDateCarbon);
                                            $isBefore = $dayDate->lt($examDateCarbon);
                                        @endphp
                                        <button type="button"
                                                wire:click="$set('examDaySelectTarget', {{ $day['index'] }})"
                                                class="btn btn-sm {{ $examDaySelectTarget === $day['index'] ? 'btn-primary' : ($isExamDate ? 'btn-warning text-white' : ($isBefore ? 'btn-outline-success' : 'btn-outline-danger')) }}">
                                            {{ $day['name'] }}
                                            @if($isExamDate)<span class="badge bg-white text-warning ms-1" style="font-size:9px;">امتحان</span>@endif
                                        </button>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="modal-footer bg-body-tertiary rounded-bottom-4">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeExamDaySelectModal">انصراف</button>
                        <button type="button" class="btn btn-warning text-white" wire:click="applyExamToDay" {{ $examDaySelectTarget === null ? 'disabled' : '' }}>
                            <span wire:loading.remove wire:target="applyExamToDay"><i class="material-symbols-outlined" style="font-size:17px;">check</i> ثبت در برنامه</span>
                            <span wire:loading wire:target="applyExamToDay">...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ====== MODALS: توزیع (تکالیف / امتحان / پرسش و پاسخ) ====== --}}
    @foreach([
        ['show' => $showDistributeHomeworkModal, 'title' => 'توزیع تکالیف', 'color' => 'linear-gradient(135deg,#7c3aed,#a855f7,#c084fc)', 'icon' => 'assignment'],
        ['show' => $showDistributeExamModal, 'title' => 'توزیع امتحانات', 'color' => 'linear-gradient(135deg,#0ea5e9,#2563eb,#1d4ed8)', 'icon' => 'school'],
        ['show' => $showDistributeQaModal, 'title' => 'توزیع پرسش و پاسخ', 'color' => 'linear-gradient(135deg,#059669,#10b981,#34d399)', 'icon' => 'forum'],
    ] as $dist)
        @if($dist['show'])
            <div class="modal fade show d-block" tabindex="-1" style="background:rgba(2,6,23,.6);backdrop-filter:blur(4px);z-index:1060;">
                <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                    <div class="modal-content rounded-4 border-0 shadow">
                        <div class="modal-header text-white rounded-top-4" style="background:{{ $dist['color'] }};">
                            <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                                <i class="material-symbols-outlined">{{ $dist['icon'] }}</i> پیش‌نمایش {{ $dist['title'] }}
                            </h5>
                            <button type="button" class="btn-close btn-close-white" wire:click="closeDistributionModal"></button>
                        </div>
                        <div class="modal-body">
                            @if(count($distributionPreview) > 0)
                                <div class="alert alert-info small py-2 mb-3">
                                    <i class="material-symbols-outlined align-middle" style="font-size:14px;">info</i>
                                    با کلیک روی «اعمال» پارت‌ها در برنامه ثبت می‌شوند.
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered align-middle">
                                        <thead class="table-light">
                                        <tr><th>#</th><th>درس</th><th class="text-center">روز</th><th class="text-center">تاریخ</th><th class="text-center">دقیقه</th><th>توضیحات</th></tr>
                                        </thead>
                                        <tbody>
                                        @foreach($distributionPreview as $idx => $item)
                                            <tr>
                                                <td>{{ $idx+1 }}</td>
                                                <td class="fw-semibold small">{{ $item['subject'] }}</td>
                                                <td class="text-center"><span class="badge bg-primary rounded-pill">{{ $item['day_name'] }}</span></td>
                                                <td class="text-center small">{{ $item['jalali_date'] }}</td>
                                                <td class="text-center small">{{ $item['duration_minutes'] }}</td>
                                                <td class="small text-muted">{{ $item['description'] }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <p class="text-muted text-center">موردی برای نمایش وجود ندارد.</p>
                            @endif
                        </div>
                        <div class="modal-footer bg-body-tertiary rounded-bottom-4">
                            <button type="button" class="btn btn-outline-secondary" wire:click="closeDistributionModal">انصراف</button>
                            @if(count($distributionPreview) > 0)
                                <button type="button" class="btn btn-success" wire:click="applyDistribution">
                                    <span wire:loading.remove wire:target="applyDistribution"><i class="material-symbols-outlined" style="font-size:17px;">check</i> اعمال در برنامه</span>
                                    <span wire:loading wire:target="applyDistribution">در حال اعمال...</span>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endforeach

    {{-- ====== MODAL: تایید روز استراحت ====== --}}
    @if($showRestDayConfirmModal)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(2,6,23,.6);backdrop-filter:blur(4px);z-index:1065;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header bg-warning text-white rounded-top-4">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">warning</i> تایید روز استراحت
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeRestDayConfirmModal"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="material-symbols-outlined text-warning" style="font-size:56px;">self_improvement</i>
                        <h5 class="mb-3 mt-2">آیا مطمئن هستید؟</h5>
                        <p class="text-muted mb-0">
                            این روز <strong class="text-danger">{{ $partsCountForRestDay }}</strong> پارت دارد.
                            با تایید، تمام پارت‌ها حذف و روز به عنوان <span class="text-success fw-bold">استراحت</span> ثبت می‌شود.
                        </p>
                    </div>
                    <div class="modal-footer bg-body-tertiary rounded-bottom-4 justify-content-center gap-2">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeRestDayConfirmModal">انصراف</button>
                        <button type="button" class="btn btn-success" wire:click="confirmRestDay">
                            <i class="material-symbols-outlined" style="font-size:17px;">check</i> تایید
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ====== MODAL: تایید آزمون جامع ====== --}}
    @if($showExamDayConfirmModal)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(2,6,23,.6);backdrop-filter:blur(4px);z-index:1065;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header bg-danger text-white rounded-top-4">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">warning</i> تایید آزمون جامع
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeExamDayConfirmModal"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="material-symbols-outlined text-danger" style="font-size:56px;">assignment</i>
                        <h5 class="mb-3 mt-2">آیا مطمئن هستید؟</h5>
                        <p class="text-muted mb-0">
                            این روز <strong class="text-danger">{{ $partsCountForExamDay }}</strong> پارت دارد.
                            با تایید، تمام پارت‌ها حذف و روز به عنوان <span class="text-danger fw-bold">آزمون جامع</span> ثبت می‌شود.
                        </p>
                    </div>
                    <div class="modal-footer bg-body-tertiary rounded-bottom-4 justify-content-center gap-2">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeExamDayConfirmModal">انصراف</button>
                        <button type="button" class="btn btn-danger" wire:click="confirmExamDay">
                            <i class="material-symbols-outlined" style="font-size:17px;">check</i> تایید
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ====== MODAL: برنامه کلاسی وجود ندارد ====== --}}
    @if($showNoScheduleModal)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(2,6,23,.6);backdrop-filter:blur(4px);z-index:1060;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header bg-warning text-white rounded-top-4">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">warning</i> برنامه کلاسی
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeNoScheduleModal"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="material-symbols-outlined text-warning" style="font-size:56px;">event_busy</i>
                        <h5 class="mt-2 mb-2">برنامه کلاسی وجود ندارد</h5>
                        <p class="text-muted mb-0">دانش‌آموز هنوز برنامه کلاسی خود را آپلود نکرده است.</p>
                    </div>
                    <div class="modal-footer bg-body-tertiary rounded-bottom-4 justify-content-center gap-2">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeNoScheduleModal">بستن</button>
                        <button type="button" class="btn btn-warning text-white" wire:click="sendScheduleReminder">
                            <i class="material-symbols-outlined" style="font-size:17px;">notifications_active</i> اطلاع‌رسانی
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ====== MODAL: حذف گروهی ====== --}}
    @if($showBulkDeleteConfirmModal)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(2,6,23,.7);backdrop-filter:blur(4px);z-index:1070;"
             x-data="{}" @keydown.enter.window="$wire.executeBulkDelete()">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header text-white rounded-top-4" style="background:linear-gradient(135deg,#dc2626,#ef4444);">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">delete_forever</i> تأیید حذف گروهی
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeBulkDeleteConfirmModal"></button>
                    </div>
                    <div class="modal-body py-3">
                        <div class="text-center mb-3">
                            <i class="material-symbols-outlined text-danger" style="font-size:52px;">delete_sweep</i>
                        </div>
                        <p class="fw-semibold text-center mb-3">
                            <span class="text-danger fw-bold">{{ count($bulkDeleteSelectedIds) }}</span> پارت حذف خواهند شد.
                        </p>
                        @php
                            $confirmDeleteInfo = [];
                            foreach($weekDays as $wd) {
                                foreach($wd['parts'] as $wp) {
                                    if(in_array($wp->id, $bulkDeleteSelectedIds)) {
                                        $confirmDeleteInfo[] = ['part' => $wp, 'day_name' => $wd['name']];
                                    }
                                }
                            }
                        @endphp
                        <div class="border border-danger rounded-3 bg-danger bg-opacity-10" style="max-height:200px;overflow-y:auto;">
                            @foreach($confirmDeleteInfo as $info)
                                <div class="d-flex align-items-start gap-2 px-3 py-2 border-bottom border-danger border-opacity-25 small">
                                    <i class="material-symbols-outlined text-danger" style="font-size:14px;margin-top:1px;">delete</i>
                                    <div>
                                        <span class="fw-semibold">{{ $info['part']->lesson_name }}</span>
                                        <span class="badge bg-danger-subtle text-danger border d-block mt-1" style="font-size:10px;width:fit-content;">{{ $info['day_name'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer bg-body-tertiary rounded-bottom-4 justify-content-center gap-2">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeBulkDeleteConfirmModal">انصراف</button>
                        <button type="button" class="btn btn-danger" wire:click="executeBulkDelete">
                            <span wire:loading.remove wire:target="executeBulkDelete"><i class="material-symbols-outlined" style="font-size:17px;">delete_forever</i> بله، حذف کن</span>
                            <span wire:loading wire:target="executeBulkDelete">در حال حذف...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ====== MODAL: هشدار پارت بدون تایم ====== --}}
    @if($showZeroTimeWarningModal)
        <div class="modal fade show d-block" tabindex="-1" style="background:rgba(2,6,23,.6);backdrop-filter:blur(4px);z-index:1070;">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content rounded-4 border-0 shadow">
                    <div class="modal-header bg-warning text-white rounded-top-4">
                        <h5 class="modal-title d-flex align-items-center gap-2 mb-0">
                            <i class="material-symbols-outlined">warning</i> هشدار — پارت‌های بدون تایم
                        </h5>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeZeroTimeWarningModal"></button>
                    </div>
                    <div class="modal-body text-center py-4">
                        <i class="material-symbols-outlined text-warning" style="font-size:56px;">schedule</i>
                        <h5 class="mt-2 mb-3">برنامه دارای پارت‌های بدون تایم است</h5>
                        <p class="text-muted mb-0">
                            <strong class="text-danger">{{ $zeroTimePartsCount }}</strong> پارت با مدت زمان ۰ دقیقه وجود دارد.
                        </p>
                    </div>
                    <div class="modal-footer bg-body-tertiary rounded-bottom-4 justify-content-center gap-2">
                        <button type="button" class="btn btn-outline-secondary" wire:click="closeZeroTimeWarningModal">بازگشت و ویرایش</button>
                        <button type="button" class="btn btn-warning text-white" wire:click="forceFinalSave">
                            <i class="material-symbols-outlined" style="font-size:17px;">save</i> ذخیره بدون تایید
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @push('script')
        <script src="/admin/assets/js/Sortable.min.js"></script>
        <script>
            let draggedPartId = null;
            let draggedFromDayIndex = null;

            function initConsultationJalaliPickers() {
                if (typeof jalaliDatepicker === 'undefined') return;
                jalaliDatepicker.startWatch({
                    minDate: 'attr',
                    maxDate: 'attr',
                    autoHide: true,
                    showTodayBtn: true,
                    showEmptyBtn: true,
                });
            }

            function setBodyScrollLock(locked) {
                const html = document.documentElement;
                const body = document.body;
                if (!body) return;

                if (locked) {
                    if (!body.dataset.scrollLockTop) {
                        body.dataset.scrollLockTop = String(window.scrollY || window.pageYOffset || 0);
                    }
                    html.classList.add('modal-open-custom');
                    body.classList.add('modal-open-custom');
                    body.style.top = `-${body.dataset.scrollLockTop}px`;
                    return;
                }

                const lockedTop = parseInt(body.dataset.scrollLockTop || '0', 10) || 0;
                html.classList.remove('modal-open-custom');
                body.classList.remove('modal-open-custom');
                body.style.top = '';
                delete body.dataset.scrollLockTop;
                window.scrollTo(0, lockedTop);
            }

            function syncBodyScrollLock() {
                const hasModal = !!document.querySelector('.modal.show.d-block');
                setBodyScrollLock(hasModal);
            }

            function initSortableRows() {
                document.querySelectorAll('tr[data-sortable-row]').forEach(function (row) {
                    if (row._sortable) row._sortable.destroy();
                    row._sortable = Sortable.create(row, {
                        animation: 150,
                        handle: '.drag-handle',
                        draggable: '.plan-part-cell',
                        group: 'weeklyParts',
                        onStart: function (evt) {
                            draggedPartId = evt.item.getAttribute('data-part-id') ? parseInt(evt.item.getAttribute('data-part-id')) : null;
                            draggedFromDayIndex = parseInt(row.getAttribute('data-day-index'));
                        },
                        onEnd: function (evt) {
                            const targetRow = evt.to;
                            const targetDayIndex = parseInt(targetRow.getAttribute('data-day-index'));
                            const sourceDayIndex = draggedFromDayIndex;

                            if (sourceDayIndex === targetDayIndex) {
                                const partIds = [];
                                targetRow.querySelectorAll('.plan-part-cell[data-part-id]').forEach(td => {
                                    const pid = td.getAttribute('data-part-id');
                                    if (pid) partIds.push(parseInt(pid));
                                });
                                if (partIds.length > 0) @this.call('reorderParts', partIds, targetDayIndex);
                            } else if (draggedPartId) {
                                const targetPartId = evt.related ? evt.related.getAttribute('data-part-id') : null;
                                if (targetPartId && parseInt(targetPartId) !== draggedPartId) {
                                @this.call('swapParts', draggedPartId, parseInt(targetPartId));
                                } else {
                                @this.call('movePartToDay', draggedPartId, targetDayIndex);
                                }
                            }
                            draggedPartId = null;
                            draggedFromDayIndex = null;
                        }
                    });
                });
            }

            document.addEventListener('DOMContentLoaded', () => setTimeout(initSortableRows, 300));
            document.addEventListener('DOMContentLoaded', () => setTimeout(initConsultationJalaliPickers, 300));
            document.addEventListener('DOMContentLoaded', () => setTimeout(syncBodyScrollLock, 50));
            document.addEventListener('livewire:navigated', () => {
                initSortableRows();
                setTimeout(initConsultationJalaliPickers, 100);
                setTimeout(syncBodyScrollLock, 50);
            });
            document.addEventListener('livewire:updated', () => {
                setTimeout(initSortableRows, 100);
                setTimeout(initConsultationJalaliPickers, 100);
                setTimeout(syncBodyScrollLock, 50);
            });

            document.addEventListener('livewire:init', () => {
                const select2Config = { dir: 'rtl', language: 'fa', allowClear: true, width: '100%' };
                const selectMappings = {
                    'education-level-select': 'partForm.education_level_id',
                    'grade-select': 'partForm.cc_grade_id',
                    'subject-select': 'partForm.cc_subject_id',
                    'chapter-select': 'partForm.cc_chapter_id',
                };

                function destroySelect2(id) {
                    const $el = $('#' + id);
                    if ($el.length && $el.hasClass('select2-hidden-accessible')) $el.off('change').select2('destroy');
                }

                function initSingleSelect2(id) {
                    const $el = $('#' + id);
                    if (!$el.length) return;
                    const $modal = $el.closest('.modal-content');
                    if (!$modal.length) return;
                    destroySelect2(id);
                    $el.select2({ ...select2Config, dropdownParent: $modal, placeholder: $el.find('option:first').text() });
                    $el.on('change', function () {
                        const prop = selectMappings[id];
                        if (prop) @this.set(prop, $(this).val() || '');
                    });
                }

                function rebuildSelect2(id, options, selected, disabled) {
                    const $el = $('#' + id);
                    if (!$el.length) return;
                    const $modal = $el.closest('.modal-content');
                    if (!$modal.length) return;
                    destroySelect2(id);
                    $el.empty();
                    options.forEach(opt => $el.append(new Option(opt.text, String(opt.value), false, String(opt.value) === String(selected))));
                    $el.prop('disabled', !!disabled);
                    $el.select2({ ...select2Config, dropdownParent: $modal, placeholder: options[0]?.text || '' });
                    if (selected) $el.val(String(selected)).trigger('change.select2');
                    $el.on('change', function () {
                        const prop = selectMappings[id];
                        if (prop) @this.set(prop, $(this).val() || '');
                    });
                }

                Livewire.on('modal-opened', () => setTimeout(() => Object.keys(selectMappings).forEach(initSingleSelect2), 250));
                Livewire.on('modal-opened', () => setTimeout(initConsultationJalaliPickers, 250));
                Livewire.on('modal-opened', () => setTimeout(syncBodyScrollLock, 50));
                Livewire.on('modal-closed', () => Object.keys(selectMappings).forEach(destroySelect2));
                Livewire.on('modal-closed', () => setTimeout(syncBodyScrollLock, 50));
                Livewire.on('select2-update', (params) => {
                    const d = Array.isArray(params) ? params[0] : params;
                    if (!d?.id) return;
                    setTimeout(() => rebuildSelect2(d.id, d.options || [], d.selected || '', d.disabled || false), 50);
                });
            });
            document.addEventListener('livewire:updated', function() {
                syncBodyScrollLock();
            });
        </script>
    @endpush

</div>
</div>
