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
                            <div class="font-black text-foreground">ارسال تیکت</div>

                            <a wire:navigate href="{{route('client.profile.ticket')}}"
                               class="inline-flex items-center justify-center gap-x-1.5 h-10 bg-background border rounded-full text-muted transition-colors hover:text-foreground px-6 ms-auto">
                                <span class="font-semibold text-xs">بازگشت</span>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m15 15 6-6m0 0-6-6m6 6H9a6 6 0 0 0 0 12h3" />
                                </svg>
                            </a>
                        </div>
                        <!-- end section:title -->

                        <!-- section:tickets:wrapper -->
                        <div class="space-y-5">
                            <form wire:submit.prevent="submit"  class="w-full md:max-w-lg space-y-3">
                                <div class="space-y-1">
                                    <label for="subject" class="font-medium text-xs text-muted">موضوع:</label>
                                    <input type="text" id="subject" wire:model="title" name="title"
                                           class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5" />
                                    @error('department_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                                </div>
                                <div class="space-y-1">
                                    <label for="department" class="font-medium text-xs text-muted">انتخاب
                                        دپارتمان:</label>
                                    <select id="department"  wire:model="department_id" name="department_id"
                                            class="form-select w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                                        <option value="">دپارتمان خودرا انتخاب کنید</option>
                                        @foreach($departments as $department)
                                            <option value="{{ $department->id }}">{{ $department->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('department_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                                </div>
                                <div class="space-y-1">
                                    <label for="department" class="font-medium text-xs text-muted">
                                        اولویت:</label>
                                    <select id="department"  wire:model="priority" name="priority"
                                            class="form-select w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                                        <option value="">اولویت خودرا انتخاب کنید</option>
                                        <option value="low">کم</option>
                                        <option value="medium">متوسط</option>
                                        <option value="high">زیاد</option>
                                    </select>
                                    @error('priority') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                                </div>
                                <div class="space-y-1">
                                    <label for="subject"
                                           class="block font-semibold text-xs text-foreground">توضیحات:</label>
                                    <textarea type="text" rows="5" name="message" wire:model="message"
                                              class="form-textarea w-full !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"></textarea>
                                    @error('message') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                                </div>
                                <div class="space-y-1">
                                    <label for="subject"
                                           class="block font-semibold text-xs text-foreground">فایل
                                        پیوست:</label>
                                    <label
                                        class="inline-flex items-center gap-x-1 border rounded-full text-muted py-2.5 px-5 cursor-pointer hover:text-foreground"
                                        for="customFile" x-data="{ files: null }">
                                        <input type="file" class="sr-only" id="customFile" wire:model="attachment" name="attachment"
                                               x-on:change="files = Object.values($event.target.files)">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                                             fill="currentColor" class="size-4">
                                            <path fill-rule="evenodd"
                                                  d="M11.914 4.086a2 2 0 0 0-2.828 0l-5 5a2 2 0 1 0 2.828 2.828l.556-.555a.75.75 0 0 1 1.06 1.06l-.555.556a3.5 3.5 0 0 1-4.95-4.95l5-5a3.5 3.5 0 0 1 4.95 4.95l-1.972 1.972a2.125 2.125 0 0 1-3.006-3.005L9.97 4.97a.75.75 0 1 1 1.06 1.06L9.058 8.003a.625.625 0 0 0 .884.883l1.972-1.972a2 2 0 0 0 0-2.828Z"
                                                  clip-rule="evenodd" />
                                        </svg>
                                        <span class="font-semibold text-xs"
                                              x-text="files ? files.map(file => file.name).join(', ') : 'بارگذاری ..'"></span>
                                        @error('attachment') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror

                                    </label>
                                </div>
                                <div class="mt-6 flex items-center justify-end gap-x-6">
                                    <button type="submit" name="submit"
                                            class="h-11 inline-flex items-center justify-center bg-primary rounded-full text-white px-8 mr-auto">
                                        <span class="font-semibold text-sm" wire:loading.remove>ارسال پیـــام</span>
                                        <div wire:loading>
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                 viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" width="40px" height="40px"
                                                 style="shape-rendering: auto; display: block; background: transparent;">
                                                <g>
                                                    <path stroke="none" fill="#ffffff"
                                                          d="M19 50A31 31 0 0 0 81 50A31 34 0 0 1 19 50">
                                                        <animateTransform values="0 50 51.5;360 50 51.5" keyTimes="0;1"
                                                                          repeatCount="indefinite" dur="0.8130081300813008s"
                                                                          type="rotate" attributeName="transform"/>
                                                    </path>
                                                    <g/>
                                                </g>
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <!-- end section:tickets:wrapper -->
                    </div>
                </div>
            </div>
        </div>
    </div></div>
