<div x-data="cartInfoForm()" x-init="init()">
    @assets

    <link rel="stylesheet" href="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.css">
    <script src="https://unpkg.com/@majidh1/jalalidatepicker/dist/jalalidatepicker.min.js"></script>
    <style>
        /* Select2 Dark Mode Support */
        .dark .select2-container--default .select2-selection--single {
            background-color: hsl(var(--secondary));
            border: 0;
            color: hsl(var(--foreground));
        }
        .dark .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: hsl(var(--foreground));
        }
        .dark .select2-dropdown {
            background-color: hsl(var(--background));
            border-color: hsl(var(--border));
        }
        .dark .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: hsl(var(--primary));
        }
        .dark .select2-search--dropdown .select2-search__field {
            background-color: hsl(var(--secondary));
            color: hsl(var(--foreground));
            border-color: hsl(var(--border));
        }

        /* Select2 Custom Styling */
        .select2-container--default .select2-selection--single {
            height: 48px !important;
            border-radius: 0.75rem !important;
            padding: 0 1rem;
            display: flex;
            align-items: center;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 48px !important;
            padding: 0 !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 48px !important;
        }
        .select2-dropdown {
            border-radius: 0.75rem !important;
            border: 2px solid hsl(var(--border)) !important;
        }
        .select2-search--dropdown .select2-search__field {
            border-radius: 0.5rem !important;
            padding: 0.5rem 1rem !important;
        }

        /* جلوگیری از تداخل Select2 با datepicker */
        .jdp-container {
            z-index: 99999 !important;
        }
    </style>
    @endassets

    <!-- container -->
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="flex md:flex-nowrap flex-wrap items-start gap-5">
            <div class="md:w-8/12 w-full">
                <!-- section:title -->
                <div class="flex items-center justify-between gap-8 bg-gradient-to-l from-secondary to-background rounded-2xl p-5">
                    <div class="flex items-center gap-5">
                          <span class="flex items-center justify-center w-12 h-12 bg-primary text-primary-foreground rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                <path fill-rule="evenodd" d="M9.664 1.319a.75.75 0 0 1 .672 0 41.059 41.059 0 0 1 8.198 5.424.75.75 0 0 1-.254 1.285 31.372 31.372 0 0 0-7.86 3.83.75.75 0 0 1-.84 0 31.508 31.508 0 0 0-2.08-1.287V9.394c0-.244.116-.463.302-.592a35.504 35.504 0 0 1 3.305-2.033.75.75 0 0 0-.714-1.319 37 37 0 0 0-3.446 2.12A2.216 2.216 0 0 0 6 9.393v.38a31.293 31.293 0 0 0-4.28-1.746.75.75 0 0 1-.254-1.285 41.059 41.059 0 0 1 8.198-5.424ZM6 11.459a29.848 29.848 0 0 0-2.455-1.158 41.029 41.029 0 0 0-.39 3.114.75.75 0 0 0 .419.74c.528.256 1.046.53 1.554.82-.21.324-.455.63-.739.914a.75.75 0 1 0 1.06 1.06c.37-.369.69-.77.96-1.193a26.61 26.61 0 0 1 3.095 2.348.75.75 0 0 0 .992 0 26.547 26.547 0 0 1 5.93-3.95.75.75 0 0 0 .42-.739 41.053 41.053 0 0 0-.39-3.114 29.925 29.925 0 0 0-5.199 2.801 2.25 2.25 0 0 1-2.514 0c-.41-.275-.826-.541-1.25-.797a6.985 6.985 0 0 1-1.084 3.45 26.503 26.503 0 0 0-1.281-.78A5.487 5.487 0 0 0 6 12v-.54Z" clip-rule="evenodd"></path>
                            </svg>
                        </span>
                        <div class="flex flex-col space-y-2">
                            <span class="font-black xs:text-2xl text-lg text-primary">تکمیل اطلاعات کاربری</span>
                            <span class="font-semibold text-xs text-muted">لطفا اطلاعات خود را با دقت وارد کنید</span>
                        </div>
                    </div>
                </div>
                <!-- end section:title -->

                <!-- alert -->
                <div class="flex items-start gap-3 relative bg-blue-50 dark:bg-blue-950/30 border border-blue-200 dark:border-blue-800 rounded-xl p-4 mt-5" x-show="open" x-data="{ open: true }">
                    <span class="text-blue-600 dark:text-blue-400 flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"></path>
                        </svg>
                    </span>
                    <div class="flex-1">
                        <div class="font-bold text-sm text-blue-800 dark:text-blue-300 mb-1">
                            نکات مهم
                        </div>
                        <ul class="font-medium text-xs text-blue-700 dark:text-blue-400 space-y-1 list-disc list-inside">
                            <li>تمام فیلدها الزامی هستند و باید با دقت تکمیل شوند</li>
                            <li>اطلاعات وارد شده پس از تایید قابل ویرایش نیست</li>
                            <li>کد ملی باید معتبر و ۱۰ رقمی باشد</li>
                        </ul>
                    </div>
                    <button type="button" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-200 transition-colors" x-on:click="open = false">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <!-- end alert -->

                <!-- form -->
                <form wire:submit="submit(Object.fromEntries(new FormData($event.target)))" class="space-y-8 mt-6">
                    <!-- بخش ۱: اطلاعات فردی -->
                    <div class="bg-gradient-to-b from-secondary to-background  rounded-2xl p-1">
                        <div class="bg-background rounded-2xl p-6 space-y-5">
                            <div class="flex items-center gap-3 pb-3 border-b border-border">
                                <div class="flex items-center justify-center w-10 h-10 bg-primary/10 rounded-full text-primary">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                        <path d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 00-13.074.003z" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-black text-base text-foreground">اطلاعات فردی</h3>
                                    <p class="font-medium text-xs text-muted mt-1">مشخصات شخصی و تحصیلی</p>
                                </div>
                            </div>
                            <div class="grid sm:grid-cols-2 gap-5">
                                <!-- نام -->
                                <div class="space-y-2">
                                    <label for="name" class="flex items-center gap-1 font-bold text-sm text-foreground">
                                        نام
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="name"
                                        name="name"
                                        wire:model="name"
                                        class="form-input w-full h-12 !ring-2 !ring-transparent focus:!ring-primary !ring-offset-0 bg-secondary border-0 rounded-xl text-sm text-foreground px-4 transition-all"
                                        placeholder="مثال: علی"
                                    />
                                    @error('name')
                                    <p class="flex items-center gap-1 font-medium text-xs text-red-500 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                        </svg>
                                        {{$message}}
                                    </p>
                                    @enderror
                                </div>
                                <!-- نام خانوادگی -->
                                <div class="space-y-2">
                                    <label for="name_full" class="flex items-center gap-1 font-bold text-sm text-foreground">
                                        نام خانوادگی
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="name_full"
                                        name="nameFull"
                                        wire:model="nameFull"
                                        class="form-input w-full h-12 !ring-2 !ring-transparent focus:!ring-primary !ring-offset-0 bg-secondary border-0 rounded-xl text-sm text-foreground px-4 transition-all"
                                        placeholder="مثال: احمدی"
                                    />
                                    @error('nameFull')
                                    <p class="flex items-center gap-1 font-medium text-xs text-red-500 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                        </svg>
                                        {{$message}}
                                    </p>
                                    @enderror
                                </div>
                                <!-- نام پدر -->
                                <div class="space-y-2">
                                    <label for="father_name" class="flex items-center gap-1 font-bold text-sm text-foreground">
                                        نام پدر
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="father_name"
                                        dir="rtl"
                                        name="fName"
                                        wire:model="fName"
                                        class="form-input w-full h-12 !ring-2 !ring-transparent focus:!ring-primary !ring-offset-0 bg-secondary border-0 rounded-xl text-sm text-foreground px-4 transition-all"
                                        placeholder="مثال: محمد"
                                    />
                                    @error('fName')
                                    <p class="flex items-center gap-1 font-medium text-xs text-red-500 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                        </svg>
                                        {{$message}}
                                    </p>
                                    @enderror
                                </div>
                                <!-- کد ملی -->
                                <div class="space-y-2">
                                    <label for="code_mell" class="flex items-center gap-1 font-bold text-sm text-foreground">
                                        کد ملی
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="tel"
                                        id="code_mell"
                                        dir="ltr"
                                        name="codeMell"
                                        wire:model="codeMell"
                                        maxlength="10"
                                        class="form-input w-full h-12 !ring-2 !ring-transparent focus:!ring-primary !ring-offset-0 bg-secondary border-0 rounded-xl text-sm text-foreground px-4 transition-all"
                                        placeholder="مثال: 1234567890"
                                    />
                                    @error('codeMell')
                                    <p class="flex items-center gap-1 font-medium text-xs text-red-500 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                        </svg>
                                        {{$message}}
                                    </p>
                                    @enderror
                                </div>
                                <!-- تاریخ تولد -->
                                <div class="space-y-2">
                                    <label for="birth_date" class="flex items-center gap-1 font-bold text-sm text-foreground">
                                        تاریخ تولد
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative" x-ref="datePickerWrap">
                                        <input
                                            type="text"
                                            id="birth_date"
                                            name="birth_date"
                                            data-jdp
                                            autocomplete="off"
                                            dir="ltr"
                                            x-ref="birthDateInput"
                                            readonly
                                            class="form-input w-full h-12 !ring-2 !ring-transparent focus:!ring-primary !ring-offset-0 bg-secondary border-0 rounded-xl text-sm text-foreground px-4 pr-12 transition-all"
                                            placeholder="1380/01/01"
                                        />
                                        <!-- hidden input حذف کن -->
                                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-muted pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                <path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </div>
                                    @error('birth_date')
                                    <p class="flex items-center gap-1 font-medium text-xs text-red-500 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                        </svg>
                                        {{$message}}
                                    </p>
                                    @enderror
                                </div>
                                <!-- محل تولد -->
                                <div class="space-y-2">
                                    <label for="place_of_birth" class="flex items-center gap-1 font-bold text-sm text-foreground">
                                        محل تولد
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        id="place_of_birth"
                                        dir="rtl"
                                        name="placeOfBirth"
                                        wire:model="placeOfBirth"
                                        class="form-input w-full h-12 !ring-2 !ring-transparent focus:!ring-primary !ring-offset-0 bg-secondary border-0 rounded-xl text-sm text-foreground px-4 transition-all"
                                        placeholder="مثال: تهران"
                                    />
                                    @error('placeOfBirth')
                                    <p class="flex items-center gap-1 font-medium text-xs text-red-500 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                        </svg>
                                        {{$message}}
                                    </p>
                                    @enderror
                                </div>
                                <!-- پایه -->
                                <div class="space-y-2">
                                    <label for="grade" class="flex items-center gap-1 font-bold text-sm text-foreground">
                                        پایه تحصیلی
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <select
                                        id="grade"
                                        name="grade"
                                        wire:model.live="grade"
                                        x-model="grade"
                                        class="form-select w-full h-12 !ring-2 !ring-transparent focus:!ring-primary !ring-offset-0 bg-secondary border-0 rounded-xl text-sm text-foreground px-4 transition-all"
                                    >
                                        <option value="">انتخاب کنید</option>
                                        <option value="9">نهم</option>
                                        <option value="10">دهم</option>
                                        <option value="11">یازدهم</option>
                                        <option value="12">دوازدهم</option>
                                    </select>
                                    @error('grade')
                                    <p class="flex items-center gap-1 font-medium text-xs text-red-500 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                        </svg>
                                        {{$message}}
                                    </p>
                                    @enderror
                                </div>
                                <!-- رشته -->
                                <div class="space-y-2">
                                    <label for="field" class="flex items-center gap-1 font-bold text-sm text-foreground">
                                        رشته تحصیلی
                                        <span class="text-red-500" x-show="grade !== '9'">*</span>
                                    </label>
                                    <select
                                        id="field"
                                        name="field"
                                        wire:model="field"
                                        :disabled="grade === '9'"
                                        class="form-select w-full h-12 !ring-2 !ring-transparent focus:!ring-primary !ring-offset-0 bg-secondary border-0 rounded-xl text-sm text-foreground px-4 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        <option value="" x-text="grade === '9' ? 'پایه نهم رشته ندارد' : 'انتخاب کنید'"></option>
                                        <template x-if="grade !== '9'">
                                            <template x-for="opt in [{v:'math',t:'ریاضی'},{v:'experimental',t:'تجربی'},{v:'human',t:'انسانی'}]">
                                                <option :value="opt.v" x-text="opt.t"></option>
                                            </template>
                                        </template>
                                    </select>
                                    @error('field')
                                    <p class="flex items-center gap-1 font-medium text-xs text-red-500 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                        </svg>
                                        {{$message}}
                                    </p>
                                    @enderror
                                    <p class="flex items-center gap-1 font-medium text-xs text-yellow-600 dark:text-yellow-400" x-show="grade === '9'">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                                        </svg>
                                        دانش‌آموزان پایه نهم رشته تحصیلی ندارند
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- پایان بخش ۱ -->

                    <!-- بخش ۲: اطلاعات محل سکونت -->
                    <div class="bg-gradient-to-b from-secondary to-background rounded-2xl p-1">
                        <div class="bg-background rounded-2xl p-6 space-y-5">
                            <div class="flex items-center gap-3 pb-3 border-b border-border">
                                <div class="flex items-center justify-center w-10 h-10 bg-green-500/10 rounded-full text-green-600 dark:text-green-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                        <path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-black text-base text-foreground">اطلاعات محل سکونت</h3>
                                    <p class="font-medium text-xs text-muted mt-1">آدرس و موقعیت مکانی</p>
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-2 gap-5">
                                <!-- استان -->
                                <div class="space-y-2">
                                    <label for="stateId" class="flex items-center gap-1 font-bold text-sm text-foreground">
                                        استان (محل سکونت)
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <!-- استان -->
                                    <select
                                        id="stateId"
                                        name="province"
                                        wire:model="province"
                                        wire:change="getCity($event.target.value)"
                                        class="form-select w-full h-12 !ring-2 !ring-transparent focus:!ring-primary !ring-offset-0 bg-secondary border-0 rounded-xl text-sm text-foreground px-4 transition-all"
                                    >
                                        <option value="">انتخاب کنید</option>
                                        @foreach($provinces as $item)
                                            <option value="{{$item->id}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('province')
                                    <p class="flex items-center gap-1 font-medium text-xs text-red-500 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                        </svg>
                                        {{$message}}
                                    </p>
                                    @enderror
                                </div>

                                <!-- شهر -->
                                <div class="space-y-2">
                                    <label for="cityId" class="flex items-center gap-1 font-bold text-sm text-foreground">
                                        شهر (محل سکونت)
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <select
                                            id="cityId"
                                            name="city"
                                            wire:model="city"
                                            :disabled="{{count($cities)}} === 0"
                                            class="form-select w-full h-12 !ring-2 !ring-transparent focus:!ring-primary !ring-offset-0 bg-secondary border-0 rounded-xl text-sm text-foreground px-4 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                                        >
                                            <option value="">
                                                @if(count($cities) === 0)
                                                    ابتدا استان را انتخاب کنید
                                                @else
                                                    انتخاب کنید
                                                @endif
                                            </option>
                                            @foreach($cities as $item)
                                                <option value="{{$item->id}}" {{$city == $item->id ? 'selected' : ''}}>
                                                    {{$item->name}}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div wire:loading wire:target="getCity" class="absolute left-4 top-1/2 -translate-y-1/2">
                                            <svg class="animate-spin h-5 w-5 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    @error('city')
                                    <p class="flex items-center gap-1 font-medium text-xs text-red-500 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                        </svg>
                                        {{$message}}
                                    </p>
                                    @enderror
                                </div>
                            </div>

                            <!-- آدرس -->
                            <div class="space-y-2">
                                <label for="address" class="flex items-center gap-1 font-bold text-sm text-foreground">
                                    آدرس محل سکونت
                                    <span class="text-red-500">*</span>
                                </label>
                                <textarea
                                    rows="4"
                                    id="address"
                                    name="address"
                                    wire:model="address"
                                    class="form-textarea w-full !ring-2 !ring-transparent focus:!ring-primary !ring-offset-0 bg-secondary border-0 rounded-xl text-sm text-foreground p-4 transition-all resize-none"
                                    placeholder="آدرس کامل پستی خود را وارد کنید..."
                                ></textarea>
                                @error('address')
                                <p class="flex items-center gap-1 font-medium text-xs text-red-500 mt-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                    </svg>
                                    {{$message}}
                                </p>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- پایان بخش ۲ -->

                    <!-- بخش ۳: راه‌های ارتباطی -->
                    <div class="bg-gradient-to-b from-secondary to-background rounded-2xl p-1">
                        <div class="bg-background rounded-2xl p-6 space-y-5">
                            <div class="flex items-center gap-3 pb-3 border-b border-border">
                                <div class="flex items-center justify-center w-10 h-10 bg-purple-500/10 rounded-full text-purple-600 dark:text-purple-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                        <path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 006.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 011.767-1.052l3.223.716A1.5 1.5 0 0118 15.352V16.5a1.5 1.5 0 01-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 012.43 8.326 13.019 13.019 0 012 5V3.5z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-black text-base text-foreground">راه‌های ارتباطی</h3>
                                    <p class="font-medium text-xs text-muted mt-1">شماره تماس والدین</p>
                                </div>
                            </div>

                            <div class="grid sm:grid-cols-2 gap-5">
                                <!-- موبایل پدر -->
                                <div class="space-y-2">
                                    <label for="father_mobile" class="flex items-center gap-1 font-bold text-sm text-foreground">
                                        موبایل پدر
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input
                                            type="tel"
                                            dir="ltr"
                                            id="father_mobile"
                                            name="fMobile"
                                            wire:model="fMobile"
                                            maxlength="11"
                                            class="form-input w-full h-12 !ring-2 !ring-transparent focus:!ring-primary !ring-offset-0 bg-secondary border-0 rounded-xl text-sm text-foreground px-4 pr-12 transition-all"
                                            placeholder="09123456789"
                                        />
                                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-muted pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                <path d="M10.5 18a7.5 7.5 0 10-7.5-7.5h7.5V18z" />
                                                <path d="M13.5 10H21a7.5 7.5 0 00-7.5-7.5v7.5z" />
                                            </svg>
                                        </span>
                                    </div>
                                    @error('fMobile')
                                    <p class="flex items-center gap-1 font-medium text-xs text-red-500 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                        </svg>
                                        {{$message}}
                                    </p>
                                    @enderror
                                </div>

                                <!-- موبایل مادر -->
                                <div class="space-y-2">
                                    <label for="mother_mobile" class="flex items-center gap-1 font-bold text-sm text-foreground">
                                        موبایل مادر
                                        <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input
                                            type="tel"
                                            dir="ltr"
                                            id="mother_mobile"
                                            name="mMobile"
                                            wire:model="mMobile"
                                            maxlength="11"
                                            class="form-input w-full h-12 !ring-2 !ring-transparent focus:!ring-primary !ring-offset-0 bg-secondary border-0 rounded-xl text-sm text-foreground px-4 pr-12 transition-all"
                                            placeholder="09123456789"
                                        />
                                        <span class="absolute right-4 top-1/2 -translate-y-1/2 text-muted pointer-events-none">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                                <path d="M10.5 18a7.5 7.5 0 10-7.5-7.5h7.5V18z" />
                                                <path d="M13.5 10H21a7.5 7.5 0 00-7.5-7.5v7.5z" />
                                            </svg>
                                        </span>
                                    </div>
                                    @error('mMobile')
                                    <p class="flex items-center gap-1 font-medium text-xs text-red-500 mt-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-5a.75.75 0 01.75.75v4.5a.75.75 0 01-1.5 0v-4.5A.75.75 0 0110 5zm0 10a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                        </svg>
                                        {{$message}}
                                    </p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- پایان بخش ۳ -->
                    <!-- دکمه ارسال -->
                    <div class="pt-2">
                        <button
                            type="submit"
                            class="w-full h-14 inline-flex items-center justify-center gap-2 bg-primary rounded-2xl text-primary-foreground font-bold text-base transition-all hover:opacity-90 hover:scale-[1.02] active:scale-[0.98] shadow-lg shadow-primary/25 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:scale-100"
                            wire:loading.attr="disabled"
                        >
                            <span wire:loading.remove wire:target="submit">تکمیل فرایند خرید</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6" wire:loading.remove wire:target="submit">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                            </svg>
                            <div wire:loading wire:target="submit" class="flex items-center gap-2">
                                <svg class="animate-spin h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>در حال پردازش...</span>
                            </div>
                        </button>
                    </div>
                </form>
                <!-- end form -->
            </div>

            <!-- cart:detail -->
            <div class="md:w-4/12 w-full md:sticky md:top-24">
                <div class="space-y-5">
                    <div class="bg-gradient-to-b from-secondary to-background rounded-2xl px-5 pb-5">
                        <div class="bg-background rounded-b-3xl space-y-2 p-5 mb-5">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-1">
                                    <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                    <div class="w-2 h-2 bg-foreground rounded-full"></div>
                                </div>
                                <div class="font-black text-foreground">اطلاعات پرداخت</div>
                            </div>
                        </div>
                        <div class="space-y-5">
                            <div class="flex flex-col space-y-3">
                                <div class="flex items-center justify-between gap-3 p-3 bg-secondary/50 rounded-xl">
                                    <div class="font-bold text-xs text-foreground">جمع کل</div>
                                    <div class="flex items-center gap-1">
                                        <span class="font-black text-base text-foreground">{{number_format($checkout['totalOriginalPrice'])}}</span>
                                        <span class="text-xs text-muted">تومان</span>
                                    </div>
                                </div>
                                @if(isset($checkout['discountAmount']) && $checkout['discountAmount'] > 0)
                                    <div class="flex items-center justify-between gap-3 p-3 bg-green-50 dark:bg-green-950/30 rounded-xl">
                                        <div class="font-bold text-xs text-green-600 dark:text-green-400">تخفیف</div>
                                        <div class="flex items-center gap-1">
                                            <span class="font-black text-base text-green-600 dark:text-green-400">- {{number_format($checkout['discountAmount'])}}</span>
                                            <span class="text-xs text-green-600 dark:text-green-400">تومان</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="h-px bg-border"></div>
                            <div class="flex items-center justify-between gap-3 p-4 bg-primary/10 rounded-xl">
                                <div class="font-bold text-sm text-primary">مبلغ قابل پرداخت</div>
                                <div class="flex items-center gap-1">
                                    <span class="font-black text-2xl text-primary">{{number_format($checkout['totalAmount'])}}</span>
                                    <span class="text-xs text-primary">تومان</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end cart:detail -->
        </div>
    </div>

    @script
    <script>
        Alpine.data('cartInfoForm', () => ({
            grade: @entangle('grade'),

            init() {
                this.$nextTick(() => {
                    this.initDatepicker();
                });

                document.addEventListener('livewire:navigated', () => {
                    this.$nextTick(() => {
                        this.initDatepicker();
                    });
                });
            },

            initDatepicker() {
                if (typeof jalaliDatepicker === 'undefined') return;

                const input = this.$refs.birthDateInput;
                if (!input || input.dataset.jdpBound) return;

                input.dataset.jdpBound = "1";

                if (!window.__JDP_STARTED__) {
                    window.__JDP_STARTED__ = true;
                    jalaliDatepicker.startWatch({ time: false });
                }

                input.addEventListener('change', () => {
                    const val = input.value?.trim();
                    if (val) {
                    @this.set('birth_date', val);
                    }
                });
            },
        }));
    </script>
    @endscript
</div>
