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

                    <div class="relative   @if($payments->isNotEmpty()) container overflow-x-auto @endif">
                        <table class="w-full text-sm text-right">
                            @if($payments->isNotEmpty())
                                <thead
                                    class="text-xs text-muted uppercase bg-background border-b border-border">
                                <tr>
                                    <th class="whitespace-nowrap p-5">ردیف</th>
                                    <th class="whitespace-nowrap p-5">کدرهگیری</th>
                                    <th class="whitespace-nowrap p-5">شرح تراکنش</th>
                                    <th class="whitespace-nowrap p-5">شماره صورتحساب</th>
                                    <th class="whitespace-nowrap p-5">مبلغ</th>
                                    <th class="whitespace-nowrap p-5">وضعیت پرداخت</th>
                                    <th class="whitespace-nowrap p-5">تاریخ</th>
                                </tr>
                                </thead>


                                    <tbody>
                                    @foreach($payments as $payment)
                                    <tr class="odd:bg-secondary even:bg-background">
                                        <td class="p-5">
                                            <div
                                                class="font-black text-sm text-foreground">{{$loop->iteration + $payments->firstItem() - 1}}</div>
                                        </td>

                                        <td class="p-5">
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="font-black text-sm text-foreground ">{{ $payment->order_number }}</span>
                                            </div>
                                        </td>
                                        <td class="p-5">
                                            <div class="flex flex-col items-start gap-1 w-36">
                                                <span class="font-bold text-xs text-muted">خرید دوره</span>
                                                <span class="font-black text-sm text-foreground line-clamp-1">
                                                        @foreach($payment->order->orderItems as $item)
                                                         <div class="text-sm text-gray-800">
                                                                {{ $item->product->title ?? 'محصول حذف شده' }}
                                                         </div>
                                                         @endforeach
                                                </span>
                                            </div>
                                        </td>
                                        <td class="p-5">
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="font-black text-sm text-foreground ">{{ $payment->refNumber ?? 'وجود ندارد' }}</span>
                                            </div>
                                        </td>
                                        <td class="p-5">
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="font-black text-sm text-foreground line-clamp-1">{{number_format($payment->amount)}}</span>
                                            </div>
                                        </td>
                                        <td class="p-5">
                                            <div class="flex items-center gap-2">
                                                @if($payment->status === 'completed')
                                                    <div class="flex-shrink-0 rounded-full bg-green-500/20 p-1">
                                                        <div class="h-1.5 w-1.5 rounded-full bg-green-500"></div>
                                                    </div>
                                                    <span class="font-bold text-green-500">پرداخت‌شده</span>
                                                @elseif($payment->status === 'pending')
                                                    <div class="flex-shrink-0 rounded-full bg-yellow-500/20 p-1">
                                                        <div class="h-1.5 w-1.5 rounded-full bg-yellow-500"></div>
                                                    </div>
                                                    <span class="font-bold text-yellow-500">در انتظار</span>
                                                @else
                                                    <div class="flex-shrink-0 rounded-full bg-red-500-500/20 p-1">
                                                        <div class="h-1.5 w-1.5 rounded-full bg-red-500"></div>
                                                    </div>
                                                    <span class="font-bold text-red-500">لغو شده</span>
                                                @endif

                                            </div>
                                        </td>
                                        <td class="p-5">
                                            <div class="text-xs text-muted whitespace-nowrap">
                                                {{jalali($payment->created_at)->format('%d %B %Y | H:i')}}
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                    </tbody>

                            @else
                                <div class="flex flex-col items-center justify-center space-y-12">
                                    <img src="/client/assets/images/theme/empty.svg" class="w-full max-w-xs opacity-35"
                                         alt="..."/>
                                    <div class="text-center space-y-3">
                                        <h2 class="font-bold text-xl text-foreground">
                                            برنامه مشاوره ای برای شما وجود ندارد.
                                        </h2>
                                    </div>
                                </div>
                            @endif

                        </table>

                    </div>
                    <div class="p-5 text-xs text-muted whitespace-nowrap text-white">
                        {{$payments->links('layouts.client.pagination')}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
