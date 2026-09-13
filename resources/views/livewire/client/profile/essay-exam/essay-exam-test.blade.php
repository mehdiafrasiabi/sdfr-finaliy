<div x-data="{
        remaining: @js((int) $remainingSeconds),
        showTimer: true,
        zoom: 1,
        pdfBaseUrl: @js($pdfUrl ?? ''),
        securityActive: false,
        showSubmitModal: false,
        showImageModal: false,
        viewingImageUrl: '',
        confirmDelete: null,
        stagedPreviews: [],
        isUploading: false,
        uploadProgress: 0,
        _booted: false,

        get pdfSrc() {
            if (!this.pdfBaseUrl) return '';
            const zoomVal = Math.round(this.zoom * 100);
            const baseUrl = this.pdfBaseUrl.split('#')[0];
            return baseUrl + '#toolbar=0&navpanes=0&scrollbar=1&zoom=' + zoomVal;
        },

        boot() {
            if (this._booted) return;
            this._booted = true;
            this.startTimer();
            this.setupSecurity();

            this.$wire.on('photos-uploaded', () => {
                this.clearStaged();
                this.isUploading = false;
                this.uploadProgress = 0;
            });
        },

        startTimer() {
            if (window._essayTimer) clearInterval(window._essayTimer);
            window._essayTimer = setInterval(() => {
                if (this.remaining > 0) {
                    this.remaining--;
                    return;
                }

                clearInterval(window._essayTimer);
                this.$wire.submitExam();
            }, 1000);
        },

        formatTime() {
            const r = Math.max(0, this.remaining);
            return {
                hours: Math.floor(r / 3600).toString().padStart(2, '0'),
                minutes: Math.floor((r % 3600) / 60).toString().padStart(2, '0'),
                seconds: (r % 60).toString().padStart(2, '0'),
            };
        },

        zoomIn() {
            this.zoom = Math.min(2, Math.round((this.zoom + 0.25) * 100) / 100);
        },

        zoomOut() {
            this.zoom = Math.max(0.5, Math.round((this.zoom - 0.25) * 100) / 100);
        },

        resetZoom() {
            this.zoom = 1;
        },

        setupSecurity() {
            const trigger = () => { this.securityActive = true; };

            if (window._essaySecurity) {
                document.removeEventListener('visibilitychange', window._essaySecurity.vis);
                window.removeEventListener('blur', window._essaySecurity.blur);
                document.removeEventListener('keydown', window._essaySecurity.key);
                window.removeEventListener('beforeprint', window._essaySecurity.print);
            }

            window._essaySecurity = {
                vis: () => { if (document.hidden) trigger(); },
                blur: trigger,
                key: (e) => {
                    if (e.key === 'PrintScreen') {
                        trigger();
                        navigator.clipboard?.writeText('').catch(() => {});
                    }

                    if ((e.metaKey || e.ctrlKey) && e.shiftKey && ['3', '4', '5', 'S', 's'].includes(e.key)) {
                        trigger();
                    }
                },
                print: trigger,
            };

            document.addEventListener('visibilitychange', window._essaySecurity.vis);
            window.addEventListener('blur', window._essaySecurity.blur);
            document.addEventListener('keydown', window._essaySecurity.key);
            window.addEventListener('beforeprint', window._essaySecurity.print);
        },

        onFileSelect(event) {
            const files = Array.from(event.target.files || []);
            this.clearStaged();
            this.stagedPreviews = files.map((file) => ({
                name: file.name,
                url: URL.createObjectURL(file),
            }));
            this.isUploading = files.length > 0;
            this.uploadProgress = 0;
        },

        onUploadFinish() {
            this.uploadProgress = 100;
            this.$wire.uploadPhotos();
        },

        clearStaged() {
            this.stagedPreviews.forEach((preview) => URL.revokeObjectURL(preview.url));
            this.stagedPreviews = [];
            if (this.$refs.fileInput) {
                this.$refs.fileInput.value = '';
            }
        },

        viewImage(url) {
            this.viewingImageUrl = url;
            this.showImageModal = true;
        },

        closeImage() {
            this.showImageModal = false;
            this.viewingImageUrl = '';
        },
     }"
     x-init="boot()"
     x-effect="(showSubmitModal || confirmDelete !== null || showImageModal || securityActive) ? window.SdfrModalScrollLock.lock() : window.SdfrModalScrollLock.unlock()"
     @contextmenu.prevent
     class="min-h-screen bg-background" dir="rtl">
    <div x-show="securityActive" x-cloak
         class="fixed inset-0 z-[99999] bg-black flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        <div class="text-center text-white space-y-5 max-w-md">
            <div class="flex justify-center">
                <div class="w-20 h-20 rounded-full bg-error/20 flex items-center justify-center">
                    <x-ui.icon name="triangle-alert" class="w-12 h-12 text-error"/>
                </div>
            </div>
            <h2 class="font-black text-2xl text-error">هشدار امنیتی!</h2>
            <p class="text-sm text-muted leading-relaxed">
                گرفتن اسکرین‌شات، تغییر پنجره، پرینت یا کپی محتوا ممنوع است.<br>
                این عمل ثبت شد و به عنوان تخلف گزارش می‌شود.
            </p>
            <x-ui.button type="button" @click="securityActive = false" variant="primary" icon="chevron-left">
                بازگشت به آزمون
            </x-ui.button>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 space-y-6">

        {{-- ════════════════════════════════════════
             تایمر بالا (sticky + توگل مشاهده)
           ════════════════════════════════════════ --}}
        <div class="sticky top-2 z-30 bg-secondary border border-border rounded-2xl p-4 shadow-lg shadow-black/5 backdrop-blur-sm">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                {{-- راست: عنوان آزمون --}}
                <div class="flex items-center gap-4">
                    <div class="flex-shrink-0 w-12 h-12 bg-primary/10 rounded-full flex items-center justify-center">
                        <x-ui.icon name="square-pen" class="w-6 h-6 text-primary"/>
                    </div>
                    <div style="margin-right: 10px">
                        <h1 class="font-bold text-xl text-foreground">{{ $exam->title }}</h1>
                        <p class="text-sm text-muted">{{ $exam->questions->count() }} سوال — نمره کل: {{ number_format($exam->total_score, 2) }}</p>
                    </div>
                </div>

                {{-- چپ: توگل مشاهده + باکس‌های زمان --}}
                <div class="flex flex-col sm:flex-row items-center justify-end gap-4">
                    {{-- توگل --}}
                    <div class="flex items-center gap-2 bg-background/80 border border-border rounded-2xl px-3 py-2">
                        <button type="button" class="relative inline-flex items-center gap-2" @click="showTimer = !showTimer">
                            <span class="flex items-center justify-center w-7 h-7 rounded-xl bg-primary/10 text-primary">
                                <x-ui.icon name="clock" class="w-4 h-4"/>
                            </span>
                            <span dir="ltr"
                                  class="relative inline-flex items-center w-11 h-6 rounded-full transition-colors duration-200"
                                  :class="showTimer ? 'bg-primary' : 'bg-muted'">
                                <span class="absolute top-0.5 w-5 h-5 rounded-full bg-white shadow-md transition-transform duration-200"
                                      :class="showTimer ? 'translate-x-[22px]' : 'translate-x-[2px]'"></span>
                            </span>
                        </button>
                        <span class="text-xs sm:text-sm text-muted" x-text="showTimer ? 'عدم مشاهده زمان' : 'مشاهده زمان'">مشاهده زمان</span>
                    </div>

                    {{-- باکس‌های زمان --}}
                    <div class="flex items-center justify-end gap-2 transition-all duration-200"
                         :class="showTimer ? '' : 'timer-blur-glass'">
                        <div class="flex flex-col items-center bg-background border border-border rounded-xl px-3 py-2 min-w-[50px]">
                            <span class="font-bold text-lg" :class="remaining < 60 ? 'text-error' : 'text-foreground'" x-text="formatTime().seconds"></span>
                            <span class="text-[10px] text-muted">ثانیه</span>
                        </div>
                        <span class="text-xl font-bold text-muted">:</span>
                        <div class="flex flex-col items-center bg-background border border-border rounded-xl px-3 py-2 min-w-[50px]">
                            <span class="font-bold text-lg text-foreground" x-text="formatTime().minutes"></span>
                            <span class="text-[10px] text-muted">دقیقه</span>
                        </div>
                        <span class="text-xl font-bold text-muted">:</span>
                        <div class="flex flex-col items-center bg-background border border-border rounded-xl px-3 py-2 min-w-[50px]">
                            <span class="font-bold text-lg text-foreground" x-text="formatTime().hours"></span>
                            <span class="text-[10px] text-muted">ساعت</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ════════════════════════════════════════
             ستون اصلی: PDF و پنل آپلود
           ════════════════════════════════════════ --}}
        <div class="grid md:grid-cols-5 gap-5">

            {{-- ─── PDF Viewer with Zoom Controls ─── --}}
            <div class="md:col-span-3 no-screenshot">
                <div class="bg-secondary border border-border rounded-2xl overflow-hidden flex flex-col">
                    {{-- نوار ابزار: عنوان + کنترل زوم --}}
                    <div class="p-3 flex items-center justify-between border-b border-border gap-2 flex-wrap">
                        <span class="font-semibold text-foreground text-sm">سوالات آزمون</span>

                        <div class="flex items-center gap-1.5">
                            <button type="button" @click="zoomOut()" data-elevated="false"
                                    :disabled="zoom <= 0.5"
                                    class="btn-press w-8 h-8 flex items-center justify-center bg-background border border-border rounded-lg hover:bg-secondary disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
                                    title="کوچک‌نمایی">
                                <x-ui.icon name="minus" class="w-4 h-4 text-foreground"/>
                            </button>

                            <button type="button" @click="resetZoom()" data-elevated="false"
                                    class="btn-press px-2 h-8 flex items-center justify-center bg-background border border-border rounded-lg hover:bg-secondary transition-colors text-xs font-bold text-foreground min-w-[52px]"
                                    title="بازنشانی زوم">
                                <span x-text="Math.round(zoom * 100) + '%'"></span>
                            </button>

                            <button type="button" @click="zoomIn()" data-elevated="false"
                                    :disabled="zoom >= 2"
                                    class="btn-press w-8 h-8 flex items-center justify-center bg-background border border-border rounded-lg hover:bg-secondary disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
                                    title="بزرگ‌نمایی">
                                <x-ui.icon name="plus" class="w-4 h-4 text-foreground"/>
                            </button>
                        </div>
                    </div>

                    {{-- PDF iframe --}}
                    @if($pdfUrl)
                        <div class="relative bg-muted" style="height: 75vh;">
                            <iframe :src="pdfSrc"
                                    class="w-full h-full" style="border:0;"
                                    oncontextmenu="return false;"></iframe>
                            <div class="absolute inset-0 pointer-events-none"></div>
                        </div>
                    @else
                        <div class="p-8 text-center text-muted">فایلی موجود نیست.</div>
                    @endif

                    <div class="px-3 py-2 border-t border-border bg-background/40">
                        <span class="text-[11px] text-error">دانلود، کپی یا اسکرین‌شات مجاز نیست.</span>
                    </div>
                </div>
            </div>

            {{-- ─── Upload Panel ─── --}}
            <div class="md:col-span-2">
                <div class="bg-secondary border border-border rounded-2xl p-4 space-y-4 md:sticky md:top-24">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center">
                            <x-ui.icon name="upload" class="w-4 h-4 text-primary"/>
                        </div>
                        <h3 class="font-bold text-foreground">ارسال پاسخ</h3>
                    </div>

                    <div class="bg-background/50 rounded-xl p-3 border border-border">
                        <p class="text-xs text-muted leading-6">
                            • حداکثر {{ \App\Livewire\Client\Profile\EssayExam\EssayExamTest::MAX_FILES }} تصویر<br>
                            • هر تصویر حداکثر 2 مگابایت<br>
                            • مجموع حداکثر 20 مگابایت
                        </p>
                    </div>

                    @error('photos') <div class="text-error text-xs bg-error/10 rounded-lg p-2">{{ $message }}</div> @enderror
                    @if(session('error')) <div class="text-error text-xs bg-error/10 rounded-lg p-2">{{ session('error') }}</div> @endif

                    {{-- دکمه انتخاب فایل --}}
                    <label class="block cursor-pointer">
                        <input type="file" wire:model="photos" multiple accept="image/*"
                               x-ref="fileInput"
                               @change="onFileSelect($event)"
                               x-on:livewire-upload-finish="onUploadFinish()"
                               x-on:livewire-upload-error="isUploading = false"
                               x-on:livewire-upload-progress="uploadProgress = $event.detail.progress"
                               class="hidden">
                        <div class="border-2 border-dashed border-border rounded-xl p-5 text-center hover:border-primary/50 hover:bg-background/50 transition-colors">
                            <x-ui.icon name="upload" class="w-10 h-10 mx-auto text-muted mb-2"/>
                            <p class="text-sm font-semibold text-foreground">انتخاب تصاویر</p>
                            <p class="text-xs text-muted mt-1">برای انتخاب یا کشیدن فایل کلیک کنید</p>
                        </div>
                    </label>

                    {{-- پیشنمایش‌های موقت + لودر --}}
                    <template x-if="stagedPreviews.length > 0">
                        <div class="space-y-2">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-semibold text-foreground">در حال آپلود <span x-text="stagedPreviews.length"></span> تصویر...</span>
                                <span x-show="isUploading" class="text-xs text-primary" x-text="uploadProgress + '%'"></span>
                            </div>
                            <div class="grid grid-cols-3 gap-2">
                                <template x-for="(p, i) in stagedPreviews" :key="i">
                                    <div class="relative">
                                        <img :src="p.url" class="w-full h-24 object-cover rounded-lg border border-border opacity-70">
                                        <div class="absolute inset-0 flex items-center justify-center bg-black/30 rounded-lg">
                                            <span class="w-6 h-6 rounded-full border-2 border-white/50 border-t-white animate-spin"></span>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>

                    <hr class="border-border">

                    {{-- تصاویر آپلودشده --}}
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="font-semibold text-sm text-foreground">
                                تصاویر آپلود شده ({{ $this->attempt->uploads->count() }})
                            </h4>
                        </div>

                        @if($this->attempt->uploads->count())
                            <div class="grid grid-cols-3 gap-2">
                                @foreach($this->attempt->uploads as $up)
                                    <div class="relative group">
                                        <img src="{{ $up->url }}" class="w-full h-24 object-cover rounded-lg border border-border">

                                        {{-- دکمه‌های روی hover --}}
                                        <div class="absolute inset-0 flex items-center justify-center gap-1 bg-black/60 rounded-lg opacity-0 group-hover:opacity-100 transition-opacity">
                                            {{-- دکمه مشاهده --}}
                                            <button type="button" data-elevated="true"
                                                    @click="viewImage('{{ $up->url }}')"
                                                    class="btn-press w-8 h-8 bg-primary text-primary-foreground rounded-full flex items-center justify-center hover:bg-primary/90"
                                                    title="مشاهده">
                                                <x-ui.icon name="eye" class="w-4 h-4"/>
                                            </button>
                                            {{-- دکمه حذف --}}
                                            <button type="button" data-elevated="true"
                                                    @click="confirmDelete = {{ $up->id }}"
                                                    class="btn-press w-8 h-8 bg-error text-white rounded-full flex items-center justify-center hover:bg-error/90"
                                                    title="حذف">
                                                <x-ui.icon name="trash" class="w-4 h-4"/>
                                            </button>
                                        </div>

                                        {{-- شماره --}}
                                        <span class="absolute top-1 right-1 bg-background/90 text-foreground text-[10px] font-bold rounded-full w-5 h-5 flex items-center justify-center">
                                            {{ $loop->iteration }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-xs text-muted py-4 text-center">هنوز تصویری آپلود نشده.</p>
                        @endif
                    </div>

                    <hr class="border-border">

                    {{-- دکمه ثبت نهایی --}}
                    <x-ui.button type="button"
                                 @click="showSubmitModal = true"
                                 variant="primary" icon="check" block
                                 :disabled="$this->attempt->uploads->count() === 0">
                        پایان و ارسال آزمون
                    </x-ui.button>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         مودال ثبت نهایی
       ════════════════════════════════════════ --}}
    <div x-show="showSubmitModal" x-cloak>
        <div
            x-show="showSubmitModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm"
            @click="showSubmitModal = false"
        ></div>

        <div
            x-show="showSubmitModal"
            class="fixed inset-0 z-[51] flex items-end justify-center overscroll-contain sm:items-center sm:p-4"
            @click.self="showSubmitModal = false"
        >
            <div
                x-show="showSubmitModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                class="relative w-full sm:max-w-md bg-background border border-border rounded-t-3xl sm:rounded-2xl flex flex-col pb-[env(safe-area-inset-bottom,0px)] sm:pb-0 shadow-2xl"
            >
                <div class="mx-auto mt-3 mb-1 h-1.5 w-14 rounded-full bg-border sm:hidden shrink-0"></div>

                <button type="button" @click="showSubmitModal = false" data-elevated="false"
                        class="btn-press absolute top-4 left-4 w-8 h-8 inline-flex items-center justify-center rounded-full text-muted hover:text-foreground hover:bg-secondary transition-colors z-10">
                    <x-ui.icon name="x" class="w-4 h-4"/>
                </button>

                <div class="p-6">
                    <h2 class="font-bold text-lg text-primary mb-4">ثبت نهایی آزمون</h2>
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-16 h-16 bg-success/15 rounded-full flex items-center justify-center">
                            <x-ui.icon name="circle-check" class="w-8 h-8 text-success"/>
                        </div>
                    </div>
                    <p class="text-sm text-muted leading-relaxed text-center">
                        آیا از پایان و ارسال آزمون اطمینان دارید؟<br>
                        پس از ارسال، امکان تغییر تصاویر وجود نخواهد داشت.
                    </p>
                    <div class="mt-4 bg-secondary rounded-xl p-3 text-center">
                        <p class="text-xs text-muted">تعداد تصاویر ارسالی:</p>
                        <p class="font-bold text-primary text-lg">{{ $this->attempt->uploads->count() }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 border-t border-border p-4">
                    <x-ui.button type="button" @click="showSubmitModal = false" variant="secondary-outline" icon="x" block>
                        انصراف
                    </x-ui.button>
                    <x-ui.button type="button" @click="$wire.submitExam(); showSubmitModal = false" variant="primary" icon="check" block>
                        بله، ارسال نهایی
                    </x-ui.button>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         مودال تأیید حذف
       ════════════════════════════════════════ --}}
    <div x-show="confirmDelete !== null" x-cloak>
        <div
            x-show="confirmDelete !== null"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm"
            @click="confirmDelete = null"
        ></div>

        <div
            x-show="confirmDelete !== null"
            class="fixed inset-0 z-[51] flex items-end justify-center overscroll-contain sm:items-center sm:p-4"
            @click.self="confirmDelete = null"
        >
            <div
                x-show="confirmDelete !== null"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-full sm:translate-y-0 sm:scale-95"
                class="relative w-full sm:max-w-sm bg-background border border-border rounded-t-3xl sm:rounded-2xl flex flex-col pb-[env(safe-area-inset-bottom,0px)] sm:pb-0 shadow-2xl"
            >
                <div class="mx-auto mt-3 mb-1 h-1.5 w-14 rounded-full bg-border sm:hidden shrink-0"></div>

                <button type="button" @click="confirmDelete = null" data-elevated="false"
                        class="btn-press absolute top-4 left-4 w-8 h-8 inline-flex items-center justify-center rounded-full text-muted hover:text-foreground hover:bg-secondary transition-colors z-10">
                    <x-ui.icon name="x" class="w-4 h-4"/>
                </button>

                <div class="p-6 text-center">
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-16 h-16 bg-error/15 rounded-full flex items-center justify-center">
                            <x-ui.icon name="trash" class="w-8 h-8 text-error"/>
                        </div>
                    </div>
                    <h2 class="font-bold text-lg text-foreground mb-2">حذف تصویر؟</h2>
                    <p class="text-sm text-muted">این تصویر حذف خواهد شد و قابل بازگشت نیست.</p>
                </div>

                <div class="flex items-center gap-3 border-t border-border p-4">
                    <x-ui.button type="button" @click="confirmDelete = null" variant="secondary-outline" icon="x" block>
                        انصراف
                    </x-ui.button>
                    <x-ui.button type="button" @click="$wire.deleteUpload(confirmDelete); confirmDelete = null" variant="error" icon="trash" block>
                        حذف
                    </x-ui.button>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         مودال مشاهده تصویر
       ════════════════════════════════════════ --}}
    <div x-show="showImageModal" x-cloak>
        <div
            x-show="showImageModal"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 z-50 bg-black/80 backdrop-blur-sm"
            @click="closeImage()"
        ></div>

        <div
            x-show="showImageModal"
            class="fixed inset-0 z-[51] flex items-center justify-center p-4 overscroll-contain"
            @click.self="closeImage()"
        >
            <div
                x-show="showImageModal"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="relative w-full max-w-4xl max-h-[90vh] flex flex-col"
            >
                {{-- دکمه بستن --}}
                <button type="button" @click="closeImage()" data-elevated="false"
                        class="btn-press absolute -top-3 -left-3 sm:top-4 sm:left-4 z-10 w-8 h-8 inline-flex items-center justify-center rounded-full bg-background border border-border text-muted hover:text-foreground hover:bg-secondary shadow-lg transition-colors">
                    <x-ui.icon name="x" class="w-4 h-4"/>
                </button>

                <img :src="viewingImageUrl"
                     class="w-full h-full object-contain rounded-2xl"
                     oncontextmenu="return false;">
            </div>
        </div>
    </div>


@assets
    <style>
        [x-cloak] { display: none !important; }
        .no-screenshot {
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
            -webkit-touch-callout: none;
        }
        .timer-blur-glass {
            filter: blur(6px);
            -webkit-backdrop-filter: blur(8px);
            backdrop-filter: blur(8px);
            background-color: rgba(15, 23, 42, 0.35);
            border-radius: 1rem;
            pointer-events: none;
            user-select: none;
            transition: all 0.2s ease-in-out;
        }
        @media print { body { display: none !important; } }
    </style>
@endassets

</div>
