<div class="col-md-12">
    @push('link')
        <style>
            .wrap-text {
                word-wrap: break-word;
                white-space: normal;
                overflow-wrap: break-word;
                max-width: 300px; /* یا هر عرضی که می‌خوای */
            }

        </style>
    @endpush


    <div class="grid lg:grid-cols-5 gap-[25px] mb-[25px]">
        <div class="lg:col-span-3">
            <!-- To Do List -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                    <div class="trezo-card-title">
                        <h5 class="!mb-0">خروجی اکسل</h5>
                    </div>
                </div>
                <div class="trezo-card-content">
                    <div>
                        <div class="mb-[20px] md:mb-[25px] last:mb-0">
                            <label class="mb-[12px] font-medium block">
                                از تاریخ (شمسی)
                            </label>
                            <select
                                wire:model.live.debounce.500ms="status"
                                class="h-[55px]  rounded-md border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[13px] block w-full outline-0 cursor-pointer transition-all focus:border-primary-500">
                                <option selected="">برای تغییر وضعیت انتخاب کنید</option>
                                <option value="all">همه وضعیت‌ها</option>
                                <option value="pending">در انتظار</option>
                                <option value="completed">تایید شده</option>
                                <option value="rejected">رد شده</option>
                            </select>

                        </div>
                        <div class="mb-[20px] md:mb-[25px] last:mb-0">
                            <label class="mb-[12px] font-medium block">
                                از تاریخ (شمسی)
                            </label>
                            <input type="text"
                                   name="startDate"
                                   wire:model="startDate"
                                   class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500"
                                   placeholder="1404/05/24">
                            @error('startDate')
                            <div class="text-[12px] font-medium text-orange-500  "
                                 style="margin-top: 7px">{{$message}}</div>
                            @enderror
                        </div>
                        <div class="mb-[20px] md:mb-[25px] last:mb-0">
                            <label class="mb-[12px] font-medium block">
                                تا تاریخ (شمسی)

                            </label>
                            <input type="text"
                                   name="endDate"
                                   wire:model="endDate"
                                   class="h-[55px] rounded-md text-black dark:text-white border border-gray-200 dark:border-[#172036] bg-white dark:bg-[#0c1427] px-[17px] block w-full outline-0 transition-all placeholder:text-gray-500 dark:placeholder:text-gray-400 focus:border-primary-500"
                                   placeholder="1404/06/24">
                            @error('endDate')
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

        <div class="lg:col-span-2">
            <!-- To Do List -->
            <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                <div class="trezo-card-header mb-[20px] md:mb-[25px] sm:flex items-center justify-between">
                    <div class="trezo-card-title">
                        <h5 class="!mb-0">دانش‌آموزان بدون گزارش در ۲۴ ساعت گذشته</h5>
                    </div>
                    <div class="trezo-card-subtitle mt-[15px] sm:mt-0">
                    </div>
                </div>
                <div class="trezo-card-content ">
                    <div class="table-responsive overflow-x-auto">
                        <div class="trezo-card bg-red-50 dark:bg-red-900 p-4 mb-4">
                            @if($studentsWithoutReports->isEmpty())
                                <p class="text-sm text-gray-700 dark:text-gray-200">همه دانش‌آموزان گزارش داده‌اند</p>
                            @else
                                @foreach($studentsWithoutReports as $student)
                                    <span style="margin-right: 10px"
                                          class="px-[8px] py-[3px] inline-block bg-primary-50 dark:bg-[#15203c] text-primary-500 rounded-sm font-medium text-xs">
                                              {{ $student->user->name }}
                                            </span>
                                @endforeach
                            @endif

                        </div>
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
                    <h5 class="!mb-0">لیست گزارش های دانش آموز</h5>
                </div>

                <div class="trezo-card-subtitle mt-[15px] sm:mt-0">
                    <button
                        wire:click="bulkAction('completed')"
                        class="inline-block py-[10px] px-[30px] text-success-500 transition-all rounded-md border border-success-500 hover:border-success-500 hover:bg-success-500 hover:text-white ltr:mr-[11px] rtl:ml-[11px] mb-[15px]"
                        type="button">
                        تایید گزارش
                    </button>
                    <button
                        wire:click="bulkAction('rejected')"
                        class="inline-block py-[10px] px-[30px] text-danger-500 transition-all rounded-md border border-danger-500 hover:border-danger-500 hover:bg-danger-500 hover:text-white ltr:mr-[11px] rtl:ml-[11px] mb-[15px]"
                        type="button">
                        رد گزارش
                    </button>

                </div>
            </div>
            <div class="flex gap-2 mb-3">


            </div>
            <div class="trezo-card-content -mx-[20px] md:-mx-[25px]">
                <div class="table-responsive overflow-x-auto">
                    <table class="w-full">
                        <thead class="text-black dark:text-white">
                        <tr>

                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">

                                <div class="form-check relative top-[2px]">
                                    <input wire:model.live="selectAll" type="checkbox" class="cursor-pointer">
                                </div>
                            </th>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                #
                            </th>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                دانش آموز
                            </th>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                پارت های موظفی
                            </th>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                تست  موظفی
                            </th>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                تست  انجام شده
                            </th>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                 درگیر با گوشی (درسی)
                            </th>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                 درگیر با گوشی (غیر درسی)
                            </th>

                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                توضیحات
                            </th>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                رضایت
                            </th>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                فایل
                            </th>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                وضعیت
                            </th>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                تاریخ ثبت درخواست
                            </th>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                تاریخ تغییر وضعیت
                            </th>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                عملیات
                            </th>
                        </tr>
                        </thead>
                        <tbody class="text-black dark:text-white">
                        @forelse($reports as $report)
                            <tr>
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                    <div class="form-check relative top-[2px]">
                                        <input wire:model.live="selectedReports"
                                               value="{{ $report->id }}"
                                               type="checkbox" class="cursor-pointer">
                                    </div>

                                </td>
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                    {{$loop->iteration + $reports->firstItem() - 1}}
                                </td>
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                            <span
                                                class="px-[8px] py-[3px] inline-block bg-primary-50 dark:bg-[#15203c] text-primary-500 rounded-sm font-medium text-xs">
                                                {{ $report->student->user->name ?? '----' }}
                                            </span>
                                </td>
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                    <div class="flex items-center">
                                        <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                    <span
                                                        class="block font-medium">{{ $report->required_parts ?? '---' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                    <div class="flex items-center">
                                        <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                    <span
                                                        class="block font-medium">{{ $report->required_tests ?? '---' }}</span>
                                        </div>

                                    </div>
                                </td>
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                    <div class="flex items-center">
                                        <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                    <span
                                                        class="block font-medium">{{ $report->done_tests ?? '---' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                    <div class="flex items-center">
                                        <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                    <span
                                                        class="block font-medium">{{ $report->phone_study_hours ?? '---' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                    <div class="flex items-center">
                                        <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                    <span
                                                        class="block font-medium">{{ $report->phone_nonstudy_hours ?? '---' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                    <div class="flex items-center">
                                        <div class="ltr:ml-[12px] rtl:mr-[12px]" x-data="{ open: false }">
                                            @php
                                                $description = $report->description ?? '';
                                                $words = explode(' ', $description);
                                                $firstPart = implode(' ', array_slice($words, 0, 10));
                                                $restPart = implode(' ', array_slice($words, 10));
                                            @endphp

                                            <span class="block font-medium">
                                                            {{ $firstPart }}
                                                    </span>

                                            @if (!empty($restPart))
                                                <template x-if="!open">
                                                    <div
                                                        style="cursor: pointer"
                                                        @click="open = true"
                                                        class="text- hover:underline transition-all duration-200">
                                                        بیشتر...
                                                    </div>

                                                </template>

                                                <template x-if="open">
                                                    <div>
                                                        <p x-transition.opacity.duration.300ms class="mt-2 wrap-text">
                                                            {{ $restPart }}
                                                        </p>
                                                        <button
                                                            style="cursor: pointer"
                                                            @click="open = false"
                                                            class="text-red-500 hover:underline transition-all duration-200 mt-1">
                                                            بستن
                                                        </button>
                                                    </div>
                                                </template>
                                            @endif
                                        </div>
                                    </div>
                                </td>

                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                    <div class="flex items-center">
                                        <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                    <span class="block font-medium">
                                                          @if($report->complacent == 1)
                                                            <span
                                                                class="px-[8px] py-[3px] inline-block bg-success-50 dark:bg-[#15203c] text-success-600 rounded-sm font-medium text-xs">
                                                                راضی ام
                                                            </span>
                                                        @elseif($report->complacent ==0)
                                                            <span
                                                                class="px-[8px] py-[3px] inline-block bg-danger-50 dark:bg-[#15203c] text-danger-500 rounded-sm font-medium text-xs">
                                                                      راضی نیستم
                                                            </span>
                                                        @endif
                                                    </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                    <div class="flex items-center">
                                        <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                    <span class="block font-medium">
                                                        @if(isset($report->report_file))
                                                            <a href="{{asset('students/reportsDaily/'.$report->student_id).'/'.$report->report_file}}">مشاهده</a>
                                                        @else
                                                            <span
                                                                class=" px-[8px] py-[3px] inline-block bg-warning-50 dark:bg-[#15203c] text-warning-700 rounded-sm font-medium text-xs">
                                                                        فایلی وجود ندارد
                                                            </span>

                                                        @endif
                                                    </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                    @can('edit_reports_for_academic_support')
                                        <select
                                            wire:confirm="آیا از انتخاب خود برای تغییر وضعیت اطمینان دارید ؟"
                                            wire:change="changeStatus({{$report->id}},$event.target.value)"
                                            class="
                                                     px-[8px] py-[3px] inline-block bg-{{$report->statusColor}} success-100 dark:bg-[#15203c] text-{{$report->statusColor}} rounded-sm font-medium text-xs
                                                     inv-status">
                                            <option value="pending" {{$report->status=='pending' ? 'selected' :''}}>
                                                درانتظار تایید مشاور یا پشتیبان
                                            </option>
                                            <option
                                                value="completed" {{$report->status=='completed' ? 'selected' :''}}>
                                                تایید گزارش و درست بودن ان

                                            </option>
                                            <option
                                                value="rejected" {{$report->status=='rejected' ? 'selected' :''}}>
                                                رد گزارش

                                            </option>
                                        </select>
                                    @else
                                        @if($report->status=='pending')
                                            <span
                                                class="px-[8px] py-[3px] inline-block bg-primary-50 dark:bg-[#15203c] text-primary-500 rounded-sm font-medium text-xs">
                                                                    در انتظار تایید مشاور یا پشتیبان
                                                    </span>
                                        @elseif($report->status=='completed')
                                            <span
                                                class="px-[8px] py-[3px] inline-block bg-success-50 dark:bg-[#15203c] text-success-600 rounded-sm font-medium text-xs">
                                                               توسط مشاور یا پشتیبان گزارش تایید  شده است
                                                    </span>
                                        @elseif($report->status=='rejected')
                                            <span
                                                class="px-[8px] py-[3px] inline-block bg-danger-50 dark:bg-[#15203c] text-danger-500 rounded-sm font-medium text-xs">
                                                                     به صلاح دید مشاور یا پشتیبان  رد شده است
                                                    </span>
                                        @endif

                                    @endcan

                                </td>
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                    {{jalali($report->created_at)->format('%d %B %Y | H:i:s')}}
                                </td>
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                    {{jalali($report->updated_at)->format('%d %B %Y | H:i:s')}}
                                </td>
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                    @can('delete_reports_for_academic_support')

                                        <button
                                            wire:confirm="آیا مطمئن هستید؟"
                                            wire:click="delete({{$report->id}})"
                                            type="button" class="text-danger-500 leading-none">
                                            <i class="material-symbols-outlined !text-md"> delete </i>
                                        </button>

                                    @else
                                        <span
                                            class="px-[8px] py-[3px] inline-block bg-danger-50 dark:bg-[#15203c] text-danger-500 rounded-sm font-medium text-xs">
                                                     <i class="material-symbols-outlined !text-md"> delete </i>
                                                    عدم دسترسی حذف!!
                                                </span>
                                    @endcan
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
                <div
                    class="px-[20px] md:px-[25px] pt-[12px] md:pt-[14px] sm:flex sm:items-center justify-between">
                    {{ $reports->links('layouts.admin.pagination') }}

                </div>
            </div>
            <div class="mt-[15px] md:mt-[20px]"></div>

        </div>
    </div>


</div>
