<div class="max-w-3xl mx-auto px-4 py-6 sm:py-12" dir="rtl">

    @if (session()->has('info'))
        <div class="alert alert-info mb-4">{{ session('info') }}</div>
    @endif
    @if (session()->has('success'))
        <div class="alert alert-success mb-4">{{ session('success') }}</div>
    @endif

    @php
        $percent = $totalQuestions > 0 ? (int) round(($answeredTotal / $totalQuestions) * 100) : 0;
    @endphp

    @if ($isAllDone)
        {{-- ═══════════ صفحهٔ تشکر ═══════════ --}}
        <div class="bg-base-100 border border-base-300 rounded-3xl p-8 sm:p-12 text-center shadow-sm">
            <div class="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-success/15 text-success">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-base-content mb-3">از تو ممنونیم! 🌱</h1>
            <p class="text-sm text-base-content/70 leading-7 mb-8">
                هر دو مرحلهٔ آزمون‌ها (MBTI و مایندست) با موفقیت تکمیل شد.
                نتایج تو ثبت شد و حالا می‌توانیم ادامهٔ مسیر هفتهٔ آزمایشی و ساخت برنامهٔ اختصاصی‌ات را شروع کنیم.
            </p>
            <button wire:click="continueToGuide" class="btn btn-primary btn-wide">
                ادامه می‌دهیم
            </button>
        </div>
    @else
        {{-- ═══════════ صفحهٔ خوش‌آمد / ادامه ═══════════ --}}
        <div class="bg-base-100 border border-base-300 rounded-3xl p-8 sm:p-12 text-center shadow-sm">

            {{-- نشانگر دو مرحله --}}
            <div class="flex items-center justify-center gap-3 mb-8">
                <div class="flex items-center gap-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold
                        {{ $currentStage >= 1 ? 'bg-primary text-primary-content' : 'bg-base-300 text-base-content/60' }}">۱</span>
                    <span class="text-sm {{ $currentStage == 1 ? 'font-bold text-primary' : 'text-base-content/60' }}">MBTI</span>
                </div>
                <span class="h-px w-10 bg-base-300"></span>
                <div class="flex items-center gap-2">
                    <span class="flex h-9 w-9 items-center justify-center rounded-full text-sm font-bold
                        {{ $currentStage >= 2 ? 'bg-primary text-primary-content' : 'bg-base-300 text-base-content/60' }}">۲</span>
                    <span class="text-sm {{ $currentStage == 2 ? 'font-bold text-primary' : 'text-base-content/60' }}">مایندست</span>
                </div>
            </div>

            <p class="text-xs font-medium text-primary mb-2">
                مرحلهٔ {{ $currentStage == 1 ? 'یک — شخصیت‌شناسی MBTI' : 'دو — مایندست' }}
            </p>

            @if ($hasStarted)
                <h1 class="text-2xl font-bold text-base-content mb-3">خوش برگشتی! 👋</h1>
                <p class="text-sm text-base-content/70 leading-7 mb-8">
                    از همان‌جایی که رها کردی ادامه می‌دهیم؛ مستقیم می‌روی سراغ سوال بعدی که هنوز پاسخ نداده‌ای.
                </p>
            @else
                <h1 class="text-2xl font-bold text-base-content mb-3">خوش اومدی! 👋</h1>
                <p class="text-sm text-base-content/70 leading-7 mb-8">
                    برای طراحی برنامهٔ اختصاصی‌ات، ابتدا آزمون شخصیت‌شناسی را در دو مرحله انجام می‌دهیم.
                    سوال‌ها یکی‌یکی نمایش داده می‌شوند و در هر زمان می‌توانی ادامه دهی.
                </p>
            @endif

            {{-- پیشرفت کلی --}}
            <div class="bg-base-200 rounded-2xl p-4 mb-8 text-right">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-medium">پیشرفت کلی</span>
                    <span class="text-sm font-bold text-primary">{{ $answeredTotal }} / {{ $totalQuestions }}</span>
                </div>
                <div class="w-full bg-base-300 rounded-full h-3 overflow-hidden">
                    <div class="bg-primary h-3 transition-all duration-500" style="width: {{ $percent }}%"></div>
                </div>
            </div>

            <button wire:click="start" class="btn btn-primary btn-wide">
                {{ $hasStarted ? 'ادامهٔ آزمون' : 'شروع آزمون' }}
            </button>
        </div>
    @endif
</div>
