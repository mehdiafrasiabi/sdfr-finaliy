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
                <div class="space-y-5">
                    <!-- section:title -->
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">ارسال تیکت</div>

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
                    <!-- end section:title -->

                    <!-- Guide Section -->
                    <div
                        dir="rtl"
                        x-data="collapseGuide('ticketCreate-guide')"
                        x-init="init()"
                        class="rounded-2xl border border-border bg-primary overflow-hidden transition-all">

                        <!-- HEADER -->
                        <button
                            @click="toggle"
                            class="w-full flex items-center justify-between px-4 md:px-6 py-4 transition">

                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-white dark:text-white"
                                     fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 14h-2v-2h2v2zm0-4h-2V6h2v6z"/>
                                </svg>
                                <span class="font-black text-white dark:text-white md:text-lg">راهنمای ثبت تیکت</span>
                            </div>
                            <svg class="w-5 h-5 text-white transition-transform duration-300"
                                 :class="open && 'rotate-180'"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <!-- Guide Section -->
                        <div
                            x-show="open"
                            x-cloak
                            x-transition:enter="transition ease-out duration-600"
                            x-transition:enter-start="opacity-0 -translate-y-2"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100 translate-y-0"
                            x-transition:leave-end="opacity-0 -translate-y-1"
                            class="px-4 md:px-6 pb-6">

                            <div class="flex flex-col md:flex-row-reverse gap-6 items-center mt-2">
                                <div class="relative w-full md:w-[280px] shrink-0 order-2 md:order-1">
                                    <img src="/client/assets/images/blog/sdfr.jpg"
                                         class="w-full h-[200px] md:h-[180px] object-cover rounded-xl">
                                    <button
                                        type="button"
                                        id="57612318744"
                                        data-video-url="https://www.aparat.com/video/video/embed/videohash/utg98i1/vt/frame?titleShow=true&recom=self"
                                        data-video-title="راهنمای ایجاد تیکت"
                                        class="absolute inset-0 flex items-center justify-center">
                                        <span class="w-14 h-14 rounded-full bg-white/90 dark:bg-black/60 flex items-center justify-center shadow-lg transition">
                                            <svg class="w-7 h-7 text-blue-600 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </span>
                                    </button>

                                </div>

                                <div class="flex-1 text-right text-sm md:text-base text-white dark:text-white leading-7 order-1 md:order-2">
                                    دانش‌آموز عزیز سلام، قبل از شرکت در آزمون موارد زیر را با دقت مطالعه کنید:
                                    <br>• استفاده از آخرین نسخه مرورگر کروم الزامی است.
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Guide Section -->

                    <!-- section:tickets:wrapper -->
                    <div class="border border-border rounded-xl overflow-hidden">
                        <form wire:submit.prevent="submit" class="p-4 space-y-4 bg-secondary">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label for="subject" class="font-medium text-xs text-muted">موضوع تیکت:</label>
                                    <input type="text" id="subject" wire:model="title" name="title"
                                           class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"
                                           placeholder="موضوع تیکت را وارد کنید"/>
                                    @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                                </div>
                                <div class="space-y-1.5">
                                    <label for="department" class="font-medium text-xs text-muted">دپارتمان:</label>
                                    <select id="department" wire:model="department_id" name="department_id"
                                            class="form-select w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                                        <option value="">دپارتمان را انتخاب کنید</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
                                        for="customFile" x-data="{ files: null }">
                                        <input type="file" class="sr-only" id="customFile" wire:model="attachment"
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
                                    <span class="font-semibold text-sm" wire:loading.remove>ارسال تیکت</span>
                                    <svg wire:loading class="animate-spin size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5" wire:loading.remove>
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
    <!-- Video Modal -->
</div>
