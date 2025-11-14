@push('link')
    <style>
        /* ====== Glassmorphism & Animations ====== */
        .glass-bg {
            background: rgba(255, 255, 255, 0.75);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1);
        }

        .dark .glass-bg {
            background: rgba(20, 25, 40, 0.8);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.3);
        }

        /* Score Cards Animation */
        .score-card {
            animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
            transition: all 0.3s ease;
        }

        .score-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }

        .dark .score-card:hover {
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.3);
        }

        /* Question Item Animation */
        .question-item {
            animation: fadeInLeft 0.5s cubic-bezier(0.34, 1.56, 0.64, 1);
            transition: all 0.25s ease;
        }

        .question-item:hover {
            transform: translateX(-4px);
        }

        /* Status Badges */
        .badge-correct {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            animation: badgePop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .badge-incorrect {
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            animation: badgePop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .badge-unanswered {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            animation: badgePop 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes badgePop {
            0% {
                transform: scale(0.5) rotate(-180deg);
                opacity: 0;
            }
            100% {
                transform: scale(1) rotate(0deg);
                opacity: 1;
            }
        }

        @keyframes successPulse {
            0%, 100% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
        }

        .success-checkmark {
            animation: successPulse 0.6s ease-out;
        }

        /* Upload Area */
        .upload-area {
            transition: all 0.3s ease;
            border: 2px dashed rgba(59, 130, 246, 0.3);
        }

        .upload-area:hover,
        .upload-area.dragover {
            border-color: rgba(59, 130, 246, 0.8);
            background: rgba(59, 130, 246, 0.05);
        }

        .dark .upload-area:hover,
        .dark .upload-area.dragover {
            background: rgba(59, 130, 246, 0.08);
        }

        /* Progress Bar */
        .progress-bar {
            transition: width 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, #3b82f6 0%, #1e40af 100%);
            border-radius: 10px;
        }

        .dark .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        /* Layout */
        .result-container {
            direction: rtl;
        }

        /* Perfect Score Celebration */
        .perfect-score {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            animation: slideUp 0.8s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        .confetti {
            position: fixed;
            pointer-events: none;
        }

        /* Question Status Indicator */
        .status-indicator {
            width: 3px;
            height: 100%;
        }

        .status-correct {
            background: linear-gradient(180deg, #10b981 0%, #059669 100%);
        }

        .status-incorrect {
            background: linear-gradient(180deg, #ef4444 0%, #dc2626 100%);
        }

        .status-unanswered {
            background: linear-gradient(180deg, #f59e0b 0%, #d97706 100%);
        }

        /* Answer Section */
        .answer-section {
            border-left: 4px solid transparent;
            padding: 1rem;
            border-radius: 0.5rem;
            transition: all 0.25s ease;
        }

        .answer-section.correct {
            border-left-color: #10b981;
            background: rgba(16, 185, 129, 0.05);
        }

        .dark .answer-section.correct {
            background: rgba(16, 185, 129, 0.08);
        }

        .answer-section.incorrect {
            border-left-color: #ef4444;
            background: rgba(239, 68, 68, 0.05);
        }

        .dark .answer-section.incorrect {
            background: rgba(239, 68, 68, 0.08);
        }

        .answer-section.unanswered {
            border-left-color: #f59e0b;
            background: rgba(245, 158, 11, 0.05);
        }

        .dark .answer-section.unanswered {
            background: rgba(245, 158, 11, 0.08);
        }

        /* New Styles for Layout Changes */
        .percentage-display {
            font-size: 4rem;
            font-weight: 900;
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .dark .percentage-display {
            background: linear-gradient(135deg, #60a5fa 0%, #3b82f6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .performance-message {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.1) 0%, rgba(5, 150, 105, 0.1) 100%);
            border: 1px solid rgba(16, 185, 129, 0.3);
        }

        .dark .performance-message {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.2) 0%, rgba(5, 150, 105, 0.2) 100%);
            border: 1px solid rgba(16, 185, 129, 0.4);
        }

        .stats-box {
            transition: all 0.3s ease;
        }
        .text-slate-800 {
            color: oklch(97.9% 0.021 166.113);



        }

        .stats-box:hover {
            transform: translateY(-5px);
        }
    </style>
@endpush

<div class="result-container min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-slate-100 dark:from-slate-950 dark:via-blue-950 dark:to-slate-950 py-8">
    <div class="max-w-7xl mx-auto px-4">

        <!-- ====== Back Button ====== -->
        <div class="mb-6">
            <a href="{{route('client.profile.exam.list')}}" wire:navigate
                    class="flex items-center gap-2 px-4 py-2 rounded-lg glass-bg border text-slate-800 border-slate-200 dark:border-slate-700 hover:shadow-lg transition-all text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                </svg>
                بازگشت
            </a>
        </div>
<br>
        <!-- ====== Header ====== -->
        <div class="mb-8">
            <div class="flex items-center gap-3 mb-6">
                <div class="flex items-center gap-1">
                    <div class="w-1 h-1 bg-blue-500 rounded-full"></div>
                    <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                </div>
                <h1 class="font-black text-3xl md:text-4xl text-transparent bg-clip-text bg-gradient-to-l from-blue-600 to-blue-400">
                    <span class="text-white">نتایج آزمون: {{ $exam->title }}</span>
                </h1>
            </div>

            <!-- ====== Percentage Display ====== -->
            <div class="text-center mb-8">
                <div class="percentage-display ">
                    {{ $percentage }}
                </div>
                <p class=" text-slate-600 font-black text-3xl dark:text-white font-semibold">درصد کل آزمون</p>
            </div>

            <!-- ====== Performance Message ====== -->
            @if($correctCount === $exam->number_of_questions && $unansweredCount === 0)
                <div class="performance-message rounded-2xl p-6 mb-8 text-center">
                    <div class="success-checkmark text-6xl mb-4">🎉</div>
                    <h2 class="text-2xl font-black text-white mb-2">تبریک! عملکرد فوق‌العاده‌ای داشتید!</h2>
                    <p class="text-green-700 dark:text-green-400">شما با پاسخ دادن به تمام سوالات، درجه تعالی را کسب کردید!</p>
                </div>
            @elseif($correctCount > $incorrectCount)
                <div class="performance-message rounded-2xl p-6 mb-8">
                    <div class="flex items-center gap-4">
                        <div class="text-5xl">👏</div>
                        <div>
                            <h3 class="font-bold text-xl text-green-500 dark:text-green-300">عملکرد خوبی داشتید!</h3>
                            <p class="text-green-500 dark:text-green-400">تعداد پاسخ‌های صحیح شما بیشتر از نادرست است.</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- ====== Stats Boxes ====== -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-10">
                <!-- Correct Answers Box -->
                <div class="stats-box glass-bg rounded-2xl p-6 border border-green-200 dark:border-green-900 text-center">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-green-400 to-green-600 flex items-center justify-center text-white text-2xl mx-auto mb-4">
                        ✔
                    </div>
                    <h3 class="font-bold text-lg text-green-500 dark:text-green-300 mb-2">پاسخ صحیح</h3>
                    <p class="text-3xl font-black text-green-500 dark:text-green-400">{{ $correctCount }}</p>
                </div>

                <!-- Incorrect Answers Box -->
                <div class="stats-box glass-bg rounded-2xl p-6 border border-red-200 dark:border-red-900 text-center">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-red-400 to-red-600 flex items-center justify-center text-white text-2xl mx-auto mb-4">
                        ✕
                    </div>
                    <h3 class="font-bold text-lg text-red-500 dark:text-red-300 mb-2">پاسخ نادرست</h3>
                    <p class="text-3xl font-black text-red-500 dark:text-red-400">{{ $incorrectCount }}</p>
                </div>

                <!-- Unanswered Box -->
                <div class="stats-box glass-bg rounded-2xl p-6 border border-amber-200 dark:border-amber-900 text-center">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-amber-400 to-amber-600 flex items-center justify-center text-white text-2xl mx-auto mb-4">
                        -
                    </div>
                    <h3 class="font-bold text-lg text-yellow-500 dark:text-amber-300 mb-2">بی‌پاسخ</h3>
                    <p class="text-3xl font-black text-yellow-500 dark:text-amber-400">{{ $unansweredCount }}</p>
                </div>
            </div>
        </div>

        <!-- ====== PDF Links ====== -->
        @if($examPdf || $solutionPdf)
            <div class="grid grid-cols-2 md:grid-cols-2 gap-2 mb-5">
                @if($examPdf)
                    <a href="{{ asset($examPdf) }}" download
                       class="glass-bg rounded-2xl p-6 border border-blue-200 dark:border-blue-900 hover:shadow-lg transition-all flex items-center justify-between group">
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-slate-200 mb-1">📄 دفترچه آزمون</h4>
                            <p class="text-sm text-white dark:text-slate-400">مشاهده سوالات اصلی</p>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-primary dark:text-primary group-hover:translate-x-1 transition-transform">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                    </a>
                @endif
                @if($solutionPdf)
                    <a href="{{ asset($solutionPdf) }}" download
                       class="glass-bg rounded-2xl p-6 border border-green-200 dark:border-green-900 hover:shadow-lg transition-all flex items-center justify-between group">
                        <div>
                            <h4 class="font-bold text-slate-800 dark:text-slate-200 mb-1">✅ پاسخنامه تشریحی</h4>
                            <p class="text-sm text-white dark:text-slate-400">مشاهده پاسخ‌ها و توضیحات</p>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-success dark:text-success group-hover:translate-x-1 transition-transform">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                        </svg>
                    </a>
                @endif
            </div>
        @endif

        <!-- ====== Analysis Upload Section ====== -->
        <div class="glass-bg rounded-2xl border border-blue-200 dark:border-blue-900 p-6 mb-5">
            <h3 class="font-bold text-xl text-slate-800 dark:text-slate-200 mb-6 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-blue-600 dark:text-blue-400">
                    <path d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.69h13.5v-2.69a.75.75 0 0 1 1.5 0v2.69A2.25 2.25 0 0 1 15.75 21H3.75A2.25 2.25 0 0 1 1.5 18.75v-2.69a.75.75 0 0 1 .75-.75Z" />
                </svg>
                آپلود تحلیل آزمون
            </h3>

            @if($analysisStatus === 'sent' && !$uploadSuccess)
                <!-- ✅ Successfully Uploaded -->
                <div class="text-center py-8">
                    <div class="success-checkmark text-6xl mb-4">✅</div>
                    <h4 class="font-bold text-xl text-green-500 dark:text-green-400 mb-2">تحلیل آپلود شد!</h4>
                    <p class="text-slate-800 dark:text-slate-400 mb-6">تحلیل شما برای مشاور ارسال شده است.</p>

                    <!-- Display Uploaded Images -->
                    @if(!empty($analysisImagePath))
                        <div x-data="{ open: false, active: 0 }" class="space-y-3">
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($analysisImagePath as $idx => $img)
                                    @php
                                        if(is_array($img)) {
                                            $img = $img['path'] ?? array_values($img)[0] ?? '';
                                        }
                                        $url = Str::startsWith($img, ['http://','https://']) ? $img : asset($img);
                                    @endphp
                                    <button type="button"
                                            x-on:click="active = {{ $idx }}; open = true"
                                            class="block overflow-hidden rounded-lg border border-slate-200 dark:border-slate-700 hover:shadow-md transition-all hover:scale-105 text-slate-800">
                                        <img src="{{ $url }}" alt="analysis-{{ $idx }}" class="w-full h-20 object-cover">
                                    </button>
                                @endforeach
                            </div>

                            <!-- Image Modal -->
                            <div x-show="open" x-on:keydown.escape.window="open = false" x-cloak
                                 class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/70">
                                <div class="max-w-2xl w-full">
                                    <div class="relative bg-white dark:bg-slate-900 rounded-2xl overflow-hidden">
                                        <button x-on:click="open = false" class="absolute top-4 right-4 z-50 p-2 text-slate-800 rounded-full bg-white dark:bg-slate-800 shadow-lg">
                                            ✕
                                        </button>

                                        <template x-if="active !== null">
                                            <div class="w-full h-[70vh] flex items-center justify-center bg-black">
                                                @php
                                                    $urls = collect($analysisImagePath)->map(function($img){
                                                        if(is_array($img)) $img = $img['path'] ?? array_values($img)[0] ?? '';
                                                        return Str::startsWith($img, ['http://','https://']) ? $img : asset($img);
                                                    })->values()->all();
                                                @endphp
                                                <img :src=" {{ json_encode($urls) }}[active] "
                                                     alt="big"
                                                     class="max-h-[68vh] max-w-full object-contain">
                                            </div>
                                        </template>

                                        <!-- Navigation -->
                                        <div class="flex items-center justify-between p-4 bg-slate-100 dark:bg-slate-800">
                                            <button x-on:click="active = (active === 0 ? ({{ count($urls) }} - 1) : active - 1)"
                                                    class="px-3 py-1 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition-colors">
                                                ⬅ قبلی
                                            </button>
                                            <span class="text-sm text-slate-600 dark:text-slate-400 font-semibold">
                                                <span x-text="(active === null ? 0 : active + 1)"></span> / {{ count($urls) }}
                                            </span>
                                            <button x-on:click="active = (active === ({{ count($urls) }} - 1) ? 0 : active + 1)"
                                                    class="px-3 py-1 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold transition-colors">
                                                بعدی ➡
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

            @elseif($analysisStatus === 'expired')
                <!-- ❌ Expired -->
                <div class="text-center py-8 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-200 dark:border-red-900">
                    <div class="text-4xl mb-3">⏰</div>
                    <h4 class="font-bold text-xl text-red-500 dark:text-red-400 mb-2">مهلت تمام شد</h4>
                    <p class="text-slate-800 dark:text-slate-400">شما نمی‌توانید پس از 48 ساعت از تکمیل آزمون، تحلیل آپلود کنید.</p>
                </div>

            @else
                <!-- Upload Form -->
                <form wire:submit="uploadAnalysis">
                    <div class="space-y-4">
                        <!-- Upload Area -->
                        <label for="analysis-upload"
                               class="upload-area glass-bg rounded-xl p-8 cursor-pointer flex flex-col items-center justify-center gap-3 transition-all"
                               x-data="{ dragover: false }"
                               x-on:dragover.prevent="dragover = true"
                               x-on:dragleave="dragover = false"
                               x-on:drop.prevent="dragover = false; $el.querySelector('input').click()">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-12 h-12 text-blue-600 dark:text-blue-400">
                                <path d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.69h13.5v-2.69a.75.75 0 0 1 1.5 0v2.69A2.25 2.25 0 0 1 15.75 21H3.75A2.25 2.25 0 0 1 1.5 18.75v-2.69a.75.75 0 0 1 .75-.75Z" />
                            </svg>
                            <div class="text-center">
                                <p class="font-bold text-slate-800 dark:text-slate-200">فایل را بکشید یا انتخاب کنید</p>
                                <p class="text-xs text-slate-600 dark:text-slate-400 mt-1">PNG, JPG, JPEG تا 5MB</p>
                            </div>
                            <input type="file" id="analysis-upload" multiple class="hidden"
                                   wire:model="analysisPhoto"
                                   accept="image/png,image/jpeg,image/jpg">
                        </label>
                        @error('analysisPhoto')
                        <p class="text-red-600 dark:text-red-400 text-sm font-semibold">⚠️ {{ $message }}</p>
                        @enderror
                        @error('analysisPhoto.*')
                        <p class="text-red-600 dark:text-red-400 text-sm font-semibold">⚠️ {{ $message }}</p>
                        @enderror

                        <!-- Preview Thumbnails -->
                        @if(!empty($analysisPhoto))
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($analysisPhoto as $photo)
                                    @if($photo instanceof \Livewire\TemporaryUploadedFile)
                                        <div class="relative rounded-lg overflow-hidden border-2 border-blue-300 dark:border-blue-700">
                                            <img src="{{ $photo->temporaryUrl() }}" alt="preview" class="w-full h-20 object-cover">
                                            <div class="absolute inset-0 bg-blue-500/20 flex items-center justify-center">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-blue-600">
                                                    <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 1 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.208Z" clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif

                        <!-- Submit Button -->
                        <button type="submit"
                                @disabled(empty($analysisPhoto))
                                class="w-full py-3 px-4 rounded-xl font-bold text-white transition-all disabled:opacity-50 disabled:cursor-not-allowed
                                    bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 shadow-md hover:shadow-lg">
                            <div wire:loading.remove class="flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                    <path d="M12 2.25a.75.75 0 0 1 .75.75v11.69l3.22-3.22a.75.75 0 1 1 1.06 1.06l-4.5 4.5a.75.75 0 0 1-1.06 0l-4.5-4.5a.75.75 0 1 1 1.06-1.06l3.22 3.22V3a.75.75 0 0 1 .75-.75Zm-9 13.5a.75.75 0 0 1 .75.75v2.69h13.5v-2.69a.75.75 0 0 1 1.5 0v2.69A2.25 2.25 0 0 1 15.75 21H3.75A2.25 2.25 0 0 1 1.5 18.75v-2.69a.75.75 0 0 1 .75-.75Z" />
                                </svg>
                                آپلود تحلیل
                            </div>
                            <div wire:loading class="flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid" width="20" height="20">
                                    <circle cx="50" cy="50" r="40" stroke="rgba(255,255,255,0.3)" stroke-width="4" fill="none"></circle>
                                    <circle cx="50" cy="50" r="40" stroke="white" stroke-width="4" fill="none" stroke-dasharray="62.83" stroke-linecap="round">
                                        <animateTransform attributeName="transform" type="rotate" from="0 50 50" to="360 50 50" dur="0.8s" repeatCount="indefinite"></animateTransform>
                                    </circle>
                                </svg>
                                در حال آپلود...
                            </div>
                        </button>
                    </div>
                </form>
            @endif
        </div>

        <!-- ====== Info Box ====== -->
        <div class="glass-bg rounded-2xl p-4 border border-slate-200 dark:border-slate-700 mb-5">
            <p class="text-sm text-slate-600 dark:text-slate-400 leading-relaxed">
                <span class="font-bold text-slate-800 dark:text-slate-200">💡 نکته:</span>
                <span class="text-rose-500">  تحلیل خود را در غضون 48 ساعت بعد از پایان آزمون آپلود کنید تا معلم بتواند آن را بررسی کند.</span>
            </p>
        </div>

        <!-- ====== Questions & Answers Section ====== -->
        <div class="glass-bg rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
            <h2 class="text-2xl font-bold text-slate-800 dark:text-slate-200 mb-6 flex items-center gap-2">
                📝 سوالات و پاسخ‌های آزمون
            </h2>
<br>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 ">
                @foreach($this->allQuestions() as $q)
                    <div class="question-item rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden mb-2 " style="margin-right: 3px">
                        <div class="flex">
                            <!-- Status Indicator -->
                            <div class="status-indicator
                        @if($q['status'] === 'correct')
                            status-correct
                        @elseif($q['status'] === 'incorrect')
                            status-incorrect
                        @else
                            status-unanswered
                        @endif"></div>

                            <!-- Content -->
                            <div class="flex-1 p-4">
                                <!-- Question Header -->
                                <div class="flex items-start justify-between gap-4 mb-3">
                                    <div class="flex items-center gap-3">
                                        <h3 class="font-bold text-lg text-slate-800 dark:text-slate-200">سوال {{ $q['number'] }}</h3>
                                    </div>
                                    <div>
                                        @if($q['status'] === 'correct')
                                            <span class="badge-correct inline-flex items-center gap-1 rounded-full px-3 py-1 text-white text-xs font-bold shadow-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M19.916 4.626a.75.75 0 0 1 .208 1.04l-9 13.5a.75.75 0 0 1-1.154.114l-6-6a.75.75 0 1 1 1.06-1.06l5.353 5.353 8.493-12.74a.75.75 0 0 1 1.04-.208Z" clip-rule="evenodd" />
                                        </svg>
                                        پاسخ صحیح
                                    </span>
                                        @elseif($q['status'] === 'incorrect')
                                            <span class="badge-incorrect inline-flex items-center gap-1 rounded-full px-3 py-1 text-white text-xs font-bold shadow-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M5.47 5.47a.75.75 0 0 1 1.06 0L12 10.94l5.47-5.47a.75.75 0 1 1 1.06 1.06L13.06 12l5.47 5.47a.75.75 0 1 1-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 0 1-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                                        </svg>
                                        پاسخ نادرست
                                    </span>
                                        @else
                                            <span class="badge-unanswered inline-flex items-center gap-1 rounded-full px-3 py-1 text-white text-xs font-bold shadow-md">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                            <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM12 4.5a.75.75 0 0 1 .75.75v5.25h5.25a.75.75 0 0 1 0 1.5H12.75V18a.75.75 0 0 1-1.5 0V7.5H6a.75.75 0 0 1 0-1.5h5.25V5.25a.75.75 0 0 1 .75-.75Z" clip-rule="evenodd" />
                                        </svg>
                                        بی‌پاسخ
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Answer Section -->
                                <div class="answer-section
                            @if($q['status'] === 'correct')
                                correct
                            @elseif($q['status'] === 'incorrect')
                                incorrect
                            @else
                                unanswered
                            @endif">
                                    <div class="space-y-3">
                                        <!-- Student's Answer -->
                                        <div>
                                            <p class="text-xs font-semibold text-slate-800 dark:text-slate-800 mb-1">پاسخ شما:</p>
                                            <div class="text-xl font-bold
                                        @if($q['status'] === 'correct')
                                            text-green-500 dark:text-green-500
                                        @elseif($q['status'] === 'incorrect')
                                            text-red-500 dark:text-red-500
                                        @else
                                            text-yellow-500 dark:text-yellow-500
                                        @endif">
                                                @if($q['student_answer'])
                                                    گزینه <span class="font-black">{{ $q['student_answer'] }}</span>
                                                @else
                                                    <span class="text-base">بدون پاسخ</span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Correct Answer -->
                                        <div>
                                            <p class="text-xs font-semibold text-slate-800 dark:text-slate-800 mb-1">پاسخ صحیح:</p>
                                            <div class="text-xl font-bold text-green-500 dark:text-green-500">
                                                گزینه <span class="font-black">{{ $q['correct_option'] ?? 'نامشخص' }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    @if($q['description'])
                                        <div class="mt-3 pt-3 border-t border-slate-200 dark:border-slate-700">
                                            <p class="text-xs font-semibold text-slate-600 dark:text-slate-400 mb-1">📚 توضیح:</p>
                                            <p class="text-sm text-slate-800 dark:text-slate-200 leading-relaxed">{{ $q['description'] }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>


    </div>
</div>
