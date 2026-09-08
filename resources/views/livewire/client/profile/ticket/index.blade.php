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

                        <button type="button" wire:click="openCreateModal"
                           class="inline-flex items-center justify-center gap-x-1.5 h-10 bg-primary rounded-full text-primary-foreground transition-colors hover:bg-foreground hover:text-background px-6 ms-auto">
                            <span class="font-semibold text-xs">ایجاد تیکت</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Stats --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="bg-secondary border border-border rounded-xl p-3 text-center">
                            <div class="text-2xl font-black text-foreground">{{ $stats['total'] }}</div>
                            <div class="text-xs font-semibold text-muted mt-1">کل تیکت‌ها</div>
                        </div>
                        <div class="bg-yellow-500/10 border border-yellow-500/20 rounded-xl p-3 text-center">
                            <div class="text-2xl font-black text-yellow-500">{{ $stats['waiting'] }}</div>
                            <div class="text-xs font-semibold text-muted mt-1">در انتظار پاسخ</div>
                        </div>
                        <div class="bg-green-500/10 border border-green-500/20 rounded-xl p-3 text-center">
                            <div class="text-2xl font-black text-green-500">{{ $stats['answered'] }}</div>
                            <div class="text-xs font-semibold text-muted mt-1">پاسخ داده شده</div>
                        </div>
                        <div class="bg-red-500/10 border border-red-500/20 rounded-xl p-3 text-center">
                            <div class="text-2xl font-black text-red-500">{{ $stats['closed'] }}</div>
                            <div class="text-xs font-semibold text-muted mt-1">بسته شده</div>
                        </div>
                    </div>

                    {{-- Filters --}}
                    <div class="space-y-3">
                        <div class="relative">
                            <input type="text" wire:model.live.debounce.300ms="search" placeholder="جستجو در تیکت‌ها..."
                                   class="form-input w-full h-10 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground pe-10 ps-4"/>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="size-4 absolute top-3 end-3 text-muted">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                            </svg>
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
                                if ($ticket->status == 'waiting')   { $sBg = 'bg-yellow-500/10'; $sFg = 'text-yellow-500'; $sDot = 'bg-yellow-500'; }
                                elseif ($ticket->status == 'answered') { $sBg = 'bg-green-500/10';  $sFg = 'text-green-500';  $sDot = 'bg-green-500'; }
                                else                                { $sBg = 'bg-red-500/10';    $sFg = 'text-red-500';    $sDot = 'bg-red-500'; }

                                if ($ticket->priority == 'low')         $pDot = 'bg-blue-400';
                                elseif ($ticket->priority == 'medium')  $pDot = 'bg-yellow-400';
                                elseif ($ticket->priority == 'high')    $pDot = 'bg-orange-500';
                                else                                    $pDot = 'bg-red-500';
                            @endphp

                            <div class="glass border border-border rounded-2xl overflow-hidden flex flex-col">

                                {{-- ═══ Mobile ═══ --}}
                                <div class="md:hidden">
                                    <div class="relative w-full h-36 flex items-center justify-center bg-gradient-to-b from-blue-100 to-blue-200 dark:from-blue-950 dark:to-blue-900">
                                        <img src="/client/icons/ticket1.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">

                                        @if($unread > 0)
                                            <span class="absolute top-3 left-3 inline-flex items-center justify-center min-w-[22px] h-[22px] px-1.5 bg-red-500 rounded-full text-white text-[11px] font-bold shadow-lg">
                                                {{ $unread }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="p-4 space-y-3" dir="rtl">
                                        <h3 class="font-bold text-foreground text-base break-words">{{ $ticket->title }}</h3>

                                        <div class="flex flex-wrap items-center gap-x-3 gap-y-1 text-xs text-muted">
                                            <span class="font-semibold">#{{ $ticket->ticket_number }}</span>
                                            <span>{{ $ticket->department->name ?? '-' }}</span>
                                            <span>{{ jalali($ticket->created_at)->format('%d %B %Y | H:i') }}</span>
                                        </div>

                                        <div class="flex flex-wrap items-center gap-2">
                                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full {{ $sBg }} {{ $sFg }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $sDot }}"></span>
                                                {{ $ticket->status_label }}
                                            </span>
                                            <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-1 rounded-full bg-background border border-border text-muted">
                                                <span class="w-1.5 h-1.5 rounded-full {{ $pDot }}"></span>
                                                اولویت {{ $ticket->priority_label }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="px-4 pb-4">
                                        <a wire:navigate href="{{ route('client.profile.ticket.show', $ticket->ticket_number) }}"
                                           class="w-full inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-semibold text-sm transition-colors">
                                            مشاهده تیکت
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>

                                {{-- ═══ Desktop ═══ --}}
                                <div class="hidden md:flex flex-row min-h-[130px]">
                                    <div class="relative flex-shrink-0 w-[120px] flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-200 dark:from-[#1e3a5f] dark:to-[#1e40af]">
                                        <img src="/client/icons/ticket1.webp" class="w-20 h-20 object-contain drop-shadow-md" alt="">

                                        @if($unread > 0)
                                            <span class="absolute top-2 left-2 inline-flex items-center justify-center min-w-[20px] h-5 px-1.5 bg-red-500 rounded-full text-white text-[10px] font-bold shadow-lg">
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
                                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-0.5 rounded-full {{ $sBg }} {{ $sFg }}">
                                                    <span class="w-1.5 h-1.5 rounded-full {{ $sDot }}"></span>
                                                    {{ $ticket->status_label }}
                                                </span>
                                                <span class="inline-flex items-center gap-1.5 text-xs px-2.5 py-0.5 rounded-full bg-background border border-border text-muted">
                                                    <span class="w-1.5 h-1.5 rounded-full {{ $pDot }}"></span>
                                                    اولویت {{ $ticket->priority_label }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2 flex-shrink-0" dir="ltr">
                                            <a wire:navigate href="{{ route('client.profile.ticket.show', $ticket->ticket_number) }}"
                                               class="inline-flex items-center justify-center gap-2 px-5 py-2 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-semibold text-sm transition-colors">
                                                مشاهده
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex flex-col items-center justify-center space-y-6 py-12">
                                <img src="/client/svg/empty2.svg"
                                     class="w-full max-w-[370px] md:max-w-xs opacity-35 mb-4 md:mb-6"
                                     alt="پیامی وجود ندارد"/>
                                <div class="text-center space-y-3">
                                    <h2 class="font-bold text-xl text-foreground">تیکتی برای شما وجود ندارد.</h2>
                                    <p class="text-sm text-muted">برای ارسال درخواست پشتیبانی، یک تیکت جدید ایجاد کنید.</p>
                                </div>
                            </div>
                        @endforelse

                        @if($tickets->isNotEmpty())
                            <div class="p-3 text-xs text-muted">
                                {{ $tickets->links('layouts.client.pagination') }}
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
        x-effect="document.body.classList.toggle('overflow-hidden', open)"
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
            class="fixed inset-0 z-[121] flex items-end justify-center md:items-center md:p-4"
            @click.self="$wire.closeCreateModal()"
        >
            <div
                x-show="open"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-y-full md:translate-y-6 md:scale-95 opacity-0"
                x-transition:enter-end="translate-y-0 md:scale-100 opacity-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="translate-y-0 md:scale-100 opacity-100"
                x-transition:leave-end="translate-y-full md:translate-y-6 md:scale-95 opacity-0"
                class="relative w-full md:max-w-2xl max-h-[92vh] overflow-auto rounded-t-3xl md:rounded-2xl bg-background border border-border shadow-2xl"
            >
                {{-- هندل کشیدن (فقط موبایل) --}}
                <div class="md:hidden flex justify-center pt-2.5 pb-0.5">
                    <div class="w-12 h-1 rounded-full bg-muted/40"></div>
                </div>

                <button type="button" @click="$wire.closeCreateModal()"
                        class="absolute top-4 left-4 p-1.5 rounded-full text-muted hover:text-foreground hover:bg-secondary transition-colors">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 6L6 18M6 6l12 12"/>
                    </svg>
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
                                @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="font-medium text-xs text-muted">دپارتمان:</label>

                                <x-ui.select
                                    wire:model="department_id"
                                    :options="$departments->map(fn($d) => ['id' => $d->id, 'name' => $d->name])->values()->toArray()"
                                    value-key="id"
                                    label-key="name"
                                    placeholder="دپارتمان را انتخاب کنید"
                                    :dropUp="true"
                                />

                                @error('department_id')
                                <span class="text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="font-medium text-xs text-muted">اولویت:</label>
                            <div class="flex flex-wrap gap-3">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" wire:model="priority" value="low" class="form-radio text-blue-400 bg-secondary border-border focus:ring-0">
                                    <span class="text-xs font-semibold text-muted">کم</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" wire:model="priority" value="medium" class="form-radio text-yellow-400 bg-secondary border-border focus:ring-0">
                                    <span class="text-xs font-semibold text-muted">متوسط</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" wire:model="priority" value="high" class="form-radio text-orange-500 bg-secondary border-border focus:ring-0">
                                    <span class="text-xs font-semibold text-muted">زیاد</span>
                                </label>
                            </div>
                            @error('priority') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="space-y-1.5">
                            <label class="block font-semibold text-xs text-foreground">توضیحات:</label>
                            <textarea rows="5" wire:model="message"
                                      class="form-textarea w-full !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5 py-3"
                                      placeholder="مشکل یا درخواست خود را با جزئیات شرح دهید..."></textarea>
                            @error('message') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="flex items-center justify-between gap-4">
                            <div class="space-y-1">

                                <label
                                    class="inline-flex items-center gap-x-1.5 border border-border rounded-full text-muted py-2 px-4 cursor-pointer hover:text-foreground hover:border-foreground/20 transition-colors"
                                    for="ticket-attachment" x-data="{ files: null }">
                                    <input type="file" class="sr-only" id="ticket-attachment" wire:model="attachment"
                                           name="attachment"
                                           x-on:change="files = Object.values($event.target.files)">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                                         fill="currentColor" class="size-4">
                                        <path fill-rule="evenodd"
                                              d="M11.914 4.086a2 2 0 0 0-2.828 0l-5 5a2 2 0 1 0 2.828 2.828l.556-.555a.75.75 0 0 1 1.06 1.06l-.555.556a3.5 3.5 0 0 1-4.95-4.95l5-5a3.5 3.5 0 0 1 4.95 4.95l-1.972 1.972a2.125 2.125 0 0 1-3.006-3.005L9.97 4.97a.75.75 0 1 1 1.06 1.06L9.058 8.003a.625.625 0 0 0 .884.883l1.972-1.972a2 2 0 0 0 0-2.828Z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                    <span class="font-semibold text-xs"
                                          x-text="files ? files.map(file => file.name).join(', ') : 'فایل پیوست (zip, rar)'"></span>
                                </label>
                                @error('attachment') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit"
                                    class="h-11 inline-flex items-center justify-center gap-x-1.5 bg-primary rounded-full text-primary-foreground transition-colors hover:bg-foreground hover:text-background px-8">
                                <span class="font-semibold text-sm" wire:loading.remove wire:target="submit">ارسال تیکت</span>
                                <svg wire:loading wire:target="submit" class="animate-spin size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5" wire:loading.remove wire:target="submit">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                </svg>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
