<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto" x-data="{
        showPreSessionModal: @entangle('showPreSessionModal'),
        showRescheduleModal: @entangle('showRescheduleModal'),
        selectedTitle: '',
        openModal(title) {
            this.selectedTitle = title;
            this.showPreSessionModal = true;
        }
    }">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">

            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>

            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-10">
                    @if($hideForExamProgramTrialStudent)
                        <x-ui.empty-state title="جلسه‌ای وجود ندارد!">
                            هنوز جلسه‌ای برای شما ثبت نشده است.
                        </x-ui.empty-state>
                    @else
                    <div class="space-y-5">

                        <!-- section:title -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-1">
                                    <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                    <div class="w-2 h-2 bg-foreground rounded-full"></div>
                                </div>
                                <div class="font-black text-foreground">اتاق مشاوره</div>
                            </div>
                        </div>
                        <!-- end section:title -->

                        <!-- لیست جلسات -->
                        @if($sessions->isEmpty())
                            <x-ui.empty-state title="جلسه‌ای وجود ندارد!">
                                هنوز جلسه ای برای شما ثبت نشده است.
                            </x-ui.empty-state>
                        @else
                            <div class="space-y-4">
                                @foreach($sessions as $session)
                                    @php
                                        $isLocked = in_array($session->id, $lockedSessionIds ?? []);
                                        $canReschedule = ! $isLocked
                                            && $session->result_status === null
                                            && $session->canFillPreSession();
                                    @endphp

                                    <div
                                        wire:key="session-card-{{ $session->id }}"
                                        x-data="{ expanded: false }"
                                        class="glass border border-border rounded-2xl overflow-hidden flex flex-col {{ $isLocked ? 'opacity-75' : '' }}"
                                    >

                                        {{-- ═══════════════════════════════════
                                             موبایل: تصویر بالا، اطلاعات وسط، دکمه‌ها پایین
                                        ════════════════════════════════════ --}}
                                        <div class="md:hidden">

                                            {{-- تصویر بالا --}}
                                            <x-ui.thumbnail :locked="$isLocked" class="w-full h-36">
                                                <img src="/client/icons/counsolotion.webp" class="w-24 h-24 object-contain drop-shadow-md" alt="">
                                            </x-ui.thumbnail>

                                            {{-- اطلاعات --}}
                                            <div class="p-4 space-y-3" dir="rtl">
                                                <h3 class="font-bold text-foreground text-base flex items-center gap-2 flex-wrap">
                                                    {{ $session->title }}
                                                    @if($isLocked)
                                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-secondary text-muted text-xs rounded-full">
                                                            <x-ui.icon name="lock" class="w-3 h-3"/>
                                                            قفل شده
                                                        </span>
                                                    @endif
                                                </h3>

                                                <p class="text-sm text-muted">
                                                    <span class="inline-flex items-center gap-1">
                                                        <x-ui.icon name="calendar" class="w-3.5 h-3.5"/>
                                                        {{ jalali($session->activation_date)->format('%d %B %Y') }}
                                                        @if($session->session_time)
                                                            &nbsp;ساعت {{ \Carbon\Carbon::parse($session->session_time)->format('H:i') }}
                                                        @endif
                                                    </span>
                                                </p>

                                                @if($isLocked)
                                                    <div class="flex items-center gap-2 text-xs text-muted bg-secondary rounded-lg px-3 py-2">
                                                        <x-ui.icon name="lock" class="w-4 h-4 flex-shrink-0"/>
                                                        <span>این جلسه قفل است. پس از مشخص شدن نتیجه جلسه قبلی، این جلسه برای شما باز می‌شود.</span>
                                                    </div>
                                                @endif
                                            </div>

                                            {{-- دکمه‌های موبایل --}}
                                            <div class="px-4 pb-4 space-y-2" dir="rtl">
                                                @if($isLocked)
                                                    <x-ui.button disabled variant="secondary" size="lg" block icon="lock">
                                                        جلسه قفل است
                                                    </x-ui.button>
                                                @else
                                                    @if($session->skyroom_link && $session->is_active)
                                                        <x-ui.button
                                                            href="{{ $session->skyroom_link }}"
                                                            target="_blank"
                                                            wire:ignore
                                                            variant="primary"
                                                            size="lg"
                                                            block
                                                            icon="monitor"
                                                        >
                                                            ورود به جلسه
                                                        </x-ui.button>
                                                    @endif
                                                    @if($session->canFillPreSession() && $session->preSession && $session->preSession->status !== 'completed')
                                                        <x-ui.button
                                                            @click="openModal('{{ addslashes($session->title) }}')"
                                                            wire:click="openPreSessionModal({{ $session->id }})"
                                                            variant="primary"
                                                            size="lg"
                                                            block
                                                            icon="square-pen"
                                                        >
                                                            پر کردن پیش‌جلسه
                                                        </x-ui.button>
                                                        @if($canReschedule)
                                                            <x-ui.button
                                                                wire:click="openReschedule({{ $session->id }})"
                                                                variant="warning-soft"
                                                                size="lg"
                                                                block
                                                                icon="git-compare-arrows"
                                                            >
                                                                درخواست جابجایی این جلسه
                                                            </x-ui.button>
                                                        @endif
                                                    @elseif($session->preSession)
                                                        <x-ui.button
                                                            href="{{ route('client.profile.consultation.pre-session', $session->id) }}"
                                                            wire:navigate
                                                            wire:ignore
                                                            variant="secondary"
                                                            size="lg"
                                                            block
                                                            icon="chevron-left"
                                                        >
                                                            مشاهده پیش‌جلسه
                                                        </x-ui.button>
                                                    @endif
                                                @endif

                                                <x-ui.button
                                                    type="button"
                                                    @click="$data.expanded = !($data.expanded ?? false)"
                                                    variant="secondary-outline"
                                                    size="md"
                                                    block
                                                >
                                                    <span>مشاهده جزئیات</span>
                                                    <x-ui.icon
                                                        name="chevron-down"
                                                        class="w-4 h-4 transition-transform duration-200"
                                                        x-bind:class="{ 'rotate-180': $data.expanded ?? false }"
                                                    />
                                                </x-ui.button>
                                            </div>
                                        </div>

                                        {{-- ═══════════════════════════════════
                                             دسکتاپ: تصویر سمت چپ، اطلاعات + دکمه‌ها وسط‌چین عمودی
                                        ════════════════════════════════════ --}}
                                        <div class="hidden md:flex flex-row min-h-[130px]">

                                            {{-- ستون تصویر --}}
                                            <x-ui.thumbnail :locked="$isLocked" gradient="to-br" size="sm" class="w-[120px]">
                                                <img src="/client/icons/counsolotion.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">
                                            </x-ui.thumbnail>

                                            {{-- محتوا: items-center برای وسط‌چین عمودی دکمه‌ها --}}
                                            <div class="flex-1 p-4 flex items-center justify-between gap-4" dir="rtl">

                                                {{-- راست: عنوان + تاریخ + بج‌ها --}}
                                                <div class="space-y-2 flex-1 min-w-0">
                                                    <h3 class="font-bold text-foreground text-base flex items-center gap-2 flex-wrap">
                                                        {{ $session->title }}
                                                        @if($isLocked)
                                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-secondary text-muted text-xs rounded-full">
                                                                <x-ui.icon name="lock" class="w-3 h-3"/>
                                                                قفل شده
                                                            </span>
                                                        @endif
                                                    </h3>

                                                    <p class="text-sm text-muted">
                                                        <span class="inline-flex items-center gap-1">
                                                            <x-ui.icon name="clock" class="w-3.5 h-3.5"/>
                                                            {{ jalali($session->activation_date)->format('%d %B') }}
                                                            @if($session->session_time)
                                                                &nbsp;ساعت {{ \Carbon\Carbon::parse($session->session_time)->format('H:i') }}
                                                            @endif
                                                        </span>
                                                    </p>

                                                    @if($isLocked)
                                                        <div class="flex items-center gap-2 text-xs text-muted bg-secondary rounded-lg px-3 py-2 mt-2">
                                                            <x-ui.icon name="lock" class="w-4 h-4 flex-shrink-0"/>
                                                            <span>این جلسه قفل است. پس از مشخص شدن نتیجه جلسه قبلی، این جلسه برای شما باز می‌شود.</span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <x-ui.button
                                                    type="button"
                                                    @click="$data.expanded = !($data.expanded ?? false)"
                                                    variant="secondary-outline"
                                                    size="md"
                                                >
                                                    <x-ui.icon
                                                        name="chevron-down"
                                                        class="w-4 h-4 transition-transform duration-200"
                                                        x-bind:class="{ 'rotate-180': $data.expanded ?? false }"
                                                    />
                                                </x-ui.button>

                                                {{-- چپ: دکمه‌ها (وسط‌چین عمودی بخاطر items-center والد). این ظرف عمداً
                                                     dir="ltr" است تا ترتیبِ افقیِ خودِ دکمه‌ها نسبت به هم عوض نشود؛
                                                     برای همین هر دکمه‌ی داخلش dir="rtl" جداگانه دارد تا متن+آیکونِ
                                                     خودش (طبق قاعده‌ی «آیکون بعد از متن») درست بچینند. --}}
                                                <div class="flex items-center gap-2 flex-shrink-0" dir="ltr">
                                                    @if($isLocked)
                                                        <x-ui.button disabled variant="secondary" size="md" icon="lock" dir="rtl">
                                                            قفل است
                                                        </x-ui.button>
                                                    @else
                                                        @if($session->canFillPreSession() && $session->preSession && $session->preSession->status !== 'completed')
                                                            <x-ui.button
                                                                @click="openModal('{{ addslashes($session->title) }}')"
                                                                wire:click="openPreSessionModal({{ $session->id }})"
                                                                variant="primary"
                                                                size="md"
                                                                icon="square-pen"
                                                                dir="rtl"
                                                            >
                                                                پر کردن پیش‌جلسه
                                                            </x-ui.button>
                                                            @if($canReschedule)
                                                                <x-ui.button
                                                                    wire:click="openReschedule({{ $session->id }})"
                                                                    variant="warning-soft"
                                                                    size="md"
                                                                    icon="git-compare-arrows"
                                                                    dir="rtl"
                                                                >
                                                                    درخواست جابجایی این جلسه
                                                                </x-ui.button>
                                                            @endif
                                                        @elseif($session->preSession)
                                                            <x-ui.button
                                                                href="{{ route('client.profile.consultation.pre-session', $session->id) }}"
                                                                wire:navigate
                                                                wire:ignore
                                                                variant="secondary"
                                                                size="md"
                                                                icon="chevron-left"
                                                                dir="rtl"
                                                            >
                                                                مشاهده پیش‌جلسه
                                                            </x-ui.button>
                                                        @endif

                                                        @if($session->skyroom_link && $session->is_active)
                                                            <x-ui.button
                                                                href="{{ $session->skyroom_link }}"
                                                                target="_blank"
                                                                wire:ignore
                                                                variant="primary"
                                                                size="md"
                                                                icon="monitor"
                                                                dir="rtl"
                                                            >
                                                                ورود به جلسه
                                                            </x-ui.button>
                                                        @endif
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        {{-- ═══ جزئیات (مشترک موبایل و دسکتاپ) ═══ --}}
                                        <div
                                            x-show="$data.expanded ?? false"
                                            x-cloak
                                            x-transition:enter="transition ease-out duration-200"
                                            x-transition:enter-start="opacity-0 -translate-y-1"
                                            x-transition:enter-end="opacity-100 translate-y-0"
                                            x-transition:leave="transition ease-in duration-150"
                                            x-transition:leave-start="opacity-100 translate-y-0"
                                            x-transition:leave-end="opacity-0 -translate-y-1"
                                            class="border-border bg-background/50 p-4"
                                            style="display: none;"
                                        >

                                            <div class="grid grid-cols-2 md:grid-cols-2 gap-4">
                                                <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                    <x-ui.icon name="calendar" class="w-6 h-6 text-primary mb-2"/>
                                                    <span class="text-xs text-muted">تاریخ جلسه</span>
                                                    <span class="font-bold text-foreground text-sm mt-1">{{ jalali($session->activation_date)->format('%d %B %Y') }}</span>
                                                </div>

                                                @if($session->session_time)
                                                    <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                        <x-ui.icon name="clock" class="w-6 h-6 mb-2 text-warning"/>
                                                        <span class="text-xs text-muted">ساعت برگزاری</span>
                                                        <span class="font-bold text-foreground text-sm mt-1">{{ \Carbon\Carbon::parse($session->session_time)->format('H:i') }}</span>
                                                    </div>
                                                @endif
                                            </div>

                                        </div>

                                    </div>
                                @endforeach
                            </div>

                            @if($sessions->hasPages())
                                <div class="mt-6">
                                    {{ $sessions->links('components.ui.pagination') }}
                                </div>
                            @endif
                        @endif

                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Modal پیش‌جلسه — موبایل: کشویی از پایین؛ دسکتاپ: از وسط با scale.
             هیچ‌کدوم اسکرولِ پشتِ صفحه رو باز نمی‌ذارن (SdfrModalScrollLock مشترک). --}}
        <div x-show="showPreSessionModal" x-cloak
             x-effect="showPreSessionModal ? window.SdfrModalScrollLock.lock() : window.SdfrModalScrollLock.unlock()"
             class="fixed inset-0 z-[100] flex flex-col justify-end sm:items-center sm:justify-center"
             @keydown.escape.window="showPreSessionModal = false; $wire.closePreSessionModal()"
             style="display: none;">

            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"
                 x-show="showPreSessionModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="showPreSessionModal = false; $wire.closePreSessionModal()"></div>

            <div class="relative z-10 w-full sm:max-w-md bg-secondary rounded-t-3xl sm:rounded-2xl border-t sm:border border-border shadow-2xl flex flex-col pb-[env(safe-area-inset-bottom,0px)] sm:pb-0"
                 x-show="showPreSessionModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95">

                <div class="sm:hidden flex justify-center pt-3 pb-1 shrink-0">
                    <div class="w-10 h-1 rounded-full bg-foreground/20"></div>
                </div>

                <div class="p-6">
                    <div class="flex flex-col items-center justify-center space-y-5">
                        <div class="flex items-center justify-center w-20 h-20 bg-primary/10 rounded-full">
                            <x-ui.icon name="square-pen" class="w-10 h-10 text-primary"/>
                        </div>
                        <h3 class="font-bold text-xl text-foreground">پر کردن پیش‌جلسه</h3>
                        <p class="text-center text-muted text-sm leading-relaxed">
                            آیا می‌خواهید پیش‌جلسه <strong x-text="selectedTitle"></strong> را پر کنید؟
                        </p>
                        <p class="flex items-center justify-center gap-2 text-center text-warning text-xs bg-warning/10 p-3 rounded-xl">
                            <x-ui.icon name="triangle-alert" class="w-4 h-4 flex-shrink-0"/>
                            <span>توجه: پس از رسیدن به تاریخ جلسه، امکان ویرایش پیش‌جلسه وجود نخواهد داشت.</span>
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-x-4 border-t border-border p-4 pb-safe">
                    <x-ui.button
                        type="button"
                        @click="showPreSessionModal = false"
                        wire:click="closePreSessionModal"
                        variant="secondary-outline"
                        block
                    >
                        لغو
                    </x-ui.button>
                    <x-ui.button
                        type="button"
                        wire:click="confirmStartPreSession"
                        wire:loading.attr="disabled"
                        wire:target="confirmStartPreSession"
                        variant="primary"
                        block
                    >
                        <span wire:loading.remove wire:target="confirmStartPreSession">بله، شروع می‌کنم</span>
                        <span wire:loading wire:target="confirmStartPreSession" class="inline-flex items-center gap-1.5">
                            <x-ui.spinner size="xs"/>
                            <span>در حال ثبت...</span>
                        </span>
                    </x-ui.button>
                </div>
            </div>
        </div>

        {{-- Modal جابجایی جلسه — همون رفتار/انیمیشنِ مودالِ بالا (کشویی موبایل /
             scale دسکتاپ + قفلِ اسکرول)، برای همین دیگه به‌جای شرطِ Blade‌ای
             (@if) که امکانِ انیمیشنِ خروج نمی‌داد، از x-show با یک متغیرِ
             entangle‌شده استفاده می‌کنیم؛ دقیقاً همون الگوی مودالِ پیش‌جلسه. --}}
        <div x-show="showRescheduleModal" x-cloak
             x-effect="showRescheduleModal ? window.SdfrModalScrollLock.lock() : window.SdfrModalScrollLock.unlock()"
             class="fixed inset-0 z-[100] flex items-end sm:items-center justify-center"
             @keydown.escape.window="showRescheduleModal = false; $wire.closeReschedule()"
             wire:key="reschedule-modal"
             style="display: none;">

            <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"
                 x-show="showRescheduleModal"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="showRescheduleModal = false; $wire.closeReschedule()"></div>

            <div class="relative z-10 w-full sm:max-w-md bg-secondary rounded-t-3xl sm:rounded-2xl border border-border shadow-2xl p-6"
                 dir="rtl"
                 x-show="showRescheduleModal"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95">
                <h3 class="font-bold text-lg text-foreground mb-2">جابجایی جلسه</h3>
                <p class="text-sm text-muted mb-4 leading-6">
                    روزِ جدیدِ جلسه را انتخاب کنید. جلسه‌ی فعلی «غیبت» ثبت می‌شود و یک «جلسه‌ی جبرانی» در روزِ انتخابی ساخته می‌شود. مشاور یک روز قبل ساعتِ آن را اعلام می‌کند.
                </p>
                <label class="block text-xs font-semibold mb-1.5 text-foreground">روز جدید</label>
                <div class="mb-4">
                    <x-ui.select
                        wire:model="rescheduleNewDay"
                        :options="collect($weekDays)->map(fn($name, $d) => ['id' => $d, 'name' => $name])->values()->all()"
                        placeholder="انتخاب روز…"
                        dropUp
                    />
                </div>
                <div class="flex gap-3">
                    <x-ui.button
                        type="button"
                        @click="showRescheduleModal = false"
                        wire:click="closeReschedule"
                        variant="secondary-outline"
                        block
                    >
                        لغو
                    </x-ui.button>
                    <x-ui.button type="button" wire:click="submitReschedule" variant="primary" block icon="check">
                        ثبت جابجایی
                    </x-ui.button>
                </div>
            </div>
        </div>

    </div>
</div>
