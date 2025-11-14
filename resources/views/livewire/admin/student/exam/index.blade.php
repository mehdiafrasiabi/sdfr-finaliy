<div>
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
    @canany(['view_exams_for_academic_support','view exams'])
        @if (session()->has('success'))
            <div
                class="alert py-[1rem] px-[1rem] text-success-500 bg-success-50 border border-success-200 dark:bg-[#15203c] dark:border-[#15203c] rounded-md flex items-center justify-between"
                id="dismissingAlert">
                {{ session('success') }}
                <button class="leading-none text-[20px] close-btn">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <br>
        @endif
        @if (session()->has('error'))
            <div
                class="alert py-[1rem] px-[1rem] text-danger-500 bg-danger-50 border border-danger-200 dark:bg-[#15203c] dark:border-[#15203c] rounded-md flex items-center justify-between"
                id="dismissingAlert">
                {{ session('error') }}
                <button class="leading-none text-[20px] close-btn">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <br>
        @endif
        <div>

            <div class="lg:col-span-2">
                <!-- Recent Leads -->
                <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                    <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                        <div class="trezo-card-title">
                            <h5 class="!mb-0">آزمون ها</h5>
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
                                        عنوان
                                    </th>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                        سطح
                                    </th>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                        وضعیت
                                    </th>
                                    <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                        عملیات
                                    </th>

                                </tr>
                                </thead>
                                <tbody class="text-black dark:text-white">
                                @forelse($exams as $exam)
                                    <tr>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                            {{$loop->iteration + $exams->firstItem() - 1}}
                                        </td>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                            <div class="flex items-center">
                                                <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                    <span class="block font-medium">{{ $exam->title }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                            {{ __('exam_levels.' . $exam->level) }}
                                        </td>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                            @if ($exam->is_active)
                                                <button
                                                    class="inline-block py-[10px] px-[30px] bg-success-500 text-white transition-all hover:bg-success-400 rounded-md border border-success-500 hover:border-success-400 ltr:mr-[11px] rtl:ml-[11px] mb-[15px]"
                                                    type="button">
                                                    فعال
                                                </button>
                                            @else
                                                <button
                                                    class="inline-block py-[10px] px-[30px] bg-danger-500 text-white transition-all hover:bg-danger-400 rounded-md border border-danger-500 hover:border-danger-400 ltr:mr-[11px] rtl:ml-[11px] mb-[15px]"
                                                    type="button">
                                                    غیرفعال
                                                </button>
                                            @endif
                                        </td>
                                        <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                            {{--                                                <div class="flex items-center gap-[9px]">--}}
                                            {{--                                                    <a href="{{route('admin.student.plan.detail',$exam->payment->order->user->id)}}" class="text-gray-500 dark:text-gray-400 leading-none">--}}
                                            {{--                                                        <i class="material-symbols-outlined !text-md">edit</i>--}}
                                            {{--                                                    </a>--}}
                                            {{--                                                </div>--}}
                                            @if (!$exam->is_active)

                                                <button
                                                    wire:confirm="آیا از فعال‌سازی این آزمون مطمئن هستید؟"
                                                    wire:click="activateExam({{ $exam->id }})"
                                                    class="inline-block py-[10px] px-[30px] bg-success-500 text-white transition-all hover:bg-success-400 rounded-md border border-success-500 hover:border-success-400 ltr:mr-[11px] rtl:ml-[11px] mb-[15px]"
                                                    type="button">
                                                    فعال‌سازی
                                                </button>

                                            @endif
                                            @canany(['create_exams_for_academic_support',])

                                                <button
                                                    wire:click="openAssignStudentsModal({{ $exam->id }})"

                                                    class="inline-block py-[10px] px-[30px] bg-secondary-500 text-white transition-all hover:bg-secondary-400 rounded-md border border-secondary-500 hover:border-secondary-400 ltr:mr-[11px] rtl:ml-[11px] mb-[15px]"
                                                    type="button">
                                                    اختصاص دانش‌آموز
                                                </button>
                                            @else
                                                <button
                                                    class="inline-block py-[10px] px-[30px] bg-primary-500 text-white transition-all hover:bg-primary-400 rounded-md border border-primary-500 hover:border-primary-400 ltr:mr-[11px] rtl:ml-[11px] mb-[15px] disabled:opacity-[.5] disabled:pointer-events-none"
                                                    type="button" disabled="">
                                                    عدم دسترسی انتخاب دانش آموز
                                                </button>
                                            @endcanany
                                            <a href="{{route('admin.student.exam.studentResult',$exam->id)}}"
                                               class="inline-block py-[10px] px-[30px] bg-info-500 text-white transition-all hover:bg-info-400 rounded-md border border-info-500 hover:border-info-400 ltr:mr-[11px] rtl:ml-[11px] mb-[15px]"
                                               type="button">
                                                نتایج
                                            </a>
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
                            {{ $exams->links('layouts.admin.pagination') }}

                        </div>
                    </div>
                    <div class="mt-[15px] md:mt-[20px]"></div>

                </div>
            </div>

            {{-- ... (بقیه کدها) --}}

            @if ($showStudentModal)
                <div class=" z-[999] fixed transition-all inset-0 overflow-x-hidden overflow-y-auto">
                    <div class="popup-dialog flex transition-all max-w-[550px] min-h-full items-center mx-auto">
                        <div class="trezo-card w-full bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
                            <div class="trezo-card-header bg-gray-50 dark:bg-[#15203c] mb-[20px] md:mb-[25px] flex items-center justify-between -mx-[20px] md:-mx-[25px] -mt-[20px] md:-mt-[25px] p-[20px] md:p-[25px] rounded-t-md">
                                <div class="trezo-card-title">
                                    <h5 class="mb-0">
                                        اختصاص دانش‌آموز به آزمون "{{ $selectedExam->title ?? '' }}"
                                    </h5>
                                </div>
                                <div class="trezo-card-subtitle">
                                    <button wire:click="closeAssignStudentsModal" type="button" class="text-[23px] transition-all leading-none text-black dark:text-white hover:text-primary-500" >
                                        <i class="ri-close-fill"></i>
                                    </button>
                                </div>
                            </div>
                            <form wire:submit.prevent="assignStudents">
                            <div class="trezo-card-content pb-[20px] md:pb-[25px]">

                                    <div class="p-4 space-y-2 max-h-[300px] overflow-y-auto">
                                        @foreach ($students as $student)
                                            <label class="flex items-center space-x-2">
                                                <input type="checkbox" wire:model="assignedStudents" value="{{ $student->id }}">
                                                <div class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                                    <div class="flex items-center">
                                                        <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                                            <span class="block font-medium">{{ optional($student->user?->personalInformation)->name ?? 'نامشخص' }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>

                            </div>
                            <div class="trezo-card-footer flex items-center justify-between -mx-[20px] md:-mx-[25px] px-[20px] md:px-[25px] pt-[20px] md:pt-[25px] border-t border-gray-100 dark:border-[#172036]">
                                <button wire:click="closeAssignStudentsModal" class="inline-block py-[10px] px-[30px] bg-danger-500 text-white transition-all hover:bg-danger-400 rounded-md border border-danger-500 hover:border-danger-400" type="button" id="add-new-popup-toggle">
                                    لغو
                                </button>
                                <button class="inline-block py-[10px] px-[30px] bg-primary-500 text-white transition-all hover:bg-primary-400 rounded-md border border-primary-500 hover:border-primary-400 ltr:mr-[11px] rtl:ml-[11px] mb-[15px]" type="submit">
                                   ثبت اختصاص
                                </button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>

            @endif

        </div>
    @else
        <div class="alert alert-icon-left alert-light-danger alert-dismissible fade show mb-4" role="alert">
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                <svg data-bs-dismiss="alert"> ...</svg>
            </button>
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                 class="feather feather-check-square">
                <polyline points="9 11 12 14 22 4"></polyline>
                <path d="M21 12v7a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11"></path>
            </svg>
            <strong></strong>
            شما به این قسمت دسترسی ندارید !!!
        </div>
    @endcanany
</div>

