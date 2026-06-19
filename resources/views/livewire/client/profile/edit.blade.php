<div>
    @assets
    <link rel="stylesheet" href="/client/assets/date/jalalidatepicker.min.css">
    <script src="/client/assets/date/persian-datepicker.min.js"></script>

    <style>
        .remove-image-btn {
            position: absolute; top: -0.5rem; right: -0.5rem;
            background: #ef4444; color: white;
            border-radius: 9999px;
            width: 2rem; height: 2rem;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        .remove-image-btn:hover { background: #dc2626; transform: scale(1.1); }

        .spinner-circle {
            width: 1.125rem; height: 1.125rem;
            border: 2.25px solid currentColor;
            border-right-color: transparent;
            border-radius: 50%;
            animation: jdp-spin 0.7s linear infinite;
            display: inline-block;
        }
        .spinner-sm { width: 1rem; height: 1rem; border-width: 2px; }
        @keyframes jdp-spin { to { transform: rotate(360deg); } }

        input[data-jdp] { direction: ltr; text-align: center; letter-spacing: 0.05em; }
        .jdp-container { font-family: inherit !important; z-index: 60 !important; }
    </style>
    @endassets

    <div class="max-w-7xl space-y-10 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">

            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>

            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-6"
                     x-data="{
                         activeTab: 'account',
                         previewUrl: null,
                         photoError: false,
                         handleFileChange(event) {
                             const file = event.target.files[0];
                             if (file) {
                                 this.previewUrl = URL.createObjectURL(file);
                                 this.photoError = false;
                             }
                         },
                         removePreview() {
                             this.previewUrl = null;
                             const el = document.getElementById('customFile');
                             if (el) el.value = '';
                             @this.set('new_photo', null);
                         }
                     }">

                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">ویرایش پروفایل</div>
                    </div>

                    {{-- ═══ Notification-style pill tabs ═══ --}}
                    <div class="inline-flex items-center gap-1 p-1 bg-secondary/60 border border-border rounded-full">
                        <button type="button" @click="activeTab = 'account'"
                                :class="activeTab === 'account' ? 'bg-background text-primary shadow-sm' : 'text-foreground/70 hover:text-foreground'"
                                class="px-4 py-2 rounded-full text-sm font-semibold transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                            </svg>
                            اطلاعات حساب
                        </button>
                        <button type="button" @click="activeTab = 'password'"
                                :class="activeTab === 'password' ? 'bg-background text-primary shadow-sm' : 'text-foreground/70 hover:text-foreground'"
                                class="px-4 py-2 rounded-full text-sm font-semibold transition-all flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                            </svg>
                            رمز عبور
                        </button>
                    </div>

                    {{-- ═══════════════ TAB: Account ═══════════════ --}}
                    <div x-show="activeTab === 'account'">
                        <form wire:submit.prevent="save" class="space-y-5">

                            {{-- ═══ Photo card (glass) ═══ --}}
                            <div class="glass border border-border rounded-2xl p-6">
                                <div class="flex flex-col md:flex-row items-center gap-6">
                                    <div class="flex-shrink-0">
                                        <div class="relative">
                                            <div class="w-32 h-32 rounded-full overflow-hidden ring-4 ring-white/40 dark:ring-white/10 shadow-xl bg-gradient-to-br from-blue-100 to-sky-100 dark:from-slate-700 dark:to-slate-600 flex items-center justify-center">

                                                <template x-if="previewUrl">
                                                    <img :src="previewUrl" class="w-full h-full object-cover" alt="پیش‌نمایش">
                                                </template>

                                                @if($photo)
                                                    <img x-show="!previewUrl && !photoError"
                                                         src="{{ asset('user/img/' . auth()->id() . '/' . $photo) }}"
                                                         x-on:error="photoError = true"
                                                         class="w-full h-full object-cover" alt="تصویر پروفایل">
                                                @endif

                                                <div x-show="!previewUrl @if($photo) && photoError @endif"
                                                     class="flex flex-col items-center justify-center px-2">
                                                    @if($gender === 'female')
                                                        <svg class="w-12 h-12 text-pink-400 dark:text-pink-500/70" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                                        </svg>
                                                    @elseif($gender === 'male')
                                                        <svg class="w-12 h-12 text-blue-400 dark:text-blue-500/70" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                                        </svg>
                                                    @else
                                                        <svg class="w-12 h-12 text-muted" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                                        </svg>
                                                    @endif
                                                </div>
                                            </div>

                                            <template x-if="previewUrl">
                                                <button type="button" @click="removePreview()" class="remove-image-btn" title="حذف">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                </button>
                                            </template>
                                        </div>

                                        {{-- Empty/error notice --}}
                                        <div x-show="!previewUrl @if($photo) && photoError @endif" class="mt-3 text-center">
                                            <div class="inline-flex items-center gap-1.5 text-[11px] text-amber-600 dark:text-amber-400 bg-amber-500/10 border border-amber-500/20 rounded-full px-2.5 py-1">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/>
                                                </svg>
                                                <span>تصویری ثبت نشده</span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex-1 text-center md:text-right">
                                        <h3 class="font-bold text-lg text-foreground mb-2 flex items-center justify-center md:justify-start gap-2">
                                            <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z"/>
                                            </svg>
                                            تصویر پروفایل
                                        </h3>
                                        <p class="text-sm text-muted mb-4">فرمت‌های مجاز: JPG, PNG, WEBP — حداکثر ۱ مگابایت</p>
                                        <label for="customFile" class="inline-flex items-center gap-2 bg-background hover:bg-secondary border border-border rounded-xl px-5 py-2.5 cursor-pointer transition-all shadow-sm">
                                            <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5"/>
                                            </svg>
                                            <span class="font-semibold text-sm text-foreground">انتخاب تصویر</span>
                                            <input type="file" class="hidden" id="customFile" wire:model="new_photo" accept="image/*" @change="handleFileChange($event)">
                                        </label>
                                        <div wire:loading wire:target="new_photo" class="mt-2 inline-flex items-center gap-2 text-xs text-muted">
                                            <span class="spinner-circle spinner-sm text-primary"></span>
                                            <span>در حال بارگذاری...</span>
                                        </div>
                                        @error('new_photo')<div class="font-medium text-xs text-red-500 mt-2">{{ $message }}</div>@enderror
                                    </div>
                                </div>
                            </div>

                            {{-- ═══ Fields card (glass) ═══ --}}
                            <div class="glass border border-border rounded-2xl p-6 space-y-5">
                                <div class="flex items-center gap-2 pb-3 border-b border-border">
                                    <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                    </svg>
                                    <h3 class="font-bold text-foreground">اطلاعات حساب</h3>
                                </div>

                                <div class="grid sm:grid-cols-2 gap-5">

                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-foreground">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                                            نام
                                        </label>
                                        <input type="text" wire:model="name" placeholder="نام خود را وارد کنید"
                                               class="w-full h-12 !ring-0 bg-secondary border border-border focus:border-primary rounded-xl text-sm text-foreground px-4 transition-all outline-none">
                                        @error('name')<div class="font-medium text-xs text-red-500">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-foreground">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/></svg>
                                            نام و نام خانوادگی
                                        </label>
                                        <input type="text" wire:model="full_name" placeholder="نام و نام خانوادگی"
                                               class="w-full h-12 !ring-0 bg-secondary border border-border focus:border-primary rounded-xl text-sm text-foreground px-4 transition-all outline-none">
                                        @error('full_name')<div class="font-medium text-xs text-red-500">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-foreground">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75"/></svg>
                                            ایمیل
                                        </label>
                                        <input type="email" dir="ltr" wire:model="email" placeholder="example@email.com"
                                               class="w-full h-12 !ring-0 bg-secondary border border-border focus:border-primary rounded-xl text-sm text-foreground px-4 transition-all outline-none">
                                        @error('email')<div class="font-medium text-xs text-red-500">{{ $message }}</div>@enderror
                                    </div>

                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-muted">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3"/></svg>
                                            شماره موبایل
                                            <span class="text-[10px] bg-muted/20 text-muted rounded px-1.5 py-0.5">غیرقابل ویرایش</span>
                                        </label>
                                        <input type="text" dir="ltr" wire:model="mobile" readonly
                                               class="w-full h-12 !ring-0 bg-secondary/60 border border-dashed border-border rounded-xl text-sm text-muted px-4 cursor-not-allowed">
                                    </div>

                                    {{-- Gender --}}
                                    <div class="space-y-2 sm:col-span-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-foreground">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/></svg>
                                            جنسیت
                                        </label>
                                        <div class="grid grid-cols-2 gap-3">
                                            <label class="relative cursor-pointer">
                                                <input type="radio" wire:model="gender" value="male" class="peer sr-only">
                                                <div class="flex items-center justify-center gap-2 h-12 bg-secondary border-2 border-border peer-checked:border-blue-500 peer-checked:bg-blue-500/10 rounded-xl transition-all">
                                                    <svg class="w-5 h-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                                                    <span class="font-semibold text-sm text-foreground">مرد</span>
                                                </div>
                                            </label>
                                            <label class="relative cursor-pointer">
                                                <input type="radio" wire:model="gender" value="female" class="peer sr-only">
                                                <div class="flex items-center justify-center gap-2 h-12 bg-secondary border-2 border-border peer-checked:border-pink-500 peer-checked:bg-pink-500/10 rounded-xl transition-all">
                                                    <svg class="w-5 h-5 text-pink-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/></svg>
                                                    <span class="font-semibold text-sm text-foreground">زن</span>
                                                </div>
                                            </label>
                                        </div>
                                        @error('gender')<div class="font-medium text-xs text-red-500">{{ $message }}</div>@enderror
                                    </div>

                                    {{-- State with search --}}
                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-foreground">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z"/>
                                            </svg>
                                            استان
                                        </label>
                                        <x-ui.select wire:model.live="state_id"
                                                     wire:key="select-state"
                                                     :options="$states->map(fn($s) => ['id' => $s->id, 'name' => $s->name])->values()->toArray()"
                                                     value-key="id" label-key="name"
                                                     :searchable="true"
                                                     placeholder="انتخاب استان..."
                                                     search-placeholder="جستجوی استان..."/>
                                        @error('state_id')<div class="font-medium text-xs text-red-500">{{ $message }}</div>@enderror
                                    </div>

                                    {{-- City with search --}}
                                    <div class="space-y-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-foreground">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z"/></svg>
                                            شهر
                                        </label>

                                        @if(!$state_id)
                                            <div class="w-full h-12 px-4 flex items-center rounded-xl border border-dashed border-border bg-secondary/40 text-muted text-sm">
                                                ابتدا استان را انتخاب کنید
                                            </div>
                                        @else
                                            <div wire:loading.flex wire:target="state_id"
                                                 class="w-full h-12 px-4 items-center rounded-xl border border-border bg-secondary text-muted gap-2">
                                                <span class="spinner-circle spinner-sm text-primary"></span>
                                                <span class="text-xs">در حال بارگذاری شهرها...</span>
                                            </div>
                                            <div wire:loading.remove wire:target="state_id">
                                                <x-ui.select wire:model.live="city_id"
                                                             wire:key="select-city-{{ $state_id }}"
                                                             :options="$cities->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->values()->toArray()"
                                                             value-key="id" label-key="name"
                                                             :searchable="true"
                                                             placeholder="انتخاب شهر..."
                                                             search-placeholder="جستجوی شهر..."/>
                                            </div>
                                        @endif
                                        @error('city_id')<div class="font-medium text-xs text-red-500">{{ $message }}</div>@enderror
                                    </div>

                                    {{-- Birth date with JalaliDatePicker --}}
                                    <div class="space-y-2 sm:col-span-2">
                                        <label class="flex items-center gap-2 font-semibold text-xs text-foreground">
                                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/></svg>
                                            تاریخ تولد
                                        </label>
                                        <div class="relative">
                                            <input type="text" wire:model="birth_date"
                                                   data-jdp data-jdp-max-date="today"
                                                   placeholder="۱۳۸۰/۰۱/۰۱"
                                                   class="w-full h-12 !ring-0 bg-secondary border border-border focus:border-primary rounded-xl text-sm text-foreground px-4 cursor-pointer transition-all outline-none">
                                            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-muted pointer-events-none" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                                            </svg>
                                        </div>
                                        <p class="text-[11px] text-muted">روی فیلد کلیک کنید تا تقویم باز شود</p>
                                        @error('birth_date')<div class="font-medium text-xs text-red-500">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="flex justify-end pt-3 border-t border-border">
                                    <button type="submit" wire:loading.attr="disabled" wire:target="save"
                                            class="h-11 inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 rounded-full text-white px-8 transition-all disabled:opacity-60 min-w-[140px]">
                                        <span wire:loading.remove wire:target="save" class="font-semibold text-sm">بروزرسانی</span>
                                        <span wire:loading wire:target="save" class="spinner-circle text-white"></span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- ═══════════════ TAB: Password ═══════════════ --}}
                    <div x-show="activeTab === 'password'" x-cloak class="space-y-5">

                        <div class="glass border border-amber-500/30 rounded-2xl p-5" x-data="{ open: true }" x-show="open">
                            <div class="flex items-start gap-3">
                                <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-amber-500/15 shrink-0">
                                    <svg class="w-5 h-5 text-amber-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/>
                                    </svg>
                                </span>
                                <div class="flex-1">
                                    <div class="font-bold text-sm text-amber-600 dark:text-amber-300 mb-2">الزامات رمز عبور</div>
                                    <ul class="space-y-1 text-xs text-amber-600/90 dark:text-amber-400">
                                        <li>• حداقل ۸ کاراکتر</li>
                                        <li>• حداقل یک حرف کوچک</li>
                                        <li>• حداقل یک حرف بزرگ</li>
                                        <li>• حداقل یک عدد</li>
                                    </ul>
                                    <button type="button" @click="open = false" class="mt-3 text-xs text-amber-600 dark:text-amber-400 hover:underline font-semibold">فهمیدم</button>
                                </div>
                            </div>
                        </div>

                        {{-- Change with current password --}}
                        <div x-show="!$wire.showForgotPassword" class="glass border border-border rounded-2xl p-6">
                            <form wire:submit.prevent="changePassword" class="space-y-5">
                                <div class="grid sm:grid-cols-2 gap-5">
                                    <div class="space-y-2">
                                        <label class="font-semibold text-xs text-foreground">رمز فعلی</label>
                                        <input type="password" dir="ltr" wire:model="current_password" placeholder="********"
                                               class="w-full h-12 !ring-0 bg-secondary border border-border focus:border-primary rounded-xl text-sm text-foreground px-4 transition-all outline-none">
                                        @error('current_password')<div class="font-medium text-xs text-red-500">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="space-y-2">
                                        <label class="font-semibold text-xs text-foreground">رمز جدید</label>
                                        <input type="password" dir="ltr" wire:model="new_password" placeholder="********"
                                               class="w-full h-12 !ring-0 bg-secondary border border-border focus:border-primary rounded-xl text-sm text-foreground px-4 transition-all outline-none">
                                        @error('new_password')<div class="font-medium text-xs text-red-500">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="space-y-2 sm:col-span-2">
                                        <label class="font-semibold text-xs text-foreground">تکرار رمز جدید</label>
                                        <input type="password" dir="ltr" wire:model="new_password_confirmation" placeholder="********"
                                               class="w-full h-12 !ring-0 bg-secondary border border-border focus:border-primary rounded-xl text-sm text-foreground px-4 transition-all outline-none">
                                        @error('new_password_confirmation')<div class="font-medium text-xs text-red-500">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-3 border-t border-border">
                                    <button type="button" wire:click="toggleForgotPassword"
                                            class="text-primary text-sm font-medium hover:underline order-2 sm:order-1">
                                        رمز عبور را فراموش کرده‌ام
                                    </button>
                                    <button type="submit" wire:loading.attr="disabled" wire:target="changePassword"
                                            class="w-full sm:w-auto h-11 inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 rounded-full text-white px-8 transition-all order-1 sm:order-2 disabled:opacity-60 min-w-[130px]">
                                        <span wire:loading.remove wire:target="changePassword" class="font-semibold text-sm">تغییر رمز</span>
                                        <span wire:loading wire:target="changePassword" class="spinner-circle text-white"></span>
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- OTP forgot flow --}}
                        <div x-show="$wire.showForgotPassword" x-cloak class="glass border border-border rounded-2xl p-6 space-y-5">
                            <div class="flex items-center gap-3 p-4 bg-blue-500/10 border border-blue-500/20 rounded-xl">
                                <svg class="w-5 h-5 text-blue-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm text-blue-600 dark:text-blue-300">کد تایید به شماره موبایل شما ارسال خواهد شد</span>
                            </div>

                            <div class="space-y-2">
                                <label class="font-semibold text-xs text-foreground">کد تایید</label>
                                <div class="flex gap-2 flex-wrap sm:flex-nowrap">
                                    <input type="text" dir="ltr" wire:model="otp_code" placeholder="کد ۶ رقمی" maxlength="6"
                                           :disabled="$wire.otp_verified"
                                           class="flex-1 min-w-0 h-12 !ring-0 bg-secondary border border-border focus:border-primary rounded-xl text-sm text-foreground px-4 transition-all outline-none disabled:opacity-50 text-center tracking-widest font-mono">

                                    <button type="button" wire:click="sendOtp" wire:loading.attr="disabled" wire:target="sendOtp" x-show="!$wire.otp_verified"
                                            class="h-12 px-5 bg-primary hover:bg-primary/90 text-white rounded-xl text-sm font-semibold transition-all whitespace-nowrap disabled:opacity-60 inline-flex items-center justify-center gap-2 min-w-[110px]">
                                        <span wire:loading.remove wire:target="sendOtp">ارسال کد</span>
                                        <span wire:loading wire:target="sendOtp" class="spinner-circle text-white"></span>
                                    </button>

                                    <button type="button" wire:click="verifyOtp" wire:loading.attr="disabled" wire:target="verifyOtp" x-show="!$wire.otp_verified"
                                            class="h-12 px-5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl text-sm font-semibold transition-all whitespace-nowrap disabled:opacity-60 inline-flex items-center justify-center gap-2 min-w-[110px]">
                                        <span wire:loading.remove wire:target="verifyOtp">تایید کد</span>
                                        <span wire:loading wire:target="verifyOtp" class="spinner-circle text-white"></span>
                                    </button>
                                </div>
                                @error('otp_code')<div class="font-medium text-xs text-red-500">{{ $message }}</div>@enderror

                                <div x-show="$wire.otp_verified" class="inline-flex items-center gap-2 text-xs text-emerald-500 mt-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    کد تایید شد
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-2 gap-5">
                                <template x-if="$wire.otp_verified">
                                    <div class="space-y-2">
                                        <label class="font-semibold text-xs text-foreground">رمز جدید</label>
                                        <input type="password" dir="ltr" wire:model="forgot_new_password" placeholder="********"
                                               class="w-full h-12 !ring-0 bg-secondary border border-border focus:border-primary rounded-xl text-sm text-foreground px-4 transition-all outline-none">
                                        @error('forgot_new_password')<div class="font-medium text-xs text-red-500">{{ $message }}</div>@enderror
                                    </div>
                                </template>

                                <template x-if="$wire.otp_verified">
                                    <div class="space-y-2">
                                        <label class="font-semibold text-xs text-foreground">تکرار رمز جدید</label>
                                        <input type="password" dir="ltr" wire:model="forgot_new_password_confirmation" placeholder="********"
                                               class="w-full h-12 !ring-0 bg-secondary border border-border focus:border-primary rounded-xl text-sm text-foreground px-4 transition-all outline-none">
                                        @error('forgot_new_password_confirmation')<div class="font-medium text-xs text-red-500">{{ $message }}</div>@enderror
                                    </div>
                                </template>
                            </div>

                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-3 border-t border-border">
                                <button type="button" wire:click="toggleForgotPassword"
                                        class="text-muted text-sm font-medium hover:underline hover:text-foreground transition-colors order-2 sm:order-1">
                                    بازگشت
                                </button>
                                <button type="button" wire:click="changePasswordWithOtp" wire:loading.attr="disabled" wire:target="changePasswordWithOtp" x-show="$wire.otp_verified"
                                        class="w-full sm:w-auto h-11 inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 rounded-full text-white px-8 transition-all order-1 sm:order-2 disabled:opacity-60 min-w-[130px]">
                                    <span wire:loading.remove wire:target="changePasswordWithOtp" class="font-semibold text-sm">تغییر رمز</span>
                                    <span wire:loading wire:target="changePasswordWithOtp" class="spinner-circle text-white"></span>
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('script')
        <script>
            // ═══ JalaliDatePicker init — runs on initial load AND wire:navigate
            function initJalaliDatePicker() {
                if (typeof jalaliDatepicker !== 'undefined') {
                    try {
                        jalaliDatepicker.startWatch({
                            persianDigits: true,
                            showTodayBtn: true,
                            showEmptyBtn: true,
                            time: false,
                            autoHide: true,
                            zIndex: 100,
                        });
                    } catch(e) { console.warn('[jdp] init failed', e); }
                }
            }
            document.addEventListener('DOMContentLoaded', initJalaliDatePicker);
            document.addEventListener('livewire:navigated', initJalaliDatePicker);
            document.addEventListener('livewire:initialized', initJalaliDatePicker);
        </script>
    @endpush
</div>
