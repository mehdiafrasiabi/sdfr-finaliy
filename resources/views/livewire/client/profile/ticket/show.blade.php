<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <!-- user:info -->

                <!-- end user:info -->

                <!-- user:menus -->
                <livewire:client.profile.sidebar />
                <!-- end user:menus -->
            </div>

            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-10">
                    <div class="space-y-5">
                        <!-- section:title -->
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-foreground">تیکت های من</div>
                        </div>
                        <!-- end section:title -->

                        <!-- section:tickets:wrapper -->
                        <div class="space-y-5">
                            <div class="border-b pb-5">
                                <div class="flex items-center justify-center gap-x-3 mb-3">
                                    <span class="grow inline-block h-px bg-gradient-to-r from-border"></span>
                                    <span class="font-semibold text-xs text-muted">
                                              {{ jalali($ticket->created_at)->format('%d %B %Y | H:i') }}
                                    </span>
                                    <span class="grow inline-block h-px bg-gradient-to-l from-border"></span>
                                </div>
                                @if (session()->has('success'))
                                    <div class="bg-green-500 rounded-full text-center text-white">
                                        {{ session('success') }}

                                    </div>
                                @endif
                                <div class="inline-flex items-center gap-2 font-bold text-xs mb-5">
                                    <span class="text-muted">شماره تیکت:</span>
                                    <span class="text-foreground">#{{$ticket->ticket_number}}</span>
                                </div>
                                <div class="flex flex-wrap items-center mb-3">
                                    <div class="inline-flex items-center gap-2 font-bold text-xs">
                                        <span class="text-muted">دپارتمان:</span>
                                        <span class="text-foreground">{{ $ticket->department->name }}</span>
                                    </div>
                                </div>
                                <div class="flex flex-wrap items-center justify-between mb-2">
                                    <div class="flex items-center gap-3 font-bold text-xs">
                                        <span class="text-muted">وضعیت:</span>
                                        <div
                                            class="flex items-center gap-2 ">
                                            <span class="font-semibold text-xs">
                                                @if($ticket->status=='waiting')
                                                    <span class="text-yellow-500"> درانتظار پاسخ ادمین</span>
                                                @elseif($ticket->status=='answered')
                                                    <span style="color: #0efd0e"> پاسخ داده شده</span>
                                                @elseif($ticket->status=='closed')
                                                    <span class=" text-red-500"> بسته شده</span>
                                                @else
                                                    --
                                                @endif</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-7 mb-5">
                                @foreach($ticket->messages as $msg)
                                    @if($msg->user_id === auth()->id())
                                        <div class="flex flex-wrap flex-col items-start">
                                            <div class="inline-flex items-start">
                                                <div class="flex flex-col items-start relative">
                                                    <div class="inline-flex items-center">
                                                        <span class="font-bold text-xs text-foreground">
                                                           {{ $msg->user->name  }}
                                                        </span>
                                                        <span class="block w-1 h-1 bg-border rounded-full mx-2"></span>
                                                        <span class="font-bold text-xxs text-muted">
                                                        {{ Date::parse($msg->created_at)->diffForHumans() }}
                                                    </span>
                                                    </div>
                                                    <div
                                                        class="relative w-full max-w-md bg-secondary rounded-lg font-semibold text-xs leading-6 text-muted px-3 py-1 my-2">
                                                        {{ $msg->message }}
                                                    </div>
                                                    @if($msg->attachment)
                                                        <a href="{{ asset('ticket/' . auth()->id() . '/file/' . $msg->attachment) }}" class="text-xs text-blue-500 underline mt-1 inline-block">دانلود فایل</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($msg->admin_id)
                                            <div class="flex flex-col space-y-5 pr-5">

                                                <div class="flex flex-wrap justify-start ms-auto">
                                                    <div class="inline-flex items-start">
                                                        <div class="flex flex-col items-start relative">
                                                            <div class="inline-flex items-center">
                                                                <span class="font-bold text-xs text-foreground">
                                                                  {{ @$msg->admin?->name ?? 'پشتیبان' }}
                                                                    </span>
                                                                <span
                                                                    class="block w-1 h-1 bg-border rounded-full mx-2"></span>
                                                                <span class="font-bold text-xxs text-muted">
                                                             {{ Date::parse($msg->created_at)->diffForHumans() }}
                                                        </span>
                                                            </div>
                                                            <div
                                                                class="relative w-full max-w-md rounded-lg font-semibold text-xs leading-6 px-3 py-1 my-2  bg-blue-500 text-white">
                                                                {{$msg->message}}
                                                            </div>
                                                            @if($msg->attachment)
                                                                <a href="{{ asset('ticket/' . auth()->id() . '/file/' . $msg->attachment) }}" class="text-xs text-blue-500 underline mt-1 inline-block">دانلود فایل</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                <div class="space-y-7">


                                    <div class="space-y-3">
                                        @if($ticket->status != 'closed' && $ticket->status != 'waiting')
                                            <form wire:submit.prevent="submit" class="space-y-3">
                                            <div class="space-y-2">
                                                <label for="subject"
                                                       class="block font-semibold text-xs text-foreground">پاسختو
                                                    اینجا بنویس:</label>
                                                <textarea type="text" rows="5" name="message" wire:model="message"
                                                          class="form-textarea w-full !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"></textarea>
                                            </div>
                                            <div class="space-y-2">
                                                <label for="subject"
                                                       class="block font-semibold text-xs text-foreground">فایل
                                                    پیوست:</label>
                                                <label
                                                    class="inline-flex items-center gap-x-1 border rounded-full text-muted py-2.5 px-5 cursor-pointer hover:text-foreground"
                                                    for="customFile" x-data="{ files: null }">
                                                    <input type="file" class="sr-only" id="customFile"
                                                           wire:model="attachment" name="attachment"
                                                           x-on:change="files = Object.values($event.target.files)">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                                                         fill="currentColor" class="size-4">
                                                        <path fill-rule="evenodd"
                                                              d="M11.914 4.086a2 2 0 0 0-2.828 0l-5 5a2 2 0 1 0 2.828 2.828l.556-.555a.75.75 0 0 1 1.06 1.06l-.555.556a3.5 3.5 0 0 1-4.95-4.95l5-5a3.5 3.5 0 0 1 4.95 4.95l-1.972 1.972a2.125 2.125 0 0 1-3.006-3.005L9.97 4.97a.75.75 0 1 1 1.06 1.06L9.058 8.003a.625.625 0 0 0 .884.883l1.972-1.972a2 2 0 0 0 0-2.828Z"
                                                              clip-rule="evenodd" />
                                                    </svg>
                                                    <span class="font-semibold text-xs"
                                                          x-text="files ? files.map(file => file.name).join(', ') : 'بارگذاری ..'"></span>
                                                </label>
                                            </div>
                                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                                <button type="submit"
                                                        class="inline-flex items-center justify-center gap-x-1.5 h-10 bg-primary rounded-full text-primary-foreground transition-colors hover:bg-foreground hover:text-background px-6 ms-auto">
                                                    <span class="font-semibold text-xs">ارسال پاسخ</span>
                                                </button>
                                            </div>
                                        </form>
                                        @else
                                            <div class="flex flex-col items-center justify-center space-y-12">
                                                <img src="/client/assets/images/theme/empty.svg" class="w-full max-w-xs opacity-35" alt="..." />
                                                <div class="text-center space-y-3">
                                                    <h2 class="font-bold text-xl text-foreground">
                                                        تیکت در وضعیت {{ ($ticket->status=='waiting' ? 'در انتظار پاسخ ادمین' : 'بسته شده' ) }} است و امکان پاسخ‌دهی وجود ندارد.                                            </h2>
                                                </div>
                                            </div>
                                            <br>
                                            <a wire:navigate href="{{route('client.profile.ticket')}}">بازگشت</a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end section:tickets:wrapper -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
