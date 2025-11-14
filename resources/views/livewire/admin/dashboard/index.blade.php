<div class="row">

    <!-- Breadcrumb -->

    <!-- Blank Page -->
    <div
        class="@if(session()->has('messageSuccess')) trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md  @endif">
        <div class=" trezo-card-content ">
            @if(session()->has('messageSuccess'))
                <div
                    class="alert py-[1rem] px-[1rem] text-success-500 bg-success-50 border border-success-200 dark:bg-[#15203c] dark:border-[#15203c] rounded-md flex items-center justify-between"
                    id="dismissingAlert">
                    {{ session()->get('messageSuccess') }}
                    <button class="leading-none text-[20px] close-btn">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="trezo-card mb-[25px]">
        <div class="trezo-card-content lg:flex justify-between items-center">
            <div>
                <h5 class="!mb-[6px] md:!mb-[3px] !font-semibold !text-[20px]">داشبورد</h5>
                <p>مدیریت یکپارچه اطلاعات دانش‌آموزان!</p>
            </div>
            <div class="flex items-center gap-[10px] mt-[12px] lg:mt-0">
                <div
                    class="rounded-md inline-block text-primary-500 py-[3.5px] px-[15px] bg-primary-50 dark:bg-[#0a0e19] border border-primary-100 dark:border-[#172036]"
                    id="currentDayDate"
                >
              <span class="inline-block relative ltr:pl-[24px] rtl:pr-[24px]">
                <i class="ri-calendar-line absolute text-[16px] top-1/2 -translate-y-1/2 ltr:left-0 rtl:right-0"></i>
                امروز - <span id="currentDate"></span>
              </span>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">
        <div class="lg:col-span-2">
            <!-- Stats -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] relative rounded-md">
                <div class="trezo-card-content">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-[25px]">
                        <div>
                            <div class="flex items-center gap-[10px] md:gap-[15px]">
                                <img src="assets/images/icons/graduation.svg" alt="graduation"/>
                                <div>
                                    <span class="block"> کل دانشجویان</span>
                                    <h5 class="!mb-0 mt-[2px] !text-[20px] !font-semibold">12,560</h5>
                                </div>
                            </div>
                            <div class="mt-[15px] md:mt-[42px] flex items-center gap-[7px]">
                                <div
                                    class="bg-success-100 text-success-700 dark:bg-[#15203c] rounded-[4px] w-[26px] h-[26px] flex items-center justify-center text-lg"
                                >
                                    <i class="ri-arrow-right-up-line"></i>
                                </div>
                                <div class="text-gray-600 dark:text-gray-400">
                                    <span class="font-medium text-gray-700 dark:text-gray-400">% 12 +</span>
                                    سال گذشته
                                </div>
                            </div>
                        </div>
                        <div class="ltr:md:pl-[20px] rtl:md:pr-[20px]">
                            <div class="flex items-center gap-[10px] md:gap-[15px]">
                                <img src="assets/images/icons/teacher.svg" alt="teacher"/>
                                <div>
                                    <span class="block"> مجموع دانش آموزان  </span>
                                    <h5 class="!mb-0 mt-[2px] !text-[20px] !font-semibold">780</h5>
                                </div>
                            </div>
                            <div class="mt-[15px] md:mt-[42px] flex items-center gap-[7px]">
                                <div
                                    class="bg-danger-100 text-danger-700 dark:bg-[#15203c] rounded-[4px] w-[26px] h-[26px] flex items-center justify-center text-lg"
                                >
                                    <i class="ri-arrow-right-down-line"></i>
                                </div>
                                <div class="text-gray-600 dark:text-gray-400">
                                    <span class="font-medium text-gray-700 dark:text-gray-400">% 12 -</span>
                                    ماه گذشته
                                </div>
                            </div>
                        </div>
                        <div class="ltr:md:pl-[20px] rtl:md:pr-[20px]">
                            <div class="flex items-center gap-[10px] md:gap-[15px]">
                                <img src="assets/images/icons/student.svg" alt="student"/>
                                <div>
                                    <span class="block"> حضور امروز(گزارش) </span>
                                    <h5 class="!mb-0 mt-[2px] !text-[20px] !font-semibold">1,425</h5>
                                </div>
                            </div>
                            <div class="mt-[15px] md:mt-[42px] flex items-center gap-[7px]">
                                <div
                                    class="bg-success-100 text-success-700 dark:bg-[#15203c] rounded-[4px] w-[26px] h-[26px] flex items-center justify-center text-lg"
                                >
                                    <i class="ri-arrow-right-up-line"></i>
                                </div>
                                <div class="text-gray-600 dark:text-gray-400">
                                    <span class="font-medium text-gray-700 dark:text-gray-400">% 12 +</span>
                                    ماه گذشته
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="w-[1px] absolute top-0 bottom-0 bg-gray-100 dark:bg-[#172036] left-[33.3333333333%] -translate-x-[33.3333333333%] hidden sm:block"
                ></div>
                <div
                    class="w-[1px] absolute top-0 bottom-0 bg-gray-100 dark:bg-[#172036] right-[33.3333333333%] translate-x-[33.3333333333%] hidden sm:block"
                ></div>
            </div>
        </div>
        <div class="lg:col-span-1">
            <!-- Upcoming Events -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[15px] flex items-center justify-between">
                    <div class="trezo-card-title">
                        <h5 class="!mb-0 !font-semibold">جلسات آینده</h5>
                    </div>
                </div>
                <div class="trezo-card-content relative" id="schoolUpcomingEventsSlides">
                    <div class="swiper mySwiper">
                        <div class="swiper-wrapper">
                            <div class="swiper-slide bg-gray-50 dark:bg-[#0a0e19] p-[12px] rounded-md">
                                <div class="flex items-center justify-between">
                                    <a
                                        href="#"
                                        class="block font-medium text-md text-black dark:text-white transition-all hover:text-primary-500"
                                    >
                                        بررسی نحوه برخورد با دانش آموزان
                                    </a>
                                    <span class="block"> 1404 مهر 10 </span>
                                </div>
                                <ul class="mt-[10px]">
                                    <li
                                        class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] ltr:mr-[20px] rtl:ml-[20px] ltr:last:mr-0 rtl:last:ml-0"
                                    >
                                        <i
                                            class="ri-time-line absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2 text-lg mt-px"
                                        ></i>
                                        9:00 صبح - 3:00 ظهر
                                    </li>
                                    <li
                                        class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] ltr:mr-[20px] rtl:ml-[20px] ltr:last:mr-0 rtl:last:ml-0"
                                    >
                                        <i class="ri-map-pin-line absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2 text-lg"></i>
                                        سالن اجتماعات مجموعه
                                    </li>
                                </ul>
                            </div>
                            <div class="swiper-slide bg-gray-50 dark:bg-[#0a0e19] p-[12px] rounded-md">
                                <div class="flex items-center justify-between">
                                    <a
                                        href="#"
                                        class="block font-medium text-md text-black dark:text-white transition-all hover:text-primary-500"
                                    >
                                       اماده شدن برای ورودی های آبان
                                    </a>
                                    <span class="block"> 1404 مهر 20 </span>
                                </div>
                                <ul class="mt-[10px]">
                                    <li
                                        class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] ltr:mr-[20px] rtl:ml-[20px] ltr:last:mr-0 rtl:last:ml-0"
                                    >
                                        <i
                                            class="ri-time-line absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2 text-lg mt-px"
                                        ></i>
                                        11:00 صبح - 3:00 ظهر
                                    </li>
                                    <li
                                        class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] ltr:mr-[20px] rtl:ml-[20px] ltr:last:mr-0 rtl:last:ml-0"
                                    >
                                        <i class="ri-map-pin-line absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2 text-lg"></i>
                                        سالن اجتماعات مجموعه
                                    </li>
                                </ul>
                            </div>
                            <div class="swiper-slide bg-gray-50 dark:bg-[#0a0e19] p-[12px] rounded-md">
                                <div class="flex items-center justify-between">
                                    <a
                                        href="#"
                                        class="block font-medium text-md text-black dark:text-white transition-all hover:text-primary-500"
                                    >
                                       اتفاقات اخیر مجموعه
                                    </a>
                                    <span class="block"> 1404 دی 30 </span>
                                </div>
                                <ul class="mt-[10px]">
                                    <li
                                        class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] ltr:mr-[20px] rtl:ml-[20px] ltr:last:mr-0 rtl:last:ml-0"
                                    >
                                        <i
                                            class="ri-time-line absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2 text-lg mt-px"
                                        ></i>
                                        9:00 صبح - 3:00 ظهر
                                    </li>
                                    <li
                                        class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] ltr:mr-[20px] rtl:ml-[20px] ltr:last:mr-0 rtl:last:ml-0"
                                    >
                                        <i class="ri-map-pin-line absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2 text-lg"></i>
                                        اسکای روم
                                    </li>
                                </ul>
                            </div>
                            <div class="swiper-slide bg-gray-50 dark:bg-[#0a0e19] p-[12px] rounded-md">
                                <div class="flex items-center justify-between">
                                    <a
                                        href="#"
                                        class="block font-medium text-md text-black dark:text-white transition-all hover:text-primary-500"
                                    >
                                        گرفتن گزارش کار
                                    </a>
                                    <span class="block"> 1404 دی 15 </span>
                                </div>
                                <ul class="mt-[10px]">
                                    <li
                                        class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] ltr:mr-[20px] rtl:ml-[20px] ltr:last:mr-0 rtl:last:ml-0"
                                    >
                                        <i
                                            class="ri-time-line absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2 text-lg mt-px"
                                        ></i>
                                        9:00 صبح - 3:00 ظهر
                                    </li>
                                    <li
                                        class="inline-block relative ltr:pl-[22px] rtl:pr-[22px] ltr:mr-[20px] rtl:ml-[20px] ltr:last:mr-0 rtl:last:ml-0"
                                    >
                                        <i class="ri-map-pin-line absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2 text-lg"></i>
                                       الوکام
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">
        <div class="lg:col-span-2">
            <!-- Attendance Analytics -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                    <div class="trezo-card-title">
                        <h5 class="!mb-0 !font-semibold">تجزیه و تحلیل حضور و غیاب</h5>
                    </div>
                    <div class="trezo-card-subtitle">
                        <div class="trezo-card-dropdown relative">
                            <button
                                type="button"
                                class="trezo-card-dropdown-btn inline-block rounded-md border border-gray-100 py-[5px] md:py-[6.5px] px-[12px] md:px-[19px] transition-all hover:bg-gray-50 dark:border-[#172036] dark:hover:bg-[#0a0e19]"
                                id="dropdownToggleBtn"
                            >
                    <span class="inline-block relative ltr:pr-[17px] ltr:md:pr-[20px] rtl:pl-[17px] rtl:ml:pr-[20px]">
                      امسال
                      <i
                          class="ri-arrow-down-s-line text-lg absolute ltr:-right-[3px] rtl:-left-[3px] top-1/2 -translate-y-1/2"
                      ></i>
                    </span>
                            </button>
                            <ul
                                class="trezo-card-dropdown-menu transition-all bg-white shadow-3xl rounded-md top-full py-[15px] absolute ltr:right-0 rtl:left-0 w-[195px] z-[5] dark:bg-dark dark:shadow-none"
                            >
                                <li>
                                    <button
                                        type="button"
                                        class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black"
                                    >
                                        امروز
                                    </button>
                                </li>
                                <li>
                                    <button
                                        type="button"
                                        class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black"
                                    >
                                        این هفته
                                    </button>
                                </li>
                                <li>
                                    <button
                                        type="button"
                                        class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black"
                                    >
                                        این ماه
                                    </button>
                                </li>
                                <li>
                                    <button
                                        type="button"
                                        class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black"
                                    >
                                        امسال
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="trezo-card-content">
                    <div class="-mt-[15px] ltr:-ml-[15px] rtl:-mr-[15px] -mb-[20px]">
                        <div id="schoolAttendanceAnalyticsChart"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="lg:col-span-1">
            <!-- Teachers -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                    <div class="trezo-card-title">
                        <h5 class="!mb-0 !font-semibold">دوستان </h5>
                    </div>
                    <div class="trezo-card-subtitle">
                        <a
                            href="#"
                            class="inline-block relative ltr:pr-[13px] rtl:pl-[13px] leading-none transition-all hover:text-primary-500"
                        >
                            مشاهده همه<i
                                class="ri-arrow-right-s-line absolute top-1/2 -translate-y-1/2 ltr:-right-[8px] rtl:-left-[8px] text-[23px] -mt-px"
                            ></i>
                        </a>
                    </div>
                </div>
                <div class="trezo-card-content -mx-[20px] md:-mx-[25px]">
                    <div class="table-responsive overflow-x-auto">
                        <table class="w-full without-border">
                            <thead>
                            <tr>
                                <th
                                    class="font-normal border-t border-gray-50 dark:border-[#172036] ltr:text-left rtl:text-right px-[20px] py-[10px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 whitespace-nowrap ltr:last:text-right rtl:last:text-left"
                                >
                                    نام
                                </th>
                                <th
                                    class="font-normal border-t border-gray-50 dark:border-[#172036] ltr:text-left rtl:text-right px-[20px] py-[10px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 whitespace-nowrap ltr:last:text-right rtl:last:text-left"
                                >
                                    سمت
                                </th>
                            </tr>
                            </thead>
                            <tbody class="text-black dark:text-white">
                            <tr>
                                <td
                                    class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-50 dark:border-[#172036] ltr:last:text-right rtl:last:text-left"
                                >
                                    <div class="flex items-center gap-[10px]">
                                        <div class="rounded-full w-[40px]">
                                            <img
                                                src="assets/images/users/user53.jpg"
                                                class="inline-block rounded-full"
                                                alt="تصویر محصول"
                                            />
                                        </div>
                                        <div>
                                            <span class="font-medium inline-block mb-px"> سارا دبلیو. </span>
                                            <span class="block text-gray-500 dark:text-gray-400 text-xs"> sarah@trezo.com </span>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-50 dark:border-[#172036] ltr:last:text-right rtl:last:text-left"
                                >
                                   پشتیبان
                                </td>
                            </tr>
                            <tr>
                                <td
                                    class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-50 dark:border-[#172036] ltr:last:text-right rtl:last:text-left"
                                >
                                    <div class="flex items-center gap-[10px]">
                                        <div class="rounded-full w-[40px]">
                                            <img
                                                src="assets/images/users/user54.jpg"
                                                class="inline-block rounded-full"
                                                alt="تصویر محصول"
                                            />
                                        </div>
                                        <div>
                                            <span class="font-medium inline-block mb-px"> مایکل تی. </span>
                                            <span class="block text-gray-500 dark:text-gray-400 text-xs"> michael@trezo.com </span>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-50 dark:border-[#172036] ltr:last:text-right rtl:last:text-left"
                                >
                                    مشاور
                                </td>
                            </tr>
                            <tr>
                                <td
                                    class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-50 dark:border-[#172036] ltr:last:text-right rtl:last:text-left"
                                >
                                    <div class="flex items-center gap-[10px]">
                                        <div class="rounded-full w-[40px]">
                                            <img
                                                src="assets/images/users/user55.jpg"
                                                class="inline-block rounded-full"
                                                alt="تصویر محصول"
                                            />
                                        </div>
                                        <div>
                                            <span class="font-medium inline-block mb-px"> امیلی جی. </span>
                                            <span class="block text-gray-500 dark:text-gray-400 text-xs"> emily@trezo.com </span>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-50 dark:border-[#172036] ltr:last:text-right rtl:last:text-left"
                                >
                                    مشاور ارشد
                                </td>
                            </tr>
                            <tr>
                                <td
                                    class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-50 dark:border-[#172036] ltr:last:text-right rtl:last:text-left"
                                >
                                    <div class="flex items-center gap-[10px]">
                                        <div class="rounded-full w-[40px]">
                                            <img
                                                src="assets/images/users/user56.jpg"
                                                class="inline-block rounded-full"
                                                alt="تصویر محصول"
                                            />
                                        </div>
                                        <div>
                                            <span class="font-medium inline-block mb-px"> دیوید آ. </span>
                                            <span class="block text-gray-500 dark:text-gray-400 text-xs"> david@trezo.com </span>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-50 dark:border-[#172036] ltr:last:text-right rtl:last:text-left"
                                >
                                    مدیر
                                </td>
                            </tr>
                            <tr>
                                <td
                                    class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-50 dark:border-[#172036] ltr:last:text-right rtl:last:text-left"
                                >
                                    <div class="flex items-center gap-[10px]">
                                        <div class="rounded-full w-[40px]">
                                            <img
                                                src="assets/images/users/user57.jpg"
                                                class="inline-block rounded-full"
                                                alt="تصویر محصول"
                                            />
                                        </div>
                                        <div>
                                            <span class="font-medium inline-block mb-px"> جسیکا م. </span>
                                            <span class="block text-gray-500 dark:text-gray-400 text-xs"> jessica@trezo.com </span>
                                        </div>
                                    </div>
                                </td>
                                <td
                                    class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-50 dark:border-[#172036] ltr:last:text-right rtl:last:text-left"
                                >
                                   فنی
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-[25px] mb-[25px]">
        <!-- Students Overview -->
        <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
            <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                <div class="trezo-card-title">
                    <h5 class="!mb-0 !font-semibold">نمای کلی دانشجویان</h5>
                </div>
                <div class="trezo-card-subtitle">
                    <div class="trezo-card-dropdown relative">
                        <button
                            type="button"
                            class="trezo-card-dropdown-btn inline-block transition-all hover:text-primary-500"
                            id="dropdownToggleBtn"
                        >
                  <span class="inline-block relative ltr:pr-[17px] ltr:md:pr-[20px] rtl:pl-[17px] rtl:ml:pr-[20px]">
                    ماه گذشته
                    <i
                        class="ri-arrow-down-s-line text-lg absolute ltr:-right-[3px] rtl:-left-[3px] top-1/2 -translate-y-1/2"
                    ></i>
                  </span>
                        </button>
                        <ul
                            class="trezo-card-dropdown-menu transition-all bg-white shadow-3xl rounded-md top-full py-[15px] absolute ltr:right-0 rtl:left-0 w-[195px] z-[5] dark:bg-dark dark:shadow-none"
                        >
                            <li>
                                <button
                                    type="button"
                                    class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black"
                                >
                                    روز گذشته
                                </button>
                            </li>
                            <li>
                                <button
                                    type="button"
                                    class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black"
                                >
                                    هفته پیش
                                </button>
                            </li>
                            <li>
                                <button
                                    type="button"
                                    class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black"
                                >
                                    ماه گذشته
                                </button>
                            </li>
                            <li>
                                <button
                                    type="button"
                                    class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black"
                                >
                                    سال گذشته
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="trezo-card-content">
                <div class="ltr:-ml-[10px] rtl:-mr-[10px] md:mb-[10px]">
                    <div id="schoolStudentsOverviewChart"></div>
                </div>
                <div class="flex items-center gap-[20px] 2xl:gap-[30px]">
                    <div class="flex items-center gap-[12px]">
                        <div
                            class="flex items-center justify-center bg-primary-100 dark:bg-[#15203c] rounded-[4px] w-[42px] h-[42px]"
                        >
                            <img src="assets/images/icons/boys.svg" alt="boys"/>
                        </div>
                        <div>
                            <span class="block"> پسران </span>
                            <h5 class="!mb-0 mt-px !text-[20px] !font-semibold">980</h5>
                        </div>
                    </div>
                    <div class="flex items-center gap-[12px]">
                        <div
                            class="flex items-center justify-center bg-orange-100 dark:bg-[#15203c] rounded-[4px] w-[42px] h-[42px]"
                        >
                            <img src="assets/images/icons/girls.svg" alt="girls"/>
                        </div>
                        <div>
                            <span class="block"> دختران </span>
                            <h5 class="!mb-0 mt-px !text-[20px] !font-semibold">675</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- New Admissions -->
        <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
            <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                <div class="trezo-card-title">
                    <h5 class="!mb-0 !font-semibold">پذیرش‌های جدید</h5>
                </div>
                <div class="trezo-card-subtitle">
                    <div class="trezo-card-dropdown relative ltr:-mr-[7px] rtl:-ml-[7px]">
                        <button
                            type="button"
                            class="trezo-card-dropdown-btn inline-block transition-all text-[22px] text-gray-500 dark:text-gray-400 leading-none hover:text-primary-500"
                            id="dropdownToggleBtn"
                        >
                            <i class="ri-more-2-fill"></i>
                        </button>
                        <ul
                            class="trezo-card-dropdown-menu transition-all bg-white shadow-3xl rounded-md top-full py-[15px] absolute ltr:right-0 rtl:left-0 w-[195px] z-[5] dark:bg-dark dark:shadow-none"
                        >
                            <li>
                                <button
                                    type="button"
                                    class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black"
                                >
                                    امروز
                                </button>
                            </li>
                            <li>
                                <button
                                    type="button"
                                    class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black"
                                >
                                    این هفته
                                </button>
                            </li>
                            <li>
                                <button
                                    type="button"
                                    class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black"
                                >
                                    این ماه
                                </button>
                            </li>
                            <li>
                                <button
                                    type="button"
                                    class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black"
                                >
                                    امسال
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="trezo-card-content">
                <div class="-mt-[8px]">
                    <div id="schoolNewAdmissionsChart"></div>
                </div>
            </div>
        </div>

        <!-- Notice Board -->
        <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
            <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                <div class="trezo-card-title">
                    <h5 class="!mb-0 !font-semibold">تابلو اعلانات</h5>
                </div>
                <div class="trezo-card-subtitle">
                    <a
                        href="#"
                        class="inline-block relative ltr:pr-[13px] rtl:pl-[13px] leading-none transition-all hover:text-primary-500"
                    >
                        مشاهده همه<i
                            class="ri-arrow-right-s-line absolute top-1/2 -translate-y-1/2 ltr:-right-[8px] rtl:-left-[8px] text-[23px] -mt-px"
                        ></i>
                    </a>
                </div>
            </div>
            <div class="trezo-card-content -mx-[20px] md:-mx-[25px]">
                <div
                    class="relative border-b border-gray-50 dark:border-[#172036] pb-[10px] mb-[11px] px-[70px] md:px-[76px] last:border-b-0 last:pb-0 last:mb-0"
                >
                    <div
                        class="w-[40px] h-[40px] bg-purple-500 rounded-full flex items-center justify-center absolute ltr:left-[20px] ltr:md:left-[25px] rtl:right-[20px] rtl:md:right-[25px] mt-[2px]"
                    >
                        <img src="assets/images/icons/note.svg" alt="توجه داشته باشید"/>
                    </div>
                    <h6 class="!text-base !font-medium !mb-[4px]">
                        <a href="#" class="text-gray-700 dark:text-gray-400 transition-all hover:text-primary-500">
                            ثبت نام نمایشگاه علمی
                        </a>
                    </h6>
                    <p class="text-xs max-w-[166px] !leading-[1.4] !mb-[5px]">ثبت نام در نمایشگاه علمی سالانه</p>
                    <span class="block relative text-primary-500 text-xs ltr:pl-[16px] rtl:pr-[16px]">
                <i class="ri-calendar-line absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2 -mt-px"></i>
                ۲۸ اکتبر ۲۰۲۵
              </span>
                    <a
                        href="javascript:void(0);"
                        class="inline-block absolute ltr:right-[20px] rtl:left-[20px] ltr:md:right-[25px] rtl:md:left-[25px] top-1/2 -translate-y-1/2 -mt-[10px] w-[40px] h-[40px] md:w-[43px] md:h-[43px] text-center text-gray-400 transition-all border border-gray-100 dark:border-[#172036] rounded-full hover:bg-primary-500 hover:border-primary-500 hover:text-white"
                    >
                        <i class="material-symbols-outlined absolute left-0 right-0 !text-[22px] top-1/2 -translate-y-1/2">
                            arrow_outward
                        </i>
                    </a>
                </div>
                <div
                    class="relative border-b border-gray-50 dark:border-[#172036] pb-[10px] mb-[11px] px-[70px] md:px-[76px] last:border-b-0 last:pb-0 last:mb-0"
                >
                    <div
                        class="w-[40px] h-[40px] bg-primary-500 rounded-full flex items-center justify-center absolute ltr:left-[20px] ltr:md:left-[25px] rtl:right-[20px] rtl:md:right-[25px] mt-[2px]"
                    >
                        <img src="assets/images/icons/video-chat.svg" alt="چت تصویری"/>
                    </div>
                    <h6 class="!text-base !font-medium !mb-[4px]">
                        <a href="#" class="text-gray-700 dark:text-gray-400 transition-all hover:text-primary-500">
                            جلسه اولیا و مربیان
                        </a>
                    </h6>
                    <p class="text-xs max-w-[166px] !leading-[1.4] !mb-[5px]">جلسه اولیا و مربیان ترم اول برگزار می
                        شود</p>
                    <span class="block relative text-primary-500 text-xs ltr:pl-[16px] rtl:pr-[16px]">
                <i class="ri-calendar-line absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2 -mt-px"></i>
                1404 دی 10
              </span>
                    <a
                        href="javascript:void(0);"
                        class="inline-block absolute ltr:right-[20px] rtl:left-[20px] ltr:md:right-[25px] rtl:md:left-[25px] top-1/2 -translate-y-1/2 -mt-[10px] w-[40px] h-[40px] md:w-[43px] md:h-[43px] text-center text-gray-400 transition-all border border-gray-100 dark:border-[#172036] rounded-full hover:bg-primary-500 hover:border-primary-500 hover:text-white"
                    >
                        <i class="material-symbols-outlined absolute left-0 right-0 !text-[22px] top-1/2 -translate-y-1/2">
                            arrow_outward
                        </i>
                    </a>
                </div>
                <div
                    class="relative border-b border-gray-50 dark:border-[#172036] pb-[10px] mb-[11px] px-[70px] md:px-[76px] last:border-b-0 last:pb-0 last:mb-0"
                >
                    <div
                        class="w-[40px] h-[40px] bg-orange-500 rounded-full flex items-center justify-center absolute ltr:left-[20px] ltr:md:left-[25px] rtl:right-[20px] rtl:md:right-[25px] mt-[2px]"
                    >
                        <img src="assets/images/icons/ball.svg" alt="توپ"/>
                    </div>
                    <h6 class="!text-base !font-medium !mb-[4px]">
                        <a href="#" class="text-gray-700 dark:text-gray-400 transition-all hover:text-primary-500">
                            مسابقات ورزشی زمستانی
                        </a>
                    </h6>
                    <p class="text-xs max-w-[166px] !leading-[1.4] !mb-[5px]">تست تیم‌های ورزشی زمستانی آغاز می‌شود</p>
                    <span class="block relative text-primary-500 text-xs ltr:pl-[16px] rtl:pr-[16px]">
                <i class="ri-calendar-line absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2 -mt-px"></i>
                1404 دی 10
              </span>
                    <a
                        href="javascript:void(0);"
                        class="inline-block absolute ltr:right-[20px] rtl:left-[20px] ltr:md:right-[25px] rtl:md:left-[25px] top-1/2 -translate-y-1/2 -mt-[10px] w-[40px] h-[40px] md:w-[43px] md:h-[43px] text-center text-gray-400 transition-all border border-gray-100 dark:border-[#172036] rounded-full hover:bg-primary-500 hover:border-primary-500 hover:text-white"
                    >
                        <i class="material-symbols-outlined absolute left-0 right-0 !text-[22px] top-1/2 -translate-y-1/2">
                            arrow_outward
                        </i>
                    </a>
                </div>
                <div
                    class="relative border-b border-gray-50 dark:border-[#172036] pb-[10px] mb-[11px] px-[70px] md:px-[76px] last:border-b-0 last:pb-0 last:mb-0"
                >
                    <div
                        class="w-[40px] h-[40px] bg-secondary-500 rounded-full flex items-center justify-center absolute ltr:left-[20px] ltr:md:left-[25px] rtl:right-[20px] rtl:md:right-[25px] mt-[2px]"
                    >
                        <img src="assets/images/icons/celebration.svg" alt="جشن"/>
                    </div>
                    <h6 class="!text-base !font-medium !mb-[4px]">
                        <a href="#" class="text-gray-700 dark:text-gray-400 transition-all hover:text-primary-500">
                            یادآوری تعطیلات مدرسه
                        </a>
                    </h6>
                    <p class="text-xs max-w-[166px] !leading-[1.4] !mb-[5px]">یادآوری تعطیلی مدارس در آبان ماه</p>
                    <span class="block relative text-primary-500 text-xs ltr:pl-[16px]">
                <i class="ri-calendar-line absolute ltr:left-0 rtl:right-0 top-1/2 -translate-y-1/2 -mt-px"></i>
                1404 دی 10
              </span>
                    <a
                        href="javascript:void(0);"
                        class="inline-block absolute ltr:right-[20px] rtl:left-[20px] ltr:md:right-[25px] rtl:md:left-[25px] top-1/2 -translate-y-1/2 -mt-[10px] w-[40px] h-[40px] md:w-[43px] md:h-[43px] text-center text-gray-400 transition-all border border-gray-100 dark:border-[#172036] rounded-full hover:bg-primary-500 hover:border-primary-500 hover:text-white"
                    >
                        <i class="material-symbols-outlined absolute left-0 right-0 !text-[22px] top-1/2 -translate-y-1/2">
                            arrow_outward
                        </i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
        <div class="trezo-card-header mb-[20px] md:mb-[25px] sm:flex sm:items-center sm:justify-between">
            <div class="trezo-card-title">
                <h5 class="!mb-0 !font-semibold">لیست دانشجویان</h5>
            </div>
            <div class="trezo-card-subtitle flex items-center mt-[15px] sm:mt-0">
                <form
                    class="relative w-[225px] sm:w-[265px] ltr:mr-[10px] rtl:ml-[10px] ltr:sm:mr-[15px] rtl:sm:ml-[15px]">
                    <label
                        class="leading-none absolute ltr:left-[13px] rtl:right-[13px] text-black dark:text-white mt-px top-1/2 -translate-y-1/2"
                    >
                        <i class="material-symbols-outlined !text-[20px]"> search </i>
                    </label>
                    <input
                        type="text"
                        placeholder="جستجوی نام ...."
                        class="bg-gray-50 border border-gray-50 h-[36px] text-xs rounded-md w-full block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] ltr:md:pr-[16px] rtl:pl-[13px] rtl:md:pl-[16px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400"
                        id="dataTableSearchInput"
                    />
                </form>
                <div class="trezo-card-dropdown relative ltr:-mr-[7px] rtl:-ml-[7px]">
                    <button
                        type="button"
                        class="trezo-card-dropdown-btn inline-block transition-all text-[22px] text-gray-500 dark:text-gray-400 leading-none hover:text-primary-500"
                        id="dropdownToggleBtn"
                    >
                        <i class="ri-more-2-fill"></i>
                    </button>
                    <ul
                        class="trezo-card-dropdown-menu transition-all bg-white shadow-3xl rounded-md top-full py-[15px] absolute ltr:right-0 rtl:left-0 w-[195px] z-[5] dark:bg-dark dark:shadow-none"
                    >
                        <li>
                            <button
                                type="button"
                                class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black"
                            >
                                این روز
                            </button>
                        </li>
                        <li>
                            <button
                                type="button"
                                class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black"
                            >
                                این هفته
                            </button>
                        </li>
                        <li>
                            <button
                                type="button"
                                class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black"
                            >
                                این ماه
                            </button>
                        </li>
                        <li>
                            <button
                                type="button"
                                class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black"
                            >
                                امسال
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="trezo-card-content -mx-[20px] md:-mx-[25px]" id="dataTable">
            <div class="table-responsive overflow-x-auto">
                <table class="w-full">
                    <thead>
                    <tr>
                        <th
                            class="font-normal border-t border-gray-100 dark:border-[#172036] ltr:text-left rtl:text-right px-[20px] py-[12px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 whitespace-nowrap cursor-pointer relative"
                            data-column="id"
                        >
                            شناسه
                            <i class="ri-expand-up-down-fill text-gray-500 dark:text-gray-400"></i>
                        </th>
                        <th
                            class="font-normal border-t border-gray-100 dark:border-[#172036] ltr:text-left rtl:text-right px-[20px] py-[12px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 whitespace-nowrap cursor-pointer relative"
                            data-column="name"
                        >
                            نام
                            <i class="ri-expand-up-down-fill text-gray-500 dark:text-gray-400"></i>
                        </th>
                        <th
                            class="font-normal border-t border-gray-100 dark:border-[#172036] ltr:text-left rtl:text-right px-[20px] py-[12px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 whitespace-nowrap cursor-pointer relative"
                            data-column="subject"
                        >
                            موضوع
                            <i class="ri-expand-up-down-fill text-gray-500 dark:text-gray-400"></i>
                        </th>
                        <th
                            class="font-normal border-t border-gray-100 dark:border-[#172036] ltr:text-left rtl:text-right px-[20px] py-[12px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 whitespace-nowrap cursor-pointer relative"
                            data-column="class"
                        >
                            کلاس
                            <i class="ri-expand-up-down-fill text-gray-500 dark:text-gray-400"></i>
                        </th>
                        <th
                            class="font-normal border-t border-gray-100 dark:border-[#172036] ltr:text-left rtl:text-right px-[20px] py-[12px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 whitespace-nowrap cursor-pointer relative"
                            data-column="contact"
                        >
                            تماس
                            <i class="ri-expand-up-down-fill text-gray-500 dark:text-gray-400"></i>
                        </th>
                        <th
                            class="font-normal border-t border-gray-100 dark:border-[#172036] ltr:text-left rtl:text-right px-[20px] py-[12px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 whitespace-nowrap cursor-pointer relative"
                            data-column="result"
                        >
                            نتیجه
                            <i class="ri-expand-up-down-fill text-gray-500 dark:text-gray-400"></i>
                        </th>
                        <th
                            class="font-normal border-t border-gray-100 dark:border-[#172036] ltr:text-left rtl:text-right px-[20px] py-[12px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 whitespace-nowrap cursor-pointer relative"
                            data-column="status"
                        >
                            وضعیت
                            <i class="ri-expand-up-down-fill text-gray-500 dark:text-gray-400"></i>
                        </th>
                        <th
                            class="font-normal border-t border-gray-100 dark:border-[#172036] ltr:text-left rtl:text-right px-[20px] py-[12px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 whitespace-nowrap cursor-pointer relative"
                            data-column="action"
                        >
                            عملیات
                            <i class="ri-expand-up-down-fill text-gray-500 dark:text-gray-400"></i>
                        </th>
                    </tr>
                    </thead>
                    <tbody class="text-black dark:text-white">
                    <tr>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            #101
                        </td>
                        <td
                            class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[10px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            <div class="flex items-center gap-[10px]">
                                <div class="rounded-full w-[40px]">
                                    <img src="assets/images/users/user46.jpg" class="inline-block rounded-full"
                                         alt="تصویر محصول"/>
                                </div>
                                <div>
                                    <span class="font-medium inline-block mb-px"> امیلی جانسون </span>
                                    <span
                                        class="block text-gray-500 dark:text-gray-400 text-xs"> emily@gmail.com </span>
                                </div>
                            </div>
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            ریاضی
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            5ب
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            021-2546525
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            ۸۹٪ امتیاز کلی (A)
                        </td>
                        <td
                            class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                    <span
                        class="px-[8px] py-[3px] inline-block font-medium bg-success-100 dark:bg-[#15203c] text-success-700 rounded-sm text-xs"
                    >
                      تصویب شد
                    </span>
                        </td>
                        <td
                            class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            <div class="flex items-center gap-[9px]">
                                <button
                                    type="button"
                                    class="text-primary-500 leading-none custom-tooltip"
                                    id="customTooltip"
                                    data-text="نمایش"
                                >
                                    <i class="material-symbols-outlined !text-md"> visibility </i>
                                    <span class="tooltip-text">مشاهده</span>
                                </button>
                                <button
                                    type="button"
                                    class="text-gray-500 dark:text-gray-400 leading-none custom-tooltip"
                                    id="customTooltip"
                                    data-text="ویرایش"
                                >
                                    <i class="material-symbols-outlined !text-md"> edit </i>
                                    <span class="tooltip-text">ویرایش</span>
                                </button>
                                <button
                                    type="button"
                                    class="text-danger-500 leading-none custom-tooltip"
                                    id="customTooltip"
                                    data-text="حذف"
                                >
                                    <i class="material-symbols-outlined !text-md"> Delete </i>
                                    <span class="tooltip-text">حذف</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            #102
                        </td>
                        <td
                            class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[10px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            <div class="flex items-center gap-[10px]">
                                <div class="rounded-full w-[40px]">
                                    <img src="assets/images/users/user47.jpg" class="inline-block rounded-full"
                                         alt="تصویر محصول"/>
                                </div>
                                <div>
                                    <span class="font-medium inline-block mb-px"> مایکل تامپسون </span>
                                    <span
                                        class="block text-gray-500 dark:text-gray-400 text-xs"> lmichael@gmail.com </span>
                                </div>
                            </div>
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            انگلیسی
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            8ب
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            021-2546525
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            ۳۲٪ کل (F)
                        </td>
                        <td
                            class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                    <span
                        class="px-[8px] py-[3px] inline-block font-medium bg-danger-100 dark:bg-[#15203c] text-danger-700 rounded-sm text-xs"
                    >
                      شکست
                    </span>
                        </td>
                        <td
                            class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            <div class="flex items-center gap-[9px]">
                                <button
                                    type="button"
                                    class="text-primary-500 leading-none custom-tooltip"
                                    id="customTooltip"
                                    data-text="نمایش"
                                >
                                    <i class="material-symbols-outlined !text-md"> visibility </i>
                                    <span class="tooltip-text">مشاهده</span>
                                </button>
                                <button
                                    type="button"
                                    class="text-gray-500 dark:text-gray-400 leading-none custom-tooltip"
                                    id="customTooltip"
                                    data-text="ویرایش"
                                >
                                    <i class="material-symbols-outlined !text-md"> edit </i>
                                    <span class="tooltip-text">ویرایش</span>
                                </button>
                                <button
                                    type="button"
                                    class="text-danger-500 leading-none custom-tooltip"
                                    id="customTooltip"
                                    data-text="حذف"
                                >
                                    <i class="material-symbols-outlined !text-md"> Delete </i>
                                    <span class="tooltip-text">حذف</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            #103
                        </td>
                        <td
                            class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[10px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            <div class="flex items-center gap-[10px]">
                                <div class="rounded-full w-[40px]">
                                    <img src="assets/images/users/user48.jpg" class="inline-block rounded-full"
                                         alt="تصویر محصول"/>
                                </div>
                                <div>
                                    <span class="font-medium inline-block mb-px"> سارا ویلیامز </span>
                                    <span
                                        class="block text-gray-500 dark:text-gray-400 text-xs"> sarah@gmail.com </span>
                                </div>
                            </div>
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            جغرافیا
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            ۴سی
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            021-2546525
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            ۹۲٪ امتیاز کلی (A+)
                        </td>
                        <td
                            class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                    <span
                        class="px-[8px] py-[3px] inline-block font-medium bg-primary-100 dark:bg-[#15203c] text-primary-700 rounded-sm text-xs"
                    >
                      رها شده
                    </span>
                        </td>
                        <td
                            class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            <div class="flex items-center gap-[9px]">
                                <button
                                    type="button"
                                    class="text-primary-500 leading-none custom-tooltip"
                                    id="customTooltip"
                                    data-text="نمایش"
                                >
                                    <i class="material-symbols-outlined !text-md"> visibility </i>
                                    <span class="tooltip-text">مشاهده</span>
                                </button>
                                <button
                                    type="button"
                                    class="text-gray-500 dark:text-gray-400 leading-none custom-tooltip"
                                    id="customTooltip"
                                    data-text="ویرایش"
                                >
                                    <i class="material-symbols-outlined !text-md"> edit </i>
                                    <span class="tooltip-text">ویرایش</span>
                                </button>
                                <button
                                    type="button"
                                    class="text-danger-500 leading-none custom-tooltip"
                                    id="customTooltip"
                                    data-text="حذف"
                                >
                                    <i class="material-symbols-outlined !text-md"> Delete </i>
                                    <span class="tooltip-text">حذف</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            #104
                        </td>
                        <td
                            class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[10px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            <div class="flex items-center gap-[10px]">
                                <div class="rounded-full w-[40px]">
                                    <img src="assets/images/users/user49.jpg" class="inline-block rounded-full"
                                         alt="تصویر محصول"/>
                                </div>
                                <div>
                                    <span class="font-medium inline-block mb-px"> دیوید اندرسون </span>
                                    <span
                                        class="block text-gray-500 dark:text-gray-400 text-xs"> david@gmail.com </span>
                                </div>
                            </div>
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            فیزیک
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            6دی
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            021-2546525
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            ۸۵٪ امتیاز کلی (B+)
                        </td>
                        <td
                            class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                    <span
                        class="px-[8px] py-[3px] inline-block font-medium bg-success-100 dark:bg-[#15203c] text-success-700 rounded-sm text-xs"
                    >
                      تصویب شد
                    </span>
                        </td>
                        <td
                            class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            <div class="flex items-center gap-[9px]">
                                <button
                                    type="button"
                                    class="text-primary-500 leading-none custom-tooltip"
                                    id="customTooltip"
                                    data-text="نمایش"
                                >
                                    <i class="material-symbols-outlined !text-md"> visibility </i>
                                    <span class="tooltip-text">مشاهده</span>
                                </button>
                                <button
                                    type="button"
                                    class="text-gray-500 dark:text-gray-400 leading-none custom-tooltip"
                                    id="customTooltip"
                                    data-text="ویرایش"
                                >
                                    <i class="material-symbols-outlined !text-md"> edit </i>
                                    <span class="tooltip-text">ویرایش</span>
                                </button>
                                <button
                                    type="button"
                                    class="text-danger-500 leading-none custom-tooltip"
                                    id="customTooltip"
                                    data-text="حذف"
                                >
                                    <i class="material-symbols-outlined !text-md"> Delete </i>
                                    <span class="tooltip-text">حذف</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            #105
                        </td>
                        <td
                            class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[10px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            <div class="flex items-center gap-[10px]">
                                <div class="rounded-full w-[40px]">
                                    <img src="assets/images/users/user50.jpg" class="inline-block rounded-full"
                                         alt="تصویر محصول"/>
                                </div>
                                <div>
                                    <span class="font-medium inline-block mb-px"> جسیکا مارتینز </span>
                                    <span
                                        class="block text-gray-500 dark:text-gray-400 text-xs"> jessica@gmail.com </span>
                                </div>
                            </div>
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            تاریخچه
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            7ب
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            021-2546525
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            ۲۵٪ کل (F)
                        </td>
                        <td
                            class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                    <span
                        class="px-[8px] py-[3px] inline-block font-medium bg-danger-100 dark:bg-[#15203c] text-danger-700 rounded-sm text-xs"
                    >
                      شکست
                    </span>
                        </td>
                        <td
                            class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            <div class="flex items-center gap-[9px]">
                                <button
                                    type="button"
                                    class="text-primary-500 leading-none custom-tooltip"
                                    id="customTooltip"
                                    data-text="نمایش"
                                >
                                    <i class="material-symbols-outlined !text-md"> visibility </i>
                                    <span class="tooltip-text">مشاهده</span>
                                </button>
                                <button
                                    type="button"
                                    class="text-gray-500 dark:text-gray-400 leading-none custom-tooltip"
                                    id="customTooltip"
                                    data-text="ویرایش"
                                >
                                    <i class="material-symbols-outlined !text-md"> edit </i>
                                    <span class="tooltip-text">ویرایش</span>
                                </button>
                                <button
                                    type="button"
                                    class="text-danger-500 leading-none custom-tooltip"
                                    id="customTooltip"
                                    data-text="حذف"
                                >
                                    <i class="material-symbols-outlined !text-md"> Delete </i>
                                    <span class="tooltip-text">حذف</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            #106
                        </td>
                        <td
                            class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[10px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            <div class="flex items-center gap-[10px]">
                                <div class="rounded-full w-[40px]">
                                    <img src="assets/images/users/user51.jpg" class="inline-block rounded-full"
                                         alt="تصویر محصول"/>
                                </div>
                                <div>
                                    <span class="font-medium inline-block mb-px"> جیمز لی </span>
                                    <span
                                        class="block text-gray-500 dark:text-gray-400 text-xs"> james@gmail.com </span>
                                </div>
                            </div>
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            زیست‌شناسی
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            5ب
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            021-2546525
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            ۸۸٪ امتیاز کلی (A)
                        </td>
                        <td
                            class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                    <span
                        class="px-[8px] py-[3px] inline-block font-medium bg-success-100 dark:bg-[#15203c] text-success-700 rounded-sm text-xs"
                    >
                      تصویب شد
                    </span>
                        </td>
                        <td
                            class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            <div class="flex items-center gap-[9px]">
                                <button
                                    type="button"
                                    class="text-primary-500 leading-none custom-tooltip"
                                    id="customTooltip"
                                    data-text="نمایش"
                                >
                                    <i class="material-symbols-outlined !text-md"> visibility </i>
                                    <span class="tooltip-text">مشاهده</span>
                                </button>
                                <button
                                    type="button"
                                    class="text-gray-500 dark:text-gray-400 leading-none custom-tooltip"
                                    id="customTooltip"
                                    data-text="ویرایش"
                                >
                                    <i class="material-symbols-outlined !text-md"> edit </i>
                                    <span class="tooltip-text">ویرایش</span>
                                </button>
                                <button
                                    type="button"
                                    class="text-danger-500 leading-none custom-tooltip"
                                    id="customTooltip"
                                    data-text="حذف"
                                >
                                    <i class="material-symbols-outlined !text-md"> Delete </i>
                                    <span class="tooltip-text">حذف</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            #107
                        </td>
                        <td
                            class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[10px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            <div class="flex items-center gap-[10px]">
                                <div class="rounded-full w-[40px]">
                                    <img src="assets/images/users/user52.jpg" class="inline-block rounded-full"
                                         alt="تصویر محصول"/>
                                </div>
                                <div>
                                    <span class="font-medium inline-block mb-px"> ایتان کلارک </span>
                                    <span
                                        class="block text-gray-500 dark:text-gray-400 text-xs"> ethan@gmail.com </span>
                                </div>
                            </div>
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            موسیقی
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            8ب
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            021-2546525
                        </td>
                        <td
                            class="font-medium ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            ۹۳٪ امتیاز کلی (A+)
                        </td>
                        <td
                            class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                    <span
                        class="px-[8px] py-[3px] inline-block font-medium bg-primary-100 dark:bg-[#15203c] text-primary-700 rounded-sm text-xs"
                    >
                      رها شده
                    </span>
                        </td>
                        <td
                            class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                        >
                            <div class="flex items-center gap-[9px]">
                                <button
                                    type="button"
                                    class="text-primary-500 leading-none custom-tooltip"
                                    id="customTooltip"
                                    data-text="نمایش"
                                >
                                    <i class="material-symbols-outlined !text-md"> visibility </i>
                                    <span class="tooltip-text">مشاهده</span>
                                </button>
                                <button
                                    type="button"
                                    class="text-gray-500 dark:text-gray-400 leading-none custom-tooltip"
                                    id="customTooltip"
                                    data-text="ویرایش"
                                >
                                    <i class="material-symbols-outlined !text-md"> edit </i>
                                    <span class="tooltip-text">ویرایش</span>
                                </button>
                                <button
                                    type="button"
                                    class="text-danger-500 leading-none custom-tooltip"
                                    id="customTooltip"
                                    data-text="حذف"
                                >
                                    <i class="material-symbols-outlined !text-md"> Delete </i>
                                    <span class="tooltip-text">حذف</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div id="noResultsMessage" class="hidden my-[10px] px-[20px] md:px-[25px]">نتیجه ای یافت نشد.</div>
            <div class="px-[20px] md:px-[25px] pt-[12px] md:pt-[14px] sm:flex sm:items-center justify-between">
                <p class="!mb-0 text-sm">نمایش ۷ از ۳۶ نتیجه</p>
                <ol class="mt-[10px] sm:mt-0" dir="ltr">
                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                        <a
                            href="javascript:void(0);"
                            class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500"
                        >
                            <span class="opacity-0"> 0 </span>
                            <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">
                                chevron_left
                            </i>
                        </a>
                    </li>
                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                        <a
                            href="javascript:void(0);"
                            class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-primary-500 bg-primary-500 text-white"
                        >
                            ۱
                        </a>
                    </li>
                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                        <a
                            href="javascript:void(0);"
                            class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500"
                        >
                            ۲
                        </a>
                    </li>
                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                        <a
                            href="javascript:void(0);"
                            class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500"
                        >
                            ۳
                        </a>
                    </li>
                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                        <a
                            href="javascript:void(0);"
                            class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500"
                        >
                            ۴
                        </a>
                    </li>
                    <li class="inline-block mx-[1px] ltr:first:ml-0 ltr:last:mr-0 rtl:first:mr-0 rtl:last:ml-0">
                        <a
                            href="javascript:void(0);"
                            class="w-[31px] h-[31px] block leading-[29px] relative text-center rounded-md border border-gray-100 dark:border-[#172036] transition-all hover:bg-primary-500 hover:text-white hover:border-primary-500"
                        >
                            <span class="opacity-0"> 0 </span>
                            <i class="material-symbols-outlined left-0 right-0 absolute top-1/2 -translate-y-1/2">
                                chevron_right
                            </i>
                        </a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>
