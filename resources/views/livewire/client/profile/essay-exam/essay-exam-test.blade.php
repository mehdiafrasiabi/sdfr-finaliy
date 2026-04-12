<div x-data="essayExamTimer({{ $remainingSeconds }})" x-init="init()"
     @contextmenu.prevent
     class="max-w-7xl mx-auto px-4 py-6 space-y-6" dir="rtl">

    <style>
        .no-screenshot { user-select: none; -webkit-user-select: none; }
        @media print { body { display: none; } }
    </style>

    <!-- Anti-screenshot warning overlay (shown when window loses focus) -->
    <div x-show="warned" x-cloak
         class="fixed inset-0 bg-black/80 z-[100] flex items-center justify-center p-4">
        <div class="bg-background border border-error rounded-2xl max-w-md p-6 text-center space-y-4">
            <svg class="w-16 h-16 mx-auto text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
            </svg>
            <h3 class="font-bold text-lg text-foreground">هشدار!</h3>
            <p class="text-sm text-muted">
                گرفتن اسکرین‌شات، تغییر پنجره یا کپی محتوا ممنوع است و به عنوان تقلب ثبت می‌شود.
            </p>
            <button type="button" @click="warned = false" class="px-4 py-2 bg-primary text-primary-foreground rounded-lg">متوجه شدم</button>
        </div>
    </div>

    <!-- Header -->
    <div class="flex items-center justify-between bg-secondary border border-border rounded-2xl p-4 flex-wrap gap-3">
        <div>
            <h1 class="font-bold text-foreground text-lg">{{ $exam->title }}</h1>
            <div class="text-xs text-muted mt-1">تعداد سوالات: {{ $exam->questions->count() }} — نمره کل: {{ number_format($exam->total_score, 2) }}</div>
        </div>
        <div class="flex items-center gap-2 bg-background rounded-xl px-4 py-2 border border-border">
            <svg class="w-5 h-5 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-bold text-foreground" x-text="formatted"></span>
        </div>
    </div>

    <div class="grid md:grid-cols-5 gap-5">
        <!-- PDF Viewer (read-only) -->
        <div class="md:col-span-3 no-screenshot">
            <div class="bg-secondary border border-border rounded-2xl overflow-hidden">
                <div class="p-3 flex items-center justify-between border-b border-border">
                    <span class="font-semibold text-foreground text-sm">سوالات آزمون</span>
                    <span class="text-xs text-red-500">دانلود یا کپی مجاز نیست.</span>
                </div>
                @if($pdfUrl)
                    <div class="relative" style="height: 75vh;">
                        <iframe src="{{ $pdfUrl }}#toolbar=0&navpanes=0&scrollbar=1"
                                class="w-full h-full" style="border:0;"
                                oncontextmenu="return false;"></iframe>
                        <!-- Overlay to prevent right-click save -->
                        <div class="absolute inset-0 pointer-events-none"></div>
                    </div>
                @else
                    <div class="p-8 text-center text-muted">فایلی موجود نیست.</div>
                @endif
            </div>
        </div>

        <!-- Upload Panel -->
        <div class="md:col-span-2">
            <div class="bg-secondary border border-border rounded-2xl p-4 space-y-4 md:sticky md:top-24">
                <h3 class="font-bold text-foreground">ارسال پاسخ</h3>
                <p class="text-xs text-muted leading-6">
                    • حداکثر {{ \App\Livewire\Client\Profile\EssayExam\EssayExamTest::MAX_FILES }} تصویر<br>
                    • هر تصویر حداکثر 2 مگابایت<br>
                    • مجموع حداکثر 20 مگابایت
                </p>

                @error('photos') <div class="text-red-500 text-xs">{{ $message }}</div> @enderror
                @if(session('error')) <div class="text-red-500 text-xs">{{ session('error') }}</div> @endif
                @if(session('upload_success')) <div class="text-green-500 text-xs">{{ session('upload_success') }}</div> @endif

                <form wire:submit.prevent="uploadPhotos" class="space-y-3">
                    <input type="file" wire:model="photos" multiple accept="image/*"
                           class="block w-full text-sm text-foreground bg-background border border-border rounded-lg p-2">
                    <div wire:loading wire:target="photos" class="text-xs text-info">در حال دریافت...</div>
                    <button type="submit"
                            class="w-full px-4 py-2 bg-primary text-primary-foreground rounded-lg text-sm font-semibold disabled:opacity-50"
                            :disabled="!$wire.photos || $wire.photos.length === 0">
                        <span wire:loading.remove wire:target="uploadPhotos">آپلود تصاویر</span>
                        <span wire:loading wire:target="uploadPhotos">در حال آپلود...</span>
                    </button>
                </form>

                <hr class="border-border">

                <div>
                    <h4 class="font-semibold text-sm text-foreground mb-2">
                        تصاویر آپلود شده ({{ $this->attempt->uploads->count() }})
                    </h4>
                    @if($this->attempt->uploads->count())
                        <div class="grid grid-cols-3 gap-2">
                            @foreach($this->attempt->uploads as $up)
                                <div class="relative group">
                                    <img src="{{ $up->url }}" class="w-full h-24 object-cover rounded-lg border border-border">
                                    <button type="button" wire:click="deleteUpload({{ $up->id }})"
                                            wire:confirm="حذف شود؟"
                                            class="absolute top-1 left-1 bg-red-500 text-white rounded-full w-6 h-6 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                        ×
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-muted">هنوز تصویری آپلود نشده.</p>
                    @endif
                </div>

                <hr class="border-border">

                <button type="button"
                        wire:click="submitExam"
                        wire:confirm="آیا از پایان و ارسال آزمون اطمینان دارید؟"
                        class="w-full px-4 py-3 bg-green-500 hover:bg-green-600 text-white rounded-xl font-bold text-sm">
                    پایان و ارسال آزمون
                </button>
            </div>
        </div>
    </div>

    <script>
        function essayExamTimer(seconds) {
            return {
                remaining: seconds,
                formatted: '00:00:00',
                warned: false,
                init() {
                    this.tick();
                    setInterval(() => this.tick(), 1000);

                    // Warning on blur (possible screenshot / tab switch)
                    window.addEventListener('blur', () => { this.warned = true; });
                    // Detect print
                    window.addEventListener('beforeprint', (e) => { this.warned = true; });
                    // Basic screenshot-key detection
                    document.addEventListener('keyup', (e) => {
                        if (e.key === 'PrintScreen') {
                            navigator.clipboard.writeText('');
                            this.warned = true;
                        }
                    });
                },
                tick() {
                    if (this.remaining <= 0) {
                        this.formatted = '00:00:00';
                    @this.call('submitExam');
                        return;
                    }
                    this.remaining--;
                    const h = Math.floor(this.remaining / 3600).toString().padStart(2, '0');
                    const m = Math.floor((this.remaining % 3600) / 60).toString().padStart(2, '0');
                    const s = (this.remaining % 60).toString().padStart(2, '0');
                    this.formatted = `${h}:${m}:${s}`;
                },
            }
        }
    </script>
</div>
