<div>
    <div class="max-w-7xl space-y-14 px-4 mx-auto">
        <div class="grid md:grid-cols-12 grid-cols-1 items-start gap-5">
            <div class="lg:col-span-3 md:col-span-4 md:sticky md:top-24">
                <livewire:client.profile.sidebar/>
            </div>
            <div class="lg:col-span-9 md:col-span-8">
                <div
                    class="space-y-6">
                    {{-- Wallet Guide --}}
                    <!-- section:title -->
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-1">
                            <div class="w-1 h-1 bg-foreground rounded-full"></div>
                            <div class="w-2 h-2 bg-foreground rounded-full"></div>
                        </div>
                        <div class="font-black text-foreground">کیف پول</div>
                    </div>
                    <!-- end section:title -->
                    <!-- Guide Section -->
                    <div dir="rtl" x-data="collapseGuide('wallet-guide')" x-init="init()"
                         class="rounded-2xl border border-border bg-primary  overflow-hidden transition-all">
                        <!-- HEADER -->
                        <button @click="toggle"
                                class="w-full flex items-center justify-between px-4 md:px-6 py-4 transition">
                            <!-- title -->
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-white dark:text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm1 14h-2v-2h2v2zm0-4h-2V6h2v6z"/>
                                </svg>
                                <span class="font-black text-white dark:text-white text-blue-300 md:text-lg">
                                    راهنمای کیف پول
                                </span>
                            </div>
                            <!-- arrow -->
                            <svg class="w-5 h-5 text-white transition-transform duration-300"
                                 :class="open && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <!-- CONTENT -->
                        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-600"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-1" class="px-4 md:px-6 pb-6">
                            <div class="flex flex-col md:flex-row-reverse gap-6 items-center mt-2">
                                <!-- IMAGE -->
                                <div class="relative w-full md:w-[280px] shrink-0 order-2 md:order-1">
                                    <img
                                        src="/client/assets/images/blog/sdfr.jpg" class="w-full h-[200px] md:h-[180px] object-cover rounded-xl">
                                    <button type="button" id="57612318744"
                                            data-video-url="https://www.aparat.com/video/video/embed/videohash/utg98i1/vt/frame?titleShow=true&recom=self"
                                            allowFullScreen="true" webkitallowfullscreen="true"
                                            mozallowfullscreen="true" data-video-title="راهنمای کیف پول"
                                            class="absolute inset-0 flex items-center justify-center">
                                        <span
                                            class="w-14 h-14 rounded-full bg-white/90 dark:bg-black/60
                                            flex items-center justify-center shadow-lg transition">
                                            <svg
                                                class="w-7 h-7 text-blue-600 mr-1" fill="currentColor"
                                                viewBox="0 0 24 24">
                                                <path d="M8 5v14l11-7z"/>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                                <!-- TEXT -->
                                <div
                                    class="flex-1 text-right text-sm md:text-base  text-white dark:text-white leading-7 order-1 md:order-2">
                                    دانش‌آموز عزیز سلام، قبل از شرکت در آزمون موارد زیر را با دقت مطالعه کنید: <br>•
                                    استفاده از آخرین نسخه مرورگر کروم الزامی است. <br>• حتماً قبل از خروج ثبت نهایی
                                    انجام شود. <br>• پس از ورود به هر دفترچه امکان بازگشت وجود ندارد. <br>• دفترچه
                                    آزمایشی ممکن است در پایان نمایش داده شود.
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Guide Section -->
                    <div class="grid md:grid-cols-12 gap-6">
                        {{-- Balance Card --}}
                        <div class="md:col-span-4">
                            <div
                                class="bg-gradient-to-br from-blue-400 via-blue-500 to-blue-600 dark:from-blue-600 dark:via-blue-700 dark:to-blue-800 rounded-2xl p-6 text-white h-full flex flex-col justify-center">
                                <div class="text-center">
                                    <img src="/client/assets/images/wallet.webp" class="w-25 h-25">
                                    <div class="flex items-center justify-center gap-2"><span
                                            class="text-2xl font-black">{{ number_format($wallet->balance) }}</span>
                                        <svg width="24" height="24" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg" strokeWidth="2" class="text-neutral-30 w-6 h-6"><path d="M10.615 5.19403C10.615 5.70736 10.531 6.18803 10.363 6.63603C10.2043 7.09336 9.97568 7.49003 9.67701 7.82603C9.37834 8.16203 9.01434 8.42336 8.58501 8.61003C8.15567 8.80603 7.67501 8.90403 7.14301 8.90403H6.28901C5.24368 8.90403 4.43167 8.58203 3.85301 7.93803C3.27434 7.29403 2.98501 6.41203 2.98501 5.29203V2.81403H4.11901V5.25003C4.11901 5.97803 4.30101 6.56603 4.66501 7.01403C5.02901 7.46203 5.62634 7.68603 6.45701 7.68603H7.07301C7.49301 7.68603 7.85234 7.6207 8.15101 7.49003C8.45901 7.35936 8.71101 7.18203 8.90701 6.95803C9.10301 6.73403 9.24768 6.4727 9.34101 6.17403C9.43434 5.87536 9.48101 5.5627 9.48101 5.23603V1.47003H10.615V5.19403ZM7.28301 1.20403H5.91101V2.86102e-05H7.28301V1.20403Z" fill="currentColor"></path> <path d="M1.904 15.848C1.652 15.848 1.40933 15.8153 1.176 15.75C0.942667 15.6753 0.737334 15.554 0.56 15.386C0.392 15.218 0.256667 14.9987 0.154 14.728C0.0513336 14.448 0 14.1027 0 13.692V7.168H1.148V13.44C1.148 13.7947 1.22267 14.0793 1.372 14.294C1.53067 14.5087 1.778 14.616 2.114 14.616H2.394C2.59933 14.616 2.702 14.8167 2.702 15.218C2.702 15.638 2.59933 15.848 2.394 15.848H1.904Z" fill="currentColor"></path> <path d="M2.57994 14.616C3.28927 14.616 3.64394 14.3173 3.64394 13.72V13.286C3.64394 12.4927 3.84927 11.8673 4.25994 11.41C4.67994 10.9433 5.2586 10.71 5.99594 10.71C6.3786 10.71 6.7146 10.7753 7.00394 10.906C7.29327 11.0273 7.53594 11.2 7.73194 11.424C7.92794 11.648 8.0726 11.9187 8.16594 12.236C8.2686 12.544 8.31994 12.8847 8.31994 13.258C8.31994 14.0793 8.10994 14.7187 7.68994 15.176C7.26994 15.624 6.69127 15.848 5.95394 15.848C5.5806 15.848 5.22127 15.778 4.87594 15.638C4.53994 15.4887 4.2786 15.2413 4.09194 14.896C4.00794 15.092 3.90527 15.2553 3.78394 15.386C3.6626 15.5073 3.5366 15.6053 3.40594 15.68C3.27527 15.7453 3.13527 15.792 2.98594 15.82C2.84594 15.8387 2.7106 15.848 2.57994 15.848H2.39794C2.2766 15.848 2.19727 15.8013 2.15994 15.708C2.11327 15.6053 2.08994 15.456 2.08994 15.26C2.08994 15.036 2.11327 14.8727 2.15994 14.77C2.19727 14.6673 2.2766 14.616 2.39794 14.616H2.57994ZM7.19994 13.342C7.19994 12.9313 7.1066 12.5953 6.91994 12.334C6.7426 12.0727 6.42527 11.942 5.96794 11.942C5.54794 11.942 5.23527 12.068 5.02994 12.32C4.83394 12.572 4.73594 12.9267 4.73594 13.384C4.73594 13.7947 4.84794 14.1027 5.07194 14.308C5.30527 14.5133 5.59927 14.616 5.95394 14.616C6.37394 14.616 6.6866 14.5087 6.89194 14.294C7.09727 14.07 7.19994 13.7527 7.19994 13.342Z" fill="currentColor"></path> <path d="M11.0998 18.018C11.4825 18.018 11.8185 17.9573 12.1078 17.836C12.3971 17.724 12.6351 17.57 12.8218 17.374C13.0178 17.178 13.1671 16.9493 13.2698 16.688C13.3725 16.4267 13.4331 16.1467 13.4518 15.848H12.2618C11.7858 15.848 11.3938 15.7967 11.0858 15.694C10.7778 15.5913 10.5351 15.442 10.3578 15.246C10.1805 15.05 10.0545 14.812 9.97981 14.532C9.91448 14.2427 9.88181 13.9207 9.88181 13.566C9.88181 13.2113 9.93314 12.8753 10.0358 12.558C10.1385 12.2313 10.2878 11.9467 10.4838 11.704C10.6798 11.4613 10.9225 11.27 11.2118 11.13C11.5105 10.9807 11.8558 10.906 12.2478 10.906C12.5558 10.906 12.8498 10.9573 13.1298 11.06C13.4191 11.1627 13.6711 11.326 13.8858 11.55C14.1005 11.7647 14.2685 12.0493 14.3898 12.404C14.5205 12.7587 14.5858 13.188 14.5858 13.692V14.616H15.8318C15.9438 14.616 16.0185 14.6673 16.0558 14.77C16.1025 14.8633 16.1258 15.0127 16.1258 15.218C16.1258 15.4327 16.1025 15.5913 16.0558 15.694C16.0185 15.7967 15.9438 15.848 15.8318 15.848H14.5578C14.5391 16.3053 14.4458 16.7393 14.2778 17.15C14.1191 17.5607 13.8951 17.92 13.6058 18.228C13.3165 18.536 12.9665 18.7787 12.5558 18.956C12.1451 19.1427 11.6878 19.236 11.1838 19.236H9.72781L9.64381 18.018H11.0998ZM10.9738 13.496C10.9738 13.8973 11.0671 14.1867 11.2538 14.364C11.4498 14.532 11.8231 14.616 12.3738 14.616H13.4798V13.748C13.4798 13.16 13.3631 12.74 13.1298 12.488C12.9058 12.2267 12.5885 12.096 12.1778 12.096C11.7951 12.096 11.4965 12.222 11.2818 12.474C11.0765 12.7167 10.9738 13.0573 10.9738 13.496Z" fill="currentColor"></path> <path d="M17.1114 14.616C17.4474 14.616 17.7087 14.5273 17.8954 14.35C18.0914 14.1727 18.1894 13.9207 18.1894 13.594V11.802H19.3374V13.65C19.3374 14.3593 19.1461 14.9053 18.7634 15.288C18.3901 15.6613 17.8674 15.848 17.1954 15.848H15.8374C15.7161 15.848 15.6367 15.8013 15.5994 15.708C15.5527 15.6053 15.5294 15.456 15.5294 15.26C15.5294 15.036 15.5527 14.8727 15.5994 14.77C15.6367 14.6673 15.7161 14.616 15.8374 14.616H17.1114ZM19.4354 10.08H18.1614V8.904H19.4354V10.08ZM17.5594 10.08H16.2854V8.904H17.5594V10.08Z" fill="currentColor"></path></svg>
                                    </div>
                                </div>
                            </div>
                        </div> {{-- Charge & Gift Code Section --}}
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
                                    <div class="flex-1"><label class="text-sm text-muted mb-2 block">مبلغ دلخواه</label>
                                        <input type="text" wire:model="chargeAmount"
                                               class="w-full bg-background dark:bg-zinc-700 border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:ring-2 focus:ring-blue-500"
                                               placeholder="مبلغ به تومان"></div>
                                    <button wire:click="chargeWallet" wire:loading.attr="disabled"
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
                            </div> {{-- Gift Code Section --}}
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
                                    <div class="flex-1"><label class="text-sm text-muted mb-2 block">کد هدیه</label>
                                        <input type="text" wire:model="giftCode"
                                               class="w-full bg-background dark:bg-zinc-700 border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:ring-2 focus:ring-purple-500"
                                               placeholder="کد هدیه را وارد کنید">
                                    </div>
                                    <button wire:click="applyGiftCode" wire:loading.attr="disabled"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 mt-6 transition-colors disabled:opacity-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5"
                                             wire:loading.class="animate-spin" wire:target="applyGiftCode">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="m4.5 12.75 6 6 9-13.5"/>
                                        </svg>
                                        <span>ثبت</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div> {{-- Transaction History --}}
                    <div class="space-y-5">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-1">
                                <div class="w-1 h-1 bg-foreground rounded-full"></div>
                                <div class="w-2 h-2 bg-foreground rounded-full"></div>
                            </div>
                            <div class="font-black text-foreground">لیست تراکنش‌ها</div>
                        </div>
                        <div
                            class="bg-secondary dark:bg-zinc-800 rounded-2xl overflow-hidden">
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
                                                <td class="p-5"><span
                                                        class="font-bold {{ in_array($transaction->type, ['deposit', 'gift', 'refund']) ? 'text-green-500' : 'text-red-500' }}">
                                                        {{ in_array($transaction->type, ['deposit', 'gift', 'refund']) ? '+' : '-' }}{{ number_format($transaction->amount) }}
                                                    </span>
                                                    <span class="text-xs text-muted">تومان</span>
                                                </td>
                                                <td class="p-5 text-muted">{{ jalali($transaction->created_at)->format('%d %B %Y') }}</td>
                                                <td class="p-5 text-muted">-</td>
                                                <td class="p-5"><span
                                                        class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-{{ $transaction->type_color }}-100 dark:bg-{{ $transaction->type_color }}-900/30 text-{{ $transaction->type_color }}-600 dark:text-{{ $transaction->type_color }}-400">
                                                        {{ $transaction->type_text }}
                                                    </span> @if($transaction->description)
                                                        <p class="text-xs text-muted mt-1">{{ $transaction->description }}</p>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div
                                    class="p-5">
                                    {{ $transactions->links('layouts.client.pagination') }}
                                </div>
                            @else
                                <div class="flex flex-col items-center justify-center py-16 space-y-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1" stroke="currentColor" class="w-24 h-24 text-muted opacity-30">
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
