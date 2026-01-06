<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">

        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">

            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">

                <livewire:client.profile.sidebar/>

            </div>


            <div class="lg:col-span-9 md:col-span-8">

                <div class="space-y-6">


                    {{-- Wallet Guide --}}

                    <div class="bg-blue-50 dark:bg-blue-950/30 rounded-2xl overflow-hidden">

                        <div class="flex items-center justify-between p-4 cursor-pointer" x-data="{ open: true }"
                             @click="open = !open">

                            <div class="flex items-center gap-3">

                                <div class="w-8 h-8 bg-blue-500/20 rounded-full flex items-center justify-center">

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-blue-500">

                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>

                                    </svg>

                                </div>

                                <span class="font-bold text-blue-700 dark:text-blue-400">راهنمای کیف پول</span>

                            </div>

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                 stroke="currentColor" class="w-5 h-5 text-blue-500 transition-transform"
                                 :class="{ 'rotate-180': open }">

                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5"/>

                            </svg>

                        </div>

                        <div class="px-4 pb-4" x-show="open" x-collapse>

                            <p class="text-sm text-blue-600 dark:text-blue-300 leading-7">

                                این بخش اعتبار الکترونیکی کاربر را نمایش میدهد، شما میتوانید با پرداخت هزینه مورد نظر، کیف
                                پول خود را تا سقف بینهایت شارژ کنید.

                            </p>

                        </div>

                    </div>


                    <div class="grid md:grid-cols-12 gap-6">

                        {{-- Balance Card --}}

                        <div class="md:col-span-4">

                            <div
                                class="bg-gradient-to-br from-blue-400 via-blue-500 to-blue-600 dark:from-blue-600 dark:via-blue-700 dark:to-blue-800 rounded-2xl p-6 text-white h-full flex flex-col justify-center">

                                <div class="text-center">

                                    <p class="text-blue-100 mb-2">موجودی کیف پول</p>

                                    <div class="flex items-center justify-center gap-2">

                                        <span class="text-4xl font-black">{{ number_format($wallet->balance) }}</span>

                                        <span class="text-lg">تومان</span>

                                    </div>

                                </div>

                            </div>

                        </div>


                        {{-- Charge & Gift Code Section --}}

                        <div class="md:col-span-8 space-y-4">

                            {{-- Charge Section --}}

                            <div class="bg-secondary dark:bg-zinc-800 rounded-2xl p-5">

                                <div class="flex items-center gap-3 mb-4">

                                    <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center">

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-500">

                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>

                                        </svg>

                                    </div>

                                    <span class="font-bold text-foreground">شارژ کیف پول</span>

                                </div>

                                <div class="flex items-center gap-3">

                                    <div class="flex-1">

                                        <label class="text-sm text-muted mb-2 block">مبلغ دلخواه</label>

                                        <input type="text"

                                               wire:model="chargeAmount"

                                               class="w-full bg-background dark:bg-zinc-700 border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:ring-2 focus:ring-blue-500"

                                               placeholder="مبلغ به تومان">

                                    </div>

                                    <button wire:click="chargeWallet"

                                            wire:loading.attr="disabled"

                                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 mt-6 transition-colors disabled:opacity-50">

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5"
                                             wire:loading.class="animate-spin" wire:target="chargeWallet">

                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/>

                                        </svg>

                                        <span>پرداخت</span>

                                    </button>

                                </div>

                            </div>


                            {{-- Gift Code Section --}}

                            <div class="bg-secondary dark:bg-zinc-800 rounded-2xl p-5">

                                <div class="flex items-center gap-3 mb-4">

                                    <div class="w-10 h-10 bg-purple-500/20 rounded-xl flex items-center justify-center">

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-purple-500">

                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M21 11.25v8.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1 0 9.375 7.5H12m0-2.625V7.5m0-2.625A2.625 2.625 0 1 1 14.625 7.5H12m0 0V21m-8.625-9.75h18c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125h-18c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>

                                        </svg>

                                    </div>

                                    <span class="font-bold text-foreground">کد هدیه</span>

                                </div>

                                <div class="flex items-center gap-3">

                                    <div class="flex-1">

                                        <label class="text-sm text-muted mb-2 block">کد هدیه</label>

                                        <input type="text"

                                               wire:model="giftCode"

                                               class="w-full bg-background dark:bg-zinc-700 border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:ring-2 focus:ring-purple-500"

                                               placeholder="کد هدیه را وارد کنید">

                                    </div>

                                    <button wire:click="applyGiftCode"

                                            wire:loading.attr="disabled"

                                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 mt-6 transition-colors disabled:opacity-50">

                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5"
                                             wire:loading.class="animate-spin" wire:target="applyGiftCode">

                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>

                                        </svg>

                                        <span>ثبت</span>

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- Transaction History --}}

                    <div class="space-y-5">

                        <div class="flex items-center gap-3">

                            <div class="flex items-center gap-1">

                                <div class="w-1 h-1 bg-foreground rounded-full"></div>

                                <div class="w-2 h-2 bg-foreground rounded-full"></div>

                            </div>

                            <div class="font-black text-foreground">لیست تراکنش‌ها</div>

                        </div>


                        <div class="bg-secondary dark:bg-zinc-800 rounded-2xl overflow-hidden">

                            @if($transactions->isNotEmpty())

                                <div class="overflow-x-auto">

                                    <table class="w-full text-sm text-right">

                                        <thead
                                            class="text-xs text-muted uppercase bg-background dark:bg-zinc-900 border-b border-border">

                                        <tr>

                                            <th class="whitespace-nowrap p-5">مبلغ</th>

                                            <th class="whitespace-nowrap p-5">تاریخ ثبت</th>

                                            <th class="whitespace-nowrap p-5">انقضا</th>

                                            <th class="whitespace-nowrap p-5">توضیحات</th>

                                        </tr>

                                        </thead>

                                        <tbody>

                                        @foreach($transactions as $transaction)

                                            <tr class="odd:bg-secondary dark:odd:bg-zinc-800 even:bg-background dark:even:bg-zinc-900 border-b border-border last:border-0">

                                                <td class="p-5">

                                                    <span
                                                        class="font-bold {{ in_array($transaction->type, ['deposit', 'gift', 'refund']) ? 'text-green-500' : 'text-red-500' }}">

                                                        {{ in_array($transaction->type, ['deposit', 'gift', 'refund']) ? '+' : '-' }}{{ number_format($transaction->amount) }}

                                                    </span>

                                                    <span class="text-xs text-muted">تومان</span>

                                                </td>

                                                <td class="p-5 text-muted">

                                                    {{ jalali($transaction->created_at)->format('%d %B %Y') }}

                                                </td>

                                                <td class="p-5 text-muted">-</td>

                                                <td class="p-5">

                                                    <span
                                                        class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-{{ $transaction->type_color }}-100 dark:bg-{{ $transaction->type_color }}-900/30 text-{{ $transaction->type_color }}-600 dark:text-{{ $transaction->type_color }}-400">

                                                        {{ $transaction->type_text }}

                                                    </span>

                                                    @if($transaction->description)

                                                        <p class="text-xs text-muted mt-1">{{ $transaction->description }}</p>

                                                    @endif

                                                </td>

                                            </tr>

                                        @endforeach

                                        </tbody>

                                    </table>

                                </div>

                                <div class="p-5">

                                    {{ $transactions->links('layouts.client.pagination') }}

                                </div>

                            @else

                                <div class="flex flex-col items-center justify-center py-16 space-y-4">

                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                                         stroke="currentColor" class="w-24 h-24 text-muted opacity-30">

                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/>

                                    </svg>

                                    <p class="text-muted font-bold">هیچ تراکنشی یافت نشد</p>

                                </div>

                            @endif

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


</div>
