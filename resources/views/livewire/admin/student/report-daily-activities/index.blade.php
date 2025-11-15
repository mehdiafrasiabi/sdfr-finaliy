<div>




        <div class="lg:col-span-2">
            <!-- Recent Leads -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                    <div class="trezo-card-title">
                        <h5 class="!mb-0">ریز گزارش روزانه دانش آموز</h5>
                    </div>

                    <div class="trezo-card-subtitle sm:flex sm:items-center">
                        <form class="relative sm:w-[240px] ltr:sm:mr-[20px] rtl:sm:ml-[20px] my-[13px] sm:my-0">
                            <label
                                class="leading-none absolute ltr:left-[13px] rtl:right-[13px] text-black dark:text-white mt-px top-1/2 -translate-y-1/2">
                                <i class="material-symbols-outlined !text-[20px]"> search </i>
                            </label>
                            <input type="text" placeholder="جستجو....."
                                   wire:model.live.debounce.350ms="search"
                                   class="bg-gray-50 border border-gray-50 h-[36px] text-xs rounded-md w-full block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] ltr:md:pr-[16px] rtl:pl-[13px] rtl:md:pl-[16px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400">
                        </form>
                        <div class="trezo-card-dropdown relative">
                            {{--                            <a href="{{ route('admin.blog.create') }}"--}}
                            {{--                               class="text-white trezo-card-dropdown-btn inline-block bg-secondary-500 rounded-md border border-gray-100 py-[5px] md:py-[6.5px] px-[12px] md:px-[19px] transition-all hover:bg-secondary-400 dark:border-[#172036] dark:hover:bg-[#0a0e19]">--}}
                            {{--                                افزودن بلاگ جدید--}}
                            {{--                            </a>--}}
                        </div>
                    </div>
                </div>
                <div class="trezo-card-content -mx-[20px] md:-mx-[25px]">
                    <div class="table-responsive overflow-x-auto">
                        <table class="w-full">
                            <thead class="text-black dark:text-white">
                            <tr>

                                <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                    #
                                </th>
                                <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                    دانش آموز
                                </th>
                                <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                    تلفن همراه
                                </th>
                                <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                    پایه
                                </th>
                                <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                    رشته
                                </th>

                                <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                    عملیات
                                </th>

                                <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                    آخرین نظر دانش‌آموز
                                </th>

                            </tr>
                            </thead>
                            <tbody class="text-black dark:text-white">
                            @forelse($students as $student)
                                <tr>
                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                        {{$loop->iteration + $students->firstItem() - 1}}
                                    </td>
                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                        <div class="flex items-center">
                                            <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                <span
                                                    class="block font-medium">{{$student->user->personalInformation->name }}</span>
                                            </div>
                                            @if(!empty($student->unread_student_replies_count))
                                                <span class="relative inline-flex items-center justify-center rounded-full h-5 w-5 bg-primary text-primary-foreground font-bold text-xs">
                                                    {{ $student->unread_student_replies_count }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                        {{$student->payment->order->user->mobile}}
                                    </td>
                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                        @if($student->user->personalInformation->grade == 12)
                                            دوازدهم
                                        @elseif($student->user->personalInformation->grade == 11)
                                            یازدهم
                                        @elseif($student->user->personalInformation->grade == 10)
                                            دهم
                                        @endif

                                    </td>
                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                        @if($student->user->personalInformation->field == 'math')
                                            ریاضی
                                        @elseif($student->user->personalInformation->field == 'experimental')
                                            تجربی
                                        @elseif($student->user->personalInformation->field == 'human')
                                            انسانی
                                        @endif
                                    </td>
                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                        <div class="flex items-center gap-[9px]">
                                            <a href="{{route('admin.student.reportDailyActivities.detail',$student->payment->order->user->id)}}"
                                               class="text-gray-500 dark:text-gray-400 leading-none">
                                                <i class="material-symbols-outlined !text-md">edit</i>
                                            </a>
                                        </div>
                                    </td>
                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                        @php
                                            $latestReplyAt = $student->latest_student_reply_at ? \Illuminate\Support\Carbon::parse($student->latest_student_reply_at) : null;
                                        @endphp
                                        @if($latestReplyAt)
                                            {{ \Morilog\Jalali\Jalalian::fromCarbon($latestReplyAt)->format('Y/m/d | H:i') }}
                                        @else
                                            ---
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">

                                    <div class="text-center">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop"
                                                   colors="primary:#121331,secondary:#08a88a"
                                                   style="width:75px;height:75px"></lord-icon>
                                        <h5 class="mt-2">متاسفیم! هیچ نتیجه ای یافت نشد</h5>

                                    </div>
                                </td>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-[20px] md:px-[25px] pt-[12px] md:pt-[14px] sm:flex sm:items-center justify-between">
                        {{ $students->links('layouts.admin.pagination') }}

                    </div>
                </div>
                <div class="mt-[15px] md:mt-[20px]"></div>

            </div>
        </div>


</div>
