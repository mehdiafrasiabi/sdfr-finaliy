<div class="max-w-7xl space-y-14 px-4 mx-auto">
    <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
        <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">

            <!-- end user:info -->

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
                        <div class="font-black text-foreground">گزارش های من</div>
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
                                            class="flex items-center gap-x-2 relative rounded-full py-2 px-4"
                                            x-bind:class="activeTab === 'tabOne' ? 'text-foreground bg-background' : 'text-muted'"
                                            x-on:click="activeTab = 'tabOne'">
                                        <!-- active icon -->
                                        <span x-show="activeTab === 'tabOne'">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                             fill="currentColor" class="w-5 h-5">
                                                            <path
                                                                d="M11.7 2.805a.75.75 0 0 1 .6 0A60.65 60.65 0 0 1 22.83 8.72a.75.75 0 0 1-.231 1.337 49.948 49.948 0 0 0-9.902 3.912l-.003.002c-.114.06-.227.119-.34.18a.75.75 0 0 1-.707 0A50.88 50.88 0 0 0 7.5 12.173v-.224c0-.131.067-.248.172-.311a54.615 54.615 0 0 1 4.653-2.52.75.75 0 0 0-.65-1.352 56.123 56.123 0 0 0-4.78 2.589 1.858 1.858 0 0 0-.859 1.228 49.803 49.803 0 0 0-4.634-1.527.75.75 0 0 1-.231-1.337A60.653 60.653 0 0 1 11.7 2.805Z">
                                                            </path>
                                                            <path
                                                                d="M13.06 15.473a48.45 48.45 0 0 1 7.666-3.282c.134 1.414.22 2.843.255 4.284a.75.75 0 0 1-.46.711 47.87 47.87 0 0 0-8.105 4.342.75.75 0 0 1-.832 0 47.87 47.87 0 0 0-8.104-4.342.75.75 0 0 1-.461-.71c.035-1.442.121-2.87.255-4.286.921.304 1.83.634 2.726.99v1.27a1.5 1.5 0 0 0-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.66a6.727 6.727 0 0 0 .551-1.607 1.5 1.5 0 0 0 .14-2.67v-.645a48.549 48.549 0 0 1 3.44 1.667 2.25 2.25 0 0 0 2.12 0Z">
                                                            </path>
                                                            <path
                                                                d="M4.462 19.462c.42-.419.753-.89 1-1.395.453.214.902.435 1.347.662a6.742 6.742 0 0 1-1.286 1.794.75.75 0 0 1-1.06-1.06Z">
                                                            </path>
                                                        </svg>
                                                    </span><!-- end active icon -->

                                        <!-- inactive icon -->
                                        <span x-show="activeTab !== 'tabOne'">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                             viewBox="0 0 24 24" stroke-width="1.5"
                                                             stroke="currentColor"
                                                             class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5">
                                                            </path>
                                                        </svg>
                                                    </span><!-- end inactive icon -->

                                        <span class="font-semibold text-sm">ارسال گزارش</span>
                                    </button>
                                </li><!-- end tabs:list:item -->

                                <!-- tabs:list:item -->
                                <li>
                                    <button type="button"
                                            class="flex items-center gap-x-2 relative rounded-full py-2 px-4"
                                            x-bind:class="activeTab === 'tabTwo' ? 'text-foreground bg-background' : 'text-muted'"
                                            x-on:click="activeTab = 'tabTwo'">
                                        <!-- active icon -->
                                        <span x-show="activeTab === 'tabTwo'">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                             fill="currentColor" class="w-5 h-5">
                                                            <path fill-rule="evenodd"
                                                                  d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625ZM7.5 15a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 7.5 15Zm.75 2.25a.75.75 0 0 0 0 1.5H12a.75.75 0 0 0 0-1.5H8.25Z"
                                                                  clip-rule="evenodd"></path>
                                                            <path
                                                                d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z">
                                                            </path>
                                                        </svg>
                                                    </span><!-- end active icon -->

                                        <!-- inactive icon -->
                                        <span x-show="activeTab !== 'tabTwo'">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                             viewBox="0 0 24 24" stroke-width="1.5"
                                                             stroke="currentColor"
                                                             class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z">
                                                            </path>
                                                        </svg>
                                                    </span><!-- end inactive icon -->

                                        <span class="font-semibold text-sm">گزارش های ارسال شده</span>
                                    </button>
                                </li><!-- end tabs:list:item -->
                            </ul>
                            <!-- end tabs:list -->
                        </div>
                        <!-- end tabs:list-container -->

                        <!-- tabs:contents -->
                        <div>
                            <!-- tabs:contents:tabOne -->
                            <div class="space-y-5" x-show="activeTab === 'tabOne'">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-1">
                                        <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                        <div class="w-2 h-2 bg-foreground rounded-full"></div>
                                    </div>
                                    <div class="font-black text-foreground">گزارش</div>
                                </div>

                                <div
                                    class="flex items-start gap-3 relative bg-zinc-50 dark:bg-zinc-900 border border-border rounded-xl p-5"
                                    x-show="open" x-data="{ open: true }">
                                    <!-- alert:icon -->
                                    <span class="text-yellow-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                         fill="currentColor" class="w-5 h-5">
                                                        <path fill-rule="evenodd"
                                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                                              clip-rule="evenodd"></path>
                                                    </svg>
                                                </span><!-- alert:icon -->

                                    <!-- alert:content -->
                                    <div class="flex flex-col items-start">
                                        <!-- alert:title -->
                                        <div class="font-bold text-sm text-yellow-500 mb-2">
                                            توجه :&zwnj;
                                        </div><!-- end alert:title -->

                                        <!-- alert:desc -->
                                        <div class="font-semibold text-xs text-zinc-400">
                                            <ul>
                                                <li>1- لطفا اطلاعات خود را با دقت وارد کنید.</li>
                                                <li>2- تمام فیلد های ستاره دار
                                                    <sup class="text-red-500 text-xs font-bold">*</sup>
                                                    باید پر شود.</li>
                                                <li>3- سعی کنید گزارش را هرشب ارسال کنید تا امتیاز بیشتری دریافت کنبد.</li>
                                                <li>4- چناچه از گزارش عقب ماندید در تاریخ گزارش تاریخ مربوطه را وارد کنید.</li>
                                                <li>گزارش های شما پس از ارسال، توسط پشتیبان شما مشاهده خواهد شد که در قسمت "گزارش های ارسال شده" میتوانید وضعیت گزارش خود را مشاهده کنید</li>
                                            </ul>
                                        </div><!-- end alert:desc -->

                                        <!-- alert:actions -->
                                        <div class="flex flex-wrap items-center gap-3 mt-5">
                                            <button type="button"
                                                    class="flex items-center gap-x-1 text-zinc-400 underline-offset-1 hover:underline"
                                                    x-on:click="open = false">
                                                <span class="font-bold text-xs">فهمیدم</span>
                                            </button>
                                        </div><!-- end alert:actions -->
                                    </div><!-- end alert:content -->
                                </div>
                                <form wire:submit.prevent="submit"
                                      class="space-y-5">
                                    <div class="grid sm:grid-cols-2 gap-5">
                                        <div class="space-y-1">
                                            <label for="required_parts" class="font-medium text-xs text-muted">

                                                تعداد پارت موظفی امروز :</label>
                                            <sup class="text-red-500">*</sup>
                                            <select type="text" id="required_parts" name="required_parts" wire:model="required_parts"
                                                   class="form-select w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                                                @for($i=0;$i<=10;$i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                            @error('required_parts')
                                            <div class="font-medium text-xs text-muted text-red-500">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="space-y-1">
                                            <label for="done_parts"
                                                   class="font-medium text-xs text-muted">تعداد پارت انجام شده :</label>
                                            <sup class="text-red-500">*</sup>
                                            <select type="text" id="done_parts" name="done_parts" wire:model="done_parts"
                                                    class="form-select w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                                                @for($i=0;$i<=10;$i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                            @error('done_parts')
                                            <div class="font-medium text-xs text-muted text-red-500">
                                                {{$message}}
                                            </div>
                                            @enderror

                                        </div>
                                        <div class="space-y-1">
                                            <label for="required_tests"
                                                   class="font-medium text-xs text-muted">تعداد کل تست‌های موظفی :</label>
                                            <input type="tel" id="required_tests" dir="ltr" name="required_tests" min="0"
                                                   maxlength="4"
                                                   wire:model="required_tests"
                                                   class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"/>
                                            @error('required_tests')
                                            <div class="font-medium text-xs text-muted text-red-500">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="space-y-1">
                                            <label for="done_tests"
                                                   class="font-medium text-xs text-muted">تعداد تست های زده شده :</label>
                                            <input type="tel" id="done_tests" dir="ltr" name="done_tests" min="0"
                                                   maxlength="4"
                                                   wire:model="done_tests"
                                                   class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"/>
                                            @error('done_tests')
                                            <div class="font-medium text-xs text-muted text-red-500">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="space-y-1">
                                            <label for="phone_study_hours"
                                                   class="font-medium text-xs text-muted">ساعات درگیر با گوشی (درسی) :</label>
                                            <sup class="text-red-500">*</sup>
                                            <input type="tel" id="phone_study_hours" dir="ltr" name="phone_study_hours" min="0" max="24"
                                                   maxlength="2"
                                                   wire:model="phone_study_hours"
                                                   class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"/>
                                            @error('phone_study_hours')
                                            <div class="font-medium text-xs text-muted text-red-500">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="space-y-1">
                                            <label for="phone_nonstudy_hours"
                                                   class="font-medium text-xs text-muted">ساعات درگیر با گوشی (غیر درسی) :</label>
                                            <sup class="text-red-500">*</sup>
                                            <input type="tel" id="phone_nonstudy_hours" dir="ltr" name="phone_nonstudy_hours" min="0" max="24"
                                                   maxlength="2"
                                                   wire:model="phone_nonstudy_hours"
                                                   class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"/>
                                            @error('phone_nonstudy_hours')
                                            <div class="font-medium text-xs text-muted text-red-500">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="grid sm:grid-cols-4 gap-5">
                                        <div class="space-y-1">
                                            <label for="name" class="font-medium text-xs text-muted">
                                                توضیحات
                                            </label>
                                            <textarea type="text" rows="5" wire:model="description"
                                                      class="form-textarea w-full !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"></textarea>

                                            @error('description')
                                            <div class="font-medium text-xs text-muted text-red-500">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="grid sm:grid-cols-2 gap-5">
                                        <div class="space-y-1">
                                            <label for="complacent" class="block font-semibold text-xs text-foreground">حس من نسبت به گزارش امروز:
                                                <sup class="text-red-500">*</sup>

                                            </label>

                                            <div class="flex items-center gap-x-8">
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="radio" name="complacent" class="form-radio !ring-0 !ring-offset-0 bg-border border-0" value="1" wire:model="complacent">
                                                    <span class="text-sm text-muted">  راضی ام</span>
                                                </label>
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="radio" name="complacent" class="form-radio !ring-0 !ring-offset-0 bg-border border-0" value="0" wire:model="complacent">
                                                    <span class="text-sm text-muted">راضی نیستم </span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center gap-1">
                                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                                        </div>
                                        <div class="font-black text-foreground">آپلود فایل(اختیاری)</div>
                                    </div>
                                    <div class="grid sm:grid-cols-2 gap-5">
                                        <div class="space-y-1">
                                            <label
                                                class="inline-flex items-center gap-x-1 border rounded-full text-muted py-2.5 px-5 cursor-pointer hover:text-foreground"
                                                for="customFile" x-data="{ files: null }">
                                                <input type="file" class="sr-only" id="customFile"
                                                       wire:model="report_file" accept="image/*"
                                                       x-on:change="files = Object.values($event.target.files)">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"
                                                     fill="currentColor" class="size-4">
                                                    <path fill-rule="evenodd"
                                                          d="M11.914 4.086a2 2 0 0 0-2.828 0l-5 5a2 2 0 1 0 2.828 2.828l.556-.555a.75.75 0 0 1 1.06 1.06l-.555.556a3.5 3.5 0 0 1-4.95-4.95l5-5a3.5 3.5 0 0 1 4.95 4.95l-1.972 1.972a2.125 2.125 0 0 1-3.006-3.005L9.97 4.97a.75.75 0 1 1 1.06 1.06L9.058 8.003a.625.625 0 0 0 .884.883l1.972-1.972a2 2 0 0 0 0-2.828Z"
                                                          clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="font-semibold text-xs"
                                                      x-text="files ? files.map(file => file.name).join(', ') : 'بارگذاری ..'"></span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="flex justify-end gap-5">
                                        <button type="submit"
                                                class="h-11 inline-flex items-center justify-center bg-primary rounded-full text-white px-8 mr-auto">
                                            <span class="font-semibold text-sm" wire:loading.remove>ثبت</span>
                                            <div wire:loading>
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     xmlns:xlink="http://www.w3.org/1999/xlink"
                                                     viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"
                                                     width="40px" height="40px"
                                                     style="shape-rendering: auto; display: block; background: transparent;">
                                                    <g>
                                                        <path stroke="none" fill="#ffffff"
                                                              d="M19 50A31 31 0 0 0 81 50A31 34 0 0 1 19 50">
                                                            <animateTransform values="0 50 51.5;360 50 51.5"
                                                                              keyTimes="0;1"
                                                                              repeatCount="indefinite"
                                                                              dur="0.8130081300813008s"
                                                                              type="rotate"
                                                                              attributeName="transform"/>
                                                        </path>
                                                        <g/>
                                                    </g>
                                                </svg>
                                            </div>
                                        </button>
                                    </div>
                                </form>

                            </div>
                            <!-- end tabs:contents:tabOne -->

                            <!-- tabs:contents:tabTwo -->
                            <div x-show="activeTab === 'tabTwo'">
                                <div class="relative   @if($reports->isNotEmpty()) container overflow-x-auto @endif">
                                    <table wire:poll.visible class="w-full text-sm text-right">
                                        @if($reports->isNotEmpty())
                                            <thead
                                                class="text-xs text-muted uppercase bg-background border-b border-border">
                                            <tr>
                                                <th class="whitespace-nowrap p-5">ردیف</th>
                                                <th class="whitespace-nowrap p-5">پارت های موظفی</th>
                                                <th class="whitespace-nowrap p-5">پارت های انجام شده</th>
                                                <th class="whitespace-nowrap p-5">دروسی که انجام نشده</th>
                                                <th class="whitespace-nowrap p-5">تست های موظفی</th>
                                                <th class="whitespace-nowrap p-5">تست های زده شده</th>
                                                <th class="whitespace-nowrap p-5">ساعات درگیر با گوشی (درسی) </th>
                                                <th class="whitespace-nowrap p-5">ساعات درگیر با گوشی (غیر درسی)</th>
                                                <th class="whitespace-nowrap p-5">توضیحات</th>
                                                <th class="whitespace-nowrap p-5">رضایت</th>>
                                                <th class="whitespace-nowrap p-5">نظر مشاور</th>
                                                <th class="whitespace-nowrap p-5">وضعیت</th>
                                                <th class="whitespace-nowrap p-5">فایل</th>
                                                <th class="whitespace-nowrap p-5">تاریخ ثبت</th>
                                            </tr>
                                            </thead>


                                            <tbody>
                                            @foreach($reports as $report)
                                                <tr class="odd:bg-secondary even:bg-background whitespace-nowrap">
                                                    <td class="p-5">
                                                        <div
                                                            class="font-black text-xs text-foreground">{{$loop->iteration + $reports->firstItem() - 1}}</div>
                                                    </td>
                                                    <td class="p-5">
                                                        <div class="flex items-center gap-2">
                                                     <span class="  text-foreground ">{{ $report->required_parts ?? '---' }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="p-5">
                                                        <div class="flex items-center gap-2">
                                                     <span class=" text-xs text-white ">{{ $report->done_parts ?? '---' }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="p-5">
                                                        <div class="flex items-center gap-2">
                                                     <span class=" text-xs text-white ">{{ $report->missed_subjects ?? '---' }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="p-5">
                                                        <div class="flex items-center gap-2">
                                                     <span class=" text-xs text-white ">{{ $report->required_tests ?? '---' }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="p-5">
                                                        <div class="flex items-center gap-2">
                                                     <span class=" text-sm text-white ">{{ $report->done_tests ?? '---' }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="p-5">
                                                        <div class="flex items-center gap-2">
                                                     <span class=" text-sm text-white ">{{ $report->phone_study_hours ?? '---' }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="p-5">
                                                        <div class="flex items-center gap-2">
                                                     <span class=" text-sm text-white ">{{ $report->phone_nonstudy_hours ?? '---' }}</span>
                                                        </div>
                                                    </td>
                                                    <td class="p-5">
                                                        <div class="flex items-center gap-2">
                                                     <span class=" text-sm text-foreground ">
                                                         {{ \Illuminate\Support\Str::limit($report->description, 50) }}
                                                     </span>
                                                        </div>
                                                    </td>

                                                    <td class="p-5">
                                                        <div class=" text-muted whitespace-nowrap">
                                                             <span class="text-xs text-foreground ">
                                                                 @if($report->complacent == 1)
                                                                     <span  class="font-bold text-success text-xs"> راضی ام</span>
                                                                 @elseif($report->complacent ==0)
                                                                    <span class="font-bold text-yellow-500 text-xs"> تلاشمو قرار بیشتر کنم و بترکونم</span>
                                                                 @endif
                                                             </span>
                                                        </div>
                                                    </td>
                                                    <td class="p-5">
                                                        <div class="space-y-2 text-xs text-muted">
                                                            @if($report->advisor_comment)
                                                                <button type="button"
                                                                        wire:click="openReplyModal({{ $report->id }})"
                                                                        class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary text-white hover:bg-primary/80 transition-all">
                                                                    مشاهده نظر و پاسخ
                                                                </button>
                                                            @else
                                                                <span>منتظر نظر مشاور</span>
                                                            @endif
                                                        </div>
                                                    </td>


                                                    <td class="p-5">
                                                        <div class="text-xs text-muted whitespace-nowrap">
                                                            @if($report->status === 'completed')
                                                                <div class="flex-shrink-0 rounded-full bg-green-500/20 p-1">
                                                                    <div class="h-1.5 w-1.5 rounded-full bg-green-500"></div>
                                                                </div>
                                                                <span class="font-bold text-green-500">تایید شده</span>
                                                            @elseif($report->status === 'pending')
                                                                <div class="flex-shrink-0 rounded-full bg-yellow-500/20 p-1">
                                                                    <div class="h-1.5 w-1.5 rounded-full bg-yellow-500"></div>
                                                                </div>
                                                                <span class="font-bold text-yellow-500">در انتظار</span>
                                                            @else
                                                                <div class="flex-shrink-0 rounded-full bg-red-500-500/20 p-1">
                                                                    <div class="h-1.5 w-1.5 rounded-full bg-red-500"></div>
                                                                </div>
                                                                <span class="font-bold text-red-500">رد شده</span>
                                                            @endif

                                                        </div>
                                                    </td>
                                                    <td class="p-5">
                                                        <div class="text-xs text-muted whitespace-nowrap">
                                                            @if(isset($report->report_file))
                                                                <a href="{{asset('students/reportsDaily/'.auth()->id()).'/'.$report->report_file}}">مشاهده</a>
                                                            @else
                                                                 وجود ندارد
                                                            @endif
                                                        </div>
                                                    </td>
                                                    <td class="p-5">
                                                        <div class="text-xs text-muted whitespace-nowrap">
                                                            {{jalali($report->created_at)->format('%d %B %Y | H:i')}}
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>

                                        @else
                                            <div class="flex flex-col items-center justify-center space-y-12">
                                                <img src="/client/assets/images/theme/empty.svg" class="w-full max-w-xs opacity-35"
                                                     alt="..."/>
                                                <div class="text-center space-y-3">
                                                    <h2 class="font-bold text-xl text-foreground">
                                                        برنامه مشاوره ای برای شما وجود ندارد.
                                                    </h2>
                                                </div>
                                            </div>
                                        @endif

                                    </table>

                                </div>
                                <div class="p-5 text-xs text-muted whitespace-nowrap text-white">
                                    {{$reports->links('layouts.client.pagination')}}
                                </div>
                            </div>
                            <!-- end tabs:contents:tabTwo -->
                        </div><!-- end tabs:contents -->
                    </div>
                    <!-- end tabs container -->
                </div>
            </div>
        </div>
    </div>
    @if($replyModalOpen)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm" wire:click.self="closeReplyModal">
            <div class="w-full max-w-xl mx-4 bg-background border border-border rounded-2xl shadow-2xl" wire:keydown.escape.window="closeReplyModal">
                <div class="flex items-center justify-between px-6 py-4 border-b border-border">
                    <div>
                        <h3 class="text-base font-bold text-foreground">پاسخ به نظر مشاور</h3>
                        <p class="text-xs text-muted mt-1">امکان ثبت تنها یک پاسخ برای هر گزارش وجود دارد.</p>
                    </div>
                    <button type="button" wire:click="closeReplyModal" class="text-muted hover:text-foreground transition-all">
                        <i class="material-symbols-outlined !text-[22px]">✕</i>
                    </button>
                </div>
                <div class="px-6 py-5 space-y-4">
                    <div class="space-y-2">
                        <div class="text-xs text-muted text-primary font-bold">نظر مشاور</div>
                        <p class="text-sm leading-6 text-foreground bg-secondary border border-border rounded-xl p-4">{{ $advisorCommentPreview }}</p>
                    </div>
                    @if($studentReplyPreview)
                        <br>
                        <div class="space-y-2">
                            <div class="text-xs text-muted text-success font-bold">پاسخ شما</div>
                            <p class="text-sm leading-6 text-foreground bg-secondary border border-border rounded-xl p-4">{{ $studentReplyPreview }}</p>
                        </div>
                    @else
                        <div class="space-y-2">
                            <label for="student_reply_input" class="text-sm font-medium text-foreground">پاسخ شما</label>
                            <textarea id="student_reply_input" rows="4" wire:model.defer="studentReplyInput" class="w-full rounded-xl border border-border bg-secondary text-sm text-foreground px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary/40"></textarea>
                            @error('studentReplyInput')
                            <div class="text-xs font-medium text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                </div>
                <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-border bg-secondary rounded-b-2xl">

                    @if($studentReplyPreview)
                        <button type="button" wire:click="closeReplyModal" class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-primary text-white hover:bg-primary/80 transition-all">بستن</button>

                    @else
                        <button type="button" wire:click="saveStudentReply" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-5 py-2 rounded-full bg-primary text-white hover:bg-primary/80 transition-all">
                            <span wire:loading.remove wire:target="saveStudentReply">ثبت پاسخ</span>
                            <span wire:loading wire:target="saveStudentReply" class="flex items-center gap-2">
                        <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>
                        در حال ارسال...
                    </span>
                        </button>
                    @endif

                </div>
            </div>
        </div>
    @endif
</div>


