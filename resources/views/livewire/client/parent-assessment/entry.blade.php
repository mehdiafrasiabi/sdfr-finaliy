<div class="min-h-screen flex items-center justify-center px-4 py-10" dir="rtl">
    <div class="max-w-xl w-full bg-base-100 border border-base-300 rounded-2xl shadow-lg p-6 sm:p-8">
        @if ($expired)
            <div class="text-center">
                <div class="text-warning text-5xl mb-4">⚠</div>
                <h1 class="text-xl font-bold mb-2">لینک منقضی شده است</h1>
                <p class="text-sm text-base-content/70 leading-7 mb-4">
                    این لینک دیگر معتبر نیست. لطفاً با همکاران ما تماس بگیرید تا لینک تازه برای شما ارسال شود.
                </p>
                <p class="text-xs text-base-content/50">کد ارجاع: {{ \Illuminate\Support\Str::limit($token, 8, '...') }}</p>
            </div>
        @else
            <h1 class="text-xl sm:text-2xl font-bold mb-3">
                {{ $invitation->parent_role_label }} گرامی،
            </h1>
            <p class="text-base leading-8 text-base-content/80 mb-4">
                فرزند شما <strong>{{ $invitation->user->name }}</strong> تست‌های روان‌شناختی خود را در سیستم ما تکمیل کرده است.
                برای آنکه بتوانیم برنامه‌ی متناسب و دقیق‌تری برای ایشان طراحی کنیم، دیدگاه شما به‌عنوان والد نیز برای ما ارزشمند است.
            </p>
            <p class="text-sm text-base-content/70 leading-7 mb-6">
                تکمیل این تست‌ها حدود ۵ تا ۸ دقیقه زمان می‌برد. پاسخ‌های شما صرفاً به مشاور تحصیلی فرزندتان برای طراحی برنامه ارائه می‌شود و کاملاً محرمانه باقی می‌ماند.
            </p>

            <div class="bg-base-200 rounded-xl p-4 mb-6 text-sm leading-7">
                <p><strong>چه چیزهایی می‌پرسیم؟</strong></p>
                <ul class="list-disc pr-5 mt-2 text-base-content/80">
                    <li>دیدگاه شما درباره‌ی ارتباط فرزندتان با درس و نمره</li>
                    <li>دیدگاه شما درباره‌ی دوستان و محیط اجتماعی فرزندتان</li>
                </ul>
            </div>

            <button wire:click="proceed" class="btn btn-primary w-full">
                شروع تست‌ها
            </button>

            <p class="text-xs text-base-content/50 mt-4 text-center">
                اعتبار این لینک تا {{ optional($invitation->expires_at)->format('Y/m/d') }} است.
            </p>
        @endif
    </div>
</div>
