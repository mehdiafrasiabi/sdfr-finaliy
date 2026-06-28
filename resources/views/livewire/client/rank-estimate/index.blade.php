<div>
    <div dir="rtl" class="font-vazir" x-data="rankEstimate()" x-init="init()">

        {{-- ========== مرحله ۱: اطلاعات اولیه ========== --}}
        <div x-show="step === 1" x-transition>

            {{-- هدر بنر --}}
            <div class="bg-primary py-10 px-4">
                <div class="max-w-7xl mx-auto">
                    <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                        <div class="space-y-3 text-center md:text-right order-1">
                            <div class="flex items-center gap-2 justify-center md:justify-end">
                                <svg width="24" height="70" viewBox="0 0 46 140" fill="none" xmlns="http://www.w3.org/2000/svg" class="h-12 text-primary-80 dark:text-white">
                                    <path d="M14.6961 131.927L27.9077 128.958C27.8003 130.209 27.3414 131.404 26.5918 132.383C25.8423 133.362 24.8374 134.079 23.7102 134.44C17.7 136.566 11.5905 138.334 5.41003 139.735C1.88814 140.487 0.107879 137.811 1.27564 134.189C3.22637 128.125 5.45891 122.147 7.58317 116.132C7.70288 115.782 7.81512 115.314 8.06083 115.141C8.87447 114.624 9.75059 114.213 10.6059 113.767C10.9329 114.546 11.652 115.377 11.5315 116.126C11.165 118.539 10.5156 120.9 9.97527 123.275C9.57055 124.691 9.52631 126.189 9.84717 127.615C11.2303 126.331 12.6663 125.106 13.9859 123.751C27.0005 110.485 35.6931 93.1013 38.7375 74.2506C41.782 55.3999 39.0112 36.1173 30.8478 19.3444C28.7161 14.869 26.0615 10.6917 23.6744 6.34201C22.8435 4.85252 22.0911 3.29367 21.2943 1.75769L22.0985 0.817938C23.0816 1.22994 24.3579 1.34076 25.003 2.08855C27.2882 4.63792 29.4005 7.35962 31.3246 10.2336C50.1428 39.6302 49.7137 80.106 30.0307 111.127C26.2796 117.05 21.5217 122.255 17.1923 127.759C16.305 128.921 15.2592 129.893 14.2875 130.947L14.6961 131.927Z" fill="currentColor"/>
                                </svg>
                                <h1 class="font-black text-white text-3xl sm:text-4xl leading-snug">
                                    تخمین رتبه کنکور سراسری
                                </h1>
                            </div>
                            <p class="text-white/80 text-sm sm:text-base max-w-lg mx-auto md:mx-0">
                                با وارد کردن درصد درس‌های خود، رتبه تخمینی‌ات را در کنکور سراسری محاسبه کن
                            </p>
                        </div>
                        <div class="order-2 flex-shrink-0">
                            <div class="relative w-32 h-32 md:w-44 md:h-44">
                                <div class="absolute inset-0 rounded-full bg-white/10 animate-ping" style="animation-duration: 3s;"></div>
                                <div class="absolute inset-4 rounded-full bg-white/10"></div>
                                <div class="absolute inset-8 rounded-full bg-blue-400/30 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- کارت فرم اطلاعات --}}
            <div class="max-w-7xl mx-auto px-4 -mt-10 relative z-50 pb-10">

                {{-- نوار مراحل --}}
                <div class="bg-secondary border border-border rounded-2xl p-4 mb-4 flex items-center gap-2">
                    <template x-for="(s, i) in steps" :key="i">
                        <div class="flex items-center gap-2 flex-1">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0"
                                     :class="{
                                     'bg-primary text-white': step === i + 1,
                                     'bg-green-500 text-white': step > i + 1,
                                     'bg-secondary border border-border text-muted': step < i + 1
                                 }">
                                    <span x-show="step <= i + 1" x-text="i + 1"></span>
                                    <svg x-show="step > i + 1" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-xs font-medium hidden sm:block" :class="step === i + 1 ? 'text-foreground' : 'text-muted'" x-text="s"></span>
                            </div>
                            <div x-show="i < steps.length - 1" class="flex-1 h-px bg-border"></div>
                        </div>
                    </template>
                </div>

                <div class="bg-secondary border border-border rounded-2xl shadow-xl overflow-hidden">
                    <div class="px-6 py-5 border-b border-border">
                        <h2 class="font-black text-xl text-foreground">اطلاعات اولیه</h2>
                        <p class="text-muted text-sm mt-1">رشته و مشخصات کنکوری خود را وارد کنید</p>
                    </div>
                    <div class="p-6 space-y-6">

                        {{-- رشته --}}
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-2">گروه آزمایشی</label>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                <template x-for="g in groups" :key="g.id">
                                    <button type="button"
                                            @click="selectedGroup = g.id; updateSubjects()"
                                            class="flex items-center gap-2 px-4 py-3 rounded-xl border text-sm font-medium transition-all"
                                            :class="selectedGroup === g.id
                                            ? 'bg-primary text-white border-primary shadow-sm'
                                            : 'bg-secondary border-border text-foreground hover:border-primary/50'">
                                        <span x-text="g.icon"></span>
                                        <span x-text="g.label"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- سال آخر خواندن --}}
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-2">سال آخر خواندن</label>
                            <div class="flex gap-2">
                                <template x-for="y in [12, 11, 10]" :key="y">
                                    <button type="button"
                                            @click="selectedYear = y"
                                            class="flex-1 py-2.5 rounded-xl border text-sm font-medium transition-all"
                                            :class="selectedYear === y
                                            ? 'bg-primary text-white border-primary'
                                            : 'bg-secondary border-border text-foreground hover:border-primary/50'">
                                        <span x-text="y === 12 ? 'دوازدهم' : y === 11 ? 'یازدهم' : 'دهم'"></span>
                                    </button>
                                </template>
                            </div>
                        </div>

                        {{-- سهمیه --}}
                        <div>
                            <label class="block text-sm font-medium text-foreground mb-2">سهمیه</label>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="q in quotas" :key="q.id">
                                    <button type="button"
                                            @click="selectedQuota = q.id"
                                            class="px-3 py-1.5 rounded-full border text-sm transition-all"
                                            :class="selectedQuota === q.id
                                            ? 'bg-primary/10 border-primary text-primary font-medium'
                                            : 'bg-secondary border-border text-muted hover:border-primary/40'">
                                        <span x-text="q.label"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                    <div class="px-6 pb-6">
                        <button type="button"
                                @click="goNext()"
                                :disabled="!selectedGroup"
                                class="w-full py-3 rounded-xl font-bold text-base transition-all"
                                :class="selectedGroup
                                ? 'bg-primary text-white hover:bg-primary/90 shadow-sm'
                                : 'bg-border text-muted cursor-not-allowed'">
                            مرحله بعد: ورود نمرات
                            <span class="mr-1">←</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== مرحله ۲: ورود درس‌ها ========== --}}
        <div x-show="step === 2" x-transition>
            <div class="bg-primary py-6 px-4">
                <div class="max-w-7xl mx-auto">
                    <h1 class="font-black text-white text-2xl">ورود نمرات درس‌ها</h1>
                    <p class="text-white/70 text-sm mt-1">تعداد سوالات صحیح و غلط هر درس را وارد کنید</p>
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-4 -mt-4 relative z-50 pb-10">

                {{-- نوار مراحل --}}
                <div class="bg-secondary border border-border rounded-2xl p-4 mb-4 flex items-center gap-2">
                    <template x-for="(s, i) in steps" :key="i">
                        <div class="flex items-center gap-2 flex-1">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0"
                                     :class="{
                                     'bg-primary text-white': step === i + 1,
                                     'bg-green-500 text-white': step > i + 1,
                                     'bg-secondary border border-border text-muted': step < i + 1
                                 }">
                                    <span x-show="step <= i + 1" x-text="i + 1"></span>
                                    <svg x-show="step > i + 1" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                <span class="text-xs font-medium hidden sm:block" :class="step === i + 1 ? 'text-foreground' : 'text-muted'" x-text="s"></span>
                            </div>
                            <div x-show="i < steps.length - 1" class="flex-1 h-px bg-border"></div>
                        </div>
                    </template>
                </div>

                <div class="bg-secondary border border-border rounded-2xl shadow-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-border flex items-center justify-between">
                        <h2 class="font-black text-lg text-foreground">درس‌های گروه <span x-text="currentGroupLabel()" class="text-primary"></span></h2>
                        <span class="text-xs text-muted bg-secondary border border-border px-2 py-1 rounded-full">
                        سال <span x-text="selectedYear === 12 ? 'دوازدهم' : selectedYear === 11 ? 'یازدهم' : 'دهم'"></span>
                    </span>
                    </div>

                    {{-- هدر جدول --}}
                    <div class="hidden sm:grid grid-cols-[1fr_80px_80px_80px] gap-3 px-6 py-2 border-b border-border bg-secondary/60">
                        <span class="text-xs font-medium text-muted">نام درس</span>
                        <span class="text-xs font-medium text-muted text-center">صحیح</span>
                        <span class="text-xs font-medium text-muted text-center">غلط</span>
                        <span class="text-xs font-medium text-muted text-center">درصد</span>
                    </div>

                    <div class="divide-y divide-border">
                        <template x-for="(subj, i) in currentSubjects" :key="i">
                            <div class="px-4 sm:px-6 py-4">
                                {{-- موبایل --}}
                                <div class="flex items-center justify-between mb-3 sm:hidden">
                                    <div>
                                        <span class="font-medium text-foreground text-sm" x-text="subj.name"></span>
                                        <span class="text-xs text-muted mr-2" x-text="subj.q + ' سوال | ضریب ' + subj.weight"></span>
                                    </div>
                                    <span class="font-bold text-sm px-2 py-0.5 rounded-lg"
                                          :class="calcPct(i) >= 50 ? 'bg-green-500/10 text-green-500' : calcPct(i) >= 25 ? 'bg-yellow-500/10 text-yellow-600' : 'bg-red-500/10 text-red-500'"
                                          x-text="calcPct(i) + '٪'"></span>
                                </div>
                                <div class="grid grid-cols-2 gap-2 sm:hidden">
                                    <div>
                                        <label class="block text-xs text-muted mb-1">صحیح</label>
                                        <input type="number" :min="0" :max="subj.q" x-model.number="inputs[i].correct"
                                               @input="updateInput(i)"
                                               class="w-full bg-secondary border border-border rounded-lg px-3 py-2 text-center text-sm text-foreground focus:outline-none focus:border-primary transition-colors"
                                               placeholder="0">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-muted mb-1">غلط</label>
                                        <input type="number" :min="0" :max="subj.q" x-model.number="inputs[i].wrong"
                                               @input="updateInput(i)"
                                               class="w-full bg-secondary border border-border rounded-lg px-3 py-2 text-center text-sm text-foreground focus:outline-none focus:border-primary transition-colors"
                                               placeholder="0">
                                    </div>
                                </div>
                                {{-- بار موبایل --}}
                                <div class="mt-2 sm:hidden">
                                    <div class="h-1.5 bg-border rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-500"
                                             :class="calcPct(i) >= 50 ? 'bg-green-500' : calcPct(i) >= 25 ? 'bg-yellow-500' : 'bg-red-400'"
                                             :style="'width:' + calcPct(i) + '%'"></div>
                                    </div>
                                </div>

                                {{-- دسکتاپ --}}
                                <div class="hidden sm:grid grid-cols-[1fr_80px_80px_80px] gap-3 items-center">
                                    <div>
                                        <span class="font-medium text-foreground text-sm" x-text="subj.name"></span>
                                        <span class="text-xs text-muted block mt-0.5" x-text="subj.q + ' سوال  |  ضریب ' + subj.weight"></span>
                                    </div>
                                    <input type="number" :min="0" :max="subj.q" x-model.number="inputs[i].correct"
                                           @input="updateInput(i)"
                                           class="bg-secondary border border-border rounded-lg px-2 py-2 text-center text-sm text-foreground focus:outline-none focus:border-primary transition-colors w-full"
                                           placeholder="0">
                                    <input type="number" :min="0" :max="subj.q" x-model.number="inputs[i].wrong"
                                           @input="updateInput(i)"
                                           class="bg-secondary border border-border rounded-lg px-2 py-2 text-center text-sm text-foreground focus:outline-none focus:border-primary transition-colors w-full"
                                           placeholder="0">
                                    <div class="text-center">
                                    <span class="font-bold text-sm px-2 py-1 rounded-lg block"
                                          :class="calcPct(i) >= 50 ? 'bg-green-500/10 text-green-500' : calcPct(i) >= 25 ? 'bg-yellow-500/10 text-yellow-600' : 'bg-red-500/10 text-red-500'"
                                          x-text="calcPct(i) + '٪'"></span>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>

                    {{-- پیش‌نمایش میانگین --}}
                    <div class="px-6 py-4 border-t border-border bg-secondary/40">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-muted">میانگین وزنی کل</span>
                            <span class="font-black text-xl text-foreground" x-text="weightedAvg() + '٪'"></span>
                        </div>
                    </div>

                    <div class="px-6 pb-6 pt-2 flex gap-3">
                        <button type="button" @click="step = 1"
                                class="flex-1 py-3 rounded-xl border border-border font-bold text-sm text-foreground hover:bg-secondary/60 transition-all">
                            ← برگشت
                        </button>
                        <button type="button" @click="calcRank()"
                                class="flex-[2] py-3 rounded-xl bg-primary text-white font-bold text-base hover:bg-primary/90 transition-all shadow-sm">
                            محاسبه رتبه 🎯
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== مرحله ۳: نتیجه ========== --}}
        <div x-show="step === 3" x-transition>
            <div class="bg-primary py-6 px-4">
                <div class="max-w-7xl mx-auto">
                    <h1 class="font-black text-white text-2xl">نتیجه تخمین رتبه</h1>
                    <p class="text-white/70 text-sm mt-1">بر اساس اطلاعاتی که وارد کردی</p>
                </div>
            </div>
            <div class="max-w-7xl mx-auto px-4 -mt-4 relative z-50 pb-10">
                <div class="space-y-4">

                    {{-- کارت رتبه اصلی --}}
                    <div class="rounded-2xl overflow-hidden shadow-xl" style="background: linear-gradient(135deg, #006EE6 0%, #0047a8 100%);">
                        <div class="px-6 py-8 text-center text-white">
                            <p class="text-white/70 text-sm mb-2">رتبه تخمینی شما در گروه <span x-text="currentGroupLabel()" class="font-bold text-white"></span></p>
                            <div class="font-black leading-none mb-3" style="font-size: clamp(3rem, 10vw, 5rem);" x-text="result.rankDisplay"></div>
                            <p class="text-white/60 text-sm">از <span x-text="result.totalDisplay" class="text-white/90 font-medium"></span> داوطلب</p>
                        </div>
                        <div class="grid grid-cols-3 divide-x divide-white/20 border-t border-white/20" style="direction: ltr;">
                            <div class="px-4 py-4 text-center" style="direction: rtl;">
                                <p class="text-white/60 text-xs mb-1">درصد کل</p>
                                <p class="font-black text-2xl text-white" x-text="result.avgPct + '٪'"></p>
                            </div>
                            <div class="px-4 py-4 text-center" style="direction: rtl;">
                                <p class="text-white/60 text-xs mb-1">تراز تخمینی</p>
                                <p class="font-black text-2xl text-white" x-text="result.taraz"></p>
                            </div>
                            <div class="px-4 py-4 text-center" style="direction: rtl;">
                                <p class="text-white/60 text-xs mb-1">درصدک</p>
                                <p class="font-black text-2xl text-white" x-text="result.percentile + '٪'"></p>
                            </div>
                        </div>
                    </div>

                    {{-- تحلیل درس‌ها --}}
                    <div class="bg-secondary border border-border rounded-2xl overflow-hidden shadow-xl">
                        <div class="px-6 py-4 border-b border-border">
                            <h2 class="font-black text-lg text-foreground">تحلیل درس به درس</h2>
                        </div>
                        <div class="p-6 space-y-4">
                            <template x-for="(s, i) in result.subjects" :key="i">
                                <div>
                                    <div class="flex items-center justify-between mb-1.5">
                                        <span class="text-sm font-medium text-foreground" x-text="s.name"></span>
                                        <div class="flex items-center gap-2">
                                            <span class="text-xs text-muted" x-text="'ضریب ' + s.weight"></span>
                                            <span class="text-sm font-bold px-2 py-0.5 rounded-lg"
                                                  :class="s.pct >= 50 ? 'bg-green-500/10 text-green-500' : s.pct >= 25 ? 'bg-yellow-500/10 text-yellow-600' : 'bg-red-500/10 text-red-500'"
                                                  x-text="s.pct + '٪'"></span>
                                        </div>
                                    </div>
                                    <div class="h-2 bg-border rounded-full overflow-hidden">
                                        <div class="h-full rounded-full transition-all duration-700"
                                             :class="s.pct >= 50 ? 'bg-green-500' : s.pct >= 25 ? 'bg-yellow-500' : 'bg-red-400'"
                                             :style="'width:' + s.pct + '%'"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- کارت پیشنهاد --}}
                    <div class="bg-secondary border border-border rounded-2xl p-6 shadow-xl">
                        <div class="flex gap-4 items-start">
                            <div class="text-3xl flex-shrink-0" x-text="result.tipIcon"></div>
                            <div>
                                <h3 class="font-bold text-foreground mb-1">تحلیل و پیشنهاد</h3>
                                <p class="text-sm text-muted leading-relaxed" x-text="result.tip"></p>
                            </div>
                        </div>
                    </div>

                    {{-- دکمه محاسبه مجدد --}}
                    <div class="flex gap-3">
                        <button type="button" @click="step = 2"
                                class="flex-1 py-3 rounded-xl border border-border font-bold text-sm text-foreground hover:bg-secondary/60 transition-all">
                            ← ویرایش نمرات
                        </button>
                        <button type="button" @click="reset()"
                                class="flex-1 py-3 rounded-xl bg-primary text-white font-bold text-sm hover:bg-primary/90 transition-all">
                            محاسبه مجدد
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>

    @script
    <script>
        function rankEstimate() {
            return {
                step: 1,
                steps: ['اطلاعات اولیه', 'ورود نمرات', 'نتیجه'],
                selectedGroup: '',
                selectedYear: 12,
                selectedQuota: 'free',
                inputs: [],
                currentSubjects: [],
                result: {},

                groups: [
                    { id: 'riazi',   label: 'ریاضی',    icon: '📐', total: 150000 },
                    { id: 'tajrobi', label: 'تجربی',     icon: '🔬', total: 620000 },
                    { id: 'ensani',  label: 'انسانی',    icon: '📖', total: 320000 },
                    { id: 'honar',   label: 'هنر',       icon: '🎨', total: 45000  },
                    { id: 'zaban',   label: 'زبان',      icon: '🌐', total: 35000  },
                ],

                quotas: [
                    { id: 'free',    label: 'آزاد' },
                    { id: 'r25',     label: 'ایثارگران ۲۵٪' },
                    { id: 'r5',      label: 'ایثارگران ۵٪' },
                    { id: 'm3',      label: 'مناطق ۳' },
                    { id: 'm2',      label: 'مناطق ۲' },
                    { id: 'm1',      label: 'مناطق ۱' },
                ],

                subjectData: {
                    riazi: [
                        { name: 'ریاضی',           q: 30, weight: 4 },
                        { name: 'فیزیک',           q: 25, weight: 3 },
                        { name: 'شیمی',            q: 20, weight: 2 },
                        { name: 'عربی',            q: 20, weight: 1 },
                        { name: 'ادبیات فارسی',    q: 25, weight: 1 },
                        { name: 'زبان انگلیسی',    q: 25, weight: 1 },
                        { name: 'دین و زندگی',     q: 25, weight: 1 },
                    ],
                    tajrobi: [
                        { name: 'زیست‌شناسی',      q: 40, weight: 4 },
                        { name: 'شیمی',            q: 25, weight: 3 },
                        { name: 'فیزیک',           q: 20, weight: 2 },
                        { name: 'ریاضی',           q: 20, weight: 2 },
                        { name: 'ادبیات فارسی',    q: 25, weight: 1 },
                        { name: 'عربی',            q: 20, weight: 1 },
                        { name: 'دین و زندگی',     q: 25, weight: 1 },
                    ],
                    ensani: [
                        { name: 'ادبیات فارسی',    q: 30, weight: 4 },
                        { name: 'عربی',            q: 25, weight: 3 },
                        { name: 'تاریخ و جغرافیا', q: 25, weight: 2 },
                        { name: 'اقتصاد',          q: 20, weight: 2 },
                        { name: 'فلسفه و منطق',    q: 20, weight: 2 },
                        { name: 'زبان انگلیسی',    q: 25, weight: 1 },
                        { name: 'دین و زندگی',     q: 25, weight: 1 },
                    ],
                    honar: [
                        { name: 'درک عمومی هنر',   q: 30, weight: 4 },
                        { name: 'ادبیات فارسی',    q: 25, weight: 2 },
                        { name: 'عربی',            q: 20, weight: 1 },
                        { name: 'زبان انگلیسی',    q: 25, weight: 1 },
                    ],
                    zaban: [
                        { name: 'زبان انگلیسی',    q: 60, weight: 4 },
                        { name: 'ادبیات فارسی',    q: 25, weight: 2 },
                        { name: 'عربی',            q: 20, weight: 1 },
                    ],
                },

                init() {},

                updateSubjects() {
                    if (!this.selectedGroup) return;
                    this.currentSubjects = this.subjectData[this.selectedGroup] || [];
                    this.inputs = this.currentSubjects.map(() => ({ correct: '', wrong: '' }));
                },

                currentGroupLabel() {
                    const g = this.groups.find(x => x.id === this.selectedGroup);
                    return g ? g.label : '';
                },

                goNext() {
                    if (!this.selectedGroup) return;
                    this.step = 2;
                },

                calcPct(i) {
                    const s = this.currentSubjects[i];
                    if (!s) return 0;
                    const c = parseFloat(this.inputs[i]?.correct) || 0;
                    const w = parseFloat(this.inputs[i]?.wrong) || 0;
                    const raw = (c - w / 3) / s.q * 100;
                    return Math.max(0, Math.min(100, Math.round(raw)));
                },

                updateInput(i) {},

                weightedAvg() {
                    if (!this.currentSubjects.length) return 0;
                    let wSum = 0, wTotal = 0;
                    this.currentSubjects.forEach((s, i) => {
                        wSum += this.calcPct(i) * s.weight;
                        wTotal += s.weight;
                    });
                    return wTotal ? Math.round(wSum / wTotal) : 0;
                },

                calcRank() {
                    const group = this.groups.find(x => x.id === this.selectedGroup);
                    const avg = this.weightedAvg();
                    const taraz = Math.round(3000 + avg * 50);
                    const ratio = Math.max(0, 1 - (avg / 100) * 0.92);
                    const rank = Math.max(1, Math.round(ratio * group.total));
                    const percentile = Math.round((1 - ratio) * 100);

                    let tip = '', tipIcon = '';
                    if (avg >= 70) {
                        tip = 'وضعیت فوق‌العاده! با این درصد شانس بالایی برای قبولی در دانشگاه‌های برتر داری. سعی کن ثابت نگهش داری.';
                        tipIcon = '🏆';
                    } else if (avg >= 50) {
                        tip = 'وضعیت خوبی داری. با تمرکز بیشتر روی درس‌های ضعیف‌تر می‌توانی رتبه را به شکل قابل‌توجهی بهتر کنی.';
                        tipIcon = '📈';
                    } else if (avg >= 30) {
                        tip = 'هنوز وقت هست! با برنامه‌ریزی دقیق و تمرکز روی درس‌های پرضریب می‌توانی رتبه را به نصف برسانی.';
                        tipIcon = '⚡';
                    } else {
                        tip = 'نگران نباش. بسیاری از قبول‌شده‌های برتر از همین نقطه شروع کردند. با برنامه و انگیزه می‌شه به رتبه‌های خوب رسید.';
                        tipIcon = '💪';
                    }

                    this.result = {
                        rankDisplay: rank.toLocaleString('fa-IR'),
                        totalDisplay: group.total.toLocaleString('fa-IR'),
                        avgPct: avg,
                        taraz: taraz.toLocaleString('fa-IR'),
                        percentile,
                        tip,
                        tipIcon,
                        subjects: this.currentSubjects.map((s, i) => ({
                            name: s.name,
                            weight: s.weight,
                            pct: this.calcPct(i),
                        })),
                    };

                    this.step = 3;
                },

                reset() {
                    this.step = 1;
                    this.selectedGroup = '';
                    this.selectedYear = 12;
                    this.selectedQuota = 'free';
                    this.inputs = [];
                    this.currentSubjects = [];
                    this.result = {};
                },
            };
        }
    </script>
    @endscript

</div>
