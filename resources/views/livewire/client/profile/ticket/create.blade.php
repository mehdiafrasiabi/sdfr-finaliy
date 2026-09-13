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

                        <x-ui.button href="{{ route('client.profile.ticket') }}" wire:navigate
                                     variant="secondary-outline" icon="chevron-right" pill class="ms-auto">
                            بازگشت
                        </x-ui.button>
                    </div>
                    <!-- end section:title -->

                    <!-- section:tickets:wrapper -->
                    <div class="border border-border rounded-xl overflow-hidden">
                        <form wire:submit.prevent="submit" class="p-4 space-y-4 bg-secondary">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="space-y-1.5">
                                    <label for="subject" class="font-medium text-xs text-muted">موضوع تیکت:</label>
                                    <input type="text" id="subject" wire:model="title" name="title"
                                           class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"
                                           placeholder="موضوع تیکت را وارد کنید"/>
                                    @error('title') <span class="text-error text-xs">{{ $message }}</span> @enderror

                                </div>
                                <div class="space-y-1.5">
                                    <label class="font-medium text-xs text-muted">دپارتمان:</label>

                                    <x-ui.select
                                        wire:model="department_id"
                                        :options="$departments->map(fn($d) => ['id' => $d->id, 'name' => $d->name])->values()->toArray()"
                                        value-key="id"
                                        label-key="name"
                                        placeholder="دپارتمان را انتخاب کنید"
                                    />

                                    @error('department_id')
                                    <span class="text-error text-xs">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="space-y-1.5">
                                <label class="font-medium text-xs text-muted">اولویت:</label>
                                <div class="flex flex-wrap gap-3">
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" wire:model="priority" value="low" class="form-radio text-success bg-secondary border-border focus:ring-0">
                                        <span class="text-xs font-semibold text-muted">کم</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" wire:model="priority" value="medium" class="form-radio text-warning bg-secondary border-border focus:ring-0">
                                        <span class="text-xs font-semibold text-muted">متوسط</span>
                                    </label>
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="radio" wire:model="priority" value="high" class="form-radio text-error bg-secondary border-border focus:ring-0">
                                        <span class="text-xs font-semibold text-muted">زیاد</span>
                                    </label>
                                </div>
                                @error('priority') <span class="text-error text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1.5">
                                <label class="block font-semibold text-xs text-foreground">توضیحات:</label>
                                <textarea rows="5" wire:model="message"
                                          class="form-textarea w-full !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5 py-3"
                                          placeholder="مشکل یا درخواست خود را با جزئیات شرح دهید..."></textarea>
                                @error('message') <span class="text-error text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div class="flex items-center justify-between gap-4">
                                <div class="space-y-1">

                                    <label
                                        data-elevated="false"
                                        class="btn-press inline-flex items-center gap-x-1.5 border border-border rounded-full text-muted py-2 px-4 cursor-pointer hover:text-foreground hover:border-foreground/20 transition-colors"
                                        for="customFile" x-data="{ files: null }">
                                        <input type="file" class="sr-only" id="customFile" wire:model="attachment"
                                               name="attachment"
                                               x-on:change="files = Object.values($event.target.files)">
                                        <span class="font-semibold text-xs"
                                              x-text="files ? files.map(file => file.name).join(', ') : 'فایل پیوست (zip, rar)'"></span>
                                        <x-ui.icon name="paperclip" class="w-4 h-4"/>
                                    </label>
                                    @error('attachment') <span class="text-error text-xs block mt-1">{{ $message }}</span> @enderror

                                </div>
                                <x-ui.button type="submit" wire:loading.attr="disabled" wire:target="submit" variant="primary" pill class="px-8">
                                    <span wire:loading.remove wire:target="submit" class="inline-flex items-center gap-1.5">
                                        ارسال تیکت <x-ui.icon name="send" class="w-4 h-4"/>
                                    </span>
                                    <span wire:loading wire:target="submit">
                                        <x-ui.spinner size="xs"/>
                                    </span>
                                </x-ui.button>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
