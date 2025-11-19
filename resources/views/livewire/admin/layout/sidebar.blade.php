<div>
    <div
        class="logo bg-white dark:bg-[#0c1427] border-b border-gray-100 dark:border-[#172036] px-[25px] pt-[19px] pb-[15px] absolute z-[2] right-0 top-0 left-0">
        <a href="{{route('admin.dashboard.index')}}" class="transition-none relative flex items-center">
            <img src="/admin/assets/images/logo-icon.svg" alt="logo-icon"/>
            <span class="font-bold text-black dark:text-white relative ltr:ml-[8px] rtl:mr-[8px] top-px text-xl">
            مدیران
          </span>
        </a>
        <button
            type="button"
            class="burger-menu inline-block absolute z-[3] top-[24px] ltr:right-[25px] rtl:left-[25px] transition-all hover:text-primary-500"
            id="hide-sidebar-toggle2"
        >
            <i class="material-symbols-outlined"> بسته </i>
        </button>
    </div>

    <div class="pt-[89px] px-[25px] pb-[20px] h-screen" data-simplebar>
        <div class="accordion">
              <span
                  class="block relative font-medium uppercase text-gray-400 mb-[10px] text-xs [&:not(:first-child)]:mt-[22px]">
            داشبورد کل
          </span>
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <a
                    href="{{route('admin.dashboard.index')}}"
                    class="accordion-button flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        dashboard
                    </i>
                    <span class="title leading-none">پیشخوان</span>
                </a>
            </div>
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <a
                    href="{{route('admin.todo')}}"
                    class="accordion-button flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        team_dashboard
                    </i>
                    <span class="title leading-none">لیست وظیفه</span>
                </a>
            </div>


            <span
                class="block relative font-medium uppercase text-gray-400 mb-[10px] text-xs [&:not(:first-child)]:mt-[22px]">
            خدمات
          </span>

            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <button
                    class="accordion-button toggle flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]"
                    type="button"
                >
                    <i
                        class="material-symbols-outlined ri-graduation-cap-line transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px"
                    >
                    </i>
                    <span class="title leading-none">دانش آموزان</span>
                </button>
                <div class="accordion-collapse hidden">
                    <div class="pt-[4px]">
                        <ul class="sidebar-sub-menu">
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a
                                    href="{{route('admin.student.index')}}"
                                    class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]"
                                >
                                    کل دانش آموزان
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0 ">
                                <a
                                    href="{{route('admin.student.plan.index')}}"
                                    class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c] "
                                >
                                    برنامه درسی
                                </a>
                            </li>

                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a
                                    href="{{route('admin.student.reportStudent.index')}}"
                                    class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]"
                                >
                                    کارنامه وضعیت
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a
                                    href="{{route('admin.student.studySession.index')}}"
                                    class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]"
                                >
                                    میزان مطالعه دانش آموزان
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a
                                    href="{{route('admin.advising-sessions')}}"
                                    class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]"
                                >
                                    جلسه مشاوره
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <button
                    class="accordion-button toggle flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]"
                    type="button"
                >
                    <i
                        class="material-symbols-outlined ri-file-chart-line transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px"
                    >

                    </i>
                    <span class="title leading-none">گزارش درسی</span>
                </button>
                <div class="accordion-collapse hidden">
                    <div class="pt-[4px]">
                        <ul class="sidebar-sub-menu">
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a
                                    href="{{route('admin.student.reportDailyActivities.index')}}"
                                    class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]"
                                >
                                    گزارش جامع
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a
                                    href="{{ route('admin.reportStudentDay', ['status' => 'pending']) }}"
                                    class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[##3cb371]"
                                >
                                    در انتظار تایید
                                </a>
                            </li>
                            <li class="sidemenu-item mb-[4px] last:mb-0">
                                <a
                                    href="{{ route('admin.reportMissing') }}"
                                    class="sidemenu-link rounded-md flex items-center relative transition-all font-medium text-gray-500 dark:text-gray-400 py-[9px] ltr:pl-[38px] ltr:pr-[30px] rtl:pr-[38px] rtl:pl-[30px] hover:text-primary-500 hover:bg-primary-50 w-full text-left dark:hover:bg-[#15203c]"
                                >
                                     ارسال نشده
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <a
                    href="{{route('admin.student.exam.index')}}"
                    class="accordion-button flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]"
                >
                    <i
                        class="material-symbols-outlined ri-contract-line transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px"
                    >

                    </i>
                    <span class="title leading-none">برگزاری آزمون</span>
                </a>
            </div>


            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <a
                    href="{{route('admin.student.notification')}}"
                    class="accordion-button flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]"
                >
                    <i
                        class="material-symbols-outlined ri-arrow-right-up-line transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px"
                    >
                    </i>
                    <span class="title leading-none">پیام به دانش آموز</span>
                </a>
            </div>
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <a
                    href="{{route('admin.student.reportCalling.index')}}"
                    class="accordion-button flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]"
                >
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        emoji_emotions
                    </i>


                    <span class="title leading-none">ثبت مستندات تماس</span>
                </a>
            </div>
            <span
                class="block relative font-medium uppercase text-gray-400 mb-[10px] text-xs [&:not(:first-child)]:mt-[22px]">
           اضافه بر سازمان
          </span>
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <a
                    href="{{route('admin.blog.index')}}"
                    class="accordion-button flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]"
                >
                    <i
                        class="material-symbols-outlined ri-news-line transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px"
                    >

                    </i>
                    <span class="title leading-none">بلاگ </span>
                </a>
            </div>
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <a
                    href="{{route('admin.contact-us')}}"
                    class="accordion-button flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]"
                >
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        contact_page
                    </i>

                    <span class="title leading-none">درخواست کاربران </span>
                </a>
            </div>

            <span
                class="block relative font-medium uppercase text-gray-400 mb-[10px] text-xs [&:not(:first-child)]:mt-[22px]">حساب کاربری</span>
            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <a
                    href="{{route('admin.profile')}}"
                    class="accordion-button flex items-center transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]">
                    <i class="material-symbols-outlined transition-all text-gray-500 dark:text-gray-400 ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px">
                        account_circle
                    </i>

                    <span class="title leading-none">پروفایل من </span>
                </a>
            </div>

            <div class="accordion-item rounded-md text-black dark:text-white mb-[5px] whitespace-nowrap">
                <a
                    href="{{route('admin.logout')}}"
                    class="accordion-button flex items-center bg-orange-500 transition-all py-[9px] ltr:pl-[14px] ltr:pr-[28px] rtl:pr-[14px] rtl:pl-[28px] rounded-md font-medium w-full relative hover:bg-gray-50 text-left dark:hover:bg-[#15203c]"
                >
                    <i
                        class="material-symbols-outlined transition-all text-white dark:text-white ltr:mr-[7px] rtl:ml-[7px] !text-[22px] leading-none relative -top-px"
                    >
                        logout
                    </i>
                    <span class="title leading-none text-white"> خروج از حساب </span>
                </a>
            </div>
        </div>
    </div>
</div>
