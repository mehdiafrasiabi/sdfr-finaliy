<div class="tams-page" dir="rtl">
    @push('link')
        <style>
            .tams-page{
                --tams-surface:var(--bs-body-bg);--tams-soft:var(--bs-tertiary-bg);--tams-border:var(--bs-border-color);
                --tams-text:var(--bs-body-color);--tams-muted:var(--bs-secondary-color);--tams-shadow:0 14px 38px rgba(15,23,42,.08);
                color:var(--tams-text);direction:rtl;text-align:right;overflow-x:hidden;
            }
            [data-bs-theme="dark"] .tams-page,html.dark .tams-page{
                --tams-surface:#171d2d;--tams-soft:rgba(255,255,255,.055);--tams-border:rgba(255,255,255,.14);
                --tams-text:#f8fafc;--tams-muted:#e2e8f0;--tams-shadow:0 18px 48px rgba(0,0,0,.28);
            }
            .tams-page .breadcrumb{margin-bottom:0;color:var(--tams-muted)}
            .tams-page .breadcrumb a{color:var(--bs-primary);text-decoration:none}
            .tams-hero,.tams-shell{background:var(--tams-surface);border:1px solid var(--tams-border);border-radius:10px;box-shadow:var(--tams-shadow)}
            .tams-hero{display:flex;align-items:center;justify-content:space-between;gap:18px;margin:14px 0;padding:20px}
            .tams-hero-main{display:flex;align-items:center;gap:13px;min-width:0}
            .tams-hero-icon{display:inline-flex;align-items:center;justify-content:center;width:48px;height:48px;flex:0 0 48px;color:#fff;background:linear-gradient(135deg,#0d6efd,#6f42c1);border-radius:10px;font-size:1.2rem}
            .tams-hero h3{margin:0 0 4px;color:var(--tams-text);font-size:1.2rem}
            .tams-hero p{margin:0;color:var(--tams-muted);font-size:.78rem;line-height:1.8}
            .tams-total{display:flex;align-items:center;gap:9px;flex:0 0 auto;padding:9px 12px;background:var(--tams-soft);border:1px solid var(--tams-border);border-radius:9px}
            .tams-total strong{color:var(--tams-text);font-size:1.15rem}.tams-total span{color:var(--tams-muted);font-size:.72rem}
            .tams-shell{overflow:hidden}
            .tams-toolbar{display:flex;align-items:center;justify-content:space-between;gap:14px;padding:14px 16px;background:var(--tams-soft);border-bottom:1px solid var(--tams-border)}
            .tams-toolbar-title{color:var(--tams-text);font-size:.83rem;font-weight:800;white-space:nowrap}
            .tams-search{position:relative;width:min(100%,400px)}
            .tams-search i{position:absolute;inset-inline-start:13px;top:50%;z-index:2;color:var(--tams-muted);transform:translateY(-50%)}
            .tams-search .form-control{min-height:42px;padding-inline-start:40px;color:var(--tams-text);background:var(--tams-surface);border-color:var(--tams-border);text-align:right}
            .tams-search .form-control::placeholder{color:var(--tams-muted);opacity:.8}
            .tams-table{margin:0;color:var(--tams-text)}
            .tams-table thead th{padding:12px 14px;color:var(--tams-muted);background:var(--tams-soft);border-color:var(--tams-border);font-size:.7rem;font-weight:800;white-space:nowrap;text-align:right}
            .tams-table tbody td{padding:14px;color:var(--tams-text);background:var(--tams-surface);border-color:var(--tams-border);vertical-align:middle}
            .tams-table tbody tr:hover td{background:var(--tams-soft)}
            .tams-student{display:flex;align-items:center;gap:10px;min-width:190px}
            .tams-avatar{display:inline-flex;align-items:center;justify-content:center;width:38px;height:38px;flex:0 0 38px;color:var(--bs-primary);background:rgba(var(--bs-primary-rgb),.12);border-radius:10px;font-weight:900}
            .tams-name{color:var(--tams-text);font-size:.8rem;font-weight:800}.tams-sub{margin-top:3px;color:var(--tams-muted);font-size:.68rem}
            .tams-stack{display:flex;flex-direction:column;align-items:flex-start;gap:5px}
            .tams-phone{display:flex;align-items:center;justify-content:space-between;gap:12px;width:100%;color:var(--tams-text);font-size:.73rem}
            .tams-phone-label{color:var(--tams-muted);font-size:.66rem}.tams-phone-number{font-weight:700;direction:ltr;unicode-bidi:isolate}
            .tams-badge{display:inline-flex;align-items:center;justify-content:center;gap:5px;padding:5px 8px;border-radius:999px;font-size:.66rem;font-weight:800;white-space:normal;text-align:center}
            .tams-access{display:flex;flex-direction:column;align-items:center;gap:5px;min-width:150px}.tams-access small{color:var(--tams-muted);font-size:.66rem}
            .tams-page .btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;white-space:normal;text-align:center}
            .tams-empty{display:flex;flex-direction:column;align-items:center;gap:10px;padding:52px 20px;color:var(--tams-muted);text-align:center}
            .tams-empty i{display:inline-flex;align-items:center;justify-content:center;width:58px;height:58px;color:var(--bs-primary);background:rgba(var(--bs-primary-rgb),.1);border-radius:50%;font-size:1.35rem}
            .tams-footer{padding:12px 16px;background:var(--tams-soft);border-top:1px solid var(--tams-border)}
            [data-bs-theme="dark"] .tams-page .btn-outline-primary,html.dark .tams-page .btn-outline-primary{color:#93c5fd;border-color:#60a5fa}
            [data-bs-theme="dark"] .tams-page .btn-light,html.dark .tams-page .btn-light{color:#e2e8f0;background:#293246;border-color:#3c475e}
            @media(max-width:767.98px){
                .tams-page{margin-inline:-5px}.tams-page .app-page-head{padding-inline:5px}
                .tams-hero{align-items:stretch;flex-direction:column;padding:15px}.tams-total{justify-content:center;width:100%}
                .tams-toolbar{align-items:stretch;flex-direction:column}.tams-search{width:100%}
                .tams-table-responsive{overflow:visible}.tams-table thead{display:none}.tams-table,.tams-table tbody{display:block}
                .tams-table tbody{padding:10px;background:var(--tams-soft)}
                .tams-table tbody tr{display:block;margin-bottom:10px;padding:4px 12px;background:var(--tams-surface);border:1px solid var(--tams-border);border-radius:10px;box-shadow:0 6px 18px rgba(15,23,42,.05)}
                .tams-table tbody tr:last-child{margin-bottom:0}.tams-table tbody tr:hover td{background:transparent}
                .tams-table tbody td{display:grid;grid-template-columns:minmax(88px,.72fr) minmax(0,1fr);align-items:center;gap:10px;padding:11px 0;background:transparent;border-width:0 0 1px;text-align:right!important}
                .tams-table tbody td:last-child{border-bottom:0}.tams-table tbody td::before{content:attr(data-label);color:var(--tams-muted);font-size:.68rem;font-weight:800}
                .tams-table tbody td.tams-student-cell{display:block;padding-block:13px}.tams-table tbody td.tams-student-cell::before{display:none}
                .tams-student{min-width:0}.tams-stack,.tams-access{align-items:flex-start;min-width:0}.tams-action .btn{width:100%;min-height:40px}
                .tams-empty-row{padding:0!important;border:0!important;background:transparent!important;box-shadow:none!important}.tams-empty-cell{display:block!important;border:0!important}.tams-empty-cell::before{display:none!important}
            }
            @media(max-width:420px){
                .tams-hero-main{align-items:flex-start}.tams-hero-icon{width:42px;height:42px;flex-basis:42px}.tams-hero h3{font-size:1.05rem}
                .tams-table tbody{padding-inline:5px}.tams-table tbody tr{padding-inline:10px}.tams-table tbody td{grid-template-columns:82px minmax(0,1fr)}
            }
        </style>
    @endpush

    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.trial-acquisition.dashboard') }}">مشاوره جذب</a></li>
                <li class="breadcrumb-item active">دانش‌آموزان من</li>
            </ol>
        </nav>
    </div>

    <section class="tams-hero">
        <div class="tams-hero-main">
            <span class="tams-hero-icon"><i class="fi fi-rr-users-alt"></i></span>
            <div>
                <h3>دانش‌آموزان من</h3>
                <p>اطلاعات دانش‌آموزان هفته آزمایشی که برای پیگیری به شما اختصاص داده شده‌اند.</p>
            </div>
        </div>
        <div class="tams-total">
            <i class="fi fi-rr-user"></i>
            <strong>{{ number_format($students->total()) }}</strong>
            <span>دانش‌آموز</span>
        </div>
    </section>

    <section class="tams-shell">
        <div class="tams-toolbar">
            <span class="tams-toolbar-title">فهرست دانش‌آموزان اختصاص‌یافته</span>
            <label class="tams-search">
                <i class="fi fi-rr-search"></i>
                <input type="search" wire:model.live.debounce.400ms="search" class="form-control"
                       placeholder="جست‌وجوی نام یا شماره تماس…" autocomplete="off">
            </label>
        </div>

        <div class="table-responsive tams-table-responsive" wire:loading.class="opacity-50">
            <table class="table tams-table align-middle">
                <thead>
                <tr>
                    <th>دانش‌آموز</th>
                    <th>مشخصات تحصیلی</th>
                    <th>شماره‌های تماس</th>
                    <th class="text-center">وضعیت دسترسی</th>
                    <th class="text-center">کارنامه</th>
                </tr>
                </thead>
                <tbody>
                @forelse($students as $trial)
                    @php
                        $info = $trial->user?->personalInformation;
                        $access = $accessMeta[$trial->id] ?? null;
                        $fullName = trim(($info?->name ?? '') . ' ' . ($info?->name_full ?? '')) ?: ($trial->user?->name ?? '—');
                        $location = collect([$info?->state?->name, $info?->city?->name])->filter()->implode('، ');
                        $gradeLabels = [9 => 'نهم', 10 => 'دهم', 11 => 'یازدهم', 12 => 'دوازدهم', 13 => 'فارغ‌التحصیل'];
                        $fieldLabels = ['math' => 'ریاضی', 'experimental' => 'تجربی', 'human' => 'انسانی'];
                        $gradeLabel = $gradeLabels[(int) $trial->grade] ?? ('پایه ' . $trial->grade);
                        $fieldLabel = (int) $trial->grade === 9 ? 'انتخاب رشته نشده' : ($fieldLabels[$trial->field] ?? ($trial->field ?: '—'));
                    @endphp
                    <tr wire:key="trial-my-student-{{ $trial->id }}">
                        <td class="tams-student-cell">
                            <div class="tams-student">
                                <span class="tams-avatar">{{ mb_substr($fullName, 0, 1) }}</span>
                                <div>
                                    <div class="tams-name">{{ $fullName }}</div>
                                    <div class="tams-sub">{{ $location ?: 'موقعیت ثبت نشده' }}</div>
                                </div>
                            </div>
                        </td>
                        <td data-label="مشخصات تحصیلی">
                            <div class="tams-stack">
                                <span class="tams-badge bg-primary-subtle text-primary">{{ $gradeLabel }}</span>
                                <span class="tams-sub">رشته {{ $fieldLabel }}</span>
                            </div>
                        </td>
                        <td data-label="شماره‌های تماس">
                            <div class="tams-stack">
                                <span class="tams-phone"><span class="tams-phone-label">دانش‌آموز</span><span class="tams-phone-number">{{ $trial->user?->mobile ?? '—' }}</span></span>
                                <span class="tams-phone"><span class="tams-phone-label">پدر</span><span class="tams-phone-number">{{ $trial->father_mobile ?? $info?->father_mobile ?? '—' }}</span></span>
                                <span class="tams-phone"><span class="tams-phone-label">مادر</span><span class="tams-phone-number">{{ $trial->mother_mobile ?? $info?->mother_mobile ?? '—' }}</span></span>
                            </div>
                        </td>
                        <td data-label="وضعیت دسترسی" class="text-center">
                            @if($access)
                                <div class="tams-access">
                                    <span class="tams-badge bg-{{ $access['color'] }}-subtle text-{{ $access['color'] }}">{{ $access['label'] }}</span>
                                    <small>{{ $access['type'] }}</small>
                                    @if($access['ends_at'])
                                        <small>تا {{ jalali($access['ends_at'])->format('%d %B %Y') }}</small>
                                    @endif
                                </div>
                            @else
                                <span class="tams-sub">ثبت نشده</span>
                            @endif
                        </td>
                        <td data-label="کارنامه" class="text-center tams-action">
                            @if(($reportCardAvailable[$trial->id] ?? false) && ($reportCardLinks[$trial->id] ?? null))
                                <a href="{{ $reportCardLinks[$trial->id] }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fi fi-rr-eye"></i> مشاهده کارنامه
                                </a>
                            @else
                                <button type="button" class="btn btn-sm btn-light" disabled>
                                    <i class="fi fi-rr-lock"></i> هنوز فعال نشده
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr class="tams-empty-row">
                        <td colspan="5" class="tams-empty-cell">
                            <div class="tams-empty">
                                <i class="fi fi-rr-users"></i>
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
            <div class="tams-footer">{{ $students->links('layouts.admin.pagination') }}</div>
        @endif
    </section>
</div>
