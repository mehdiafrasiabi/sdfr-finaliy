<div class="row">
    {{--form--}}
    @push('link')
        <link rel="stylesheet"
              href="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css"/>
    @endpush
    <div>
        {{--form--}}
        <div>
            <div>دانش آموز:</div>
            <h4 wire:ignore style="color: #29971d">{{$studentName}}</h4>

                <div class="grid lg:grid-cols-5 gap-[25px] mb-[25px]">
                    <div class="lg:col-span-3">
                        <!-- Recent Customer Ratings -->
                        <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                            <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                                <div class="trezo-card-title">
                                    <h5 class="!mb-0">افزودن گزارش تماس جدید</h5>
                                </div>
                                <div>
                                    <span wire:ignore style="color: #29971d">{{$studentName}}</span>
                                </div>
                            </div>
                            <div class="trezo-card-content">
                                <div class=" overflow-x-auto">
                                    <form wire:submit="submit(Object.fromEntries(new FormData($event.target)))">
                                        <div class="mb-[20px] md:mb-[25px] last:mb-0">
                                            <label class="mb-[12px] font-medium block">
                                                عنوان  :
                                            </label>
                                            <input type="text"
                                                   name="title"
                                                   wire:model="title"
                                                   class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500"
                                                   placeholder="کارنامه-آبان-1404">
                                            @error('title')
                                            <div class="text-[12px] font-medium text-orange-500  "
                                                 style="margin-top: 7px">{{$message}}</div>

                                            @enderror
                                        </div>
                                        <div class="mb-[20px] md:mb-[25px] last:mb-0">
                                            <label class="mb-[12px] font-medium block">
                                                توضیجات:
                                            </label>
                                            <textarea
                                                name="description"
                                                wire:model="description"
                                                type="text"
                                                class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500"
                                                placeholder=""></textarea>
                                            @error('description')
                                            <div class="text-[12px] font-medium text-orange-500  "
                                                 style="margin-top: 7px">{{$message}}</div>
                                            @enderror
                                        </div>

                                        <div class="mb-[20px] md:mb-[25px] last:mb-0">
                                            <label class="mb-[12px] font-medium block">
                                                توضیجات:
                                            </label>
                                            <select
                                                name="answer"
                                                wire:model="answer"
                                                type="file"
                                                class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500">
                                                <option value="mather">مادر</option>
                                                <option value="father">پدر</option>
                                                <option value="student">دانش‌آموز</option>
                                            </select>
                                            @error('answer')
                                            <div class="text-[12px] font-medium text-orange-500  "
                                                 style="margin-top: 7px">{{$message}}</div>
                                            @enderror
                                        </div>
                                        <div class="mb-[20px] md:mb-[25px] last:mb-0">
                                            <label class="mb-[12px] font-medium block">
                                                تاریخ تماس:
                                            </label>
                                            <textarea
                                                name="call_date"
                                                type="text" id="call_date" wire:model.lazy="call_date"
                                                class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500"
                                                placeholder=""></textarea>
                                            @error('call_date')
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
                    <div class="lg:col-span-2">
                        <!-- To Do List -->
                        <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                            <div class="trezo-card-header mb-[20px] md:mb-[25px] sm:flex items-center justify-between">
                                <div class="trezo-card-title">
                                    <h5 class="!mb-0">خروجی اکسل</h5>
                                </div>
                                <div class="trezo-card-subtitle mt-[15px] sm:mt-0">
                                    <span wire:ignore style="color: #29971d">{{$studentName}}</span>

                                </div>
                            </div>
                            <div class="trezo-card-content ">
                                <div class="table-responsive overflow-x-auto">
                                    <div class="mb-[20px] md:mb-[25px] last:mb-0">
                                        <label class="mb-[12px] font-medium block">
                                            از تاریخ (شمسی)
                                        </label>
                                        <input type="text"
                                               name="from"
                                               wire:model="from"
                                               class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500"
                                               placeholder="1404/05/24">
                                        @error('from')
                                        <div class="text-[12px] font-medium text-orange-500  "
                                             style="margin-top: 7px">{{$message}}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-[20px] md:mb-[25px] last:mb-0">
                                        <label class="mb-[12px] font-medium block">
                                            تا تاریخ (شمسی)

                                        </label>
                                        <input type="text"
                                               name="to"
                                               wire:model="to"
                                               class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500"
                                               placeholder="1404/06/24">
                                        @error('to')
                                        <div class="text-[12px] font-medium text-orange-500  "
                                             style="margin-top: 7px">{{$message}}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="px-[25px] pt-[12px] md:pt-[15px] ltr:text-right rtl:text-left">
                                    <button wire:click="exportExcel" type="button" wire:loading.attr="disabled"
                                            class="inline-block transition-all rounded-md font-medium px-[13px] py-[6px] text-primary-500 border border-primary-500 hover:bg-primary-500 hover:text-white"
                                            id="add-new-popup-toggle">
                                  <span class="inline-block relative ltr:pl-[22px] rtl:pr-[22px]">
                                      <i class="material-symbols-outlined !text-[22px] absolute ltr:-left-[4px] rtl:-right-[4px] top-1/2 -translate-y-1/2">
                                              add
                                      </i>
                                      <span wire:loading.remove>خروجی اکسل</span>
                                            <span wire:loading>در حال آماده‌سازی...</span>
                                  </span>
                                    </button>
                                </div>
                            </div>
                            <div class="mt-[15px] md:mt-[20px]"></div>
                        </div>
                    </div>
                </div>

        </div>
        {{--list--}}

            <div class="lg:col-span-2">
                <!-- Recent Leads -->
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                    <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                        <div class="trezo-card-title">
                            <h5 class="!mb-0">لیست برنامه های دانش آموز</h5>
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
                                        پاسخ دهدنده
                                    </th>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                        تاریخ تماس
                                    </th>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                        تاریخ بارگزاری
                                    </th>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                        عملیات
                                    </th>

                                </tr>
                                </thead>
                                <tbody class="text-black dark:text-white">
                                @forelse($ReportCallingStudent as $report)
                                    <tr>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                            {{$loop->iteration + $ReportCallingStudent->firstItem() - 1}}
                                        </td>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                            <div class="flex items-center">
                                                <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                    <span class="block font-medium">{{$report->title}}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                            <div class="flex items-center">
                                                <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                    <span class="block font-medium">{{$report->description}}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                            <div class="flex items-center">
                                                <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                    <span class="block font-medium">
                                                           {{ match($report->answer) {
                                                                          'mather' => 'مادر',
                                                                          'father' => 'پدر',
                                                                          'student' => 'دانش‌آموز',
                                                                          default => $report->answer,
                                                                      } }}
                                                    </span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                            {{jalali($report->created_at)->format('%d %B %Y | H:i:s')}}
                                        </td>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                            {{jalali($report->call_date)->format('%d %B %Y')}}
                                        </td>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                                <div class="flex items-center gap-[9px]">
                                                    <button
                                                        wire:confirm="آیا مطمئن هستید؟"
                                                        wire:click="delete({{$report->id}})"
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
                            {{ $ReportCallingStudent->links('layouts.admin.pagination') }}

                        </div>
                    </div>
                    <div class="mt-[15px] md:mt-[20px]"></div>

                </div>
            </div>
    </div>

@push('script')
        <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/persian-date/dist/persian-date.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const $input = $("#call_date");

                const picker = $input.persianDatepicker({
                    format: "YYYY/MM/DD",
                    initialValueType: 'gregorian',
                    calendarType: 'persian',
                    autoClose: true,
                    onSelect: function (unix) {
                        const pd = new persianDate(unix);
                        const formatted = pd.format('YYYY/MM/DD');

                        // نمایش در خود input
                        $input.val(formatted);

                        // پیدا کردن نزدیک‌ترین کامپوننت Livewire مربوط به این input
                        const livewireRoot = $input[0].closest('[wire\\:id]');
                        if (!livewireRoot) {
                            console.warn('کامپوننت Livewire پیدا نشد برای input تاریخ.');
                            return;
                        }

                        const wireId = livewireRoot.getAttribute('wire:id');
                        if (!wireId) {
                            console.warn('wire:id یافت نشد روی ریشه Livewire.');
                            return;
                        }

                        // ست کردن مستقیم پراپرتی
                        try {
                            const comp = window.Livewire.find(wireId);
                            if (comp && typeof comp.set === 'function') {
                                comp.set('call_date', formatted);
                                console.log('call_date به کامپوننت گزارش ست شد:', formatted);
                            } else {
                                console.warn('کامپوننت یا متد set پیدا نشد.');
                            }
                        } catch (e) {
                            console.error('خطا در یافتن/ست کردن Livewire component:', e);
                        }
                    }
                }).data('datepicker');
            });
        </script>
    @endpush


</div>
