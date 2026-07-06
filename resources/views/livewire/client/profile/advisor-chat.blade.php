<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar />
            </div>

            <div class="lg:col-span-9 md:col-span-8">

                {{-- ═══════════════ حالت قفل (هفته‌ی آزمایشی) ═══════════════ --}}
                @if($locked)
                    <div class="glass rounded-2xl p-10 flex flex-col items-center justify-center text-center space-y-4 min-h-[60vh]">
                        <div class="w-20 h-20 rounded-full bg-secondary border border-border flex items-center justify-center text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </div>
                        <h2 class="font-black text-lg text-foreground">عدم دسترسی</h2>
                        <p class="text-sm text-muted leading-7 max-w-md">
                            بخش «ارتباط با مشاور» در دوره‌ی هفته‌ی آزمایشی در دسترس نیست.
                            پس از تکمیل مراحل و فعال‌سازی دسترسی کامل، می‌توانید مستقیماً با مشاور خود گفتگو کنید.
                        </p>
                        <a wire:navigate href="{{ route('client.profile.dashboard') }}"
                           class="inline-flex items-center justify-center gap-x-1.5 h-10 bg-primary rounded-full text-primary-foreground transition-colors hover:bg-foreground hover:text-background px-6">
                            <span class="font-semibold text-xs">بازگشت به پیشخوان</span>
                        </a>
                    </div>

                {{-- ═══════════════ حالت بدون مشاور ═══════════════ --}}
                @elseif($noAdvisor)
                    <div class="glass rounded-2xl p-10 flex flex-col items-center justify-center text-center space-y-4 min-h-[60vh]">
                        <div class="w-20 h-20 rounded-full bg-secondary border border-border flex items-center justify-center text-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                            </svg>
                        </div>
                        <h2 class="font-black text-lg text-foreground">هنوز مشاوری تعیین نشده</h2>
                        <p class="text-sm text-muted leading-7 max-w-md">
                            به‌محض تخصیص مشاور تحصیلی، این بخش برای گفتگوی مستقیم فعال می‌شود.
                        </p>
                    </div>

                {{-- ═══════════════ چت ═══════════════ --}}
                @else
                    <div wire:poll.3s="tick"
                         x-data="advisorChat()"
                         x-on:selection-cleared.window="selectedIds = []"
                         x-on:livewire-upload-start.window="uploading = true; uploadProgress = 0; showImageModal = true"
                         x-on:livewire-upload-finish.window="uploading = false; uploadProgress = 100"
                         x-on:livewire-upload-error.window="uploading = false"
                         x-on:livewire-upload-cancel.window="uploading = false; uploadProgress = 0"
                         x-on:livewire-upload-progress.window="uploadProgress = $event.detail.progress"
                         x-on:chat-message-sent.window="showImageModal = false"
                         class="glass rounded-2xl overflow-hidden flex flex-col h-[88vh] relative select-none">

                        {{-- ─── Header: مشاور + وضعیت ─── --}}
                        <div x-show="selectedIds.length === 0"
                             class="flex items-center gap-3 px-4 py-3 border-b border-border bg-background/40">
                            <div class="shrink-0 w-11 h-11 rounded-full bg-primary/10 text-primary flex items-center justify-center font-bold">
                                {{ mb_substr($advisor->name ?? 'م', 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-sm text-foreground truncate">{{ $advisor->name ?? 'مشاور' }}</div>
                                <div class="text-[11px] h-4">
                                    @if($conversation->isTyping('advisor'))
                                        <span class="text-primary font-semibold">در حال نوشتن…</span>
                                    @elseif($conversation->isOnline('advisor'))
                                        <span class="text-green-500 inline-flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> آنلاین
                                        </span>
                                    @else
                                        <span class="text-muted">آفلاین</span>
                                    @endif
                                </div>
                            </div>

                            {{-- دکمه‌ی بازگشت به پیشخوان (سمت چپ در RTL) --}}
                            <a wire:navigate href="{{ route('client.profile.dashboard') }}"
                               class="ms-auto shrink-0 w-9 h-9 rounded-full bg-secondary border border-border text-muted hover:text-primary flex items-center justify-center transition-colors"
                               title="بازگشت به پیشخوان">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-5">
                                    {{-- فلش به سمت چپ (بازگشت در چیدمان راست‌به‌چپ) --}}
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                                </svg>
                            </a>
                        </div>

                        {{-- ─── نوار انتخابِ چندتایی (جایگزینِ هدر) ─── --}}
                        <div x-show="selectedIds.length > 0" x-cloak dir="ltr"
                             class="flex items-center justify-between px-4 py-3 border-b border-border bg-secondary">
                            {{-- چپ: حذف --}}
                            <button type="button"
                                    x-on:click="confirmDelete = true"
                                    class="w-9 h-9 rounded-full text-red-500 hover:bg-red-500/10 flex items-center justify-center transition-colors"
                                    title="حذف">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </button>

                            <span class="text-xs font-bold text-foreground" dir="rtl">
                                <span x-text="selectedIds.length"></span> پیام انتخاب شد
                            </span>

                            {{-- راست: انصراف --}}
                            <button type="button" x-on:click="selectedIds = []"
                                    class="text-xs font-semibold text-muted hover:text-foreground px-3 py-1.5 rounded-full hover:bg-background/60 transition-colors">
                                انصراف
                            </button>
                        </div>

                        {{-- ─── پیام‌ها ─── --}}
                        <div id="chat-scroll"
                             x-init="$el.scrollTop = $el.scrollHeight"
                             x-on:chat-message-sent.window="$nextTick(() => $el.scrollTop = $el.scrollHeight)"
                             class="flex-1 overflow-y-auto px-4 py-5 space-y-3">

                            @php $lastDay = null; @endphp

                            @forelse($messages as $msg)
                                @php
                                    $mine = $msg->sender_type === 'student';
                                    $day  = jalali($msg->created_at)->format('%Y/%m/%d');
                                @endphp

                                {{-- جداکننده‌ی تاریخ هنگام تغییر روز --}}
                                @if($day !== $lastDay)
                                    @php $lastDay = $day; @endphp
                                    <div class="flex justify-center my-2">
                                        <span class="text-[10px] font-semibold text-muted bg-secondary/80 border border-border rounded-full px-3 py-1">
                                            {{ jalali($msg->created_at)->format('%d %B %Y') }}
                                        </span>
                                    </div>
                                @endif

                                <div class="flex {{ $mine ? 'justify-start' : 'justify-end' }}"
                                     wire:key="msg-{{ $msg->id }}">
                                    @php
                                        $imageOnly = $msg->image_path && ! $msg->body;
                                    @endphp
                                    <div class="group relative w-fit max-w-[78%] sm:max-w-xs transition-all"
                                         :class="selectedIds.includes({{ $msg->id }}) ? 'ring-2 ring-primary ring-offset-2 ring-offset-transparent rounded-2xl' : ''"
                                         x-on:contextmenu.prevent="openMenu($event, @js(['id' => $msg->id, 'mine' => $mine, 'hasBody' => (bool) $msg->body]), @js((string) $msg->body))"
                                         x-on:touchstart="pressStart($event, @js(['id' => $msg->id, 'mine' => $mine, 'hasBody' => (bool) $msg->body]), @js((string) $msg->body))"
                                         x-on:touchend="pressEnd()"
                                         x-on:touchmove="pressEnd()"
                                         x-on:click="if (selectionMode) { toggleSelect({{ $msg->id }}) }">

                                        {{-- ─── حباب پیام ─── --}}
                                        <div class="overflow-hidden rounded-2xl
                                            {{ $imageOnly ? 'p-1' : 'px-3 py-1.5' }}
                                            {{ $mine ? 'bg-primary text-primary-foreground rounded-tr-sm' : 'bg-secondary text-foreground rounded-tl-sm' }}">

                                            {{-- نقل‌قولِ ریپلای --}}
                                            @if($msg->reply_to_id)
                                                <div class="mb-1.5 rounded-lg px-2 py-1 border-s-2 text-[11px] leading-5
                                                    {{ $mine ? 'bg-black/15 border-white/60' : 'bg-foreground/5 border-primary' }}">
                                                    @if($msg->replyTo && ! $msg->replyTo->is_deleted)
                                                        <div class="font-bold opacity-90">
                                                            {{ $msg->replyTo->sender_type === 'student' ? 'شما' : ($advisor->name ?? 'مشاور') }}
                                                        </div>
                                                        <div class="opacity-80 truncate max-w-[12rem] inline-flex items-center gap-1">
                                                            @if($msg->replyTo->image_path)
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="size-3">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 19.5h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Z" />
                                                                </svg>
                                                            @endif
                                                            {{ \Illuminate\Support\Str::limit($msg->replyTo->body, 40) ?: 'تصویر' }}
                                                        </div>
                                                    @else
                                                        <div class="opacity-70 italic">پیام حذف‌شده</div>
                                                    @endif
                                                </div>
                                            @endif

                                            @if($msg->image_path)
                                                <a href="{{ asset($msg->image_path) }}" target="_blank"
                                                   x-on:click="selectionMode && $event.preventDefault()"
                                                   class="block {{ $msg->body ? 'mb-1' : '' }}">
                                                    <img src="{{ asset($msg->image_path) }}" alt="تصویر"
                                                         loading="lazy"
                                                         class="rounded-lg w-auto h-auto max-w-full max-h-60 object-cover">
                                                </a>
                                            @endif

                                            @if($msg->body)
                                                <div class="text-xs leading-snug whitespace-pre-wrap break-words">{{ $msg->body }}</div>
                                            @endif

                                            {{-- متادیتا: زمان + ویرایش + تیک‌ها --}}
                                            <div class="flex items-center gap-1.5 mt-0.5 {{ $imageOnly ? 'px-1.5 pb-0.5' : '' }} {{ $mine ? 'justify-end text-primary-foreground/70' : 'justify-start text-muted' }}">
                                                @if($msg->is_edited)
                                                    <span class="text-[9px]">ویرایش‌شده</span>
                                                @endif
                                                <span class="text-[9px]">{{ jalali($msg->created_at)->format('%H:%M') }}</span>
                                                @if($mine)
                                                    @if($msg->read_at)
                                                        {{-- دو تیک آبی --}}
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2.5" class="size-3.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M1.5 12.5l4 4L13 8" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 16.5L16.5 8" />
                                                        </svg>
                                                    @else
                                                        {{-- تک تیک --}}
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="size-3.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 12.5l4 4L19 7" />
                                                        </svg>
                                                    @endif
                                                @endif
                                            </div>
                                        </div>

                                        {{-- نشانگر انتخاب --}}
                                        <div x-show="selectedIds.includes({{ $msg->id }})" x-cloak
                                             class="absolute -top-1.5 {{ $mine ? '-start-1.5' : '-end-1.5' }} w-5 h-5 rounded-full bg-primary text-primary-foreground flex items-center justify-center shadow">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" class="size-3">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 12.5l4 4L19 7" />
                                            </svg>
                                        </div>

                                    </div>
                                </div>
                            @empty
                                <div class="h-full flex flex-col items-center justify-center text-center text-muted gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-10 opacity-40">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                                    </svg>
                                    <span class="text-xs">هنوز پیامی رد و بدل نشده است. اولین پیام را بفرستید.</span>
                                </div>
                            @endforelse

                            {{-- ─── حباب موقتِ «در حال ارسال» (clock) ─── --}}
                            <div wire:loading.flex wire:target="send" class="justify-start">
                                <div class="w-fit max-w-[78%] sm:max-w-xs">
                                    <div class="rounded-2xl rounded-tr-sm bg-primary/70 text-primary-foreground px-3 py-1.5 overflow-hidden">
                                        <template x-if="ghostImage">
                                            <img :src="ghostImage" alt="" class="rounded-lg w-auto h-auto max-w-full max-h-60 object-cover mb-1">
                                        </template>
                                        <div class="text-xs leading-snug whitespace-pre-wrap break-words" x-text="ghostText"></div>
                                        <div class="flex items-center justify-end gap-1 mt-1 text-primary-foreground/70">
                                            <span class="text-[9px]" x-text="nowTime()"></span>
                                            {{-- آیکون ساعت = در انتظار ارسال --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="size-3 animate-pulse">
                                                <circle cx="12" cy="12" r="9" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5V12l3 1.5" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- ─── منوی متنی (long-press / right-click) ─── --}}
                        <template x-teleport="body">
                            <div x-show="menu.open" x-cloak
                                 x-on:pointerdown.outside="closeMenu()"
                                 x-on:keydown.escape.window="closeMenu()"
                                 class="fixed z-[60] min-w-[160px] rounded-xl border border-border bg-background shadow-2xl py-1.5 text-sm"
                                 :style="`top:${menu.y}px; left:${menu.x}px;`"
                                 x-transition.opacity.duration.100ms>
                                <button type="button" x-on:click="doReply()"
                                        class="w-full flex items-center gap-2.5 px-3.5 py-2 text-foreground hover:bg-secondary transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="size-4 text-muted">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 6 6v3" />
                                    </svg>
                                    پاسخ
                                </button>
                                <button type="button" x-show="menu.mine && menu.hasBody" x-on:click="doEdit()"
                                        class="w-full flex items-center gap-2.5 px-3.5 py-2 text-foreground hover:bg-secondary transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="size-4 text-muted">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                    </svg>
                                    ویرایش
                                </button>
                                <button type="button" x-show="menu.hasBody" x-on:click="doCopy()"
                                        class="w-full flex items-center gap-2.5 px-3.5 py-2 text-foreground hover:bg-secondary transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="size-4 text-muted">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                                    </svg>
                                    کپی متن
                                </button>
                                <button type="button" x-on:click="doSelect()"
                                        class="w-full flex items-center gap-2.5 px-3.5 py-2 text-foreground hover:bg-secondary transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="size-4 text-muted">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                    انتخاب
                                </button>
                            </div>
                        </template>

                        {{-- ─── مودالِ ارسال تصویر (پیش‌نمایش بزرگ + کپشن اختیاری) ─── --}}
                        <template x-teleport="body">
                            <div x-show="showImageModal" x-cloak
                                 class="fixed inset-0 z-[70] flex items-end justify-center sm:items-center">
                                {{-- پس‌زمینه --}}
                                <div x-show="showImageModal"
                                     x-transition:enter="transition-opacity ease-out duration-200"
                                     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition-opacity ease-in duration-150"
                                     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                     class="absolute inset-0 bg-black/60 backdrop-blur-sm"
                                     x-on:click="$wire.cancelUpload('image'); $wire.set('image', null); showImageModal = false; uploading = false; uploadProgress = 0"></div>

                                <div x-show="showImageModal"
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-4 sm:scale-95"
                                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-4"
                                     class="relative w-full sm:max-w-sm sm:mx-4 max-h-[92vh] rounded-t-2xl sm:rounded-2xl bg-background border border-border shadow-2xl overflow-hidden flex flex-col" dir="rtl">
                                    {{-- سرتیتر --}}
                                    <div class="flex items-center justify-between px-4 py-3 border-b border-border">
                                        <span class="text-sm font-bold text-foreground">ارسال تصویر</span>
                                        <button type="button"
                                                x-on:click="$wire.cancelUpload('image'); $wire.set('image', null); showImageModal = false; uploading = false; uploadProgress = 0"
                                                class="text-muted hover:text-red-500 transition-colors" title="لغو">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                            </svg>
                                        </button>
                                    </div>

                                    {{-- پیش‌نمایش تصویر --}}
                                    <div class="relative bg-secondary/40 flex items-center justify-center min-h-[12rem] max-h-[60vh] overflow-hidden">
                                        @if($image)
                                            <img x-ref="previewImg" src="{{ $image->temporaryUrl() }}" alt="پیش‌نمایش"
                                                 class="w-auto h-auto max-w-full max-h-[60vh] object-contain">
                                        @endif
                                        {{-- پوشش پراگرس هنگام آپلود --}}
                                        <div x-show="uploading" x-cloak class="absolute inset-0 bg-black/55 flex flex-col items-center justify-center gap-2">
                                            <svg viewBox="0 0 40 40" class="w-12 h-12 -rotate-90">
                                                <circle cx="20" cy="20" r="16" fill="none" stroke="rgba(255,255,255,.25)" stroke-width="4" />
                                                <circle cx="20" cy="20" r="16" fill="none" stroke="#fff" stroke-width="4" stroke-linecap="round"
                                                        stroke-dasharray="100.53"
                                                        :stroke-dashoffset="100.53 - (100.53 * uploadProgress / 100)" />
                                            </svg>
                                            <span class="text-xs font-bold text-white" x-text="uploadProgress + '%'"></span>
                                        </div>
                                    </div>

                                    {{-- کپشن (اختیاری) --}}
                                    <div class="px-4 pt-3">
                                        <textarea wire:model="body" rows="2"
                                                  x-on:keydown.ctrl.enter.prevent="captureGhost(); $wire.send()"
                                                  placeholder="کپشن (اختیاری)…"
                                                  class="form-textarea w-full resize-none !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-2xl text-xs text-foreground px-4 py-3 max-h-32"></textarea>
                                    </div>

                                    {{-- اکشن‌ها --}}
                                    <div class="flex items-center justify-between gap-2 px-4 py-3">
                                        {{-- تغییر تصویر --}}
                                        <label for="chat-image-modal"
                                               class="cursor-pointer inline-flex items-center gap-1.5 text-xs font-semibold text-muted hover:text-primary transition-colors">
                                            <input type="file" id="chat-image-modal" class="sr-only" wire:model="image" accept="image/jpeg,image/png,image/webp">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Z" />
                                            </svg>
                                            تغییر تصویر
                                        </label>

                                        <button type="button"
                                                x-on:click="captureGhost(); $wire.send()"
                                                wire:loading.attr="disabled" wire:target="send,image"
                                                :disabled="uploading"
                                                class="inline-flex items-center gap-1.5 h-10 px-5 rounded-full bg-primary text-primary-foreground font-semibold text-xs transition-colors hover:bg-foreground hover:text-background disabled:opacity-50">
                                            <svg wire:loading.remove wire:target="send" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                            </svg>
                                            <svg wire:loading wire:target="send" class="animate-spin size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                            </svg>
                                            ارسال
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- ─── مودالِ تأیید حذف ─── --}}
                        <template x-teleport="body">
                            <div x-show="confirmDelete" x-cloak
                                 class="fixed inset-0 z-[80] flex items-end justify-center sm:items-center">
                                {{-- پس‌زمینه --}}
                                <div x-show="confirmDelete"
                                     x-transition:enter="transition-opacity ease-out duration-200"
                                     x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                                     x-transition:leave="transition-opacity ease-in duration-150"
                                     x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                     class="absolute inset-0 bg-black/60 backdrop-blur-sm"
                                     x-on:click="confirmDelete = false"></div>

                                <div x-show="confirmDelete"
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-4 sm:scale-95"
                                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100 translate-y-0"
                                     x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-4"
                                     class="relative w-full sm:max-w-sm sm:mx-4 rounded-t-2xl sm:rounded-2xl bg-background border border-border shadow-2xl overflow-hidden" dir="rtl">
                                    <div class="px-5 pt-5 pb-2 flex flex-col items-center text-center gap-3">
                                        <div class="w-12 h-12 rounded-full bg-red-500/10 text-red-500 flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="size-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                        </div>
                                        <div class="text-sm font-bold text-foreground">حذف پیام‌ها</div>
                                        <p class="text-xs text-muted leading-6">
                                            آیا از حذف <span class="font-bold text-foreground" x-text="selectedIds.length"></span> پیام انتخاب‌شده مطمئن هستید؟ این پیام‌ها فقط از نمای شما حذف می‌شوند.
                                        </p>
                                    </div>
                                    <div class="flex items-center gap-2 px-5 py-4">
                                        <button type="button" x-on:click="confirmDelete = false"
                                                class="flex-1 h-10 rounded-full bg-secondary border border-border text-foreground font-semibold text-xs hover:bg-background/60 transition-colors">
                                            انصراف
                                        </button>
                                        <button type="button"
                                                x-on:click="$wire.deleteSelected(selectedIds); selectedIds = []; confirmDelete = false"
                                                class="flex-1 h-10 rounded-full bg-red-500 text-white font-semibold text-xs hover:bg-red-600 transition-colors">
                                            حذف
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- ─── فرم ارسال ─── --}}
                        <div class="border-t border-border bg-background/40 px-3 py-3">

                            {{-- نوارِ «در حال ویرایش» --}}
                            @if($editingId)
                                @php $editing = $messages->firstWhere('id', $editingId); @endphp
                                <div class="mb-2 flex items-center gap-2 bg-secondary rounded-xl ps-3 pe-2 py-2 border-s-2 border-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="size-4 text-primary shrink-0">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Z" />
                                    </svg>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-[11px] font-bold text-primary">در حال ویرایش پیام</div>
                                        <div class="text-[11px] text-muted truncate">
                                            {{ \Illuminate\Support\Str::limit($editing?->body, 50) ?: 'تصویر' }}
                                        </div>
                                    </div>
                                    <button type="button" wire:click="cancelEdit" class="text-muted hover:text-red-500 shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                            {{-- نوارِ «پاسخ به …» --}}
                            @elseif($replyToId && ($replied = $messages->firstWhere('id', $replyToId)))
                                <div class="mb-2 flex items-center gap-2 bg-secondary rounded-xl ps-3 pe-2 py-2 border-s-2 border-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="size-4 text-primary shrink-0">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 6 6v3" />
                                    </svg>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-[11px] font-bold text-primary">
                                            پاسخ به {{ $replied->sender_type === 'student' ? 'خودتان' : ($advisor->name ?? 'مشاور') }}
                                        </div>
                                        <div class="text-[11px] text-muted truncate">
                                            {{ \Illuminate\Support\Str::limit($replied->body, 50) ?: 'تصویر' }}
                                        </div>
                                    </div>
                                    <button type="button" wire:click="cancelReply" class="text-muted hover:text-red-500 shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>
                            @endif

                            @error('image') <div class="mb-2 text-[11px] text-red-500">{{ $message }}</div> @enderror
                            @error('body') <div class="mb-2 text-[11px] text-red-500">{{ $message }}</div> @enderror
                            @error('editingBody') <div class="mb-2 text-[11px] text-red-500">{{ $message }}</div> @enderror

                            <form wire:submit.prevent="{{ $editingId ? 'saveEdit' : 'send' }}" class="flex items-end gap-2">
                                {{-- دکمه‌ی تصویر (هنگام ویرایش غیرفعال) --}}
                                @unless($editingId)
                                    <label for="chat-image" class="shrink-0 cursor-pointer w-10 h-10 rounded-full bg-secondary border border-border text-muted hover:text-primary flex items-center justify-center transition-colors">
                                        <input type="file" id="chat-image" class="sr-only" wire:model="image" accept="image/jpeg,image/png,image/webp">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                                        </svg>
                                    </label>
                                @endunless

                                @if($editingId)
                                    <textarea wire:key="composer-edit" wire:model="editingBody" rows="1"
                                              x-data x-init="$el.focus()"
                                              x-on:keydown.ctrl.enter.prevent="$wire.saveEdit()"
                                              placeholder="ویرایش پیام…"
                                              class="form-textarea flex-1 resize-none !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-2xl text-xs text-foreground px-4 py-3 max-h-32"></textarea>
                                @else
                                    <textarea wire:key="composer-new" wire:model="body" rows="1"
                                              x-data
                                              x-on:input.debounce.1500ms="$wire.setTyping()"
                                              x-on:keydown.ctrl.enter.prevent="captureGhost(); $wire.send()"
                                              placeholder="{{ $image ? 'کپشن (اختیاری)…' : 'پیام خود را بنویسید…' }}"
                                              class="form-textarea flex-1 resize-none !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-2xl text-xs text-foreground px-4 py-3 max-h-32"></textarea>
                                @endif

                                <button type="submit"
                                        x-on:click="captureGhost()"
                                        wire:loading.attr="disabled" wire:target="send,saveEdit,image"
                                        class="shrink-0 w-10 h-10 rounded-full bg-primary text-primary-foreground flex items-center justify-center transition-colors hover:bg-foreground hover:text-background disabled:opacity-50">
                                    {{-- آیکون ارسال --}}
                                    <svg wire:loading.remove wire:target="send,saveEdit" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                    </svg>
                                    {{-- اسپینر هنگام ارسال --}}
                                    <svg wire:loading wire:target="send,saveEdit" class="animate-spin size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    {{-- ─── منطق Alpine چت ─── --}}
@push('script')
        <script>
            function advisorChat() {
                return {
                    selectedIds: [],
                    uploading: false,
                    uploadProgress: 0,
                    showImageModal: false,
                    confirmDelete: false,
                    ghostText: '',
                    ghostImage: '',
                    pressTimer: null,
                    menu: { open: false, x: 0, y: 0, id: null, mine: false, hasBody: false, body: '' },

                    get selectionMode() {
                        return this.selectedIds.length > 0;
                    },

                    nowTime() {
                        return new Date().toLocaleTimeString('fa-IR', { hour: '2-digit', minute: '2-digit' });
                    },

                    toggleSelect(id) {
                        const i = this.selectedIds.indexOf(id);
                        if (i === -1) {
                            this.selectedIds.push(id);
                        } else {
                            this.selectedIds.splice(i, 1);
                        }
                    },

                    openMenu(e, opts, body) {
                        if (this.selectionMode) { return; }
                        const x = (e.touches && e.touches[0]) ? e.touches[0].clientX : e.clientX;
                        const y = (e.touches && e.touches[0]) ? e.touches[0].clientY : e.clientY;
                        this.menu = {
                            open: true,
                            x: Math.min(x, window.innerWidth - 180),
                            y: Math.min(y, window.innerHeight - 200),
                            id: opts.id,
                            mine: opts.mine,
                            hasBody: opts.hasBody,
                            body: body || '',
                        };
                    },

                    closeMenu() {
                        this.menu.open = false;
                    },

                    pressStart(e, opts, body) {
                        this.pressEnd();
                        this.pressTimer = setTimeout(() => this.openMenu(e, opts, body), 500);
                    },

                    pressEnd() {
                        if (this.pressTimer) {
                            clearTimeout(this.pressTimer);
                            this.pressTimer = null;
                        }
                    },

                    doReply() {
                        this.$wire.startReply(this.menu.id);
                        this.closeMenu();
                    },

                    doEdit() {
                        this.$wire.startEdit(this.menu.id);
                        this.closeMenu();
                    },

                    doCopy() {
                        const text = this.menu.body || '';
                        if (navigator.clipboard && navigator.clipboard.writeText) {
                            navigator.clipboard.writeText(text).catch(() => {});
                        }
                        this.closeMenu();
                    },

                    doSelect() {
                        this.toggleSelect(this.menu.id);
                        this.closeMenu();
                    },

                    captureGhost() {
                        if (this.$wire.editingId) { return; }
                        this.ghostText = this.$wire.body || '';
                        this.ghostImage = this.$refs.previewImg ? this.$refs.previewImg.src : '';
                    },
                };
            }
        </script>
@endpush
</div>
