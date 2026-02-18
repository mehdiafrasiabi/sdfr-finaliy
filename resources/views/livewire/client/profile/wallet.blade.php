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
                            </div>                                <!-- arrow -->
                            <svg class="w-5 h-5 text-white transition-transform duration-300"
                                 :class="open && 'rotate-180'" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>                            <!-- CONTENT -->
                        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-600"
                             x-transition:enter-start="opacity-0 -translate-y-2"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="opacity-100 translate-y-0"
                             x-transition:leave-end="opacity-0 -translate-y-1" class="px-4 md:px-6 pb-6">
                            <div class="flex flex-col md:flex-row-reverse gap-6 items-center mt-2">
                                <!-- IMAGE -->
                                <div class="relative w-full md:w-[280px] shrink-0 order-2 md:order-1"><img
                                        src="/client/assets/images/blog/sdfr.jpg"
                                        class="w-full h-[200px] md:h-[180px] object-cover rounded-xl">
                                    <button type="button" id="57612318744"
                                            data-video-url="https://www.aparat.com/video/video/embed/videohash/utg98i1/vt/frame?titleShow=true&recom=self"
                                            allowFullScreen="true" webkitallowfullscreen="true"
                                            mozallowfullscreen="true" data-video-title="راهنمای کیف پول"
                                            class="absolute inset-0 flex items-center justify-center"><span
                                            class="w-14 h-14 rounded-full bg-white/90 dark:bg-black/60                         flex items-center justify-center shadow-lg transition">                  <svg
                                                class="w-7 h-7 text-blue-600 mr-1" fill="currentColor"
                                                viewBox="0 0 24 24">                      <path d="M8 5v14l11-7z"/>                  </svg>              </span>
                                    </button>
                                </div>                                    <!-- TEXT -->
                                <div
                                    class="flex-1 text-right text-sm md:text-base  text-white dark:text-white leading-7 order-1 md:order-2">
                                    دانش‌آموز عزیز سلام، قبل از شرکت در آزمون موارد زیر را با دقت مطالعه کنید: <br>•
                                    استفاده از آخرین نسخه مرورگر کروم الزامی است. <br>• حتماً قبل از خروج ثبت نهایی
                                    انجام شود. <br>• پس از ورود به هر دفترچه امکان بازگشت وجود ندارد. <br>• دفترچه
                                    آزمایشی ممکن است در پایان نمایش داده شود.
                                </div>
                            </div>
                        </div>
                    </div>                      <!-- End Guide Section -->
                    <div class="grid md:grid-cols-12 gap-6">                            {{-- Balance Card --}}
                        <div class="md:col-span-4">
                            <div
                                class="bg-gradient-to-br from-blue-400 via-blue-500 to-blue-600 dark:from-blue-600 dark:via-blue-700 dark:to-blue-800 rounded-2xl p-6 text-white h-full flex flex-col justify-center">
                                <div class="text-center"><p class="text-blue-100 mb-2">موجودی کیف پول</p>
                                    <div class="flex items-center justify-center gap-2"><span
                                            class="text-4xl font-black">{{ number_format($wallet->balance) }}</span>
                                        <span class="text-lg">تومان</span></div>
                                </div>
                            </div>
                        </div> {{-- Charge & Gift Code Section --}}
                        <div class="md:col-span-8 space-y-4">                                {{-- Charge Section --}}
                            <div class="bg-secondary dark:bg-zinc-800 rounded-2xl p-5">
                                <div class="flex items-center gap-3 mb-4">
                                    <div class="w-10 h-10 bg-blue-500/20 rounded-xl flex items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                    </div>
                                    <span class="font-bold text-foreground">شارژ کیف پول</span></div>
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
                                        <span>پرداخت</span></button>
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
                                    <span class="font-bold text-foreground">کد هدیه</span></div>
                                <div class="flex items-center gap-3">
                                    <div class="flex-1"><label class="text-sm text-muted mb-2 block">کد هدیه</label>
                                        <input type="text" wire:model="giftCode"
                                               class="w-full bg-background dark:bg-zinc-700 border border-border rounded-xl px-4 py-3 text-foreground focus:outline-none focus:ring-2 focus:ring-purple-500"
                                               placeholder="کد هدیه را وارد کنید"></div>
                                    <button wire:click="applyGiftCode" wire:loading.attr="disabled"
                                            class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-xl font-bold flex items-center gap-2 mt-6 transition-colors disabled:opacity-50">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-5 h-5"
                                             wire:loading.class="animate-spin" wire:target="applyGiftCode">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="m4.5 12.75 6 6 9-13.5"/>
                                        </svg>
                                        <span>ثبت</span></button>
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
                            class="bg-secondary dark:bg-zinc-800 rounded-2xl overflow-hidden">                                @if($transactions->isNotEmpty())
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
                                        <tbody>                                            @foreach($transactions as $transaction)
                                            <tr class="odd:bg-secondary dark:odd:bg-zinc-800 even:bg-background dark:even:bg-zinc-900 border-b border-border last:border-0">
                                                <td class="p-5"><span
                                                        class="font-bold {{ in_array($transaction->type, ['deposit', 'gift', 'refund']) ? 'text-green-500' : 'text-red-500' }}">                                                            {{ in_array($transaction->type, ['deposit', 'gift', 'refund']) ? '+' : '-' }}{{ number_format($transaction->amount) }}                                                        </span>
                                                    <span class="text-xs text-muted">تومان</span></td>
                                                <td class="p-5 text-muted">{{ jalali($transaction->created_at)->format('%d %B %Y') }}</td>
                                                <td class="p-5 text-muted">-</td>
                                                <td class="p-5"><span
                                                        class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-{{ $transaction->type_color }}-100 dark:bg-{{ $transaction->type_color }}-900/30 text-{{ $transaction->type_color }}-600 dark:text-{{ $transaction->type_color }}-400">                                                            {{ $transaction->type_text }}                                                        </span> @if($transaction->description)
                                                        <p class="text-xs text-muted mt-1">{{ $transaction->description }}</p>
                                                    @endif                                                    </td>
                                            </tr>
                                        @endforeach                                            </tbody>
                                    </table>
                                </div>
                                <div
                                    class="p-5">                                        {{ $transactions->links('layouts.client.pagination') }}                                    </div>
                            @else
                                <div class="flex flex-col items-center justify-center py-16 space-y-4">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1" stroke="currentColor" class="w-24 h-24 text-muted opacity-30">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z"/>
                                    </svg>
                                    <p class="text-muted font-bold">هیچ تراکنشی یافت نشد</p></div>
                            @endif                          </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
