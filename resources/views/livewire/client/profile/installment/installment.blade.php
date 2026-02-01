<div class="max-w-7xl space-y-14 px-4 mx-auto">
    <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
        <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
            <!-- user:info -->
            <!-- end user:info -->

            <!-- user:menus -->
            <livewire:client.profile.sidebar/>

            <!-- end user:menus -->
        </div>

        <div class="lg:col-span-9 md:col-span-8">
            <div class="space-y-10">

                <div class="space-y-5">
                    <!-- section:title -->
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">اقساط شهریه</div>
                    </div>
                    <!-- end section:title -->

                    <div class="blur-container">
                        <div class="relative overflow-x-auto">
                            <table class="w-full text-sm text-right">
                                <thead class="text-xs text-muted uppercase bg-background border-b border-border">
                                <tr>
                                    <th class="whitespace-nowrap p-5">ردیف</th>
                                    <th class="whitespace-nowrap p-5">شماره پیگیری</th>
                                    <th class="whitespace-nowrap p-5">وضعیت</th>
                                    <th class="whitespace-nowrap p-5">شرح تراکنش</th>
                                    <th class="whitespace-nowrap p-5">مبلغ</th>
                                    <th class="whitespace-nowrap p-5">تاریخ ایجاد</th>
                                    <th class="whitespace-nowrap p-5">عملیات</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr class="odd:bg-secondary even:bg-background">
                                    <td class="p-5">
                                        <div class="font-black text-sm text-foreground">1</div>
                                    </td>
                                    <td class="p-5">
                                        <div class="font-black text-sm text-foreground">۱۰۷۹</div>
                                    </td>
                                    <td class="p-5">
                                        <div class="flex items-center gap-2">
                                            <div class="flex-shrink-0 rounded-full bg-green-500/20 p-1">
                                                <div class="h-1.5 w-1.5 rounded-full bg-green-500"></div>
                                            </div>
                                            <span class="font-bold text-green-500">موفق</span>
                                        </div>
                                    </td>
                                    <td class="p-5">
                                        <div class="flex flex-col items-start gap-1 w-36">
                                            <span class="font-bold text-xs text-muted">خرید دوره</span>
                                            <span class="font-black text-sm text-foreground line-clamp-1">کهکشان</span>
                                        </div>
                                    </td>
                                    <td class="p-5">
                                        <div class="flex items-center gap-1">
                                            <span class="font-black text-sm text-foreground">۱2,۰00,۰۰۰</span>
                                            <span class="text-xs text-muted">تومان</span>
                                        </div>
                                    </td>
                                    <td class="p-5">
                                        <div class="text-xs text-muted whitespace-nowrap">
                                            ۲۰ اردیبهشت ۱۴۰۲
                                        </div>
                                    </td>
                                    <td class="p-5">
                                        <a wire:navigate href="{{route('client.profile.installmentDetail')}}" class="text-xs text-red-500 whitespace-nowrap">
                                            <button style="margin-left: 40px;background-color: #0a3622" class="h-11 inline-flex items-center justify-center gap-3  rounded-full text-white px-4 mr-auto" wire:click="startTimer">
                                                مشاهده اقساط
                                            </button>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="blur-overlay">
                            <img src="/client/soon2.png" alt="بزودی">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
