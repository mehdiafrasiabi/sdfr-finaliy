<div class="min-h-screen flex items-center justify-center px-4 py-10" dir="rtl">
    <div class="max-w-xl w-full bg-base-100 border border-base-300 rounded-2xl shadow-lg p-6 sm:p-8">

        @if ($expired)
            <div class="text-center">
                <div class="text-warning text-5xl mb-4">⚠</div>
                <h1 class="text-xl font-bold mb-2">لینک منقضی شده است</h1>
                <p class="text-sm text-base-content/70 leading-7">
                    این لینک دیگر معتبر نیست. لطفاً با همکاران ما تماس بگیرید تا لینک تازه برای شما ارسال شود.
                </p>
            </div>
        @else
            @if ($isFirstEntry)
                <h1 class="text-xl sm:text-2xl font-bold mb-3">
                    {{ $invitation->parent_role_label }} گرامی، خوش آمدید 👋
                </h1>
                <p class="text-base leading-8 text-base-content/80 mb-3">
                    فرزند شما <strong>{{ $invitation->user->name }}</strong> تست‌های روان‌شناختی خود را در سیستم ما تکمیل کرده است.
                </p>
                <p class="text-sm leading-7 text-base-content/70 mb-5">
                    دیدگاه شما به‌عنوان والد برای ما ارزشمند است. این تست‌ها حدود ۵ تا ۸ دقیقه زمان می‌برد.
                </p>
            @else
                <h1 class="text-xl sm:text-2xl font-bold mb-3">
                    خوش برگشتید 👋
                </h1>
                <p class="text-base leading-8 text-base-content/80 mb-3">
                    تا الان به <strong>{{ $answered }}</strong> سوال از
                    <strong>{{ $totalQuestions }}</strong> سوال پاسخ داده‌اید.
                    از همانجا که جا گذاشتید ادامه خواهید داد.
                </p>
            @endif

            <div class="mb-6">
                <div class="flex items-center justify-between text-xs mb-2">
                    <span class="text-base-content/70">پیشرفت شما</span>
                    <span class="font-bold text-primary">{{ $percent }}%</span>
                </div>
                <div class="w-full bg-base-300 rounded-full h-2 overflow-hidden">
                    <div class="bg-primary h-2 transition-all duration-500"
                         style="width: {{ $percent }}%"></div>
                </div>
                <p class="text-xs text-base-content/50 mt-2 text-left">
                    {{ $answered }} / {{ $totalQuestions }} سوال
                </p>
            </div>

            <button wire:click="proceed" class="btn btn-primary w-full">
                @if ($isFirstEntry)
                    شروع تست‌ها
                @else
                    ادامه‌ی تست‌ها
                @endif
            </button>

            <p class="text-xs text-base-content/50 mt-4 text-center leading-6">
                پاسخ‌های شما خودکار ذخیره می‌شوند.
                @if ($invitation && $invitation->expires_at)
                    اعتبار این لینک تا {{ $invitation->expires_at->format('Y/m/d') }} است.
                @endif
            </p>
        @endif
    </div>
</div>
