<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>

            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-6">

                    {{-- Section title --}}
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">تیکت های من</div>

                        <x-ui.button type="button" wire:click="openCreateModal" wire:loading.attr="disabled" wire:target="openCreateModal"
                                     variant="primary" pill class="ms-auto">
                            <span wire:loading.remove wire:target="openCreateModal" class="inline-flex items-center gap-1.5">
                                ایجاد تیکت <x-ui.icon name="plus" class="w-4 h-4"/>
                            </span>
                            <span wire:loading wire:target="openCreateModal">
                                <x-ui.spinner size="xs"/>
                            </span>
                        </x-ui.button>
                    </div>

                    {{-- Stats --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="bg-secondary border border-border rounded-xl p-3 text-center">
                            <div class="text-2xl font-black text-foreground">{{ $stats['total'] }}</div>
                            <div class="text-xs font-semibold text-muted mt-1">کل تیکت‌ها</div>
                        </div>
                        <div class="bg-warning/10 border border-warning/20 rounded-xl p-3 text-center">
                            <div class="text-2xl font-black text-warning">{{ $stats['waiting'] }}</div>
                            <div class="text-xs font-semibold text-muted mt-1">در انتظار پاسخ</div>
                        </div>
                        <div class="bg-success/10 border border-success/20 rounded-xl p-3 text-center">
                            <div class="text-2xl font-black text-success">{{ $stats['answered'] }}</div>
                            <div class="text-xs font-semibold text-muted mt-1">پاسخ داده شده</div>
                        </div>
                        <div class="bg-error/10 border border-error/20 rounded-xl p-3 text-center">
                            <div class="text-2xl font-black text-error">{{ $stats['closed'] }}</div>
                            <div class="text-xs font-semibold text-muted mt-1">بسته شده</div>
                        </div>
                    </div>

                    {{-- Filters --}}
                    <div class="space-y-3">
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="جستجو در تیکت‌ها..."
                                   class="form-input w-full h-10 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground pe-10 ps-4"/>
                            <x-ui.icon name="search" class="size-4 absolute top-3 end-3 text-muted"/>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <x-ui.select wire:model.live="statusFilter"
                                         :options="[
                                    ['id' => 'waiting',  'name' => 'در انتظار پاسخ'],
                                    ['id' => 'answered', 'name' => 'پاسخ داده شده'],
                                    ['id' => 'closed',   'name' => 'بسته شده'],
                                ]"
                                         value-key="id" label-key="name" placeholder="همه وضعیت‌ها"/>
                            <x-ui.select wire:model.live="priorityFilter"
                                         :options="[
                                    ['id' => 'low',    'name' => 'کم'],
                                    ['id' => 'medium', 'name' => 'متوسط'],
                                    ['id' => 'high',   'name' => 'زیاد'],
                                    ['id' => 'urgent', 'name' => 'فوری'],
                                ]"
                                         value-key="id" label-key="name" placeholder="همه اولویت‌ها"/>
                        </div>
                    </div>

                    {{-- ═══════════════ Tickets list — universal box pattern ═══════════════ --}}
                    <div class="space-y-4">
                        @forelse($tickets as $ticket)
                            @php
                                $unread = $ticket->unreadMessagesForUser()->count();
                                [$sStatusKey] = match ($ticket->status) {
                                    'waiting'  => ['pending'],
                                    'answered' => ['paid'],
                                    default    => ['voided'],
                                };

                                $pDot = match ($ticket->priority) {
                                    'low'    => 'bg-success',
                                    'medium' => 'bg-warning',
                                    'high'   => 'bg-error',
                                    default  => 'bg-error animate-pulse', // فوری
                                };
                            @endphp

                            <div class="glass border border-border rounded-2xl overflow-hidden flex flex-col">

                                {{-- ═══ Mobile ═══ --}}
                                <div class="md:hidden">
                                    <x-ui.thumbnail class="relative w-full h-36">
                                        <img src="/client/icons/ticket1.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">

                                        @if($unread > 0)
                                            <span class="absolute top-3 left-3 inline-flex items-center justify-center min-w-[22px] h-[22px] px-1.5 bg-error rounded-full text-error-foreground text-[11px] font-bold shadow-lg">
                                                {{ $unread }}
                                            </span>
                                        @endif
                                    </x-ui.thumbnail>

                                    <div class="p-4 space-y-3" dir="rtl">
                                        <h3 class="font-bold text-foreground text-base break-words">{{ $ticket->title }}</h3>

                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-muted">
                                            <span class="font-semibold">#{{ $ticket->ticket_number }}</span>
                                            <span>{{ $ticket->department->name ?? '-' }}</span>
                                            <span>{{ jalali($ticket->created_at)->format('%d %B %Y | H:i') }}</span>
                                        </div>

                                        <div class="flex flex-wrap items-center gap-2">
                                            <x-ui.status-badge :status="$sStatusKey" :label="$ticket->status_label"/>
                                            <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-full bg-background border border-border text-muted">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $pDot }}"></span>
                                                اولویت {{ $ticket->priority_label }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="px-4 pb-4">
                                        <x-ui.button href="{{ route('client.profile.ticket.show', $ticket->ticket_number) }}"
                                                     wire:navigate variant="primary" icon="chevron-left" block>
                                            مشاهده تیکت
                                        </x-ui.button>
                                    </div>
                                </div>

                                {{-- ═══ Desktop ═══ --}}
                                <div class="hidden md:flex flex-row min-h-[130px]">
                                    {{-- دارک‌مودِ دسکتاپ عمداً همون هگزِ سفارشیِ #1e3a5f/#1e40af نگه داشته
                                         شده (نه x-ui.thumbnail)، طبق همون قرارِ قبلی درباره‌ی این گرادیان‌ها --}}
                                    <div class="relative flex-shrink-0 w-[120px] flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 dark:from-[#1e3a5f] dark:to-[#1e40af]">
                                        <img src="/client/icons/ticket1.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">

                                        @if($unread > 0)
                                            <span class="absolute top-2 left-2 inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 bg-error rounded-full text-error-foreground text-[10px] font-bold shadow-lg">
                                                {{ $unread }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="flex-1 p-4 flex items-center justify-between gap-4" dir="rtl">
                                        <div class="space-y-2 flex-1 min-w-0">
                                            <h3 class="font-bold text-foreground text-base truncate">{{ $ticket->title }}</h3>

                                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-muted">
                                                <span class="font-semibold">#{{ $ticket->ticket_number }}</span>
                                                <span>{{ $ticket->department->name ?? '-' }}</span>
                                                <span>{{ jalali($ticket->created_at)->format('%d %B %Y | H:i') }}</span>
                                            </div>

                                            <div class="flex flex-wrap items-center gap-1.5">
                                                <x-ui.status-badge :status="$sStatusKey" :label="$ticket->status_label"/>
                                                <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-0.5 rounded-full bg-background border border-border text-muted">
                                                    <span class="w-1.5 h-1.5 rounded-full {{ $pDot }}"></span>
                                                    اولویت {{ $ticket->priority_label }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 flex-shrink-0" dir="ltr">
                                            <x-ui.button href="{{ route('client.profile.ticket.show', $ticket->ticket_number) }}"
                                                         wire:navigate variant="primary" icon="chevron-left">
                                                مشاهده
                                            </x-ui.button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <x-ui.empty-state title="تیکتی برای شما وجود ندارد.">
                                برای ارسال درخواست پشتیبانی، یک تیکت جدید ایجاد کنید.
                            </x-ui.empty-state>
                        @endforelse

                        @if($tickets->isNotEmpty())
                            <div class="mt-2">
                                {{ $tickets->links('components.ui.pagination') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ═══════════════ مودال ارسال تیکت ═══════════════ --}}
    <div
        x-data="{ open: @entangle('showCreateModal') }"
        x-effect="open ? window.SdfrModalScrollLock.lock() : window.SdfrModalScrollLock.unlock()"
        x-cloak
    >
        {{-- Backdrop --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-[120] bg-black/60 backdrop-blur-sm"
            @click="$wire.closeCreateModal()"
            aria-hidden="true"
        ></div>

        {{-- Panel --}}
        <div
            x-show="open"
            class="fixed inset-0 z-[121] flex items-end justify-center overscroll-contain sm:items-center sm:p-4"
            @click.self="$wire.closeCreateModal()"
        >
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                class="relative w-full sm:max-w-2xl max-h-[92vh] overflow-auto rounded-t-3xl sm:rounded-2xl bg-background border border-border shadow-2xl"
            >
                {{-- هندل کشیدن (فقط موبایل) --}}
                <div class="sm:hidden flex justify-center pt-2.5 pb-0.5">
                    <div class="w-12 h-1 rounded-full bg-border"></div>
                </div>

                <button type="button" @click="$wire.closeCreateModal()" data-elevated="false"
                        class="btn-press absolute top-4 left-4 w-8 h-8 inline-flex items-center justify-center rounded-full text-muted hover:text-foreground hover:bg-secondary transition-colors">
                    <x-ui.icon name="x" class="w-4 h-4"/>
                </button>

                <div class="p-5 md:p-6 space-y-5">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">ارسال تیکت جدید</div>
                    </div>

                    <form wire:submit.prevent="submit" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label for="ticket-title" class="font-medium text-xs text-muted">موضوع تیکت:</label>
                                <input type="text" id="ticket-title" wire:model="title" name="title"
                                       class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"
                                       placeholder="موضوع تیکت را وارد کنید"/>
                                @error('title') <span class="text-error text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="font-medium text-xs text-muted">دپارتمان:</label>

                                <x-ui.select
                                    wire:model="department_id"
                                    :options="$departments->map(fn($d) => ['id' => $d->id, 'name' => $d->name])->values()->toArray()"
                                    value-key="id"
                                    label-key="name"
                                    placeholder="دپارتمان را انتخاب کنید"
                                    :drop-up="true"
                                />

                                @error('department_id')
                                <span class="text-error text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="font-medium text-xs text-muted">اولویت:</label>
                            <div class="flex flex-wrap gap-3">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" wire:model="priority" value="low" class="form-radio text-success bg-secondary border-border focus:ring-0">
                                    <span class="text-xs font-semibold text-muted">کم</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" wire:model="priority" value="medium" class="form-radio text-warning bg-secondary border-border focus:ring-0">
                                    <span class="text-xs font-semibold text-muted">متوسط</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" wire:model="priority" value="high" class="form-radio text-error bg-secondary border-border focus:ring-0">
                                    <span class="text-xs font-semibold text-muted">زیاد</span>
                                </label>
                            </div>
                            @error('priority') <span class="text-error text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="block font-semibold text-xs text-foreground">توضیحات:</label>
                            <textarea rows="5" wire:model="message"
                                      class="form-textarea w-full !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5 py-3"
                                      placeholder="مشکل یا درخواست خود را با جزئیات شرح دهید..."></textarea>
                            @error('message') <span class="text-error text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <div class="space-y-1">

                                <label
                                    data-elevated="false"
                                    class="btn-press inline-flex items-center gap-x-1.5 border border-border rounded-full text-muted py-2 px-4 cursor-pointer hover:text-foreground hover:border-foreground/20 transition-colors"
                                    for="ticket-attachment" x-data="{ files: null }">
                                    <input type="file" class="sr-only" id="ticket-attachment" wire:model="attachment"
                                           name="attachment"
                                           x-on:change="files = Object.values($event.target.files)">
                                    <span class="font-semibold text-xs"
                                          x-text="files ? files.map(file => file.name).join(', ') : 'فایل پیوست (zip, rar)'"></span>
                                    <x-ui.icon name="paperclip" class="w-4 h-4"/>
                                </label>
                                @error('attachment') <span class="text-error text-xs block mt-1">{{ $message }}</span> @enderror
                            </div>
                            <x-ui.button type="submit" wire:loading.attr="disabled" wire:target="submit" variant="primary" pill class="px-8">
                                <span wire:loading.remove wire:target="submit" class="inline-flex items-center gap-1.5">
                                    ارسال تیکت <x-ui.icon name="send" class="w-4 h-4"/>
                                </span>
                                <span wire:loading wire:target="submit">
                                    <x-ui.spinner size="xs"/>
                                </span>
                            </x-ui.button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
