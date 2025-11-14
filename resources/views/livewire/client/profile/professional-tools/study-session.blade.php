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
            @php
                function formatDuration($seconds)
                    {
                        $hours = floor($seconds / 3600);
                        $minutes = floor(($seconds % 3600) / 60);
                        $remainingSeconds = $seconds % 60;

                        return sprintf('%02d ساعت %02d دقیقه %02d ثانیه', $hours, $minutes, $remainingSeconds);
                    }
            @endphp
            <div class="lg:col-span-9 md:col-span-8">
                <div class="space-y-10">
                    <div class="space-y-5">
                        <!-- section:title -->
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-foreground">ابزار های حرفه ای</div>
                        </div>
                        <!-- end section:title -->

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
                                                             viewBox="0 0 24 24" stroke-width="1.5"
                                                             stroke="currentColor"
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
                                                1-ابتدا مبحث خودرا بنویسید و سپس گزینه شروع مطالعه را ثبت نموده تا ساعت شما برای ان مبحث ثبت گردد .
                                            </li>
                                            <li>
                                                2-این ابزار فقط برای دانش آموزان SDFR  قابل استفاده است .
                                            </li>
                                            <li>
                                                3-شما میتوانید مبحث خودرا فقط حذف نمایید و امکان ویرایش ندارید .
                                            </li>
                                            <li>
                                                ۴-تا هنگامی ک در این صفحه هستید برای شما ساعت مطالعه ثبت خواهد شد هرگونه بیرون امدن و بستن صفحه منجر به ثبت نشده مطالعه شما میشود !
                                            </li>
                                        </ul>
                                    </div><!-- end alert:desc -->

                                    <!-- alert:actions -->
                                    <!-- end alert:actions -->
                                </div>
                                <!-- end alert:content -->
                            </div>
                            <!-- tabs:contents -->
                            <div>
                                <!-- tabs:contents:tabOne -->
                                <div x-show="activeTab === 'tabOne'">
                                    <div class="text-white">
                                        <div wire:poll.visible.1000ms="tick">
                                            <h2 class="text-center font-bold">⏱️ تایمر مطالعه</h2>

                                            <div class="text-center mt-3">
                                                {{ formatDuration($liveSeconds) }}

                                            </div>

                                            <div class="text-center mt-3">
                                                <button
                                                    class="h-11 inline-flex items-center justify-center gap-3 rounded-full text-white px-6"

                                                    style="@if($isRunning=='startTimer') background-color: #600000 @elseif($isRunning=='stopTimer') background-color: #591616 @else background-color: #0a3622 @endif "
                                                    class="{
                                                                'bg-pri hover:bg-green-800': @js(!$isRunning),
                                                                'bg-red-700 hover:bg-red-800': @js($isRunning)
                                                            }"
                                                    wire:click="{{ $isRunning ? 'pauseTimer' : 'startTimer' }}">
                                                    {{ $isRunning ? '⏹ توقف مطالعه' : 'شروع مطالعه' }}
                                                </button>

                                                <button
                                                    style="background-color: #e65100 ;margin-right: 22px;margin-left: 22px"
                                                    class="h-11 inline-flex items-center justify-center gap-3  hover:bg-yellow-600 rounded-full text-white px-6 ml-3"
                                                    wire:click="resetTimer">
                                                   شروع مجدد
                                                </button>
                                                <button style="background-color: #18571b;margin-top: 15px"
                                                        class="h-11 inline-flex items-center justify-center gap-3 bg-red-500 rounded-full text-white px-4 mr-auto"
                                                        wire:click="finishAndSave" @disabled(! $startedAt)>
                                                    پایان و ثبت مطالعه
                                                </button>
                                            </div>

                                            <div class="text-right mt-5 mb-3">
                                                <div class="space-y-1">
                                                    <label for="note"
                                                           class="font-medium text-xs text-muted">مبحث مطالعه:</label>
                                                    <input type="text" wire:model="note" id="note" name="note"
                                                           class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                                                    <!--[if BLOCK]><![endif]--><!--[if ENDBLOCK]><![endif]-->
                                                </div>
                                            </div>

                                            <hr>

                                            <h3 class="text-center mt-5 mb-3">📋 جلسات قبلی</h3>
                                            <div class="relative @if(count($sessions) > 0) overflow-x-auto @endif">
                                                <table class="w-full text-sm text-right ">

                                                    @if(count($sessions) > 0)
                                                        <thead
                                                            class="text-xs text-muted uppercase bg-background border-b border-border">
                                                        <tr>
                                                            <th>شروع</th>
                                                            <th>پایان</th>
                                                            <th>مدت</th>
                                                            <th>یادداشت</th>
                                                            <th></th>
                                                        </tr>
                                                        </thead>

                                                        <tbody>
                                                        @foreach($sessions as $session)
                                                            <tr class="odd:bg-secondary even:bg-background">
                                                                <td class="p-5">
                                                                    <div class="flex items-center gap-2">
                                                                        <span
                                                                            class="font-bold text-green-500">{{ jalali($session->started_at)->format(' H:i-Y/m/d') }}</span>
                                                                    </div>
                                                                </td>
                                                                <td class="p-5">
                                                                    <div class="text-xs text-muted whitespace-nowrap">
                                                                        {{ jalali($session->ended_at) }}
                                                                    </div>
                                                                </td>
                                                                <td class="p-5">
                                                                    <div class="text-xs text-muted whitespace-nowrap">
                                                                        {{ formatDuration($session->duration_seconds) }}
                                                                    </div>
                                                                </td>
                                                                <td class="p-5">
                                                                    <div class="text-xs text-muted whitespace-nowrap">
                                                                        {{ $session->note }}
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <button
                                                                        wire:click="deleteSession({{ $session->id }})"
                                                                        onclick="return confirm('آیا مطمئنی؟')"
                                                                        class="text-red-500">🗑️ حذف
                                                                    </button>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>

                                                    @else
                                                        <div
                                                            class="flex flex-col items-center justify-center space-y-12">
                                                            <img src="/client/assets/images/theme/empty.svg"
                                                                 class="w-full max-w-xs opacity-35" alt="..."/>
                                                            <div class="text-center space-y-3">
                                                                <h2 class="font-bold text-xl text-foreground">
                                                                    تایم مطالعه ای برای شما وجود ندارد
                                                                </h2>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </table>
                                            </div>

                                        </div>


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
    @push('script')
        <script>
            window.addEventListener("beforeunload", () => {
                Livewire.dispatch("timerAborted");
            });
        </script>
    @endpush
</div>
