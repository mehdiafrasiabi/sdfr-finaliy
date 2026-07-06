<div dir="rtl" class="max-w-4xl mx-auto px-4 py-8 space-y-6">
    {{-- هدر صفحه --}}
    <div class="flex items-center gap-3 mb-2">
        <div class="flex items-center gap-1">
            <div class="w-1 h-1 bg-foreground rounded-full"></div>
            <div class="w-2 h-2 bg-foreground rounded-full"></div>
        </div>
        <h1 class="text-2xl font-black text-foreground">خرید دوره</h1>
    </div>

    @if (session('error'))
        <div class="rounded-xl bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 px-4 py-3 border border-red-200 dark:border-red-800/50 flex items-center gap-3">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            <span class="text-sm font-semibold">{{ session('error') }}</span>
        </div>
    @endif

    @if (! $price || ! $data)
        <div class="rounded-2xl border border-border bg-secondary p-8 text-center glass">
            <div class="w-16 h-16 bg-muted/20 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-muted" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <p class="text-foreground font-bold text-lg">هنوز قیمتی برای پایهٔ شما تعریف نشده است.</p>
            <p class="text-sm text-muted mt-2">لطفاً بعداً مراجعه کنید یا با پشتیبانی تماس بگیرید.</p>
        </div>
    @else
        {{-- ───────── نوار مراحل ───────── --}}
        <div class="flex items-center justify-between mb-8 select-none px-2">
            @php $steps = [1 => 'معرفی و قیمت', 2 => 'اطلاعات شما', 3 => 'پرداخت']; @endphp
            @foreach ($steps as $n => $label)
                <div class="flex items-center {{ ! $loop->last ? 'flex-1' : '' }}">
                    <div class="flex flex-col items-center">
                        <div @class([
                            'w-10 h-10 rounded-full flex items-center justify-center font-bold text-sm transition-all duration-300',
                            'bg-primary text-white shadow-lg shadow-primary/30 border-2 border-primary' => $step >= $n,
                            'bg-secondary text-muted border-2 border-border' => $step < $n,
                        ])>
                            @if ($step > $n)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                            @else
                                {{ $n }}
                            @endif
                        </div>
                        <span class="mt-2 text-xs {{ $step >= $n ? 'text-primary font-bold' : 'text-muted font-medium' }}">{{ $label }}</span>
                    </div>
                    @unless ($loop->last)
                        <div class="flex-1 h-1 mx-3 rounded-full transition-colors duration-300 {{ $step > $n ? 'bg-primary/50' : 'bg-border' }}"></div>
                    @endunless
                </div>
            @endforeach
        </div>

        <div class="rounded-3xl border border-border bg-background glass shadow-sm p-5 sm:p-8 space-y-6">

            {{-- ═══════════ مرحلهٔ ۱: معرفی خدمات + قیمت ═══════════ --}}
            @if ($step === 1)
                <div class="space-y-5">
                    <h2 class="text-xl font-black text-foreground border-r-4 border-primary pr-3">خدمات مشاورهٔ ما چیست؟</h2>
                    <div class="space-y-3 text-sm leading-relaxed text-muted p-4 rounded-2xl bg-secondary/50 border border-border">
                        <p>با خرید دورهٔ مشاوره، تا <strong class="text-foreground">پایان خرداد</strong> از این خدمات بهره‌مند می‌شوید:</p>
                        <ul class="list-disc pr-6 space-y-2 text-foreground/80 font-medium">
                            <li>مشاور اختصاصی و جلسات مشاورهٔ منظم</li>
                            <li>برنامه‌ریزی هفتگی و پایش مستمر مطالعه</li>
                            <li>آزمون‌ها، کارنامهٔ هوشمند و تحلیل عملکرد</li>
                            <li>پشتیبانی و پاسخ‌گویی در طول دوره</li>
                        </ul>
                        <div class="mt-4 flex gap-2 items-start text-xs text-primary bg-primary/5 p-3 rounded-xl border border-primary/20">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p>توجه: قیمت بر اساس <strong class="font-bold">ماهِ ورود</strong> محاسبه می‌شود؛ هرچه دیرتر ثبت‌نام کنید مبلغ کمتری می‌پردازید و تخفیف زودهنگام نیز کم‌تر می‌شود.</p>
                        </div>
                    </div>

                    {{-- کارت قیمت --}}
                    <div class="rounded-2xl border-2 border-primary/20 bg-primary/5 p-5 relative overflow-hidden">
                        <div class="absolute -right-10 -top-10 w-32 h-32 bg-primary/10 rounded-full blur-3xl"></div>

                        <div class="flex flex-wrap items-center justify-between gap-4 border-primary/10 pb-4 mb-4 relative z-10">
                            <div>
                                <div class="text-xs text-muted mb-1">پایه تحصیلی</div>
                                <div class="font-black text-lg text-foreground">{{ $price->grade_label }}</div>
                            </div>
                            <div class="text-left">
                                <div class="text-xs text-muted mb-1">ماه ورود</div>
                                <div class="font-bold text-foreground flex items-center justify-end gap-2">
                                    {{ $data['month_label'] }}
                                    @if ($data['discount'] > 0)
                                        <span class="px-2 py-0.5 rounded-md text-[10px] bg-primary text-white shadow-sm shadow-primary/30">تخفیف زودهنگام {{ $data['discount'] }}٪</span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="text-center py-4 relative z-10">
                            @if ($data['savings'] > 0)
                                <div class="text-sm text-muted line-through mb-1">{{ number_format($data['original_total']) }} تومان</div>
                            @endif
                            <div class="text-4xl font-black text-primary">
                                {{ number_format($data['total']) }}
                                <span class="text-base font-bold text-muted">تومان</span>
                            </div>
                            @if ($data['savings'] > 0)
                                <div class="mt-2 text-xs text-green-600 dark:text-green-400 font-bold bg-green-500/10 inline-block px-3 py-1 rounded-full border border-green-500/20">
                                    {{ number_format($data['savings']) }} تومان تخفیف برای ورود در {{ $data['month_label'] }}
                                </div>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-3 text-sm mt-4 relative z-10">
                            <div class="rounded-xl bg-background border border-border p-3 text-center">
                                <div class="text-xs text-muted">ماه‌های باقی‌مانده تا خرداد</div>
                                <div class="font-bold text-foreground mt-1">{{ $data['remaining_months'] }} ماه</div>
                            </div>
                            <div class="rounded-xl bg-background border border-border p-3 text-center">
                                <div class="text-xs text-muted">پایان دسترسی</div>
                                <div class="font-bold text-foreground mt-1">{{ $data['access_ends_label'] }}</div>
                            </div>
                        </div>

                        @if ($data['installment_count'] > 0)
                            <div class="mt-4 rounded-xl bg-background border border-border p-3 text-sm text-foreground flex items-start gap-2 relative z-10">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p>امکان پرداخت اقساطی: پیش‌پرداخت <strong class="text-primary">{{ number_format($data['initial']) }}</strong> تومان، سپس <strong class="text-primary">{{ $data['installment_count'] }}</strong> قسط ماهانهٔ <strong class="text-primary">{{ number_format($data['monthly']) }}</strong> تومانی.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex justify-end pt-4  border-border mt-6">
                    <button wire:click="nextStep"
                            class="inline-flex items-center gap-2 px-8 py-3 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold text-sm shadow-lg shadow-primary/30 transition-all">
                        <span>ادامه و بازبینی اطلاعات</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            @endif

            {{-- ═══════════ مرحلهٔ ۲: بازبینی اطلاعات ═══════════ --}}
            @if ($step === 2)
                <div class="flex items-center justify-between border-border pb-4">
                    <div>
                        <h2 class="text-xl font-black text-foreground border-r-4 border-primary pr-3">بازبینی اطلاعات شما</h2>
                        <p class="text-xs text-muted pr-4 mt-2">
                            در صورتی که اطلاعات صحیح نیست، روی «ویرایش» بزنید و اصلاح کنید.
                        </p>
                    </div>
                    @unless ($editingInfo)
                        <button wire:click="startEditInfo"
                                class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-secondary border border-border text-foreground hover:bg-muted/10 text-sm font-semibold transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            ویرایش
                        </button>
                    @endunless
                </div>

                @if (! $editingInfo)
                    {{-- نمایش فقط‌خواندنی --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mt-4">
                        @php
                            $rows = [
                                'نام' => $infoName,
                                'نام و نام خانوادگی' => $infoNameFull ?: '—',
                                'نام پدر' => $infoFatherName,
                                'کد ملی' => $infoCodeMell,
                                'پایه' => $gradeOptions[$infoGrade] ?? $infoGrade,
                                'رشته' => $fieldOptions[$infoField] ?? $infoField,
                                'تاریخ تولد' => $infoBirthDate ?: '—',
                                'محل تولد' => $infoPlaceOfBirth ?: '<span class="text-red-500 font-bold text-xs">تکمیل این فیلد الزامی است</span>',
                                'موبایل پدر' => $infoFatherMobile,
                                'موبایل مادر' => $infoMotherMobile,
                                'استان' => $pi?->state?->name ?? '—',
                                'شهر' => $pi?->city?->name ?? '—',
                            ];
                        @endphp
                        @foreach ($rows as $label => $value)
                            <div class="rounded-xl bg-secondary/50 border border-border p-3.5">
                                <div class="text-[11px] text-muted mb-1">{{ $label }}</div>
                                <div class="font-bold text-foreground">{!! $value !!}</div>
                            </div>
                        @endforeach
                        <div class="rounded-xl bg-secondary/50 border border-border p-3.5 sm:col-span-2">
                            <div class="text-[11px] text-muted mb-1">آدرس</div>
                            <div class="font-bold text-foreground">{!! $infoAddress ?: '<span class="text-red-500 text-xs">تکمیل این فیلد الزامی است</span>' !!}</div>
                        </div>
                    </div>
                @else
                    {{-- فرم ویرایش درجا --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-4 bg-secondary/30 p-5 rounded-2xl border border-border">
                        @php
                            $textFields = [
                                'infoName' => 'نام', 'infoNameFull' => 'نام و نام خانوادگی',
                                'infoFatherName' => 'نام پدر', 'infoCodeMell' => 'کد ملی',
                                'infoBirthDate' => 'تاریخ تولد', 'infoPlaceOfBirth' => 'محل تولد <span class="text-red-500">*</span>',
                                'infoFatherMobile' => 'موبایل پدر', 'infoMotherMobile' => 'موبایل مادر',
                            ];
                        @endphp
                        @foreach ($textFields as $model => $label)
                            <div>
                                <label class="block text-xs font-bold mb-1.5 text-foreground">{!! $label !!}</label>
                                <input type="text" wire:model="{{ $model }}"
                                       class="w-full rounded-xl border border-border bg-background text-foreground px-4 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                                @error($model)<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                            </div>
                        @endforeach

                        <div>
                            <label class="block text-xs font-bold mb-1.5 text-foreground">پایه</label>
                            <select wire:model="infoGrade"
                                    class="w-full rounded-xl border border-border bg-background text-foreground px-4 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                                @foreach ($gradeOptions as $val => $lbl)
                                    <option value="{{ $val }}">{{ $lbl }}</option>
                                @endforeach
                            </select>
                            @error('infoGrade')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div>
                            <label class="block text-xs font-bold mb-1.5 text-foreground">رشته</label>
                            <select wire:model="infoField"
                                    class="w-full rounded-xl border border-border bg-background text-foreground px-4 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all">
                                @foreach ($fieldOptions as $val => $lbl)
                                    <option value="{{ $val }}">{{ $lbl }}</option>
                                @endforeach
                            </select>
                            @error('infoField')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold mb-1.5 text-foreground">آدرس <span class="text-red-500">*</span></label>
                            <textarea wire:model="infoAddress" rows="2"
                                      class="w-full rounded-xl border border-border bg-background text-foreground px-4 py-2.5 text-sm focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all"></textarea>
                            @error('infoAddress')<div class="text-red-500 text-xs mt-1">{{ $message }}</div>@enderror
                        </div>
                    </div>

                    <p class="text-xs text-muted mt-3">استان و شهر از این صفحه قابل تغییر نیستند؛ برای تغییر آن‌ها با پشتیبانی تماس بگیرید.</p>

                    <div class="flex items-center gap-3 mt-4">
                        <button wire:click="saveInfo" wire:loading.attr="disabled"
                                class="inline-flex items-center justify-center gap-2 px-6 py-2.5 rounded-xl bg-green-600 hover:bg-green-700 text-white text-sm font-bold shadow-lg shadow-green-500/30 disabled:opacity-50 transition-all">
                            ذخیرهٔ تغییرات
                        </button>
                        <button wire:click="cancelEditInfo"
                                class="px-6 py-2.5 rounded-xl bg-secondary border border-border text-foreground hover:bg-muted/10 text-sm font-bold transition-all">
                            انصراف
                        </button>
                    </div>
                @endif

                <div class="flex items-center justify-between pt-6  border-border mt-6">
                    <button wire:click="prevStep"
                            class="px-6 py-2.5 rounded-xl bg-secondary border border-border text-foreground hover:bg-muted/10 text-sm font-bold transition-colors">
                        بازگشت
                    </button>
                    <button wire:click="nextStep" @disabled($editingInfo)
                    class="inline-flex items-center gap-2 px-8 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white font-bold text-sm shadow-lg shadow-primary/30 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                        <span>ادامه و پرداخت</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 rtl:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </button>
                </div>
            @endif

            {{-- ═══════════ مرحلهٔ ۳: روش پرداخت + قوانین ═══════════ --}}
            @if ($step === 3)
                <h2 class="text-xl font-black text-foreground border-r-4 border-primary pr-3 mb-6">تایید و پرداخت نهایی</h2>

                {{-- ۱. پذیرش قوانین (به بالای صفحه منتقل شد) --}}
                <div class="rounded-2xl border border-border bg-secondary/50 p-4 mb-6">
                    <label class="flex items-start gap-3 text-sm text-foreground cursor-pointer">
                        <input type="checkbox" wire:model.live="agreedToTerms" class="mt-0.5 w-5 h-5 rounded border-border text-primary focus:ring-primary bg-background">
                        <span class="leading-relaxed font-medium">
                            <a href="{{ route('client.terms') }}" target="_blank" class="text-primary font-bold hover:underline">قوانین و شرایط</a>
                            را خوانده‌ام و با آن موافقم. می‌دانم که اقساط باید به‌ترتیب و سرِ موعد پرداخت شوند.
                        </span>
                    </label>
                    @unless ($agreedToTerms)
                        <p class="text-xs font-bold text-amber-500 mt-2 pr-8">⚠️ برای فعال‌شدن دکمه‌های پرداخت، لطفاً تیک پذیرش قوانین را بزنید.</p>
                    @endunless
                </div>

                {{-- ۲. کد تخفیف (فقط نقدی) --}}
                {{-- ۲. کد تخفیف (فقط نقدی) --}}
                <div class="rounded-2xl border border-border bg-secondary/30 p-5 mb-6">
                    <label class="block text-sm font-bold mb-3 text-foreground flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" /></svg>
                        کد تخفیف
                        <span class="text-[11px] text-muted font-normal">(اختیاری - مختص پرداخت نقدی)</span>
                    </label>

                    @if ($couponDiscount > 0)
                        <div class="flex items-center justify-between rounded-xl bg-green-500/10 border border-green-500/20 p-3 text-sm text-green-600 dark:text-green-400 font-bold">
                            <div class="flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" /></svg>
                                {{ $couponNotice }}
                            </div>
                            <button wire:click="removeCoupon" class="text-xs text-red-500 hover:text-red-600 hover:underline px-2 transition-colors">حذف</button>
                        </div>
                    @else
                        <div class="flex gap-2">
                            <input type="text" wire:model="couponCode" placeholder="کد تخفیف خود را وارد کنید..."
                                   class="flex-1 rounded-xl border border-border bg-transparent text-foreground px-4 py-2.5 text-sm focus:bg-background focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all placeholder:text-muted/50">
                            <button wire:click="applyCoupon"
                                    class="px-6 py-2.5 rounded-xl bg-primary hover:bg-primary/90 text-white text-sm font-bold shadow-lg shadow-primary/20 transition-all flex items-center gap-2">
                                اعمال کد
                            </button>
                        </div>
                        @if ($couponError)
                            <div class="text-red-500 text-xs mt-2 font-bold flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                {{ $couponError }}
                            </div>
                        @endif
                    @endif
                </div>

                {{-- ۳. روش‌های پرداخت --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    {{-- پرداخت نقدی --}}
                    <div class="rounded-3xl border-2 border-border hover:border-green-500/50 transition-colors bg-background p-6 flex flex-col shadow-sm relative overflow-hidden">
                        <div class="text-sm font-black text-foreground mb-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd" /></svg>
                            پرداخت نقدی
                        </div>
                        <div class="text-3xl font-black text-foreground mt-2">
                            {{ number_format($data['full_with_coupon']) }}
                            <span class="text-sm font-bold text-muted">تومان</span>
                        </div>
                        @if ($couponDiscount > 0 && $data['full_with_coupon'] !== $data['total'])
                            <div class="text-xs text-muted line-through mt-1">{{ number_format($data['total']) }} تومان</div>
                        @endif
                        <p class="text-xs text-muted mt-4 leading-relaxed flex-1">کل مبلغ دوره را یک‌جا می‌پردازید و دسترسی بلافاصله تا پایان دوره فعال می‌شود.</p>

                        <button wire:click="pay" wire:loading.attr="disabled" @disabled(! $agreedToTerms)
                        class="mt-6 w-full py-3 rounded-xl bg-green-600 hover:bg-green-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold shadow-lg shadow-green-600/20 transition-all flex justify-center items-center gap-2">
                            <span wire:loading wire:target="pay">
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                            </span>
                            <span wire:loading.remove wire:target="pay">پرداخت نقدی</span>
                        </button>
                    </div>

                    {{-- پرداخت اقساطی --}}
                    <div class="rounded-3xl border-2 border-border hover:border-primary/50 transition-colors bg-background p-6 flex flex-col shadow-sm relative overflow-hidden">
                        <div class="text-sm font-black text-foreground mb-2 flex items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary" viewBox="0 0 20 20" fill="currentColor"><path d="M4 4a2 2 0 00-2 2v1h16V6a2 2 0 00-2-2H4z" /><path fill-rule="evenodd" d="M18 9H2v5a2 2 0 002 2h12a2 2 0 002-2V9zM4 13a1 1 0 011-1h1a1 1 0 110 2H5a1 1 0 01-1-1zm5-1a1 1 0 100 2h1a1 1 0 100-2H9z" clip-rule="evenodd" /></svg>
                            پرداخت اقساطی
                        </div>
                        @if ($data['installment_count'] > 0)
                            <div class="text-3xl font-black text-foreground mt-2">
                                {{ number_format($data['initial']) }}
                                <span class="text-sm font-bold text-muted">تومان (پیش‌پرداخت)</span>
                            </div>
                            <p class="text-[11px] text-muted mt-4 leading-relaxed flex-1">
                                ابتدا پیش‌پرداخت را می‌پردازید، سپس <strong class="text-primary">{{ $data['installment_count'] }}</strong> قسط ماهانهٔ
                                <strong class="text-primary">{{ number_format($data['monthly']) }}</strong> تومانی، تا پایان خرداد.
                            </p>

                            <button wire:click="payInstallment" wire:loading.attr="disabled" @disabled(! $agreedToTerms)
                            class="mt-6 w-full py-3 rounded-xl bg-primary hover:bg-primary/90 disabled:opacity-50 disabled:cursor-not-allowed text-white font-bold shadow-lg shadow-primary/30 transition-all flex justify-center items-center gap-2">
                                <span wire:loading wire:target="payInstallment">
                                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                </span>
                                <span wire:loading.remove wire:target="payInstallment">شروع با پیش‌پرداخت</span>
                            </button>
                        @else
                            <div class="flex-1 flex flex-col items-center justify-center text-center mt-4 bg-secondary/50 rounded-xl p-4">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-muted mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <p class="text-xs text-muted leading-relaxed">
                                    چون فقط یک ماه تا پایان خرداد باقی مانده، امکان پرداخت اقساطی نیست. لطفاً پرداخت نقدی را انتخاب کنید.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex items-center justify-between pt-6  border-border mt-8">
                    <button wire:click="prevStep"
                            class="px-6 py-2.5 rounded-xl bg-secondary border border-border text-foreground hover:bg-muted/10 text-sm font-bold transition-colors">
                        بازگشت
                    </button>
                </div>
            @endif
        </div>
    @endif
</div>
