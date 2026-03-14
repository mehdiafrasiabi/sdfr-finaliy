<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <!-- user:info -->

                <!-- end user:info -->

                <!-- user:menus -->
                <livewire:client.profile.sidebar/>
                <!-- end user:menus -->
            </div>

            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-6">
                    <!-- section:title -->
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">تیکت های من</div>

                        <a wire:navigate href="{{route('client.profile.ticket.create')}}"
                           class="inline-flex items-center justify-center gap-x-1.5 h-10 bg-primary rounded-full text-primary-foreground transition-colors hover:bg-foreground hover:text-background px-6 ms-auto">
                            <span class="font-semibold text-xs">ایجاد تیکت</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                        </a>
                    </div>
                    <!-- end section:title -->

                    <!-- stats cards -->
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
                    <!-- end stats -->

                    <!-- filters -->
                    <div class="relative flex-1 min-w-[200px]">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="جستجو در تیکت‌ها..."
                               class="form-input w-full h-10 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground pe-10 ps-4"/>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="size-4 absolute top-3 end-3 text-muted">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <select wire:model.live="statusFilter"
                                class="form-select h-10 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-4 min-w-[140px]">
                            <option value="">همه وضعیت‌ها</option>
                            <option value="waiting">در انتظار پاسخ</option>
                            <option value="answered">پاسخ داده شده</option>
                            <option value="closed">بسته شده</option>
                        </select>
                        <select wire:model.live="priorityFilter"
                                class="form-select h-10 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-4 min-w-[120px]">
                            <option value="">همه اولویت‌ها</option>
                            <option value="low">کم</option>
                            <option value="medium">متوسط</option>
                            <option value="high">زیاد</option>
                            <option value="urgent">فوری</option>
                        </select>
                    </div>
                    <!-- end filters -->


                    <!-- section:tickets:wrapper -->
                    <div class="space-y-3">
                        @forelse($tickets as $ticket)
                            <a wire:navigate href="{{ route('client.profile.ticket.show', $ticket->id) }}"
                               class="block border border-border rounded-xl p-4 transition-colors bg-secondary group">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0 ">
                                        <div class="flex items-center gap-2 mb-2">
                                            <span
                                                class="font-bold text-sm text-foreground truncate">{{ $ticket->title }}</span>
                                            @if($ticket->unreadMessagesForUser()->count() > 0)
                                                <span
                                                    class="shrink-0 inline-flex items-center justify-center w-5 h-5 bg-primary rounded-full text-primary-foreground text-[10px] font-bold">
                                                    {{ $ticket->unreadMessagesForUser()->count() }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-xs text-muted">
                                            <span class="font-semibold">#{{ $ticket->ticket_number }}</span>
                                            <span>{{ $ticket->department->name ?? '-' }}</span>
                                            <span>{{ jalali($ticket->created_at)->format('%d %B %Y | H:i') }}</span>
                                            <span class="inline-flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 rounded-full
                                                    @if($ticket->priority == 'low') bg-blue-400
                                                    @elseif($ticket->priority == 'medium') bg-yellow-400
                                                    @elseif($ticket->priority == 'high') bg-orange-500
                                                    @elseif($ticket->priority == 'urgent') bg-red-500
                                                    @endif"></span>
                                                {{ $ticket->priority_label }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="shrink-0 flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full
                                            @if($ticket->status == 'waiting') bg-yellow-500/10 text-yellow-500
                                            @elseif($ticket->status == 'answered') bg-green-500/10 text-green-500
                                            @elseif($ticket->status == 'closed') bg-red-500/10 text-red-500
                                            @endif">
                                            <span class="w-1.5 h-1.5 rounded-full
                                                @if($ticket->status == 'waiting') bg-yellow-500
                                                @elseif($ticket->status == 'answered') bg-green-500
                                                @elseif($ticket->status == 'closed') bg-red-500
                                                @endif"></span>
                                            {{ $ticket->status_label }}
                                        </span>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor"
                                             class="size-4 text-muted group-hover:text-foreground transition-colors">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M15.75 19.5 8.25 12l7.5-7.5"/>
                                        </svg>
                                    </div>
                                </div>
                            </a>
                        @empty
                            <div class="flex flex-col items-center justify-center space-y-6 py-12">
                                <img src="/client/assets/images/theme/empty.svg" class="w-full max-w-xs opacity-35"
                                     alt="..."/>
                                <div class="text-center space-y-3">
                                    <h2 class="font-bold text-xl text-foreground">تیکتی برای شما وجود ندارد.</h2>
                                    <p class="text-sm text-muted">برای ارسال درخواست پشتیبانی، یک تیکت جدید ایجاد
                                        کنید.</p>
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
</div>
