<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="space-y-8">
            <!-- section:title -->
            <div class="flex items-center gap-5 bg-gradient-to-l from-secondary to-background rounded-2xl p-5">
                        <span
                            class="flex items-center justify-center w-12 h-12 bg-primary text-primary-foreground rounded-full">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                 class="w-5 h-5">
                                <path fill-rule="evenodd"
                                      d="M3 6a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3V6ZM3 15.75a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-2.25Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3v-2.25Z"
                                      clip-rule="evenodd"/>
                            </svg>
                        </span>
                <div class="flex flex-col space-y-2">
                    <span class="font-black xs:text-2xl text-lg text-primary">دوره های SDFR</span>
                    <span class="font-semibold text-xs text-muted">با ما باش تا بهترین باشی !</span>
                </div>
            </div>
            <!-- end section:title -->

            <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
                <div class="md:block hidden lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                    <div class="w-full flex flex-col space-y-3 mb-3">
                        <span class="font-bold text-sm text-foreground">جستجو دوره</span>
                            <div class="flex items-center relative">
                                <input type="text"  wire:model.live.debounce.350ms="search"
                                       class="form-input w-full !ring-0 !ring-offset-0 h-10 bg-secondary !border-0 rounded-xl text-sm text-foreground"
                                       placeholder="جستجوی محصول بر اساس نام یا عنوان ...">
                                <span class="absolute left-3 text-muted" wire:loading.remove wire:target="search">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                 fill="currentColor" class="w-5 h-5">
                                                <path fill-rule="evenodd"
                                                      d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z"
                                                      clip-rule="evenodd"></path>
                                            </svg>
                                </span>
                                <span wire:loading wire:target="search">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                         stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                         stroke-linejoin="round" class="w-5 h-5 animate-spin">
                                               <path d="M21 12a9 9 0 1 1-6.219-8.56"></path>
                                    </svg>
                                </span>

                            </div>
                    </div>
                </div>

                <div class="lg:col-span-9 md:col-span-8">
                    <!-- sort & filter(offcanvas) -->
                    <div class="flex items-center gap-3 mb-3" x-data="{ offcanvasOpen: false }">
                        <!-- sort -->

                        <!-- end sort -->

                        <!-- filter:offcanvas:button -->
                        <button type="button"
                                class="md:hidden flex items-center gap-1 h-11 bg-secondary rounded-2xl text-foreground px-4"
                                x-on:click="offcanvasOpen = true">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 0 1-.659 1.591l-5.432 5.432a2.25 2.25 0 0 0-.659 1.591v2.927a2.25 2.25 0 0 1-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 0 0-.659-1.591L3.659 7.409A2.25 2.25 0 0 1 3 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0 1 12 3Z"/>
                            </svg>
                            <span class="hidden sm:block font-semibold text-xs">فیلتر دوره ها</span>
                        </button>
                        <!-- end filter:offcanvas:button -->

                        <!-- filter:offcanvas -->
                        <div x-cloak>
                            <!-- offcanvas:box -->
                            <div
                                class="fixed inset-y-0 right-0 xs:w-80 w-72 h-full bg-background rounded-l-2xl overflow-y-auto transition-transform z-50"
                                x-bind:class="offcanvasOpen ? '!translate-x-0' : 'translate-x-full'">

                                <!-- offcanvas:content -->
                                <div class="p-4">
                                    <div class="w-full flex flex-col space-y-3 mb-3">
                                        <span class="font-bold text-sm text-foreground">جستجو دوره</span>
                                        <form action="#">
                                            <div class="flex items-center relative">
                                                <input type="text" name="search" wire:model.live.debounce.350ms="search"
                                                       class="form-input w-full !ring-0 !ring-offset-0 h-10 bg-secondary !border-0 rounded-xl text-sm text-foreground"
                                                       placeholder="جستجوی محصول بر اساس نام یا عنوان...">
                                                <span class="absolute left-3 text-muted">
                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                                 fill="currentColor" class="w-5 h-5">
                                                                <path fill-rule="evenodd"
                                                                      d="M9 3.5a5.5 5.5 0 1 0 0 11 5.5 5.5 0 0 0 0-11ZM2 9a7 7 0 1 1 12.452 4.391l3.328 3.329a.75.75 0 1 1-1.06 1.06l-3.329-3.328A7 7 0 0 1 2 9Z"
                                                                      clip-rule="evenodd"></path>
                                                            </svg>
                                                </span>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <!-- end offcanvas:content -->


                            </div>
                            <!-- end offcanvas:box -->

                            <!-- offcanvas:overlay -->
                            <div
                                class="fixed inset-0 bg-black/10 dark:bg-white/10 cursor-pointer transition-all duration-1000 z-40"
                                x-bind:class="offcanvasOpen ? 'opacity-100 visible' : 'opacity-0 invisible'"
                                x-on:click="offcanvasOpen = false"></div>
                            <!-- end offcanvas:overlay -->
                        </div>
                        <!-- end filter:offcanvas -->
                    </div>
                    <!-- end sort & filter(offcanvas) -->

                    <!-- courses:wrapper -->
                    <div class="grid lg:grid-cols-3 sm:grid-cols-2 gap-x-5 gap-y-10">
                        <!-- course:card -->
                        @foreach($products as $item)
                            <div class="relative">
                                <div class="relative z-10">
                                    <a href="{{route('client.product',$item->p_code)}}/{{$item->seo->slug}}"
                                       class="block">
                                        <img src="/products/{{$item->id}}/photo/{{@$item->coverImage->path}}"
                                             class="max-w-full rounded-3xl"
                                             alt="{{$item->seo->slug}}"/>
                                    </a>
                                    <a href="{{route('client.product',$item->p_code)}}/{{$item->seo->slug}}"
                                       class="absolute left-3 top-3 h-11 inline-flex items-center justify-center gap-1 bg-black/20 rounded-full text-white transition-all hover:opacity-80 px-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                             fill="currentColor" class="w-6 h-6">
                                            <path fill-rule="evenodd"
                                                  d="M3 6a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3V6ZM3 15.75a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-2.25Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3v-2.25Z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                        <span class="font-semibold text-sm">{{$item->tag}}</span>
                                    </a>
                                </div>
                                <div class="bg-background rounded-b-3xl -mt-12 pt-12">
                                    <div
                                        class="bg-gradient-to-b from-background to-secondary rounded-b-3xl space-y-2 p-5 mx-5">
                                        <div class="flex items-center gap-2">
                                            <span class="block w-1 h-1 bg-success rounded-full"></span>
                                            <span class="font-bold text-xs text-success">{{$item->title}}</span>
                                        </div>
                                        <h2 class="font-bold text-sm">
                                            <a href="{{route('client.product',$item->p_code)}}/{{$item->seo->slug}}"
                                               class="line-clamp-1 text-foreground transition-colors hover:text-primary">
                                                {{$item->name}}
                                            </a>
                                        </h2>
                                    </div>
                                    <div class="space-y-3 p-5">
                                        <div class="flex flex-wrap items-center gap-3">
                                            <div class="flex items-center gap-1 text-muted">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                     fill="currentColor" class="w-5 h-5">
                                                    <path
                                                        d="M7 3.5A1.5 1.5 0 0 1 8.5 2h3.879a1.5 1.5 0 0 1 1.06.44l3.122 3.12A1.5 1.5 0 0 1 17 6.622V12.5a1.5 1.5 0 0 1-1.5 1.5h-1v-3.379a3 3 0 0 0-.879-2.121L10.5 5.379A3 3 0 0 0 8.379 4.5H7v-1Z">
                                                    </path>
                                                    <path
                                                        d="M4.5 6A1.5 1.5 0 0 0 3 7.5v9A1.5 1.5 0 0 0 4.5 18h7a1.5 1.5 0 0 0 1.5-1.5v-5.879a1.5 1.5 0 0 0-.44-1.06L9.44 6.439A1.5 1.5 0 0 0 8.378 6H4.5Z">
                                                    </path>
                                                </svg>
                                                <span class="font-semibold text-xs">۵ فصل</span>
                                            </div>
                                            <span class="block w-1 h-1 bg-muted-foreground rounded-full"></span>
                                            <div class="flex items-center gap-1 text-muted">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                     fill="currentColor" class="w-5 h-5">
                                                    <path fill-rule="evenodd"
                                                          d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z"
                                                          clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="font-semibold text-xs">{{$item->meeting_time}}</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between gap-5">
                                            <div class="flex items-center gap-3">
                                            </div>
                                            <div class="flex flex-col items-end justify-center h-14">
                                                <div class="flex items-center gap-1">
                                                        <span
                                                            class="font-black text-xl text-foreground">{{number_format($item->price)}}</span>
                                                    <span class="text-xs text-muted">تومان</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex gap-3 mt-3">
                                            <a href="{{route('client.product',$item->p_code)}}/{{$item->seo->slug}}" livewire:navigate
                                               class="w-full h-11 inline-flex items-center justify-center gap-1 bg-primary rounded-full text-primary-foreground transition-all hover:opacity-80 px-4">
                                                <span class="line-clamp-1 font-semibold text-sm">مشاهده دوره</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                     fill="currentColor" class="w-5 h-5">
                                                    <path fill-rule="evenodd"
                                                          d="M14.78 14.78a.75.75 0 0 1-1.06 0L6.5 7.56v5.69a.75.75 0 0 1-1.5 0v-7.5A.75.75 0 0 1 5.75 5h7.5a.75.75 0 0 1 0 1.5H7.56l7.22 7.22a.75.75 0 0 1 0 1.06Z"
                                                          clip-rule="evenodd"></path>
                                                </svg>
                                            </a>
                                            <button type="button"
                                                    class="flex-shrink-0 w-11 h-11 inline-flex items-center justify-center bg-secondary rounded-full text-muted transition-colors hover:text-red-500">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                     fill="currentColor" class="w-5 h-5">
                                                    <path
                                                        d="m9.653 16.915-.005-.003-.019-.01a20.759 20.759 0 0 1-1.162-.682 22.045 22.045 0 0 1-2.582-1.9C4.045 12.733 2 10.352 2 7.5a4.5 4.5 0 0 1 8-2.828A4.5 4.5 0 0 1 18 7.5c0 2.852-2.044 5.233-3.885 6.82a22.049 22.049 0 0 1-3.744 2.582l-.019.01-.005.003h-.002a.739.739 0 0 1-.69.001l-.002-.001Z">
                                                    </path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <!-- end course:card -->
                    </div>
                    <!-- courses:wrapper -->
                </div>
            </div>
        </div>
    </div>
</div>
