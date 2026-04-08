<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">

                <!-- user:menus -->
                <livewire:client.profile.sidebar />
                <!-- end user:menus -->
            </div>

            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-10">
                    <!-- Help Box -->

                    <div class="space-y-5">
                        <!-- section:title -->
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-foreground"> کارنامه وضعیت</div>
                        </div>
                        <!-- end section:title -->


                        <!-- tabs:contents -->
                        <div>
                            <!-- tabs:contents:tabTwo -->
                            <div class="space-y-5" wire:poll.visible>
                                <div class="relative  @if($reportMonthly->isNotEmpty()) overflow-x-auto @endif">

                                    <table class="w-full text-sm text-right">
                                        @if($reportMonthly->isNotEmpty())
                                            <thead
                                                class="text-xs text-muted uppercase bg-background border-b border-border">
                                            <tr>
                                                <th class="whitespace-nowrap p-5">ردیف</th>
                                                <th class="whitespace-nowrap p-5">عنوان</th>
                                                <th class="whitespace-nowrap p-5">تاریخ بارگذاری </th>
                                                <th class="whitespace-nowrap p-5"></th>
                                            </tr>
                                            </thead>


                                            <tbody >
                                            @foreach($reportMonthly as $report)
                                                <tr class="odd:bg-secondary even:bg-background">
                                                    <td class="p-5">
                                                        <div class="font-black text-sm text-foreground">{{$loop->iteration + $reportMonthly->firstItem() - 1}}</div>
                                                    </td>
                                                    <td class="p-5">
                                                        <div class="flex items-center gap-2">
                                                            <span class="font-bold text-white">{{@$report->title}}</span>
                                                        </div>
                                                    </td>
                                                    <td class="p-5">
                                                        <div class="text-xs text-muted whitespace-nowrap">
                                                            {{jalali(@$report->created_at)->format('%d %B %Y | H:i')}}
                                                        </div>
                                                    </td>
                                                    <td class="p-5">
                                                        <a href="{{ \App\Helpers\FileHelper::publicUrl($report->report) }}"
                                                           target="_blank"
                                                           download
                                                           class="inline-flex items-center gap-x-1 text-cyan-400">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="currentColor" class="size-4">
                                                                <path fill-rule="evenodd" d="M11.013 2.513a1.75 1.75 0 0 1 2.475 2.474L6.226 12.25a2.751 2.751 0 0 1-.892.596l-2.047.848a.75.75 0 0 1-.98-.98l.848-2.047a2.75 2.75 0 0 1 .596-.892l7.262-7.261Z" clip-rule="evenodd"></path>
                                                            </svg>
                                                            <span class="whitespace-nowrap font-semibold text-xs">مشاهده</span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                            </tbody>

                                        @else
                                            <div class="flex flex-col items-center justify-center space-y-12">
                                                <img src="/client/empty/reportStudentStudy.png" class="w-full max-w-xs" alt="..." />
                                                <div class="text-center space-y-3">
                                                    <h2 class="font-bold text-xl text-foreground">
                                                        گزارش وضعیت  برای شما وجود ندارد!
                                                        <p class="text-muted text-sm">هنوز گزارش وضعیت  برای شما ثبت نشده است.</p>
                                                    </h2>
                                                </div>
                                            </div>
                                        @endif

                                    </table>
                                </div>
                                <div class="p-5 text-xs text-muted whitespace-nowrap text-white">
                                    {{$reportMonthly->links('layouts.client.pagination')}}
                                </div>
                            </div>
                            <!-- end tabs:contents:tabTwo -->
                        </div>
                        <!-- end tabs:contents -->
                    </div>
                </div>
            </div>
        </div>

    </div>

</div>
