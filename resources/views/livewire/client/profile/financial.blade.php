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

                    <div class="space-y-4">
                        @if($payments->isEmpty())
                            <div class="flex flex-col items-center justify-center space-y-12 py-16">
                                <img src="/client/assets/images/theme/empty.svg" class="w-full max-w-xs opacity-35" alt="empty"/>
                                <div class="text-center space-y-3">
                                    <h2 class="font-bold text-xl text-foreground">
                                        تراکنشی برای شما وجود ندارد.
                                    </h2>
                                </div>
                            </div>
                        @else

                            @foreach($payments as $payment)

                                @php
                                    // اگر توی کامپوننت Livewire آرایه ساختی:
                                    // public array $expandedPayments = [];
                                    $isExpanded = in_array($payment->id, $expandedPayments ?? []);
                                @endphp

                                <div class="bg-secondary border border-border rounded-2xl overflow-hidden flex flex-col">

                                    <!-- Main Box -->
                                    <div class="p-4 flex-1 flex flex-col gap-4">

                                        <!-- بالا: آیکن + اطلاعات اصلی -->
                                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">

                                            <div class="flex items-center gap-4">
                                                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                              d="M3 10h18M7 15h1m4 0h1m-6 4h10a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>

                                                <div class="flex-1" style="margin-right: 10px">
                                                    <h3 class="font-bold text-foreground text-lg">
                                                        کد رهگیری: {{ $payment->order_number }}
                                                    </h3>

                                                    <p class="text-xs text-muted mt-1 whitespace-nowrap">
                                                        {{ jalali($payment->created_at)->format('%d %B %Y | H:i') }}
                                                    </p>

                                                    <div class="mt-3 flex flex-wrap items-center gap-2">
                                                        {{-- وضعیت --}}
                                                        @if($payment->status === 'completed')
                                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-600 dark:text-green-400 text-xs rounded-full">
                                            <span class="h-1.5 w-1.5 rounded-full bg-green-500"></span>
                                            پرداخت‌شده
                                        </span>
                                                        @elseif($payment->status === 'pending')
                                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 text-xs rounded-full">
                                            <span class="h-1.5 w-1.5 rounded-full bg-yellow-500"></span>
                                            در انتظار
                                        </span>
                                                        @else
                                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 text-xs rounded-full">
                                            <span class="h-1.5 w-1.5 rounded-full bg-red-500"></span>
                                            لغو شده
                                        </span>
                                                        @endif

                                                        {{-- مبلغ --}}
                                                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-primary/10 text-primary text-xs rounded-full">
                                        مبلغ: {{ number_format($payment->amount) }} تومان
                                    </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- پایین باکس: دکمه‌ها -->
                                        <div class="mt-2 pt-3 border-t border-border flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-2 md:gap-3">

                                            {{-- دکمه جزئیات مثل آزمون‌ها --}}
                                            <button
                                                wire:click="togglePaymentDetails({{ $payment->id }})"
                                                class="w-full sm:w-auto inline-flex items-center justify-between sm:justify-center gap-3 px-4 py-2.5 bg-background border border-border hover:bg-secondary rounded-xl font-semibold text-sm text-foreground transition-colors">
                                                <span class="md:hidden">مشاهده جزئیات</span>
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                     class="w-5 h-5 transition-transform {{ $isExpanded ? 'rotate-180' : '' }}"
                                                     fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Dropdown Details -->
                                    @if($isExpanded)
                                        <div class="border-t border-border bg-background/50 p-4">
                                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">

                                                <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                    <span class="text-xs text-muted">شماره صورتحساب</span>
                                                    <span class="font-bold text-foreground text-sm mt-1">
                                    {{ $payment->refNumber ?? 'وجود ندارد' }}
                                </span>
                                                </div>

                                                <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                    <span class="text-xs text-muted">شرح تراکنش</span>
                                                    <span class="font-bold text-foreground text-sm mt-1 text-center">
                                    خرید دوره
                                </span>
                                                </div>

                                                <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                    <span class="text-xs text-muted">محصولات</span>
                                                    <div class="font-bold text-foreground text-sm mt-1 text-center space-y-1">
                                                        @foreach($payment->order->orderItems as $item)
                                                            <div>{{ $item->product->name ?? 'محصول حذف شده' }}</div>
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <div class="flex flex-col items-center p-3 bg-secondary rounded-xl">
                                                    <span class="text-xs text-muted">ردیف</span>
                                                    <span class="font-bold text-foreground text-sm mt-1">
                                    {{ $loop->iteration + $payments->firstItem() - 1 }}
                                </span>
                                                </div>

                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <br>
                            @endforeach

                            <div class="p-5 text-xs text-muted whitespace-nowrap">
                                {{ $payments->links('layouts.client.pagination') }}
                            </div>
                        @endif
                    </div>
                    <div class="p-5 text-xs text-muted whitespace-nowrap text-white">
                        {{$payments->links('layouts.client.pagination')}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
