
<div>
    @push('link')
        <!-- Tom Select CSS -->
        <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
        <style>
            .tom-select .ts-control {
                background: var(--secondary) !important;
                border: 1px solid var(--border) !important;
                border-radius: 0.75rem !important;
                min-height: 2.75rem !important;
                padding: 0.5rem 1.25rem !important;
            }
            .tom-select .ts-dropdown {
                background: var(--background) !important;
                border: 1px solid var(--border) !important;
                border-radius: 0.75rem !important;
                margin-top: 0.25rem !important;
            }
            .tom-select .ts-dropdown .option {
                padding: 0.5rem 1rem !important;
                color: var(--foreground) !important;
            }
            .tom-select .ts-dropdown .option.active {
                background: var(--primary) !important;
                color: var(--primary-foreground) !important;
            }
            .dark .tom-select .ts-control,
            .dark .tom-select .ts-dropdown {
                background: rgb(30 41 59) !important;
            }

            /* Preview Image Styles */
            .image-preview-container {
                position: relative;
                display: inline-block;
            }
            .image-preview-container img {
                border-radius: 1rem;
                object-fit: cover;
            }
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
            }
            .remove-image-btn:hover {
                background: #dc2626;
                transform: scale(1.1);
            }
        </style>
    @endpush
        <div class="max-w-7xl space-y-14 px-4 mx-auto">
            <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
                <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">


                    <!-- user:menus -->
                    <livewire:client.profile.sidebar/>
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
                                <div class="font-black text-foreground">ویرایش پروفایل</div>
                            </div>
                            <!-- end section:title -->

                            <!-- tabs container -->
                            <div class="space-y-5" x-data="{ activeTab: 'tabOne'}">
                                <!-- tabs:list-container -->
                                <div class="relative overflow-x-auto">
                                    <!-- tabs:list -->
                                    <ul
                                        class="inline-flex gap-2 bg-secondary border border-border rounded-full p-1">
                                        <!-- tabs:list:item -->
                                        <li>
                                            <button type="button"
                                                    class="flex items-center gap-x-2 w-full relative rounded-full py-2 px-4"
                                                    x-bind:class="activeTab === 'tabOne' ? 'text-foreground bg-background' : 'text-muted'"
                                                    x-on:click="activeTab = 'tabOne'">
                                                <!-- active icon -->
                                                <span x-show="activeTab === 'tabOne'">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                             fill="currentColor" class="w-5 h-5">
                                                            <path
                                                                d="M16.7574 2.99677L9.29145 10.4627L9.29886 14.7098L13.537 14.7024L21 7.23941V19.9968C21 20.5491 20.5523 20.9968 20 20.9968H4C3.44772 20.9968 3 20.5491 3 19.9968V3.99677C3 3.44448 3.44772 2.99677 4 2.99677H16.7574ZM20.4853 2.09727L21.8995 3.51149L12.7071 12.7039L11.2954 12.7063L11.2929 11.2897L20.4853 2.09727Z">
                                                            </path>
                                                        </svg>
                                                    </span><!-- end active icon -->

                                                <!-- inactive icon -->
                                                <span x-show="activeTab !== 'tabOne'">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                             fill="currentColor" class="w-5 h-5">
                                                            <path
                                                                d="M16.7574 2.99677L14.7574 4.99677H5V18.9968H19V9.23941L21 7.23941V19.9968C21 20.5491 20.5523 20.9968 20 20.9968H4C3.44772 20.9968 3 20.5491 3 19.9968V3.99677C3 3.44448 3.44772 2.99677 4 2.99677H16.7574ZM20.4853 2.09727L21.8995 3.51149L12.7071 12.7039L11.2954 12.7063L11.2929 11.2897L20.4853 2.09727Z">
                                                            </path>
                                                        </svg>
                                                    </span><!-- end inactive icon -->

                                                <span class="font-semibold text-sm whitespace-nowrap">اطلاعات
                                                        حساب</span>
                                            </button>
                                        </li>
                                        <!-- end tabs:list:item -->

                                        <!-- tabs:list:item -->
                                        <!-- end tabs:list:item -->

                                        <!-- tabs:list:item -->
                                        <li>
                                            <button type="button"
                                                    class="flex items-center gap-x-2 w-full relative rounded-full py-2 px-4"
                                                    x-bind:class="activeTab === 'tabThree' ? 'text-foreground bg-background' : 'text-muted'"
                                                    x-on:click="activeTab = 'tabThree'">
                                                <!-- active icon -->
                                                <span x-show="activeTab === 'tabThree'">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                             fill="currentColor" class="w-5 h-5">
                                                            <path
                                                                d="M17 14H12.6586C11.8349 16.3304 9.61244 18 7 18C3.68629 18 1 15.3137 1 12C1 8.68629 3.68629 6 7 6C9.61244 6 11.8349 7.66962 12.6586 10H23V14H21V18H17V14ZM7 14C8.10457 14 9 13.1046 9 12C9 10.8954 8.10457 10 7 10C5.89543 10 5 10.8954 5 12C5 13.1046 5.89543 14 7 14Z">
                                                            </path>
                                                        </svg>
                                                    </span><!-- end active icon -->

                                                <!-- inactive icon -->
                                                <span x-show="activeTab !== 'tabThree'">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                             fill="currentColor" class="w-5 h-5">
                                                            <path
                                                                d="M12.917 13C12.441 15.8377 9.973 18 7 18C3.68629 18 1 15.3137 1 12C1 8.68629 3.68629 6 7 6C9.973 6 12.441 8.16229 12.917 11H23V13H21V17H19V13H17V17H15V13H12.917ZM7 16C9.20914 16 11 14.2091 11 12C11 9.79086 9.20914 8 7 8C4.79086 8 3 9.79086 3 12C3 14.2091 4.79086 16 7 16Z">
                                                            </path>
                                                        </svg>
                                                    </span><!-- end inactive icon -->

                                                <span class="font-semibold text-sm whitespace-nowrap">رمز
                                                        عبور</span>
                                            </button>
                                        </li><!-- end tabs:list:item -->

                                    </ul><!-- end tabs:list -->
                                </div><!-- end tabs:list-container -->
                                <!-- tabs:contents -->
                                <div class="bg-background rounded-3xl p-5">
                                    <!-- tabs:contents:tabOne -->
                                    <div class="space-y-5" x-show="activeTab === 'tabOne'" x-data="{
                                previewUrl: null,
                                handleFileChange(event) {
                                    const file = event.target.files[0];
                                    if (file) {
                                        this.previewUrl = URL.createObjectURL(file);
                                    }
                                },
                                removePreview() {
                                    this.previewUrl = null;
                                    document.getElementById('customFile').value = '';
                                    @this.set('new_photo', null);
                                }
                            }">                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center gap-1">
                                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                                            </div>
                                            <div class="font-black text-foreground">اطلاعات حساب</div>
                                        </div>

                                        <form wire:submit.prevent="save" class="space-y-6">
                                            <!-- پروفایل تصویر -->
                                            <div class="bg-gradient-to-br from-blue-50 to-purple-50 dark:from-slate-800 dark:to-slate-700 rounded-2xl p-6 border border-blue-200 dark:border-slate-600">
                                                <div class="flex flex-col md:flex-row items-center gap-6">
                                                    <!-- نمایش تصویر -->
                                                    <div class="flex-shrink-0">
                                                        <div class="relative">
                                                            <div class="w-32 h-32 rounded-full overflow-hidden ring-4 ring-white dark:ring-slate-800 shadow-xl bg-gradient-to-br from-blue-100 to-purple-100 dark:from-slate-700 dark:to-slate-600 flex items-center justify-center">
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
                                                    <!-- دکمه آپلود -->
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

                                            <!-- فیلدهای فرم -->
                                            <div class="grid sm:grid-cols-2 gap-5">
                                                <!-- نام -->
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
                                                    <div class="font-medium text-xs text-red-500 flex items-center gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                                        </svg>
                                                        {{$message}}
                                                    </div>
                                                    @enderror
                                                </div>

                                                <!-- نام و نام خانوادگی -->
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
                                                    <div class="font-medium text-xs text-red-500 flex items-center gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                                        </svg>
                                                        {{$message}}
                                                    </div>
                                                    @enderror
                                                </div>
                                                <!-- ایمیل -->
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
                                                    <div class="font-medium text-xs text-red-500 flex items-center gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                                        </svg>
                                                        {{$message}}
                                                    </div>
                                                    @enderror
                                                </div>

                                                <!-- موبایل -->
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

                                                <!-- جنسیت -->
                                                <div class="space-y-2">
                                                    <label for="gender" class="flex items-center gap-2 font-semibold text-sm text-foreground">
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
                                                                <span class="font-semibold text-sm peer-checked:text-blue-600">مرد</span>
                                                            </div>
                                                        </label>
                                                        <label class="relative cursor-pointer">
                                                            <input type="radio" wire:model="gender" value="female" class="peer sr-only">
                                                            <div class="flex items-center justify-center gap-2 h-12 bg-secondary border-2 border-border peer-checked:border-pink-500 peer-checked:bg-pink-50 dark:peer-checked:bg-pink-900/20 rounded-xl transition-all">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-pink-600">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                                                                </svg>
                                                                <span class="font-semibold text-sm peer-checked:text-pink-600">زن</span>
                                                            </div>
                                                        </label>
                                                    </div>

                                                    @error('gender')
                                                    <div class="font-medium text-xs text-red-500 flex items-center gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                                        </svg>
                                                        {{$message}}

                                                    </div>
                                                    @enderror
                                                </div>


                                                <!-- استان -->
                                                <div class="space-y-2" wire:ignore>
                                                    <label for="state_id" class="flex items-center gap-2 font-semibold text-sm text-foreground">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-primary">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                                        </svg>
                                                        استان
                                                    </label>
                                                    <select id="state_id" wire:model.live="state_id">
                                                        <option value="">انتخاب کنید</option>
                                                        @foreach($states as $state)
                                                            <option value="{{ $state->id }}">{{ $state->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('state_id')
                                                    <div class="font-medium text-xs text-red-500 flex items-center gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                                        </svg>
                                                        {{$message}}
                                                    </div>
                                                    @enderror
                                                </div>

                                                <!-- شهر -->
                                                <div class="space-y-2" wire:ignore>
                                                    <label for="city_id" class="flex items-center gap-2 font-semibold text-sm text-foreground">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-primary">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                                                        </svg>
                                                        شهر
                                                    </label>
                                                    <select id="city_id" wire:model="city_id">
                                                        <option value="">انتخاب کنید</option>
                                                        @foreach($cities as $city)
                                                            <option value="{{ $city->id }}">{{ $city->name }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('city_id')
                                                    <div class="font-medium text-xs text-red-500 flex items-center gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z" />
                                                        </svg>
                                                        {{$message}}
                                                    </div>
                                                    @enderror
                                                </div>
                                            </div>

                                    <!-- دکمه ذخیره -->
                                    <div class="flex justify-end gap-3 pt-4">
                                        <button type="submit"
                                                class="inline-flex items-center justify-center gap-2 h-12 bg-gradient-to-r from-primary to-blue-600 hover:from-primary/90 hover:to-blue-700 rounded-xl text-white px-8 font-semibold text-sm shadow-lg shadow-primary/30 transition-all hover:shadow-xl hover:shadow-primary/40 disabled:opacity-50 disabled:cursor-not-allowed"
                                                wire:loading.attr="disabled">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5" wire:loading.remove>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                            </svg>
                                            <span wire:loading.remove>بروزرسانی پروفایل</span>
                                            <svg wire:loading class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                            <span wire:loading>در حال بروزرسانی...</span>
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- end tabs:contents:tabOne -->
                                    <!-- tabs:contents:tabThree - رمز عبور -->
                                    <div class="space-y-5" x-show="activeTab === 'tabThree'">
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center gap-1">
                                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                                            </div>
                                            <div class="font-black text-foreground">رمز عبور</div>
                                        </div>
                                        <!-- پیام موفقیت -->
                                        @if (session()->has('password_success'))
                                            <div class="bg-success text-white px-4 py-2 rounded-full">
                                                {{ session('password_success') }}
                                            </div>
                                        @endif
                                        <!-- alert -->
                                        <div
                                            class="flex items-start gap-3 relative bg-zinc-50 dark:bg-zinc-900 border border-border rounded-xl p-5"
                                            x-show="open" x-data="{ open: true }">
                                    <span class="text-yellow-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                             fill="currentColor" class="w-5 h-5">
                                            <path fill-rule="evenodd"
                                                  d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                                  clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                                            <div class="flex flex-col items-start">
                                                <div class="font-bold text-sm text-yellow-500 mb-2">توجه :</div>
                                                <div class="font-semibold text-xs text-zinc-400">
                                                    <ul>
                                                        <li>حداقل یک حرف کوچک استفاده کنید</li>
                                                        <li>حداقل یک حرف بزرگ استفاده کنید</li>
                                                        <li>پسورد حداقل باید ۸ کاراکتر باشد</li>
                                                        <li>حداقل از یک عدد استفاده کنید</li>
                                                    </ul>
                                                </div>
                                                <div class="flex flex-wrap items-center gap-3 mt-5">
                                                    <button type="button"
                                                            class="flex items-center gap-x-1 text-zinc-400 underline-offset-1 hover:underline"
                                                            x-on:click="open = false">
                                                        <span class="font-bold text-xs">فهمیدم</span>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- فرم تغییر رمز با رمز فعلی -->
                                        <div x-show="!$wire.showForgotPassword" class="space-y-5">
                                            <form wire:submit.prevent="changePassword" class="space-y-5">
                                                <div class="grid sm:grid-cols-2 gap-5">
                                                    <div class="space-y-1">
                                                        <label for="current_password"
                                                               class="block font-medium text-xs text-muted">رمز فعلی</label>
                                                        <input type="password" dir="ltr" id="current_password"
                                                               wire:model="current_password"
                                                               class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"/>
                                                        @error('current_password')
                                                        <div class="font-medium text-xs text-red-500">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="space-y-1">
                                                        <label for="new_password"
                                                               class="block font-medium text-xs text-muted">رمز جدید</label>
                                                        <input type="password" dir="ltr" id="new_password"
                                                               wire:model="new_password"
                                                               class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"/>
                                                        @error('new_password')
                                                        <div class="font-medium text-xs text-red-500">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="space-y-1">
                                                        <label for="new_password_confirmation"
                                                               class="block font-medium text-xs text-muted">تکرار رمز
                                                            جدید</label>
                                                        <input type="password" dir="ltr" id="new_password_confirmation"
                                                               wire:model="new_password_confirmation"
                                                               class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"/>
                                                        @error('new_password_confirmation')
                                                        <div class="font-medium text-xs text-red-500">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="flex items-center justify-between gap-5">
                                                    <button type="button" wire:click="toggleForgotPassword"
                                                            class="text-primary text-sm font-medium hover:underline">
                                                        رمز عبور را فراموش کرده‌ام
                                                    </button>
                                                    <button type="submit"
                                                            class="h-11 inline-flex items-center justify-center gap-3 bg-primary rounded-full text-white px-4">
                                                <span class="font-semibold text-sm" wire:loading.remove
                                                      wire:target="changePassword">تغییر رمز</span>
                                                        <div wire:loading wire:target="changePassword">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"
                                                                 preserveAspectRatio="xMidYMid" width="30px" height="30px">
                                                                <g>
                                                                    <path stroke="none" fill="#ffffff"
                                                                          d="M19 50A31 31 0 0 0 81 50A31 34 0 0 1 19 50">
                                                                        <animateTransform values="0 50 51.5;360 50 51.5" keyTimes="0;1" repeatCount="indefinite" dur="0.8s" type="rotate" attributeName="transform"/></path>
                                                                </g>
                                                            </svg>
                                                        </div>
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                             fill="currentColor" class="w-5 h-5" wire:loading.remove
                                                             wire:target="changePassword">
                                                            <path fill-rule="evenodd"
                                                                  d="M15.312 11.424a5.5 5.5 0 0 1-9.201 2.466l-.312-.311h2.433a.75.75 0 0 0 0-1.5H3.989a.75.75 0 0 0-.75.75v4.242a.75.75 0 0 0 1.5 0v-2.43l.31.31a7 7 0 0 0 11.712-3.138.75.75 0 0 0-1.449-.39Zm1.23-3.723a.75.75 0 0 0 .219-.53V2.929a.75.75 0 0 0-1.5 0V5.36l-.31-.31A7 7 0 0 0 3.239 8.188a.75.75 0 1 0 1.448.389A5.5 5.5 0 0 1 13.89 6.11l.311.31h-2.432a.75.75 0 0 0 0 1.5h4.243a.75.75 0 0 0 .53-.219Z"
                                                                  clip-rule="evenodd"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                        <!-- فرم فراموشی رمز عبور با OTP -->
                                        <div x-show="$wire.showForgotPassword" class="space-y-5">
                                            <div class="flex items-center gap-3 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-xl">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                                     class="w-5 h-5 text-blue-500">
                                                    <path fill-rule="evenodd"
                                                          d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z"
                                                          clip-rule="evenodd"/>
                                                </svg>
                                                <span class="text-sm text-blue-700 dark:text-blue-300">کد تایید به شماره موبایل شما ارسال خواهد شد</span>
                                            </div>

                                            @if (session()->has('otp_sent'))
                                                <div class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm">
                                                    {{ session('otp_sent') }}
                                                </div>
                                            @endif
                                            @if (session()->has('otp_verified'))
                                                <div class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm">
                                                    {{ session('otp_verified') }}
                                                </div>
                                            @endif
                                            <div class="grid sm:grid-cols-2 gap-5">
                                                <!-- ارسال کد تایید -->
                                                <div class="space-y-1">
                                                    <label class="block font-medium text-xs text-muted">کد تایید</label>
                                                    <div class="flex gap-2">
                                                        <input type="text" dir="ltr" wire:model="otp_code"
                                                               placeholder="کد ۶ رقمی"
                                                               @if($otp_verified) disabled @endif
                                                               class="form-input flex-1 h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5 @if($otp_verified) opacity-50 @endif"/>
                                                        @if(!$otp_verified)
                                                            <button type="button" wire:click="sendOtp"
                                                                    class="h-11 px-4 bg-secondary border border-border rounded-xl text-white text-sm font-medium hover:bg-primary hover:text-white transition-colors">
                                                                <span wire:loading.remove wire:target="sendOtp">ارسال کد</span>
                                                                <span wire:loading wire:target="sendOtp">در حال ارسال...</span>
                                                            </button>
                                                        @endif
                                                    </div>
                                                    @error('otp_code')
                                                    <div class="font-medium text-xs text-red-500">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <!-- دکمه تایید کد -->
                                                @if(!$otp_verified)
                                                    <div class="space-y-1 flex items-end">
                                                        <button type="button" wire:click="verifyOtp"
                                                                class="h-11 px-6 bg-primary text-white rounded-xl text-sm font-medium">
                                                            <span wire:loading.remove wire:target="verifyOtp">تایید کد</span>
                                                            <span wire:loading wire:target="verifyOtp">در حال بررسی...</span>
                                                        </button>
                                                    </div>
                                                @endif
                                                <!-- رمز جدید (فقط بعد از تایید کد) -->
                                                @if($otp_verified)
                                                    <div class="space-y-1">
                                                        <label for="forgot_new_password"
                                                               class="block font-medium text-xs text-muted">رمز جدید</label>
                                                        <input type="password" dir="ltr" id="forgot_new_password"
                                                               wire:model="forgot_new_password"
                                                               class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"/>
                                                        @error('forgot_new_password')
                                                        <div class="font-medium text-xs text-red-500">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                    <div class="space-y-1">
                                                        <label for="forgot_new_password_confirmation" class="block font-medium text-xs text-muted">تکرار رمز جدید</label>
                                                        <input type="password" dir="ltr" id="forgot_new_password_confirmation"
                                                               wire:model="forgot_new_password_confirmation"
                                                               class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"/>
                                                        @error('forgot_new_password_confirmation')
                                                        <div class="font-medium text-xs text-red-500">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="flex items-center justify-between gap-5">
                                                <button type="button" wire:click="toggleForgotPassword"
                                                        class="text-muted text-sm font-medium hover:underline">
                                                    بازگشت به تغییر رمز با رمز فعلی
                                                </button>
                                                @if($otp_verified)
                                                    <button type="button" wire:click="changePasswordWithOtp"
                                                            class="h-11 inline-flex items-center justify-center gap-3 bg-primary rounded-full text-white px-4">
                                                <span class="font-semibold text-sm" wire:loading.remove
                                                      wire:target="changePasswordWithOtp">تغییر رمز</span>
                                                        <div wire:loading wire:target="changePasswordWithOtp">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"
                                                                 preserveAspectRatio="xMidYMid" width="30px" height="30px">
                                                                <g>
                                                                    <path stroke="none" fill="#ffffff"
                                                                          d="M19 50A31 31 0 0 0 81 50A31 34 0 0 1 19 50">
                                                                        <animateTransform values="0 50 51.5;360 50 51.5"
                                                                                          keyTimes="0;1"
                                                                                          repeatCount="indefinite" dur="0.8s"
                                                                                          type="rotate"
                                                                                          attributeName="transform"/>
                                                                    </path>
                                                                </g>
                                                            </svg>
                                                        </div>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <!-- end tabs:contents:tabThree -->
                                    <!-- end tabs:contents:tabTwo -->
                                </div><!-- end tabs:contents -->
                            </div><!-- end tabs container -->
                        </div>
                    </div>
                </div>
            </div>

        </div>



        @push('script')
            <!-- Tom Select JS -->
            <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
            <script>
                document.addEventListener('livewire:initialized', () => {
                    let stateSelect, citySelect;

                    // Initialize Tom Select for State
                    function initStateSelect() {
                        const stateElement = document.getElementById('state_id');
                        if (stateElement && !stateElement.tomselect) {
                            stateSelect = new TomSelect('#state_id', {
                                placeholder: 'جستجو و انتخاب استان...',
                                create: false,
                                sortField: 'text',
                                onChange: function(value) {
                                @this.set('state_id', value);
                                }
                            });
                        }
                    }

                    // Initialize Tom Select for City
                    function initCitySelect() {
                        const cityElement = document.getElementById('city_id');
                        if (cityElement && !cityElement.tomselect) {
                            citySelect = new TomSelect('#city_id', {
                                placeholder: 'جستجو و انتخاب شهر...',
                                create: false,
                                sortField: 'text',
                                onChange: function(value) {
                                @this.set('city_id', value);
                                }
                            });
                        }
                    }

                    // Initialize on page load
                    initStateSelect();
                    initCitySelect();

                    // Reinitialize city select when state changes
                    Livewire.on('state-changed', () => {
                        if (citySelect) {
                            citySelect.destroy();
                        }
                        setTimeout(() => {
                            initCitySelect();
                        }, 100);
                    });

                    // Listen for success event and reload page
                    Livewire.on('success', (message) => {
                        // Show toast notification if you have one
                        // toast.success(message);

                        // Reload page after a short delay
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    });
                });
            </script>
        @endpush
</div>
