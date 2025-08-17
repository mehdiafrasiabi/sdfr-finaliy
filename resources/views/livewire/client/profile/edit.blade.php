
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
                            <div class="space-y-5" x-show="activeTab === 'tabOne'">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-1">
                                        <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                        <div class="w-2 h-2 bg-foreground rounded-full"></div>
                                    </div>
                                    <div class="font-black text-foreground">اطلاعات حساب</div>
                                </div>

                                <form wire:submit.prevent="save" class="space-y-5">
                                    <div class="grid sm:grid-cols-2 gap-5">
                                        <div class="space-y-1">
                                            <label for="fullname" class="font-medium text-xs text-muted">نام
                                                و
                                                نام خانوادگی (فارسی)</label>
                                            <input type="text" id="fullname" wire:model="name" name="name"
                                                   class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"/>
                                            @error('name')
                                            <div class="font-medium text-xs text-red-500">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="space-y-1">
                                            <label for="email"
                                                   class="font-medium text-xs text-muted">ایمیل</label>
                                            <input type="text" id="email" dir="ltr"
                                                   wire:model="email"

                                                   class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"/>
                                          @error('email')
                                            <div class="font-medium text-xs text-red-500">
                                               {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                        <div class="space-y-1">
                                            <label for="subject"
                                                   class="block font-semibold text-xs text-foreground">فایل
                                                پیوست:</label>
                                            <label
                                                class="inline-flex items-center gap-x-1 border rounded-full text-muted py-2.5 px-5 cursor-pointer hover:text-foreground"
                                                for="customFile" x-data="{ files: null }">
                                                <input type="file" class="sr-only" id="customFile"
                                                       wire:model="new_photo"
                                                       name="new_photo"
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
                                            @error('new_photo')
                                            <div class="font-medium text-xs text-red-500">
                                                {{$message}}
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    @if (session()->has('success'))
                                        <div class="bg-success text-white px-4 py-2  rounded-full">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    <div class="flex justify-end gap-5">
                                        <button type="submit"
                                                class="h-11 inline-flex items-center justify-center gap-3 bg-primary rounded-full text-white px-4 mr-auto">

                                               <span class="font-semibold text-sm" wire:loading.remove>بروزرسانی</span>


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
                            <!-- end tabs:contents:tabOne -->


                            <!-- tabs:contents:tabTwo -->
                            <div class="space-y-5" x-show="activeTab === 'tabThree'">
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-1">
                                        <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                        <div class="w-2 h-2 bg-foreground rounded-full"></div>
                                    </div>
                                    <div class="font-black text-foreground">رمز عبور</div>
                                </div>
                                <!-- alert -->
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
                                            توجه :‌
                                        </div><!-- end alert:title -->

                                        <!-- alert:desc -->
                                        <div class="font-semibold text-xs text-zinc-400">
                                            <ul>
                                                <li>حداقل یک حرف کوچک استفاده کنید</li>
                                                <li>حداقل یک حرف بزرگ استفاده کنید</li>
                                                <li>پسورد حداقل باید ۸ کاراکتر باشد</li>
                                                <li>حداقل از یک عدد استفاده کنید</li>
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
                                </div><!-- end alert -->

                                <form action="#" class="space-y-5">
                                    <div class="flex flex-col gap-5">
                                        <div class="space-y-1 sm:w-1/2">
                                            <label for="password"
                                                   class="block font-medium text-xs text-muted">پسورد
                                                فعلی</label>
                                            <input type="text" dir="ltr" id="password"
                                                   class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"/>
                                        </div>
                                        <div class="space-y-1 sm:w-1/2">
                                            <label for="password_new"
                                                   class="block font-medium text-xs text-muted">پسورد
                                                جدید</label>
                                            <input type="text" dir="ltr" id="password_new"
                                                   class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5"/>
                                        </div>
                                    </div>
                                    <div class="flex justify-end gap-5">
                                        <button type="submit"
                                                class="h-11 inline-flex items-center justify-center gap-3 bg-primary rounded-full text-white px-4 mr-auto">
                                            <span class="font-semibold text-sm">بروزرسانی</span>
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                 fill="currentColor" class="w-5 h-5">
                                                <path fill-rule="evenodd"
                                                      d="M15.312 11.424a5.5 5.5 0 0 1-9.201 2.466l-.312-.311h2.433a.75.75 0 0 0 0-1.5H3.989a.75.75 0 0 0-.75.75v4.242a.75.75 0 0 0 1.5 0v-2.43l.31.31a7 7 0 0 0 11.712-3.138.75.75 0 0 0-1.449-.39Zm1.23-3.723a.75.75 0 0 0 .219-.53V2.929a.75.75 0 0 0-1.5 0V5.36l-.31-.31A7 7 0 0 0 3.239 8.188a.75.75 0 1 0 1.448.389A5.5 5.5 0 0 1 13.89 6.11l.311.31h-2.432a.75.75 0 0 0 0 1.5h4.243a.75.75 0 0 0 .53-.219Z"
                                                      clip-rule="evenodd"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <!-- end tabs:contents:tabTwo -->

                            <!-- tabs:contents:tabTwo -->
                            <!-- end tabs:contents:tabTwo -->
                        </div><!-- end tabs:contents -->
                    </div><!-- end tabs container -->
                </div>
            </div>
        </div>
    </div>
</div>
