<div>
    @if ($paymentData || session('paymentError'))
        <div class="min-h-screen flex items-center justify-center p-4">
            <div class="w-full max-w-md">
                <div class="overflow-hidden rounded-2xl bg-white shadow-xl dark:bg-zinc-800">
                    <div class="p-8 text-center relative {{ $isSuccessful ? 'bg-gradient-to-br from-green-600 to-green-700 dark:from-green-900 dark:to-green-950' : 'bg-gradient-to-br from-red-600 to-red-700 dark:from-red-900 dark:to-red-950' }}">
                        <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-full {{ $isSuccessful ? 'bg-green-800/30 dark:bg-green-950/50' : 'bg-red-800/30 dark:bg-red-950/50' }}">
                            @if ($isSuccessful)
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-10 w-10 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="h-10 w-10 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            @endif
                        </div>

                        <h1 class="mb-2 text-2xl font-bold text-white">{{ $isSuccessful ? 'پرداخت موفق' : 'پرداخت ناموفق' }}</h1>
                        <p class="{{ $isSuccessful ? 'text-green-100 dark:text-green-200' : 'text-red-100 dark:text-red-200' }}">{{ $statusSubtitle }}</p>
                    </div>

                    <div class="p-6">
                        @if ($paymentData)
                            <div class="flex items-center justify-between border-b border-gray-200 py-3 dark:border-zinc-700">
                                <span class="text-gray-600 dark:text-gray-400">شماره سفارش:</span>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $paymentData['order_number'] }}</span>
                            </div>
                            <div class="flex items-center justify-between border-b border-gray-200 py-3 dark:border-zinc-700">
                                <span class="text-gray-600 dark:text-gray-400">{{ $isSuccessful ? 'تاریخ پرداخت:' : 'زمان تلاش:' }}</span>
                                <span class="font-medium text-gray-900 dark:text-white">
                                    {{ \Morilog\Jalali\Jalalian::fromDateTime($isSuccessful ? $paymentData['updated_at'] : $paymentData['created_at'])->format('Y/m/d - H:i') }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between border-b border-gray-200 py-3 dark:border-zinc-700">
                                <span class="text-gray-600 dark:text-gray-400">{{ $isSuccessful ? 'مبلغ پرداختی:' : 'مبلغ پرداخت:' }}</span>
                                <span class="font-medium {{ $isSuccessful ? 'text-green-600 dark:text-green-400' : 'text-gray-900 dark:text-white' }}">
                                    {{ number_format($paymentData['amount']) }} <span class="text-sm">تومان</span>
                                </span>
                            </div>
                            <div class="flex items-center justify-between py-3">
                                <span class="text-gray-600 dark:text-gray-400">روش پرداخت:</span>
                                <span class="flex items-center font-medium text-gray-900 dark:text-white">
                                    {{ $paymentData['paymentMethod'] ?? 'کارت بانکی' }}
                                </span>
                            </div>
                        @endif

                        <div class="mt-6 rounded-lg border p-4 {{ $isSuccessful ? 'border-blue-200 bg-blue-50 dark:border-blue-900/50 dark:bg-blue-950/20' : 'border-red-200 bg-red-50 dark:border-red-900/50 dark:bg-red-950/20' }}">
                            <p class="text-sm {{ $isSuccessful ? 'text-blue-800 dark:text-blue-400' : 'text-red-700 dark:text-red-400' }}">
                                {{ $infoMessage }}
                            </p>
                        </div>

                        @unless ($isSuccessful)
                            <div class="mt-4 rounded-lg border border-blue-200 bg-blue-50 p-4 dark:border-blue-900/50 dark:bg-blue-950/20">
                                <p class="text-sm text-blue-800 dark:text-blue-400">
                                    در صورتی که مبلغ از حساب شما کسر شده است، معمولاً حداکثر تا ۷۲ ساعت آینده به حساب شما بازمی‌گردد. اگر بازنگشت، با پشتیبانی تماس بگیرید.
                                </p>
                            </div>
                        @endunless

                        <div class="mt-6 flex flex-col space-y-3">
                            @if ($primaryActionIsRetry && $paymentData && isset($paymentData['payment_id']))
                                <button wire:click="retryPayment({{ $paymentData['payment_id'] }})"
                                        class="flex items-center justify-center rounded-lg bg-red-600 px-4 py-3 text-center font-medium text-white transition-colors hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800">
                                    {{ $primaryActionLabel }}
                                </button>
                            @elseif ($primaryActionLabel && $primaryActionUrl)
                                <a href="{{ $primaryActionUrl }}"
                                   class="flex items-center justify-center rounded-lg {{ $isSuccessful ? 'bg-green-600 hover:bg-green-700 dark:bg-green-700 dark:hover:bg-green-800 text-white' : 'bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 text-white' }} px-4 py-3 text-center font-medium transition-colors">
                                    {{ $primaryActionLabel }}
                                </a>
                            @endif

                            @if ($secondaryActionLabel && $secondaryActionUrl)
                                <a href="{{ $secondaryActionUrl }}"
                                   class="flex items-center justify-center rounded-lg border border-gray-300 px-4 py-3 text-center font-medium text-gray-700 transition-colors hover:bg-gray-50 dark:border-zinc-600 dark:text-gray-300 dark:hover:bg-zinc-700">
                                    {{ $secondaryActionLabel }}
                                </a>
                            @endif
                        </div>

                        <div class="mt-6 text-center">
                            <p class="mb-2 text-sm text-gray-600 dark:text-gray-400">برای راهنمایی بیشتر با پشتیبانی تماس بگیرید</p>
                            <a href="tel:05135092160" class="inline-flex items-center font-medium {{ $isSuccessful ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                                ۰۵۱۳-۵۰۹-۲۱۶۰
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="grid grid-cols-1 items-start gap-5 md:grid-cols-12">
            <div class="order-2 md:order-1 md:col-span-8 lg:col-span-9">
                <div class="grid gap-x-5 gap-y-10 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="col-span-full flex flex-col items-center justify-center text-center">
                        <img src="/client/assets/images/theme/empty.svg" class="w-full max-w-xs opacity-35" alt="..." />
                        <div class="space-y-3 text-center">
                            <h2 class="text-xl font-bold text-foreground">تراکنشی برای نمایش پیدا نشد.</h2>
                        </div>
                    </div>
                </div>

                <div class="mt-8 flex justify-center">
                    <a href="{{ route('client.purchase') }}"
                       class="inline-flex h-11 items-center justify-center gap-1 rounded-full bg-secondary px-8 text-primary">
                        <span class="text-sm font-semibold">بازگشت به خرید</span>
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>
