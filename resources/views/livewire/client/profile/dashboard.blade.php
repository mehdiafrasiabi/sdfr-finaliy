<div class="max-w-7xl space-y-14 px-4 mx-auto">
    <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
        <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">

            <!-- end user:info -->

            <!-- user:menus -->

            <livewire:client.profile.sidebar/>

            <!-- end user:menus -->
        </div>

        <div class="lg:col-span-9 md:col-span-8">
            <div class="space-y-10">
                <!-- statistics:items:wrapper -->
                <div class="grid lg:grid-cols-4 sm:grid-cols-2 grid-cols-1 gap-5 mb-8">
                    <!-- statistics:item -->

                    <!-- end statistics:item -->

                    <!-- statistics:item -->
                    <div class="flex items-center gap-3 bg-secondary rounded-2xl cursor-default p-3">
                                    <span
                                        class="flex items-center justify-center w-12 h-12 bg-background rounded-full text-primary">

                                           <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                 <path stroke-linecap="round" stroke-linejoin="round"
                                                       d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"></path>
                                        </svg>
                                    </span>
                        <div class="flex flex-col items-start text-right space-y-1">
                            <span class="font-bold text-xs text-muted line-clamp-1">پشتیبان من </span>

                            @if($supporterStudent)
                                <span class="font-bold text-sm text-foreground line-clamp-1">
                                {{ $supporterStudent->name }}
                                </span>
                            @else
                                <span class="font-bold text-sm text-foreground line-clamp-1">
                             تعیین نشده است
                                </span>
                            @endif


                        </div>
                    </div>
                    <!-- end statistics:item -->
                    <div class="flex items-center gap-3 bg-secondary rounded-2xl cursor-default p-3">
                                    <span
                                        class="flex items-center justify-center w-12 h-12 bg-background rounded-full text-primary">
                                           <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                                class="w-5 h-5">
                                            <path fill-rule="evenodd"
                                                  d="M9.664 1.319a.75.75 0 0 1 .672 0 41.059 41.059 0 0 1 8.198 5.424.75.75 0 0 1-.254 1.285 31.372 31.372 0 0 0-7.86 3.83.75.75 0 0 1-.84 0 31.508 31.508 0 0 0-2.08-1.287V9.394c0-.244.116-.463.302-.592a35.504 35.504 0 0 1 3.305-2.033.75.75 0 0 0-.714-1.319 37 37 0 0 0-3.446 2.12A2.216 2.216 0 0 0 6 9.393v.38a31.293 31.293 0 0 0-4.28-1.746.75.75 0 0 1-.254-1.285 41.059 41.059 0 0 1 8.198-5.424ZM6 11.459a29.848 29.848 0 0 0-2.455-1.158 41.029 41.029 0 0 0-.39 3.114.75.75 0 0 0 .419.74c.528.256 1.046.53 1.554.82-.21.324-.455.63-.739.914a.75.75 0 1 0 1.06 1.06c.37-.369.69-.77.96-1.193a26.61 26.61 0 0 1 3.095 2.348.75.75 0 0 0 .992 0 26.547 26.547 0 0 1 5.93-3.95.75.75 0 0 0 .42-.739 41.053 41.053 0 0 0-.39-3.114 29.925 29.925 0 0 0-5.199 2.801 2.25 2.25 0 0 1-2.514 0c-.41-.275-.826-.541-1.25-.797a6.985 6.985 0 0 1-1.084 3.45 26.503 26.503 0 0 0-1.281-.78A5.487 5.487 0 0 0 6 12v-.54Z"
                                                  clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                        <div class="flex flex-col items-start text-right space-y-1">
                            <span class="font-bold text-xs text-muted line-clamp-1">مشاور من </span>
                            <div class="flex items-center gap-1">
                                <span class="font-bold text-sm text-foreground">تعیین نشده است</span>
                                <span class="text-xs text-muted"></span>
                            </div>
                        </div>
                    </div>
                    <!-- statistics:item -->
                    <div class="flex items-center gap-3 bg-secondary rounded-2xl cursor-default p-3">
                                    <span
                                        class="flex items-center justify-center w-12 h-12 bg-background rounded-full ">
                                        <img src="/client/assets/images/icon/medal.png" class="w-5 h-5">
                                    </span>
                        <div class="flex flex-col items-start text-right space-y-1">
                            <span class="font-bold text-xs text-muted line-clamp-1">سطح آموزشی</span>
                            <span class="font-bold text-sm text-foreground line-clamp-1">سطح : {{$student->star ?? '--'}} </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 bg-secondary rounded-2xl cursor-default p-3">
                                    <span
                                        class="flex items-center justify-center w-12 h-12 bg-background rounded-full text-yellow-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                             class="w-5 h-5">
                                            <path fill-rule="evenodd"
                                                  d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401Z"
                                                  clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                        <div class="flex flex-col items-start text-right space-y-1">
                            <span class="font-bold text-xs text-muted line-clamp-1">سکه</span>
                            <span class="font-bold text-sm text-foreground line-clamp-1">بزودی 🔥</span>
                        </div>
                    </div>
                    <!-- end statistics:item -->

                    <!-- statistics:item -->

                    <!-- end statistics:item -->
                </div>
                <!-- end statistics:wrapper -->

                <!-- section:learning-courses -->
                <div class="space-y-5">
                    <!-- section:title -->
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">داشبورد</div>
                    </div>
                    <!-- end section:title -->

                    <!-- section:learning-courses:slider -->

                    <!-- end section:learning-courses:slider -->
                </div>
                <!-- end section:learning-courses -->
            </div>

        </div>
    </div>
</div>
