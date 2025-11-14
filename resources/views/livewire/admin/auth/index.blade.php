<div>

    <!-- End Light/Dark Mode Button -->

    <!-- Sign In -->
    <div class="bg-white dark:bg-[#0a0e19] py-[60px] md:py-[80px] lg:py-[135px]">
        <div class="mx-auto px-[12.5px] md:max-w-[720px] lg:max-w-[960px] xl:max-w-[1255px]">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-[25px] items-center">
                <div
                    class="xl:ltr:-mr-[25px] xl:rtl:-ml-[25px] 2xl:ltr:-mr-[45px] 2xl:rtl:-ml-[45px] rounded-[25px] order-2 lg:order-1"
                >
                    <img src="/admin/assets/images/admin.gif" alt="تصویر در تصویر" class="rounded-[25px]"/>
                </div>
                <div class="xl:ltr:pl-[90px] xl:rtl:pr-[90px] 2xl:ltr:pl-[120px] 2xl:rtl:pr-[120px] order-1 lg:order-2">
                    <img src="/admin/assets/images/sdfr.png" width="140px" height="120px" alt="لوگو"
                         class="inline-block dark:hidden"/>
                    <img src="/admin/assets/images/sdfr.png" width="140px" height="120px" alt="لوگو"
                         class="hidden dark:inline-block"/>
                    <div class="my-[17px] md:my-[25px]">
                        <h1 class="!font-semibold !text-[22px] md:!text-xl lg:!text-2xl !mb-[5px] md:!mb-[7px]">
                            به پنل مدیریت خوش آمدید!
                        </h1>
                        <p class="font-medium lg:text-md text-[#445164] dark:text-gray-400">
                            با حساب اجتماعی وارد شوید یا جزئیات خود را وارد کنید
                        </p>
                    </div>
                    <form wire:submit="submit(Object.fromEntries(new FormData($event.target)))">
                        <div>
                            <div class="mb-[15px] relative">
                                <label class="mb-[10px] md:mb-[12px] text-black dark:text-white font-medium block">نام
                                    کاربری</label>
                                <input
                                    name="email"
                                    wire:model="email"
                                    type="text"
                                    class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500"
                                    placeholder="به عنوان مثال:sdfr@gmail.com"
                                />
                                @error('email')
                                <div class="text-[12px] font-medium text-orange-500  "
                                     style="margin-top: 7px">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-[15px] relative">
                                <label class="mb-[10px] md:mb-[12px] text-black dark:text-white font-medium block">شماره
                                    موبایل</label>
                                <input
                                    type="tel"
                                    name="mobile"
                                    wire:model="mobile"
                                    class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500"
                                    placeholder="به عنوان مثال:09021234567"
                                />
                                @error('mobile')
                                <div class="text-[12px] font-medium text-orange-500  "
                                     style="margin-top: 7px">{{$message}}</div>
                                @enderror
                            </div>
                            <div class="mb-[15px] relative" id="passwordHideShow">
                                <label
                                    class="mb-[10px] md:mb-[12px] text-black dark:text-white font-medium block">رمز</label>
                                <input
                                    type="password"
                                    name="password"
                                    wire:model="password"
                                    class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500"
                                    id="password"
                                    placeholder="رمز ورود"
                                />

                                <button
                                    class="absolute text-lg ltr:right-[20px] rtl:left-[20px] bottom-[12px] transition-all hover:text-primary-500"
                                    id="toggleButton"
                                    type="button"
                                >
                                    <i class="ri-eye-off-line"></i>
                                </button>

                            </div>
                            @error('password')
                            <div class="text-[12px] font-medium text-orange-500">{{$message}}</div>
                            @enderror
                        </div>
                        @if(session()->has('message'))
                            <div
                                class="alert py-[1rem] px-[1rem] text-danger-500 bg-danger-50 border border-danger-200 dark:bg-[#15203c] dark:border-[#15203c] rounded-md flex items-center justify-between"
                                id="dismissingAlert">
                                {{session('message')}}
                                <button class="leading-none text-[20px] close-btn">
                                    <i class="ri-close-line"></i>
                                </button>
                            </div>
                        @endif
                        <button
                            type="submit"
                            class="md:text-md block w-full text-center transition-all rounded-md font-medium mt-[20px] md:mt-[25px] py-[12px] px-[25px] text-white bg-primary-500 hover:bg-primary-700">
                          <span  class="flex items-center justify-center gap-[5px]" wire:loading.remove>
                            <i class="material-symbols-outlined">login</i>
                              ورود به سیستم
                          </span>
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



                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Sign In -->
</div>
