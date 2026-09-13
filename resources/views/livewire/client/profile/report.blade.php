<div x-data="{
        showReportModal: @entangle('showReportModal'),
        showCompensatoryModal: @entangle('showCompensatoryModal'),
        replyModalOpen: @entangle('replyModalOpen'),
        activeTab: 'submit',
     }"
     x-effect="(showReportModal || showCompensatoryModal || replyModalOpen) ? window.SdfrModalScrollLock.lock() : window.SdfrModalScrollLock.unlock()">

    {{-- اسپینرهای این صفحه حالا از کامپوننت مشترک <x-ui.spinner> میان (رجوع کنید به
         resources/views/components/ui/spinner.blade.php)، و هر سه مودال زیر هم طبق
         الگوی یکسانِ استانداردِ همه‌ی مودال‌های پروژه (SdfrModalScrollLock +
         ترنزیشن + دستگیره + دکمه‌ی بستنِ گرد) بازسازی شده‌اند؛ x-cloak روی خودِ
         هر مودال است، نه روی کل صفحه. --}}
    @once('sdfr-ui-kit-assets')
        @include('components.ui._kit-assets')
    @endonce
    <div class="max-w-7xl space-y-6 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">

            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>

            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-6">

                    {{-- Section Title --}}
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">گزارش های روزانه</div>
                    </div>

                    {{-- Tabs --}}
                    <div class="space-y-5">

                        <x-ui.segmented-tabs
                            :items="[
                                'submit'  => 'ارسال گزارش',
                                'history' => 'گزارش های ارسال شده',
                            ]"
                            :icons="[
                                'submit'  => 'M12 4.5v15m7.5-7.5h-15',
                                'history' => 'M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z',
                            ]"
                            active="submit"
                            @segmented-change="activeTab = $event.detail"
                        />

                        {{-- Tab: Submit --}}
                        <div x-show="activeTab === 'submit'" class="space-y-6">
                            @if(count($missedParts) > 0)
                                <div class="bg-warning/10 border border-warning/30 rounded-2xl p-5">
                                    <div class="flex flex-wrap items-center justify-between gap-4 mb-4">
                                        <div>
                                            <h3 class="font-bold text-warning flex items-center gap-2">
                                                <x-ui.icon name="triangle-alert" class="w-5 h-5"/>
                                                پارت های جبرانی
                                            </h3>
                                            <p class="text-sm text-warning mt-1">{{ count($missedParts) }} پارت از دست رفته دارید</p>
                                        </div>
                                        <x-ui.button type="button" wire:click="openCompensatoryModal" wire:loading.attr="disabled" wire:target="openCompensatoryModal"
                                                     variant="warning">
                                            <span wire:loading.remove wire:target="openCompensatoryModal" class="inline-flex items-center gap-1.5">
                                                ثبت پارت جبرانی <x-ui.icon name="plus" class="w-4 h-4"/>
                                            </span>
                                            <span wire:loading wire:target="openCompensatoryModal">
                                                <x-ui.spinner size="xs"/>
                                            </span>
                                        </x-ui.button>
                                    </div>
                                </div>
                            @endif

                            @if($currentSession && $currentProgram)

                                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                                    @foreach($weekDays as $dayIndex => $day)
                                        <div wire:key="day-{{ $dayIndex }}"
                                             class="glass border rounded-2xl p-4 transition-colors
 {{ $day['is_submitted'] ? 'border-success/50' : '' }}
 {{ $day['can_submit'] ? 'border-info' : '' }}
 {{ $day['is_rest_day'] ? 'border-success/50' : '' }}
 {{ !$day['is_submitted'] && !$day['can_submit'] && !$day['is_rest_day'] ? 'border-border' : '' }}">

                                            <div class="flex items-center justify-between mb-3">
                                                <div>
                                                    <h4 class="font-bold {{ $day['is_rest_day'] ? 'text-success' : 'text-foreground' }}">{{ $day['name'] }}</h4>
                                                    <p class="text-xs text-muted">{{ $day['jalali_date'] }}</p>
                                                </div>

                                                @if($day['is_rest_day'])
                                                    <span class="text-success">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z"/>
                                                        </svg>
                                                    </span>
                                                @elseif($day['is_submitted'])
                                                    <span class="text-success">
                                                        <x-ui.icon name="circle-check" class="w-6 h-6"/>
                                                    </span>
                                                @elseif($day['is_locked'])
                                                    <span class="text-error">
                                                        <x-ui.icon name="lock" class="w-6 h-6"/>
                                                    </span>
                                                @elseif($day['can_submit'])
                                                    <span class="text-info animate-pulse">
                                                        <x-ui.icon name="clock" class="w-6 h-6"/>
                                                    </span>
                                                @endif
                                            </div>

                                            @if($day['is_rest_day'])
                                                <div class="text-center py-4">
                                                    <div class="text-2xl mb-2">🌿</div>
                                                    <p class="text-sm text-success font-medium">روز استراحت</p>
                                                    <p class="text-xs text-success/70 mt-1">نیازی به ارسال گزارش نیست</p>
                                                </div>
                                            @else
                                                <div class="space-y-2 mb-4">
                                                    <div class="flex items-center justify-between text-sm">
                                                        <span class="text-muted">تعداد پارت:</span>
                                                        <span class="font-medium text-foreground">{{ count($day['parts']) }}</span>
                                                    </div>
                                                    <div class="flex items-center justify-between text-sm">
                                                        <span class="text-muted">تعداد تست:</span>
                                                        <span class="font-medium text-foreground">{{ $day['total_tests'] }}</span>
                                                    </div>
                                                </div>

                                                @if($day['can_submit'])
                                                    {{-- مودال بلافاصله با Alpine باز می‌شود (بدون لگ)، و داده‌ها با Livewire بارگذاری می‌شوند --}}
                                                    <x-ui.button type="button" @click="showReportModal = true" wire:click="openReportModal({{ $dayIndex }})" wire:loading.attr="disabled" wire:target="openReportModal({{ $dayIndex }})"
                                                                 variant="primary" block>
                                                        <span wire:loading.remove wire:target="openReportModal({{ $dayIndex }})" class="inline-flex items-center gap-1.5">
                                                            ثبت گزارش <x-ui.icon name="square-pen" class="w-4 h-4"/>
                                                        </span>
                                                        <span wire:loading wire:target="openReportModal({{ $dayIndex }})">
                                                            <x-ui.spinner size="xs"/>
                                                        </span>
                                                    </x-ui.button>
                                                @elseif($day['is_submitted'])
                                                    <div class="text-center text-success text-sm font-medium py-2">گزارش ثبت شده</div>
                                                @elseif($day['is_locked'])
                                                    <div class="text-center text-error text-sm font-medium py-2">مهلت تمام شده</div>
                                                @else
                                                    <div class="text-center text-muted text-sm py-2">در انتظار</div>
                                                @endif
                                            @endif
                                        </div>
                                    @endforeach
                                </div>

                            @else
                                <x-ui.empty-state title="گزارشی وجود ندارد !">
                                    پس از برگزاری جلسه مشاوره، گزارش های روزانه شما در اینجا ثبت می‌شود.
                                </x-ui.empty-state>
                            @endif
                        </div>

                        {{-- Tab: History --}}
                        <div x-show="activeTab === 'history'" x-cloak class="space-y-5">

                            {{-- ورق‌زدن صفحات یک رفت‌وبرگشت لایوایره؛ تا رسیدن صفحه‌ی جدید به‌جای
                                 خالی/پرش ناگهانی لیست، این لودینگ نشون داده می‌شه --}}
                            <div wire:loading.flex wire:target="previousPage,nextPage,gotoPage" class="hidden flex-col items-center justify-center gap-3 py-16">
                                <x-ui.spinner size="lg" class="text-primary" />
                                <span class="text-sm text-muted">در حال بارگذاری...</span>
                            </div>

                            <div wire:loading.remove wire:target="previousPage,nextPage,gotoPage">
                            @if($reports->isNotEmpty())
                                <div class="space-y-4 sm:space-y-5">
                                    @foreach($reports as $report)
                                        <div wire:key="report-{{ $report->id }}"
                                             class="glass rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-300 border border-border">

                                            <div class="bg-gradient-to-r from-primary/10 via-info/10 to-info/10 px-4 sm:px-6 py-3 sm:py-4 border-b border-border">
                                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                                    <div class="flex items-center gap-3 sm:gap-4">
                                                        <div class="flex h-11 w-11 sm:h-14 sm:w-14 items-center justify-center rounded-xl bg-gradient-to-br from-info to-info/70 text-white shadow-lg shadow-info/30">
                                                            <svg class="w-5 h-5 sm:w-7 sm:h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                            </svg>
                                                        </div>
                                                        <div>
                                                            <h3 class="text-base sm:text-lg font-black text-foreground">{{ $report->day_name }}</h3>
                                                            <div class="flex items-center gap-2 mt-0.5">
                                                                <p class="text-xs sm:text-sm text-muted">{{ jdate($report->report_date)->format('Y/m/d') }}</p>
                                                                @if($report->is_compensatory)
                                                                    <span class="inline-flex items-center gap-1 bg-warning/20 text-warning text-[10px] sm:text-xs px-2 py-0.5 rounded-full font-bold">
                                                                        <x-ui.icon name="check" class="w-3 h-3"/>
                                                                        جبرانی
                                                                    </span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>

                                                    @php
                                                        $reportStatusKey = match($report->status) {
                                                            'approved' => 'paid',
                                                            'rejected' => 'voided',
                                                            default    => 'pending',
                                                        };
                                                        $reportStatusLabel = match($report->status) {
                                                            'approved' => 'تایید شده',
                                                            'rejected' => 'رد شده',
                                                            default    => 'در انتظار بررسی',
                                                        };
                                                    @endphp
                                                    <div>
                                                        <x-ui.status-badge :status="$reportStatusKey" :label="$reportStatusLabel"/>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="p-4 sm:p-6">
                                                <div class="grid grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4 mb-4">

                                                    <div class="bg-muted/30 rounded-xl p-3 sm:p-4">
                                                        <div class="flex items-center gap-2 mb-2">
                                                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-success/20">
                                                                <svg class="w-4 h-4 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                                </svg>
                                                            </div>
                                                            <span class="text-xs text-muted font-semibold">پارت خوانده</span>
                                                        </div>
                                                        <div class="flex items-baseline gap-1.5">
                                                            <span class="text-2xl sm:text-3xl font-black text-success">{{ $report->read_parts_count }}</span>
                                                            <span class="text-sm text-muted font-medium">/</span>
                                                            <span class="text-base text-foreground font-bold">{{ $report->total_parts }}</span>
                                                        </div>
                                                    </div>

                                                    <div class="bg-muted/30 rounded-xl p-3 sm:p-4">
                                                        <div class="flex items-center gap-2 mb-2">
                                                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-info/20">
                                                                <svg class="w-4 h-4 text-info" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                                                </svg>
                                                            </div>
                                                            <span class="text-xs text-muted font-semibold">تست زده</span>
                                                        </div>
                                                        <div class="text-2xl sm:text-3xl font-black text-info">{{ $report->reportParts->sum('tests_done') }}</div>
                                                    </div>

                                                    <div class="bg-muted/30 rounded-xl p-3 sm:p-4">
                                                        @php
                                                            $rpt = $report->calculated_rating;
                                                            $rptColorName = $this->getRatingColor($rpt);
                                                        @endphp
                                                        <div class="flex items-center gap-2 mb-2">
                                                            <div @class([
                                                                'flex h-8 w-8 items-center justify-center rounded-lg',
                                                                'bg-success/20' => $rptColorName === 'emerald',
                                                                'bg-info/20' => $rptColorName === 'blue',
                                                                'bg-warning/20' => $rptColorName === 'yellow',
                                                                'bg-warning/20' => $rptColorName === 'orange',
                                                                'bg-error/20' => $rptColorName === 'red',
                                                                'bg-muted/20' => $rptColorName === 'gray',
                                                            ])>
                                                                <svg @class([
                                                                    'w-4 h-4',
                                                                    'text-success' => $rptColorName === 'emerald',
                                                                    'text-info' => $rptColorName === 'blue',
                                                                    'text-warning' => $rptColorName === 'yellow',
                                                                    'text-warning' => $rptColorName === 'orange',
                                                                    'text-error' => $rptColorName === 'red',
                                                                    'text-muted' => $rptColorName === 'gray',
                                                                ]) fill="currentColor" viewBox="0 0 24 24">
                                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                                </svg>
                                                            </div>
                                                            <span class="text-xs text-muted font-semibold">امتیاز</span>
                                                        </div>
                                                        @if($rpt > 0)
                                                            <div class="flex items-baseline gap-1">
                                                                <span @class([
                                                                    'text-2xl sm:text-3xl font-black',
                                                                    'text-success' => $rptColorName === 'emerald',
                                                                    'text-info' => $rptColorName === 'blue',
                                                                    'text-warning' => $rptColorName === 'yellow',
                                                                    'text-warning' => $rptColorName === 'orange',
                                                                    'text-error' => $rptColorName === 'red',
                                                                    'text-muted' => $rptColorName === 'gray',
                                                                ])>{{ $rpt }}</span>
                                                                <span class="text-xs text-muted font-medium">/ 10</span>
                                                            </div>
                                                            <span @class([
                                                                'inline-flex items-center mt-1 text-xs font-bold px-2 py-0.5 rounded-lg',
                                                                'bg-success/20 text-success' => $rptColorName === 'emerald',
                                                                'bg-info/20 text-info' => $rptColorName === 'blue',
                                                                'bg-warning/20 text-warning' => $rptColorName === 'yellow',
                                                                'bg-warning/20 text-warning' => $rptColorName === 'orange',
                                                                'bg-error/20 text-error' => $rptColorName === 'red',
                                                                'bg-muted/20 text-muted' => $rptColorName === 'gray',
                                                            ])>
                                                                {{ $this->getRatingLabel($rpt) }}
                                                            </span>
                                                        @else
                                                            <span class="text-sm text-muted">ثبت نشده</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                @php
                                                    $rptDesc = $report->detail?->description;
                                                    $rptMissed = $report->detail?->missed_parts_reason;
                                                @endphp
                                                @if($rptDesc || $rptMissed)
                                                    <div class="mb-3 space-y-2">
                                                        @if($rptDesc)
                                                            <div class="bg-muted/20 rounded-xl p-3 border border-border">
                                                                <p class="text-xs font-semibold text-muted mb-1">توضیحات:</p>
                                                                <p class="text-sm text-foreground leading-6">{{ $rptDesc }}</p>
                                                            </div>
                                                        @endif
                                                        @if($rptMissed)
                                                            <div class="bg-error/5 rounded-xl p-3 border border-error/30">
                                                                <p class="text-xs font-semibold text-error mb-1">علت عدم انجام پارت:</p>
                                                                <p class="text-sm text-foreground leading-6">{{ $rptMissed }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif

                                                @if($report->advisor_comment)
                                                    <div class="bg-gradient-to-br from-primary/10 via-info/10 to-info/10 rounded-xl p-4 sm:p-5 border-2 border-primary/20">
                                                        <div class="flex items-start gap-3 mb-3">
                                                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/20 shrink-0">
                                                                <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                                                </svg>
                                                            </div>
                                                            <div class="flex-1">
                                                                <h4 class="text-sm sm:text-base font-black text-primary mb-1">نظر مشاور</h4>
                                                                <p class="text-xs sm:text-sm text-muted">مشاور شما پاسخی برای این گزارش ثبت کرده است</p>
                                                            </div>
                                                        </div>
                                                        <x-ui.button type="button" wire:click="openReplyModal({{ $report->id }})" wire:loading.attr="disabled" wire:target="openReplyModal({{ $report->id }})"
                                                                     variant="primary" size="lg" class="w-full sm:w-auto">
                                                            <span wire:loading.remove wire:target="openReplyModal({{ $report->id }})" class="inline-flex items-center gap-2">
                                                                مشاهده نظر کامل <x-ui.icon name="eye" class="w-4 h-4 sm:w-5 sm:h-5"/>
                                                            </span>
                                                            <span wire:loading wire:target="openReplyModal({{ $report->id }})">
                                                                <x-ui.spinner size="sm"/>
                                                            </span>
                                                        </x-ui.button>
                                                    </div>
                                                @else
                                                    <div class="bg-muted/30 rounded-xl p-4 border border-border">
                                                        <div class="flex items-center gap-3">
                                                            <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-muted/50">
                                                                <svg class="w-5 h-5 text-muted" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                            </div>
                                                            <div>
                                                                <p class="text-sm font-semibold text-foreground">در انتظار بررسی</p>
                                                                <p class="text-xs text-muted mt-0.5">مشاور هنوز نظری ثبت نکرده است</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-6 sm:mt-8">
                                    {{ $reports->links('components.ui.pagination') }}
                                </div>

                            @else
                                <x-ui.empty-state title="گزارشی ثبت نشده است">
                                    گزارش‌های روزانه ارسال شده شما در اینجا نمایش داده می‌شود.
                                </x-ui.empty-state>
                            @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ═════════════════════ Report Modal ═════════════════════ --}}
    <div x-show="showReportModal" x-cloak>
        <div
            x-show="showReportModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[60] bg-black/60 backdrop-blur-sm"
            @click="showReportModal = false; $wire.closeReportModal()"
            @keydown.escape.window="showReportModal = false; $wire.closeReportModal()"
        ></div>

        <div
            x-show="showReportModal"
            class="fixed inset-0 z-[61] flex items-end justify-center overscroll-contain sm:items-center sm:p-4"
            @click.self="showReportModal = false; $wire.closeReportModal()"
        >
            <div
                x-show="showReportModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                class="relative w-full sm:max-w-2xl max-h-[85vh] sm:max-h-[88vh] overflow-hidden bg-background border border-border rounded-t-3xl sm:rounded-2xl shadow-2xl flex flex-col pb-[env(safe-area-inset-bottom,80px)] sm:pb-0"
            >
                <div class="mx-auto mt-3 mb-1 h-1.5 w-14 rounded-full bg-border sm:hidden shrink-0"></div>

                <button type="button" @click="showReportModal = false; $wire.closeReportModal()" data-elevated="false"
                        class="btn-press absolute top-4 left-4 w-8 h-8 inline-flex items-center justify-center rounded-full text-muted hover:text-foreground hover:bg-secondary transition-colors z-10">
                    <x-ui.icon name="x" class="w-4 h-4"/>
                </button>

                @if(isset($weekDays[$selectedDayIndex]))
                    @php $selectedDay = $weekDays[$selectedDayIndex]; @endphp

                    <div class="shrink-0 border-b border-border px-4 sm:px-6 py-3 sm:py-4">
                        <h3 class="text-base sm:text-lg font-bold text-foreground">ثبت گزارش روزانه</h3>
                        <p class="text-xs sm:text-sm text-muted">{{ $selectedDay['name'] }} - {{ $selectedDay['jalali_short'] }}</p>
                    </div>

                    <div class="flex-1 overflow-y-auto px-4 sm:px-6 py-4 sm:py-5 space-y-4 sm:space-y-5">

                        @if($errors->any())
                            <div class="rounded-xl border border-error/30 bg-error/10 p-3.5 sm:p-4" role="alert">
                                <div class="flex items-start gap-2.5">
                                    <x-ui.icon name="triangle-alert" class="w-5 h-5 text-error shrink-0 mt-0.5"/>
                                    <div class="min-w-0">
                                        <p class="font-bold text-sm text-error">لطفاً موارد زیر را اصلاح کنید:</p>
                                        <ul class="mt-2 space-y-1 text-xs sm:text-sm text-error list-disc list-inside">
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endif

                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <label class="font-semibold text-foreground text-sm sm:text-base">پارت‌های خوانده شده:</label>
                            <span class="text-xs sm:text-sm text-muted">
                                <span class="font-medium text-success">{{ count($selectedParts) }}</span> / {{ count($selectedDay['parts']) }}
                            </span>
                        </div>
                        @if(count($selectedParts) > 0)
                            <div class="flex items-center gap-2 px-3 py-2 rounded-lg bg-success/10 border border-success/30">
                                <x-ui.icon name="circle-check" class="w-4 h-4 text-success shrink-0"/>
                                <span class="text-xs text-success font-medium">{{ count($selectedParts) }} پارت بر اساس ثبت ساعت مطالعه شما خودکار انتخاب شدند.</span>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 gap-2.5 sm:gap-3">
                            @foreach($selectedDay['parts'] as $part)
                                @php
                                    $partHasStudyHours = in_array($part->id, $completedStudyParts);
                                    $partIsRejected = in_array($part->id, $rejectedCheatPartIds);
                                    $partIsSelected = in_array($part->id, $selectedParts);
                                    $partIsLocked = ($partHasStudyHours && $partIsSelected) || $partIsRejected;
                                @endphp
                                <div wire:key="part-select-{{ $part->id }}"
                                     class="relative bg-background rounded-xl border-2 transition-all duration-200
 {{ $partIsRejected ? 'border-error bg-error/30' : ($partIsSelected ? 'border-success bg-success/50' : 'border-transparent') }}
 {{ !$partHasStudyHours && !$partIsRejected ? 'border-error opacity-70' : '' }}">

                                    @if($partIsRejected)
                                        <div class="flex items-center gap-2 px-3 sm:px-3.5 pt-2.5 pb-1">
                                            <x-ui.icon name="triangle-alert" class="w-4 h-4 text-error shrink-0"/>
                                            <span class="text-[10px] sm:text-xs text-error font-semibold">گزارش تقلب این پارت توسط مشاور رد شده — انتخاب اجباری</span>
                                        </div>
                                    @elseif(!$partHasStudyHours)
                                        <div class="flex items-center gap-2 px-3 sm:px-3.5 pt-2.5 pb-1">
                                            <x-ui.icon name="triangle-alert" class="w-4 h-4 text-error shrink-0"/>
                                            <span class="text-[10px] sm:text-xs text-error font-semibold">ساعت مطالعه ثبت نشده — ابتدا از بخش «ثبت ساعت مطالعه» اقدام کنید</span>
                                        </div>
                                    @elseif($partIsLocked)
                                        <div class="flex items-center gap-2 px-3 sm:px-3.5 pt-2.5 pb-1">
                                            <x-ui.icon name="lock" class="w-4 h-4 text-success shrink-0"/>
                                            <span class="text-[10px] sm:text-xs text-success font-semibold">ساعت مطالعه ثبت شده — انتخاب خودکار</span>
                                        </div>
                                    @endif

                                    <div wire:click="togglePart({{ $part->id }})"
                                         class="flex items-start gap-2.5 sm:gap-3 p-3 sm:p-3.5 {{ !$partHasStudyHours ? 'cursor-not-allowed' : ($partIsLocked ? 'cursor-default' : 'cursor-pointer') }}">
                                        <div class="mt-0.5 shrink-0 {{ $partIsSelected ? 'text-success' : (!$partHasStudyHours ? 'text-error' : 'text-muted') }}">
                                            @if($partIsSelected)
                                                <x-ui.icon name="circle-check" class="w-5 h-5 sm:w-6 sm:h-6"/>
                                            @elseif(!$partHasStudyHours)
                                                <x-ui.icon name="lock" class="w-5 h-5 sm:w-6 sm:h-6"/>
                                            @else
                                                <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            @endif
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold {{ !$partHasStudyHours ? 'text-muted' : 'text-foreground' }} text-sm sm:text-base line-clamp-1">{{ $part->lesson_name }}@include('livewire.client.profile.partials.part-chapter-label', ['part' => $part])</h4>

                                            <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 mt-1">
                                                @if($part->source_type && $part->source_type !== 'normal')
                                                    <span class="text-[10px] sm:text-xs rounded-full px-2 py-0.5 font-medium {{ $part->source_type_tw_class }}">{{ $part->source_type_label }}</span>
                                                @endif
                                                @php $partMeta = $completedStudyPartsMeta[$part->id] ?? null; @endphp
                                                @if($partMeta && (
                                                    ($partMeta['is_early_finish'] ?? false) ||
                                                    ($partMeta['extra_seconds'] ?? 0) > 0 ||
                                                    ($partMeta['is_cheating'] ?? false)
                                                ))
                                                    <x-study-session-badges
                                                        :is-early-finish="(bool)($partMeta['is_early_finish'] ?? false)"
                                                        :extra-seconds="(int)($partMeta['extra_seconds'] ?? 0)"
                                                        :extra-target-seconds="(int)($partMeta['extra_target_seconds'] ?? 0)"
                                                        :is-cheating="(bool)($partMeta['is_cheating'] ?? false)"
                                                        :cheat-status="$partMeta['cheat_status'] ?? null"
                                                        :cheat-minutes="(int)($partMeta['cheat_minutes'] ?? 0)" />
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2 mt-1.5 text-xs text-muted">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                                    {{ $part->duration_minutes }} دقیقه
                                                </span>
                                                @if($part->test_count)
                                                    <span class="text-muted/50">•</span>
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                                        {{ $part->test_count }} تست
                                                    </span>
                                                @endif
                                                <span class="flex items-center gap-1 mr-auto" wire:loading wire:target="togglePart({{ $part->id }})"><x-ui.spinner size="sm" /></span>
                                            </div>
                                        </div>
                                    </div>

                                    @if($partIsSelected && $part->test_count)
                                        <div class="px-3 sm:px-3.5 pb-3 sm:pb-3.5 space-y-2.5 border-border/50" wire:click.stop>
                                            <div>
                                                <label class="text-xs text-muted block mb-1">
                                                    تعداد تست زده شده:
                                                    <span class="text-error font-semibold">(اجباری — حداقل ۰)</span>
                                                </label>
                                                <input type="number" wire:model="testsDone.{{ $part->id }}" min="0" max="{{ $part->test_count }}"
                                                       class="w-full h-9 sm:h-10 rounded-lg border {{ $errors->has('testsDone.'.$part->id) ? 'border-error' : 'border-border' }} bg-background text-foreground text-sm px-3 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                                       placeholder="عدد وارد کنید (از ۰ تا {{ $part->test_count }})">
                                                @error('testsDone.'.$part->id)<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    @php $unreadCount = $this->unreadPartsCount; @endphp

                    @if($unreadCount >= 2)
                        <div class="space-y-2">
                            <label class="font-semibold text-foreground text-sm sm:text-base flex items-center gap-2">
                                <svg class="w-4 h-4 text-error" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                علت عدم انجام پارت:
                                <span class="text-error text-xs font-normal">(اجباری)</span>
                            </label>
                            <p class="text-xs text-muted">{{ $unreadCount }} پارت از برنامه امروز انجام نشده — لطفاً دلیل را توضیح دهید.</p>
                            <textarea wire:model="missedPartsReason" rows="3"
                                      class="w-full rounded-xl border {{ $errors->has('missedPartsReason') ? 'border-error' : 'border-error' }} bg-background text-foreground px-4 py-3 text-sm sm:text-base resize-none focus:ring-2 focus:ring-error/20 focus:border-error transition-all"
                                      placeholder="لطفاً توضیح دهید چرا پارت‌های مطالعاتی انجام نشدند..."></textarea>
                            @error('missedPartsReason')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    @else
                        <div class="space-y-2">
                            <label class="font-semibold text-foreground text-sm sm:text-base flex items-center gap-2">
                                <svg class="w-4 h-4 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                توضیحات
                                <span class="text-muted text-xs font-normal">(اختیاری)</span>
                            </label>
                            <textarea wire:model="description" rows="3"
                                      class="w-full rounded-xl border border-border bg-secondary text-foreground px-4 py-3 text-sm sm:text-base resize-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                      placeholder="اگر توضیحی دارید اینجا بنویسید..."></textarea>
                            @error('description')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    @endif


                    @if(count($currentDayMakeupSessions) > 0)
                        <div class="rounded-xl border border-info/30 bg-info/10 p-3.5 sm:p-4 space-y-2">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-info shrink-0" viewBox="0 0 24 24" fill="currentColor">
                                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" clip-rule="evenodd"/>
                                </svg>
                                <span class="font-semibold text-sm text-info">مطالعه اضافه بر برنامه (خودکار ضمیمه می‌شود):</span>
                            </div>
                            <div class="space-y-1.5">
                                @foreach($currentDayMakeupSessions as $ms)
                                    <div class="flex items-center justify-between text-xs text-info bg-info/15 rounded-lg px-3 py-1.5">
                                        <span class="font-medium">
                                            @if($ms['subject_name']){{ $ms['subject_name'] }}@endif
                                            @if($ms['chapter_name']) <span class="text-info">«</span> {{ $ms['chapter_name'] }}@elseif(!$ms['subject_name'])نامشخص@endif
                                        </span>
                                        <div class="flex items-center gap-2 text-info">
                                            <span>{{ $ms['part_type_label'] }}</span>
                                            @if($ms['duration_minutes'] > 0)
                                                <span class="text-info">•</span>
                                                <span>{{ $ms['duration_minutes'] }} دقیقه</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-[10px] text-info">این موارد به‌عنوان گزارش اضافه بر سازمان برای {{ $this->reportRecipientLabel }} ارسال می‌شوند.</p>
                        </div>
                    @endif

                    @php $computedRating = $this->computedRating; @endphp
                    <div class="bg-gradient-to-br from-primary/5 to-primary/10 border border-primary/20 rounded-xl p-3.5 sm:p-4">
                        <div class="flex items-center justify-between gap-3">
                            <span class="font-semibold text-foreground text-sm sm:text-base flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></svg>
                                امتیاز کلی روز:
                            </span>
                            @if($computedRating > 0)
                                @php
                                    $ratingColorName = $this->getRatingColor($computedRating);
                                @endphp
                                <div class="flex items-center gap-2">
                                    <span @class([
                                        'text-2xl font-black',
                                        'text-success' => $ratingColorName === 'emerald',
                                        'text-info' => $ratingColorName === 'blue',
                                        'text-warning' => $ratingColorName === 'yellow',
                                        'text-warning' => $ratingColorName === 'orange',
                                        'text-error' => $ratingColorName === 'red',
                                    ])>{{ $computedRating }}</span>
                                    <span class="text-muted text-sm">/ 10</span>
                                    <span @class([
                                        'text-xs sm:text-sm font-bold px-2 py-1 rounded-lg',
                                        'bg-success/20 text-success' => $ratingColorName === 'emerald',
                                        'bg-info/20 text-info' => $ratingColorName === 'blue',
                                        'bg-warning/20 text-warning' => $ratingColorName === 'yellow',
                                        'bg-warning/20 text-warning' => $ratingColorName === 'orange',
                                        'bg-error/20 text-error' => $ratingColorName === 'red',
                                    ])>
                                        {{ $this->getRatingLabel($computedRating) }}
                                    </span>
                                </div>
                            @else
                                <span class="text-muted text-xs sm:text-sm">امتیازی از ثبت ساعت مطالعه یافت نشد</span>
                            @endif
                        </div>
                    </div>
                </div>

                    <div class="shrink-0 bg-secondary border-t border-border px-4 sm:px-6 py-3 sm:py-4">
                        <div class="flex items-center justify-end gap-2 sm:gap-3">
                            <x-ui.button type="button" @click="showReportModal = false; $wire.closeReportModal()"
                                         variant="secondary-outline" icon="x">
                                انصراف
                            </x-ui.button>
                            <x-ui.button type="button" wire:click="submitReport" wire:loading.attr="disabled" wire:target="submitReport"
                                         variant="primary" class="min-w-[120px]">
                                <span wire:loading.remove wire:target="submitReport" class="inline-flex items-center gap-1.5">
                                    ثبت گزارش <x-ui.icon name="check" class="w-4 h-4"/>
                                </span>
                                <span wire:loading wire:target="submitReport">
                                    <x-ui.spinner size="xs"/>
                                </span>
                            </x-ui.button>
                        </div>
                    </div>
                @else
                    {{-- حالت بارگذاری: تا رسیدن اطلاعات روز از سرور --}}
                    <div class="flex-1 flex flex-col items-center justify-center gap-3 p-12">
                        <x-ui.spinner class="text-primary" />
                        <span class="text-sm text-muted">در حال بارگذاری اطلاعات…</span>
                    </div>
                @endif
            </div>
        </div>
    </div>


    {{-- ═════════════════════ Compensatory Modal ═════════════════════ --}}
    <div x-show="showCompensatoryModal" x-cloak>
        <div
            x-show="showCompensatoryModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[80] bg-black/60 backdrop-blur-sm"
            @click="showCompensatoryModal = false; $wire.closeCompensatoryModal()"
            @keydown.escape.window="showCompensatoryModal = false; $wire.closeCompensatoryModal()"
        ></div>

        <div
            x-show="showCompensatoryModal"
            class="fixed inset-0 z-[81] flex items-end justify-center overscroll-contain sm:items-center sm:p-4"
            @click.self="showCompensatoryModal = false; $wire.closeCompensatoryModal()"
        >
            <div
                x-show="showCompensatoryModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                class="relative w-full sm:max-w-2xl max-h-[90vh] overflow-hidden bg-background border border-border rounded-t-3xl sm:rounded-2xl shadow-2xl flex flex-col pb-[env(safe-area-inset-bottom,0px)] sm:pb-0"
            >
                <div class="mx-auto mt-3 mb-1 h-1.5 w-14 rounded-full bg-border sm:hidden shrink-0"></div>

                <button type="button" @click="showCompensatoryModal = false; $wire.closeCompensatoryModal()" data-elevated="false"
                        class="btn-press absolute top-4 left-4 w-8 h-8 inline-flex items-center justify-center rounded-full text-muted hover:text-foreground hover:bg-secondary transition-colors z-10">
                    <x-ui.icon name="x" class="w-4 h-4"/>
                </button>

                <div class="shrink-0 border-b border-border px-6 py-4">
                    <h3 class="text-lg font-bold text-foreground">ثبت پارت جبرانی</h3>
                    <p class="text-sm text-muted">
                        @if($compensatoryStep === 1) مرحله ۱: پارت های خوانده شده را انتخاب کنید
                        @else مرحله ۲: جزئیات گزارش را وارد کنید @endif
                    </p>
                    <div class="flex items-center gap-2 mt-4">
                        <div class="flex-1 h-2 rounded-full {{ $compensatoryStep >= 1 ? 'bg-warning' : 'bg-border' }}"></div>
                        <div class="flex-1 h-2 rounded-full {{ $compensatoryStep >= 2 ? 'bg-warning' : 'bg-border' }}"></div>
                    </div>
                </div>

                <div class="flex-1 overflow-y-auto px-6 py-5 space-y-4">

                    @if($compensatoryStep === 1)
                        @foreach($missedParts as $missed)
                            @php $compPartHasStudyHours = in_array($missed['part']->id, $completedStudyParts); @endphp

                            <div @if($missed['has_study']) wire:click="toggleCompensatoryPart({{ $missed['part']->id }})" @endif
                            wire:key="comp-part-{{ $missed['part']->id }}"
                                 class="p-4 bg-muted/30 rounded-xl transition-all duration-200
 {{ in_array($missed['part']->id, $selectedCompensatoryParts) ? 'border-2 border-success bg-success/50' : 'border-2 border-transparent' }}
 {{ !$missed['has_study'] ? 'border-error opacity-70 cursor-not-allowed' : 'cursor-pointer' }}">

                                @if(!$missed['has_study'])

                                    <div class="flex items-center justify-between gap-2 mb-2 flex-wrap">
                                        <div class="flex items-center gap-2">
                                            <x-ui.icon name="triangle-alert" class="w-4 h-4 text-error shrink-0"/>
                                            <span class="text-[10px] sm:text-xs text-error font-semibold">ساعت مطالعه این پارت هنوز ثبت نشده</span>
                                        </div>
                                        @if($currentProgram)
                                            <x-ui.button href="{{ route('client.profile.consultation.weekly-program', ['program' => $currentProgram->id, 'focus_part' => $missed['part']->id]) }}"
                                                         wire:navigate variant="warning" size="sm" pill icon="chevron-left">
                                                شروع و ثبت ساعت مطالعه
                                            </x-ui.button>
                                        @endif
                                    </div>
                                @endif

                                <div class="flex items-start gap-3">
                                    <div class="mt-1 {{ in_array($missed['part']->id, $selectedCompensatoryParts) ? 'text-success' : (!$compPartHasStudyHours ? 'text-error' : 'text-muted') }}">
                                        @if(in_array($missed['part']->id, $selectedCompensatoryParts))
                                            <x-ui.icon name="circle-check" class="w-5 h-5"/>
                                        @elseif(!$compPartHasStudyHours)
                                            <x-ui.icon name="lock" class="w-5 h-5"/>
                                        @else
                                        <svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs bg-warning/20 text-warning px-2 py-0.5 rounded-full">
                                            {{ $missed['day_name'] }} - {{ $missed['jalali_date'] }}
                                        </span>
                                    </div>
                                    <h4 class="font-medium {{ !$compPartHasStudyHours ? 'text-muted' : 'text-foreground' }} text-sm">{{ $missed['part']->lesson_name }}@include('livewire.client.profile.partials.part-chapter-label', ['part' => $missed['part']])</h4>
                                    <div class="flex flex-wrap items-center gap-1.5 mt-1">
                                        @if($missed['part']->source_type && $missed['part']->source_type !== 'normal')
                                            <span class="text-[10px] sm:text-xs rounded-full px-2 py-0.5 font-medium {{ $missed['part']->source_type_tw_class }}">{{ $missed['part']->source_type_label }}</span>
                                        @endif
                                    </div>
                                </div>
                                <x-ui.spinner size="sm" class="text-muted" wire:loading wire:target="toggleCompensatoryPart({{ $missed['part']->id }})" />
                            </div>
                        </div>
                    @endforeach

                @else
                    <div class="bg-warning/10 border border-warning/30 rounded-xl p-4 mb-4">
                        <h4 class="font-semibold text-warning mb-3">پارت‌های انتخاب شده:</h4>
                        <div class="space-y-4">
                            @foreach($missedParts as $missed)
                                @if(in_array($missed['part']->id, $selectedCompensatoryParts))
                                    <div class="pb-3 border-b border-warning/50 last:border-0 last:pb-0">
                                        <div class="flex items-center justify-between text-sm mb-2 flex-wrap gap-2">
                                            <span class="text-foreground font-medium">
                                                {{ $missed['part']->lesson_name }}@include('livewire.client.profile.partials.part-chapter-label', ['part' => $missed['part']])
                                            </span>
                                            @if($missed['part']->test_count)
                                                <div class="flex items-center gap-2">
                                                    <label class="text-xs text-muted">تست زده:</label>
                                                    <input type="number" wire:model="compensatoryTestsDone.{{ $missed['part']->id }}"
                                                           min="0" max="{{ $missed['part']->test_count }}"
                                                           class="w-20 h-8 rounded-lg border border-border bg-secondary text-foreground text-sm px-2 text-center"
                                                           placeholder="{{ $missed['part']->test_count }}">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="font-semibold text-foreground flex items-center gap-2">
                            <x-ui.icon name="square-pen" class="w-4 h-4 text-muted"/>
                            علت عدم انجام پارت
                            <span class="text-muted text-xs font-normal">(اختیاری)</span>
                        </label>
                        <textarea wire:model="compensatoryMissedPartsReason" rows="3"
                                  class="w-full rounded-xl border border-border bg-secondary text-foreground px-4 py-3 resize-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                  placeholder="در صورت تمایل توضیح دهید چرا پارت‌ها در زمان اصلی انجام نشدند..."></textarea>
                        @error('compensatoryMissedPartsReason')<p class="text-error text-xs">{{ $message }}</p>@enderror
                    </div>

                    @endif
                </div>

                <div class="shrink-0 bg-secondary border-t border-border px-6 py-4 flex items-center justify-between flex-wrap gap-3">
                    <p class="text-sm text-muted">
                        انتخاب شده: <span class="font-medium text-success">{{ count($selectedCompensatoryParts) }}</span>
                        از <span class="font-medium">{{ count($missedParts) }}</span>
                    </p>
                    <div class="flex items-center gap-3">
                        @if($compensatoryStep === 1)
                            <x-ui.button type="button" @click="showCompensatoryModal = false; $wire.closeCompensatoryModal()"
                                         variant="secondary-outline" icon="x">
                                انصراف
                            </x-ui.button>
                            <x-ui.button type="button" wire:click="goToCompensatoryStep2" wire:loading.attr="disabled" wire:target="goToCompensatoryStep2"
                                         variant="warning" class="min-w-[120px]">
                                <span wire:loading.remove wire:target="goToCompensatoryStep2" class="inline-flex items-center gap-1.5">
                                    مرحله بعد <x-ui.icon name="chevron-left" class="w-4 h-4"/>
                                </span>
                                <span wire:loading wire:target="goToCompensatoryStep2">
                                    <x-ui.spinner size="xs"/>
                                </span>
                            </x-ui.button>
                        @else
                            <x-ui.button type="button" wire:click="goToCompensatoryStep1" wire:loading.attr="disabled" wire:target="goToCompensatoryStep1"
                                         variant="secondary-outline" class="min-w-[110px]">
                                <span wire:loading.remove wire:target="goToCompensatoryStep1" class="inline-flex items-center gap-1.5">
                                    مرحله قبل <x-ui.icon name="chevron-right" class="w-4 h-4"/>
                                </span>
                                <span wire:loading wire:target="goToCompensatoryStep1">
                                    <x-ui.spinner size="xs"/>
                                </span>
                            </x-ui.button>
                            <x-ui.button type="button" wire:click="submitCompensatory" wire:loading.attr="disabled" wire:target="submitCompensatory"
                                         variant="warning" class="min-w-[150px]">
                                <span wire:loading.remove wire:target="submitCompensatory" class="inline-flex items-center gap-1.5">
                                    ثبت گزارش جبرانی <x-ui.icon name="check" class="w-4 h-4"/>
                                </span>
                                <span wire:loading wire:target="submitCompensatory">
                                    <x-ui.spinner size="xs"/>
                                </span>
                            </x-ui.button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    {{-- ═════════════════════ Reply Modal ═════════════════════ --}}
    <div x-show="replyModalOpen" x-cloak>
        <div
            x-show="replyModalOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[90] bg-black/60 backdrop-blur-sm"
            @click="replyModalOpen = false; $wire.closeReplyModal()"
            @keydown.escape.window="replyModalOpen = false; $wire.closeReplyModal()"
        ></div>

        <div
            x-show="replyModalOpen"
            class="fixed inset-0 z-[91] flex items-end justify-center overscroll-contain sm:items-center sm:p-4"
            @click.self="replyModalOpen = false; $wire.closeReplyModal()"
        >
            <div
                x-show="replyModalOpen"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                class="relative w-full sm:max-w-lg bg-background border border-border rounded-t-3xl sm:rounded-2xl shadow-2xl flex flex-col pb-[env(safe-area-inset-bottom,0px)] sm:pb-0"
            >
                <div class="mx-auto mt-3 mb-1 h-1.5 w-14 rounded-full bg-border sm:hidden shrink-0"></div>

                <button type="button" @click="replyModalOpen = false; $wire.closeReplyModal()" data-elevated="false"
                        class="btn-press absolute top-4 left-4 w-8 h-8 inline-flex items-center justify-center rounded-full text-muted hover:text-foreground hover:bg-secondary transition-colors z-10">
                    <x-ui.icon name="x" class="w-4 h-4"/>
                </button>

                <div class="shrink-0 px-6 py-4 border-b border-border">
                    <h3 class="text-lg font-bold text-foreground">نظر مشاور</h3>
                </div>

            <div class="px-6 py-5 space-y-4">
                @if($advisorCommentPreview)
                    <div class="bg-secondary border border-primary/20 rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-primary/20">
                                <svg class="w-4 h-4 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                </svg>
                            </div>
                            <span class="font-bold text-primary text-sm">نظر مشاور</span>
                        </div>
                        <p class="text-sm text-foreground leading-7 whitespace-pre-line">{{ $advisorCommentPreview }}</p>
                    </div>
                @endif

                @if($studentReplyPreview)
                    <div class="bg-success/10 border border-success/20 rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-success/20">
                                <svg class="w-4 h-4 text-success" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                </svg>
                            </div>
                            <span class="font-bold text-success text-sm">پاسخ شما</span>
                        </div>
                        <p class="text-sm text-foreground leading-7 whitespace-pre-line">{{ $studentReplyPreview }}</p>
                    </div>
                @elseif($advisorCommentPreview)
                    <div class="space-y-2">
                        <label class="font-semibold text-foreground text-sm">پاسخ شما (اختیاری):</label>
                        <textarea wire:model="studentReplyInput" rows="3"
                                  class="w-full rounded-xl border border-border bg-background text-foreground px-4 py-3 text-sm resize-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                  placeholder="پاسخ خود را بنویسید..."></textarea>
                        @error('studentReplyInput')<p class="text-error text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                @else
                    <div class="flex items-center justify-center py-6">
                        <x-ui.spinner class="text-primary" />
                    </div>
                @endif
            </div>

                <div class="shrink-0 flex items-center justify-end gap-3 px-6 py-4 border-t border-border">
                    <x-ui.button type="button" @click="replyModalOpen = false; $wire.closeReplyModal()"
                                 variant="secondary-outline" icon="x">
                        بستن
                    </x-ui.button>
                    @if(!$studentReplyPreview && $advisorCommentPreview)
                        <x-ui.button type="button" wire:click="saveStudentReply" wire:loading.attr="disabled" wire:target="saveStudentReply"
                                     variant="primary" class="min-w-[110px]">
                            <span wire:loading.remove wire:target="saveStudentReply" class="inline-flex items-center gap-1.5">
                                ثبت پاسخ <x-ui.icon name="check" class="w-4 h-4"/>
                            </span>
                            <span wire:loading wire:target="saveStudentReply">
                                <x-ui.spinner size="xs"/>
                            </span>
                        </x-ui.button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
