<div class="max-w-2xl mx-auto px-4 py-6 sm:py-10" dir="rtl">

    <div class="bg-base-100 border border-base-300 rounded-2xl p-6 sm:p-8 shadow-sm">

        @if ($status['is_first_entry'])
            <h1 class="text-xl sm:text-2xl font-bold mb-3">
                خوش اومدی{{ $userName ? '، ' . $userName : '' }} 👋
            </h1>
            <p class="text-base leading-8 text-base-content/80 mb-4">
                به مرحله‌ی <strong>{{ $title }}</strong> رسیدی.
            </p>
            <p class="text-sm leading-7 text-base-content/70 mb-6">
                {{ $description }}
            </p>
        @elseif ($status['percent'] >= 100)
            <h1 class="text-xl sm:text-2xl font-bold mb-3">
                آفرین! 🎉
            </h1>
            <p class="text-base leading-8 text-base-content/80 mb-4">
                مرحله‌ی قبلی با موفقیت تمام شد. حالا بریم سراغ <strong>{{ $title }}</strong>.
            </p>
            <p class="text-sm leading-7 text-base-content/70 mb-6">
                {{ $description }}
            </p>
        @else
            <h1 class="text-xl sm:text-2xl font-bold mb-3">
                خوش برگشتی{{ $userName ? '، ' . $userName : '' }} 👋
            </h1>
            <p class="text-base leading-8 text-base-content/80 mb-4">
                مرحله‌ی <strong>{{ $title }}</strong> هنوز ادامه دارد.
                تا الان به <strong>{{ $status['answered'] }}</strong> سوال از
                <strong>{{ $status['total_questions'] }}</strong> سوال این مرحله پاسخ دادی.
            </p>
            <p class="text-sm leading-7 text-base-content/70 mb-6">
                از همان سوالی که جا گذاشتی ادامه می‌دهی.
            </p>
        @endif

        <div class="mb-7">
            <div class="flex items-center justify-between text-xs mb-2">
                <span class="text-base-content/70">پیشرفت این مرحله</span>
                <span class="font-bold text-primary">{{ $status['percent'] }}%</span>
            </div>
            <div class="w-full bg-base-300 rounded-full h-2 overflow-hidden">
                <div class="bg-primary h-2 transition-all duration-500"
                     style="width: {{ $status['percent'] }}%"></div>
            </div>
            <p class="text-xs text-base-content/50 mt-2 text-left">
                {{ $status['answered'] }} / {{ $status['total_questions'] }} سوال
            </p>
        </div>

        <button wire:click="proceed" class="btn btn-primary w-full">
            @if ($status['is_first_entry'])
                شروع پاسخ‌گویی
            @else
                ادامه‌ی پاسخ‌گویی
            @endif
        </button>

        <p class="text-xs text-base-content/50 mt-4 text-center leading-6">
            پاسخ‌های شما خودکار ذخیره می‌شوند؛ هر وقت بخواهی می‌توانی صفحه را ببندی و بعداً ادامه دهی.
        </p>
    </div>
</div>
