<div class="py-10" dir="rtl">
    <section class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="rounded-3xl bg-gradient-to-l from-blue-700 via-blue-600 to-indigo-700 text-white p-8 md:p-10 shadow-2xl">
                <p class="text-sm md:text-base text-blue-100 mb-3">دانلود اپلیکیشن SDFR</p>
                <h1 class="text-2xl md:text-4xl font-black leading-tight mb-4">اپلیکیشن <span class="text-yellow-300 text-3xl">SDFR</span> رو نصب کن!</h1>
                <p class="text-sm md:text-base leading-8 text-blue-100 mb-7">
                     بدون نیاز به دانلود از مارکت، اپلیکیشن را مستقیم روی گوشی یا دسکتاپ داشته باش.
                </p>

                <div class="flex flex-wrap items-center gap-3">
                    <button type="button" id="installAppPage"
                            class="rounded-full px-6 py-3 font-bold bg-emerald-500 hover:bg-emerald-400 active:scale-[0.98] transition">
                        نصب اپلیکیشن
                    </button>
                    <span id="pwaInstallHint" class="inline-flex items-center rounded-full bg-white/15 px-4 py-2 text-sm font-bold">
                        نسخه فعال: PWA
                    </span>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-4 mt-6">
                <div class="rounded-2xl border border-emerald-400/50 bg-emerald-500/10 p-5">
                    <p class="text-lg font-bold text-emerald-400 mb-2">PWA</p>
                    <p class="text-sm text-foreground/80">فعّال و قابل نصب روی Android، iOS و دسکتاپ.</p>
                </div>

                <div class="rounded-2xl border border-slate-700 bg-slate-900/40 p-5 opacity-60">
                    <p class="text-lg font-bold mb-2">Android APK</p>
                    <p class="text-sm text-foreground/70">به‌زودی</p>
                </div>

                <div class="rounded-2xl border border-slate-700 bg-slate-900/40 p-5 opacity-60">
                    <p class="text-lg font-bold mb-2">iOS AppStore</p>
                    <p class="text-sm text-foreground/70">به‌زودی</p>
                </div>
            </div>
        </div>
    </section>
    <!-- ANDROID Modal -->
    <div id="pwaAndroidModal" class="hidden fixed inset-0 z-50">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

        <div class="relative mx-auto w-[92%] max-w-lg mt-16 sm:mt-24 rounded-2xl overflow-hidden
              bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-2xl">
            <div class="p-5 sm:p-6">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-lg sm:text-xl font-extrabold">نصب اپلیکیشن روی اندروید</p>
                        <p class="mt-2 text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-7">
                            1) روی دکمه <b>«فهمیدم»</b> بزن.<br>
                            2) پنجره‌ی نصب مرورگر باز میشه.<br>
                            3) گزینه <b>Install</b> یا <b>Add</b> رو بزن تا اپ نصب بشه ✅
                        </p>
                    </div>
                    <button type="button" data-close-modal="android"
                            class="shrink-0 rounded-xl px-3 py-1.5 text-sm font-bold
                       bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 transition">
                        بستن
                    </button>
                </div>

                <div class="mt-5 flex flex-col sm:flex-row gap-2 sm:gap-3 sm:justify-end">
                    <button type="button" data-understood="android"
                            class="rounded-xl px-4 py-2 font-bold bg-emerald-500 hover:bg-emerald-400 text-white transition">
                        فهمیدم ✅
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- IOS Modal -->
    <div id="pwaIOSModal" class="hidden fixed inset-0 z-50">
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

        <div class="relative mx-auto w-[92%] max-w-lg mt-16 sm:mt-24 rounded-2xl overflow-hidden
              bg-white dark:bg-slate-900 text-slate-900 dark:text-slate-100 shadow-2xl">
            <div class="p-5 sm:p-6">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-lg sm:text-xl font-extrabold">نصب روی iPhone / iPad</p>
                        <p class="mt-2 text-sm sm:text-base text-slate-600 dark:text-slate-300 leading-7">
                            iOS نصب خودکار نداره. برای نصب:<br>
                            1) پایین Safari روی دکمه <b>Share</b> بزن (آیکن مربع با فلش بالا).<br>
                            2) گزینه <b>Add to Home Screen</b> رو انتخاب کن.<br>
                            3) روی <b>Add</b> بزن ✅
                        </p>
                    </div>
                    <button type="button" data-close-modal="ios"
                            class="shrink-0 rounded-xl px-3 py-1.5 text-sm font-bold
                       bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 transition">
                        بستن
                    </button>
                </div>

                <div class="mt-5 flex flex-col sm:flex-row gap-2 sm:gap-3 sm:justify-end">
                    <button type="button" data-close-modal="ios"
                            class="rounded-xl px-4 py-2 font-bold bg-emerald-500 hover:bg-emerald-400 text-white transition">
                        فهمیدم ✅
                    </button>
                </div>
            </div>
        </div>
    </div>
    @script
    <script data-navigate-once>
        const androidModal = document.getElementById('pwaAndroidModal');
        const iosModal     = document.getElementById('pwaIOSModal');
        const installPageBtn = document.getElementById('installAppPage');

        let deferredPrompt = null;

        const LS_KEY_INSTALLED = 'pwa_installed';

        // ── helpers ──────────────────────────────────────────────────────────
        function setInstalled() {
            localStorage.setItem(LS_KEY_INSTALLED, 'true');
        }

        function isInstalledSaved() {
            return localStorage.getItem(LS_KEY_INSTALLED) === 'true';
        }

        const isInStandaloneMode =
            window.matchMedia('(display-mode: standalone)').matches ||
            window.navigator.standalone ||
            document.referrer.startsWith('android-app://');

        const isIOS     = /iPhone|iPad|iPod/i.test(navigator.userAgent);
        const isAndroid = /Android/i.test(navigator.userAgent);

        // ── وضعیت دکمه نصب صفحه ─────────────────────────────────────────────
        function updateInstallPageBtn() {
            if (!installPageBtn) return;

            if (isInStandaloneMode || isInstalledSaved()) {
                installPageBtn.textContent = '✅ نصب شده';
                installPageBtn.disabled = true;
                installPageBtn.classList.remove('bg-emerald-500', 'hover:bg-emerald-400');
                installPageBtn.classList.add('bg-slate-500', 'cursor-not-allowed', 'opacity-70');
            }
        }

        // ── modal helpers ─────────────────────────────────────────────────────
        function openModal(type) {
            if (type === 'android') androidModal.classList.remove('hidden');
            if (type === 'ios')     iosModal.classList.remove('hidden');
            document.documentElement.classList.add('overflow-hidden');
        }

        function closeModal(type) {
            if (type === 'android') androidModal.classList.add('hidden');
            if (type === 'ios')     iosModal.classList.add('hidden');
            document.documentElement.classList.remove('overflow-hidden');
        }

        // ── نصب واقعی ────────────────────────────────────────────────────────
        async function triggerInstallPrompt() {
            if (!deferredPrompt) {
                alert('متاسفانه مرورگر شما از نصب خودکار پشتیبانی نمی‌کند.');
                return;
            }
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            if (outcome === 'accepted') {
                setInstalled();
                updateInstallPageBtn();
            }
            deferredPrompt = null;
        }

        // ── رویداد نصب مرورگر ────────────────────────────────────────────────
        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
        });

        // ── بعد از نصب موفق ───────────────────────────────────────────────────
        window.addEventListener('appinstalled', () => {
            setInstalled();
            updateInstallPageBtn();
            deferredPrompt = null;
        });

        // ── دکمه نصب صفحه ────────────────────────────────────────────────────
        if (installPageBtn) {
            installPageBtn.addEventListener('click', async () => {
                if (isInStandaloneMode || isInstalledSaved()) return; // قبلاً نصب شده

                if (isIOS) {
                    openModal('ios');
                    return;
                }

                if (isAndroid) {
                    openModal('android');
                    return;
                }

                // دسکتاپ یا مرورگرهای دیگه
                await triggerInstallPrompt();
            });
        }

        // ── بستن مودال‌ها ─────────────────────────────────────────────────────
        document.querySelectorAll('[data-close-modal]').forEach(btn => {
            btn.addEventListener('click', (e) => {
                closeModal(e.currentTarget.getAttribute('data-close-modal'));
            });
        });

        // دکمه «فهمیدم» اندروید → نصب واقعی
        document.querySelectorAll('[data-understood="android"]').forEach(btn => {
            btn.addEventListener('click', async () => {
                closeModal('android');
                await triggerInstallPrompt();
            });
        });

        // ── بارگذاری اولیه ────────────────────────────────────────────────────
        updateInstallPageBtn();
    </script>
    @endscript
</div>
