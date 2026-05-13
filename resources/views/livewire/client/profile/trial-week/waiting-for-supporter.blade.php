<div dir="rtl" class="min-h-screen flex items-center justify-center bg-gradient-to-br from-indigo-50 to-blue-50 dark:from-slate-900 dark:to-slate-800 px-4">
    <div class="max-w-xl w-full bg-white dark:bg-slate-900 rounded-3xl shadow-xl p-8 md:p-12 text-center">
        <div class="w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white text-5xl animate-pulse">
            ⌛
        </div>
        <h1 class="text-2xl md:text-3xl font-extrabold text-slate-800 dark:text-white mb-3">
            در انتظار تخصیص پشتیبان
        </h1>
        <p class="text-slate-600 dark:text-slate-300 leading-loose mb-6">
            ثبت‌نام شما با موفقیت انجام شد. به‌زودی یک <strong>پشتیبان جذب</strong> اختصاصی به شما معرفی می‌شود
            و با شما تماس خواهد گرفت. تا آن زمان دسترسی به سایر بخش‌های پروفایل فعال نیست.
        </p>

        @if ($trial)
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-3">
                    <div class="text-slate-500">پایه</div>
                    <div class="font-bold mt-1">{{ $trial->grade_label }}</div>
                </div>
                <div class="bg-slate-50 dark:bg-slate-800 rounded-xl p-3">
                    <div class="text-slate-500">وضعیت</div>
                    <div class="font-bold mt-1">{{ $trial->status_label }}</div>
                </div>
            </div>
        @endif

        <button wire:click="$refresh" class="mt-8 px-6 py-3 rounded-xl bg-indigo-600 text-white font-bold hover:bg-indigo-700 transition-all">
            بررسی مجدد
        </button>
    </div>
</div>
