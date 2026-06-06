<div class="min-h-screen flex items-center justify-center px-4 py-10" dir="rtl">
    <div class="max-w-xl w-full bg-base-100 border border-base-300 rounded-2xl shadow-lg p-6 sm:p-8 text-center">
        <div class="text-success text-6xl mb-4">✓</div>
        <h1 class="text-2xl font-bold mb-3">ممنون از همراهی شما</h1>
        <p class="text-sm leading-7 text-base-content/80 mb-4">
            پاسخ‌های شما با موفقیت ثبت شد. این اطلاعات به مشاور تحصیلی فرزندتان منتقل خواهد شد تا بتواند برنامه‌ی دقیق‌تری برای او طراحی کند.
        </p>
        @if ($invitation && $invitation->user)
            <p class="text-xs text-base-content/60">
                {{ $invitation->parent_role_label }} گرامی {{ $invitation->user->name }} — به‌روزرسانی نهایی بعد از مشاوره به شما اطلاع داده می‌شود.
            </p>
        @endif
    </div>
</div>
