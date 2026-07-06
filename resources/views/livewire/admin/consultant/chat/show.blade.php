<div>
    <div class="app-page-head">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard.index') }}">صفحه اصلی</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.consultant.chats') }}">گفتگو با دانش‌آموزان</a></li>
                <li class="breadcrumb-item active">{{ $student->user->name ?? 'دانش‌آموز' }}</li>
            </ol>
        </nav>
    </div>

    <div wire:poll.3s="tick" class="statbox widget box box-shadow">
        {{-- ─── Header ─── --}}
        <div class="widget-header border-bottom">
            <div class="d-flex align-items-center gap-3 py-1">
                <div class="rounded-circle bg-primary-subtle text-primary d-flex align-items-center justify-content-center fw-bold"
                     style="width:44px;height:44px;">
                    {{ mb_substr($student->user->name ?? 'د', 0, 1) }}
                </div>
                <div>
                    <div class="fw-bold">{{ $student->user->name ?? 'دانش‌آموز' }}</div>
                    <div class="small" style="height:18px;">
                        @if($conversation->isTyping('student'))
                            <span class="text-primary fw-semibold">در حال نوشتن…</span>
                        @elseif($conversation->isOnline('student'))
                            <span class="text-success"><span class="d-inline-block bg-success rounded-circle" style="width:7px;height:7px;"></span> آنلاین</span>
                        @elseif($conversation->lastSeenLabel('student'))
                            <span class="text-muted">آخرین بازدید {{ $conversation->lastSeenLabel('student') }}</span>
                        @else
                            <span class="text-muted">آفلاین</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="widget-content widget-content-area">
            {{-- ─── پیام‌ها ─── --}}
            <div id="chat-scroll"
                 x-data
                 x-init="$el.scrollTop = $el.scrollHeight"
                 x-on:chat-message-sent.window="$nextTick(() => $el.scrollTop = $el.scrollHeight)"
                 style="height:60vh;overflow-y:auto;background:var(--bs-tertiary-bg, #f5f5f9);border-radius:.5rem;padding:1rem;">

                @forelse ($messages as $msg)
                    @php $mine = $msg->sender_type === 'advisor'; @endphp
                    <div class="d-flex mb-3 {{ $mine ? 'justify-content-end' : 'justify-content-start' }}">
                        <div style="max-width:75%;">

                            @if($msg->is_deleted)
                                <div class="border border-dashed rounded-3 px-3 py-2 text-muted fst-italic small bg-white">
                                    🚫 این پیام حذف شد
                                </div>

                            @elseif($editingId === $msg->id)
                                <div class="bg-white border rounded-3 p-2" style="width:18rem;">
                                    <textarea wire:model="editingBody" rows="3" class="form-control form-control-sm mb-2"></textarea>
                                    @error('editingBody') <div class="text-danger small mb-1">{{ $message }}</div> @enderror
                                    <div class="d-flex justify-content-end gap-2">
                                        <button wire:click="cancelEdit" type="button" class="btn btn-sm btn-light">انصراف</button>
                                        <button wire:click="saveEdit" type="button" class="btn btn-sm btn-primary">ذخیره</button>
                                    </div>
                                </div>

                            @else
                                <div class="rounded-3 px-3 py-2 {{ $mine ? 'bg-primary text-white' : 'bg-white border' }}"
                                     style="white-space:pre-wrap;word-break:break-word;">
                                    @if($msg->image_path)
                                        <a href="{{ asset($msg->image_path) }}" target="_blank" class="d-block mb-1">
                                            <img src="{{ asset($msg->image_path) }}" alt="تصویر" class="rounded" style="max-height:16rem;max-width:100%;">
                                        </a>
                                    @endif
                                    @if($msg->body)
                                        <div class="small">{{ $msg->body }}</div>
                                    @endif
                                    <div class="d-flex align-items-center gap-1 mt-1 {{ $mine ? 'justify-content-end text-white-50' : 'justify-content-start text-muted' }}">
                                        @if($msg->is_edited)<span style="font-size:9px;">ویرایش‌شده</span>@endif
                                        <span style="font-size:9px;">{{ jalali($msg->created_at)->format('%H:%M') }}</span>
                                        @if($mine)
                                            @if($msg->read_at)
                                                <svg viewBox="0 0 24 24" fill="none" stroke="#7ee0ff" stroke-width="2.5" width="14" height="14">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M1.5 12.5l4 4L13 8" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 16.5L16.5 8" />
                                                </svg>
                                            @else
                                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" width="14" height="14">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12.5l4 4L19 7" />
                                                </svg>
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                @if($mine)
                                    <div class="d-flex justify-content-end gap-2 mt-1">
                                        @if($msg->body)
                                            <button wire:click="startEdit({{ $msg->id }})" type="button" class="btn btn-link btn-sm text-muted p-0" style="font-size:11px;">ویرایش</button>
                                        @endif
                                        <button wire:click="deleteMessage({{ $msg->id }})" wire:confirm="این پیام حذف شود؟" type="button" class="btn btn-link btn-sm text-danger p-0" style="font-size:11px;">حذف</button>
                                    </div>
                                @endif
                            @endif

                        </div>
                    </div>
                @empty
                    <div class="h-100 d-flex align-items-center justify-content-center text-muted small">
                        هنوز پیامی رد و بدل نشده است. اولین پیام را بفرستید.
                    </div>
                @endforelse
            </div>

            {{-- ─── فرم ارسال ─── --}}
            <div class="mt-3">
                @if($image)
                    <div class="d-flex align-items-center gap-2 bg-light rounded p-2 mb-2">
                        <img src="{{ $image->temporaryUrl() }}" alt="پیش‌نمایش" class="rounded" style="width:46px;height:46px;object-fit:cover;">
                        <small class="text-muted flex-grow-1">تصویر انتخاب شد — می‌توانید زیرش کپشن بنویسید.</small>
                        <button type="button" wire:click="$set('image', null)" class="btn btn-sm btn-light">✕</button>
                    </div>
                @endif
                <div wire:loading wire:target="image" class="small text-muted mb-1">در حال بارگذاری تصویر…</div>
                @error('image') <div class="text-danger small mb-1">{{ $message }}</div> @enderror
                @error('body') <div class="text-danger small mb-1">{{ $message }}</div> @enderror

                <form wire:submit.prevent="send" class="d-flex align-items-end gap-2">
                    <label class="btn btn-light mb-0" title="افزودن تصویر">
                        <input type="file" class="d-none" wire:model="image" accept="image/jpeg,image/png,image/webp">
                        <i class="fi fi-rr-picture"></i>
                    </label>
                    <textarea wire:model="body" rows="1"
                              x-data
                              x-on:input.debounce.1500ms="$wire.setTyping()"
                              x-on:keydown.ctrl.enter.prevent="$wire.send()"
                              class="form-control" style="resize:none;max-height:8rem;"
                              placeholder="{{ $image ? 'کپشن (اختیاری)…' : 'پیام خود را بنویسید…' }}"></textarea>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="send">
                        <span wire:loading.remove wire:target="send">ارسال</span>
                        <span wire:loading wire:target="send" class="spinner-border spinner-border-sm"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
