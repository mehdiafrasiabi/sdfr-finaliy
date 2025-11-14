<div>
    <div class="lg:col-span-2">
        <!-- Recent Leads -->
        <div class="trezo-card bg-white dark:bg-[#0c1427] p-[20px] md:p-[25px] rounded-md">
            <div class="trezo-card-header mb-[20px] md:mb-[25px] flex items-center justify-between">
                <div class="trezo-card-title">
                    <h5 class="!mb-0">لیست بلاگ ها</h5>
                </div>

                <div class="trezo-card-subtitle sm:flex sm:items-center">
                    <form class="relative sm:w-[240px] ltr:sm:mr-[20px] rtl:sm:ml-[20px] my-[13px] sm:my-0">
                        <label
                            class="leading-none absolute ltr:left-[13px] rtl:right-[13px] text-black dark:text-white mt-px top-1/2 -translate-y-1/2">
                            <i class="material-symbols-outlined !text-[20px]"> search </i>
                        </label>
                        <input type="text" placeholder="جستجو....."
                               class="bg-gray-50 border border-gray-50 h-[36px] text-xs rounded-md w-full block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] ltr:md:pr-[16px] rtl:pl-[13px] rtl:md:pl-[16px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400">
                    </form>
                    <div class="trezo-card-dropdown relative">
                        <a href="{{ route('admin.blog.create') }}"
                           class="text-white trezo-card-dropdown-btn inline-block bg-secondary-500 rounded-md border border-gray-100 py-[5px] md:py-[6.5px] px-[12px] md:px-[19px] transition-all hover:bg-secondary-400 dark:border-[#172036] dark:hover:bg-[#0a0e19]">
                            افزودن بلاگ جدید
                        </a>
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
                                دسته بندی
                            </th>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                مدت زمان مطالعه
                            </th>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                وضعیت
                            </th>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                عملیات
                            </th>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                تاریخ ثبت
                            </th>
                            <th class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap">
                                تاریخ آخرین تغییرات
                            </th>
                        </tr>
                        </thead>
                        <tbody class="text-black dark:text-white">
                        @forelse($blogs as $blog)
                            <tr>
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                    {{$loop->iteration + $blogs->firstItem() - 1}}
                                </td>
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                    <div class="flex items-center">
                                        <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                            <span class="block font-medium">{{ $blog->title ?? 'ناموجود'}}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                    {{ $blog->category->name ?? '-' }}
                                </td>
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                    {{ $blog->study_time }}دقیقه
                                </td>
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">

                                    @if($blog->status == 'pending')
                                        <span class="px-[8px] py-[3px] inline-block bg-warning-50 dark:bg-[#15203c] text-warning-700 rounded-sm font-medium text-xs">
                                            در حال بررسی
                                        </span>
                                    @elseif($blog->status == 'completed')
                                        <span class="px-[8px] py-[3px] inline-block bg-success-100 dark:bg-[#15203c] text-success-600 rounded-sm font-medium text-xs">
                                            تایید شده
                                        </span>
                                    @elseif($blog->status == 'rejected')
                                        <span class="px-[8px] py-[3px] inline-block bg-danger-100 dark:bg-[#15203c] text-danger-500 rounded-sm font-medium text-xs">
                                            توسط کارشناسان رد شده است
                                        </span>
                                    @else
                                        <span class="px-[8px] py-[3px] inline-block bg-primary-50 dark:bg-[#15203c] text-primary-500 rounded-sm font-medium text-xs">
                                            {{ $blog->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                    <div class="flex items-center gap-[9px]">
                                        @if($blog->status == 'rejected')
                                            <a href="{{ route('admin.blog.create', ['blog_id' => $blog->id]) }}" class="text-gray-500 dark:text-gray-400 leading-none">
                                                <i class="material-symbols-outlined !text-md">edit</i>
                                            </a>
                                        @else
                                            <button type="button" class="text-primary-500 leading-none">
                                                <i class="material-symbols-outlined !text-md"> visibility </i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                    {{jalali($blog->created_at)->format('%d %B %Y | H:i:s')}}
                                </td>
                                <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
                                    {{jalali($blog->updated_at)->format('%d %B %Y | H:i')}}
                                </td>
                            </tr>
                        @empty
                            <td class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[15px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]">
دیتایی وجود ندارد
                            </td>
                        @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-[20px] md:px-[25px] pt-[12px] md:pt-[14px] sm:flex sm:items-center justify-between">
                    {{ $blogs->links('layouts.admin.pagination') }}

                </div>
            </div>
            <div class="mt-[15px] md:mt-[20px]"></div>

        </div>
    </div>

</div>
