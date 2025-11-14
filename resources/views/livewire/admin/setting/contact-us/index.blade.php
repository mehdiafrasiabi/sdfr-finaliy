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
    <!-- Breadcrumb -->
    <div class="mb-[25px] md:flex items-center justify-between">
        <h5 class="!mb-0">درخواست های ارتباط با ما</h5>
    </div>

    <!-- Contacts -->
    <div class="trezo-card bg-white dark:bg-[#0c1427] mb-[25px] p-[20px] md:p-[25px] rounded-md">
        <div class="trezo-card-header mb-[20px] md:mb-[25px] sm:flex items-center justify-between">
            <div class="trezo-card-title">
                <form class="relative sm:w-[265px]">
                    <label
                        class="leading-none absolute ltr:left-[13px] rtl:right-[13px] text-black dark:text-white mt-px top-1/2 -translate-y-1/2"
                    >
                        <i class="material-symbols-outlined !text-[20px]"> search </i>
                    </label>
                    <input
                        type="text"
                        placeholder="اینجا جستجو کنید....."
                        class="bg-gray-50 border border-gray-50 h-[36px] text-xs rounded-md w-full block text-black pt-[11px] pb-[12px] ltr:pl-[38px] rtl:pr-[38px] ltr:pr-[13px] ltr:md:pr-[16px] rtl:pl-[13px] rtl:md:pl-[16px] placeholder:text-gray-500 outline-0 dark:bg-[#15203c] dark:text-white dark:border-[#15203c] dark:placeholder:text-gray-400"
                    />
                </form>
            </div>
            <div class="trezo-card-subtitle mt-[15px] sm:mt-0">
                <div class="trezo-card-dropdown relative">
                    <button
                        type="button"
                        class="trezo-card-dropdown-btn inline-block rounded-md border border-gray-100 py-[5px] md:py-[6.5px] px-[12px] md:px-[19px] transition-all hover:bg-gray-50 dark:border-[#172036] dark:hover:bg-[#0a0e19]"
                        id="dropdownToggleBtn"
                    >
                <span class="inline-block relative ltr:pr-[17px] ltr:md:pr-[20px] rtl:pl-[17px] rtl:ml:pr-[20px]">
                  همه
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
                                همه
                            </button>
                        </li>
                        <li>
                            <button
                                type="button"
                                class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black"
                            >
                                فعال
                            </button>
                        </li>
                        <li>
                            <button
                                type="button"
                                class="block w-full transition-all text-black ltr:text-left rtl:text-right relative py-[8px] px-[20px] hover:bg-gray-50 dark:text-white dark:hover:bg-black"
                            >
                                غیر فعال
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="trezo-card-content -mx-[20px] md:-mx-[25px]">
            <div class="table-responsive overflow-x-auto">
                <table class="w-full">
                    <thead class="text-black dark:text-white">
                    <tr>
                        <th
                            class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap"
                        >
                            شناسه
                        </th>
                        <th
                            class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap"
                        >
                            نام و نام خانوادگی
                        </th>
                        <th
                            class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap"
                        >
                            متن پیفام
                        </th>
                        <th
                            class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap"
                        >
                            تلفن
                        </th>
                        <th
                            class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap"
                        >
                            تاریخ ثبت
                        </th>


                        <th
                            class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap"
                        >
                            وضعیت
                        </th>
                        <th
                            class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap"
                        >
                           تاریخ اخرین تغییرات
                        </th>
                        <th
                            class="font-medium ltr:text-left rtl:text-right px-[20px] py-[11px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 bg-primary-50 dark:bg-[#15203c] whitespace-nowrap"
                        >
                            عملیات
                        </th>
                    </tr>
                    </thead>
                    <tbody class="text-black dark:text-white">
                    @forelse($contactUs as $item)
                        <tr>

                            <td
                                class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                            >
                                <span
                                    class="text-gray-500 dark:text-gray-400">{{$loop->iteration + $contactUs->firstItem() - 1}}</span>
                            </td>
                            <td
                                class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                            >
                                <div class="flex items-center">
                                    <div class="rounded-md w-[40px] rounded-full">
                                        <img src="assets/images/users/user6.jpg" class="inline-block rounded-full"
                                             alt="تصویر محصول"/>
                                    </div>
                                    <div class="ltr:ml-[12px] rtl:mr-[12px]">
                                        <span class="block font-medium">{{$item->name}} </span>
                                    </div>
                                </div>
                            </td>
                            <td
                                class="ltr:text-left rtl:text-right wrap-text whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                            >
                               {{$item->text}}
                            </td>
                            <td
                                class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                            >
                                <span class="text-gray-500 dark:text-gray-400">{{$item->mobile}}</span>
                            </td>
                            <td
                                class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                            >
                                <span class="text-gray-500 dark:text-gray-400"> {{jalali($item->created_at)->format('%d %B %Y | H:i')}} </span>
                            </td>

                            <td
                                class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                            >
                                <select
                                    wire:confirm="آیا از انتخاب خود برای تغییر وضعیت اطمینان دارید ؟"
                                    class="
                                     px-[8px] py-[3px] inline-block bg-{{$item->statusColor}} success-100 dark:bg-[#15203c] text-{{$item->statusColor}} rounded-sm font-medium text-xs
                                     inv-status"
                                    wire:change="changeStatus({{$item->id}},$event.target.value)">
                                    <option value="pending" {{$item->status=='pending' ? 'selected' :''}}>درانتظار
                                        تماس
                                    </option>
                                    <option value="completed" {{$item->status=='completed' ? 'selected' :''}} >تماس گرفته
                                        شد
                                    </option>
                                    <option value="canceled" {{$item->status=='canceled' ? 'selected' :''}}>پاسخ داده
                                        نشده
                                    </option>
                                </select>
                            </td>

                            <td
                                class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                            >
                                <span class="text-gray-500 dark:text-gray-400">
                                    @if($item->created_at != $item->updated_at)
                                        {{jalali($item->updated_at)->format('%d %B %Y | H:i')}}
                                    @else
                                        وجود ندارد
                                    @endif

                                </span>
                            </td>
                            <td
                                class="ltr:text-left rtl:text-right whitespace-nowrap px-[20px] py-[17px] md:ltr:first:pl-[25px] md:rtl:first:pr-[25px] ltr:first:pr-0 rtl:first:pl-0 border-b border-gray-100 dark:border-[#172036]"
                            >
                                <div class="flex items-center gap-[9px]">
                                    <button
                                        type="button"
                                        wire:confirm="آیا مطمئن هستید؟"
                                        wire:click="delete({{$item->id}})"
                                        class="text-danger-500 leading-none custom-tooltip"
                                        id="customTooltip"
                                        data-text="حذف"
                                    >
                                        <i class="material-symbols-outlined !text-md"> delete </i>
                                        <span class="tooltip-text">حذف</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                    @endforelse

                    </tbody>
                </table>
            </div>
            <div class="px-[20px] md:px-[25px] pt-[12px] md:pt-[14px] sm:flex sm:items-center justify-between">
                {{$contactUs->links('layouts.admin.pagination')}}
            </div>
        </div>
    </div>
</div>



