<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar />
            </div>

            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-5">

                    {{-- ═══ Header ═══ --}}
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">مشاهده تیکت</div>
                        <a wire:navigate href="{{route('client.profile.ticket')}}"
                           class="inline-flex items-center justify-center gap-x-1.5 h-10 bg-background border border-border rounded-full text-muted transition-colors hover:text-foreground px-6 ms-auto">
                            <span class="font-semibold text-xs">بازگشت</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m15 15 6-6m0 0-6-6m6 6H9a6 6 0 0 0 0 12h3"/>
                            </svg>
                        </a>
                    </div>

                    {{-- ═══ Ticket info card ═══ --}}
                    <div class="border border-border rounded-xl overflow-hidden">
                        <div class="bg-secondary px-4 py-3 border-b border-border">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <h3 class="font-bold text-sm text-foreground break-words min-w-0">{{ $ticket->title }}</h3>
                                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1 rounded-full shrink-0
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
                            </div>
                        </div>
                        <div class="px-4 py-3 flex flex-wrap items-center gap-x-6 gap-y-2 text-xs">
                            <div class="inline-flex items-center gap-1.5">
                                <span class="text-muted">شماره تیکت:</span>
                                <span class="font-bold text-foreground">#{{ $ticket->ticket_number }}</span>
                            </div>
                            <div class="inline-flex items-center gap-1.5">
                                <span class="text-muted">دپارتمان:</span>
                                <span class="font-bold text-foreground">{{ $ticket->department->name }}</span>
                            </div>
                            <div class="inline-flex items-center gap-1.5">
                                <span class="text-muted">اولویت:</span>
                                <span class="font-bold
                                    @if($ticket->priority == 'low') text-blue-400
                                    @elseif($ticket->priority == 'medium') text-yellow-500
                                    @elseif($ticket->priority == 'high') text-orange-500
                                    @elseif($ticket->priority == 'urgent') text-red-500
                                    @endif">{{ $ticket->priority_label }}</span>
                            </div>
                            <div class="inline-flex items-center gap-1.5">
                                <span class="text-muted">تاریخ ثبت:</span>
                                <span class="font-bold text-foreground">{{ jalali($ticket->created_at)->format('%d %B %Y | H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    @if (session()->has('success'))
                        <div class="bg-green-500/10 border border-green-500/20 rounded-xl text-center text-green-500 text-sm font-semibold py-3 px-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    {{-- ═══ Messages — همیشه نمایش داده می‌شود (تاریخچه کامل) ═══ --}}
                    <div class="space-y-4">
                        <div class="flex items-center justify-center gap-x-3">
                            <span class="grow inline-block h-px bg-gradient-to-r from-border"></span>
                            <span class="font-semibold text-xs text-muted">گفتگوها</span>
                            <span class="grow inline-block h-px bg-gradient-to-l from-border"></span>
                        </div>

                        @if($ticket->messages->isEmpty())
                            <div class="text-center py-10 text-sm text-muted">
                                هنوز پیامی ثبت نشده است.
                            </div>
                        @else
                            <div class="space-y-4 max-h-[500px] overflow-y-auto px-1" id="messages-container">
                                @foreach($ticket->messages as $msg)
                                    @php
                                        // مقایسه با == برای جلوگیری از مشکل type (string vs int)
                                        $isMine = $msg->user_id && (int)$msg->user_id === (int)\Illuminate\Support\Facades\Auth::id();
                                        $isAdmin = !$isMine && $msg->admin_id;
                                    @endphp

                                    @if($isMine)
                                        {{-- ─── User (Mine) Message ─── --}}
                                        <div class="flex items-start gap-3">
                                            <div class="shrink-0 w-8 h-8 bg-primary/10 text-primary rounded-full flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                                </svg>
                                            </div>
                                            {{-- min-w-0 → flex item می‌تواند کوچک‌تر از content shrink شود --}}
                                            <div class="flex-1 min-w-0 max-w-lg">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="font-bold text-xs text-foreground truncate">{{ $msg->user->name ?? 'شما' }}</span>
                                                    <span class="w-1 h-1 bg-border rounded-full shrink-0"></span>
                                                    <span class="text-[10px] text-muted shrink-0">{{ \Date::parse($msg->created_at)->diffForHumans() }}</span>
                                                </div>
                                                {{-- break-words و whitespace-pre-wrap → بدون overflow و حفظ خطوط جدید --}}
                                                <div class="bg-secondary rounded-xl rounded-tr-sm font-semibold text-xs leading-6 text-muted px-4 py-2.5 break-words whitespace-pre-wrap">{{ $msg->message }}</div>
                                                @if($msg->attachment)
                                                    <a href="{{ asset('ticket/' . \Illuminate\Support\Facades\Auth::id() . '/file/' . $msg->attachment) }}"
                                                       class="inline-flex items-center gap-1 text-xs text-primary hover:underline mt-1.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                                                        </svg>
                                                        دانلود فایل پیوست
                                                    </a>
                                                @endif
                                            </div>
                                        </div>

                                    @elseif($isAdmin)
                                        {{-- ─── Admin Message ─── --}}
                                        <div class="flex items-start gap-3 flex-row-reverse">
                                            <div class="shrink-0 w-8 h-8 bg-blue-500/10 text-blue-500 rounded-full flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0 max-w-lg">
                                                <div class="flex items-center gap-2 mb-1 justify-end">
                                                    <span class="text-[10px] text-muted shrink-0">{{ \Date::parse($msg->created_at)->diffForHumans() }}</span>
                                                    <span class="w-1 h-1 bg-border rounded-full shrink-0"></span>
                                                    <span class="font-bold text-xs text-blue-500 truncate">{{ $msg->admin->name ?? 'پشتیبان' }}</span>
                                                </div>
                                                <div class="bg-blue-500 text-white rounded-xl rounded-tl-sm font-semibold text-xs leading-6 px-4 py-2.5 break-words whitespace-pre-wrap">{{ $msg->message }}</div>
                                                @if($msg->attachment)
                                                    <div class="text-left">
                                                        <a href="{{ asset('ticket/' . \Illuminate\Support\Facades\Auth::id() . '/file/' . $msg->attachment) }}"
                                                           class="inline-flex items-center gap-1 text-xs text-blue-400 hover:underline mt-1.5">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3.5">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                                                            </svg>
                                                            دانلود فایل پیوست
                                                        </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        {{-- ─── Fallback: پیامی که نه به کاربر فعلی متعلق است و نه ادمین (مثلاً تیکت قبلی با user_id متفاوت یا اولین پیام تیکت) ─── --}}
                                        <div class="flex items-start gap-3">
                                            <div class="shrink-0 w-8 h-8 bg-muted/20 text-muted rounded-full flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0 max-w-lg">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="font-bold text-xs text-foreground truncate">{{ $msg->user->name ?? 'کاربر' }}</span>
                                                    <span class="w-1 h-1 bg-border rounded-full shrink-0"></span>
                                                    <span class="text-[10px] text-muted shrink-0">{{ \Date::parse($msg->created_at)->diffForHumans() }}</span>
                                                </div>
                                                <div class="bg-secondary rounded-xl rounded-tr-sm font-semibold text-xs leading-6 text-muted px-4 py-2.5 break-words whitespace-pre-wrap">{{ $msg->message }}</div>
                                                @if($msg->attachment)
                                                    <a href="{{ asset('ticket/' . ($msg->user_id ?? \Illuminate\Support\Facades\Auth::id()) . '/file/' . $msg->attachment) }}"
                                                       class="inline-flex items-center gap-1 text-xs text-primary hover:underline mt-1.5">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-3.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13" />
                                                        </svg>
                                                        دانلود فایل پیوست
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- ═══ Reply form / Status notice ═══ --}}
                    <div class="border border-border rounded-xl overflow-hidden">
                        @if($ticket->status != 'closed' && $ticket->status != 'waiting')
                            <div class="bg-secondary px-4 py-3 border-b border-border">
                                <span class="font-bold text-xs text-foreground">پاسخ به تیکت</span>
                            </div>
                            <form wire:submit.prevent="submit" class="p-4 space-y-4">
                                <div class="space-y-1.5">
                                    <label class="block font-semibold text-xs text-foreground">متن پاسخ:</label>
                                    <textarea rows="4" wire:model="message"
                                              class="form-textarea w-full !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-4 py-3"
                                              placeholder="پاسخ خود را اینجا بنویسید..."></textarea>
                                    @error('message') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="flex items-center justify-between gap-4">
                                    <label class="inline-flex items-center gap-x-1.5 border border-border rounded-full text-muted py-2 px-4 cursor-pointer hover:text-foreground hover:border-foreground/20 transition-colors"
                                           for="customFile" x-data="{ files: null }">
                                        <input type="file" class="sr-only" id="customFile"
                                               wire:model="attachment" name="attachment"
                                               x-on:change="files = Object.values($event.target.files)">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                            <path fill-rule="evenodd"
                                                  d="M11.914 4.086a2 2 0 0 0-2.828 0l-5 5a2 2 0 1 0 2.828 2.828l.556-.555a.75.75 0 0 1 1.06 1.06l-.555.556a3.5 3.5 0 0 1-4.95-4.95l5-5a3.5 3.5 0 0 1 4.95 4.95l-1.972 1.972a2.125 2.125 0 0 1-3.006-3.005L9.97 4.97a.75.75 0 1 1 1.06 1.06L9.058 8.003a.625.625 0 0 0 .884.883l1.972-1.972a2 2 0 0 0 0-2.828Z"
                                                  clip-rule="evenodd" />
                                        </svg>
                                        <span class="font-semibold text-xs"
                                              x-text="files ? files.map(file => file.name).join(', ') : 'فایل پیوست'"></span>
                                    </label>
                                    @error('attachment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                    <button type="submit"
                                            class="inline-flex items-center justify-center gap-x-1.5 h-10 bg-primary rounded-full text-primary-foreground transition-colors hover:bg-foreground hover:text-background px-6">
                                        <span class="font-semibold text-xs" wire:loading.remove>ارسال پاسخ</span>
                                        <svg wire:loading class="animate-spin size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                        </svg>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4" wire:loading.remove>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                        </svg>
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="flex flex-col items-center justify-center space-y-4 py-8 px-4 bg-secondary">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center
                                    @if($ticket->status == 'waiting') bg-yellow-500/10 text-yellow-500 @else bg-red-500/10 text-red-500 @endif">
                                    @if($ticket->status == 'waiting')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                                        </svg>
                                    @endif
                                </div>
                                <p class="font-bold text-sm text-foreground text-center break-words">
                                    {{ $ticket->status == 'waiting' ? 'تیکت در انتظار پاسخ ادمین است و امکان ارسال پیام جدید وجود ندارد.' : 'این تیکت بسته شده و امکان پاسخ‌دهی وجود ندارد.' }}
                                </p>
                                @if($ticket->status == 'waiting')
                                    <p class="text-xs text-muted text-center">تاریخچه پیام‌های قبلی شما در بالا قابل مشاهده است.</p>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
