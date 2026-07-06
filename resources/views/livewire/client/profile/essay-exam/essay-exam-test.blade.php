<div x-data="essayExamApp({
        remainingSeconds: {{ $remainingSeconds }},
        pdfUrl: @js($pdfUrl ?? ''),
     })"
     x-init="init()"
     @contextmenu.prevent
     class="min-h-screen bg-background" dir="rtl">

    {{-- ════════════════════════════════════════
         اوورلی سیاه امنیتی — کل صفحه را می‌پوشاند
         زمانی که اسکرین‌شات/تغییر تب/پرینت تشخیص داده شود
       ════════════════════════════════════════ --}}
    <div x-show="securityActive" x-cloak
         class="fixed inset-0 z-[99999] bg-black flex items-center justify-center p-4"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        <div class="text-center text-white space-y-5 max-w-md">
            <div class="flex justify-center">
                <div class="w-20 h-20 rounded-full bg-red-500/20 flex items-center justify-center">
                    <svg class="w-12 h-12 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            <h2 class="font-black text-2xl text-red-400">هشدار امنیتی!</h2>
            <p class="text-sm text-gray-300 leading-relaxed">
                گرفتن اسکرین‌شات، تغییر پنجره، پرینت یا کپی محتوا ممنوع است.<br>
                این عمل ثبت شد و به عنوان تخلف گزارش می‌شود.
            </p>
            <button type="button" @click="securityActive = false"
                    class="px-6 py-2.5 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-semibold text-sm">
                بازگشت به آزمون
            </button>
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
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
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
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                          d="M12 8v4l2 2m-5-9h6M12 4a8 8 0 100 16 8 8 0 000-16z"/>
                                </svg>
                            </span>
                            <span dir="ltr"
                                  class="relative inline-flex items-center w-11 h-6 rounded-full transition-colors duration-200"
                                  :class="showTimer ? 'bg-primary' : 'bg-gray-300 dark:bg-gray-600'">
                                <span class="absolute top-0.5 w-5 h-5 rounded-full bg-white shadow-md transition-transform duration-200"
                                      :class="showTimer ? 'translate-x-[22px]' : 'translate-x-[2px]'"></span>
                            </span>
                        </button>
                        <span class="text-xs sm:text-sm text-muted" x-text="showTimer ? 'عدم مشاهده زمان' : 'مشاهده زمان'">مشاهده زمان</span>
                    </div>

                    {{-- باکس‌های زمان --}}
                    <div class="flex items-center justify-end gap-2 transition-all duration-200"
                         :class="showTimer ? 'timer-blur-glass' : ''">
                        <div class="flex flex-col items-center bg-background border border-border rounded-xl px-3 py-2 min-w-[50px]">
                            <span class="font-bold text-lg" :class="remaining < 60 ? 'text-red-500' : 'text-foreground'" x-text="formatTime().seconds"></span>
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
                            <button type="button" @click="zoomOut()"
                                    :disabled="zoom <= 0.5"
                                    class="w-8 h-8 flex items-center justify-center bg-background border border-border rounded-lg hover:bg-secondary disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
                                    title="کوچک‌نمایی">
                                <svg class="w-4 h-4 text-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/>
                                </svg>
                            </button>

                            <button type="button" @click="resetZoom()"
                                    class="px-2 h-8 flex items-center justify-center bg-background border border-border rounded-lg hover:bg-secondary transition-colors text-xs font-bold text-foreground min-w-[52px]"
                                    title="بازنشانی زوم">
                                <span x-text="Math.round(zoom * 100) + '%'"></span>
                            </button>

                            <button type="button" @click="zoomIn()"
                                    :disabled="zoom >= 2"
                                    class="w-8 h-8 flex items-center justify-center bg-background border border-border rounded-lg hover:bg-secondary disabled:opacity-40 disabled:cursor-not-allowed transition-colors"
                                    title="بزرگ‌نمایی">
                                <svg class="w-4 h-4 text-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- PDF iframe --}}
                    @if($pdfUrl)
                        <div class="relative bg-gray-50 dark:bg-gray-900" style="height: 75vh;">
                            <iframe :src="pdfSrc"
                                    class="w-full h-full" style="border:0;"
                                    oncontextmenu="return false;"></iframe>
                            <div class="absolute inset-0 pointer-events-none"></div>
                        </div>
                    @else
                        <div class="p-8 text-center text-muted">فایلی موجود نیست.</div>
                    @endif

                    <div class="px-3 py-2 border-t border-border bg-background/40">
                        <span class="text-[11px] text-red-500">دانلود، کپی یا اسکرین‌شات مجاز نیست.</span>
                    </div>
                </div>
            </div>

            {{-- ─── Upload Panel ─── --}}
            <div class="md:col-span-2">
                <div class="bg-secondary border border-border rounded-2xl p-4 space-y-4 md:sticky md:top-24">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-primary/10 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5-5 5 5M12 5v12"/>
                            </svg>
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

                    @error('photos') <div class="text-red-500 text-xs bg-red-500/10 rounded-lg p-2">{{ $message }}</div> @enderror
                    @if(session('error')) <div class="text-red-500 text-xs bg-red-500/10 rounded-lg p-2">{{ session('error') }}</div> @endif

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
                            <svg class="w-10 h-10 mx-auto text-muted mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                            </svg>
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
                                            <button type="button"
                                                    @click="viewImage('{{ $up->url }}')"
                                                    class="w-8 h-8 bg-primary text-primary-foreground rounded-full flex items-center justify-center hover:bg-primary/90"
                                                    title="مشاهده">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </button>
                                            {{-- دکمه حذف --}}
                                            <button type="button"
                                                    @click="confirmDelete = {{ $up->id }}"
                                                    class="w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600"
                                                    title="حذف">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/>
                                                </svg>
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
                    <button type="button"
                            @click="showSubmitModal = true"
                            class="w-full px-4 py-3 bg-primary hover:bg-primary/90 text-primary-foreground rounded-xl font-bold text-sm transition-colors shadow-md shadow-primary/20 disabled:opacity-50"
                            :disabled="{{ $this->attempt->uploads->count() === 0 ? 'true' : 'false' }}">
                        پایان و ارسال آزمون
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         مودال ثبت نهایی (bottom-sheet موبایل، centered دسکتاپ)
       ════════════════════════════════════════ --}}
    <div x-show="showSubmitModal" x-cloak
         class="fixed inset-0 z-50 flex flex-col justify-end sm:items-center sm:justify-center"
         x-transition.opacity>
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="showSubmitModal = false"></div>

        <div class="relative w-full sm:max-w-md bg-secondary border-t sm:border border-border rounded-t-3xl sm:rounded-2xl flex flex-col pb-[env(safe-area-inset-bottom,0px)] sm:pb-0 shadow-xl"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-8">

            <div class="sm:hidden flex justify-center pt-3 pb-1 shrink-0">
                <div class="w-10 h-1 rounded-full bg-foreground/20"></div>
            </div>

            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="font-bold text-lg text-primary">ثبت نهایی آزمون</h2>
                    <button type="button" class="text-muted hover:text-foreground" @click="showSubmitModal = false">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
                <div class="flex items-center justify-center mb-4">
                    <div class="w-16 h-16 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-muted leading-relaxed text-center">
                    آیا از پایان و ارسال آزمون اطمینان دارید؟<br>
                    پس از ارسال، امکان تغییر تصاویر وجود نخواهد داشت.
                </p>
                <div class="mt-4 bg-background/50 rounded-xl p-3 text-center">
                    <p class="text-xs text-muted">تعداد تصاویر ارسالی:</p>
                    <p class="font-bold text-primary text-lg">{{ $this->attempt->uploads->count() }}</p>
                </div>
            </div>

            <div class="flex items-center gap-3 border-t border-border p-4">
                <button type="button"
                        class="flex-1 px-4 py-3 rounded-xl border border-border bg-background text-sm text-foreground hover:bg-secondary transition-colors font-semibold"
                        @click="showSubmitModal = false">
                    انصراف
                </button>
                <button type="button"
                        class="flex-1 px-4 py-3 rounded-xl bg-primary hover:bg-primary/90 text-primary-foreground text-sm font-semibold transition-colors"
                        @click="$wire.submitExam(); showSubmitModal = false">
                    بله، ارسال نهایی
                </button>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         مودال تأیید حذف (bottom-sheet موبایل، centered دسکتاپ)
       ════════════════════════════════════════ --}}
    <div x-show="confirmDelete !== null" x-cloak
         class="fixed inset-0 z-50 flex flex-col justify-end sm:items-center sm:justify-center"
         x-transition.opacity>
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="confirmDelete = null"></div>

        <div class="relative w-full sm:max-w-sm bg-secondary border-t sm:border border-border rounded-t-3xl sm:rounded-2xl flex flex-col pb-[env(safe-area-inset-bottom,0px)] sm:pb-0 shadow-xl"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-8"
             x-transition:enter-end="opacity-100 translate-y-0">

            <div class="sm:hidden flex justify-center pt-3 pb-1 shrink-0">
                <div class="w-10 h-1 rounded-full bg-foreground/20"></div>
            </div>

            <div class="p-6 text-center">
                <div class="flex items-center justify-center mb-4">
                    <div class="w-16 h-16 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M9 7V4a1 1 0 011-1h4a1 1 0 011 1v3"/>
                        </svg>
                    </div>
                </div>
                <h2 class="font-bold text-lg text-foreground mb-2">حذف تصویر؟</h2>
                <p class="text-sm text-muted">این تصویر حذف خواهد شد و قابل بازگشت نیست.</p>
            </div>

            <div class="flex items-center gap-3 border-t border-border p-4">
                <button type="button"
                        class="flex-1 px-4 py-3 rounded-xl border border-border bg-background text-sm text-foreground hover:bg-secondary transition-colors font-semibold"
                        @click="confirmDelete = null">
                    انصراف
                </button>
                <button type="button"
                        class="flex-1 px-4 py-3 rounded-xl bg-red-500 hover:bg-red-600 text-white text-sm font-semibold transition-colors"
                        @click="$wire.deleteUpload(confirmDelete); confirmDelete = null">
                    حذف
                </button>
            </div>
        </div>
    </div>

    {{-- ════════════════════════════════════════
         مودال مشاهده تصویر
       ════════════════════════════════════════ --}}
    <div x-show="showImageModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         x-transition.opacity>
        <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="closeImage()"></div>

        <div class="relative w-full max-w-4xl max-h-[90vh] flex flex-col"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            {{-- دکمه بستن --}}
            <button type="button" @click="closeImage()"
                    class="absolute -top-2 -left-2 sm:top-2 sm:left-2 z-10 w-9 h-9 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-lg transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <img :src="viewingImageUrl"
                 class="w-full h-full object-contain rounded-2xl"
                 oncontextmenu="return false;">
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

    @script
    <script>
        function essayExamApp(config) {
            return {
                // Timer
                remaining: config.remainingSeconds,
                showTimer: false,

                // PDF
                zoom: 1,
                pdfBaseUrl: config.pdfUrl,

                // Security
                securityActive: false,

                // Modals
                showSubmitModal: false,
                showImageModal: false,
                viewingImageUrl: '',
                confirmDelete: null,

                // Upload
                stagedPreviews: [],
                isUploading: false,
                uploadProgress: 0,

                get pdfSrc() {
                    if (!this.pdfBaseUrl) return '';
                    const zoomVal = Math.round(this.zoom * 100);
                    return `${this.pdfBaseUrl}#toolbar=0&navpanes=0&scrollbar=1&zoom=${zoomVal}`;
                },

                init() {
                    this.startTimer();
                    this.setupSecurity();

                    // پاک کردن پیشنمایش‌ها بعد از موفقیت آپلود
                    this.$wire.on('photos-uploaded', () => {
                        this.clearStaged();
                        this.isUploading = false;
                        this.uploadProgress = 0;
                    });
                },

                // ─── Timer ───
                startTimer() {
                    if (window._essayTimer) clearInterval(window._essayTimer);
                    window._essayTimer = setInterval(() => {
                        if (this.remaining > 0) {
                            this.remaining--;
                        } else {
                            clearInterval(window._essayTimer);
                            this.$wire.submitExam();
                        }
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

                // ─── Zoom ───
                zoomIn() {
                    this.zoom = Math.min(2, Math.round((this.zoom + 0.25) * 100) / 100);
                },
                zoomOut() {
                    this.zoom = Math.max(0.5, Math.round((this.zoom - 0.25) * 100) / 100);
                },
                resetZoom() {
                    this.zoom = 1;
                },

                // ─── Security ───
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
                            // Mac: Cmd+Shift+3,4,5
                            if ((e.metaKey || e.ctrlKey) && e.shiftKey) {
                                if (['3', '4', '5', 'S', 's'].includes(e.key)) {
                                    trigger();
                                }
                            }
                        },
                        print: trigger,
                    };

                    document.addEventListener('visibilitychange', window._essaySecurity.vis);
                    window.addEventListener('blur', window._essaySecurity.blur);
                    document.addEventListener('keydown', window._essaySecurity.key);
                    window.addEventListener('beforeprint', window._essaySecurity.print);
                },

                // ─── File Upload ───
                onFileSelect(event) {
                    const files = Array.from(event.target.files || []);
                    this.clearStaged();
                    this.stagedPreviews = files.map(f => ({
                        name: f.name,
                        url: URL.createObjectURL(f),
                    }));
                    this.isUploading = true;
                    this.uploadProgress = 0;
                },

                onUploadFinish() {
                    // فایل‌ها در temp storage آماده‌اند؛ پردازش سرور را شروع کن
                    this.$wire.uploadPhotos();
                },

                clearStaged() {
                    this.stagedPreviews.forEach(p => URL.revokeObjectURL(p.url));
                    this.stagedPreviews = [];
                    if (this.$refs.fileInput) {
                        this.$refs.fileInput.value = '';
                    }
                },

                // ─── Image Modal ───
                viewImage(url) {
                    this.viewingImageUrl = url;
                    this.showImageModal = true;
                },
                closeImage() {
                    this.showImageModal = false;
                    this.viewingImageUrl = '';
                },
            };
        }
    </script>
    @endscript

</div>
