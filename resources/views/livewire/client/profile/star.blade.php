<div>
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
                            <div class="font-black text-foreground">تاریخچه تراکنشها</div>
                        </div>
                        <!-- end section:title -->
                        <div class="bg-background rounded-3xl p-5">
                            <!-- tabs:contents:tabOne -->

                            <!-- tabs:contents:tabTwo -->
                            <div class="space-y-5" x-data="{ activeTab: 'tabOne'}">
                                <!-- tabs:list-container -->

                                <!-- end tabs:list-container -->

                                <!-- tabs:contents -->
                                <div>
                                    <!-- tabs:contents:tabOne -->
                                    <div class="space-y-5" x-show="activeTab === 'tabOne'">


                                        <div
                                            class="flex items-start gap-3 relative bg-zinc-50 dark:bg-zinc-900 border border-border rounded-xl p-5"
                                            x-show="open" x-data="{ open: true }">
                                            <!-- alert:icon -->
                                            <span class="text-yellow-500">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                         fill="currentColor" class="w-5 h-5">
                                                        <path fill-rule="evenodd"
                                                              d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z"
                                                              clip-rule="evenodd"></path>
                                                    </svg>
                                                </span><!-- alert:icon -->

                                            <!-- alert:content -->
                                            <div class="flex flex-col items-start">
                                                <!-- alert:title -->
                                                <div class="font-bold text-sm text-yellow-500 mb-2">
                                                    توجه :&zwnj;
                                                </div><!-- end alert:title -->

                                                <!-- alert:desc -->
                                                <div class="font-semibold text-xs text-zinc-400">
                                                    <p class="mb-3">
                                                        سلام امیدوارم حال دلتون عالی باشه ، لطفا قبل از هرگونه انجام درخواستی بخش زیر را مطالعه فرمایید.
                                                        ممنون از شما
                                                    </p>
                                                    <ul>
                                                        <li>
                                                            1-هر 100 سکه معادل هزار تومان است .
                                                        </li>
                                                        <li>
                                                            2-دانش اموز درصورتی پاداش دریافت میکند که گزارش کار خوب یا درصد ازمون بالا کسب کند
                                                        </li>
                                                        <li>
                                                            3-دانش اموز میتواند از طریق بازی یا مسابقه ای که برگزار میشود در صورت برنده شدن سکه دریافت نماید(بزوووودی)
                                                        </li>
                                                        <li>
                                                            ۴-فراییند تایید ممکن است 24الی48 ساعت زمان ببرد.
                                                        </li>
                                                        <li>
                                                            5-میزان درخواستی که مورد برسی قرار میگیرد بالای 10 هزار تومان می باشد(از درخواست بی مورد خودداری فرمایید)
                                                        </li>
                                                    </ul>
                                                </div><!-- end alert:desc -->

                                                <!-- alert:actions -->
                                                <div class="flex flex-wrap items-center gap-3 mt-5">
                                                    <button type="button"
                                                            class="flex items-center gap-x-1 text-zinc-400 underline-offset-1 hover:underline"
                                                            x-on:click="open = false">
                                                        <span class="font-bold text-xs">فهمیدم</span>
                                                    </button>
                                                </div><!-- end alert:actions -->
                                            </div>
                                            <!-- end alert:content -->
                                        </div>
                                        <div class="max-w-7xl space-y-14 px-4 mx-auto">
                                            <div class="flex flex-col items-center justify-center space-y-12">
                                                <img src="/client/assets/images/theme/empty.svg" class="w-full max-w-xs opacity-35" alt="..." />
                                                <div class="text-center space-y-3">
                                                    <h2 class="font-bold text-xl text-foreground">
                                                        بزودی این قسمت راه اندازی خواهد شد :)
                                                    </h2>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div><!-- end tabs:contents -->
                            </div>
                            <!-- end tabs:contents:tabTwo -->

                            <!-- tabs:contents:tabTwo -->



                        </div>
                    </div>
                </div>
            </div>

        </div>
</div>
</div>
