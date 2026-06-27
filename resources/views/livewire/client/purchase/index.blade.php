<div class="max-w-3xl mx-auto px-4 py-8 text-slate-800 dark:text-slate-100">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-extrabold">خرید دوره</h1>
    </div>

    @if (session('error'))
        <div class="mb-4 rounded-lg bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-200 px-4 py-3 border border-rose-200 dark:border-rose-800">
            {{ session('error') }}
        </div>
    @endif

    @if (! $price || ! $data)
        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/40 p-6 text-center">
            <p class="text-slate-700 dark:text-slate-200 font-semibold">هنوز قیمتی برای پایهٔ شما تعریف نشده است.</p>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-2">لطفاً بعداً مراجعه کنید یا با پشتیبانی تماس بگیرید.</p>
        </div>
    @else
        {{-- ───────── نوار مراحل ───────── --}}
        <div class="flex items-center justify-between mb-6 select-none">
            @php
                $steps = [1 => 'معرفی و قیمت', 2 => 'اطلاعات شما', 3 => 'پرداخت'];
            @endphp
            @foreach ($steps as $n => $label)
                <div class="flex items-center {{ ! $loop->last ? 'flex-1' : '' }}">
                    <div class="flex flex-col items-center">
                        <div @class([
                            'w-9 h-9 rounded-full flex items-center justify-center font-bold text-sm transition',
                            'bg-indigo-600 text-white shadow' => $step >= $n,
                            'bg-slate-200 dark:bg-slate-700 text-slate-500 dark:text-slate-400' => $step < $n,
                        ])>
                            @if ($step > $n) ✓ @else {{ $n }} @endif
                        </div>
                        <span class="mt-1 text-xs {{ $step >= $n ? 'text-indigo-600 dark:text-indigo-300 font-semibold' : 'text-slate-400' }}">{{ $label }}</span>
                    </div>
                    @unless ($loop->last)
                        <div class="flex-1 h-0.5 mx-2 {{ $step > $n ? 'bg-indigo-600' : 'bg-slate-200 dark:bg-slate-700' }}"></div>
                    @endunless
                </div>
            @endforeach
        </div>

        <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 shadow-sm p-6 space-y-5">

            {{-- ═══════════ مرحلهٔ ۱: معرفی خدمات + قیمت ═══════════ --}}
            @if ($step === 1)
                <div class="space-y-4">
                    <h2 class="text-lg font-extrabold text-slate-900 dark:text-slate-100">خدمات مشاورهٔ SDFR چیست؟</h2>
                    <div class="space-y-2 text-sm leading-7 text-slate-600 dark:text-slate-300">
                        <p>با خرید دورهٔ مشاوره، تا <strong>پایان خرداد</strong> از این خدمات بهره‌مند می‌شوید:</p>
                        <ul class="list-disc ps-5 space-y-1">
                            <li>مشاور اختصاصی و جلسات مشاورهٔ منظم</li>
                            <li>برنامه‌ریزی هفتگی و پایش مستمر مطالعه</li>
                            <li>آزمون‌ها، کارنامهٔ هوشمند و تحلیل عملکرد</li>
                            <li>پشتیبانی و پاسخ‌گویی در طول دوره</li>
                        </ul>
                        <p class="text-xs text-slate-400">
                            توجه: قیمت بر اساس <strong>ماهِ ورود</strong> محاسبه می‌شود؛ هرچه دیرتر ثبت‌نام کنید، چون ماه‌های باقی‌مانده تا خرداد کمتر است، مبلغ کمتری می‌پردازید و تخفیف زودهنگام نیز کم‌تر می‌شود.
                        </p>
                    </div>

                    {{-- کارت قیمت --}}
                    <div class="rounded-2xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/40 p-5">
                        <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 dark:border-slate-700 pb-3 mb-3">
                            <div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">پایه</div>
                                <div class="font-bold text-slate-900 dark:text-slate-100">{{ $price->grade_label }}</div>
                            </div>
                            <div class="text-end">
                                <div class="text-xs text-slate-500 dark:text-slate-400">ماه ورود</div>
                                <div class="font-bold text-slate-900 dark:text-slate-100">
                                    {{ $data['month_label'] }}
                                    @if ($data['discount'] > 0)
                                        <span class="ms-1 px-2 py-0.5 rounded text-xs bg-rose-100 dark:bg-rose-900/40 text-rose-700 dark:text-rose-300">تخفیف زودهنگام {{ $data['discount'] }}٪</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="text-center py-2">
                            @if ($data['savings'] > 0)
                                <div class="text-sm text-slate-400 line-through">{{ number_format($data['original_total']) }} تومان</div>
                            @endif
                            <div class="text-3xl font-extrabold text-indigo-600 dark:text-indigo-300 mt-1">
                                {{ number_format($data['total']) }}
                                <span class="text-base font-normal text-slate-500">تومان</span>
                            </div>
                            @if ($data['savings'] > 0)
                                <div class="mt-1 text-xs text-emerald-600 dark:text-emerald-400 font-semibold">
                                    {{ number_format($data['savings']) }} تومان تخفیف برای ورود در {{ $data['month_label'] }}
                                </div>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-3 text-sm mt-3">
                            <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 p-3 text-center">
                                <div class="text-xs text-slate-500 dark:text-slate-400">ماه‌های باقی‌مانده تا خرداد</div>
                                <div class="font-bold mt-1">{{ $data['remaining_months'] }} ماه</div>
                            </div>
                            <div class="rounded-xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700 p-3 text-center">
                                <div class="text-xs text-slate-500 dark:text-slate-400">پایان دسترسی</div>
                                <div class="font-bold mt-1">{{ $data['access_ends_label'] }}</div>
                            </div>
                        </div>

                        @if ($data['installment_count'] > 0)
                            <div class="mt-3 rounded-xl bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-100 dark:border-indigo-800 p-3 text-sm text-indigo-700 dark:text-indigo-200">
                                امکان پرداخت اقساطی: پیش‌پرداخت <strong>{{ number_format($data['initial']) }}</strong> تومان،
                                سپس <strong>{{ $data['installment_count'] }}</strong> قسط ماهانهٔ <strong>{{ number_format($data['monthly']) }}</strong> تومانی.
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button wire:click="nextStep"
                            class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow">
                        ادامه و بازبینی اطلاعات
                    </button>
                </div>
            @endif

            {{-- ═══════════ مرحلهٔ ۲: بازبینی اطلاعات ═══════════ --}}
            @if ($step === 2)
                <div class="flex items-center justify-between">
                    <h2 class="text-lg font-extrabold text-slate-900 dark:text-slate-100">بازبینی اطلاعات شما</h2>
                    @unless ($editingInfo)
                        <button wire:click="startEditInfo"
                                class="px-4 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-sm font-semibold">
                            ویرایش
                        </button>
                    @endunless
                </div>
                <p class="text-sm text-slate-500 dark:text-slate-400 -mt-3">
                    در صورتی که اطلاعات زیر صحیح نیست، روی «ویرایش» بزنید و اصلاح کنید.
                </p>

                @if (! $editingInfo)
                    {{-- نمایش فقط‌خواندنی --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm">
                        @php
                            $rows = [
                                'نام' => $infoName,
                                'نام و نام خانوادگی' => $infoNameFull ?: '—',
                                'نام پدر' => $infoFatherName,
                                'کد ملی' => $infoCodeMell,
                                'پایه' => $gradeOptions[$infoGrade] ?? $infoGrade,
                                'رشته' => $fieldOptions[$infoField] ?? $infoField,
                                'تاریخ تولد' => $infoBirthDate ?: '—',
                                'محل تولد' => $infoPlaceOfBirth ?: '—',
                                'موبایل پدر' => $infoFatherMobile,
                                'موبایل مادر' => $infoMotherMobile,
                                'استان' => $pi?->state?->name ?? '—',
                                'شهر' => $pi?->city?->name ?? '—',
                            ];
                        @endphp
                        @foreach ($rows as $label => $value)
                            <div class="rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 p-3">
                                <div class="text-xs text-slate-500 dark:text-slate-400">{{ $label }}</div>
                                <div class="font-semibold mt-0.5">{{ $value }}</div>
                            </div>
                        @endforeach
                        <div class="rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 p-3 sm:col-span-2">
                            <div class="text-xs text-slate-500 dark:text-slate-400">آدرس</div>
                            <div class="font-semibold mt-0.5">{{ $infoAddress }}</div>
                        </div>
                    </div>
                @else
                    {{-- فرم ویرایش درجا --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @php
                            $textFields = [
                                'infoName' => 'نام', 'infoNameFull' => 'نام و نام خانوادگی',
                                'infoFatherName' => 'نام پدر', 'infoCodeMell' => 'کد ملی',
                                'infoBirthDate' => 'تاریخ تولد', 'infoPlaceOfBirth' => 'محل تولد',
                                'infoFatherMobile' => 'موبایل پدر', 'infoMotherMobile' => 'موبایل مادر',
                            ];
                        @endphp
                        @foreach ($textFields as $model => $label)
                            <div>
                                <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-200">{{ $label }}</label>
                                <input type="text" wire:model="{{ $model }}"
                                       class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2 text-sm">
                                @error($model)<div class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</div>@enderror
                            </div>
                        @endforeach

                        <div>
                            <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-200">پایه</label>
                            <select wire:model="infoGrade"
                                    class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2 text-sm">
                                @foreach ($gradeOptions as $val => $lbl)
                                    <option value="{{ $val }}">{{ $lbl }}</option>
                                @endforeach
                            </select>
                            @error('infoGrade')<div class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-200">رشته</label>
                            <select wire:model="infoField"
                                    class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2 text-sm">
                                @foreach ($fieldOptions as $val => $lbl)
                                    <option value="{{ $val }}">{{ $lbl }}</option>
                                @endforeach
                            </select>
                            @error('infoField')<div class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold mb-1 text-slate-700 dark:text-slate-200">آدرس</label>
                            <textarea wire:model="infoAddress" rows="2"
                                      class="w-full rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-3 py-2 text-sm"></textarea>
                            @error('infoAddress')<div class="text-rose-600 dark:text-rose-400 text-xs mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>
                    <p class="text-xs text-slate-400">استان و شهر از این صفحه قابل تغییر نیستند؛ برای تغییر آن‌ها با پشتیبانی تماس بگیرید.</p>
                    <div class="flex items-center gap-2">
                        <button wire:click="saveInfo" wire:loading.attr="disabled"
                                class="px-5 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold disabled:opacity-50">
                            ذخیرهٔ تغییرات
                        </button>
                        <button wire:click="cancelEditInfo"
                                class="px-5 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-sm">
                            انصراف
                        </button>
                    </div>
                @endif

                <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-800">
                    <button wire:click="prevStep"
                            class="px-5 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-sm">
                        بازگشت
                    </button>
                    <button wire:click="nextStep" @disabled($editingInfo)
                            class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold shadow disabled:opacity-50">
                        ادامه و پرداخت
                    </button>
                </div>
            @endif

            {{-- ═══════════ مرحلهٔ ۳: روش پرداخت + قوانین ═══════════ --}}
            @if ($step === 3)
                <h2 class="text-lg font-extrabold text-slate-900 dark:text-slate-100">روش پرداخت را انتخاب کنید</h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- پرداخت نقدی --}}
                    <div class="rounded-2xl border-2 border-emerald-200 dark:border-emerald-800 bg-emerald-50 dark:bg-emerald-900/20 p-5 flex flex-col">
                        <div class="text-sm font-bold text-emerald-700 dark:text-emerald-300 mb-1">پرداخت نقدی</div>
                        <div class="text-2xl font-extrabold text-emerald-700 dark:text-emerald-200">
                            {{ number_format($data['full_with_coupon']) }}
                            <span class="text-sm font-normal">تومان</span>
                        </div>
                        @if ($couponDiscount > 0 && $data['full_with_coupon'] !== $data['total'])
                            <div class="text-xs text-slate-500 line-through mt-1">{{ number_format($data['total']) }} تومان</div>
                        @endif
                        <p class="text-xs text-emerald-700/80 dark:text-emerald-300/80 mt-2 flex-1">کل مبلغ دوره را یک‌جا می‌پردازید و دسترسی بلافاصله فعال می‌شود.</p>
                        <button wire:click="pay" wire:loading.attr="disabled" @disabled(! $agreedToTerms)
                                class="mt-3 w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 disabled:opacity-40 disabled:cursor-not-allowed text-white font-bold shadow">
                            پرداخت نقدی {{ number_format($data['full_with_coupon']) }} تومان
                        </button>
                    </div>

                    {{-- پرداخت اقساطی --}}
                    <div class="rounded-2xl border-2 border-indigo-200 dark:border-indigo-800 bg-indigo-50 dark:bg-indigo-900/20 p-5 flex flex-col">
                        <div class="text-sm font-bold text-indigo-700 dark:text-indigo-300 mb-1">پرداخت اقساطی</div>
                        @if ($data['installment_count'] > 0)
                            <div class="text-2xl font-extrabold text-indigo-700 dark:text-indigo-200">
                                {{ number_format($data['initial']) }}
                                <span class="text-sm font-normal">تومان پیش‌پرداخت</span>
                            </div>
                            <p class="text-xs text-indigo-700/80 dark:text-indigo-300/80 mt-2 flex-1">
                                ابتدا پیش‌پرداخت را می‌پردازید، سپس <strong>{{ $data['installment_count'] }}</strong> قسط ماهانهٔ
                                <strong>{{ number_format($data['monthly']) }}</strong> تومانی، هر ماه در همین روز تا پایان خرداد.
                            </p>
                            <button wire:click="payInstallment" wire:loading.attr="disabled" @disabled(! $agreedToTerms)
                                    class="mt-3 w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 disabled:opacity-40 disabled:cursor-not-allowed text-white font-bold shadow">
                                پرداخت پیش‌پرداخت {{ number_format($data['initial']) }} تومان
                            </button>
                        @else
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 flex-1">
                                چون فقط یک ماه تا پایان خرداد باقی مانده، امکان پرداخت اقساطی نیست. لطفاً پرداخت نقدی را انتخاب کنید.
                            </p>
                        @endif
                    </div>
                </div>

                {{-- کد تخفیف (فقط نقدی) --}}
                <div class="border-t border-slate-100 dark:border-slate-800 pt-4">
                    <label class="block text-sm font-semibold mb-2 text-slate-700 dark:text-slate-200">کد تخفیف <span class="text-xs text-slate-400">(روی پرداخت نقدی)</span></label>
                    @if ($couponDiscount > 0)
                        <div class="rounded-lg bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 p-3 text-sm text-emerald-700 dark:text-emerald-200">
                            ✔ {{ $couponNotice }}
                            <button wire:click="removeCoupon" class="float-end text-xs underline">حذف</button>
                        </div>
                    @else
                        <div class="flex gap-2">
                            <input type="text" wire:model="couponCode" placeholder="کد تخفیف خود را وارد کنید"
                                   class="flex-1 rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-slate-100 px-4 py-2 text-sm">
                            <button wire:click="applyCoupon"
                                    class="px-4 py-2 rounded-lg bg-slate-700 hover:bg-slate-800 dark:bg-slate-200 dark:hover:bg-slate-100 dark:text-slate-800 text-white text-sm">
                                اعمال
                            </button>
                        </div>
                        @if ($couponError)
                            <div class="text-rose-600 dark:text-rose-400 text-sm mt-2">{{ $couponError }}</div>
                        @endif
                    @endif
                </div>

                {{-- پذیرش قوانین --}}
                <label class="flex items-start gap-2 text-sm text-slate-700 dark:text-slate-200 border-t border-slate-100 dark:border-slate-800 pt-4">
                    <input type="checkbox" wire:model.live="agreedToTerms" class="mt-1">
                    <span>
                        <a href="{{ route('client.terms') }}" target="_blank" class="text-indigo-600 dark:text-indigo-300 underline">قوانین و شرایط</a>
                        را خوانده‌ام و با آن موافقم. می‌دانم که اقساط باید به‌ترتیب و سرِ موعد پرداخت شوند.
                    </span>
                </label>
                @unless ($agreedToTerms)
                    <p class="text-xs text-amber-600 dark:text-amber-400">برای فعال‌شدن دکمه‌های پرداخت، تیک پذیرش قوانین را بزنید.</p>
                @endunless

                <div class="flex items-center justify-between pt-3 border-t border-slate-100 dark:border-slate-800">
                    <button wire:click="prevStep"
                            class="px-5 py-2 rounded-lg bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 text-sm">
                        بازگشت
                    </button>
                </div>
            @endif
        </div>
    @endif
</div>
