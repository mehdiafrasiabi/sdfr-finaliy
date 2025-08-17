<div class="max-w-7xl space-y-14 px-4 mx-auto">
    <div class="flex md:flex-nowrap flex-wrap items-start gap-5">
        <div class="md:w-8/12 w-full">
            <div class="relative">
                <div class="relative z-10">
                    <!-- course:thumbnail -->
                    <div>
                        <img src="/products/{{$product->id}}/photo/{{@$product->coverImage->path}}" class="max-w-full rounded-3xl"
                             alt="{{$product->seo->meta_title}}" />
                    </div>
                    <!-- end course:thumbnail -->
                </div>
                <div class="-mt-12 pt-12">
                    <div
                        class="bg-gradient-to-b from-background to-secondary rounded-b-3xl space-y-2 p-5 mx-5">
                        <!-- course:status -->
                        <div class="flex items-center gap-2">
                            <span class="block w-1 h-1 bg-success rounded-full"></span>
                            <span class="font-bold text-xs text-success">{{$product->title}}</span>
                        </div>

                        <!-- course:title -->
                        <h1 class="font-bold text-xl text-foreground">{{$product->name}}</h1>
                    </div>
                    <div class="space-y-10 py-5">

                        <div class="grid lg:grid-cols-4 grid-cols-2 gap-5">
                            <div
                                class="flex flex-col items-center justify-center gap-3 bg-secondary border border-border rounded-2xl cursor-default p-3">
                                            <span
                                                class="flex items-center justify-center w-12 h-12 bg-background rounded-full text-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                     fill="currentColor" class="w-5 h-5">
                                                    <path fill-rule="evenodd"
                                                          d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z"
                                                          clip-rule="evenodd"></path>
                                                </svg>
                                            </span>
                                <div
                                    class="flex flex-col items-center justify-center text-center space-y-1">
                                    <span class="font-bold text-xs text-muted line-clamp-1">مدت دوره</span>
                                    <span
                                        class="font-bold text-sm text-foreground line-clamp-1">{{$product->course_time}}</span>
                                </div>
                            </div>
                            <div
                                class="flex flex-col items-center justify-center gap-3 bg-secondary border border-border rounded-2xl cursor-default p-3">
                                            <span
                                                class="flex items-center justify-center w-12 h-12 bg-background rounded-full text-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                     fill="currentColor" class="w-5 h-5">
                                                    <path fill-rule="evenodd"
                                                          d="M4.25 2A2.25 2.25 0 0 0 2 4.25v2.5A2.25 2.25 0 0 0 4.25 9h2.5A2.25 2.25 0 0 0 9 6.75v-2.5A2.25 2.25 0 0 0 6.75 2h-2.5Zm0 9A2.25 2.25 0 0 0 2 13.25v2.5A2.25 2.25 0 0 0 4.25 18h2.5A2.25 2.25 0 0 0 9 15.75v-2.5A2.25 2.25 0 0 0 6.75 11h-2.5Zm9-9A2.25 2.25 0 0 0 11 4.25v2.5A2.25 2.25 0 0 0 13.25 9h2.5A2.25 2.25 0 0 0 18 6.75v-2.5A2.25 2.25 0 0 0 15.75 2h-2.5Zm0 9A2.25 2.25 0 0 0 11 13.25v2.5A2.25 2.25 0 0 0 13.25 18h2.5A2.25 2.25 0 0 0 18 15.75v-2.5A2.25 2.25 0 0 0 15.75 11h-2.5Z"
                                                          clip-rule="evenodd"></path>
                                                </svg>
                                            </span>
                                <div
                                    class="flex flex-col items-center justify-center text-center space-y-1">
                                                <span class="font-bold text-xs text-muted line-clamp-1">
                                                    تعداد جلسات
                                                </span>
                                    <span class="font-bold text-sm text-foreground line-clamp-1">  {{$product->meeting_time}}</span>
                                </div>
                            </div>
                            <div
                                class="flex flex-col items-center justify-center gap-3 bg-secondary border border-border rounded-2xl cursor-default p-3">
                                            <span
                                                class="flex items-center justify-center w-12 h-12 bg-background rounded-full text-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                     fill="currentColor" class="w-5 h-5">
                                                    <path fill-rule="evenodd"
                                                          d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z"
                                                          clip-rule="evenodd"></path>
                                                </svg>
                                            </span>
                                <div
                                    class="flex flex-col items-center justify-center text-center space-y-1">
                                    <span class="font-bold text-xs text-muted line-clamp-1">نوع دوره</span>
                                    <span class="font-bold text-sm text-foreground line-clamp-1">
                                                   اقساطی/ نقدی
                                    </span>
                                </div>
                            </div>
                            <div
                                class="flex flex-col items-center justify-center gap-3 bg-secondary border border-border rounded-2xl cursor-default p-3">
                                            <span
                                                class="flex items-center justify-center w-12 h-12 bg-background rounded-full text-primary">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                     fill="currentColor" class="w-5 h-5">
                                                    <path
                                                        d="M10 9a3 3 0 1 0 0-6 3 3 0 0 0 0 6ZM6 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM1.49 15.326a.78.78 0 0 1-.358-.442 3 3 0 0 1 4.308-3.516 6.484 6.484 0 0 0-1.905 3.959c-.023.222-.014.442.025.654a4.97 4.97 0 0 1-2.07-.655ZM16.44 15.98a4.97 4.97 0 0 0 2.07-.654.78.78 0 0 0 .357-.442 3 3 0 0 0-4.308-3.517 6.484 6.484 0 0 1 1.907 3.96 2.32 2.32 0 0 1-.026.654ZM18 8a2 2 0 1 1-4 0 2 2 0 0 1 4 0ZM5.304 16.19a.844.844 0 0 1-.277-.71 5 5 0 0 1 9.947 0 .843.843 0 0 1-.277.71A6.975 6.975 0 0 1 10 18a6.974 6.974 0 0 1-4.696-1.81Z">
                                                    </path>
                                                </svg>
                                            </span>
                                <div
                                    class="flex flex-col items-center justify-center text-center space-y-1">
                                                <span
                                                    class="font-bold text-xs text-muted line-clamp-1">شرکت‌کنندگان</span>
                                    <span class="font-bold text-sm text-foreground line-clamp-1">۱۲۲
                                                    دانشجو</span>
                                </div>
                            </div>
                        </div>

                        <!-- tabs container -->
                        <div class="space-y-5"
                             x-data="{ activeTab: 'tabOne', scroll: function() { document.getElementById(this.activeTab).scrollIntoView({ behavior: 'smooth' }) } }">
                            <div class="sticky top-24 z-10">
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
                                                    x-on:click="activeTab = 'tabOne'; scroll();">
                                                <!-- active icon -->
                                                <span x-show="activeTab === 'tabOne'">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                     viewBox="0 0 20 20" fill="currentColor"
                                                                     class="w-5 h-5">
                                                                    <path
                                                                        d="M2.695 14.763l-1.262 3.154a.5.5 0 00.65.65l3.155-1.262a4 4 0 001.343-.885L17.5 5.5a2.121 2.121 0 00-3-3L3.58 13.42a4 4 0 00-.885 1.343z">
                                                                    </path>
                                                                </svg>
                                                            </span><!-- end active icon -->

                                                <!-- inactive icon -->
                                                <span x-show="activeTab !== 'tabOne'">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                     viewBox="0 0 24 24" stroke-width="1.5"
                                                                     stroke="currentColor" class="w-5 h-5">
                                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                                          d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125">
                                                                    </path>
                                                                </svg>
                                                            </span><!-- end inactive icon -->

                                                <span
                                                    class="font-semibold text-sm whitespace-nowrap">معرفی</span>
                                            </button>
                                        </li><!-- end tabs:list:item -->



                                    </ul><!-- end tabs:list -->
                                </div><!-- end tabs:list-container -->
                            </div>

                            <!-- tabs:contents -->
                            <div class="space-y-8">
                                <!-- tabs:contents:tabOne -->
                                <div class="bg-background rounded-3xl p-5" id="tabOne">
                                    <div class="flex items-center gap-3 mb-5">
                                        <div class="flex items-center gap-1">
                                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                                        </div>
                                        <div class="font-black text-foreground">معرفی دوره</div>
                                    </div>

                                    <!-- course:description -->
                                    <div class="description">
                                      {!! $product->description !!}
                                    </div>
                                    <!-- end course:description -->
                                </div>
                                <!-- end tabs:contents:tabOne -->


                            </div>
                            <!-- end tabs:contents -->
                        </div>
                        <!-- end tabs container -->
                    </div>
                </div>
            </div>
        </div>

        <div class="md:w-4/12 w-full md:sticky md:top-24 space-y-8">
            <!-- course:registering -->
            <div class="bg-gradient-to-b from-secondary to-background rounded-2xl px-5 pb-5">
                <div class="bg-background rounded-b-3xl space-y-2 p-5 mb-5">
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">عضویت در  {{$product->name}}</div>
                    </div>
                </div>
                <div class="flex items-center justify-between gap-5">
                    <span class="font-bold text-base text-muted">هزینه ثبت نام:</span>
                    <div class="flex flex-col items-end justify-center h-14">
                        <div class="flex items-center gap-1">
                            <span class="font-black text-xl text-foreground">{{number_format($product->price)}}</span>
                            <span class="text-xs text-muted">تومان</span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-3 mt-3">
                    @if(\Illuminate\Support\Facades\Auth::check())
                        @if($hasPurchased)
                            <div class="w-full h-11 inline-flex items-center justify-center gap-1 bg-secondary rounded-full text-primary-foreground transition-all hover:opacity-80 px-4" style="cursor: not-allowed">
                                <span class="font-semibold text-sm text-success "> شما دانشجوی این دوره هستید ✅</span>
                            </div>
                        @elseif(!$inCart)
                               <button  wire:click="addToCart"
                            class="w-full h-11 inline-flex items-center justify-center gap-1 bg-primary rounded-full text-primary-foreground transition-all hover:opacity-80 px-4">
                        <span class="font-semibold text-sm" wire:loading.remove>اضافه به سبد خرید</span>
                        <svg wire:loading.remove xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="w-5 h-5">
                            <path fill-rule="evenodd"
                                  d="M14.78 14.78a.75.75 0 0 1-1.06 0L6.5 7.56v5.69a.75.75 0 0 1-1.5 0v-7.5A.75.75 0 0 1 5.75 5h7.5a.75.75 0 0 1 0 1.5H7.56l7.22 7.22a.75.75 0 0 1 0 1.06Z"
                                  clip-rule="evenodd"></path>
                        </svg>
                        <div wire:loading>
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                 viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" width="40px" height="40px"
                                 style="shape-rendering: auto; display: block; background: transparent;">
                                <g>
                                    <path stroke="none" fill="#ffffff"
                                          d="M19 50A31 31 0 0 0 81 50A31 34 0 0 1 19 50">
                                        <animateTransform values="0 50 51.5;360 50 51.5" keyTimes="0;1"
                                                          repeatCount="indefinite" dur="0.8130081300813008s"
                                                          type="rotate" attributeName="transform"/>
                                    </path>
                                    <g/>
                                </g>
                            </svg>
                        </div>
                    </button>
                        @else
                            <a href="{{route('client.checkout.cart')}}"
                                    class="w-full h-11 inline-flex items-center justify-center gap-1 bg-success rounded-full text-primary-foreground transition-all hover:opacity-80 px-4">
                                <span class="font-semibold text-sm">مشاهده در سبد خرید</span>
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                     class="w-5 h-5">
                                    <path fill-rule="evenodd"
                                          d="M14.78 14.78a.75.75 0 0 1-1.06 0L6.5 7.56v5.69a.75.75 0 0 1-1.5 0v-7.5A.75.75 0 0 1 5.75 5h7.5a.75.75 0 0 1 0 1.5H7.56l7.22 7.22a.75.75 0 0 1 0 1.06Z"
                                          clip-rule="evenodd"></path>
                                </svg>
                            </a>
                        @endif
                    @else
                         <a href="{{route('client.auth.login')}}"
                                class="w-full h-11 inline-flex items-center justify-center gap-1 bg-red-500 rounded-full text-primary-foreground transition-all hover:opacity-80 px-4">
                            <span class="font-semibold text-sm">لطفا ابتدا وارد شوید</span>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                 class="w-5 h-5">
                                <path fill-rule="evenodd"
                                      d="M14.78 14.78a.75.75 0 0 1-1.06 0L6.5 7.56v5.69a.75.75 0 0 1-1.5 0v-7.5A.75.75 0 0 1 5.75 5h7.5a.75.75 0 0 1 0 1.5H7.56l7.22 7.22a.75.75 0 0 1 0 1.06Z"
                                      clip-rule="evenodd"></path>
                            </svg>
                         </a>
                    @endif
                    <button type="button"
                            class="flex-shrink-0 w-11 h-11 inline-flex items-center justify-center bg-secondary rounded-full text-muted transition-colors hover:text-red-500">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="w-5 h-5">
                            <path
                                d="m9.653 16.915-.005-.003-.019-.01a20.759 20.759 0 0 1-1.162-.682 22.045 22.045 0 0 1-2.582-1.9C4.045 12.733 2 10.352 2 7.5a4.5 4.5 0 0 1 8-2.828A4.5 4.5 0 0 1 18 7.5c0 2.852-2.044 5.233-3.885 6.82a22.049 22.049 0 0 1-3.744 2.582l-.019.01-.005.003h-.002a.739.739 0 0 1-.69.001l-.002-.001Z">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>
            <!-- end course:registering -->
        </div>
    </div>
</div>
