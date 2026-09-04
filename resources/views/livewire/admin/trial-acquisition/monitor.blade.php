<div class="trial-monitor-page" dir="rtl">
    @push('link')
        <style>
            @keyframes trial-call-ring {
                0%, 100% { transform: rotate(0); }
                20% { transform: rotate(14deg); }
                40% { transform: rotate(-14deg); }
                60% { transform: rotate(9deg); }
                80% { transform: rotate(-9deg); }
            }

            @keyframes trial-call-pulse {
                0% { box-shadow: 0 0 0 0 rgba(13, 110, 253, .55); }
                70% { box-shadow: 0 0 0 24px rgba(13, 110, 253, 0); }
                100% { box-shadow: 0 0 0 0 rgba(13, 110, 253, 0); }
            }

            .trial-monitor-page {
                overflow-x: hidden;
                color: var(--bs-body-color);
            }

            .trial-monitor-page .card {
                --bs-card-height: auto;
                height: auto;
                background: var(--bs-body-bg);
                color: var(--bs-body-color);
                border: 1px solid var(--bs-border-color) !important;
            }

            .trial-monitor-page .card-body {
                flex: 0 0 auto;
            }

            .trial-monitor-page .card-header.bg-white,
            .trial-monitor-page .table-light {
                --bs-table-bg: var(--bs-tertiary-bg);
                --bs-table-color: var(--bs-body-color);
                background: var(--bs-tertiary-bg) !important;
                color: var(--bs-body-color) !important;
                border-color: var(--bs-border-color);
            }

            .trial-monitor-page .table {
                --bs-table-bg: var(--bs-body-bg);
                --bs-table-color: var(--bs-body-color);
                --bs-table-border-color: var(--bs-border-color);
                --bs-table-hover-bg: var(--bs-tertiary-bg);
            }

            .trial-monitor-page .table,
            .trial-monitor-page .list-group-item,
            .trial-monitor-page .modal-content {
                color: var(--bs-body-color);
                border-color: var(--bs-border-color);
            }

            .trial-monitor-page .list-group-item:not(.active),
            .trial-live-stats .border.rounded-3,
            .trial-soft-panel,
            .trial-day-card {
                background: var(--bs-body-bg);
                color: var(--bs-body-color);
                border-color: var(--bs-border-color) !important;
            }

            .trial-stat-card {
                min-height: 112px;
            }

            .trial-students-list {
                max-height: 760px;
                overflow: auto;
            }

            .trial-report-table th,
            .trial-report-table td,
            .trial-detail-table th,
            .trial-detail-table td {
                vertical-align: middle;
            }

            .trial-row-actions {
                white-space: nowrap;
            }

            .trial-day-badges,
            .trial-status-pills,
            .trial-report-controls,
            .trial-bulk-actions {
                min-width: 0;
            }

            .trial-mini-title .material-symbols-outlined {
                font-size: 20px;
                line-height: 1;
            }

            .trial-call-content {
                background: #1f1f1f;
                border: 0;
                color: #fff;
                overflow: hidden;
            }

            .trial-call-header,
            .trial-call-footer,
            .trial-call-body {
                background: #1f1f1f;
            }

            .trial-call-muted {
                color: rgba(255, 255, 255, .6);
            }

            .trial-call-icon {
                width: 92px;
                height: 92px;
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #0d6efd;
                color: #fff;
                font-size: 36px;
                margin: 0 auto;
                animation: trial-call-pulse 1.5s infinite;
            }

            .trial-call-icon i {
                display: inline-block;
                animation: trial-call-ring 1s infinite;
            }

            .trial-call-form-panel {
                background: var(--bs-body-bg);
                color: var(--bs-body-color);
            }

            .trial-modal-layer {
                background: rgba(15, 23, 42, .58) !important;
                z-index: 1055;
            }

            .trial-modal-dialog .modal-content {
                background: var(--bs-body-bg);
                color: var(--bs-body-color);
                border: 1px solid var(--bs-border-color) !important;
            }

            .trial-modal-dialog .modal-header,
            .trial-modal-dialog .modal-footer {
                background: var(--bs-tertiary-bg);
                border-color: var(--bs-border-color);
            }

            @media (max-width: 991.98px) {
                .trial-students-list {
                    max-height: 300px;
                }

                .trial-monitor-shell {
                    --bs-gutter-y: 1rem;
                }

                .trial-student-summary h4 {
                    font-size: 1.15rem;
                }

                .trial-stats-row .card-body {
                    padding: .85rem;
                }

                .trial-stats-row h3 {
                    font-size: 1.35rem;
                }

                .trial-section-header,
                .trial-report-head,
                .trial-day-head,
                .trial-day-part {
                    align-items: stretch !important;
                    flex-direction: column;
                }

                .trial-section-header {
                    gap: .65rem;
                }

                .trial-section-header .badge,
                .trial-day-badges .badge,
                .trial-status-pills .badge,
                .trial-status-pills .btn {
                    width: fit-content;
                    max-width: 100%;
                    white-space: normal;
                    text-align: right;
                }

                .trial-day-badges,
                .trial-report-controls {
                    width: 100%;
                }

                .trial-report-controls > * {
                    flex: 1 1 calc(50% - .5rem);
                    min-width: 0;
                }

                .trial-report-controls .trial-report-status,
                .trial-report-controls .btn-primary {
                    flex-basis: 100%;
                    width: 100% !important;
                }

                .trial-window-badge {
                    display: inline-flex;
                    margin: .35rem 0 0 !important;
                    white-space: normal;
                    line-height: 1.7;
                    text-align: right;
                }

                .trial-missing-alert {
                    line-height: 2.1;
                }

                .trial-missing-alert .badge {
                    display: inline-flex;
                    max-width: 100%;
                    white-space: normal;
                    text-align: right;
                }

                .trial-bulk-actions,
                .trial-bulk-actions .btn {
                    width: 100%;
                }

                .trial-report-table,
                .trial-report-table thead,
                .trial-report-table tbody,
                .trial-report-table tr,
                .trial-report-table td,
                .trial-detail-table,
                .trial-detail-table thead,
                .trial-detail-table tbody,
                .trial-detail-table tr,
                .trial-detail-table td {
                    display: block;
                    width: 100%;
                }

                .trial-report-table thead,
                .trial-detail-table thead {
                    display: none;
                }

                .trial-report-table tbody,
                .trial-detail-table tbody {
                    padding: .75rem;
                }

                .trial-report-table tr,
                .trial-detail-table tr {
                    border: 1px solid var(--bs-border-color);
                    border-radius: .75rem;
                    margin-bottom: .75rem;
                    overflow: hidden;
                    background: var(--bs-body-bg);
                    box-shadow: 0 .25rem .75rem rgba(15, 23, 42, .05);
                }

                .trial-report-table tr:last-child,
                .trial-detail-table tr:last-child {
                    margin-bottom: 0;
                }

                .trial-report-table td,
                .trial-detail-table td {
                    border: 0;
                    border-bottom: 1px solid var(--bs-border-color);
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    gap: .75rem;
                    padding: .72rem .85rem;
                    text-align: left !important;
                }

                .trial-detail-table td {
                    align-items: flex-start;
                }

                .trial-report-table td::before,
                .trial-detail-table td::before {
                    content: attr(data-label);
                    color: var(--bs-secondary-color);
                    font-size: .78rem;
                    font-weight: 700;
                    flex: 0 0 auto;
                    text-align: right;
                }

                .trial-report-table td:last-child,
                .trial-detail-table td:last-child {
                    border-bottom: 0;
                }

                .trial-row-actions {
                    display: grid !important;
                    grid-template-columns: repeat(4, minmax(42px, 1fr));
                    gap: .35rem;
                    width: 100%;
                }

                .trial-row-actions .btn {
                    border-radius: .5rem !important;
                }

                .trial-modal-layer {
                    display: flex !important;
                    align-items: flex-end;
                    padding: 0;
                }

                .trial-modal-dialog {
                    width: 100%;
                    max-width: 100%;
                    margin: 0;
                }

                .trial-modal-dialog .modal-content {
                    border-radius: 1rem 1rem 0 0;
                    max-height: 88svh;
                }

                .trial-modal-dialog .modal-header,
                .trial-modal-dialog .modal-footer {
                    padding: .85rem 1rem;
                }

                .trial-modal-dialog .modal-body {
                    padding: 1rem;
                    overflow-y: auto;
                }

                .trial-call-layer {
                    display: flex !important;
                    align-items: flex-end;
                    padding: 0;
                }

                .trial-call-dialog {
                    width: 100%;
                    max-width: 100%;
                    margin: 0;
                }

                .trial-call-dialog .modal-content {
                    border-radius: 1rem 1rem 0 0;
                    max-height: 88svh;
                }

                .trial-call-dialog .modal-body {
                    overflow-y: auto;
                }
            }

            @media (max-width: 575.98px) {
                .trial-monitor-page .app-page-head {
                    margin-bottom: .75rem;
                }

                .trial-monitor-page .breadcrumb {
                    row-gap: .25rem;
                    font-size: .8rem;
                }

                .trial-students-card .card-header,
                .trial-section-card .card-header,
                .trial-section-card .card-body,
                .trial-student-summary .card-body {
                    padding: .85rem;
                }

                .trial-student-row {
                    align-items: flex-start;
                    flex-direction: column;
                }

                .trial-student-row .badge {
                    white-space: normal;
                    text-align: right;
                }

                .trial-status-pills {
                    width: 100%;
                }

                .trial-status-pills .badge {
                    flex: 1 1 100%;
                }

                .trial-status-pills .btn {
                    justify-content: center;
                    width: 100%;
                }

                .trial-stats-row {
                    --bs-gutter-x: .65rem;
                    --bs-gutter-y: .65rem;
                }

                .trial-stats-row .text-muted.small {
                    min-height: 2.35em;
                    line-height: 1.55;
                }

                .trial-mini-title {
                    flex-wrap: wrap;
                    line-height: 1.8;
                }

                .trial-day-card .p-3 {
                    padding: .85rem !important;
                }

                .trial-day-part {
                    gap: .35rem !important;
                }

                .trial-day-part .text-nowrap {
                    white-space: normal !important;
                    color: var(--bs-primary);
                    font-weight: 700;
                }

                .trial-report-table tbody,
                .trial-detail-table tbody {
                    padding: .65rem;
                }

                .trial-report-table td,
                .trial-detail-table td {
                    flex-direction: column;
                    align-items: stretch;
                    text-align: right !important;
                }

                .trial-report-table td::before,
                .trial-detail-table td::before {
                    margin-bottom: .15rem;
                }

                .trial-row-actions {
                    grid-template-columns: repeat(2, minmax(0, 1fr));
                }

                .trial-monitor-page .table-responsive {
                    border-radius: .75rem;
                    overflow-x: visible;
                }

                .trial-monitor-page .table-responsive table:not(.trial-report-table):not(.trial-detail-table) {
                    width: 100%;
                    min-width: 0;
                    table-layout: fixed;
                    font-size: .78rem;
                }

                .trial-monitor-page .table-responsive table:not(.trial-report-table):not(.trial-detail-table) th,
                .trial-monitor-page .table-responsive table:not(.trial-report-table):not(.trial-detail-table) td {
                    overflow-wrap: anywhere;
                    padding: .45rem .35rem;
                    white-space: normal;
                }

                .trial-monitor-page .modal-title {
                    font-size: 1rem;
                    line-height: 1.7;
                }
            }
            /* Unified RTL monitoring UI */
            .trial-monitor-page{
                --tm-surface:var(--bs-body-bg);--tm-soft:var(--bs-tertiary-bg);--tm-border:var(--bs-border-color);
                --tm-text:var(--bs-body-color);--tm-muted:var(--bs-secondary-color);--tm-shadow:0 14px 38px rgba(15,23,42,.08);
                direction:rtl;text-align:right;color:var(--tm-text);overflow-x:hidden;
            }
            [data-bs-theme="dark"] .trial-monitor-page,html.dark .trial-monitor-page{
                --tm-surface:#171d2d;--tm-soft:rgba(255,255,255,.055);--tm-border:rgba(255,255,255,.14);
                --tm-text:#f8fafc;--tm-muted:#e2e8f0;--tm-shadow:0 18px 48px rgba(0,0,0,.28);
            }
            .trial-monitor-page .breadcrumb{margin-bottom:0;color:var(--tm-muted)}.trial-monitor-page .breadcrumb a{color:var(--bs-primary);text-decoration:none}
            .trial-monitor-hero{display:flex;align-items:center;justify-content:space-between;gap:18px;margin:14px 0;padding:18px 20px;background:var(--tm-surface);border:1px solid var(--tm-border);border-radius:10px;box-shadow:var(--tm-shadow)}
            .trial-monitor-hero-main{display:flex;align-items:center;gap:13px;min-width:0}.trial-monitor-hero-icon{display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;flex:0 0 48px;color:#fff;background:linear-gradient(135deg,#0d6efd,#6f42c1);border-radius:10px;font-size:1.2rem}
            .trial-monitor-hero h3{margin:0 0 4px;color:var(--tm-text);font-size:1.16rem}.trial-monitor-hero p{margin:0;color:var(--tm-muted);font-size:.76rem;line-height:1.8}.trial-monitor-total{display:flex;align-items:center;gap:7px;flex:0 0 auto;padding:9px 12px;background:var(--tm-soft);border:1px solid var(--tm-border);border-radius:9px}.trial-monitor-total strong{color:var(--tm-text);font-size:1.1rem}.trial-monitor-total span{color:var(--tm-muted);font-size:.68rem}
            .trial-monitor-page .card{background:var(--tm-surface)!important;border:1px solid var(--tm-border)!important;border-radius:10px!important;box-shadow:var(--tm-shadow)!important;overflow:hidden}.trial-monitor-page .card-header{padding:13px 15px;background:var(--tm-soft)!important;color:var(--tm-text)!important;border-color:var(--tm-border)!important}
            .trial-monitor-page .form-control,.trial-monitor-page .form-select{color:var(--tm-text);background-color:var(--tm-surface);border-color:var(--tm-border);text-align:right}.trial-monitor-page .form-control::placeholder{color:var(--tm-muted);opacity:.78}
            .trial-students-list{background:var(--tm-surface)}.trial-students-list .list-group-item{padding:12px 14px;color:var(--tm-text);background:var(--tm-surface);border-color:var(--tm-border);text-align:right}.trial-students-list .list-group-item:hover{background:var(--tm-soft)}.trial-students-list .list-group-item.active{color:#fff;background:linear-gradient(135deg,#0d6efd,#4f46e5);border-color:#0d6efd}.trial-students-list .list-group-item.active .text-muted{color:rgba(255,255,255,.76)!important}
            .trial-student-summary{border-inline-start:4px solid var(--bs-primary)!important}.trial-section-card .card-header{font-weight:800}.trial-stats-row .card{height:100%}.trial-stats-row .card-body{background:var(--tm-surface)}
            .trial-monitor-page .table{--bs-table-bg:var(--tm-surface);--bs-table-color:var(--tm-text);--bs-table-border-color:var(--tm-border);--bs-table-hover-bg:var(--tm-soft);margin-bottom:0}.trial-monitor-page .table thead th{color:var(--tm-muted);background:var(--tm-soft);font-size:.72rem;font-weight:800;white-space:nowrap}.trial-monitor-page .table th,.trial-monitor-page .table td{text-align:right}.trial-monitor-page .table td.text-center,.trial-monitor-page .table th.text-center{text-align:center!important}
            .trial-monitor-page .badge{white-space:normal;line-height:1.45}.trial-monitor-page .btn{display:inline-flex;align-items:center;justify-content:center;gap:5px;white-space:normal;text-align:center}.trial-monitor-page .modal{direction:rtl;text-align:right}.trial-monitor-page .modal-title{color:var(--tm-text)}.trial-monitor-page .modal-body{color:var(--tm-text)}
            .trial-collapse-toggle{display:inline-flex!important;align-items:center;justify-content:center;width:34px;height:34px;flex:0 0 34px;padding:0!important;color:var(--tm-muted);background:var(--tm-surface);border:1px solid var(--tm-border);border-radius:8px}.trial-collapse-toggle:hover{color:var(--bs-primary);border-color:var(--bs-primary)}.trial-collapse-toggle i{transition:transform .2s ease}.trial-collapse-toggle i.is-open{transform:rotate(180deg)}
            .trial-extra-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px}.trial-extra-item{padding:11px 12px;background:var(--tm-soft);border:1px solid var(--tm-border);border-radius:9px}.trial-extra-item-head{display:flex;align-items:flex-start;justify-content:space-between;gap:8px}.trial-extra-item-title{color:var(--tm-text);font-size:.76rem;font-weight:850}.trial-extra-item-meta{display:flex;align-items:center;flex-wrap:wrap;gap:6px;margin-top:8px;color:var(--tm-muted);font-size:.66rem}.trial-extra-note{margin-top:8px;padding-top:8px;color:var(--tm-muted);border-top:1px dashed var(--tm-border);font-size:.68rem;line-height:1.8}.trial-row-actions{display:grid!important;grid-template-columns:repeat(2,minmax(0,1fr));gap:5px;width:100%}.trial-row-actions .btn{border-radius:7px!important}
            [data-bs-theme="dark"] .trial-monitor-page .text-muted,html.dark .trial-monitor-page .text-muted{color:#cbd5e1!important}[data-bs-theme="dark"] .trial-monitor-page .bg-light,html.dark .trial-monitor-page .bg-light{color:#f8fafc!important;background:#293246!important;border-color:#475569!important}[data-bs-theme="dark"] .trial-monitor-page .btn-outline-secondary,html.dark .trial-monitor-page .btn-outline-secondary{color:#e2e8f0;border-color:#64748b}
            @media(max-width:991.98px){
                .trial-monitor-page .trial-report-table td,.trial-monitor-page .trial-detail-table td{align-items:center;text-align:right!important}.trial-monitor-page .trial-report-table td::before,.trial-monitor-page .trial-detail-table td::before{text-align:right}.trial-monitor-shell>.col-xl-3,.trial-monitor-shell>.col-xl-9{width:100%}.trial-students-list{max-height:330px}.trial-row-actions{direction:rtl}
            }
            @media(max-width:767.98px){
                .trial-monitor-page{margin-inline:-5px}.trial-monitor-page .app-page-head{padding-inline:5px}.trial-monitor-hero{align-items:stretch;flex-direction:column;padding:15px}.trial-monitor-total{justify-content:center;width:100%}.trial-monitor-shell{--bs-gutter-x:10px}.trial-students-card .card-header{position:sticky;top:0;z-index:2}.trial-section-header,.trial-report-head{gap:10px}.trial-report-controls>*{min-height:40px}.trial-status-pills .btn,.trial-status-pills .badge{min-height:38px}
                .trial-modal-dialog .modal-footer{display:grid;grid-template-columns:1fr;gap:8px}.trial-modal-dialog .modal-footer .btn{width:100%;min-height:42px}.trial-call-footer>div{width:100%}.trial-call-footer>div:not([style*="display: none"]){display:grid;grid-template-columns:1fr;gap:8px}.trial-call-footer .btn{width:100%;min-height:42px}.trial-extra-grid{grid-template-columns:1fr}
            }
            @media(max-width:420px){.trial-monitor-hero-main{align-items:flex-start}.trial-monitor-hero-icon{width:42px;height:42px;flex-basis:42px}.trial-monitor-hero h3{font-size:1rem}.trial-students-list{max-height:285px}.trial-stats-row>.col-6{width:100%}.trial-row-actions{grid-template-columns:1fr!important}}
        </style>
    @endpush
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.trial-acquisition.dashboard') }}">مشاوره جذب</a></li>
                <li class="breadcrumb-item active">رصد دانش‌آموزان یک هفته آزمایشی</li>
            </ol>
        </nav>
    </div>

    <section class="trial-monitor-hero">
        <div class="trial-monitor-hero-main">
            <span class="trial-monitor-hero-icon"><i class="fi fi-rr-chart-histogram"></i></span>
            <div>
                <h3>رصد دانش‌آموزان هفته آزمایشی</h3>
                <p>بررسی برنامه، گزارش‌های روزانه، عملکرد مطالعاتی و وضعیت تماس دانش‌آموزان.</p>
            </div>
        </div>
        <div class="trial-monitor-total"><strong>{{ number_format($trials->count()) }}</strong><span>دانش‌آموز قابل رصد</span></div>
    </section>

    <div class="row g-3 trial-monitor-shell">
        <div class="col-xl-3">
            <div class="card border-0 shadow-sm h-100 trial-students-card">
                <div class="card-header bg-white">
                    <div class="fw-bold">دانش‌آموزان یک هفته آزمایشی</div>
                    <input type="text" wire:model.live.debounce.400ms="search" class="form-control form-control-sm mt-2" placeholder="جستجو نام، موبایل، تلفن پدر یا مادر">
                </div>
                <div class="list-group list-group-flush trial-students-list">
                    @forelse($trials as $trial)
                        @php
                            $name = $this->trialStudentFullName($trial);
                            $studentMobile = $this->trialStudentMobile($trial);
                            $fatherMobile = $this->trialFatherMobile($trial);
                            $motherMobile = $this->trialMotherMobile($trial);
                            $active = (int) $selectedTrial?->id === (int) $trial->id;
                            $mutedClass = $active ? 'text-white-50' : 'text-muted';
                        @endphp
                        <button type="button"
                                wire:click="selectTrial({{ $trial->id }})"
                                class="list-group-item list-group-item-action {{ $active ? 'active' : '' }}">
                            <div class="d-flex justify-content-between gap-2 trial-student-row">
                                <span class="fw-semibold">{{ $name }}</span>
                                <span class="badge bg-{{ $trial->status_color }}">{{ $trial->status_label }}</span>
                            </div>
                            <div class="small {{ $mutedClass }} mt-1">{{ $trial->grade_label }} / {{ $trial->field_label }}</div>
                            <div class="small {{ $mutedClass }} d-flex flex-wrap gap-2 mt-1">
                                <span>دانش‌آموز: <span dir="ltr">{{ $studentMobile }}</span></span>
                                <span>پدر: <span dir="ltr">{{ $fatherMobile }}</span></span>
                                <span>مادر: <span dir="ltr">{{ $motherMobile }}</span></span>
                            </div>
                        </button>
                    @empty
                        <div class="text-center text-muted py-4">دانش‌آموزی برای رصد وجود ندارد.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-xl-9">

            @if($selectedTrial)
                @php
                    $studentName = $this->trialStudentFullName($selectedTrial);
                    $studentMobile = $this->trialStudentMobile($selectedTrial);
                    $fatherMobile = $this->trialFatherMobile($selectedTrial);
                    $motherMobile = $this->trialMotherMobile($selectedTrial);
                    $programParts = $weeklyProgram?->parts?->count() ?? 0;
                    $programMinutes = $weeklyProgram?->parts?->sum('duration_minutes') ?? 0;
                @endphp

                @if($monitorLocked)
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center py-5">
                            <div class="mb-3">
                                <span class="badge bg-secondary-subtle text-secondary border px-3 py-2">رصد قفل است</span>
                            </div>
                            <h4 class="mb-2">برای رصد، اول باید تماس را بگیری.</h4>
                            <p class="text-muted mb-0">تا وقتی تماس موفق ثبت نشده باشد، گزارش‌ها و جزئیات این دانش‌آموز نمایش داده نمی‌شود.</p>
                        </div>
                    </div>
                @else

                    <div class="card border-0 shadow-sm mb-3 trial-student-summary">
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 trial-student-summary-inner">
                                <div>
                                    <h4 class="mb-1">{{ $studentName }}</h4>
                                    <div class="text-muted small">
                                        {{ $selectedTrial->grade_label }} / {{ $selectedTrial->field_label }}
                                        <span class="mx-2">•</span>
                                        دانش‌آموز: <span dir="ltr">{{ $studentMobile }}</span>
                                    </div>
                                    <div class="text-muted small mt-1 d-flex flex-wrap gap-2">
                                        <span>پدر: <span dir="ltr">{{ $fatherMobile }}</span></span>
                                        <span>مادر: <span dir="ltr">{{ $motherMobile }}</span></span>
                                    </div>
                                </div>
                                <div class="d-flex flex-wrap gap-2 trial-status-pills">
                                    @if($examProgramSummary)
                                        <a href="{{ route('admin.trial-acquisition.exam-monitor', $selectedTrial->id) }}" class="badge bg-warning text-dark px-3 py-2 text-decoration-none">صفحه امتحانات</a>
                                    @endif
                                    <span class="badge bg-{{ $selectedTrial->status_color }} px-3 py-2">{{ $selectedTrial->status_label }}</span>
                                    <button type="button" class="btn btn-sm btn-primary d-inline-flex align-items-center gap-1" wire:click="openExtraCall({{ $selectedTrial->id }})">
                                        <i class="fi fi-rr-phone-call"></i>
                                        تماس اضافه
                                    </button>
                                    @if($selectedTrial->expires_at)
                                        <span class="badge bg-light text-dark border px-3 py-2">
                                            {{ $selectedTrial->isExpired() ? 'منقضی شده' : $selectedTrial->days_remaining . ' روز مانده تا پایان دسترسی' }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($examProgramSummary)
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-body d-flex flex-wrap gap-2 align-items-center">
                                <span class="badge bg-warning text-dark">برنامه امتحانی</span>
                                <span class="fw-semibold">{{ $examProgramSummary['title'] }}</span>
                                <span class="badge bg-{{ $examProgramSummary['stage_color'] }}">{{ $examProgramSummary['stage_label'] }}</span>
                                <span class="text-muted small">تاریخ‌ها: {{ $examProgramSummary['exam_range'] }}</span>
                                <span class="text-muted small">ثبت برنامه: {{ $examProgramSummary['program_built_at'] }}</span>
                                <span class="text-muted small">تعداد روزها: {{ $examProgramSummary['days_count'] }}</span>
                            </div>
                        </div>
                    @endif

                    <div class="row g-3 mb-3 trial-stats-row">
                        <div class="col-6 col-lg-3">
                            <div class="card border-0 shadow-sm h-100 trial-stat-card"><div class="card-body">
                                <div class="text-muted small">پارت برنامه</div>
                                <h3 class="mb-0">{{ number_format($programParts) }}</h3>
                            </div></div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="card border-0 shadow-sm h-100 trial-stat-card"><div class="card-body">
                                <div class="text-muted small">زمان کل برنامه</div>
                                <h3 class="mb-0">{{ $this->formatMinutes($programMinutes) }}</h3>
                            </div></div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="card border-0 shadow-sm h-100 trial-stat-card"><div class="card-body">
                                <div class="text-muted small">گزارش‌های بدون ارسال</div>
                                <h3 class="mb-0 text-danger">{{ count($missingReports) }}</h3>
                            </div></div>
                        </div>
                        <div class="col-6 col-lg-3">
                            <div class="card border-0 shadow-sm h-100 trial-stat-card"><div class="card-body">
                                <div class="text-muted small">گزارش‌های مورد انتظار</div>
                                <h3 class="mb-0 text-info">{{ count($expectedReportRows) }}</h3>
                            </div></div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-3 trial-section-card" x-data="{ open: true }">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center gap-2 trial-section-header">
                            <div>
                                <strong>خلاصه عملکرد تا الان</strong>
                                <div class="small text-muted">بر اساس برنامه فعال، گزارش‌های ثبت‌شده و زمان‌های مطالعه ثبت‌شده</div>
                            </div>
                            <button type="button" class="trial-collapse-toggle" @click="open = !open" :aria-expanded="open"><i class="fi fi-rr-angle-small-down" :class="{ 'is-open': open }"></i></button>
                        </div>
                        <div class="card-body" x-show="open" x-transition>
                            <div class="row g-3 trial-live-stats">
                                <div class="col-6 col-lg">
                                    <div class="border rounded-3 p-3 h-100">
                                        <div class="text-muted small">ساعت مطالعه تا الان</div>
                                        <div class="h5 mb-0 text-primary">{{ $monitorSummary['study_until_now'] }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-lg">
                                    <div class="border rounded-3 p-3 h-100">
                                        <div class="text-muted small">ساعت مطالعه امروز</div>
                                        <div class="h5 mb-0 text-info">{{ $monitorSummary['study_today'] }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-lg">
                                    <div class="border rounded-3 p-3 h-100">
                                        <div class="text-muted small">گزارش ارسال تا الان</div>
                                        <div class="h5 mb-0 text-success">{{ number_format($monitorSummary['sent_reports_until_now']) }}</div>
                                    </div>
                                </div>
                                <div class="col-6 col-lg">
                                    <div class="border rounded-3 p-3 h-100">
                                        <div class="text-muted small">عدم گزارش تا الان</div>
                                        <div class="h5 mb-0 text-danger">{{ number_format($monitorSummary['missing_reports_until_now']) }}</div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg">
                                    <div class="border rounded-3 p-3 h-100">
                                        <div class="text-muted small">تمام بخش‌ها</div>
                                        <div class="h5 mb-0 text-body">{{ number_format($monitorSummary['done_parts']) }} / {{ number_format($monitorSummary['total_parts']) }}</div>
                                        <div class="small text-muted mt-1">انجام شده / کل</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <div class="card border-0 shadow-sm mb-3 trial-section-card" x-data="{ open: true }">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center gap-2 trial-section-header">
                        <div>
                            <strong>برنامه هفته آزمایشی</strong>
                            @if($weeklyProgram)
                                <div class="small text-muted">شروع {{ jalali($weeklyProgram->start_date)->format('%Y/%m/%d') }} تا {{ jalali($weeklyProgram->end_date)->format('%Y/%m/%d') }}</div>
                            @endif
                        </div>
                        <button type="button" class="trial-collapse-toggle" @click="open = !open" :aria-expanded="open"><i class="fi fi-rr-angle-small-down" :class="{ 'is-open': open }"></i></button>
                    </div>
                    <div class="card-body" x-show="open" x-transition>
                        @if($weeklyProgram)
                            <div class="row g-3">
                                @foreach($weekDays as $day)
                                    <div class="col-lg-6">
                                        <div class="border rounded-3 h-100 trial-day-card">
                                            <div class="p-3 border-bottom d-flex justify-content-between trial-day-head">
                                                <div>
                                                    <strong>{{ $day['name'] }}</strong>
                                                    <span class="text-muted small">{{ $day['jalali_date'] }}</span>
                                                </div>
                                                <div class="d-flex gap-1 trial-day-badges">
                                                    @if($day['is_rest_day'])<span class="badge bg-success">استراحت</span>@endif
                                                    @if($day['is_exam_day'])<span class="badge bg-warning text-dark">آزمون جامع</span>@endif
                                                    <span class="badge bg-{{ $day['status_color'] }}">{{ $day['status_label'] }}</span>
                                                    <span class="badge bg-light text-dark border">{{ $day['total_time_label'] }}</span>
                                                </div>
                                            </div>
                                            <div class="p-3">
                                                @if($day['can_view_details'])
                                                    <div class="mb-2">
                                                        @if($day['report_id'])
                                                            <button type="button" wire:click="openDetailModal({{ $day['report_id'] }})" class="btn btn-sm btn-outline-primary">
                                                                مشاهده جزئیات
                                                            </button>
                                                        @else
                                                            <button type="button" wire:click="openDayDetailModal({{ $day['index'] }})" class="btn btn-sm btn-outline-danger">
                                                                مشاهده جزئیات
                                                            </button>
                                                        @endif
                                                    </div>
                                                @endif
                                                @forelse($day['parts'] as $part)
                                                    <div class="d-flex justify-content-between gap-2 py-2 border-bottom trial-day-part">
                                                        <div>
                                                            <div class="fw-semibold">{{ $part['lesson_name'] }}</div>
                                                            <div class="small text-muted">
                                                                {{ $part['chapter_name'] ?? $part['topic_name'] ?? '—' }}
                                                                <span class="mx-1">•</span>{{ $part['part_type_label'] }}
                                                                <span class="mx-1">•</span>{{ $part['source_type_label'] }}
                                                            </div>
                                                            <span class="badge bg-{{ $part['status_color'] }} mt-2">{{ $part['status_label'] }}</span>
                                                        </div>
                                                        <div class="text-nowrap small">
                                                            {{ $part['duration_label'] }}
                                                            @if($part['test_count']) / {{ $part['test_count'] }} تست @endif
                                                            @if($part['has_study_session'])
                                                                <div class="text-success fw-semibold mt-1">{{ $part['study_duration_label'] }}</div>
                                                            @endif
                                                        </div>
                                                    </div>
                                                @empty
                                                    <div class="text-muted small">پارتی برای این روز تعریف نشده است.</div>
                                                @endforelse
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-4">هنوز برنامه‌ای برای این دانش‌آموز ساخته نشده است.</div>
                        @endif
                    </div>
                </div>

                @if(!empty($makeupSessions))
                    <div class="card border-0 shadow-sm mb-3 trial-section-card" x-data="{ open: true }">
                        <div class="card-header bg-white d-flex justify-content-between align-items-center gap-2 trial-section-header">
                            <div>
                                <strong>مطالعه اضافه‌برسازمان</strong>
                                <div class="small text-muted">فعالیت‌های مطالعاتی ثبت‌شده خارج از پارت‌های برنامه</div>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-primary-subtle text-primary">{{ number_format(count($makeupSessions)) }} مورد</span>
                                <button type="button" class="trial-collapse-toggle" @click="open = !open" :aria-expanded="open"><i class="fi fi-rr-angle-small-down" :class="{ 'is-open': open }"></i></button>
                            </div>
                        </div>
                        <div class="card-body" x-show="open" x-transition>
                            <div class="trial-extra-grid">
                                @foreach($makeupSessions as $session)
                                    <article class="trial-extra-item">
                                        <div class="trial-extra-item-head">
                                            <div><div class="trial-extra-item-title">{{ $session['subject_name'] }}</div><div class="small text-muted mt-1">{{ $session['chapter_name'] }}</div></div>
                                            <span class="badge bg-info-subtle text-info">{{ $session['part_type_label'] }}</span>
                                        </div>
                                        <div class="trial-extra-item-meta">
                                            <span><i class="fi fi-rr-clock-three"></i> {{ $session['duration_label'] }}</span>
                                            <span><i class="fi fi-rr-calendar"></i> {{ $session['date_label'] }}</span>
                                            <span dir="ltr">{{ $session['time_label'] }}</span>
                                        </div>
                                        @if(filled($session['note']))<div class="trial-extra-note">{{ $session['note'] }}</div>@endif
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <div class="card border-0 shadow-sm trial-section-card" x-data="{ open: true }">
                    <div class="card-header bg-white">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 trial-report-head">
                            <div>
                                <strong>لیست گزارش‌های مورد انتظار</strong>
                                <div class="small text-muted">
                                    همه روزهایی که باید برای این دانش‌آموز گزارش ارسال شود
                                    <span class="badge bg-info ms-2 trial-window-badge">مهلت هر گزارش: تا ۰۶:۰۰ صبح روز بعد</span>
                                </div>
                            </div>
                            <div class="d-flex flex-wrap gap-2 trial-report-controls">
                                <select wire:model.live="reportStatus" class="form-select form-select-sm w-auto trial-report-status">
                                    <option value="all">همه</option>
                                    <option value="sent">ارسال کرده</option>
                                    <option value="missing">عدم ارسال</option>
                                    <option value="waiting">در انتظار ارسال</option>
                                </select>
                                <button type="button" class="trial-collapse-toggle" @click="open = !open" :aria-expanded="open"><i class="fi fi-rr-angle-small-down" :class="{ 'is-open': open }"></i></button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-0" x-show="open" x-transition>
                        @if(count($expectedReportRows) > 0)
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0 trial-report-table">
                                    <thead class="table-light">
                                    <tr>
                                        <th>دانش‌آموز</th>
                                        <th class="text-center">تاریخ گزارش</th>
                                        <th class="text-center">مهلت ارسال</th>
                                        <th class="text-center">پارت</th>
                                        <th class="text-center">تست</th>
                                        <th class="text-center">امتیاز</th>
                                        <th class="text-center">وضعیت</th>
                                        <th class="text-center">عملیات</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($expectedReportRows as $row)
                                        @php
                                            $report = $row['report'];
                                            $ratingVal = (float) $row['rating'];
                                        @endphp
                                        <tr wire:key="trial-monitor-expected-report-{{ $row['student_id'] }}-{{ $row['report_date'] }}">
                                            <td class="fw-medium" data-label="دانش‌آموز">{{ $row['student_name'] }}</td>
                                            <td class="text-center small" data-label="تاریخ گزارش">
                                                <div class="fw-semibold">{{ $row['day_name'] }}</div>
                                                <div class="text-muted">{{ $row['report_date_label'] }}</div>
                                            </td>
                                            <td class="text-center small text-muted" data-label="مهلت ارسال">{{ $row['deadline_label'] }}</td>
                                            <td class="text-center" data-label="پارت"><span class="text-success fw-bold">{{ $row['parts_done'] }}</span>/<span>{{ $row['parts_total'] }}</span></td>
                                            <td class="text-center" data-label="تست">{{ $row['tests_total'] }}</td>
                                            <td class="text-center" data-label="امتیاز">
                                                @if($ratingVal > 0)
                                                    <span class="badge bg-primary rounded-pill">{{ $ratingVal }} / 10</span>
                                                    <div class="small text-muted">{{ $this->getRatingLabel($ratingVal) }}</div>
                                                @else
                                                    <span class="text-muted small">ثبت نشده</span>
                                                @endif
                                            </td>
                                            <td class="text-center" data-label="وضعیت"><span class="badge bg-{{ $row['status_color'] }}">{{ $row['status_label'] }}</span></td>
                                            <td class="text-center" data-label="عملیات">
                                                    <div class="btn-group btn-group-sm trial-row-actions">
                                                        @if($report)
                                                        <button type="button" wire:click="openDetailModal({{ $report->id }})" class="btn btn-outline-primary" title="مشاهده گزارش">
                                                            <i class="fi fi-rr-eye"></i>
                                                            مشاهده
                                                        </button>
                                                        <button type="button" wire:click="openCommentModal({{ $report->id }})" class="btn btn-outline-secondary" title="نظر مشاور">
                                                            <i class="fi fi-rr-comment"></i>
                                                            نظر
                                                        </button>
                                                    @else
                                                        <button type="button"
                                                                wire:click="sendReportReminder({{ $row['student_id'] }}, '{{ $row['report_date'] }}')"
                                                                class="btn btn-outline-warning"
                                                                title="ارسال اعلان گزارش">
                                                            <i class="fi fi-rr-bell"></i>
                                                            ارسال اعلان گزارش
                                                        </button>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center text-muted py-5">گزارشی برای این وضعیت وجود ندارد.</div>
                        @endif
                    </div>
                </div>


                @endif
            @else
                <div class="card border-0 shadow-sm"><div class="card-body text-center text-muted py-5">دانش‌آموزی برای رصد انتخاب نشده است.</div></div>
            @endif

        </div>
    </div>

    @if($extraCallTrialId && $extraCallTrial)
        @php
            $extraCallStudentName = $this->trialStudentFullName($extraCallTrial);
        @endphp
        <div class="modal fade show d-block trial-call-layer" tabindex="-1" style="background:rgba(0,0,0,.6)" wire:click.self="closeExtraCall">
            <div class="modal-dialog modal-lg modal-dialog-centered trial-call-dialog">
                <div class="modal-content trial-call-content"
                     wire:key="extra-call-modal-{{ $extraCallTrialId }}-{{ $extraCallPhase }}"
                     x-data="{
                        phase: '{{ $extraCallPhase }}',
                        secondsLeft: 15,
                        talkSeconds: 0,
                        _t: null,
                        start(){ if (this.phase === 'ringing') { this._t = setInterval(() => { if (this.secondsLeft > 0) { this.secondsLeft--; } if (this.secondsLeft <= 0) { this.stop(); } }, 1000); } if (this.phase === 'talking') { this.startTalk(); } },
                        startTalk(){ this.stop(); this.talkSeconds = 0; this._t = setInterval(() => this.talkSeconds++, 1000); },
                        stop(){ if (this._t) { clearInterval(this._t); this._t = null; } },
                        answerNow(){ this.stop(); $wire.markExtraCallAnswered(); },
                        endTalk(){ this.stop(); $wire.endExtraConversation(this.talkSeconds); },
                        fmt(s){ return String(Math.floor(s / 60)).padStart(2, '0') + ':' + String(s % 60).padStart(2, '0'); },
                     }"
                     x-init="start()">
                    <div class="modal-header trial-call-header border-0">
                        <div>
                            <h5 class="modal-title text-white mb-1">تماس اضافه — {{ $extraCallStudentName }}</h5>
                            <div class="small trial-call-muted" dir="ltr">{{ $extraCallTrial->user?->mobile ?? '—' }}</div>
                        </div>
                        <button type="button" class="btn-close btn-close-white" wire:click="closeExtraCall"></button>
                    </div>
                    <div class="modal-body trial-call-body">
                        @if($extraCallPhase === 'ringing')
                            <div class="text-center py-4">
                                <div class="trial-call-icon mb-3"><i class="fi fi-rr-phone-call"></i></div>
                                <h5 class="mb-1 text-white">در حال تماس اضافه</h5>
                                <p class="trial-call-muted mb-3" dir="ltr">{{ $extraCallTrial->user?->mobile ?? '—' }}</p>
                                <div class="display-4 fw-bold text-primary" dir="ltr" x-text="String(secondsLeft).padStart(2, '0')"></div>
                                <p class="trial-call-muted small mt-2">در صورت پاسخ، ثبت پاسخ را بزنید تا زمان مکالمه ضبط شود.</p>
                            </div>
                        @elseif($extraCallPhase === 'talking')
                            <div class="text-center py-4">
                                <div class="trial-call-icon mb-3"><i class="fi fi-rr-comment-alt"></i></div>
                                <h5 class="mb-1 text-white">در حال مکالمه</h5>
                                <p class="trial-call-muted mb-3" dir="ltr">{{ $extraCallTrial->user?->mobile ?? '—' }}</p>
                                <div class="display-3 fw-bold text-success" dir="ltr" x-text="fmt(talkSeconds)"></div>
                                <p class="trial-call-muted small mt-2">پس از پایان تماس، اتمام مکالمه را بزنید و توضیحات پیگیری را ثبت کنید.</p>
                            </div>
                        @else
                            <div class="trial-call-form-panel rounded-4 p-3">
                                <div class="alert alert-primary py-2 mb-3">
                                    مدت تماس ثبت شد:
                                    <strong dir="ltr">{{ sprintf('%02d:%02d', intdiv((int) ($extraTalkSeconds ?? 0), 60), (int) ($extraTalkSeconds ?? 0) % 60) }}</strong>
                                </div>
                                <div class="mb-0">
                                    <label class="form-label">توضیحات تماس اضافه <span class="text-danger">*</span></label>
                                    <textarea wire:model="extraCallNotes" rows="4" class="form-control" placeholder="خلاصه پیگیری، گزارش‌گیری یا نکته‌ای که باید بعداً دنبال شود را بنویسید."></textarea>
                                    @error('extraCallNotes')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="modal-footer trial-call-footer border-0">
                        @if($extraCallPhase === 'ringing')
                            <button type="button" class="btn btn-success" @click="answerNow()">
                                <i class="fi fi-rr-phone-call"></i>
                                ثبت پاسخ
                            </button>
                            <button type="button" class="btn btn-outline-light" wire:click="closeExtraCall">لغو</button>
                        @elseif($extraCallPhase === 'talking')
                            <button type="button" class="btn btn-danger btn-lg w-100" @click="endTalk()">
                                <i class="fi fi-rr-phone-slash"></i>
                                اتمام مکالمه
                            </button>
                        @else
                            <button type="button" class="btn btn-primary" wire:click="saveExtraCall">ثبت تماس اضافه</button>
                            <button type="button" class="btn btn-secondary" wire:click="closeExtraCall">انصراف</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($commentModalOpen)
        <div class="modal fade show d-block trial-modal-layer" tabindex="-1" style="background:rgba(0,0,0,.5)" wire:click.self="closeCommentModal">
            <div class="modal-dialog modal-dialog-centered trial-modal-dialog">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header">
                        <h5 class="modal-title">نظر مشاور <small class="text-muted">{{ $commentStudentName }}</small></h5>
                        <button type="button" class="btn-close" wire:click="closeCommentModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">وضعیت گزارش <span class="text-danger">*</span></label>
                            <select wire:model="commentStatusInput" class="form-select" {{ $advisorCommentReadonly ? 'disabled' : '' }}>
                                <option value="">— انتخاب کنید —</option>
                                <option value="approved">تأیید این گزارش</option>
                                <option value="rejected">رد این گزارش</option>
                            </select>
                            @error('commentStatusInput')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label">نظر مشاور <span class="text-danger">*</span></label>
                            <textarea wire:model="advisorCommentInput" rows="4" class="form-control" {{ $advisorCommentReadonly ? 'readonly' : '' }} placeholder="نظر خود درباره گزارش را بنویسید."></textarea>
                            @error('advisorCommentInput')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        @if($commentStudentReply)
                            <div class="alert alert-success mb-0"><strong>پاسخ دانش‌آموز:</strong> {{ $commentStudentReply }}</div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeCommentModal">بستن</button>
                        @unless($advisorCommentReadonly)
                            <button type="button" class="btn btn-primary" wire:click="saveAdvisorComment">ثبت نظر</button>
                        @endunless
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($detailModalOpen)
        <div class="modal fade show d-block trial-modal-layer" tabindex="-1" style="background:rgba(0,0,0,.5)" wire:click.self="closeDetailModal">
            <div class="modal-dialog modal-xl modal-dialog-scrollable trial-modal-dialog trial-detail-dialog">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header">
                        <h5 class="modal-title">جزئیات گزارش - {{ $selectedReportData['student_name'] ?? '' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeDetailModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-3"><div class="border rounded-3 p-3 trial-soft-panel"><div class="small text-muted">تاریخ</div><strong>{{ $selectedReportData['day_name'] ?? '' }} {{ $selectedReportData['report_date'] ?? '' }}</strong></div></div>
                            <div class="col-md-3"><div class="border rounded-3 p-3 trial-soft-panel"><div class="small text-muted">پارت خوانده</div><strong>{{ $selectedReportData['read_parts'] ?? 0 }} / {{ $selectedReportData['total_parts'] ?? 0 }}</strong></div></div>
                            <div class="col-md-3"><div class="border rounded-3 p-3 trial-soft-panel"><div class="small text-muted">تست</div><strong>{{ $selectedReportData['done_tests'] ?? 0 }} / {{ $selectedReportData['total_tests'] ?? 0 }}</strong></div></div>
                            <div class="col-md-3"><div class="border rounded-3 p-3 trial-soft-panel"><div class="small text-muted">امتیاز</div><strong>{{ $selectedReportData['rating'] ?? 0 }} / 10 - {{ $selectedReportData['rating_label'] ?? '' }}</strong></div></div>
                        </div>

                        <div class="table-responsive mb-3">
                            <table class="table table-sm table-bordered align-middle trial-detail-table">
                                <thead class="table-light"><tr><th>درس</th><th>فصل/مبحث</th><th class="text-center">انجام پارت</th><th class="text-center">نوع</th><th class="text-center">زمان</th><th class="text-center">مطالعه</th><th class="text-center">تست</th><th class="text-center">امتیاز</th></tr></thead>
                                <tbody>
                                @forelse($reportPartsDetails as $part)
                                    <tr>
                                        <td class="fw-semibold" data-label="درس">{{ $part['lesson_name'] }}</td>
                                        <td data-label="فصل/مبحث">{{ $part['chapter_name'] ?? $part['topic_name'] ?? '—' }}</td>
                                        <td class="text-center" data-label="انجام پارت"><span class="badge bg-{{ $part['status_color'] }}">{{ $part['status_label'] }}</span></td>
                                        <td class="text-center" data-label="نوع">{{ $part['part_type_label'] }}<div class="small text-muted">{{ $part['lesson_type_label'] }}</div></td>
                                        <td class="text-center" data-label="زمان">{{ $part['duration_label'] }}</td>
                                        <td class="text-center" data-label="مطالعه">
                                            @if($part['has_study_session'])
                                                <span class="text-success fw-bold">{{ $part['study_duration_label'] }}</span>
                                                <div class="small text-muted">{{ $part['study_started_at'] }} - {{ $part['study_ended_at'] }}</div>
                                                <x-study-session-badges
                                                    :is-early-finish="(bool)($part['is_early_finish'] ?? false)"
                                                    :extra-seconds="(int)($part['extra_seconds'] ?? 0)"
                                                    :extra-target-seconds="(int)($part['extra_target_seconds'] ?? 0)"
                                                    :is-cheating="(bool)($part['is_cheating'] ?? false)"
                                                    :cheat-status="$part['cheat_status'] ?? null"
                                                    :cheat-minutes="(int)($part['cheat_minutes'] ?? 0)"
                                                    style="bootstrap" />
                                                @if(!empty($part['cheat_reason']))<div class="small text-muted">علت تقلب: {{ \Illuminate\Support\Str::limit($part['cheat_reason'], 40) }}</div>@endif
                                            @else
                                                <span class="text-danger small">ثبت نشده</span>
                                            @endif
                                        </td>
                                        <td class="text-center" data-label="تست">{{ $part['tests_done'] }} / {{ $part['test_count'] }}</td>
                                        <td class="text-center" data-label="امتیاز">{{ $part['session_rating'] ?: '—' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="8" class="text-center text-muted py-4">پارتی برای این گزارش یافت نشد.</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if(!empty($selectedReportData['makeup_sessions']))
                            <div class="border rounded-3 p-3 mb-3 trial-soft-panel">
                                <h6 class="fw-bold">مطالعه اضافه بر سازمان</h6>
                                @foreach($selectedReportData['makeup_sessions'] as $ms)
                                    <span class="badge bg-light text-dark border me-1">{{ $ms['topic_name'] }} - {{ $ms['duration_minutes'] }} دقیقه - {{ $ms['ended_at'] }}</span>
                                @endforeach
                            </div>
                        @endif

                        <div class="row g-3">
                            <div class="col-md-6"><div class="border rounded-3 p-3 h-100 trial-soft-panel"><h6>توضیحات دانش‌آموز</h6><p class="mb-0 text-muted">{{ $selectedReportData['description'] ?: 'وجود ندارد' }}</p></div></div>
                            <div class="col-md-6"><div class="border rounded-3 p-3 h-100 trial-soft-panel"><h6>علت عدم انجام پارت</h6><p class="mb-0 text-muted">{{ $selectedReportData['missed_parts_reason'] ?: 'وجود ندارد' }}</p></div></div>
                            @if($selectedReportData['advisor_comment'] ?? null)<div class="col-md-6"><div class="alert alert-primary mb-0"><strong>نظر مشاور:</strong> {{ $selectedReportData['advisor_comment'] }}</div></div>@endif
                            @if($selectedReportData['student_reply'] ?? null)<div class="col-md-6"><div class="alert alert-success mb-0"><strong>پاسخ دانش‌آموز:</strong> {{ $selectedReportData['student_reply'] }}</div></div>@endif
                        </div>
                    </div>
                    <div class="modal-footer gap-2">
                        @if($selectedReportId)
                            <button type="button" class="btn btn-outline-secondary" wire:click="openCommentModal({{ $selectedReportId }})">نظر</button>
                        @endif
                        <button type="button" class="btn btn-secondary" wire:click="closeDetailModal">بستن</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($dayDetailModalOpen)
        <div class="modal fade show d-block trial-modal-layer" tabindex="-1" style="background:rgba(0,0,0,.5)" wire:click.self="closeDayDetailModal">
            <div class="modal-dialog modal-xl modal-dialog-scrollable trial-modal-dialog trial-detail-dialog">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header">
                        <h5 class="modal-title">جزئیات روز - {{ $selectedDayData['day_name'] ?? '' }} {{ $selectedDayData['date'] ?? '' }}</h5>
                        <button type="button" class="btn-close" wire:click="closeDayDetailModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3 mb-3">
                            <div class="col-md-3"><div class="border rounded-3 p-3 trial-soft-panel"><div class="small text-muted">دانش‌آموز</div><strong>{{ $selectedDayData['student_name'] ?? '' }}</strong></div></div>
                            <div class="col-md-3"><div class="border rounded-3 p-3 trial-soft-panel"><div class="small text-muted">وضعیت گزارش</div><span class="badge bg-{{ $selectedDayData['status_color'] ?? 'secondary' }}">{{ $selectedDayData['status_label'] ?? '' }}</span></div></div>
                            <div class="col-md-3"><div class="border rounded-3 p-3 trial-soft-panel"><div class="small text-muted">تعداد پارت</div><strong>{{ $selectedDayData['total_parts'] ?? 0 }}</strong></div></div>
                            <div class="col-md-3"><div class="border rounded-3 p-3 trial-soft-panel"><div class="small text-muted">زمان برنامه</div><strong>{{ $selectedDayData['total_time'] ?? '0 دقیقه' }}</strong></div></div>
                        </div>

                        @if(($selectedDayData['is_rest_day'] ?? false) || ($selectedDayData['is_exam_day'] ?? false))
                            <div class="d-flex flex-wrap gap-2 mb-3">
                                @if($selectedDayData['is_rest_day'] ?? false)<span class="badge bg-success">روز استراحت</span>@endif
                                @if($selectedDayData['is_exam_day'] ?? false)<span class="badge bg-warning text-dark">آزمون جامع</span>@endif
                            </div>
                        @endif

                        <div class="table-responsive mb-3">
                            <table class="table table-sm table-bordered align-middle trial-detail-table">
                                <thead class="table-light"><tr><th>درس</th><th>فصل/مبحث</th><th class="text-center">انجام پارت</th><th class="text-center">نوع</th><th class="text-center">زمان برنامه</th><th class="text-center">مطالعه ثبت‌شده</th><th class="text-center">تست</th></tr></thead>
                                <tbody>
                                @forelse($dayPartsDetails as $part)
                                    <tr>
                                        <td class="fw-semibold" data-label="درس">{{ $part['lesson_name'] }}</td>
                                        <td data-label="فصل/مبحث">{{ $part['chapter_name'] ?? $part['topic_name'] ?? '—' }}</td>
                                        <td class="text-center" data-label="انجام پارت"><span class="badge bg-{{ $part['status_color'] }}">{{ $part['status_label'] }}</span></td>
                                        <td class="text-center" data-label="نوع">{{ $part['part_type_label'] }}<div class="small text-muted">{{ $part['lesson_type_label'] }}</div></td>
                                        <td class="text-center" data-label="زمان برنامه">{{ $part['duration_label'] }}</td>
                                        <td class="text-center" data-label="مطالعه ثبت‌شده">
                                            @if($part['has_study_session'])
                                                <span class="text-success fw-bold">{{ $part['study_duration_label'] }}</span>
                                                <div class="small text-muted">{{ $part['study_started_at'] }} - {{ $part['study_ended_at'] }}</div>
                                            @else
                                                <span class="text-danger small">ثبت نشده</span>
                                            @endif
                                        </td>
                                        <td class="text-center" data-label="تست">{{ $part['tests_done'] }} / {{ $part['test_count'] }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center text-muted py-4">پارتی برای این روز تعریف نشده است.</td></tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-warning mb-0">
                            برای این روز گزارش روزانه ارسال نشده است؛ جزئیات بالا از برنامه و مطالعه‌های ثبت‌شده استخراج شده‌اند.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeDayDetailModal">بستن</button>
                    </div>
                </div>
            </div>
        </div>
    @endif


</div>
