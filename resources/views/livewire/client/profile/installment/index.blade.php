<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">

            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>

            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-8">

                    {{-- section:title --}}
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">اقساط من</div>
                    </div>

                    @if (session('error'))
                        <div class="rounded-xl bg-error/10 text-error px-4 py-3 border border-error/30 text-sm">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('success'))
                        <div class="rounded-xl bg-success/10 text-success px-4 py-3 border border-success/30 text-sm">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (! $plan)
                        {{-- بدون طرح اقساطی --}}
                        <div class="glass border border-border rounded-2xl">
                            <x-ui.empty-state title="طرح اقساطی ندارید">
                                شما خریدِ اقساطی فعالی ندارید.
                            </x-ui.empty-state>
                        </div>
                    @else
                        @php
                            $count    = (int) $plan->installment_count;
                            $paidCnt  = $paidList->count();
                            $progress = $count > 0 ? round($paidCnt / $count * 100) : 0;

                            // نگاشتِ وضعیتِ طرح اقساطی روی وضعیت‌های استانداردِ x-ui.status-badge
                            // (دقیقاً همون نگاشتی که در financial.blade.php هم استفاده شده)
                            $planStatusKey = match ($plan->status) {
                                \App\Models\InstallmentPlan::STATUS_COMPLETED => 'completed',
                                \App\Models\InstallmentPlan::STATUS_ACTIVE    => 'active',
                                default                                       => 'pending',
                            };
                            $planStatusLabel = match ($plan->status) {
                                \App\Models\InstallmentPlan::STATUS_COMPLETED => 'تسویه‌شده',
                                \App\Models\InstallmentPlan::STATUS_ACTIVE    => null,
                                default                                       => 'در انتظار پیش‌پرداخت',
                            };
                        @endphp

                        {{-- ═══════ حالت پرداخت گروهی ═══════ --}}
                        @if($groupPaymentMode)
                            <div class="glass border-2 border-info/30 rounded-2xl p-5 sm:p-6 space-y-4 relative transition-all">
                                <div class="absolute -top-3 right-5 bg-info text-info-foreground text-xs font-bold px-3 py-1 rounded-full shadow-lg">حالت پرداخت گروهی</div>

                                <div class="flex items-center justify-between flex-wrap gap-3">
                                    <div>
                                        <h3 class="font-black text-foreground text-lg">انتخاب اقساط برای پرداخت</h3>
                                        <p class="text-xs text-muted mt-1">قسط‌های مورد نظر را به ترتیب انتخاب کنید (حداکثر ۵ قسط).</p>
                                    </div>
                                    <x-ui.button type="button" wire:click="toggleGroupPaymentMode" variant="error-soft" icon="x" size="sm">
                                        لغو
                                    </x-ui.button>
                                </div>

                                @php
                                    $selectedAmount = $dueList->whereIn('id', $selectedInstallments)->sum('amount');
                                @endphp
                                <div class="sticky top-20 z-10">
                                    <div class="glass border border-border rounded-xl p-4 space-y-3 shadow-lg bg-background/80 backdrop-blur-sm">
                                         <div class="flex items-center justify-between text-sm">
                                            <span class="text-muted">تعداد اقساط انتخابی:</span>
                                            <span class="font-bold text-foreground">{{ count($selectedInstallments) }} قسط</span>
                                        </div>
                                        <div class="flex items-center justify-between text-base">
                                            <span class="text-muted">مبلغ کل:</span>
                                            <span class="font-extrabold text-primary">{{ number_format($selectedAmount) }} تومان</span>
                                        </div>
                                        {{-- آیکون این دکمه دستی داخل اسلات گذاشته شده (نه پراپ icon) چون در
                                             حالت wire:loading باید کاملاً جای متن را با اسپینر عوض کند --}}
                                        <x-ui.button type="button" wire:click="$set('showConfirmationModal', true)"
                                                     wire:loading.attr="disabled" wire:target="$set('showConfirmationModal', true)"
                                                     :disabled="count($selectedInstallments) == 0" variant="primary" block>
                                            <span wire:loading.remove wire:target="$set('showConfirmationModal', true)" class="inline-flex items-center gap-1.5">
                                                ادامه فرآیند پرداخت <x-ui.icon name="chevron-left" class="w-4 h-4"/>
                                            </span>
                                            <span wire:loading wire:target="$set('showConfirmationModal', true)">
                                                <x-ui.spinner size="sm"/>
                                            </span>
                                        </x-ui.button>
                                    </div>
                                </div>
                            </div>
                        {{-- ═══════ حالت عادی ═══════ --}}
                        @else
                             <div class="glass border border-border rounded-2xl p-5 sm:p-6 space-y-4">
                                <div class="flex items-center justify-between flex-wrap gap-2">
                                    <h3 class="font-black text-foreground text-lg">طرح اقساطی — {{ $plan->gradePrice?->grade_label ?? ('پایه ' . $plan->grade) }}</h3>
                                    <x-ui.status-badge :status="$planStatusKey" :label="$planStatusLabel"/>
                                </div>

                                <div class="divide-y divide-border text-sm">
                                   <div class="flex items-center justify-between py-2.5"><span class="text-muted">مبلغ کل خدمات</span> <span class="font-bold text-foreground">{{ number_format($plan->total_amount) }} تومان</span></div>
                                   <div class="flex items-center justify-between py-2.5"><span class="text-muted">مبلغ پرداخت اولیه</span> <span class="font-bold text-foreground">{{ number_format($plan->initial_amount) }} تومان</span></div>
                                   <div class="flex items-center justify-between py-2.5"><span class="text-muted">تعداد اقساط</span> <span class="font-bold text-foreground">{{ $plan->installment_count }} قسط</span></div>
                                   <div class="flex items-center justify-between py-2.5"><span class="text-muted">مبلغ هر قسط</span> <span class="font-bold text-foreground">{{ number_format($plan->monthly_amount) }} تومان</span></div>
                                   @if ($current) <div class="flex items-center justify-between py-2.5"><span class="text-muted">سر رسید قسط بعدی</span> <span class="font-bold text-foreground">{{ jalali($current->due_date)->format('%Y/%m/%d') }}</span></div> @endif
                                </div>

                                <div>
                                    <x-ui.progress-bar :percent="$progress" :show-percent="false"/>
                                    <div class="text-xs text-muted mt-1 text-end">{{ $progress }}٪ تکمیل شده</div>
                                </div>

                                <div class=" border-border pt-4 grid sm:grid-cols-2 gap-3">
                                    @if ($current)
                                        <x-ui.button type="button" wire:click="payInstallment({{ $current->id }})"
                                                     wire:loading.attr="disabled" wire:target="payInstallment({{ $current->id }})"
                                                     variant="primary" block>
                                            <span wire:loading.remove wire:target="payInstallment({{ $current->id }})" class="inline-flex items-center gap-1.5">
                                                پرداخت قسط جاری <x-ui.icon name="wallet" class="w-4 h-4"/>
                                            </span>
                                            <span wire:loading wire:target="payInstallment({{ $current->id }})">
                                                <x-ui.spinner size="sm"/>
                                            </span>
                                        </x-ui.button>
                                        <x-ui.button type="button" wire:click="toggleGroupPaymentMode" variant="secondary-outline" icon="layers" block>
                                            پرداخت گروهی
                                        </x-ui.button>
                                    @elseif ($plan->status === \App\Models\InstallmentPlan::STATUS_COMPLETED)
                                        <div class="sm:col-span-2 text-center text-success text-sm font-semibold py-2">
                                            ✔ همهٔ اقساط تسویه شده است.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif

                        {{-- ═══════ تب‌ها و لیست اقساط ═══════ --}}
                        <x-ui.segmented-tabs
                            :items="['due' => 'قابل پرداخت', 'paid' => 'پرداخت‌شده']"
                            :active="$tab"
                            :badges="['due' => $dueList->count(), 'paid' => $paidList->count()]"
                            method="setTab"
                        />

                        @php $list = $tab === 'paid' ? $paidList : $dueList; @endphp
                        @if ($list->isEmpty())
                            <div class="text-center text-muted text-sm py-10 glass border border-border rounded-2xl">
                                @if ($tab === 'paid') هنوز قسطی پرداخت نشده است. @else قسطِ قابل پرداختی وجود ندارد. @endif
                            </div>
                        @else
                            <div class="space-y-3">
                                @foreach ($list as $inst)
                                    @php
                                        $isCurrent = $current && $inst->id === $current->id;
                                        $isOverdue = $inst->status === 'pending' && $inst->isOverdue();
                                        $isSelected = in_array($inst->id, $selectedInstallments);

                                        // نگاشتِ وضعیتِ قسط روی وضعیت‌های استانداردِ x-ui.status-badge
                                        [$instStatusKey, $instStatusLabel] = match (true) {
                                            $inst->status === 'paid' => ['paid', null],
                                            $isOverdue                => ['expired', 'سررسید گذشته'],
                                            $isCurrent                 => ['joinable', 'قسط جاری'],
                                            default                     => ['not_started', 'در نوبت'],
                                        };
                                    @endphp

                                    <div wire:key="installment-card-{{ $inst->id }}"
                                         x-data="{ expanded: false }"
                                         @class([
                                            "glass border rounded-2xl overflow-hidden flex flex-col transition-all",
                                            "border-info/40 bg-info/10 ring-2 ring-info/20" => $groupPaymentMode && $isSelected,
                                            "border-border" => !$groupPaymentMode || !$isSelected,
                                         ])
                                    >
                                        {{-- ═══════════════════════════════════
                                             موبایل: ساختار کپی‌شده از financial.blade.php
                                        ════════════════════════════════════ --}}
                                        <div class="md:hidden">
                                            <x-ui.thumbnail class="w-full h-36">
                                                <img src="{{ asset('client/icons/installment.webp') }}" class="w-[9rem] h-[9rem] object-contain drop-shadow-md" alt="Installment">
                                            </x-ui.thumbnail>

                                            <div class="p-4 space-y-3" dir="rtl">
                                                @if ($groupPaymentMode && $tab === 'due')
                                                    <label class="flex items-center gap-4 cursor-pointer">
                                                        <input type="checkbox"
                                                               value="{{ $inst->id }}"
                                                               wire:model.live="selectedInstallments"
                                                               class="w-5 h-5 rounded border-border bg-secondary text-primary focus:ring-primary focus:ring-offset-secondary">
                                                        <span class="font-bold text-foreground">انتخاب قسط {{ $inst->sequence }}</span>
                                                    </label>
                                                @endif
                                                <h3 class="font-bold text-foreground text-base truncate">
                                                    قسط {{ $inst->sequence }} — سررسید: {{ jalali($inst->due_date)->format('%d %B %Y') }}
                                                </h3>
                                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                                    <x-ui.status-badge :status="$instStatusKey" :label="$instStatusLabel"/>
                                                    <span class="inline-flex items-center gap-1 px-2 py-1 bg-primary/10 text-primary text-xs font-bold rounded-full">
                                                        {{ number_format($inst->amount) }} تومان
                                                    </span>
                                                </div>
                                            </div>

                                            <div class="px-4 pb-4 space-y-2" dir="rtl">
                                                {{-- دستی (نه x-ui.button) چون آیکونش باید با چرخش ۱۸۰ درجه
                                                     بین باز/بسته انیمیشن بگیره --}}
                                                <button type="button" @click="expanded = !expanded" data-elevated="false"
                                                        class="btn-press w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-background border border-border hover:bg-secondary rounded-xl font-semibold text-sm text-foreground transition-colors">
                                                    <span>مشاهده جزئیات</span>
                                                    <x-ui.icon name="chevron-down" class="w-4 h-4 transition-transform duration-200"
                                                               x-bind:class="{ 'rotate-180': expanded }"/>
                                                </button>
                                                @if ($tab === 'due' && !$groupPaymentMode)
                                                     @if ($isCurrent)
                                                         <x-ui.button type="button" wire:click="payInstallment({{ $inst->id }})"
                                                                      wire:loading.attr="disabled" wire:target="payInstallment({{ $inst->id }})"
                                                                      :variant="$isOverdue ? 'error' : 'primary'" block>
                                                            <span wire:loading.remove wire:target="payInstallment({{ $inst->id }})" class="inline-flex items-center gap-1.5">
                                                                پرداخت <x-ui.icon name="wallet" class="w-4 h-4"/>
                                                            </span>
                                                            <span wire:loading wire:target="payInstallment({{ $inst->id }})">
                                                                <x-ui.spinner size="sm"/>
                                                            </span>
                                                         </x-ui.button>
                                                     @else
                                                         <x-ui.button type="button" variant="warning" icon="clock" disabled block>
                                                             پرداخت
                                                         </x-ui.button>
                                                     @endif
                                                @endif
                                            </div>
                                        </div>

                                        {{-- ═══════════════════════════════════
                                             دسکتاپ: ساختار کپی‌شده از financial.blade.php
                                        ════════════════════════════════════ --}}
                                        <div class="hidden md:flex flex-row min-h-[130px]">
                                             @if ($groupPaymentMode && $tab === 'due')
                                                <div class="flex-shrink-0 w-[120px] flex items-center justify-center">
                                                     <input type="checkbox"
                                                           value="{{ $inst->id }}"
                                                           wire:model.live="selectedInstallments"
                                                           class="w-6 h-6 rounded-md border-border bg-secondary text-primary focus:ring-primary focus:ring-offset-secondary cursor-pointer">
                                                </div>
                                             @else
                                                {{-- دارک‌مودِ دسکتاپ عمداً همون هگزِ سفارشیِ #1e3a5f/#1e40af نگه
                                                     داشته شده (نه x-ui.thumbnail)، طبق همون قرارِ قبلی درباره‌ی
                                                     این گرادیان‌های آبی --}}
                                                <div class="flex-shrink-0 w-[120px] flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 dark:from-[#1e3a5f] dark:to-[#1e40af]">
                                                    <img src="{{ asset('client/icons/installment.webp') }}" class="w-22 h-22 object-contain drop-shadow-md" alt="Installment">
                                                </div>
                                             @endif

                                            <div class="flex-1 p-4 flex items-center justify-between gap-4" dir="rtl">
                                                <div class="space-y-3 flex-1 min-w-0">
                                                    <h3 class="font-bold text-foreground text-base truncate">
                                                         قسط {{ $inst->sequence }} — سررسید: {{ jalali($inst->due_date)->format('%d %B %Y') }}
                                                    </h3>
                                                    <div class="flex flex-wrap items-center gap-2">
                                                        <x-ui.status-badge :status="$instStatusKey" :label="$instStatusLabel"/>
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-primary/10 text-primary text-xs font-bold rounded-full">
                                                            مبلغ: {{ number_format($inst->amount) }} تومان
                                                        </span>
                                                         @if ($inst->status === 'paid' && $inst->paid_at)
                                                            <div class="text-xs text-success">
                                                                (پرداخت در {{ jalali($inst->paid_at)->format('Y/m/d') }})
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="flex items-center gap-2 flex-shrink-0" dir="ltr">
                                                     @if ($tab === 'due' && !$groupPaymentMode)
                                                        @if ($isCurrent)
                                                             <x-ui.button type="button" wire:click="payInstallment({{ $inst->id }})"
                                                                          wire:loading.attr="disabled" wire:target="payInstallment({{ $inst->id }})"
                                                                          :variant="$isOverdue ? 'error' : 'primary'">
                                                                <span wire:loading.remove wire:target="payInstallment({{ $inst->id }})" class="inline-flex items-center gap-1.5">
                                                                    پرداخت <x-ui.icon name="wallet" class="w-4 h-4"/>
                                                                </span>
                                                                <span wire:loading wire:target="payInstallment({{ $inst->id }})">
                                                                    <x-ui.spinner size="sm"/>
                                                                </span>
                                                             </x-ui.button>
                                                        @else
                                                             <x-ui.button type="button" variant="warning" icon="clock" disabled>
                                                                 پرداخت
                                                             </x-ui.button>
                                                        @endif
                                                    @endif
                                                    {{-- دستی (نه x-ui.button) چون آیکونش باید با چرخش ۱۸۰ درجه
                                                         بین باز/بسته انیمیشن بگیره --}}
                                                    <button type="button" @click="expanded = !expanded" data-elevated="false"
                                                            class="btn-press inline-flex items-center justify-center gap-2 px-4 py-2 bg-background border border-border hover:bg-secondary rounded-xl font-semibold text-sm text-foreground transition-colors">
                                                        <x-ui.icon name="chevron-down" class="w-4 h-4 transition-transform duration-200"
                                                                   x-bind:class="{ 'rotate-180': expanded }"/>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- ═══ جزئیات (کپی‌شده از financial.blade.php) ═══ --}}
                                        <div x-show="expanded" x-cloak
                                             x-transition:enter="transition ease-out duration-200"
                                             x-transition:enter-start="opacity-0 -translate-y-1"
                                             x-transition:enter-end="opacity-100 translate-y-0"
                                             x-transition:leave="transition ease-in duration-150"
                                             x-transition:leave-start="opacity-100 translate-y-0"
                                             x-transition:leave-end="opacity-0 -translate-y-1"
                                             class=" border-border bg-background/50 p-4" style="display: none;">

                                            @php
                                                // آیکون/رنگِ «وضعیت» پویا بود ولی با یک باگِ واقعی: :class روی
                                                // یک svg خام با یک عبارتِ PHP ($inst->status...) نوشته شده بود
                                                // که هیچ‌وقت واقعاً توسط Alpine (که فقط جاوااسکریپت می‌فهمه، نه
                                                // PHP) ارزیابی نمی‌شد. حالا مستقیم با Blade محاسبه می‌شه.
                                                $statusIcon  = $inst->status === 'paid' ? 'check' : 'clock';
                                                $statusColor = $inst->status === 'paid' ? 'text-success' : 'text-warning';
                                            @endphp
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                                <div class="flex flex-col items-center p-3 bg-secondary rounded-xl text-center">
                                                    <x-ui.icon name="calendar" class="w-6 h-6 text-primary mb-2"/>
                                                    <span class="text-xs text-muted">تاریخ سررسید</span>
                                                    <span class="font-bold text-foreground text-sm mt-1">{{ jalali($inst->due_date)->format('%d %B %Y') }}</span>
                                                </div>
                                                <div class="flex flex-col items-center p-3 bg-secondary rounded-xl text-center">
                                                    <x-ui.icon name="{{ $statusIcon }}" class="w-6 h-6 mb-2 {{ $statusColor }}"/>
                                                    <span class="text-xs text-muted">وضعیت</span>
                                                    <span class="font-bold text-foreground text-sm mt-1">@if($inst->isPaid()) پرداخت شده @else در انتظار پرداخت @endif</span>
                                                </div>
                                                <div class="flex flex-col items-center p-3 bg-secondary rounded-xl text-center">
                                                    <x-ui.icon name="receipt" class="w-6 h-6 text-success mb-2"/>
                                                    <span class="text-xs text-muted">صورتحساب</span>
                                                    <span class="font-bold text-foreground text-sm mt-1" dir="ltr">{{ $inst->payment->refNumber ?? '—' }}</span>
                                                </div>
                                                <div class="flex flex-col items-center p-3 bg-secondary rounded-xl text-center">
                                                    <x-ui.icon name="info" class="w-6 h-6 mb-2 text-info"/>
                                                    <span class="text-xs text-muted">نوع پرداخت</span>
                                                    <span class="font-bold text-foreground text-sm mt-1">{{ $this->getInstallmentDescription($inst) }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- مودال تایید پرداخت گروهی --}}
    <div
        x-data="{ showConfirm: @entangle('showConfirmationModal') }"
        x-effect="showConfirm ? window.SdfrModalScrollLock.lock() : window.SdfrModalScrollLock.unlock()"
        x-cloak
    >
        <div
            x-show="showConfirm"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm"
            wire:click="$set('showConfirmationModal', false)"
        ></div>

        <div
            x-show="showConfirm"
            class="fixed inset-0 z-[101] flex items-end md:items-center justify-center p-0 md:p-4"
            aria-labelledby="modal-title" role="dialog" aria-modal="true"
        >
            <div
                x-show="showConfirm"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-full md:translate-y-0 md:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 md:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 md:scale-100"
                x-transition:leave-end="opacity-0 translate-y-full md:translate-y-0 md:scale-95"
                class="relative glass bg-background/95 rounded-t-3xl md:rounded-2xl w-full max-w-md p-6 shadow-2xl border border-border"
            >
                <div class="w-12 h-1 bg-foreground/20 rounded-full mx-auto mb-4 md:hidden" wire:click="$set('showConfirmationModal', false)"></div>

                <h3 class="text-lg font-bold text-foreground text-center" id="modal-title">تایید پرداخت گروهی</h3>
                <p class="text-sm text-muted text-center mt-2">شما در حال پرداخت همزمان چند قسط هستید.</p>

                <div class="mt-6 space-y-2 text-sm">
                    @php
                        $selectedToPay = $dueList->whereIn('id', $selectedInstallments);
                        $totalToPay = $selectedToPay->sum('amount');
                    @endphp
                    @foreach($selectedToPay as $item)
                        <div class="flex items-center justify-between p-2 rounded-lg bg-secondary">
                            <span>قسط {{ $item->sequence }} (سررسید: {{ jalali($item->due_date)->format('Y/m/d') }})</span>
                            <span class="font-semibold">{{ number_format($item->amount) }} تومان</span>
                        </div>
                    @endforeach
                    <div class="flex items-center justify-between p-3 rounded-lg bg-secondary border-2 border-primary text-base">
                        <span class="font-bold">جمع کل قابل پرداخت</span>
                        <span class="font-extrabold text-primary">{{ number_format($totalToPay) }} تومان</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <x-ui.button type="button" wire:click="$set('showConfirmationModal', false)" variant="secondary-outline" icon="x">
                        انصراف
                    </x-ui.button>
                    <x-ui.button type="button" wire:click="paySelectedInstallments"
                                 wire:loading.attr="disabled" wire:target="paySelectedInstallments" variant="primary">
                        <span wire:loading.remove wire:target="paySelectedInstallments" class="inline-flex items-center gap-1.5">
                            تایید و پرداخت <x-ui.icon name="wallet" class="w-4 h-4"/>
                        </span>
                        <span wire:loading wire:target="paySelectedInstallments">
                            <x-ui.spinner size="sm"/>
                        </span>
                    </x-ui.button>
                </div>
            </div>
        </div>
    </div>
</div>
