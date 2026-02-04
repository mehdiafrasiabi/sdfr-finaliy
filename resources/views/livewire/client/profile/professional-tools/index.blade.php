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

                        <!-- end section:title -->

                        <!-- Guide Section -->
                        <div
                            dir="rtl"
                            x-data="collapseGuide('report-guide')"
                            x-init="init()"
                            class="rounded-2xl border border-border bg-primary  overflow-hidden transition-all">

                            <!-- HEADER -->
                            <button
                                @click="toggle"
                                class="w-full flex items-center justify-between px-4 md:px-6 py-4
                                 transition">

                                <!-- title -->
                                <div class="flex items-center gap-2">

                                    <svg class="w-5 h-5 text-white dark:text-white"
                                         fill="currentColor" viewBox="0 0 24 24">
                                        <path
                                            d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 14h-2v-2h2v2zm0-4h-2V6h2v6z"/>
                                    </svg>

                                    <span class="font-black text-white dark:text-white text-blue-300 md:text-lg">
               راهنمای آچار فرانسه
            </span>
                                </div>

                                <!-- arrow -->
                                <svg
                                    class="w-5 h-5 text-white transition-transform duration-300"
                                    :class="open && 'rotate-180'"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>

                            <!-- CONTENT -->
                            <div
                                x-show="open"
                                x-cloak
                                x-transition:enter="transition ease-out duration-600"
                                x-transition:enter-start="opacity-0 -translate-y-2"
                                x-transition:enter-end="opacity-100 translate-y-0"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 translate-y-0"
                                x-transition:leave-end="opacity-0 -translate-y-1"
                                class="px-4 md:px-6 pb-6"
                            >


                                <div class="flex flex-col md:flex-row-reverse gap-6 items-center mt-2">

                                    <!-- IMAGE -->
                                    <div class="relative w-full md:w-[280px] shrink-0 order-2 md:order-1">

                                        <img
                                            src="/client/assets/images/blog/sdfr.jpg"
                                            class="w-full h-[200px] md:h-[180px] object-cover rounded-xl"
                                        >

                                        <button
                                            type="button"
                                            id="57612318744"
                                            data-video-url="https://www.aparat.com/video/video/embed/videohash/utg98i1/vt/frame?titleShow=true&recom=self"
                                            allowFullScreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"
                                            data-video-title="راهنمای آچار فرانسه"
                                            class="absolute inset-0 flex items-center justify-center"
                                        >
                                        <span
                                            class="w-14 h-14 rounded-full bg-white/90 dark:bg-black/60
                                               flex items-center justify-center shadow-lg transition">
                                            <svg class="w-7 h-7 text-blue-600 mr-1"
                                                 fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </span>
                                        </button>
                                    </div>

                                    <!-- TEXT -->
                                    <div
                                        class="flex-1 text-right text-sm md:text-base  text-white dark:text-white leading-7 order-1 md:order-2">

                                        دانش‌آموز عزیز سلام، قبل از شرکت در آزمون موارد زیر را با دقت مطالعه کنید:

                                        <br>• استفاده از آخرین نسخه مرورگر کروم الزامی است.
                                        <br>• حتماً قبل از خروج ثبت نهایی انجام شود.
                                        <br>• پس از ورود به هر دفترچه امکان بازگشت وجود ندارد.
                                        <br>• دفترچه آزمایشی ممکن است در پایان نمایش داده شود.

                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- End Guide Section -->


                        <!-- tabs container -->
                        <div class="space-y-5" x-data="{ activeTab: 'tabOne'}">
                            <!-- tabs:list-container -->
                            <div class="relative overflow-x-auto">
                                <!-- tabs:list -->
                                <ul
                                    class="inline-flex gap-2 bg-secondary border border-border rounded-full p-1">
                                    <!-- tabs:list:item -->
                                    <li>
                                        <button type="button"
                                                class="flex items-center gap-x-2 relative rounded-full py-2 px-4"
                                                x-bind:class="activeTab === 'tabOne' ? 'text-foreground bg-background' : 'text-muted'"
                                                x-on:click="activeTab = 'tabOne'">
                                            <!-- active icon -->
                                            <span x-show="activeTab === 'tabOne'">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                             fill="currentColor" class="w-5 h-5">
                                                            <path
                                                                d="M11.7 2.805a.75.75 0 0 1 .6 0A60.65 60.65 0 0 1 22.83 8.72a.75.75 0 0 1-.231 1.337 49.948 49.948 0 0 0-9.902 3.912l-.003.002c-.114.06-.227.119-.34.18a.75.75 0 0 1-.707 0A50.88 50.88 0 0 0 7.5 12.173v-.224c0-.131.067-.248.172-.311a54.615 54.615 0 0 1 4.653-2.52.75.75 0 0 0-.65-1.352 56.123 56.123 0 0 0-4.78 2.589 1.858 1.858 0 0 0-.859 1.228 49.803 49.803 0 0 0-4.634-1.527.75.75 0 0 1-.231-1.337A60.653 60.653 0 0 1 11.7 2.805Z">
                                                            </path>
                                                            <path
                                                                d="M13.06 15.473a48.45 48.45 0 0 1 7.666-3.282c.134 1.414.22 2.843.255 4.284a.75.75 0 0 1-.46.711 47.87 47.87 0 0 0-8.105 4.342.75.75 0 0 1-.832 0 47.87 47.87 0 0 0-8.104-4.342.75.75 0 0 1-.461-.71c.035-1.442.121-2.87.255-4.286.921.304 1.83.634 2.726.99v1.27a1.5 1.5 0 0 0-.14 2.508c-.09.38-.222.753-.397 1.11.452.213.901.434 1.346.66a6.727 6.727 0 0 0 .551-1.607 1.5 1.5 0 0 0 .14-2.67v-.645a48.549 48.549 0 0 1 3.44 1.667 2.25 2.25 0 0 0 2.12 0Z">
                                                            </path>
                                                            <path
                                                                d="M4.462 19.462c.42-.419.753-.89 1-1.395.453.214.902.435 1.347.662a6.742 6.742 0 0 1-1.286 1.794.75.75 0 0 1-1.06-1.06Z">
                                                            </path>
                                                        </svg>
                                                    </span><!-- end active icon -->

                                            <!-- inactive icon -->
                                            <span x-show="activeTab !== 'tabOne'">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                             class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5">
                                                            </path>
                                                        </svg>
                                                    </span><!-- end inactive icon -->

                                            <span class="font-semibold text-sm">ابزار ها</span>
                                        </button>
                                    </li><!-- end tabs:list:item -->

                                    <!-- tabs:list:item -->

                                    <!-- end tabs:list:item -->
                                </ul>
                                <!-- end tabs:list -->
                            </div>
                            <!-- end tabs:list-container -->

                            <!-- tabs:contents -->
                            <div>
                                <!-- tabs:contents:tabOne -->
                                <div x-show="activeTab === 'tabOne'">
                                    <div class="relative overflow-x-auto">
                                        <table class="w-full text-sm text-right">

                                                <thead
                                                    class="text-xs text-muted uppercase bg-background border-b border-border">
                                                <tr>
                                                    <th class="whitespace-nowrap p-5">ردیف</th>
                                                    <th class="whitespace-nowrap p-5">توضیحات</th>
                                                    <th class="whitespace-nowrap p-5"></th>
                                                </tr>
                                                </thead>


                                                <tbody>
                                                    <tr class="odd:bg-secondary even:bg-background">
                                                        <td class="p-5">
                                                            <div class="font-black text-sm text-foreground">1</div>
                                                        </td>
                                                        <td class="p-5">
                                                            <div class="flex items-center gap-2">
                                                                <span class="font-bold text-white"> پومودورو</span>
                                                            </div>
                                                        </td>

                                                        <td class="p-5">
                                                            <a wire:navigate href="{{route('client.profile.professionalTools.pomodoro')}}"
                                                               class="inline-flex items-center gap-x-1 text-cyan-400">
                                                                <span class="h-11 inline-flex items-center bg-primary justify-center gap-3 rounded-full text-white px-4 mr-auto">شروع</span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr class="odd:bg-secondary even:bg-background">
                                                        <td class="p-5">
                                                            <div class="font-black text-sm text-foreground">2</div>
                                                        </td>
                                                        <td class="p-5">
                                                            <div class="flex items-center gap-2">
                                                                <span class="font-bold text-white">تست سرعتی</span>
                                                            </div>
                                                        </td>

                                                        <td class="p-5">
                                                            <a wire:navigate href="#"
                                                               class="inline-flex items-center gap-x-1 text-cyan-400">
                                                                <span class="h-11 inline-flex items-center bg-yellow-600 justify-center gap-3 rounded-full text-white px-4 mr-auto">بزودی</span>
                                                            </a>
                                                        </td>
                                                    </tr>

                                                    <tr class="odd:bg-secondary even:bg-background">
                                                        <td class="p-5">
                                                            <div class="font-black text-sm text-foreground">3</div>
                                                        </td>
                                                        <td class="p-5">
                                                            <div class="flex items-center gap-2">
                                                                <span class="font-bold text-white">کلاسور تحلیل</span>
                                                            </div>
                                                        </td>

                                                        <td class="p-5">
                                                            <a wire:navigate href="#"
                                                               class="inline-flex items-center gap-x-1 text-cyan-400">
                                                                <span class="h-11 inline-flex items-center bg-yellow-600 justify-center gap-3 rounded-full text-white px-4 mr-auto">بزودی</span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <tr class="odd:bg-secondary even:bg-background">
                                                        <td class="p-5">
                                                            <div class="font-black text-sm text-foreground">4</div>
                                                        </td>
                                                        <td class="p-5">
                                                            <div class="flex items-center gap-2">
                                                                <span class="font-bold text-white">دفتر خلاصه ها</span>
                                                            </div>
                                                        </td>

                                                        <td class="p-5">
                                                            <a wire:navigate href="#"
                                                               class="inline-flex items-center gap-x-1 text-cyan-400">
                                                                <span class="h-11 inline-flex items-center bg-yellow-600 justify-center gap-3 rounded-full text-white px-4 mr-auto">بزودی</span>
                                                            </a>
                                                        </td>
                                                    </tr>


                                                </tbody>

                                        </table>

                                    </div>

                                </div>
                                <!-- end tabs:contents:tabOne -->

                                <!-- tabs:contents:tabTwo -->
                                <!-- end tabs:contents:tabTwo -->
                            </div><!-- end tabs:contents -->
                        </div><!-- end tabs container -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
