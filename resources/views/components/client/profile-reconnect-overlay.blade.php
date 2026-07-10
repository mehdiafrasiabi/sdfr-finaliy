<div x-data="sdfrReconnectOverlay()" x-init="init()" x-show="show" x-cloak
     class="fixed inset-0 z-[140] flex items-center justify-center bg-[#05070c]/95 p-6 text-center">
    <div class="w-full max-w-sm rounded-3xl border border-white/10 bg-[#05070c] px-6 py-8 shadow-2xl shadow-sky-500/10">
        <img src="/client/assets/logoPwa/logo180.png"
             alt="SDFR"
             class="mx-auto mb-5 h-24 w-24 rounded-[28px] shadow-2xl shadow-sky-500/20 ring-1 ring-white/10">
        <h3 class="mb-2 text-2xl font-black text-white">خطا در اتصال</h3>
        <p class="mb-6 text-sm leading-7 text-neutral-300">
            ارتباط شما با سرور برقرار نشد. اینترنت را بررسی کنید و اگر VPN روشن است، لطفاً آن را خاموش کنید.
        </p>
        <button @click="retryConnection()"
                class="inline-flex items-center justify-center rounded-2xl bg-sky-500 px-7 py-3 text-sm font-black text-white shadow-lg shadow-sky-500/25 transition hover:bg-sky-400">
            اتصال مجدد
        </button>
    </div>
</div>

@once
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    <script>
        function sdfrReconnectOverlay() {
            return {
                show: false,
                onlineHandler: null,
                offlineHandler: null,

                init() {
                    this.show = !navigator.onLine;
                    this.offlineHandler = () => {
                        this.show = true;
                        document.body.style.overflow = 'hidden';
                    };
                    this.onlineHandler = () => {
                        this.show = false;
                        document.body.style.overflow = '';
                    };

                    window.addEventListener('offline', this.offlineHandler);
                    window.addEventListener('online', this.onlineHandler);

                    if (this.show) {
                        document.body.style.overflow = 'hidden';
                    }
                },

                retryConnection() {
                    window.location.reload();
                },
            };
        }
    </script>
@endonce
