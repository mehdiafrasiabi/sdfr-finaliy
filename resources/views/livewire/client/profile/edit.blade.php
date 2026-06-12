<div>
    @assets
    <style>
        .remove-image-btn {
            position: absolute;
            top: -0.5rem;
            right: -0.5rem;
            background: #ef4444;
            color: white;
            border-radius: 9999px;
            width: 2rem;
            height: 2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        .remove-image-btn:hover {
            background: #dc2626;
            transform: scale(1.1);
        }
        .password-eye-btn{
            position:absolute;
            inset-inline-end: .75rem;
            top:50%;
            transform: translateY(-50%);
            display:flex;
            align-items:center;
            justify-content:center;
            width:2.5rem;
            height:2.5rem;
            border-radius: .75rem;
            color: hsl(var(--muted-foreground));
            transition: all .15s ease;
        }
        .password-eye-btn:hover{
            background: hsl(var(--secondary));
            color: hsl(var(--foreground));
        }
    </style>
    @endassets

    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>

            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-10">
                    <div class="space-y-5">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-foreground">ویرایش پروفایل</div>
                        </div>

                        <div class="space-y-5" x-data="{ activeTab: 'tabOne'}">
                            <div class="relative overflow-x-auto">
                                <ul class="inline-flex gap-2 bg-secondary border border-border rounded-full p-1">
                                    <li>
                                        <button type="button"
                                                class="flex items-center gap-x-2 w-full relative rounded-full py-2 px-4 transition-all"
                                                :class="activeTab === 'tabOne' ? 'text-foreground bg-background' : 'text-muted'"
                                                @click="activeTab = 'tabOne'">
                                            <span x-show="activeTab === 'tabOne'">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                    <path d="M16.7574 2.99677L9.29145 10.4627L9.29886 14.7098L13.537 14.7024L21 7.23941V19.9968C21 20.5491 20.5523 20.9968 20 20.9968H4C3.44772 20.9968 3 20.5491 3 19.9968V3.99677C3 3.44448 3.44772 2.99677 4 2.99677H16.7574ZM20.4853 2.09727L21.8995 3.51149L12.7071 12.7039L11.2954 12.7063L11.2929 11.2897L20.4853 2.09727Z"></path>
                                                </svg>
                                            </span>
                                            <span x-show="activeTab !== 'tabOne'">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                    <path d="M16.7574 2.99677L14.7574 4.99677H5V18.9968H19V9.23941L21 7.23941V19.9968C21 20.5491 20.5523 20.9968 20 20.9968H4C3.44772 20.9968 3 20.5491 3 19.9968V3.99677C3 3.44448 3.44772 2.99677 4 2.99677H16.7574ZM20.4853 2.09727L21.8995 3.51149L12.7071 12.7039L11.2954 12.7063L11.2929 11.2897L20.4853 2.09727Z"></path>
                                                </svg>
                                            </span>
                                            <span class="font-semibold text-sm whitespace-nowrap">اطلاعات حساب</span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button"
                                                class="flex items-center gap-x-2 w-full relative rounded-full py-2 px-4 transition-all"
                                                :class="activeTab === 'tabThree' ? 'text-foreground bg-background' : 'text-muted'"
                                                @click="activeTab = 'tabThree'">
                                            <span x-show="activeTab === 'tabThree'">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                    <path d="M17 14H12.6586C11.8349 16.3304 9.61244 18 7 18C3.68629 18 1 15.3137 1 12C1 8.68629 3.68629 6 7 6C9.61244 6 11.8349 7.66962 12.6586 10H23V14H21V18H17V14ZM7 14C8.10457 14 9 13.1046 9 12C9 10.8954 8.10457 10 7 10C5.89543 10 5 10.8954 5 12C5 13.1046 5.89543 14 7 14Z"></path>
                                                </svg>
                                            </span>
                                            <span x-show="activeTab !== 'tabThree'">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                                    <path d="M12.917 13C12.441 15.8377 9.973 18 7 18C3.68629 18 1 15.3137 1 12C1 8.68629 3.68629 6 7 6C9.973 6 12.441 8.16229 12.917 11H23V13H21V17H19V13H17V17H15V13H12.917ZM7 16C9.20914 16 11 14.2091 11 12C11 9.79086 9.20914 8 7 8C4.79086 8 3 9.79086 3 12C3 14.2091 4.79086 16 7 16Z"></path>
                                                </svg>
                                            </span>
                                            <span class="font-semibold text-sm whitespace-nowrap">رمز عبور</span>
                                        </button>
                                    </li>
                                </ul>
                            </div>

                            <div class="bg-background rounded-3xl p-5">

                                {{-- ════════════ تب اطلاعات حساب ════════════ --}}
                                <div class="space-y-5" x-show="activeTab === 'tabOne'"
                                     x-data="{
                                         previewUrl: null,
                                         handleFileChange(event) {
                                             const file = event.target.files[0];
                                             if (file) { this.previewUrl = URL.createObjectURL(file); }
                                         },
                                         removePreview() {
                                             this.previewUrl = null;
                                             document.getElementById('customFile').value = '';
                                             @this.set('new_photo', null);
                                         }
                                     }">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1">
                                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                                        </div>
                                        <div class="font-black text-foreground">اطلاعات حساب</div>
                                    </div>

                                    <form wire:submit.prevent="save" class="space-y-6">
                                        {{-- پروفایل تصویر --}}
                                        <div class="bg-gradient-to-br from-blue-50 to-sky-50 dark:from-slate-800 dark:to-slate-700 rounded-2xl p-6 border border-blue-200 dark:border-slate-600">
                                            <div class="flex flex-col md:flex-row items-center gap-6">
                                                <div class="flex-shrink-0">
                                                    <div class="relative">
                                                        <div class="w-32 h-32 rounded-full overflow-hidden ring-4 ring-white dark:ring-slate-800 shadow-xl bg-gradient-to-br from-blue-100 to-sky-100 dark:from-slate-700 dark:to-slate-600 flex items-center justify-center">
                                                            <template x-if="previewUrl">
                                                                <img :src="previewUrl" class="w-full h-full object-cover" alt="Preview">
                                                            </template>
                                                            <template x-if="!previewUrl && '{{ $photo }}'">
                                                                <img src="{{ asset('user/img/' . auth()->id() . '/' . $photo) }}" class="w-full h-full object-cover" alt="Current Photo">
                                                            </template>
                                                            <template x-if="!previewUrl && !'{{ $photo }}'">
                                                                @if($gender === 'female')
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-pink-500">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                                                    </svg>
                                                                @elseif($gender === 'male')
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-blue-500">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                                                    </svg>
                                                                @else
                                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-16 h-16 text-slate-400">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                                                    </svg>
                                                                @endif
                                                            </template>
                                                        </div>
                                                        <template x-if="previewUrl">
                                                            <button type="button" @click="removePreview()" class="remove-image-btn">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                                                </svg>
                                                            </button>
                                                        </template>
                                                    </div>
                                                </div>
                                                <div class="flex-1 text-center md:text-right">
                                                    <h3 class="font-bold text-lg text-foreground mb-2">تصویر پروفایل</h3>
                                                    <p class="text-sm text-muted mb-4">فرمت‌های مجاز: JPG, PNG, WEBP - حداکثر 1MB</p>
                                                    <label for="customFile" class="inline-flex items-center gap-2 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-xl px-5 py-2.5 cursor-pointer transition-all shadow-sm hover:shadow">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-primary">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                                        </svg>
                                                        <span class="font-semibold text-sm text-foreground">انتخاب تصویر</span>
                                                        <input type="file" class="hidden" id="customFile" wire:model="new_photo" accept="image/*" @change="handleFileChange($event)">
                                                    </label>
                                                    @error('new_photo')
                                                    <div class="font-medium text-xs text-red-500 mt-2">{{$message}}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        {{-- فیلدهای فرم --}}
                                        <div class="grid sm:grid-cols-2 gap-5">
                                            <div class="space-y-2">
                                                <label for="name" class="flex items-center gap-2 font-semibold text-sm text-foreground">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-primary">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                                    </svg>
                                                    نام
                                                </label>
                                                <input type="text" id="name" wire:model="name" placeholder="نام خود را وارد کنید"
                                                       class="form-input w-full h-12 !ring-0 !ring-offset-0 bg-secondary border-2 border-border focus:border-primary rounded-xl text-sm text-foreground px-4 transition-all"/>
                                                @error('name')
                                                <div class="font-medium text-xs text-red-500 mt-1">{{$message}}</div>
                                                @enderror
                                            </div>

                                            <div class="space-y-2">
                                                <label for="full_name" class="flex items-center gap-2 font-semibold text-sm text-foreground">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-primary">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                    </svg>
                                                    نام و نام خانوادگی
                                                </label>
                                                <input type="text" id="full_name" wire:model="full_name" placeholder="نام و نام خانوادگی"
                                                       class="form-input w-full h-12 !ring-0 !ring-offset-0 bg-secondary border-2 border-border focus:border-primary rounded-xl text-sm text-foreground px-4 transition-all"/>
                                                @error('full_name')
                                                <div class="font-medium text-xs text-red-500 mt-1">{{$message}}</div>
                                                @enderror
                                            </div>

                                            <div class="space-y-2">
                                                <label for="email" class="flex items-center gap-2 font-semibold text-sm text-foreground">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-primary">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                                                    </svg>
                                                    ایمیل
                                                </label>
                                                <input type="email" id="email" dir="ltr" wire:model="email" placeholder="example@email.com"
                                                       class="form-input w-full h-12 !ring-0 !ring-offset-0 bg-secondary border-2 border-border focus:border-primary rounded-xl text-sm text-foreground px-4 transition-all"/>
                                                @error('email')
                                                <div class="font-medium text-xs text-red-500 mt-1">{{$message}}</div>
                                                @enderror
                                            </div>

                                            <div class="space-y-2">
                                                <label for="mobile" class="flex items-center gap-2 font-semibold text-sm text-muted">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" />
                                                    </svg>
                                                    شماره موبایل (غیرقابل ویرایش)
                                                </label>
                                                <input type="text" id="mobile" dir="ltr" wire:model="mobile" readonly
                                                       class="form-input w-full h-12 !ring-0 !ring-offset-0 bg-slate-100 dark:bg-slate-800 border-2 border-border rounded-xl text-sm text-muted px-4 cursor-not-allowed"/>
                                            </div>

                                            {{-- جنسیت --}}
                                            <div class="space-y-2">
                                                <label class="flex items-center gap-2 font-semibold text-sm text-foreground">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-primary">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                                    </svg>
                                                    جنسیت
                                                </label>
                                                <div class="grid grid-cols-2 gap-3">
                                                    <label class="relative cursor-pointer">
                                                        <input type="radio" wire:model="gender" value="male" class="peer sr-only">
                                                        <div class="flex items-center justify-center gap-2 h-12 bg-secondary border-2 border-border peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 rounded-xl transition-all">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-600">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                                            </svg>
                                                            <span class="font-semibold text-sm text-foreground">مرد</span>
                                                        </div>
                                                    </label>
                                                    <label class="relative cursor-pointer">
                                                        <input type="radio" wire:model="gender" value="female" class="peer sr-only">
                                                        <div class="flex items-center justify-center gap-2 h-12 bg-secondary border-2 border-border peer-checked:border-pink-500 peer-checked:bg-pink-50 dark:peer-checked:bg-pink-900/20 rounded-xl transition-all">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-pink-600">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                                            </svg>
                                                            <span class="font-semibold text-sm text-foreground">زن</span>
                                                        </div>
                                                    </label>
                                                </div>
                                                @error('gender')
                                                <div class="font-medium text-xs text-red-500 mt-1">{{$message}}</div>
                                                @enderror
                                            </div>

                                            {{-- ═══ استان (x-ui.select) ═══ --}}
                                            <div class="space-y-2">
                                                <label class="flex items-center gap-2 font-semibold text-sm text-foreground">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-primary">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                                    </svg>
                                                    استان
                                                </label>
                                                <x-ui.select wire:model.live="state_id"
                                                             wire:key="select-state"
                                                             :options="$states->map(fn($s) => ['id' => $s->id, 'name' => $s->name])->values()->toArray()"
                                                             value-key="id" label-key="name"
                                                             placeholder="انتخاب استان..."/>
                                                @error('state_id')
                                                <div class="font-medium text-xs text-red-500 mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- ═══ شهر (x-ui.select) ═══ --}}
                                            <div class="space-y-2">
                                                <label class="flex items-center gap-2 font-semibold text-sm text-foreground">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-primary">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                                                    </svg>
                                                    شهر
                                                </label>

                                                @if(!$state_id)
                                                    <div class="w-full h-12 px-4 flex items-center rounded-xl border-2 border-dashed border-border bg-secondary/50 text-muted text-sm">
                                                        ابتدا استان را انتخاب کنید
                                                    </div>
                                                @else
                                                    <div wire:loading.flex wire:target="state_id"
                                                         class="w-full h-12 px-4 items-center rounded-xl border-2 border-border bg-secondary text-muted text-sm gap-2">
                                                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                                                        </svg>
                                                        در حال بارگذاری شهرها...
                                                    </div>
                                                    <div wire:loading.remove wire:target="state_id">
                                                        <x-ui.select wire:model.live="city_id"
                                                                     wire:key="select-city-{{ $state_id }}"
                                                                     :options="$cities->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->values()->toArray()"
                                                                     value-key="id" label-key="name"
                                                                     placeholder="انتخاب شهر..."/>
                                                    </div>
                                                @endif
                                                @error('city_id')
                                                <div class="font-medium text-xs text-red-500 mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            {{-- تاریخ تولد --}}
                                            <div class="space-y-2 sm:col-span-2">
                                                <label class="flex items-center gap-2 font-semibold text-sm text-foreground">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-primary">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                                    </svg>
                                                    تاریخ تولد
                                                </label>
                                                <input type="text" wire:model="birth_date" dir="ltr" placeholder="مثال: ۱۳۸۰/۰۱/۰۱"
                                                       class="form-input w-full h-12 !ring-0 !ring-offset-0 bg-secondary border-2 border-border focus:border-primary rounded-xl text-sm text-foreground px-4 text-center tracking-wider transition-all"/>
                                                <p class="text-xs text-muted">فرمت: سال/ماه/روز (شمسی)</p>
                                                @error('birth_date')
                                                <div class="font-medium text-xs text-red-500 mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="flex justify-end gap-3 pt-4">
                                            <button type="submit"
                                                    class="h-11 inline-flex items-center justify-center gap-3 bg-primary hover:bg-primary/90 rounded-full text-white px-6 transition-all">
                                                <span class="font-semibold text-sm" wire:loading.remove wire:target="save">بروزرسانی</span>
                                                <div wire:loading wire:target="save">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" width="24px" height="24px">
                                                        <g>
                                                            <path stroke="none" fill="#ffffff" d="M19 50A31 31 0 0 0 81 50A31 34 0 0 1 19 50">
                                                                <animateTransform values="0 50 51.5;360 50 51.5" keyTimes="0;1" repeatCount="indefinite" dur="0.8s" type="rotate" attributeName="transform"/>
                                                            </path>
                                                        </g>
                                                    </svg>
                                                </div>
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                {{-- ════════════ تب رمز عبور ════════════ --}}
                                <div class="space-y-5" x-show="activeTab === 'tabThree'">
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1">
                                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                                        </div>
                                        <div class="font-black text-foreground">رمز عبور</div>
                                    </div>

                                    <div class="flex items-start gap-3 relative bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-5" x-data="{ open: true }" x-show="open">
                                        <span class="text-yellow-500">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd"/>
                                            </svg>
                                        </span>
                                        <div class="flex-1">
                                            <div class="font-bold text-sm text-yellow-700 dark:text-yellow-300 mb-2">الزامات رمز عبور:</div>
                                            <ul class="space-y-1 text-xs text-yellow-600 dark:text-yellow-400">
                                                <li>• حداقل ۸ کاراکتر</li>
                                                <li>• حداقل یک حرف کوچک</li>
                                                <li>• حداقل یک حرف بزرگ</li>
                                                <li>• حداقل یک عدد</li>
                                            </ul>
                                            <button type="button" @click="open = false" class="mt-3 text-xs text-yellow-600 dark:text-yellow-400 hover:underline font-semibold">
                                                فهمیدم
                                            </button>
                                        </div>
                                    </div>

                                    {{-- فرم تغییر رمز با رمز فعلی --}}
                                    <div x-show="!$wire.showForgotPassword" class="space-y-5">
                                        <form wire:submit.prevent="changePassword" class="space-y-5">
                                            <div class="grid sm:grid-cols-2 gap-5">
                                                <div class="space-y-2">
                                                    <label for="current_password" class="flex items-center gap-2 font-semibold text-sm text-foreground">رمز فعلی</label>
                                                    <input type="password" dir="ltr" id="current_password" wire:model="current_password" placeholder="********"
                                                           class="form-input w-full h-12 !ring-0 !ring-offset-0 bg-secondary border-2 border-border focus:border-primary rounded-xl text-sm text-foreground px-4 transition-all"/>
                                                    @error('current_password')
                                                    <div class="font-medium text-xs text-red-500 mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="space-y-2">
                                                    <label for="new_password" class="flex items-center gap-2 font-semibold text-sm text-foreground">رمز جدید</label>
                                                    <input type="password" dir="ltr" id="new_password" wire:model="new_password" placeholder="********"
                                                           class="form-input w-full h-12 !ring-0 !ring-offset-0 bg-secondary border-2 border-border focus:border-primary rounded-xl text-sm text-foreground px-4 transition-all"/>
                                                    @error('new_password')
                                                    <div class="font-medium text-xs text-red-500 mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="space-y-2 sm:col-span-2">
                                                    <label for="new_password_confirmation" class="flex items-center gap-2 font-semibold text-sm text-foreground">تکرار رمز جدید</label>
                                                    <input type="password" dir="ltr" id="new_password_confirmation" wire:model="new_password_confirmation" placeholder="********"
                                                           class="form-input w-full h-12 !ring-0 !ring-offset-0 bg-secondary border-2 border-border focus:border-primary rounded-xl text-sm text-foreground px-4 transition-all"/>
                                                    @error('new_password_confirmation')
                                                    <div class="font-medium text-xs text-red-500 mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                                                <button type="button" wire:click="toggleForgotPassword"
                                                        class="text-primary text-sm font-medium hover:underline order-2 sm:order-1">
                                                    رمز عبور را فراموش کرده‌ام
                                                </button>
                                                <button type="submit"
                                                        class="w-full sm:w-auto h-11 inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 rounded-full text-white px-6 transition-all order-1 sm:order-2">
                                                    <span class="font-semibold text-sm" wire:loading.remove wire:target="changePassword">تغییر رمز</span>
                                                    <div wire:loading wire:target="changePassword">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" width="24px" height="24px">
                                                            <g><path stroke="none" fill="#ffffff" d="M19 50A31 31 0 0 0 81 50A31 34 0 0 1 19 50"><animateTransform values="0 50 51.5;360 50 51.5" keyTimes="0;1" repeatCount="indefinite" dur="0.8s" type="rotate" attributeName="transform"/></path></g>
                                                        </svg>
                                                    </div>
                                                </button>
                                            </div>
                                        </form>
                                    </div>

                                    {{-- فرم فراموشی رمز با OTP --}}
                                    <div x-show="$wire.showForgotPassword" class="space-y-5">
                                        <div class="flex items-center gap-3 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-blue-500 flex-shrink-0">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-sm text-blue-700 dark:text-blue-300">کد تایید به شماره موبایل شما ارسال خواهد شد</span>
                                        </div>

                                        <div class="grid sm:grid-cols-2 gap-5">
                                            <div class="space-y-2">
                                                <label class="flex items-center gap-2 font-semibold text-sm text-foreground">کد تایید</label>
                                                <div class="flex gap-2">
                                                    <input type="text" dir="ltr" wire:model="otp_code" placeholder="کد ۶ رقمی" maxlength="6"
                                                           :disabled="$wire.otp_verified"
                                                           class="form-input flex-1 h-12 !ring-0 !ring-offset-0 bg-secondary border-2 border-border focus:border-primary rounded-xl text-sm text-foreground px-4 transition-all disabled:opacity-50 disabled:cursor-not-allowed"/>
                                                    <button type="button" wire:click="sendOtp" x-show="!$wire.otp_verified"
                                                            class="h-12 px-5 bg-gradient-to-r from-blue-500 to-sky-500 hover:from-blue-600 hover:to-sky-600 text-white rounded-xl text-sm font-semibold transition-all whitespace-nowrap">
                                                        <span wire:loading.remove wire:target="sendOtp">ارسال کد</span>
                                                        <span wire:loading wire:target="sendOtp">صبر کنید...</span>
                                                    </button>
                                                </div>
                                                @error('otp_code')
                                                <div class="font-medium text-xs text-red-500 mt-1">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="space-y-2 flex items-end" x-show="!$wire.otp_verified">
                                                <button type="button" wire:click="verifyOtp"
                                                        class="w-full h-12 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white rounded-xl text-sm font-semibold transition-all">
                                                    <span wire:loading.remove wire:target="verifyOtp">تایید کد</span>
                                                    <span wire:loading wire:target="verifyOtp">صبر کنید...</span>
                                                </button>
                                            </div>

                                            <template x-if="$wire.otp_verified">
                                                <div class="space-y-2">
                                                    <label class="flex items-center gap-2 font-semibold text-sm text-foreground">رمز جدید</label>
                                                    <input type="password" dir="ltr" wire:model="forgot_new_password" placeholder="********"
                                                           class="form-input w-full h-12 !ring-0 !ring-offset-0 bg-secondary border-2 border-border focus:border-primary rounded-xl text-sm text-foreground px-4 transition-all"/>
                                                    @error('forgot_new_password')
                                                    <div class="font-medium text-xs text-red-500 mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </template>

                                            <template x-if="$wire.otp_verified">
                                                <div class="space-y-2">
                                                    <label class="flex items-center gap-2 font-semibold text-sm text-foreground">تکرار رمز جدید</label>
                                                    <input type="password" dir="ltr" wire:model="forgot_new_password_confirmation" placeholder="********"
                                                           class="form-input w-full h-12 !ring-0 !ring-offset-0 bg-secondary border-2 border-border focus:border-primary rounded-xl text-sm text-foreground px-4 transition-all"/>
                                                    @error('forgot_new_password_confirmation')
                                                    <div class="font-medium text-xs text-red-500 mt-1">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </template>
                                        </div>

                                        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-2">
                                            <button type="button" wire:click="toggleForgotPassword"
                                                    class="text-muted text-sm font-medium hover:underline hover:text-foreground transition-colors order-2 sm:order-1">
                                                بازگشت به تغییر رمز با رمز فعلی
                                            </button>
                                            <button type="button" wire:click="changePasswordWithOtp" x-show="$wire.otp_verified"
                                                    class="w-full sm:w-auto h-11 inline-flex items-center justify-center gap-2 bg-primary hover:bg-primary/90 rounded-full text-white px-6 transition-all order-1 sm:order-2">
                                                <span class="font-semibold text-sm" wire:loading.remove wire:target="changePasswordWithOtp">تغییر رمز</span>
                                                <div wire:loading wire:target="changePasswordWithOtp">صبر کنید...</div>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
