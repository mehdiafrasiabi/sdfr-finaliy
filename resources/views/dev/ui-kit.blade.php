<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    {{-- تشخیص تم قبل از رندر، تا هنگام رفرش صفحه فلش رنگ غلط نداشته باشیم --}}
    <script>
        (function () {
            var KEY = 'sdfr-ui-kit-theme';
            var saved = localStorage.getItem(KEY);
            var theme = saved || 'dark';
            document.documentElement.classList.toggle('dark', theme === 'dark');
        })();
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <title>کیت رابط کاربری SDFR — پیش‌نمایش کامپوننت‌ها</title>

    <link rel="stylesheet" href="/client/assets/css/fonts.css"/>
    <link rel="stylesheet" href="/client/assets/css/app.css"/>
    <script src="/client/assets/tailwind-3.4.17.js"></script>

    @livewireStyles
    @stack('link')

    <style>
        [x-cloak] { display: none !important; }

        body {
            font-family: 'YekanBakh', ui-sans-serif, system-ui, sans-serif;
        }

        .kit-card {
            border-radius: 1.25rem;
            border-width: 1px;
        }

        ::selection {
            background: hsl(var(--primary) / .25);
        }
    </style>
</head>
<body class="bg-background text-foreground min-h-screen antialiased" dir="rtl">

<div
    x-data="{
        dark: document.documentElement.classList.contains('dark'),
        uploadPercent: 0,
        uploading: false,
        startUpload() {
            if (this.uploading) return;
            this.uploading = true;
            this.uploadPercent = 0;
            const timer = setInterval(() => {
                this.uploadPercent = Math.min(100, this.uploadPercent + Math.random() * 12 + 4);
                if (this.uploadPercent >= 100) {
                    this.uploadPercent = 100;
                    this.uploading = false;
                    clearInterval(timer);
                }
            }, 220);
        },
        page: 4,
        totalPages: 12,
        goPage(p) { if (p >= 1 && p <= this.totalPages) this.page = p; }
    }"
    x-init="$watch('dark', v => { document.documentElement.classList.toggle('dark', v); localStorage.setItem('sdfr-ui-kit-theme', v ? 'dark' : 'light'); })"
>

    {{-- ═══════ نوار بالا ═══════ --}}
    <header class="sticky top-0 z-40 backdrop-blur-md bg-background/80 border-b border-border">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center justify-between">
            <div>
                <h1 class="text-sm sm:text-base font-extrabold">کیت رابط کاربری SDFR</h1>
                <p class="text-[11px] text-muted">پیش‌نمایش زنده‌ی کامپوننت‌های ui/ — برای تست دکمه، وضعیت، پیشرفت، صفحه‌بندی و مودال</p>
            </div>

            {{-- سوییچ روشن/تاریک --}}
            <button
                type="button"
                @click="dark = !dark"
                data-elevated="false"
                class="btn-press relative inline-flex items-center gap-2 rounded-full border border-border bg-secondary px-3 h-9 text-xs font-bold hover:bg-border/60 transition-colors"
            >
                <svg x-show="dark" x-cloak class="w-4 h-4 text-warning" fill="currentColor" viewBox="0 0 20 20"><path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zM5.05 6.464A1 1 0 106.464 5.05l-.707-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1zm6 7a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1z"/></svg>
                <svg x-show="!dark" x-cloak class="w-4 h-4 text-info" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/></svg>
                <span x-text="dark ? 'حالت تاریک' : 'حالت روشن'"></span>
            </button>
        </div>
    </header>

    <main class="max-w-5xl mx-auto px-4 sm:px-6 py-8 space-y-10">

        {{-- ═══════ ۱) دکمه‌ها ═══════ --}}
        <section class="kit-card border-border bg-secondary/30 p-5 sm:p-6">
            <h2 class="text-sm font-extrabold mb-1">۱) دکمه‌های عملیاتی — با حس فشاری</h2>
            <p class="text-[11px] text-muted mb-5">دقیقاً همان دکمه‌ی نمونه‌ی خودت، تبدیل‌شده به کامپوننت <code class="text-primary">x-ui.button</code>. رویش کلیک کن تا فشرده شدن و برگشتنش را ببینی.</p>

            {{-- دمو دقیقاً مطابق نمونه‌ی ارسالی --}}
            <div
                x-data="{
                    index: 0,
                    steps: [1, 2, 3, 4],
                    transitioning: false,
                    next() {
                        if (this.transitioning) return;
                        this.transitioning = true;
                        setTimeout(() => {
                            this.index = this.index < this.steps.length - 1 ? this.index + 1 : 0;
                            this.transitioning = false;
                        }, 450);
                    }
                }"
                class="flex items-center gap-4 mb-6 p-4 rounded-xl border border-dashed border-border"
            >
                <x-ui.button @click="next()" x-bind:disabled="transitioning">
                    <span x-text="transitioning ? 'در حال رفتن...' : (index >= steps.length - 1 ? 'تمام 🎉' : 'بعدی')"></span>
                </x-ui.button>
                <span class="text-xs text-muted">مرحله <span x-text="index + 1"></span> از <span x-text="steps.length"></span></span>
            </div>

            {{-- انواع variant --}}
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
                <x-ui.button variant="primary">اصلی</x-ui.button>
                <x-ui.button variant="secondary">ثانویه</x-ui.button>
                <x-ui.button variant="outline">outline</x-ui.button>
                <x-ui.button variant="ghost">ghost</x-ui.button>
                <x-ui.button variant="success">موفق</x-ui.button>
                <x-ui.button variant="warning">هشدار</x-ui.button>
                <x-ui.button variant="error">خطرناک</x-ui.button>
                <x-ui.button variant="info">اطلاعات</x-ui.button>
            </div>

            {{-- سایزها --}}
            <div class="flex flex-wrap items-center gap-3 mb-6">
                <x-ui.button size="sm">کوچک</x-ui.button>
                <x-ui.button size="md">متوسط (پیش‌فرض)</x-ui.button>
                <x-ui.button size="lg">بزرگ</x-ui.button>
                <x-ui.button pill>گرد (pill)</x-ui.button>
            </div>

            {{-- حالت‌ها: غیرفعال / بارگذاری --}}
            <p class="text-[11px] text-muted mb-2">
                حالت <code class="text-primary">loading</code> برای وقتی است که پراپ از سرور/Livewire می‌آید
                (مثلاً <code class="text-primary">:loading="$wire.saving"</code>) و بعد از رفت‌وبرگشت شبکه اسپینر خودش خاموش می‌شود؛
                این‌جا فقط ظاهرش را می‌بینی. دکمه‌ی «غیرفعال (کلیک کن)» چون <code class="text-primary">:disabled</code> عادیِ Alpine است، همین الان زنده کار می‌کند.
            </p>
            <div
                x-data="{ demoDisabled: false }"
                class="flex flex-wrap items-center gap-3"
            >
                <x-ui.button loading variant="secondary">در حال ذخیره...</x-ui.button>
                <x-ui.button disabled>همیشه غیرفعال</x-ui.button>
                <x-ui.button variant="error" @click="demoDisabled = true; setTimeout(() => demoDisabled = false, 1500)" x-bind:disabled="demoDisabled">
                    <span x-text="demoDisabled ? 'در حال پردازش...' : 'غیرفعال (کلیک کن)'"></span>
                </x-ui.button>
                <x-ui.button variant="secondary" block class="max-w-xs">عرض کامل (block)</x-ui.button>
            </div>
        </section>

        {{-- ═══════ ۲) نشان‌های وضعیت ═══════ --}}
        <section class="kit-card border-border bg-secondary/30 p-5 sm:p-6">
            <h2 class="text-sm font-extrabold mb-1">۲) نشان‌های وضعیت</h2>
            <p class="text-[11px] text-muted mb-5">هر وضعیت هم رنگ متفاوت دارد هم آیکون/نقطه‌ی متفاوت (نه فقط رنگ) تا برای کاربر رنگ‌کور هم قابل تشخیص باشد.</p>

            <div class="flex flex-wrap gap-2.5">
                <x-ui.status-badge status="pending"/>
                <x-ui.status-badge status="voided"/>
                <x-ui.status-badge status="cancelled"/>
                <x-ui.status-badge status="paid"/>
                <x-ui.status-badge status="not_started"/>
                <x-ui.status-badge status="joinable"/>
                <x-ui.status-badge status="expired"/>
                <x-ui.status-badge status="completed"/>
                <x-ui.status-badge status="active"/>
                <x-ui.status-badge status="inactive"/>
            </div>
        </section>

        {{-- ═══════ ۳) نوار پیشرفت آپلود ═══════ --}}
        <section class="kit-card border-border bg-secondary/30 p-5 sm:p-6">
            <h2 class="text-sm font-extrabold mb-1">۳) نوار پیشرفت (درصد آپلود فایل)</h2>
            <p class="text-[11px] text-muted mb-5">نمونه‌ی زنده — دکمه را بزن تا شبیه‌سازی آپلود اجرا شود.</p>

            <div class="space-y-5 mb-6">
                <x-ui.progress-bar model="uploadPercent" label="در حال آپلود فایل..." variant="info" :striped="true"/>
                <x-ui.button size="sm" variant="secondary" @click="startUpload()" x-bind:disabled="uploading">
                    <span x-text="uploading ? 'در حال آپلود...' : 'شبیه‌سازی آپلود'"></span>
                </x-ui.button>
            </div>

            <div class="grid sm:grid-cols-2 gap-5">
                <x-ui.progress-bar :percent="100" label="آپلود موفق" variant="success"/>
                <x-ui.progress-bar :percent="35" label="آپلود ناموفق" variant="error"/>
                <x-ui.progress-bar :percent="60" label="اندازه‌ی کوچک (sm)" size="sm" variant="primary"/>
                <x-ui.progress-bar :percent="80" label="اندازه‌ی بزرگ (lg)" size="lg" variant="warning"/>
            </div>
        </section>

        {{-- ═══════ ۴) صفحه‌بندی ═══════ --}}
        <section class="kit-card border-border bg-secondary/30 p-5 sm:p-6">
            <h2 class="text-sm font-extrabold mb-1">۴) صفحه‌بندی (Pagination)</h2>
            <p class="text-[11px] text-muted mb-5">
                این‌جا چون paginator واقعی لاراول نداریم، ظاهر و رفتار با متغیرهای Alpine شبیه‌سازی شده؛
                در صفحات واقعی از <code class="text-primary">x-ui.pagination</code> با
                <code class="text-primary">:paginator="$results"</code> استفاده کن.
            </p>

            <nav class="flex justify-center" role="navigation" aria-label="ناوبری صفحات (دمو)">
                <ul class="inline-flex flex-wrap items-center justify-center gap-1.5 sm:gap-2">
                    <li>
                        <button
                            @click="goPage(page - 1)" data-elevated="false" x-bind:disabled="page === 1"
                            class="btn-press inline-flex items-center px-3 sm:px-4 py-1.5 text-xs sm:text-sm font-medium rounded-full border border-border transition-colors"
                            :class="page === 1 ? 'text-muted bg-secondary/60 cursor-not-allowed' : 'text-foreground bg-secondary hover:bg-border/60'"
                        >قبلی</button>
                    </li>
                    <template x-for="p in totalPages" :key="p">
                        <li>
                            <button
                                x-show="Math.abs(p - page) <= 2 || p === 1 || p === totalPages"
                                @click="goPage(p)" data-elevated="false"
                                class="btn-press inline-flex items-center justify-center min-w-[2.25rem] px-3 sm:px-4 py-1.5 text-xs sm:text-sm font-medium rounded-full transition-colors"
                                :class="p === page ? 'bg-primary text-white font-bold shadow-md shadow-primary/20' : 'bg-secondary text-foreground border border-border hover:bg-border/60'"
                                x-text="p"
                            ></button>
                        </li>
                    </template>
                    <li>
                        <button
                            @click="goPage(page + 1)" data-elevated="false" x-bind:disabled="page === totalPages"
                            class="btn-press inline-flex items-center px-3 sm:px-4 py-1.5 text-xs sm:text-sm font-medium rounded-full border border-border transition-colors"
                            :class="page === totalPages ? 'text-muted bg-secondary/60 cursor-not-allowed' : 'text-foreground bg-secondary hover:bg-border/60'"
                        >بعدی</button>
                    </li>
                </ul>
            </nav>
        </section>

        {{-- ═══════ ۵) مودال ═══════ --}}
        <section class="kit-card border-border bg-secondary/30 p-5 sm:p-6">
            <h2 class="text-sm font-extrabold mb-1">۵) مودال باکس</h2>
            <p class="text-[11px] text-muted mb-5">باز/بسته شدن با ایونت‌های window انجام می‌شود، پس هر دکمه‌ای در هر جای صفحه می‌تواند مودال را کنترل کند.</p>

            <div class="flex flex-wrap gap-3">
                <x-ui.button variant="secondary" @click="$dispatch('open-modal', 'info-demo')">باز کردن مودال اطلاعات</x-ui.button>
                <x-ui.button variant="error" @click="$dispatch('open-modal', 'confirm-demo')">باز کردن مودال تایید حذف</x-ui.button>
            </div>
        </section>

        <p class="text-center text-[11px] text-muted pb-4">
            کامپوننت‌ها در <code class="text-primary">resources/views/components/ui/</code> اضافه شدند:
            button، status-badge، progress-bar، pagination، modal — همه dark/light-aware و از توکن‌های رنگی خودِ پروژه استفاده می‌کنند.
        </p>
    </main>

    {{-- مودال اطلاعات --}}
    <x-ui.modal id="info-demo" max-width="sm">
        <x-slot:title>راهنما</x-slot:title>
        این یک مودالِ نمونه از کامپوننت <strong class="text-foreground">x-ui.modal</strong> است؛
        با کلیک روی بک‌دراپ، دکمه‌ی ×، یا کلید Esc بسته می‌شود.
        <x-slot:footer>
            <x-ui.button variant="secondary" size="sm" @click="$dispatch('close-modal', 'info-demo')">باشه</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>

    {{-- مودال تایید حذف --}}
    <x-ui.modal id="confirm-demo" max-width="sm">
        <x-slot:title>حذف این مورد؟</x-slot:title>
        این عملیات غیرقابل بازگشت است. مطمئنی می‌خوای ادامه بدی؟
        <x-slot:footer>
            <x-ui.button variant="secondary" size="sm" @click="$dispatch('close-modal', 'confirm-demo')">انصراف</x-ui.button>
            <x-ui.button variant="error" size="sm" @click="$dispatch('close-modal', 'confirm-demo')">بله، حذف کن</x-ui.button>
        </x-slot:footer>
    </x-ui.modal>
</div>

@livewireScripts
</body>
</html>
