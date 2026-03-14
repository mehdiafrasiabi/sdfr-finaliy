<div>
    @if(session('paymentSuccess') || ($paymentData && $paymentData['status'] === 'completed'))
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-md">
                <!-- Payment Success Card -->
                <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-xl overflow-hidden">
                    <!-- Header with Icon -->
                    <div class="bg-gradient-to-br from-green-600 to-green-700 dark:from-green-900 dark:to-green-950 p-8 text-center relative">
                        <!-- Success Icon -->
                        <div
                            class="mx-auto w-20 h-20 bg-green-800/30 dark:bg-green-950/50 rounded-full flex items-center justify-center mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                 stroke="currentColor" class="w-10 h-10 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-white mb-2">پرداخت موفق</h1>
                        <p class="text-green-100 dark:text-green-200">سفارش شما با موفقیت ثبت شد</p>
                    </div>
                    <!-- Payment Details -->
                    <div class="p-6">
                        @if($paymentData)
                            <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-zinc-700">
                                <span class="text-gray-600 dark:text-gray-400">شماره سفارش:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $paymentData['order_number'] }}</span>
                            </div>
                            <div class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-zinc-700">
                                <span class="text-gray-600 dark:text-gray-400">تاریخ پرداخت:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ \Morilog\Jalali\Jalalian::fromDateTime($paymentData['updated_at'])->format('Y/m/d - H:i') }}</span>
                            </div>
                            <div
                                class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-zinc-700">
                                <span class="text-gray-600 dark:text-gray-400">مبلغ پرداختی:</span>
                                <span class="font-medium text-green-600 dark:text-green-400">{{ number_format($paymentData['amount']) }} <span
                                        class="text-sm">تومان</span></span>
                            </div>
                            <div class="flex justify-between items-center py-3">
                                <span class="text-gray-600 dark:text-gray-400">روش پرداخت:</span>
                                <span class="font-medium text-gray-900 dark:text-white flex items-center">
                                    @if(isset($paymentData['paymentMethod']) && $paymentData['paymentMethod'] === 'کیف پول')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor"
                                             class="w-5 h-5 ml-1 text-blue-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"

                                                  d="M21 12a2.25 2.25 0 00-2.25-2.25H15a3 3 0 11-6 0H5.25A2.25 2.25 0 003 12m18 0v6a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 18v-6m18 0V9M3 12V9m18 0a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 9m18 0V6a2.25 2.25 0 00-2.25-2.25H5.25A2.25 2.25 0 003 6v3"/>
                                        </svg>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5 ml-1">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                                        </svg>
                                    @endif
                                    {{ $paymentData['paymentMethod'] ?? 'کارت بانکی' }}
                                </span>
                            </div>
                        @endif
                        <!-- Info Message -->
                        <div
                            class="mt-6 bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-900/50 rounded-lg p-4">
                            <p class="text-blue-800 dark:text-blue-400 text-sm">
                                رسید پرداخت به ایمیل و پیامک شما ارسال شد. برای پیگیری سفارش می‌توانید به بخش
                                <a href="{{ route('client.profile.dashboard') }}" class="font-bold underline">سفارشات
                                    من</a> مراجعه کنید.
                            </p>
                        </div>
                        <!-- Action Buttons -->
                        <div class="mt-6 flex flex-col space-y-3">
                            <a href="{{ route('client.profile.dashboard') }}"
                               class="bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 text-white py-3 px-4 rounded-lg font-medium text-center flex items-center justify-center transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5 ml-2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>
                                </svg>
                                مشاهده سفارش
                            </a>
                            <a href="{{ route('client.home') }}"
                               class="border border-gray-300 dark:border-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-700 text-gray-700 dark:text-gray-300 py-3 px-4 rounded-lg font-medium text-center flex items-center justify-center transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5 ml-2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                                </svg>
                                بازگشت به خانه
                            </a>
                        </div>
                        <!-- Footer Note -->
                        <div class="mt-6 text-center">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                در صورت هرگونه سوال با پشتیبانی تماس بگیرید
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @elseif((session('paymentError')))
        <div class="min-h-screen flex items-center justify-center p-4">

            <div class="w-full max-w-md">

                <!-- Payment Error Card -->

                <div class="bg-white dark:bg-zinc-800 rounded-2xl shadow-xl overflow-hidden">

                    <!-- Header with Icon -->

                    <div
                        class="bg-gradient-to-br from-red-600 to-red-700 dark:from-red-900 dark:to-red-950 p-8 text-center relative">

                        <!-- Close Icon -->

                        <div
                            class="mx-auto w-20 h-20 bg-red-800/30 dark:bg-red-950/50 rounded-full flex items-center justify-center mb-4">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                                 stroke="currentColor" class="w-10 h-10 text-white">

                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>

                            </svg>

                        </div>

                        <h1 class="text-2xl font-bold text-white mb-2">پرداخت ناموفق</h1>

                        <p class="text-red-100 dark:text-red-200">متاسفانه پرداخت شما تکمیل نشد</p>

                    </div>


                    <!-- Payment Details -->

                    <div class="p-6">

                        @if($paymentData)

                            <div
                                class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-zinc-700">

                                <span class="text-gray-600 dark:text-gray-400">شماره سفارش:</span>

                                <span
                                    class="font-medium text-gray-900 dark:text-white">{{ $paymentData['order_number'] }}</span>

                            </div>

                            <div
                                class="flex justify-between items-center py-3 border-b border-gray-200 dark:border-zinc-700">

                                <span class="text-gray-600 dark:text-gray-400">زمان تلاش:</span>

                                <span
                                    class="font-medium text-gray-900 dark:text-white">{{ \Morilog\Jalali\Jalalian::fromDateTime($paymentData['created_at'])->format('Y/m/d - H:i') }}</span>

                            </div>

                            <div class="flex justify-between items-center py-3">

                                <span class="text-gray-600 dark:text-gray-400">مبلغ پرداخت:</span>

                                <span class="font-medium text-gray-900 dark:text-white">{{ number_format($paymentData['amount']) }} <span
                                        class="text-sm">تومان</span></span>

                            </div>

                        @endif



                        <!-- Error Reasons -->

                        <div
                            class="mt-6 bg-red-50 dark:bg-red-950/20 border border-red-200 dark:border-red-900/50 rounded-lg p-4">

                            <div class="flex items-start">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor"
                                     class="w-5 h-5 text-red-600 dark:text-red-500 mt-1 flex-shrink-0">

                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>

                                </svg>

                                <div class="mr-3">

                                    <h3 class="font-bold text-red-800 dark:text-red-400 mb-2">علت احتمالی:</h3>

                                    <ul class="list-disc pr-5 text-red-700 dark:text-red-500 text-sm space-y-1">

                                        <li>عدم موجودی کافی در حساب</li>

                                        <li>اطلاعات کارت نامعتبر</li>

                                        <li>مشکل موقت در درگاه پرداخت</li>

                                    </ul>

                                </div>

                            </div>

                        </div>


                        <!-- Info Message -->

                        <div
                            class="mt-4 bg-blue-50 dark:bg-blue-950/20 border border-blue-200 dark:border-blue-900/50 rounded-lg p-4">

                            <p class="text-blue-800 dark:text-blue-400 text-sm">

                                در صورتی که مبلغ از حساب شما کسر شده است، تا ۷۲ ساعت آینده به حساب شما باز خواهد گشت. در
                                غیر این صورت با پشتیبانی تماس بگیرید.

                            </p>

                        </div>


                        <!-- Action Buttons -->

                        <div class="mt-6 flex flex-col space-y-3">

                            @if($paymentData && isset($paymentData['payment_id']))
                                <button wire:click="retryPayment({{ $paymentData['payment_id'] }})"

                                        class="bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 text-white py-3 px-4 rounded-lg font-medium text-center flex items-center justify-center transition-colors">

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5 ml-2">

                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>

                                    </svg>

                                    تلاش مجدد برای پرداخت

                                </button>

                            @endif

                            <a href="{{ route('client.checkout.cart') }}"

                               class="border border-gray-300 dark:border-zinc-600 hover:bg-gray-50 dark:hover:bg-zinc-700 text-gray-700 dark:text-gray-300 py-3 px-4 rounded-lg font-medium text-center flex items-center justify-center transition-colors">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5 ml-2">

                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"/>

                                </svg>

                                بازگشت به سبد خرید

                            </a>

                        </div>


                        <!-- Support Contact -->

                        <div class="mt-6 text-center">

                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">برای راهنمایی بیشتر با پشتیبانی
                                تماس بگیرید</p>

                            <a href="tel:05135092160"
                               class="inline-flex items-center text-red-600 dark:text-red-400 font-medium">

                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-5 h-5 ml-1">

                                    <path stroke-linecap="round" stroke-linejoin="round"
                                          d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>

                                </svg>

                                ۰۵۱۳-۵۰۹-۲۱۶۰

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    @else
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">

            <!-- Main Content -->
            <div class="lg:col-span-9 md:col-span-8 md:order-1 order-2">
                <!-- sort & filter(offcanvas) -->
                <div class="flex items-center gap-3 mb-3" x-data="{ offcanvasOpen: false }">
                    <!-- sort -->
                    <div
                        x-data="{ range: function(start, end) { return Array(end - start + 1).fill().map((_, idx) => start + idx) } }">
                        <!-- form:select container -->
                        <div class="flex items-center gap-3">
                            <!-- form:select:label -->
                            <label class="sm:flex hidden items-center gap-1 font-semibold text-xs text-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                     class="w-5 h-5">
                                    <path
                                        d="M10 3.75a2 2 0 1 0-4 0 2 2 0 0 0 4 0ZM17.25 4.5a.75.75 0 0 0 0-1.5h-5.5a.75.75 0 0 0 0 1.5h5.5ZM5 3.75a.75.75 0 0 1-.75.75h-1.5a.75.75 0 0 1 0-1.5h1.5a.75.75 0 0 1 .75.75ZM4.25 17a.75.75 0 0 0 0-1.5h-1.5a.75.75 0 0 0 0 1.5h1.5ZM17.25 17a.75.75 0 0 0 0-1.5h-5.5a.75.75 0 0 0 0 1.5h5.5ZM9 10a.75.75 0 0 1-.75.75h-5.5a.75.75 0 0 1 0-1.5h5.5A.75.75 0 0 1 9 10ZM17.25 10.75a.75.75 0 0 0 0-1.5h-1.5a.75.75 0 0 0 0 1.5h1.5ZM14 10a2 2 0 1 0-4 0 2 2 0 0 0 4 0ZM10 16.25a2 2 0 1 0-4 0 2 2 0 0 0 4 0Z"/>
                                </svg>
                                مرتب سازی:
                            </label><!-- end form:select:label -->
                            <!-- form:select -->
                            <div class="w-52 relative"
                                 x-data="{ open: false, selectedOption: 'انتخاب کنید', selectedValue: '', options: ['جدید‌ترین', 'در حال برگزاری', 'تکمیل ضبط‌', 'دوره‌های خریداری شده', 'در حال مشاهده', 'قدیمی‌ترین'] }">
                                <select wire:model.live.debounce.350ms="categoryId" id="category"
                                        class="form-select w-full h-11 !ring-0 !ring-offset-0 bg-secondary border-border focus:border-border rounded-xl text-sm text-foreground px-5">
                                    <option value="">انتخاب پایه و رشته</option>
                                </select>
                            </div><!-- end form:select -->
                        </div><!-- end form:select container -->
                    </div>
                    <!-- end sort -->

                </div>

                <!-- articles:wrapper -->
                <div class="grid lg:grid-cols-3 sm:grid-cols-2 gap-x-5 gap-y-10">
                    <div class="flex flex-col items-center justify-center text-center col-span-full">
                        <img src="/client/assets/images/theme/empty.svg" class="w-full max-w-xs opacity-35"
                             alt="..."/>
                        <div class="text-center space-y-3">
                            <h2 class="font-bold text-xl text-foreground">مقاله ای وجود ندارد</h2>
                        </div>
                    </div>
                </div>
                <!-- end articles:wrapper -->

                <div class="flex justify-center mt-8">
                    <!-- load more:button -->
                    <button type="button"
                            class="h-11 inline-flex items-center justify-center gap-1 bg-secondary rounded-full text-primary px-8">
                        <span class="font-semibold text-sm">در حال بارگذاری</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                             stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                             class="w-5 h-5 animate-spin">
                            <path d="M21 12a9 9 0 1 1-6.219-8.56"/>
                        </svg>
                    </button>
                    <!-- end load more:button -->
                </div>
            </div>
        </div>
    @endif
</div>
