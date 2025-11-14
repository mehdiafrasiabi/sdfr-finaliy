<div class="row">
    @push('link')
        <style>
            .wrap-text {
                word-wrap: break-word;
                white-space: normal;
                overflow-wrap: break-word;
                max-width: 500px; /* یا هر عرضی که می‌خوای */
            }
        </style>
    @endpush
        <div class="grid lg:grid-cols-5 gap-[25px] mb-[25px]">
            <div class="lg:col-span-2">
                <!-- Recent Customer Ratings -->
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                    <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                        <div class="trezo-card-title">
                            <h5 class="!mb-0"> اطلاع رسانی</h5>
                        </div>
                    </div>
                    <div class="trezo-card-content ">
                        <div class=" overflow-x-auto">
                            <form wire:submit.prevent="send">
                                <div class="mb-[20px] md:mb-[25px] last:mb-0">
                                    <label class="mb-[12px] font-medium block">
                                        عنوان اعلان :
                                    </label>
                                    <input type="text"
                                           name="title"
                                           wire:model="title"
                                           class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500"
                                           placeholder="اقساط مهر">
                                    @error('title')
                                    <div class="text-[12px] font-medium text-orange-500  "
                                         style="margin-top: 7px">{{$message}}</div>

                                    @enderror
                                </div>
                                <div class="mb-[20px] md:mb-[25px] last:mb-0">
                                    <label class="mb-[12px] font-medium block">
                                        توضیحات:
                                    </label>
                                    <textarea
                                        rows="4"
                                        name="body"
                                        wire:model="body"
                                        class="h-[140px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] p-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500" placeholder="این باعث میشه حس کنم..."></textarea>
                                    @error('body')
                                    <div class="text-[12px] font-medium text-orange-500  "
                                         style="margin-top: 7px">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="mb-[20px] md:mb-[25px] last:mb-0">
                                    <div class="col-sm-12">
                                        <label class="form-label">ارسال به:</label>
                                        <select
                                            wire:model="studentId"
                                            class="h-[55px] rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[13px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500">
                                            <option value="">همه دانش‌آموزها</option>
                                            @foreach($notifications as $notifStudent)
                                                <option value="{{ $notifStudent->id }}">
                                                    {{ $notifStudent->student?->personalInformation?->name ?? 'نامشخص' }}

                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @error('studentId')
                                    <div class="text-[12px] font-medium text-orange-500  "
                                         style="margin-top: 7px">{{$message}}</div>
                                    @enderror
                                </div>
                                <a href="{{route('admin.student.index')}}"
                                   class="inline-block py-[10px] px-[30px] bg-danger-500 text-white transition-all hover:bg-danger-400 rounded-md border border-danger-500 hover:border-danger-400 ltr:mr-[11px] rtl:ml-[11px] mb-[15px]"
                                   type="button">
                                    خروج
                                </a>
                                <button
                                    type="submit"
                                    class="inline-block py-[10px] px-[30px] bg-success-500 text-white transition-all hover:bg-success-400 rounded-md border border-success-500 hover:border-success-400 ltr:mr-[11px] rtl:ml-[11px] mb-[15px]">
                                <span class="flex items-center justify-center gap-[5px]" wire:loading.remove>
                              ثبت و ارسال
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
                    <div class="mt-[15px] md:mt-[20px]"></div>

                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <!-- Recent Leads -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                    <div class="trezo-card-title">
                        <h5 class="!mb-0"> اطلاع رسانی   </h5>
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
                                    عنوان
                                </th>
                                <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                    متن
                                </th>
                                <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                    تاریخ ثبت
                                </th>
                                <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                    عملیات
                                </th>


                            </tr>
                            </thead>
                            <tbody class="text-black dark:text-white">
                            @forelse($notifications as $notif)
                                <tr>
                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                        {{$loop->iteration + $notifications->firstItem() - 1}}
                                    </td>

                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                        @if($notif->student)
                                            {{ $notif->student->personalInformation->name }}
                                        @else
                                            همه
                                        @endif
                                    </td>
                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                        <p class="wrap-text">
                                            {{ $notif->title }}
                                        </p>
                                    </td>
                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                        <p class="wrap-text">{{ $notif->body }}</p>
                                    </td>
                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                        {{jalali($notif->created_at)->format('%d %B %Y | H:i')}}
                                    </td>
                                    <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                        delete
                                    </td>
                                </tr>
                            @empty
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">

                                    <div class="text-center">
                                        <lord-icon src="https://cdn.lordicon.com/msoeawqm.json" trigger="loop" colors="primary:#121331,secondary:#08a88a" style="width:75px;height:75px"></lord-icon>
                                        <h5 class="mt-2">متاسفیم! هیچ نتیجه ای یافت نشد</h5>

                                    </div>
                                </td>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="px-[20px] md:px-[25px] pt-[12px] md:pt-[14px] sm:flex sm:items-center justify-between">
                        {{ $notifications->links('layouts.admin.pagination') }}

                    </div>
                </div>
                <div class="mt-[15px] md:mt-[20px]"></div>

            </div>
        </div>


</div>
