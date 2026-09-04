<div class="nsi-page {{ $status === \App\Models\TrialWeek::ACQ_DISINTEREST_TEMPORARY ? 'is-temporary' : 'is-definitive' }}" dir="rtl">
    @php $isTemporary = $status === \App\Models\TrialWeek::ACQ_DISINTEREST_TEMPORARY; @endphp

    @push('link')
        <style>
            .nsi-page{
                --nsi-surface:var(--bs-body-bg);--nsi-soft:var(--bs-tertiary-bg);--nsi-border:var(--bs-border-color);
                --nsi-text:var(--bs-body-color);--nsi-muted:var(--bs-secondary-color);--nsi-shadow:0 14px 38px rgba(15,23,42,.08);
                color:var(--nsi-text);direction:rtl;text-align:right;overflow-x:hidden;
            }
            [data-bs-theme="dark"] .nsi-page,html.dark .nsi-page{
                --nsi-surface:#171d2d;--nsi-soft:rgba(255,255,255,.055);--nsi-border:rgba(255,255,255,.14);
                --nsi-text:#f8fafc;--nsi-muted:#e2e8f0;--nsi-shadow:0 18px 48px rgba(0,0,0,.28);
            }
            .nsi-page .breadcrumb{margin-bottom:0;color:var(--nsi-muted)}.nsi-page .breadcrumb a{color:var(--bs-primary);text-decoration:none}
            .nsi-hero,.nsi-shell{background:var(--nsi-surface);border:1px solid var(--nsi-border);border-radius:10px;box-shadow:var(--nsi-shadow)}
            .nsi-hero{display:flex;align-items:center;justify-content:space-between;gap:18px;margin:14px 0;padding:20px}
            .nsi-hero-main{display:flex;align-items:center;gap:13px;min-width:0}.nsi-hero-copy{min-width:0}
            .nsi-hero-icon{display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;flex:0 0 48px;color:#fff;border-radius:10px;font-size:1.2rem}
            .nsi-page.is-temporary .nsi-hero-icon{background:linear-gradient(135deg,#f59e0b,#f97316)}
            .nsi-page.is-definitive .nsi-hero-icon{background:linear-gradient(135deg,#ef4444,#be123c)}
            .nsi-hero h3{margin:0 0 4px;color:var(--nsi-text);font-size:1.18rem}.nsi-hero p{margin:0;color:var(--nsi-muted);font-size:.77rem;line-height:1.8}
            .nsi-summary{display:flex;align-items:center;gap:8px;flex:0 0 auto;padding:9px 12px;background:var(--nsi-soft);border:1px solid var(--nsi-border);border-radius:9px}
            .nsi-summary strong{color:var(--nsi-text);font-size:1.15rem}.nsi-summary span{color:var(--nsi-muted);font-size:.7rem}
            .nsi-shell{overflow:hidden}.nsi-toolbar{display:flex;align-items:center;justify-content:space-between;gap:14px;padding:14px 16px;background:var(--nsi-soft);border-bottom:1px solid var(--nsi-border)}
            .nsi-toolbar-title{display:flex;align-items:center;gap:7px;color:var(--nsi-text);font-size:.8rem;font-weight:800;white-space:nowrap}
            .nsi-search{position:relative;width:min(100%,400px)}.nsi-search i{position:absolute;inset-inline-start:13px;top:50%;z-index:2;color:var(--nsi-muted);transform:translateY(-50%)}
            .nsi-search .form-control{min-height:42px;padding-inline-start:40px;color:var(--nsi-text);background:var(--nsi-surface);border-color:var(--nsi-border);text-align:right}
            .nsi-search .form-control::placeholder{color:var(--nsi-muted);opacity:.8}
            .nsi-table{margin:0;color:var(--nsi-text)}.nsi-table thead th{padding:12px 14px;color:var(--nsi-muted);background:var(--nsi-soft);border-color:var(--nsi-border);font-size:.7rem;font-weight:800;white-space:nowrap;text-align:right}
            .nsi-table tbody td{padding:14px;color:var(--nsi-text);background:var(--nsi-surface);border-color:var(--nsi-border);vertical-align:middle}.nsi-table tbody tr:hover td{background:var(--nsi-soft)}
            .nsi-student{display:flex;align-items:center;gap:10px;min-width:190px}.nsi-avatar{display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;flex:0 0 38px;color:var(--bs-primary);background:rgba(var(--bs-primary-rgb),.12);border-radius:10px;font-weight:900}
            .nsi-name{color:var(--nsi-text);font-size:.79rem;font-weight:800}.nsi-mobile{margin-top:3px;color:var(--nsi-muted);font-size:.68rem;direction:ltr;text-align:right;unicode-bidi:isolate}
            .nsi-education{display:flex;align-items:center;flex-wrap:wrap;gap:5px}.nsi-chip{display:inline-flex;align-items:center;justify-content:center;padding:5px 8px;border-radius:999px;font-size:.66rem;font-weight:800}
            .nsi-reason{max-width:320px;color:var(--nsi-text);font-size:.74rem;line-height:1.85;overflow-wrap:anywhere}.nsi-reason.is-empty{color:var(--nsi-muted)}
            .nsi-date{display:inline-flex;align-items:center;justify-content:center;gap:5px;color:var(--nsi-text);font-size:.69rem;white-space:nowrap;direction:ltr;unicode-bidi:isolate}.nsi-date i{color:var(--nsi-muted)}
            .nsi-reminder{display:inline-flex;align-items:center;justify-content:center;gap:5px;padding:6px 8px;color:#92400e;background:#fef3c7;border:1px solid #fcd34d;border-radius:8px;font-size:.68rem;font-weight:800;white-space:nowrap;direction:ltr;unicode-bidi:isolate}
            [data-bs-theme="dark"] .nsi-reminder,html.dark .nsi-reminder{color:#fde68a;background:rgba(245,158,11,.14);border-color:rgba(245,158,11,.4)}
            .nsi-muted{color:var(--nsi-muted);font-size:.7rem}.nsi-empty{display:flex;flex-direction:column;align-items:center;gap:10px;padding:52px 20px;color:var(--nsi-muted);text-align:center}
            .nsi-empty i{display:inline-flex;align-items:center;justify-content:center;width:58px;height:58px;color:var(--bs-primary);background:rgba(var(--bs-primary-rgb),.1);border-radius:50%;font-size:1.35rem}
            .nsi-footer{padding:12px 16px;background:var(--nsi-soft);border-top:1px solid var(--nsi-border)}
            @media(max-width:767.98px){
                .nsi-page{margin-inline:-5px}.nsi-page .app-page-head{padding-inline:5px}.nsi-hero{align-items:stretch;flex-direction:column;padding:15px}.nsi-summary{justify-content:center;width:100%}
                .nsi-toolbar{align-items:stretch;flex-direction:column}.nsi-search{width:100%}.nsi-table-responsive{overflow:visible}.nsi-table thead{display:none}.nsi-table,.nsi-table tbody{display:block}
                .nsi-table tbody{padding:10px;background:var(--nsi-soft)}.nsi-table tbody tr{display:block;margin-bottom:10px;padding:4px 12px;background:var(--nsi-surface);border:1px solid var(--nsi-border);border-radius:10px;box-shadow:0 6px 18px rgba(15,23,42,.05)}
                .nsi-table tbody tr:last-child{margin-bottom:0}.nsi-table tbody tr:hover td{background:transparent}.nsi-table tbody td{display:grid;grid-template-columns:minmax(82px,.65fr) minmax(0,1fr);align-items:center;gap:10px;padding:11px 0;background:transparent;border-width:0 0 1px;text-align:right!important}
                .nsi-table tbody td:last-child{border-bottom:0}.nsi-table tbody td::before{content:attr(data-label);color:var(--nsi-muted);font-size:.68rem;font-weight:800}.nsi-table tbody td.nsi-student-cell{display:block;padding-block:13px}.nsi-table tbody td.nsi-student-cell::before{display:none}
                .nsi-student{min-width:0}.nsi-reason{max-width:none}.nsi-date,.nsi-reminder{justify-self:start}.nsi-empty-row{padding:0!important;border:0!important;background:transparent!important;box-shadow:none!important}.nsi-empty-cell{display:block!important;border:0!important}.nsi-empty-cell::before{display:none!important}
            }
            @media(max-width:420px){.nsi-hero-main{align-items:flex-start}.nsi-hero-icon{width:42px;height:42px;flex-basis:42px}.nsi-hero h3{font-size:1.02rem}.nsi-table tbody{padding-inline:5px}.nsi-table tbody tr{padding-inline:10px}}
        </style>
    @endpush

    <div class="app-page-head">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.trial-acquisition.dashboard') }}">مشاوره جذب</a></li>
                    <li class="breadcrumb-item active">{{ $pageTitle }}</li>
                </ol>
            </nav>
        </div>

        <section class="nsi-hero">
            <div class="nsi-hero-main">
                <span class="nsi-hero-icon"><i class="fi {{ $isTemporary ? 'fi-rr-time-quarter-past' : 'fi-rr-ban' }}"></i></span>
                <div class="nsi-hero-copy">
                    <h3>{{ $pageTitle }}</h3>
                    <p>
                        {{ $isTemporary
                            ? 'دانش‌آموزانی که فعلاً تمایلی ندارند و ممکن است در زمان دیگری نیاز به پیگیری داشته باشند.'
                            : 'دانش‌آموزانی که عدم تمایل قطعی آن‌ها ثبت شده و در فهرست اصلی پیگیری نمایش داده نمی‌شوند.' }}
                    </p>
                </div>
            </div>
            <div class="nsi-summary"><strong>{{ number_format($students->total()) }}</strong><span>دانش‌آموز</span></div>
        </section>

        <section class="nsi-shell">
            <div class="nsi-toolbar">
                <span class="nsi-toolbar-title"><i class="fi fi-rr-list"></i> فهرست {{ $isTemporary ? 'عدم تمایل موقت' : 'عدم تمایل قطعی' }}</span>
                <label class="nsi-search">
                    <i class="fi fi-rr-search"></i>
                    <input type="search" wire:model.live.debounce.400ms="search" class="form-control"
                           placeholder="جست‌وجوی نام یا شماره تماس…" autocomplete="off">
                </label>
            </div>

            <div class="table-responsive nsi-table-responsive" wire:loading.class="opacity-50">
                <table class="table nsi-table align-middle">
                    <thead>
                    <tr>
                        <th>دانش‌آموز</th>
                        <th>پایه و رشته</th>
                        <th>علت عدم تمایل</th>
                        <th class="text-center">یادآور</th>
                        <th class="text-center">زمان ثبت</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($students as $trial)
                        @php
                            $info = $trial->user?->personalInformation;
                            $fullName = trim(($info?->name ?? '') . ' ' . ($info?->name_full ?? '')) ?: ($trial->user?->profile?->full_name ?: ($trial->user?->name ?? '—'));
                            $gradeLabels = [9 => 'نهم', 10 => 'دهم', 11 => 'یازدهم', 12 => 'دوازدهم', 13 => 'فارغ‌التحصیل'];
                            $fieldLabels = ['math' => 'ریاضی', 'experimental' => 'تجربی', 'human' => 'انسانی'];
                            $gradeLabel = $gradeLabels[(int) $trial->grade] ?? ('پایه ' . $trial->grade);
                            $fieldLabel = (int) $trial->grade === 9 ? 'بدون رشته' : ($fieldLabels[$trial->field] ?? ($trial->field ?: '—'));
                        @endphp
                        <tr wire:key="no-interest-student-{{ $trial->id }}">
                            <td class="nsi-student-cell">
                                <div class="nsi-student">
                                    <span class="nsi-avatar">{{ mb_substr($fullName, 0, 1) }}</span>
                                    <div>
                                        <div class="nsi-name">{{ $fullName }}</div>
                                        <div class="nsi-mobile">{{ $trial->user?->mobile ?? 'شماره ثبت نشده' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="پایه و رشته">
                                <div class="nsi-education">
                                    <span class="nsi-chip bg-primary-subtle text-primary">{{ $gradeLabel }}</span>
                                    <span class="nsi-chip bg-info-subtle text-info">{{ $fieldLabel }}</span>
                                </div>
                            </td>
                            <td data-label="علت عدم تمایل">
                                <div class="nsi-reason {{ filled($trial->acq_disinterest_reason) ? '' : 'is-empty' }}">
                                    {{ $trial->acq_disinterest_reason ?: 'علتی ثبت نشده است.' }}
                                </div>
                            </td>
                            <td data-label="یادآور" class="text-center">
                                @if($trial->acq_reminder_at)
                                    <span class="nsi-reminder"><i class="fi fi-rr-alarm-clock"></i>{{ jdate($trial->acq_reminder_at)->format('Y/m/d H:i') }}</span>
                                @else
                                    <span class="nsi-muted">بدون یادآور</span>
                                @endif
                            </td>
                            <td data-label="زمان ثبت" class="text-center">
                                @if($trial->acq_disinterest_at)
                                    <span class="nsi-date"><i class="fi fi-rr-clock-three"></i>{{ jdate($trial->acq_disinterest_at)->format('Y/m/d H:i') }}</span>
                                @else
                                    <span class="nsi-muted">ثبت نشده</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr class="nsi-empty-row">
                            <td colspan="5" class="nsi-empty-cell">
                                <div class="nsi-empty">
                                    <i class="fi fi-rr-user-slash"></i>
                                    <strong>دانش‌آموزی برای نمایش وجود ندارد</strong>
                                    <span>نتیجه‌ای مطابق جست‌وجوی شما پیدا نشد.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            @if($students->hasPages())
                <div class="nsi-footer">{{ $students->links('layouts.admin.pagination') }}</div>
            @endif
        </section>
</div>
