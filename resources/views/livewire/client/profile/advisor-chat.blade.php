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
                            <x-ui.icon name="lock" class="w-10 h-10"/>
                        </div>
                        <h2 class="font-black text-lg text-foreground">عدم دسترسی</h2>
                        <p class="text-sm text-muted leading-7 max-w-md">
                            بخش «ارتباط با مشاور» در دوره‌ی هفته‌ی آزمایشی در دسترس نیست.
                            پس از تکمیل مراحل و فعال‌سازی دسترسی کامل، می‌توانید مستقیماً با مشاور خود گفتگو کنید.
                        </p>
                        <x-ui.button href="{{ route('client.profile.dashboard') }}" wire:navigate variant="primary" pill>
                            بازگشت به پیشخوان
                        </x-ui.button>
                    </div>

                {{-- ═══════════════ حالت بدون مشاور ═══════════════ --}}
                @elseif($noAdvisor)
                    <div class="glass rounded-2xl p-10 flex flex-col items-center justify-center text-center space-y-4 min-h-[60vh]">
                        <div class="w-20 h-20 rounded-full bg-secondary border border-border flex items-center justify-center text-muted">
                            <x-ui.icon name="user" class="w-10 h-10"/>
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
                         x-effect="(showImageModal || confirmDelete) ? window.SdfrModalScrollLock.lock() : window.SdfrModalScrollLock.unlock()"
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
                                        <span class="text-success inline-flex items-center gap-1">
                                            <span class="w-1.5 h-1.5 rounded-full bg-success"></span> آنلاین
                                        </span>
                                    @else
                                        <span class="text-muted">آفلاین</span>
                                    @endif
                                </div>
                            </div>

                            {{-- دکمه‌ی بازگشت به پیشخوان (سمت چپ در RTL) --}}
                            <a wire:navigate href="{{ route('client.profile.dashboard') }}"
                               data-elevated="false"
                               class="btn-press ms-auto shrink-0 w-9 h-9 rounded-full bg-secondary border border-border text-muted hover:text-primary flex items-center justify-center transition-colors"
                               title="بازگشت به پیشخوان">
                                <x-ui.icon name="arrow-left" class="w-5 h-5"/>
                            </a>
                        </div>

                        {{-- ─── نوار انتخابِ چندتایی (جایگزینِ هدر) ─── --}}
                        <div x-show="selectedIds.length > 0" x-cloak dir="ltr"
                             class="flex items-center justify-between px-4 py-3 border-b border-border bg-secondary">
                            {{-- چپ: حذف --}}
                            <button type="button"
                                    x-on:click="confirmDelete = true"
                                    data-elevated="false"
                                    class="btn-press w-9 h-9 rounded-full text-error hover:bg-error/10 flex items-center justify-center transition-colors"
                                    title="حذف">
                                <x-ui.icon name="trash" class="w-5 h-5"/>
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
                                                                <x-ui.icon name="camera" class="size-3"/>
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
                                                        {{-- دو تیک (خونده‌شده) --}}
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="size-3.5 text-info">
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
                                            <x-ui.icon name="check" class="size-3"/>
                                        </div>

                                    </div>
                                </div>
                            @empty
                                <div class="h-full flex items-center justify-center">
                                    <x-ui.empty-state class="!py-0 !space-y-4">
                                        هنوز پیامی رد و بدل نشده است. اولین پیام را بفرستید.
                                    </x-ui.empty-state>
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
                                            <x-ui.icon name="clock" class="size-3 animate-pulse"/>
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
                                {{-- آیکون «پاسخ» (فلش برگشتی) معادل دقیقی در دیکشنری Keyline نداره؛ طبق قاعده‌ی
                                     «هیچ‌وقت آیکون از حافظه ساخته نشه»، همون SVG اصلی نگه داشته شده --}}
                                <button type="button" x-on:click="doReply()"
                                        class="w-full flex items-center gap-2.5 px-3.5 py-2 text-foreground hover:bg-secondary transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="size-4 text-muted">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 6 6v3" />
                                    </svg>
                                    پاسخ
                                </button>
                                <button type="button" x-show="menu.mine && menu.hasBody" x-on:click="doEdit()"
                                        class="w-full flex items-center gap-2.5 px-3.5 py-2 text-foreground hover:bg-secondary transition-colors">
                                    <x-ui.icon name="pen-line" class="size-4 text-muted"/>
                                    ویرایش
                                </button>
                                {{-- آیکون «کپی» (کلیپ‌بورد) معادل دقیقی در دیکشنری Keyline نداره؛ همون SVG اصلی نگه داشته شده --}}
                                <button type="button" x-show="menu.hasBody" x-on:click="doCopy()"
                                        class="w-full flex items-center gap-2.5 px-3.5 py-2 text-foreground hover:bg-secondary transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="size-4 text-muted">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.666 3.888A2.25 2.25 0 0 0 13.5 2.25h-3c-1.03 0-1.9.693-2.166 1.638m7.332 0c.055.194.084.4.084.612v0a.75.75 0 0 1-.75.75H9a.75.75 0 0 1-.75-.75v0c0-.212.03-.418.084-.612m7.332 0c.646.049 1.288.11 1.927.184 1.1.128 1.907 1.077 1.907 2.185V19.5a2.25 2.25 0 0 1-2.25 2.25H6.75A2.25 2.25 0 0 1 4.5 19.5V6.257c0-1.108.806-2.057 1.907-2.185a48.208 48.208 0 0 1 1.927-.184" />
                                    </svg>
                                    کپی متن
                                </button>
                                <button type="button" x-on:click="doSelect()"
                                        class="w-full flex items-center gap-2.5 px-3.5 py-2 text-foreground hover:bg-secondary transition-colors">
                                    <x-ui.icon name="circle-check" class="size-4 text-muted"/>
                                    انتخاب
                                </button>
                            </div>
                        </template>

                        {{-- ─── مودالِ ارسال تصویر (پیش‌نمایش بزرگ + کپشن اختیاری) ─── --}}
                        <template x-teleport="body">
                            <div x-show="showImageModal" x-cloak>
                                <div
                                    x-show="showImageModal"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    class="fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm"
                                    x-on:click="$wire.cancelUpload('image'); $wire.set('image', null); showImageModal = false; uploading = false; uploadProgress = 0"
                                ></div>

                                <div
                                    x-show="showImageModal"
                                    class="fixed inset-0 z-[101] flex items-end justify-center overscroll-contain sm:items-center sm:p-4"
                                    x-on:click.self="$wire.cancelUpload('image'); $wire.set('image', null); showImageModal = false; uploading = false; uploadProgress = 0"
                                >
                                    <div
                                        x-show="showImageModal"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                        x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                                        class="relative w-full sm:max-w-sm bg-background border border-border rounded-t-3xl sm:rounded-2xl shadow-2xl max-h-[92vh] overflow-hidden flex flex-col"
                                        dir="rtl">

                                        <div class="mx-auto mt-3 mb-1 h-1.5 w-14 rounded-full bg-border sm:hidden shrink-0"></div>

                                        <button type="button"
                                                x-on:click="$wire.cancelUpload('image'); $wire.set('image', null); showImageModal = false; uploading = false; uploadProgress = 0"
                                                data-elevated="false"
                                                class="btn-press absolute top-4 left-4 w-8 h-8 inline-flex items-center justify-center rounded-full text-muted hover:text-foreground hover:bg-secondary transition-colors z-10">
                                            <x-ui.icon name="x" class="w-4 h-4"/>
                                        </button>

                                        {{-- سرتیتر --}}
                                        <div class="shrink-0 px-4 py-3 border-b border-border">
                                            <span class="text-sm font-bold text-foreground">ارسال تصویر</span>
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
                                                <x-ui.icon name="camera" class="size-4"/>
                                                تغییر تصویر
                                            </label>

                                            <button type="button"
                                                    x-on:click="captureGhost(); $wire.send()"
                                                    wire:loading.attr="disabled" wire:target="send,image"
                                                    :disabled="uploading"
                                                    data-elevated="true"
                                                    class="btn-press inline-flex items-center gap-1.5 h-10 px-5 rounded-full bg-primary text-primary-foreground font-semibold text-xs transition-colors hover:bg-foreground hover:text-background disabled:opacity-50">
                                                <span wire:loading.remove wire:target="send">
                                                    <x-ui.icon name="send" class="size-4"/>
                                                </span>
                                                <x-ui.spinner size="xs" wire:loading wire:target="send"/>
                                                ارسال
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>

                        {{-- ─── مودالِ تأیید حذف ─── --}}
                        <template x-teleport="body">
                            <div x-show="confirmDelete" x-cloak>
                                <div
                                    x-show="confirmDelete"
                                    x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="opacity-0"
                                    x-transition:enter-end="opacity-100"
                                    x-transition:leave="transition ease-in duration-200"
                                    x-transition:leave-start="opacity-100"
                                    x-transition:leave-end="opacity-0"
                                    class="fixed inset-0 z-[100] bg-black/60 backdrop-blur-sm"
                                    x-on:click="confirmDelete = false"
                                ></div>

                                <div
                                    x-show="confirmDelete"
                                    class="fixed inset-0 z-[101] flex items-end justify-center overscroll-contain sm:items-center sm:p-4"
                                    x-on:click.self="confirmDelete = false"
                                >
                                    <div
                                        x-show="confirmDelete"
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                                        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                        x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                                        class="relative w-full sm:max-w-sm bg-background border border-border rounded-t-3xl sm:rounded-2xl shadow-2xl"
                                        dir="rtl">

                                        <div class="mx-auto mt-3 mb-1 h-1.5 w-14 rounded-full bg-border sm:hidden shrink-0"></div>

                                        <button type="button" x-on:click="confirmDelete = false" data-elevated="false"
                                                class="btn-press absolute top-4 left-4 w-8 h-8 inline-flex items-center justify-center rounded-full text-muted hover:text-foreground hover:bg-secondary transition-colors z-10">
                                            <x-ui.icon name="x" class="w-4 h-4"/>
                                        </button>

                                        <div class="px-5 pt-5 pb-2 flex flex-col items-center text-center gap-3">
                                            <div class="w-12 h-12 rounded-full bg-error/15 text-error flex items-center justify-center">
                                                <x-ui.icon name="trash" class="size-6"/>
                                            </div>
                                            <div class="text-sm font-bold text-foreground">حذف پیام‌ها</div>
                                            <p class="text-xs text-muted leading-6">
                                                آیا از حذف <span class="font-bold text-foreground" x-text="selectedIds.length"></span> پیام انتخاب‌شده مطمئن هستید؟ این پیام‌ها فقط از نمای شما حذف می‌شوند.
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-2 border-t border-border px-5 py-4">
                                            <x-ui.button type="button" variant="secondary-outline" icon="x" block
                                                         @click="confirmDelete = false">
                                                انصراف
                                            </x-ui.button>
                                            <x-ui.button type="button" variant="error" icon="trash" block
                                                         @click="$wire.deleteSelected(selectedIds); selectedIds = []; confirmDelete = false">
                                                حذف
                                            </x-ui.button>
                                        </div>
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
                                    <x-ui.icon name="pen-line" class="size-4 text-primary shrink-0"/>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-[11px] font-bold text-primary">در حال ویرایش پیام</div>
                                        <div class="text-[11px] text-muted truncate">
                                            {{ \Illuminate\Support\Str::limit($editing?->body, 50) ?: 'تصویر' }}
                                        </div>
                                    </div>
                                    <button type="button" wire:click="cancelEdit" class="text-muted hover:text-error shrink-0">
                                        <x-ui.icon name="x" class="size-4"/>
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
                                    <button type="button" wire:click="cancelReply" class="text-muted hover:text-error shrink-0">
                                        <x-ui.icon name="x" class="size-4"/>
                                    </button>
                                </div>
                            @endif

                            @error('image') <div class="mb-2 text-[11px] text-error">{{ $message }}</div> @enderror
                            @error('body') <div class="mb-2 text-[11px] text-error">{{ $message }}</div> @enderror
                            @error('editingBody') <div class="mb-2 text-[11px] text-error">{{ $message }}</div> @enderror

                            <form wire:submit.prevent="{{ $editingId ? 'saveEdit' : 'send' }}" class="flex items-end gap-2">
                                {{-- دکمه‌ی تصویر (هنگام ویرایش غیرفعال) --}}
                                @unless($editingId)
                                    <label for="chat-image" data-elevated="false"
                                           class="btn-press shrink-0 cursor-pointer w-10 h-10 rounded-full bg-secondary border border-border text-muted hover:text-primary flex items-center justify-center transition-colors">
                                        <input type="file" id="chat-image" class="sr-only" wire:model="image" accept="image/jpeg,image/png,image/webp">
                                        <x-ui.icon name="camera" class="size-5"/>
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
                                        data-elevated="true"
                                        class="btn-press shrink-0 w-10 h-10 rounded-full bg-primary text-primary-foreground flex items-center justify-center transition-colors hover:bg-foreground hover:text-background disabled:opacity-50">
                                    {{-- آیکون ارسال --}}
                                    <x-ui.icon wire:loading.remove wire:target="send,saveEdit" name="send" class="size-5"/>
                                    {{-- اسپینر هنگام ارسال --}}
                                    <x-ui.spinner size="sm" wire:loading wire:target="send,saveEdit"/>
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
