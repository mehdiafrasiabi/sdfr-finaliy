<div class="row">
    {{--form--}}
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
    <div>
            <h4 wire:ignore style="color: #29971d">{{$studentName}}</h4>

            <div class="grid lg:grid-cols-5 gap-[25px] mb-[25px]">
                <div class="lg:col-span-2">
                    <!-- Recent Customer Ratings -->
                    <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                        <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                            <div class="trezo-card-title">
                                <h5 class="!mb-0">برگزاری کارگاه آموزشی</h5>
                            </div>
                            <div>
                                <span wire:ignore style="color: #29971d">{{$studentName}}</span>
                            </div>
                        </div>
                        <div class="trezo-card-content ">
                            <div class=" overflow-x-auto">
                                <form wire:submit="submit">
                                    <div class="mb-[20px] md:mb-[25px] last:mb-0">
                                        <label class="mb-[12px] font-medium block">
                                            عنوان  :
                                        </label>
                                        <input type="text"
                                               name="title"
                                               wire:model="title"
                                               class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500"
                                               placeholder="آموزش تست زنی">
                                        @error('title')
                                        <div class="text-[12px] font-medium text-orange-500  "
                                             style="margin-top: 7px">{{$message}}</div>

                                        @enderror
                                    </div>
                                    <div class="mb-[20px] md:mb-[25px] last:mb-0">
                                        <label class="mb-[12px] font-medium block">
                                             توضیحات :
                                        </label>
                                        <input
                                            name="body"
                                            wire:model="body"
                                            type="text"
                                            class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500"
                                            placeholder="">
                                        @error('body')
                                        <div class="text-[12px] font-medium text-orange-500  "
                                             style="margin-top: 7px">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-[20px] md:mb-[25px] last:mb-0">
                                        <label class="mb-[12px] font-medium block">
                                             ادرس محل برگزاری :
                                        </label>
                                        <input
                                            name="link"
                                            wire:model="link"
                                            type="text"
                                            class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500"
                                            placeholder="">
                                        @error('link')
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
                            <h5 class="!mb-0">لیست کارگاه های دانش آموز</h5>
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
                                        عنوان
                                    </th>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                        توضیحات
                                    </th>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                       لینک
                                    </th>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                        وضعیت مشاهده
                                    </th>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                        تاریخ اخرین تغییرات
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
                                @forelse($meets as $meet)
                                    <tr>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                            {{$loop->iteration + $meets->firstItem() - 1}}
                                        </td>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                            <div class="flex items-center">
                                                <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                    <span class="block font-medium">{{$meet->title}}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                           {{$meet->body}}
                                        </td>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                            <a href="{{$meet->link}}"
                                               class="text-secondary">
                                                مشاهده</a>
                                        </td>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                            <button
                                                    wire:click="toggleActive({{ $meet->id }})"
                                                    class="btn btn-sm me-2"
                                                    title="{{ $meet->is_active ? 'غیرفعال کن' : 'فعال کن' }}">
                                                @if($meet->is_active)
                                                    <button class="inline-block py-[10px] px-[30px] text-success-500 transition-all rounded-md border border-success-500 hover:border-success-500 hover:bg-success-500 hover:text-white ltr:mr-[11px] rtl:ml-[11px] mb-[15px]" type="button">
                                                       فعال
                                                    </button>
                                                @else
                                                    <button class="inline-block py-[10px] px-[30px] text-danger-500 transition-all rounded-md border border-danger-500 hover:border-danger-500 hover:bg-danger-500 hover:text-white ltr:mr-[11px] rtl:ml-[11px] mb-[15px]" type="button">
                                                        غیرفعال
                                                    </button>
                                                @endif
                                            </button>

                                        </td>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                            {{jalali($meet->updated_at)->format('%d %B %Y | H:i:s')}}
                                        </td>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                            {{jalali($meet->created_at)->format('%d %B %Y | H:i:s')}}
                                        </td>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                            <div class="flex items-center gap-[9px]">
                                                <button
                                                    wire:confirm="آیا مطمئن هستید؟"
                                                    wire:click="delete({{$meet->id}})"
                                                    type="button" class="text-danger-500 leading-none">
                                                    <i class="material-symbols-outlined !text-md"> delete </i>
                                                </button>
                                            </div>
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
                            {{ $meets->links('layouts.admin.pagination') }}

                        </div>
                    </div>
                    <div class="mt-[15px] md:mt-[20px]"></div>
                </div>
            </div>
    </div>

</div>
