<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">

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
                            <div class="font-black text-foreground">اتاق مشاوره</div>
                        </div>
                        <!-- end section:title -->

                        <!-- tabs container -->
                        <div class="space-y-5" x-data="{ activeTab: 'tabOne'}" wire:poll.visible>
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

                                            <span class="font-semibold text-sm">اتاق مشاوره من </span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button"
                                                class="flex items-center gap-x-2 relative rounded-full py-2 px-4"
                                                x-bind:class="activeTab === 'tabTwo' ? 'text-foreground bg-background' : 'text-muted'"
                                                x-on:click="activeTab = 'tabTwo'">
                                            <!-- active icon -->
                                            <span x-show="activeTab === 'tabTwo'">
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
                                            <span x-show="activeTab !== 'tabTwo'">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                             viewBox="0 0 24 24" stroke-width="1.5"
                                                             stroke="currentColor"
                                                             class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5">
                                                            </path>
                                                        </svg>
                                                    </span><!-- end inactive icon -->

                                            <span class="font-semibold text-sm">پیش جلسه </span>
                                        </button>
                                    </li>
                                    <li>
                                        <button type="button"
                                                class="flex items-center gap-x-2 relative rounded-full py-2 px-4"
                                                x-bind:class="activeTab === 'tabThere' ? 'text-foreground bg-background' : 'text-muted'"
                                                x-on:click="activeTab = 'tabThere'">
                                            <!-- active icon -->
                                            <span x-show="activeTab === 'tabThere'">
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
                                            <span x-show="activeTab !== 'tabThere'">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                             viewBox="0 0 24 24" stroke-width="1.5"
                                                             stroke="currentColor"
                                                             class="w-5 h-5">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                  d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5">
                                                            </path>
                                                        </svg>
                                                    </span><!-- end inactive icon -->

                                            <span class="font-semibold text-sm">کارگاه آموزشی</span>
                                        </button>
                                    </li>
                                    <!-- end tabs:list:item -->

                                </ul>
                                <!-- end tabs:list -->
                            </div>
                            <!-- end tabs:list-container -->

                            <!-- tabs:contents -->
                            <div>
                                <!-- tabs:contents:tabOne -->
                                <!-- end tabs:contents:tabOne -->

                                <!-- tabs:contents:tabTwo -->
                                <div x-show="activeTab === 'tabOne'">
                                    <div class="relative  @if($sessions->isNotEmpty()) overflow-x-auto @endif">

                                        <table class="w-full text-sm text-right">
                                            @if($sessions->isNotEmpty())
                                                <thead
                                                        class="text-xs text-muted uppercase bg-background border-b border-border">
                                                <tr>
                                                    <th class="whitespace-nowrap p-5">ردیف</th>
                                                    <th class="whitespace-nowrap p-5">عنوان</th>
                                                    <th class="whitespace-nowrap p-5">توضیحات</th>
                                                    <th class="whitespace-nowrap p-5">تاریخ برگزاری</th>
                                                    <th class="whitespace-nowrap p-5">وضعیت</th>
                                                    <th class="whitespace-nowrap p-5">لینک</th>
                                                </tr>
                                                </thead>


                                                <tbody>
                                                @foreach($sessions as $session)
                                                    <tr class="odd:bg-secondary even:bg-background">
                                                        <td class="p-5">
                                                            <div class="font-black text-sm text-foreground">{{$loop->iteration + $sessions->firstItem() - 1}}</div>
                                                        </td>
                                                        <td class="p-5">
                                                            <div class="flex items-center gap-2">
                                                                <span class="font-bold text-white">   {{ $session->title ?? '---' }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="p-5">
                                                            <div class="flex items-center gap-2">
                                                                <span class="font-bold text-yellow-500">{{$session->description}}</span>
                                                            </div>
                                                        </td>
                                                        <td class="p-5">
                                                            <div class="text-xs text-muted whitespace-nowrap">
                                                                {{jalali(@$session->activation_date)->format('%d %B %Y | H:i')}}
                                                            </div>
                                                        </td>
                                                        <td class="p-5">
                                                            <div class="flex items-center gap-2">
                                                                @if($session->status == 'inactive')
                                                                    <span class="font-bold text-red-500">غیرفعال</span>

                                                                @elseif($session->status === 'active')
                                                                    <span class="font-bold text-green-500">فعال</span>
                                                                @else
                                                                    نا مشخص
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="p-5">
                                                            <div class="flex items-center gap-2">
                                                                <span class="font-bold text-red-500"></span>
                                                                @if ($session->status === 'active' && $session->skyroom_link)
                                                                    <a href="{{ $session->skyroom_link }}"
                                                                       target="_blank"
                                                                       class="text-blue-600 hover:underline">
                                                                        ورود به جلسه
                                                                    </a>
                                                                @else
                                                                    <span class="text-red-500">وجود ندارد</span>
                                                                @endif
                                                            </div>
                                                        </td>

                                                    </tr>
                                                @endforeach
                                                </tbody>

                                            @else
                                                <div class="flex flex-col items-center justify-center space-y-12">
                                                    <img src="/client/assets/images/theme/empty.svg"
                                                         class="w-full max-w-xs opacity-35" alt="..."/>
                                                    <div class="text-center space-y-3">
                                                        <h2 class="font-bold text-xl text-foreground">
                                                            جلسه مشاوره برای شما وجود ندارد.
                                                        </h2>
                                                    </div>
                                                </div>
                                            @endif

                                        </table>
                                    </div>
                                    <div class="p-5 text-xs text-muted whitespace-nowrap text-white">
                                        {{$sessions->links('layouts.client.pagination')}}
                                    </div>
                                </div>

                                <div x-show="activeTab === 'tabTwo'">
                                    <div class="relative  @if($sessions->isNotEmpty()) overflow-x-auto @endif">
                                        <table class="w-full text-sm text-right">
                                            @if($sessions->isNotEmpty())
                                                <thead class="text-xs text-muted uppercase bg-background border-b border-border">
                                                <tr>
                                                    <th class="whitespace-nowrap p-5">عنوان</th>
                                                    <th class="whitespace-nowrap p-5">تاریخ</th>
                                                    <th class="whitespace-nowrap p-5">لینک</th>
                                                    <th class="whitespace-nowrap p-5">وضعیت</th>
                                                    <th class="whitespace-nowrap p-5"></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($sessions as $session)
                                                    <tr class="odd:bg-secondary even:bg-background">
                                                        <td class="p-5">
                                                            <div class="text-xs text-muted whitespace-nowrap">
                                                                <span class="text-white">{{$session->title}}</span>
                                                            </div>
                                                        </td>
                                                        <td class="p-5">
                                                            <div class="text-xs text-muted whitespace-nowrap">
                                                                {{jalali(@$session->activation_date)->format('%d %B %Y | H:i')}}

                                                            </div>
                                                        </td>
                                                        <td class="p-5">
                                                            <div class="flex items-center gap-2">
                                                                @if ($session->status === 'active' && $session->skyroom_link)
                                                                    <a href="{{ $session->skyroom_link }}"
                                                                       target="_blank"
                                                                       class="text-blue-600 hover:underline">ورود به
                                                                        جلسه</a>
                                                                @else
                                                                    <span class="text-white">---</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="p-5">
                                                            <div class="flex items-center gap-2">
                                                                @if ($session->status === 'active')
                                                                    <span class="text-green-500">فعال</span>
                                                                @else
                                                                    <span class="text-red-500">غیرفعال</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td>
                                                            @if(!$alreadySubmitted)
                                                                <button wire:click="openPreAdvisingModal"
                                                                        class="h-11 inline-flex items-center justify-center gap-3 bg-primary  rounded-full text-white px-4 mr-auto">
                                                                    تکمیل فرم پیش جلسه
                                                                </button>
                                                            @else
                                                                <span class="text-white font-bold">✅ فرم ثبت شده</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            @else
                                                <div class="flex flex-col items-center justify-center space-y-12">
                                                    <img src="/client/assets/images/theme/empty.svg"
                                                         class="w-full max-w-xs opacity-35" alt="..."/>
                                                    <div class="text-center space-y-3">
                                                        <h2 class="font-bold text-xl text-foreground">
                                                            جلسه مشاوره برای شما وجود ندارد.
                                                        </h2>
                                                    </div>
                                                </div>
                                            @endif
                                        </table>
                                    </div>
                                    <div class="p-5 text-xs text-muted whitespace-nowrap text-white">
                                        {{$sessions->links('layouts.client.pagination')}}
                                    </div>
                                    @if($showPreAdvisingModal)
                                        <livewire:client.profile.multi-step-modal
                                            wire:key="multi-step-modal"
                                            wire:ignore.self />
                                    @endif
                                    <!-- end tabs:contents:tabTwo -->
                                </div>

                                <div x-show="activeTab === 'tabThere'">
                                    <div class="relative  @if($sessions->isNotEmpty()) overflow-x-auto @endif">
                                        <table class="w-full text-sm text-right">
                                            @if($sessions->isNotEmpty())
                                                <thead class="text-xs text-muted uppercase bg-background border-b border-border">
                                                <tr>
                                                    <th class="whitespace-nowrap p-5">#</th>
                                                    <th class="whitespace-nowrap p-5">عنوان</th>
                                                    <th class="whitespace-nowrap p-5">توضیحات</th>
                                                    <th class="whitespace-nowrap p-5">تاریخ برگزاری</th>
                                                    <th class="whitespace-nowrap p-5">لینک</th>
                                                    <th class="whitespace-nowrap p-5">وضعیت</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @foreach($googleMeet as $meet)
                                                    <tr class="odd:bg-secondary even:bg-background">
                                                        <td class="p-5">
                                                            <div class="font-black text-sm text-foreground">{{$loop->iteration + $googleMeet->firstItem() - 1}}</div>
                                                        </td>
                                                        <td class="p-5">
                                                            <div class="flex items-center gap-2">
                                                                <span class="font-bold text-white">{{$meet->title}}</span>
                                                            </div>
                                                        </td>
                                                        <td class="p-5">
                                                            <div class="flex items-center gap-2">
                                                                <span class="font-bold text-primary">{{$meet->body}}</span>
                                                            </div>
                                                        </td>

                                                        <td class="p-5">
                                                            <div class="text-xs text-muted whitespace-nowrap">
                                                                {{jalali(@$meet->created_at)->format('%d %B %Y | H:i')}}

                                                            </div>
                                                        </td>
                                                        <td class="p-5">
                                                            <div class="flex items-center gap-2">
                                                                <a href="{{$meet->link}}"
                                                                   class="text-white" target="_blank">
                                                                    مشاهده</a>
                                                            </div>
                                                        </td>
                                                        <td class="p-5">
                                                            <div class="flex items-center gap-2">
                                                                <button
                                                                    class="btn btn-sm me-2">
                                                                    @if($meet->is_active)
                                                                        <button class="text-green-500" type="button">
                                                                            فعال
                                                                        </button>
                                                                    @else
                                                                        <button class="text-red-500" type="button">
                                                                            غیرفعال
                                                                        </button>
                                                                    @endif
                                                                </button>
                                                            </div>
                                                        </td>

                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            @else
                                                <div class="flex flex-col items-center justify-center space-y-12">
                                                    <img src="/client/assets/images/theme/empty.svg"
                                                         class="w-full max-w-xs opacity-35" alt="..."/>
                                                    <div class="text-center space-y-3">
                                                        <h2 class="font-bold text-xl text-foreground">
                                                            جلسه مشاوره برای شما وجود ندارد.
                                                        </h2>
                                                    </div>
                                                </div>
                                            @endif
                                        </table>
                                    </div>
                                    <div class="p-5 text-xs text-muted whitespace-nowrap text-white">
                                        {{$googleMeet->links('layouts.client.pagination')}}
                                    </div>
                                    <!-- end tabs:contents:tabTwo -->
                                </div>


                                <!-- end tabs:contents -->
                            </div><!-- end tabs container -->
                        </div>
                    </div>
                </div>


            </div>

        </div>


    </div>
</div>
