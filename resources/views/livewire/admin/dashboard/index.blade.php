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
                                    <span class="block"> دانش آموزان </span>
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
                                    <span class="block">دانش آموزان تحت پشتیبان </span>
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
                                    <span class="block"> دانش آموزان تحت مشاور  </span>
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

</div>
