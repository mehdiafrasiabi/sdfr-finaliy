<div>
    @php
        [$statusKey] = match ($ticket->status) {
            'waiting'  => ['pending'],
            'answered' => ['paid'],
            default    => ['voided'],
        };
        $priorityColor = match ($ticket->priority) {
            'low'    => 'text-success',
            'medium' => 'text-warning',
            default  => 'text-error', // high / urgent
        };

        /**
         * diffForHumans() آرگومان altNumbers نداره — Carbon همچین گزینه‌ای رو
         * پشتیبانی نمی‌کنه و پاس‌دادنش (['options' => ['altNumbers' => true]])
         * باعث کرش CarbonInterval::getRoundingMethodFromOptions() می‌شد. خروجی
         * diffForHumans() همیشه با ارقام لاتینه، پس خودمون تبدیلش می‌کنیم به
         * فارسی تا همون ظاهر قبلی (مورد نظر) حفظ بشه.
         */
        $toPersianDigits = fn (string $value) => strtr($value, [
            '0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴',
            '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹',
        ]);
    @endphp

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
                        <x-ui.button href="{{ route('client.profile.ticket') }}" wire:navigate
                                     variant="secondary-outline" icon="chevron-right" pill class="ms-auto">
                            بازگشت
                        </x-ui.button>
                    </div>

                    {{-- ═══ Ticket info card ═══ --}}
                    <div class="border border-border rounded-xl overflow-hidden">
                        <div class="bg-secondary px-4 py-3 border-b border-border">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <h3 class="font-bold text-sm text-foreground break-words min-w-0">{{ $ticket->title }}</h3>
                                <x-ui.status-badge :status="$statusKey" :label="$ticket->status_label" class="shrink-0"/>
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
                                <span class="font-bold {{ $priorityColor }}">{{ $ticket->priority_label }}</span>
                            </div>
                            <div class="inline-flex items-center gap-1.5">
                                <span class="text-muted">تاریخ ثبت:</span>
                                <span class="font-bold text-foreground">{{ jalali($ticket->created_at)->format('%d %B %Y | H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    @if (session()->has('success'))
                        <div class="bg-success/10 border border-success/20 rounded-xl text-center text-success text-sm font-semibold py-3 px-4">
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
                                                <x-ui.icon name="user" class="size-4"/>
                                            </div>
                                            {{-- min-w-0 → flex item می‌تواند کوچک‌تر از content shrink شود --}}
                                            <div class="flex-1 min-w-0 max-w-lg">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="font-bold text-xs text-foreground truncate">{{ $msg->user->name ?? 'شما' }}</span>
                                                    <span class="w-1 h-1 bg-border rounded-full shrink-0"></span>
                                                    <span class="text-[10px] text-muted shrink-0">{{ $toPersianDigits(\Date::parse($msg->created_at)->diffForHumans()) }}</span>
                                                </div>
                                                {{-- break-words و whitespace-pre-wrap → بدون overflow و حفظ خطوط جدید --}}
                                                <div class="bg-secondary rounded-xl rounded-tr-sm font-semibold text-xs leading-6 text-muted px-4 py-2.5 break-words whitespace-pre-wrap">{{ $msg->message }}</div>
                                                @if($msg->attachment)
                                                    <a href="{{ asset('ticket/' . \Illuminate\Support\Facades\Auth::id() . '/file/' . $msg->attachment) }}"
                                                       class="inline-flex items-center gap-1 text-xs text-primary hover:underline mt-1.5">
                                                        <x-ui.icon name="paperclip" class="size-3.5"/>
                                                        دانلود فایل پیوست
                                                    </a>
                                                @endif
                                            </div>
                                        </div>

                                    @elseif($isAdmin)
                                        {{-- ─── Admin Message ─── --}}
                                        <div class="flex items-start gap-3 flex-row-reverse">
                                            <div class="shrink-0 w-8 h-8 bg-info/10 text-info rounded-full flex items-center justify-center">
                                                <x-ui.icon name="user" class="size-4"/>
                                            </div>
                                            <div class="flex-1 min-w-0 max-w-lg">
                                                <div class="flex items-center gap-2 mb-1 justify-end">
                                                    <span class="text-[10px] text-muted shrink-0">{{ $toPersianDigits(\Date::parse($msg->created_at)->diffForHumans()) }}</span>
                                                    <span class="w-1 h-1 bg-border rounded-full shrink-0"></span>
                                                    <span class="font-bold text-xs text-info truncate">{{ $msg->admin->name ?? 'پشتیبان' }}</span>
                                                </div>
                                                <div class="bg-info text-info-foreground rounded-xl rounded-tl-sm font-semibold text-xs leading-6 px-4 py-2.5 break-words whitespace-pre-wrap">{{ $msg->message }}</div>
                                                @if($msg->attachment)
                                                    <div class="text-left">
                                                        <a href="{{ asset('ticket/' . \Illuminate\Support\Facades\Auth::id() . '/file/' . $msg->attachment) }}"
                                                           class="inline-flex items-center gap-1 text-xs text-info hover:underline mt-1.5">
                                                            <x-ui.icon name="paperclip" class="size-3.5"/>
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
                                                <x-ui.icon name="user" class="size-4"/>
                                            </div>
                                            <div class="flex-1 min-w-0 max-w-lg">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="font-bold text-xs text-foreground truncate">{{ $msg->user->name ?? 'کاربر' }}</span>
                                                    <span class="w-1 h-1 bg-border rounded-full shrink-0"></span>
                                                    <span class="text-[10px] text-muted shrink-0">{{ $toPersianDigits(\Date::parse($msg->created_at)->diffForHumans()) }}</span>
                                                </div>
                                                <div class="bg-secondary rounded-xl rounded-tr-sm font-semibold text-xs leading-6 text-muted px-4 py-2.5 break-words whitespace-pre-wrap">{{ $msg->message }}</div>
                                                @if($msg->attachment)
                                                    <a href="{{ asset('ticket/' . ($msg->user_id ?? \Illuminate\Support\Facades\Auth::id()) . '/file/' . $msg->attachment) }}"
                                                       class="inline-flex items-center gap-1 text-xs text-primary hover:underline mt-1.5">
                                                        <x-ui.icon name="paperclip" class="size-3.5"/>
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
                                    @error('message') <span class="text-error text-xs">{{ $message }}</span> @enderror
                                </div>
                                <div class="flex flex-col gap-2">
                                    <div class="flex items-center justify-between gap-4">
                                        <div class="flex flex-col gap-1.5 grow min-w-0"
                                             x-data="{ files: null, uploading: false, progress: 0 }"
                                             x-on:livewire-upload-start="uploading = true; progress = 0"
                                             x-on:livewire-upload-finish="uploading = false; progress = 100"
                                             x-on:livewire-upload-cancel="uploading = false"
                                             x-on:livewire-upload-error="uploading = false"
                                             x-on:livewire-upload-progress="progress = $event.detail.progress">
                                            <label data-elevated="false"
                                                   class="btn-press inline-flex items-center gap-x-1.5 border border-border rounded-full text-muted py-2 px-4 cursor-pointer hover:text-foreground hover:border-foreground/20 transition-colors w-fit max-w-full"
                                                   for="customFile">
                                                <input type="file" class="sr-only" id="customFile"
                                                       wire:model="attachment" name="attachment"
                                                       x-on:change="files = Object.values($event.target.files)">
                                                <span class="font-semibold text-xs truncate"
                                                      x-text="files ? files.map(file => file.name).join(', ') : 'فایل پیوست'"></span>
                                                <x-ui.icon name="paperclip" class="size-4 shrink-0"/>
                                            </label>

                                            <div x-show="uploading" x-cloak style="display: none;" class="flex items-center gap-2 px-1">
                                                <div class="grow h-1.5 bg-secondary rounded-full overflow-hidden">
                                                    <div class="h-full bg-primary rounded-full transition-all duration-150" :style="`width: ${progress}%`"></div>
                                                </div>
                                                <span class="text-[10px] font-semibold text-muted shrink-0" x-text="progress + '%'"></span>
                                            </div>
                                        </div>
                                        <x-ui.button type="submit" wire:loading.attr="disabled" wire:target="submit" variant="primary" pill class="shrink-0">
                                            <span wire:loading.remove wire:target="submit" class="inline-flex items-center gap-1.5">
                                                ارسال پاسخ <x-ui.icon name="send" class="w-4 h-4"/>
                                            </span>
                                            <span wire:loading wire:target="submit">
                                                <x-ui.spinner size="xs"/>
                                            </span>
                                        </x-ui.button>
                                    </div>
                                    @error('attachment') <span class="text-error text-xs">{{ $message }}</span> @enderror
                                </div>
                            </form>
                        @else
                            <div class="flex flex-col items-center justify-center space-y-4 py-8 px-4 bg-secondary">
                                <div class="w-12 h-12 rounded-full flex items-center justify-center
                                    @if($ticket->status == 'waiting') bg-warning/10 text-warning @else bg-error/10 text-error @endif">
                                    @if($ticket->status == 'waiting')
                                        <x-ui.icon name="clock" class="size-6"/>
                                    @else
                                        <x-ui.icon name="lock" class="size-6"/>
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
