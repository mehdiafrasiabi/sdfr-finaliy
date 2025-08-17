<div x-data="{ modalOpen: false }">
    <!-- container -->
    @if(session()->has('debug'))
        <pre style="color: red">{{ print_r(session('debug'), true) }}</pre>
    @endif
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        @if($cart)
        <div class="flex md:flex-nowrap flex-wrap items-start gap-5">
            <div class="md:w-8/12 w-full">

                    <!-- section:title -->
                    <div
                        class="flex items-center justify-between gap-8 bg-gradient-to-l from-secondary to-background rounded-2xl p-5">
                        <div class="flex items-center gap-5">
                                    <span
                                        class="flex items-center justify-center w-12 h-12 bg-primary text-primary-foreground rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                             class="w-5 h-5">
                                            <path fill-rule="evenodd"
                                                  d="M9.664 1.319a.75.75 0 0 1 .672 0 41.059 41.059 0 0 1 8.198 5.424.75.75 0 0 1-.254 1.285 31.372 31.372 0 0 0-7.86 3.83.75.75 0 0 1-.84 0 31.508 31.508 0 0 0-2.08-1.287V9.394c0-.244.116-.463.302-.592a35.504 35.504 0 0 1 3.305-2.033.75.75 0 0 0-.714-1.319 37 37 0 0 0-3.446 2.12A2.216 2.216 0 0 0 6 9.393v.38a31.293 31.293 0 0 0-4.28-1.746.75.75 0 0 1-.254-1.285 41.059 41.059 0 0 1 8.198-5.424ZM6 11.459a29.848 29.848 0 0 0-2.455-1.158 41.029 41.029 0 0 0-.39 3.114.75.75 0 0 0 .419.74c.528.256 1.046.53 1.554.82-.21.324-.455.63-.739.914a.75.75 0 1 0 1.06 1.06c.37-.369.69-.77.96-1.193a26.61 26.61 0 0 1 3.095 2.348.75.75 0 0 0 .992 0 26.547 26.547 0 0 1 5.93-3.95.75.75 0 0 0 .42-.739 41.053 41.053 0 0 0-.39-3.114 29.925 29.925 0 0 0-5.199 2.801 2.25 2.25 0 0 1-2.514 0c-.41-.275-.826-.541-1.25-.797a6.985 6.985 0 0 1-1.084 3.45 26.503 26.503 0 0 0-1.281-.78A5.487 5.487 0 0 0 6 12v-.54Z"
                                                  clip-rule="evenodd"></path>
                                        </svg>
                                    </span>
                            <div class="flex flex-col space-y-2">
                                <span class="font-black xs:text-2xl text-lg text-primary">سبد خرید شما</span>
                                <span class="font-semibold text-xs text-muted">{{$cart}} دوره به سبد اضافه کرده
                                            اید</span>
                            </div>
                        </div>
                    </div>
                    <!-- end section:title -->


                <!-- cart-items:wrapper -->
                <div class="divide-y divide-dashed divide-border">
                    @foreach($cartItems as $item)
                    <div class="flex sm:flex-nowrap flex-wrap items-start gap-8 relative py-6">
                        <div class="sm:w-4/12 w-full relative z-10">
                            <a href="{{route('client.product',$item->product->p_code)}}/{{$item->product->seo->slug}}" class="block">
                                <img src="/products/{{$item->product->id}}/photo/{{@$item->product->coverImage->path}}" class="max-w-full rounded-3xl"
                                     alt="{{$item->product->seo->meta_title}}" />
                            </a>
                            <button type="button" wire:click="confirmDeleteItem({{$item->id}})"
                                    class="flex-shrink-0 absolute right-1/2 translate-x-1/2 -translate-y-6 w-11 h-11 inline-flex items-center justify-center bg-error rounded-full text-error-foreground shadow-2xl"
                                    x-on:click="modalOpen = true">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M6 18 18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="sm:w-8/12 w-full">
                            <div class="bg-gradient-to-b from-secondary to-background rounded-3xl">
                                <div class="bg-background rounded-b-3xl space-y-2 p-5 mx-5">
                                    <div class="flex items-center gap-2">
                                        <span class="block w-1 h-1 bg-success rounded-full"></span>
                                        <span class="font-bold text-xs text-success">{{$item->product->title}}</span>
                                    </div>
                                    <h2 class="font-bold text-sm">
                                        <a href="{{route('client.product',$item->product->p_code)}}/{{$item->product->seo->slug}}"
                                           class="line-clamp-1 text-foreground transition-colors hover:text-primary">{{$item->product->name}}</a>
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
                                            <span class="font-semibold text-xs">{{$item->product->meeting_time}}</span>
                                        </div>
                                        <span class="block w-1 h-1 bg-muted-foreground rounded-full"></span>
                                        <div class="flex items-center gap-1 text-muted">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                 fill="currentColor" class="w-5 h-5">
                                                <path fill-rule="evenodd"
                                                      d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm.75-13a.75.75 0 0 0-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 0 0 0-1.5h-3.25V5Z"
                                                      clip-rule="evenodd"></path>
                                            </svg>
                                            <span class="font-semibold text-xs">{{$item->product->course_time}}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between gap-5">
                                        <div class="flex items-center gap-3">


                                        </div>
                                        <div class="flex flex-col items-end justify-center h-14">
                                            <div class="flex items-center gap-1">
                                                            <span
                                                                class="font-black text-xl text-foreground">{{number_format($item->product->price)}}</span>
                                                <span class="text-xs text-muted">تومان</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex gap-3 mt-3">
                                        <a href="{{route('client.product',$item->product->p_code)}}/{{$item->product->seo->slug}}"
                                           class="w-full h-11 inline-flex items-center justify-center gap-1 bg-primary rounded-full text-primary-foreground transition-all hover:opacity-80 px-4">
                                                        <span class="line-clamp-1 font-semibold text-sm">مشاهده
                                                            دوره</span>
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
                    </div>
                    @endforeach
                </div>
                <!-- end cart-items:wrapper -->
            </div>

            <!-- cart:detail -->
            <div class="md:w-4/12 w-full md:sticky md:top-24">
                <div class="space-y-5">
                    <div class="bg-gradient-to-b from-secondary to-background rounded-2xl px-5 pb-5">
                        <div class="bg-background rounded-b-3xl space-y-2 p-5 mb-5">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-1">
                                    <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                    <div class="w-2 h-2 bg-foreground rounded-full"></div>
                                </div>
                                <div class="font-black text-foreground">اطلاعات پرداخت</div>
                            </div>
                        </div>
                        <div class="space-y-5">
                            <form  wire:submit="checkDiscountCode(Object.fromEntries(new FormData($event.target)))">
                                <div class="flex items-center gap-3 relative">
                                                <span class="absolute right-3 text-muted">
                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                         fill="currentColor" class="w-5 h-5">
                                                        <path fill-rule="evenodd"
                                                              d="M4.5 2A2.5 2.5 0 0 0 2 4.5v3.879a2.5 2.5 0 0 0 .732 1.767l7.5 7.5a2.5 2.5 0 0 0 3.536 0l3.878-3.878a2.5 2.5 0 0 0 0-3.536l-7.5-7.5A2.5 2.5 0 0 0 8.38 2H4.5ZM5 6a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z"
                                                              clip-rule="evenodd"></path>
                                                    </svg>
                                                </span>
                                    <input type="text" name="code" wire:model="code"
                                           class="form-input w-full h-11 !ring-0 !ring-offset-0 bg-background border-0 focus:border-border rounded-xl text-sm text-foreground pr-10"
                                           placeholder="کد تخفیف" />
                                    <button type="submit"
                                            class="h-11 inline-flex items-center justify-center gap-1 bg-primary rounded-xl text-primary-foreground transition-all hover:opacity-80 px-4">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                             viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                             class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                                @error('code')
                                <div style="color: red;margin-right: 8px;margin-top: 10px">{{$message}}</div>
                                @enderror
                                @if(session()->has('success'))
                                    <div class="text-body-2  text-danger" style="color: #2bd02b;margin-top: 10px">{{session('success')}}</div>
                                @endif
                                @if(session()->has('error'))
                                    <div class="text-body-2  text-danger" style="color: red;margin-top: 10px">{{session('error')}}</div>
                                @endif
                            </form>
                            <div class="flex flex-col space-y-2">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="font-bold text-xs text-foreground">جمع کل</div>
                                    <div class="flex items-center gap-1">
                                        <span class="font-black text-base text-foreground">{{number_format($invoice['totalOriginalPrice'])}}</span>
                                        <span class="text-xs text-muted">تومان</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <div class="font-bold text-xs text-foreground">موجودی کیف پول</div>
                                    <div class="flex items-center gap-1">
                                        <span class="font-black text-base text-foreground">۵۲۰,۰۰۰</span>
                                        <span class="text-xs text-muted">تومان</span>
                                    </div>
                                </div>
                                @if($showDiscountCode)
                                <div class="flex items-center justify-between gap-3">
                                    <div class="font-bold text-xs text-green-500"> میزان تخفیف</div>
                                    <div class="flex items-center gap-1">
                                        <span class="font-black text-base text-green-500">{{number_format($discountCodeAmount)}}</span>
                                        <span class="text-xs text-muted">تومان</span>
                                    </div>
                                </div>
                            </div>
                            @endif
                            <div class="h-px bg-secondary"></div>
                            <div class="flex items-center justify-between gap-3 text-primary">
                                <div class="font-bold text-sm text-foreground">مبلغ قابل پرداخت</div>
                                <div class="flex items-center gap-1">
                                    <span class="font-black text-xl text-foreground">{{number_format($this->totalAmount)}}</span>
                                    <span class="text-xs text-muted">تومان</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" wire:click="goToOrderInfo"
                            class="w-full h-11 inline-flex items-center justify-center gap-1 bg-primary rounded-full text-primary-foreground transition-all hover:opacity-80 px-4">
                        <span class="font-semibold text-sm">تکمیل فرایند خرید</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                             class="w-5 h-5">
                            <path fill-rule="evenodd"
                                  d="M14.78 14.78a.75.75 0 0 1-1.06 0L6.5 7.56v5.69a.75.75 0 0 1-1.5 0v-7.5A.75.75 0 0 1 5.75 5h7.5a.75.75 0 0 1 0 1.5H7.56l7.22 7.22a.75.75 0 0 1 0 1.06Z"
                                  clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
            </div>
            <!-- end cart:detail -->
        </div>
        @else
            <div class="flex flex-col items-center justify-center space-y-12">
                <img src="/client/assets/images/theme/empty.svg" class="w-full max-w-xs opacity-35" alt="..." />
                <div class="text-center space-y-3">
                    <h2 class="font-bold text-xl text-foreground">
                        سبد خرید شما خالی است :(
                    </h2>
                </div>
            </div>
        @endif

    </div>
    <!-- end container -->

    <!-- modal:delete cart-item -->
    <div x-cloak x-show="modalOpen" class="fixed inset-0 z-50 overflow-y-auto" wire:ignore.self>
        <div class="flex items-center justify-center min-h-screen px-4">
            <!-- modal:box -->
            <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300 transform"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200 transform"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative w-full max-w-sm my-20 overflow-hidden transition-all transform bg-background border border-border rounded-2xl shadow-2xl z-20">
                <!-- modal:header -->
                <div class="relative p-4">
                    <!-- modal:close-button -->
                    <button
                        class="absolute left-4 text-muted-foreground focus:outline-none hover:text-error"
                        x-on:click="modalOpen = false">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12">
                            </path>
                        </svg>
                    </button>
                    <!-- end modal:close-button -->
                </div>
                <!-- end modal:header -->


                    <!-- modal:content -->
                    <div class="p-6" >
                        <div class="flex flex-col items-center justify-center space-y-5">
                            <div
                                class="flex items-center justify-center w-14 h-14 bg-error rounded-full text-error-foreground">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                </svg>
                            </div>
                            <h3 class="font-bold text-foreground">آیا از حذف دوره از سبد اطمینان دارید؟</h3>
                        </div>
                    </div>
                    <!-- end modal:content -->

                    <!-- modal:footer -->
                    <div class="flex items-center gap-x-4 border-t border-border p-4">
                        <button type="button"
                                class="flex items-center justify-center gap-x-2 w-full bg-background border border-border rounded-xl text-foreground py-2 px-4"
                                x-on:click="modalOpen = false">
                            <span class="font-bold text-xs">لغو</span>
                        </button>
                        <button type="submit" wire:click="deleteItem"
                                class="flex items-center justify-center gap-x-2 w-full bg-error border border-transparent rounded-xl text-error-foreground py-2 px-4"
                                x-on:click="modalOpen = false">
                            <span class="font-bold text-xs">آره،حذف کن</span>
                        </button>
                    </div>
                    <!-- end modal:footer -->
            </div>
            <!-- end modal:box -->

            <!-- modal:overlay -->
            <div class="fixed inset-0 bg-secondary/80 cursor-pointer transition-all z-10"
                 x-bind:class="modalOpen ? 'opacity-100 visible' : 'opacity-0 invisible'"
                 x-on:click="modalOpen = false">
            </div>
            <!-- end modal:overlay -->
        </div>
    </div>
    <!-- end modal:delete cart-item -->
</div>
